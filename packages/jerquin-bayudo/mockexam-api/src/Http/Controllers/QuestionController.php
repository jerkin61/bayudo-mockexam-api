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
    $query = $this->repository->select('*');  
    if ($request->has('question_id')) {
        $query->where('exam_category_id', $request->question_id);
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
            return $this->repository->findOrFail($id);
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

}
 