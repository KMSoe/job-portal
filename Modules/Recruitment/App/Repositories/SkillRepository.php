<?php
namespace Modules\Recruitment\App\Repositories;

use Illuminate\Support\Str;
use Modules\Recruitment\Entities\Skill;

class SkillRepository
{
    public function findByParams($request)
    {
        $keyword = $request->search ? $request->search : '';
        $perPage = $request->perPage ? $request->perPage : 20;

        $data = Skill::where('is_active', true)
            ->where(function ($query) use ($request, $keyword) {
                if ($keyword != '') {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('name', 'LIKE', "%$keyword%")
                            ->orWhere('description', 'LIKE', "%$keyword%");
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

        $data = $data->paginate($perPage);

        return $data;
    }

    public function findById($id)
    {
        $skill = Skill::findOrFail($id);

        return $skill;
    }

    public function store($data)
    {
        $data['created_by'] = auth()->id();

        $skill = Skill::create($data);

        return $skill;
    }

    public function update($id, $data)
    {
        $data['updated_by'] = auth()->id();

        $skill = Skill::findOrFail($id);
        return $skill->update($data);
    }

    public function delete($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();
    }
}
