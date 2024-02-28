<?php


namespace Jerquin\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Jerquin\Database\Models\MessageLogs;
use Jerquin\Database\Repositories\MessageLogsRepository;
use Jerquin\Exceptions\JerquinException;
use Jerquin\Http\Requests\MessageLogsCreateRequest;
use Jerquin\Http\Requests\MessageLogsUpdateRequest;
use Prettus\Validator\Exceptions\ValidatorException;


class MessageLogsController extends CoreController
{
    public $repository;

    public function __construct(MessageLogsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Category[]
     */

    public function fetchConversation(Request $request)
    {
        $limit = $request->limit ?   $request->limit : 15;
        try {
            $messageLogs = $this->repository->where('sender', $request->sender)->where('reciever', $request->reciever)
            ->with(['sender'])->with(['reciever']);
            return $messageLogs->orderBy('created_at')->paginate($limit);
        } catch (\Exception $e) {
            throw new JerquinException('NOT_FOUND');
        }
    }

    public function index(Request $request)
    {

        $limit = $request->limit ? $request->limit : 15;
        try {
            return $this->repository->with(['sender'])->with(['reciever'])->paginate($limit);
        } catch (\Exception $e) {
            throw new JerquinException('NOT_FOUND');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Category[]
     */
    public function store(MessageLogsCreateRequest $request)
    {
        try {
            $validatedData = $request->validated();
            return $this->repository->create($validatedData);
        } catch (\Exception $e) {
            throw new JerquinException('NOT_FOUND');
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Category[]
     */

    public function update(MessageLogsUpdateRequest $request)
    {
        try {
            $validatedData = $request->validated();
            return $this->repository->findOrFail($request->id)->update($validatedData);
        } catch (\Exception $e) {
            throw new JerquinException('NOT_FOUND');
            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */

    public function destroy($id)
    {
        try {
            return $this->repository->findOrFail($id)->delete();
        } catch (\Exception $e) {
            throw new JerquinException('Not found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */

    public function show($id)
    {
        try {
            return $this->repository->with(['reciever'])->with(['sender'])->findOrFail($id);
        } catch (\Exception $e) {
            throw new JerquinException('ERROR.NOT_FOUND');
        }
    }

}
