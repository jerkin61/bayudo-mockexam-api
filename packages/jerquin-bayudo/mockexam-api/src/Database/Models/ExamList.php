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
    use Sluggable;

    protected $table = 'examlist';

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
        return $this->hasMany(ExamCategory::class, 'exam_id', 'id');
    }

}
