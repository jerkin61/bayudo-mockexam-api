<?php

namespace Jerquin\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Jerquin\Database\Models\Profile;
use Jerquin\Database\Models\Question;
use Jerquin\Database\Repositories\QuestionRepository;
use Jerquin\Http\Requests\InvoiceCreateRequest;
use Jerquin\Http\Requests\InvoiceUpdateRequest;
use Jerquin\Http\Requests\ProductUpdateRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class QuestionController extends CoreController
{
    public $repository;

    public function __construct(QuestionRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Question
     */
    public function index(Request $request)
  {
    $limit = $request->limit ? $request->limit : 100000;
    $columnsToSelect = ['name', 'sale_price', 'id', 'sku', 'wholesale_price', 'unit', 'quantity', 'tax', 'status'];

    $query = $this->repository->select('*');
    $results = $query->paginate($limit)->withQueryString()->toArray();
    return $results;
}

    // public function update(InvoiceUpdateRequest $request, $id)
    // {
    //     try {
    //         $validatedData = $request->validated();  
    //      if ($validatedData['status'] == 'completed') {
    //             try {
    //                 // Decode the JSON product data
    //                 $products = json_decode($request->product_list, true);
    //                 // Check if $products is an array
                        
    //          if (is_array($products)) {
    //                 foreach ($products as $productData) {
    //                     $itemCount =  $productData['unitCategory']['itemCount'];
    //                     $product = \Jerquin\Database\Models\Product::where('sku', $productData['sku'])->first();
    //                     // Check if the product exists
    //                     if ($product) {
    //                         $calcQuantity = ($itemCount * $productData['quantity']) / $productData['stack_size'];
    //                        $product->decrement('quantity', $calcQuantity);
    //                         $product->save();
    //                     }
    //                 }
    //             } else {
    //                     // Handle invalid or unexpected JSON format
    //                     throw new \Exception('Invalid or unexpected JSON format for product_list.');
    //                 }

    //             } catch (\Exception $e) {
    //                 // Handle the exception
    //                 return response()->json(['error' => $e->getMessage()], 500);
    //             }
    //         }
    //     // return 'test';
    //         return $this->repository->findOrFail($id)->update($validatedData);
    //     } catch (\Throwable $th) {
	// 	return response()->json(['error' => $th->getMessage()], 500);
            
    //     }
    // }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            return $this->repository->findOrFail($id);
        } catch (\Exception $e) {
            // throw new ChatbotException('ERROR.NOT_FOUND');
        }
    }
//     public function store(InvoiceCreateRequest $request)
//     {
      
//         $validatedData = $request->validated();  
//         $existingProduct = Invoice::where('invoice_number', $validatedData['invoice_number'])->first();

//         if ($existingProduct) {
//             return response()->json(['error' => 'SKU already exists'], 422);
//         }
//         return $this->repository->create($validatedData);
//     }
    public function destroy($id)
    {
        try {
            return $this->repository->findOrFail($id)->delete();
        } catch (\Exception $e) {
            throw new JerquinException('ERROR.NOT_FOUND');
        }
    }  

}
 