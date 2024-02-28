<?php

namespace Jerquin\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Jerquin\Database\Models\Profile;
use Jerquin\Database\Repositories\ShopRepository;
use Jerquin\Http\Requests\ShopCreateRequest;

class ShopController extends CoreController
{
    public $repository;

    public function __construct(ShopRepository $repository)
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
        return $this->repository->with(['products'])->paginate($limit);
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
            return $this->repository->with(['products'])->findOrFail($id);
        } catch (\Exception $e) {
            // throw new ChatbotException('ERROR.NOT_FOUND');
        }
    }
    public function store(ShopCreateRequest $request)
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
 