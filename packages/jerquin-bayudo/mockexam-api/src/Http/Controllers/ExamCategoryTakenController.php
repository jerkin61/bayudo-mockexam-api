<?php

namespace Jerquin\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Jerquin\Database\Models\Profile;
use Jerquin\Database\Models\ExamCategoryTaken;
use Jerquin\Database\Models\AnswerExams;
use Jerquin\Database\Models\Question;
use Jerquin\Database\Repositories\ExamCategoryTakenRepository;
use Jerquin\Http\Requests\ExamCategoryTakenUpdateRequest;
use Jerquin\Http\Requests\ExamCategoryTakenCreateRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ExamCategoryTakenController extends CoreController
{
    public $repository;

    public function __construct(ExamCategoryTakenRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|ExamCategoryTaken
     */
    public function index(Request $request)
  {
    $limit = $request->limit ? $request->limit : 100000;
    $columnsToSelect = ['name', 'sale_price', 'id', 'sku', 'wholesale_price', 'unit', 'quantity', 'tax', 'status'];

    $query = $this->repository->with(['examCategory'])->select('*');
    $results = $query->paginate($limit)->withQueryString()->toArray();
    return $results;
}
    public function update(ExamCategoryTakenUpdateRequest $request, $id)
    {
        try {
            $categorytoFind = ExamCategoryTaken::find($id)->exam_category_id;
           $questionCount = Question::where('exam_category_id', $categorytoFind)->count();
           $answerExams = AnswerExams::where('exam_taken_category_id', $id)->get();
            $correctCount = $answerExams->filter(function ($answerExam) {
                return $answerExam->correct === 1;
            })->count();

            $examPercentage = ($questionCount > 0) ? ($correctCount / $questionCount) * 100 : 0; // Multiply by 100 to get percentage
         
            $validatedData = $request->validated();
            $validatedData['exam_result'] = $correctCount;
            $validatedData['answered'] = $correctCount;
            $validatedData['exam_percentage'] = $examPercentage;
            $validatedData['number_of_items'] = $questionCount;

            $repository = $this->repository->findOrFail($id);
            // return $correctCount;
            $repository->update($validatedData);
            // You can return a response or perform further actions as needed
            return response()->json(['message' => 'Update successful']);
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
    public function show($id, Request $request)
    {
        try {
                 return $this->repository->with(['examTaken'])->findOrFail($id);
        //    return $this->repository->where('exam_category_id', $id)->with(['examTaken'])->firstOrFail();
        } catch (\Exception $e) {
            // throw new ChatbotException('ERROR.NOT_FOUND');
        }
    }
    public function showByExamCategoryId($id, Request $request)
    {
        try {
                //  return $this->repository->with(['examTaken'])->findOrFail($id);
      $query = $this->repository->where('exam_category_id', $id);

    if (isset($request->completed)) {
        $query = $query->where('completed', $request->completed);
    }

    $result = $query->with(['examTaken'])->get();
    return $result;
        } catch (\Exception $e) {
            // throw new ChatbotException('ERROR.NOT_FOUND');
        }
    }
    public function store(ExamCategoryTakenCreateRequest $request)
    {
      
        $validatedData = $request->validated();  
         $userId = auth()->user()->id;
         $validatedData['user_id'] = $userId;
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
 