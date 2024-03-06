<?php

namespace Jerquin\Database\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Jerquin\Database\Models\ExamCategory;

class ExamList extends Model
{

    protected $table = 'examlist';

    public $guarded = [];

    protected $casts = [
 
    ];


     public function examCategory()
    {
        return $this->hasMany(ExamCategory::class, 'exam_id', 'id');
    }

}
