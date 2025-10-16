<?php
namespace Modules\Organization\App\Repositories;

use App\Models\User;
use Google\Service\Batch\Job;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Organization\Entities\Employee;
use Modules\Organization\Transformers\EmployeeResource;
use Modules\Recruitment\Entities\ChecklistTemplate;
use Modules\Recruitment\Entities\JobOffer;
use Modules\Recruitment\Entities\OnboardingChecklistItem;
use Modules\Recruitment\Transformers\OnboardingChecklistItemResource;
use Modules\Storage\App\Classes\LocalStorage;
use Modules\Storage\App\Interfaces\StorageInterface;

class EmployeeRepository
{
    private StorageInterface $storage;

    public function __construct(LocalStorage $storage)
    {
        $this->storage = $storage;
    }

    public function findByParams($request)
    {
        $keyword = $request->search ? $request->search : '';
        $perPage = $request->per_page ? $request->per_page : 20;

        $data = Employee::with([
                    'company',
                    'department',
                    'designation',
                    'salaryCurrency',
                    'onboardingChecklistTemplate',
                    'onboardingChecklistItems',
                    'createdBy'
                ])
                ->where(function ($query) use ($request, $keyword) {
                    if ($request->company_id) {
                        $query->where('company_id', $request->company_id);
                    }

                    if ($request->department_id) {
                        $query->where('department_id', $request->department_id);
                    }

                    if ($request->designation_id) {
                        $query->where('designation_id', $request->designation_id);
                    }

                    if ($keyword != '') {
                        $query->where(function ($q) use ($keyword) {
                            $q->where('name', 'LIKE', "%$keyword%")
                            ->orWhere('preferred_name', 'LIKE', "%$keyword%")
                            ->orWhere('email', 'LIKE', "%$keyword%")
                            ->orWhere('work_mail', 'LIKE', "%$keyword%");
                        });
                    }
                });

            if ($request->sort != null && $request->sort != '') {
                $sorts = explode(',', $request->input('sort', ''));

                foreach ($sorts as $sortColumn) {
                    $sortDirection = Str::startsWith($sortColumn, '-') ? 'DESC' : 'ASC';
                    $sortColumn    = ltrim($sortColumn, '-');

                    $data->orderBy($sortColumn, $sortDirection);
                }
            } else {
                $data->orderBy('created_at', 'DESC');
            }

            if ($request->export) {
                $items = isset($request->only_this_page) && $request->only_this_page == 1
                    ? $data->skip(($request->page - 1) * $perPage)->take($perPage)->get()
                    : $data->get();

                return EmployeeResource::collection($items);
            } else {
                $data = $data->paginate($perPage);

                $items = $data->getCollection();

                $items = collect($items)->map(function ($item) {
                    return new EmployeeResource($item);
                });

                $data = $data->setCollection($items);
            }

        return $data;
    }

    public function findById($id)
    {
        $department = Employee::with([
            'company',
            'department',
            'designation',
            'salaryCurrency',
            'onboardingChecklistTemplate',
            'onboardingChecklistItems',
            'createdBy'
        ])->findOrFail($id);

        return $department;
    }

    public function store($data)
    {
        $user = User::create([
            'name'          => $data['name'],
            'email'         => $data['email'],
            'password'      => Hash::make($data['password']),
        ]);

        $data['user_id'] = $user->id;
        $data['created_by'] = auth()->id();
        $data['user_id'] = $user->id;
        $employee = Employee::create($data);

        if (isset($data['onboarding_checklist_template_id']) && $data['onboarding_checklist_template_id'] != 0) {
            $this->createChecklistItems($data['onboarding_checklist_template_id'], $employee->id);
        }

        if (isset($data['inform_to_departments'])) 
        {
            $department_ids = is_array($data['inform_to_departments']) ? $data['inform_to_departments'] : explode(',', $data['inform_to_departments']);

            $employee->informToDepartments()->sync($department_ids);

            $logoFile = $this->storage->getFile($employee->company?->logo);
            
            if ($department_ids && count($department_ids) > 0) {
                $noti_employees = Employee::whereIn('department_id', $department_ids)
                    ->whereNotNull('email')
                    ->where('email', '!=', '')
                    ->pluck('email')
                    ->toArray();

                if (!empty($noti_employees)) {
                    Mail::send('recruitment::emails.newemployeeonboarded', [
                        'employee' => $employee, 
                        'logoFile' => $logoFile
                    ], function($message) use($noti_employees) {
                        $message->to($noti_employees);
                        $message->subject('New Employee Onboarded');
                    });
                }
            }
        }

        $user->update(['employee_id' => $employee->id]);

        return $employee;
    }

    public function createChecklistItems($template_id, $employee_id)
    {
        $template = ChecklistTemplate::find($template_id);
        if ($template) {
            $items = $template->items;

            foreach ($items as $item) {
                OnboardingChecklistItem::create([
                    'employee_id'                => $employee_id,
                    'checklist_template_item_id' => $item->id,
                    'status' => 'not_started'
                ]);
            }
        }
    }

    public function update($id, $data)
    {
        $data['updated_by'] = auth()->id();
        $employee = Employee::findOrFail($id);

        if (isset($data['onboarding_checklist_template_id']) && $data['onboarding_checklist_template_id'] != 0 && $employee->onboarding_checklist_template_id !== $data['onboarding_checklist_template_id']) {
            OnboardingChecklistItem::where('employee_id', $employee->id)->delete();
            $this->createChecklistItems($data['onboarding_checklist_template_id'], $employee->id);
        }

        if ($employee->user_id && (isset($data['name']) || isset($data['email']))) {
            $user = User::find($employee->user_id);
            if ($user) {
                $userData = [];

                if (isset($data['name'])) {
                    $userData['name'] = $data['name'];
                }

                if (isset($data['email'])) {
                    $userData['email'] = $data['email'];
                }

                if (! empty($userData)) {
                    $user->update($userData);
                }
            }
        }

        $employee->update($data);
        
        return $employee->fresh();
    }

    public function delete($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->informToDepartments()->detach();
        $employee->user()->delete();
        $employee->onboardingChecklistItems()->delete();
        $employee->delete();
    }

    public function getChecklistItems($employeeId, $data)
    {
        $per_page = $data['per_page'] ?? 20;

        $data = OnboardingChecklistItem::where('employee_id', $employeeId);

        $data = $data->paginate($per_page);

        $items = $data->getCollection();

        $items = collect($items)->map(function ($item) {
            return new OnboardingChecklistItemResource($item);
        });

        $data = $data->setCollection($items);

        return $data;
    }

    public function updateChecklistStatus($id, array $data)
    {
        $checklist_item = OnboardingChecklistItem::findOrFail($id);
        $checklist_item->update([
            'status' => $data['status']
        ]);
    }
}
