<?php

namespace Jerquin\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Jerquin\Database\Models\Profile;
use Jerquin\Database\Models\Question;
use Jerquin\Database\Repositories\QuestionRepository;
use Jerquin\Http\Requests\QuestionUpdateRequest;
use Jerquin\Http\Requests\QuestionCreateRequest;
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
    $query = $this->repository->with('examCategory')->select('*');  
    if ($request->has('question_id')) {
        $query->where('exam_category_id', $request->question_id);
    }
    if ($request->has('random')) {
        $query->inRandomOrder(); // Add random order if random is set to true
    }
    $results = $query->paginate($limit)->withQueryString()->toArray();
    return $results;
}

     public function update(QuestionUpdateRequest $request, $id)
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
            return $this->repository->with('examCategory')->findOrFail($id);
        } catch (\Exception $e) {
            // throw new ChatbotException('ERROR.NOT_FOUND');
        }
    }
    public function store(QuestionCreateRequest $request)
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
        private function arrayToCsv(array $data): string
    {
        $output = fopen('php://temp', 'w');
        fputcsv($output, array_keys($data[0])); 

        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        return $csv;
    }  
	  public function exportQuestions(Request $request){
        try {
			
        $query = Question::select('question_no', 'question', 'answer', 'choices', 'exam_category_id', 'time_left', 'time', 'answered','right_ans','explanation');
        if ($request->has('id')) {
            $query = $query->where('exam_category_id', $request->id);
        }

        $data = $query->get();
            $transformedData = $data->map(function ($item) {
        // Decode the JSON in the 'choices' column
            $choices = json_decode($item->choices, true);

        // Extract choices into separate columns
                foreach ($choices as $choice) {
                    $key = $choice['key']; // e.g., 'a', 'b', 'c', 'd'
                    $value = strip_tags($choice['value']); // Removes HTML tags and decodes HTML entities
                    $item->{$key} = $value; // Dynamically add properties to the item
                }

                unset($item->choices); // Optionally remove the original 'choices' column
                return $item;
            });
            // return $transformedData;
            // Convert the transformed data back to an array for CSV export
            $csvData = $transformedData->toArray();
                    $fileName = 'exported_questions.csv';
            $csv = $this->arrayToCsv($csvData);
            return Response::make($csv, 200);
        } catch (\Throwable $th) {
		return response()->json(['error' => $th->getMessage()], 500);
        }
    }



    public function importQuestions(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $fileData = $request->file; 
// $csvContent = file_get_contents($fileData->getRealPath());
        $csvData = array_map('str_getcsv', explode("\n", urldecode($fileData)));

        if (empty($csvData)) {
            return response()->json(['errors' => ['CSV data is empty']], 422);
        }

        $headers = array_shift($csvData); // Extract headers
        // return $headers;
        try { 
            \DB::beginTransaction();
        foreach ($csvData as $row) {  
            if (count($row) == count($headers)) { // Ensure row data matches header count
                $data = array_combine($headers, $row);

                // Process choice keys and convert them into a JSON string
                $choices = [];
        foreach (range('a', 'j') as $choiceKey) { // Extend choices up to 'j'
            if (isset($data[$choiceKey]) && $data[$choiceKey] !== '') { // Check if value is not empty
                $choices[] = [
                    'key' => $choiceKey,
                    'value' => $data[$choiceKey]
                ];
                unset($data[$choiceKey]); // Remove the individual choice from data
            }
        }
        if (!empty($choices)) { // Check if any choices were added
            $data['choices'] = json_encode($choices); // Add the choices back as a JSON string
            unset($data['a'], $data['b'], $data['c'], $data['d'], $data['e'], $data['f'], $data['g'], $data['h'], $data['i'], $data['j']); // Remove individual choice keys from data
        } else {
            unset($data['a'], $data['b'], $data['c'], $data['d'], $data['e'], $data['f'], $data['g'], $data['h'], $data['i'], $data['j']); // Remove all choice keys from data if none were added
        }

        unset($data['time']);
        // Remove unwanted fields if they exist
        unset($data['id'], $data['created_at'], $data['deleted_at']);
   
        // Insert the data into the database
        Question::create($data);
    }
}

    \DB::commit();
    return response()->json(['success' => 'Data imported successfully']);
        } catch  (\Exception $e) {
            \DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['success' => true, 'message' => 'Successfully imported chat'], 200);
    }
}
 