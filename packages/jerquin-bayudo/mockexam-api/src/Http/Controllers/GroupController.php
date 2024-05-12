<?php

namespace Jerquin\Http\Controllers;

use Jerquin\Enums\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Jerquin\Database\Models\Group;
use Jerquin\Database\Repositories\GroupRepository;
use Jerquin\Http\Requests\GroupCreateRequest;
use Jerquin\Http\Requests\GroupUpdateRequest;

class GroupController extends CoreController
{
    public $repository;

    protected $dataArray = [
            'group_code', // Each exam must have a group_code which is a string
            'limitCount' , // Each exam must have a limitCount which is a string
            'school', // Each member must have a school which is a string
            'user_id', // Each exam must have a user ID which is an integer
    ];
    public function __construct(GroupRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Group
     */
    public function index(Request $request)
    {    $user = auth()->user();
        $limit = $request->limit ? $request->limit : 100000;
        $query = $this->repository->with(['exams', 'members.examCategoryTaken', 'user']);
      
        if ($user->hasPermissionTo(Permission::ADMIN ) && !$user->hasPermissionTo(Permission::SUPER_ADMIN )) {
            $query = $query->where('user_id', $user->id);
        }
        if (isset($request['group_code']) && $request['group_code'] != 'undefined') {
            $groupId = $request->group_code;
            $group = $query->where('group_code', $groupId)->first();

        if ($group) {
            $newMembers = [];
            foreach ($group->members as $member) {
                $exam = $member->examCategoryTaken->first(function ($exam) use ($request) {
                    return $exam->exam_category_id == $request->exam_category_id;
                });

                $examCategoryTaken = $exam ? [
                    'answered' => $exam->answered,
                    'number_of_items' => $exam->number_of_items,
                ] : null;

                $newMembers[] = [
                    'user' => $member->name,
                    'exam_category_taken' => $examCategoryTaken,
                ];
            }
            usort($newMembers, function($a, $b) {
                // Compare based on 'answered' attribute
                if ($a['exam_category_taken'] === null && $b['exam_category_taken'] === null) {
                    return 0;
                }
                if ($a['exam_category_taken'] === null) {
                    return 1; // $a is considered greater
                }
                if ($b['exam_category_taken'] === null) {
                    return -1; // $b is considered greater
                }
                return $b['exam_category_taken']['answered'] - $a['exam_category_taken']['answered'];
            });
            return response()->json($newMembers);
            } else {
                return response()->json(['message' => 'Group not found'], 404);
            }
        }    
            // Execute the query with pagination
            return $query->paginate($limit);
        }


    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            return $this->repository->with(['exams', 'members','user'])->findOrFail($id);
        } catch (\Exception $e) {
            // throw new ChatbotException('ERROR.NOT_FOUND');
        }
    }
      /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function showByGroup(Request $request, $groupId)
    {
        try {
            $user = auth()->user();
        
            $query = $this->repository->with(['exams', 'members.examCategoryTaken','user'])->where('group_code', $groupId);
            if ($user->hasPermissionTo(Permission::ADMIN ) && !$user->hasPermissionTo(Permission::SUPER_ADMIN )) {
            $query = $query->where('user_id', $user->id)->firstOrFail();
              }

            if($query){
            $newMembers = [];
            foreach ($query->members as $member) {
                // if($member->name == 'staff@gmail.com') return $member;
                $exam = $member->examCategoryTaken->first(function ($exam) use ($request) {
                    return $exam->exam_category_id == $request->exam_category_id;
                });

                $examCategoryTaken = $exam ? [
                    'answered' => $exam->answered,
                    'number_of_items' => $exam->number_of_items,
                    'percentage' =>  ($exam->answered / $exam->number_of_items) * 100
                ] : null;

                $newMembers[] = [
                    'user' => $member->name,
                    'exam_category_taken' => $examCategoryTaken,
                    // 'answered' => $examCategoryTaken->answered,
                    // 'number_of_items' => $examCategoryTaken->number_of_items
                ];
            }
            usort($newMembers, function($a, $b) {
                // Compare based on 'answered' attribute
                if ($a['exam_category_taken'] === null && $b['exam_category_taken'] === null) {
                    return 0;
                }
                if ($a['exam_category_taken'] === null) {
                    return 1; // $a is considered greater
                }
                if ($b['exam_category_taken'] === null) {
                    return -1; // $b is considered greater
                }
                return $b['exam_category_taken']['answered'] - $a['exam_category_taken']['answered'];
            });
            
            return response()->json($newMembers);
            }
        // return $query;
        } catch (\Exception $e) {
            // throw new ChatbotException('ERROR.NOT_FOUND');
        }
    }

    public function store(GroupCreateRequest $request)
    {
        $data = $request->only($this->dataArray);
        $group = $this->repository->create($data);  
        if (isset($request['exams'])) {
                $group->exams()->sync($request['exams']);
            }
        if (isset($request['members'])) {
                $group->members()->sync($request['members']);
            }
        return  $group;
    }
    public function update(GroupUpdateRequest $request, $id)
    {
            $group = $this->repository->findOrFail($id);
            $data = $request->only($this->dataArray);
            $group->update($data);
            if (isset($request['exams'])) {
                        $group->exams()->sync($request['exams']);
                    }
            if (isset($request['members'])) {
                        $group->members()->sync($request['members']);
            }
            return $group ;
    }
    
    public function destroy($id)
    {
        try {
            return $this->repository->findOrFail($id)->delete();
        } catch (\Exception $e) {
            throw new JerquinException('ERROR.NOT_FOUND');
        }
    }  

}
