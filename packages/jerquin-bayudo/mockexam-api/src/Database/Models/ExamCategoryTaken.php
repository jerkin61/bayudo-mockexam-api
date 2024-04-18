<?php

namespace Jerquin\Database\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExamCategoryTaken extends Model
{
    use Sluggable;

    protected $table = 'exam_category_taken';

    public $guarded = [];

    protected $casts = [
 
    ];
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

      public function examCategory()
    {
        return $this->hasOne(ExamCategory::class, 'id', 'exam_category_id');
    }
      public function examTaken()
    {
        return $this->hasOne(ExamTaken::class, 'id', 'exam_taken_id');
    }

}
