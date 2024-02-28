<?php


namespace Jerquin\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Jerquin\Database\Models\Category;
use Jerquin\Database\Repositories\CategoryRepository;
use Jerquin\Exceptions\JerquinException;
use Jerquin\Http\Requests\CategoryCreateRequest;
use Jerquin\Http\Requests\CategoryUpdateRequest;
use Prettus\Validator\Exceptions\ValidatorException;


class CategoryController extends CoreController
{
    public $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Category[]
     */
    public function fetchOnlyParent(Request $request)
    {
        $limit = $request->limit ?   $request->limit : 15; 
         $isActive = $request->input('is_active', null);
        $query =  $this->repository->with(['parent'])->orderBy('name')->where('parent', null);
            if ($isActive === 'true') {
        $query = $query->where('is_active', true);
    }
    return $query->paginate($limit);
    }


    public function fetchOnlyChildren(Request $request)
    {
        $limit = $request->limit ?   $request->limit : 15;
        $category = $this->repository->find($request->id);
        return $category->children()->with('children')->orderBy('name')->paginate($limit);
    }

     public function index(Request $request)
    {
        $limit = $request->limit ?   $request->limit : 15;
        return $this->repository->with([ 'parent'])->paginate($limit);
    }
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Category[]
     */
    public function store(CategoryCreateRequest $request)
    {
        $validatedData = $request->validated();
        return $this->repository->create($validatedData);
    }


    public function update(CategoryUpdateRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            return $this->repository->findOrFail($id)->update($validatedData);
        } catch (\Exception $e) {
            throw new JerquinException('NOT_FOUND');
            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            return $this->repository->with(['parent'])->findOrFail($id)->delete();
        } catch (\Exception $e) {
            throw new JerquinException('ERROR.NOT_FOUND');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            return $this->repository->with(['parent'])->findOrFail($id);
        } catch (\Exception $e) {
            throw new JerquinException('ERROR.NOT_FOUND');
        }
    }

}
