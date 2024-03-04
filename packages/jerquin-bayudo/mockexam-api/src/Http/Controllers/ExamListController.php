<?php

namespace Jerquin\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Jerquin\Database\Models\Profile;
use Jerquin\Database\Models\ExamList;
use Jerquin\Database\Repositories\ExamListRepository;
use Jerquin\Http\Requests\InvoiceCreateRequest;
use Jerquin\Http\Requests\InvoiceUpdateRequest;
use Jerquin\Http\Requests\ProductUpdateRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ExamListController extends CoreController
{
    public $repository;

    public function __construct(ExamListRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|ExamList
     */
    public function index(Request $request)
  {
    $limit = $request->limit ? $request->limit : 100000;
    $columnsToSelect = ['name', 'sale_price', 'id', 'sku', 'wholesale_price', 'unit', 'quantity', 'tax', 'status'];

    $query = $this->repository->with('examCategory')->select('*');
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
//     public function destroy($id)
//     {
//         try {
//             return $this->repository->findOrFail($id)->delete();
//         } catch (\Exception $e) {
//             throw new JerquinException('ERROR.NOT_FOUND');
//         }
//     }  

//         private function arrayToCsv(array $data): string
//     {
//         $output = fopen('php://temp', 'w');
//         fputcsv($output, array_keys($data[0])); 

//         foreach ($data as $row) {
//             fputcsv($output, $row);
//         }

//         rewind($output);
//         $csv = stream_get_contents($output);
//         fclose($output);
//         return $csv;
//     }  
// 	  public function exportProducts(Request $request){
//         try {
			
//             $data = Product::select('name', 'sale_price', 'id', 'sku', 'wholesale_price', 'unit', 'quantity', 'tax')->get();
//             $csvData = $data->toArray();
//             $fileName = 'exported_products.csv';
//             $csv = $this->arrayToCsv($csvData);
//             return Response::make($csv, 200);
//         } catch (\Throwable $th) {
// 		return response()->json(['error' => $th->getMessage()], 500);
//         }
//     }
//     public function importProducts(Request $request)
// {   
//     $validator = Validator::make($request->all(), [
//         'file' => 'required',
//     ]);

//     if ($validator->fails()) {
//         return response()->json(['error' => $validator->errors()], 400);
//     }

//     $fileData = $request->file; 
//     $csvData = array_map('str_getcsv', explode("\n", urldecode($fileData)));

//     if (empty($csvData)) {
//         return response()->json(['errors' => ['CSV data is empty']], 422);
//     }

//     $headers = array_shift($csvData);
//     // return $csvData;
//     try { 
//         \DB::beginTransaction();
//         foreach ($csvData as $row) {  
// 			// return $headers;
//             $data = array_combine($headers, $row);

//             // Remove unwanted fields
//             unset($data['id']);
//             unset($data['created_at']);
//             unset($data['deleted_at']);
//             unset($data['sale_price']);

//             // Insert the data into the database
//             Product::create($data);
//         }
//         \DB::commit();
//     } catch  (\Exception $e) {
//         \DB::rollback();
//         return response()->json(['error' => $e->getMessage()], 500);
//     }

//     return response()->json(['success' => true, 'message' => 'Successfully imported chat'], 200);
// }

}
 