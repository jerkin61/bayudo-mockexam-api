<?php

namespace Jerquin\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Jerquin\Database\Models\Profile;
use Jerquin\Database\Models\QuestionFeedback;
use Jerquin\Database\Repositories\QuestionFeedbackRepository;
use Jerquin\Http\Requests\QuestionFeedbackUpdateRequest;
use Jerquin\Http\Requests\QuestionFeedbackCreateRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class QuestionFeedbackController extends CoreController
{
    public $repository;

    public function __construct(QuestionFeedbackRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|QuestionFeedback
     */
    public function index(Request $request)
  {
    $limit = $request->limit ? $request->limit : 100000;
    $query = $this->repository->select('*');  
    $results = $query->paginate($limit)->withQueryString()->toArray();
    return $results;
}

     public function update(QuestionFeedbackUpdateRequest $request, $id)
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
    public function showPerQuestionFeedback($questionId)
    {
        try {
            return $this->repository->where('question_id', $questionId)->firstOrFail();
        } catch (\Exception $e) {
            // throw new ChatbotException('ERROR.NOT_FOUND');
        }
    }
    public function store(QuestionFeedbackCreateRequest $request)
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
 