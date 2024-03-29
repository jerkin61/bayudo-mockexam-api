<?php

namespace Jerquin\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Jerquin\Database\Models\Profile;
use Jerquin\Database\Models\AnswerExams;
use Jerquin\Database\Repositories\AnswerExamsRepository;
use Jerquin\Http\Requests\AnswerExamsUpdateRequest;
use Jerquin\Http\Requests\AnswerExamsCreateRequest;
use Jerquin\Http\Requests\ProductUpdateRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AnswerExamsController extends CoreController
{
    public $repository;

    public function __construct(AnswerExamsRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|AnswerExams
     */
    public function index(Request $request)
  {
    $limit = $request->limit ? $request->limit : 100000;
    $query = $this->repository->with(['relatedQuestion'])->select('*');  
    // if ($request->has('question_id')) {
    //     $query->where('exam_category_id', $request->question_id);
    // }
    $results = $query->paginate($limit)->withQueryString()->toArray();
    return $results;
}

     public function update(AnswerExamsUpdateRequest $request, $id)
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
    public function showRelatedQuestion($questionNo, $examCategoryTaken)
    {
        try {
          return $this->repository->where('question_no', $questionNo)->where('exam_taken_category_id', $examCategoryTaken)->firstOrFail();
        } catch (\Exception $e) {
            // throw new ChatbotException('ERROR.NOT_FOUND');
        }
    }
    public function store(AnswerExamsCreateRequest $request)
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
 