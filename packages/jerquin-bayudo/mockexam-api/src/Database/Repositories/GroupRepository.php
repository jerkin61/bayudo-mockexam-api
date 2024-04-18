<?php


namespace Jerquin\Database\Repositories;

use Jerquin\Database\Models\Group;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

class GroupRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [];

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
        return Group::class;
    }
}
