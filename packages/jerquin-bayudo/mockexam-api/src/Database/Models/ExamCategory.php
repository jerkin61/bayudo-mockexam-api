<?php

namespace Jerquin\Database\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExamCategory extends Model
{
    use Sluggable;

    protected $table = 'examcategory';

    public $guarded = [];

    protected $casts = [
 
    ];
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'category_name'
            ]
        ];
    }

      public function examList()
    {
        return $this->hasOne(ExamList::class,'id', 'exam_id');
    }

}
