<?php


namespace Jerquin\Database\Repositories;


use Jerquin\Database\Models\MessageLogs;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;



class MessageLogsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'message'        => 'like',
        'reciever',
        'sender',
        'created_at',
    ];

    public function boot()
    {
        try {
            $this->pushCriteria(app(RequestCriteria::class));
        } catch (RepositoryException $e) {
        }
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return MessageLogs::class;
    }
}
