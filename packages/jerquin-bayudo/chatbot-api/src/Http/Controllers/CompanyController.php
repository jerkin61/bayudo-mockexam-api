<?php

namespace Jerquin\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Jerquin\Database\Models\Profile;
use Jerquin\Database\Repositories\CompanyRepository;
use Jerquin\Http\Requests\CompanyCreateRequest;

class CompanyController extends CoreController
{
    public $repository;

    public function __construct(CompanyRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Profile
     */
    public function index(Request $request)
    {
         $limit = $request->limit ?   $request->limit : 15;
        return $this->repository->with([ 'owner', 'shops'])->paginate($limit);
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
            return $this->repository->with('owner')->findOrFail($id);
        } catch (\Exception $e) {
            // throw new ChatbotException('ERROR.NOT_FOUND');
        }
    }
    public function store(CompanyCreateRequest $request)
    {
        $validatedData = $request->validated();
        return $this->repository->create($validatedData);
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
 