<?php

namespace Jerquin\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Jerquin\Database\Models\Profile;
use Jerquin\Database\Models\Service;
use Jerquin\Database\Repositories\ServiceRepository;
use Jerquin\Http\Requests\ProductCreateRequest;
use Jerquin\Http\Requests\ProductUpdateRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ServiceController extends CoreController
{
    public $repository;

    public function __construct(ServiceRepository $repository)
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
    $limit = $request->limit ? $request->limit : 100000;
    $columnsToSelect = ['name', 'type_of_unit', 'industry', 'basis_of_pay', 'description', 'shop_id', 'service_fee', 'status', 'duration_minutes','attachment','features', 'image', 'gallery', 'location'];

    $query = $this->repository->select($columnsToSelect);

    if ($request->has('search')) {
        $searchTerm = $request->input('search');
        $query->where('name', 'like', '%' . $searchTerm . '%');
    }

    // Use the $query variable here instead of calling select on the repository again
    return $query->paginate($limit)->withQueryString();
}

    public function store(ServiceCreateRequest $request)
    {
      
        $validatedData = $request->validated();  
        // $existingProduct = Service::where('sku', $validatedData['sku'])->first();

        // if ($existingProduct) {
        //     return response()->json(['error' => 'SKU already exists'], 422);
        // }
        return $this->repository->create($validatedData);
    }
  
}
 