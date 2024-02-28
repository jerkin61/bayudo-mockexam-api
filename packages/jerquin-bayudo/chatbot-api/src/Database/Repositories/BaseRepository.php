<?php


namespace Jerquin\Database\Repositories;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;
use Prettus\Repository\Eloquent\BaseRepository as Repository;

abstract class BaseRepository extends Repository implements CacheableInterface
{
    use CacheableRepository;

}
