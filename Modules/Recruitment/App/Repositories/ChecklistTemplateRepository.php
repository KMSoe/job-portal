<?php
namespace Modules\Recruitment\App\Repositories;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Recruitment\Entities\ChecklistTemplate;
use Modules\Recruitment\Entities\ChecklistTemplateItem;
use Modules\Recruitment\Transformers\ChecklistTemplateResource;

class ChecklistTemplateRepository
{
    /**
     * Get All Checklist Templates.
     */
    public function getAll()
    {
        return ChecklistTemplate::all();
    }

    /**
     * Get Checklist Templates With Pagination.
     */
    public function paginate($request)
    {
        $perPage = $request['per_page'] ?? 20;

        $checklists = ChecklistTemplate::query();

        // Filter by search
        if(isset($request['search'])) 
        {
            $checklists->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request['search'] . '%')
                    ->orWhereHas('items', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request['search'] . '%');
                    });
                });
        }

        // Sort With Columns
        if (isset($request['sort']) && $request['sort'] != null && $request['sort'] != '') {
            $sorts = explode(',', $request['sort']);
            foreach ($sorts as $sortColumn) {
                $sortDirection = Str::startsWith($sortColumn, '-') ? 'DESC' : 'ASC';
                $sortColumn    = ltrim($sortColumn, '-');
                $checklists->orderBy($sortColumn, $sortDirection);
            }
        } else {
            $checklists->orderBy('created_at', 'DESC');
        }

        // Handle export
        if (isset($request['export'])) {
            $items = isset($request['only_this_page']) && $request['only_this_page'] == 1
                ? $checklists->skip(($request['page'] - 1) * $perPage)->take($perPage)->get()
                : $checklists->get();

            return ChecklistTemplateResource::collection($items);
        }

        $checklists = $checklists->paginate($perPage);

        $data = $checklists->getCollection()->map(function ($item) {
            return new ChecklistTemplateResource($item);
        });
        
        return $checklists->setCollection($data);
    }

    /**
     * Get Checklist Template with id
     */
    public function get($id)
    {
        return ChecklistTemplate::findOrFail($id);
    }

    /**
     * Create Checklist Template
     */
    public function create(array $data)
    {
        $data['created_by'] = Auth::guard('api')->user()->id;
        $checklist = ChecklistTemplate::create($data);

        if (isset($data['items'])) {
            $this->createItem($checklist, $data);
        }

        return $checklist;
    }

    /**
     * Update Checklist Template
     */
    public function update($id, array $data)
    {
        $data['updated_by'] = Auth::guard('api')->user()->id;
        $checklist = ChecklistTemplate::findOrFail($id);
        $checklist->update($data);

        if (isset($data['items'])) {
            $this->createItem($checklist, $data);
        }

        return $checklist;
    }

    /**
     * Delete Checklist Template
     */
    public function delete($id)
    {
        $checklist = ChecklistTemplate::findOrFail($id);
        $checklist->items()->delete();
        $checklist->delete();
    }

    /**
     * Bulk Delete
     */
    public function bulkDelete(array $ids) {
        $checklists = ChecklistTemplate::whereIn('id', $ids)->get();
        foreach ($checklists as $checklist) {
            $checklist->items()->delete();
            $checklist->delete();
        }
    }

    public function createItem(ChecklistTemplate $checklist, array $data)
    {
        foreach ($data['items'] as $item) 
        {
            if (isset($item['id'])) {
                $existingItem = $checklist->items()->find($item['id']);
                if ($existingItem) {
                    if (!empty($item['is_delete']) && $item['is_delete'] == true) {
                        $existingItem->delete();
                    } else {
                        $existingItem->update($item);
                    }
                }
            } else {
                $checklistTemplateItem = $checklist->items()->create($item);
            }
        }
    }

    /**
     * Update Checklist Template
     */
    public function updateItem($id, array $data)
    {
        $item = ChecklistTemplateItem::findOrFail($id);
        $data['checklist_template_id'] = $item->checklist_template_id;
        $item->update($data);

        return $item;
    }

    /**
     * Delete Checklist Template
     */
    public function deleteItem($id)
    {
        $item = ChecklistTemplateItem::findOrFail($id);
        $item->delete();
    }
}
