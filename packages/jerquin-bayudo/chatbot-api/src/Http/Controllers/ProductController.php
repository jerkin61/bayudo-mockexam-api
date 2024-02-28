<?php

namespace Jerquin\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Jerquin\Database\Models\Profile;
use Jerquin\Database\Models\Product;
use Jerquin\Database\Repositories\ProductRepository;
use Jerquin\Http\Requests\ProductCreateRequest;
use Jerquin\Http\Requests\ProductUpdateRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
class ProductController extends CoreController
{
    public $repository;

    public function __construct(ProductRepository $repository)
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
        $columnsToSelect = ['name', 'sale_price', 'id', 'sku', 'wholesale_price', 'unit', 'quantity', 'tax', 'status', 'stack_size','stack_label'];

        if ($request->from === 'product-page') {
            $query = $this->repository->select('*'); 
        } else {
            $query = $this->repository->select($columnsToSelect);
        }


        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('name', 'like', '%' . $searchTerm . '%')
                ->orWhere('sku', 'like', '%' . $searchTerm . '%');
        }

        $sortableColumns = ['name', 'sale_price', 'sku', 'wholesale_price', 'unit', 'quantity', 'status'];
        $sortColumn = $request->input('sort_by');
        $sortOrder = $request->input('sort_order', 'asc'); // Default to ascending order

        if (in_array($sortColumn, $sortableColumns)) {
            $query->orderBy($sortColumn, $sortOrder);
        }

        // Use the $query variable here instead of calling select on the repository again
        return $query->paginate($limit)->withQueryString();
    }


    public function update(ProductUpdateRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            return $this->repository->findOrFail($id)->update($validatedData);
        } catch (\Throwable $th) {
		return response()->json(['error' => $th->getMessage()], 500);
            
        }
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
            return $this->repository->with(['shop'])->findOrFail($id);
        } catch (\Exception $e) {
            // throw new ChatbotException('ERROR.NOT_FOUND');
        }
    }
    public function store(ProductCreateRequest $request)
    {
      
        $validatedData = $request->validated();  
        $existingProduct = Product::where('sku', $validatedData['sku'])->first();

        if ($existingProduct) {
            return response()->json(['error' => 'SKU already exists'], 422);
        }
        if ($request->hasFile('attachment')) {
            $attachment = new Attachment();
            $attachment->save();
            $attachment->addMedia($request->file('attachment'))->toMediaCollection();
            
            // Associate the attachment with the product
            $product->attachment()->associate($attachment);
            $product->save();
        }
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

private function arrayToCsv(array $data): string
{
    $output = fopen('php://temp', 'w');
    fputcsv($output, array_keys($data[0])); 

    foreach ($data as &$row) {
        foreach ($row as $columnName => &$value) {
            if ($columnName === 'sku' && is_string($value)) {
                $value = '="' . $value . '"';
            }
        }
        fputcsv($output, $row);
    }

    rewind($output);
    $csv = stream_get_contents($output);
    fclose($output);
    return $csv;
}  
	  public function exportProducts(Request $request){
        try {
			
            $data = Product::select('name', 'sale_price','status', 'sku', 'wholesale_price', 'unit', 'quantity', 'tax','stack_size','stack_label')->get();
            $csvData = $data->toArray();
            $fileName = 'exported_products.csv';
            $csv = $this->arrayToCsv($csvData);
            return Response::make($csv, 200);
        } catch (\Throwable $th) {
		return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    public function importProducts(Request $request)
{   
    $validator = Validator::make($request->all(), [
        'file' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $fileData = $request->file; 
    $csvData = array_map('str_getcsv', explode("\n", urldecode($fileData)));

    if (empty($csvData)) {
        return response()->json(['errors' => ['CSV data is empty']], 422);
    }

    $headers = array_shift($csvData);
    // return $csvData;
    try { 
        \DB::beginTransaction();
        foreach ($csvData as $row) {  
		  $data = array_combine($headers, $row);

            // Check if SKU already exists
             if (isset($data['sku'])) {
               $data['sku'] = trim($data['sku'], "\"= \t\n\r\0\x0B");
              
                $existingProduct = Product::where('sku', $data['sku'])->whereNull('deleted_at')->first();
            //  return $existingProduct;
            if ($existingProduct) {
                    // Log the error and continue with the next iteration
                    \Log::error("SKU {$data['sku']} already exists. Skipping.");
                    continue;
                }
            }


            // Remove unwanted fields
            unset($data['id']);
            unset($data['created_at']);
            unset($data['deleted_at']);

            // Insert the data into the database
            Product::create($data);
        }
        \DB::commit();
    } catch  (\Exception $e) {
        \DB::rollback();
        return response()->json(['error' => $e->getMessage()], 500);
    }

    return response()->json(['success' => true, 'message' => 'Successfully imported chat'], 200);
}

}
 