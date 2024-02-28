<?php

namespace Jerquin\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Jerquin\Database\Models\Profile;
use Jerquin\Database\Repositories\ReportRepository;
use Jerquin\Http\Requests\CompanyCreateRequest;

class ReportController extends CoreController
{
    public $repository;

    public function __construct(ReportRepository $repository)
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
         $limit = $request->limit ?   $request->limit : 15;
        return $this->repository->with([ 'owner', 'shops'])->paginate($limit);
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
            return $this->repository->with('owner')->findOrFail($id);
        } catch (\Exception $e) {
            // throw new ChatbotException('ERROR.NOT_FOUND');
        }
    }
    public function store(CompanyCreateRequest $request)
    {
           if ($request->hasFile('attachment')) {
        // Get the uploaded file
        $attachment = $request->file('attachment');
        
        // Define the storage path and file name
        $storagePath = 'attachments/';
        $fileName = $attachment->getClientOriginalName();
        
        // Store the file in the specified location
        $attachment->storeAs($storagePath, $fileName, 'public');
        
        // Add the file path to the validated data
        $validatedData['attachment'] = $storagePath . $fileName;
    }
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
 