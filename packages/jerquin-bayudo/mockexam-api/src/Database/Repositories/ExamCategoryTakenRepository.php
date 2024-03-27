<?php


namespace Jerquin\Database\Repositories;

use Jerquin\Database\Models\ExamCategoryTaken;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

class ExamCategoryTakenRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'contact'        => 'like',
        'user.email' => 'like',
        'userr.name'  => 'like',
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
        return ExamCategoryTaken::class;
    }
}
