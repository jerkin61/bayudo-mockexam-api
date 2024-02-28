<?php


namespace Jerquin\Database\Repositories;


use Jerquin\Database\Models\Attachment;

class AttachmentRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Attachment::class;
    }
}
