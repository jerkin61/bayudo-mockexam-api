<?php

namespace Jerquin\Http\Controllers;

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
    { 
        $limit = $request->limit ? $request->limit : 100000;
        return $this->repository->with(['exams', 'members','user'])->paginate($limit);
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
