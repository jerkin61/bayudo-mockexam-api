<?php

namespace Jerquin\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Jerquin\Database\Models\Profile;
use Jerquin\Database\Models\User;
use Jerquin\Database\Models\BlogItem;
use Jerquin\Database\Repositories\BlogItemRepository;
use Jerquin\Http\Requests\BlogItemCreateRequest;
use Jerquin\Http\Requests\BlogItemUpdateRequest;
use Jerquin\Http\Requests\ProductUpdateRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Jerquin\Enums\Permission;

class BlogItemController extends CoreController
{
    public $repository;

    public function __construct(BlogItemRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|BlogItem
     */
    public function index(Request $request)
  {
    $limit = $request->limit ? $request->limit : 100000;
    $query = $this->repository->select('*');
    $results = $query->paginate($limit)->withQueryString()->toArray();
    return $results;
}

    public function update(BlogItemUpdateRequest $request, $id)
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
            return $this->repository->findOrFail($id);
        } catch (\Exception $e) {
            // throw new ChatbotException('ERROR.NOT_FOUND');
        }
    }
 
    public function store(BlogItemCreateRequest $request)
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

//         public function arrayToCsv(array $data): string
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
 