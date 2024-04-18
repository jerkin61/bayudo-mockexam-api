<?php

namespace Jerquin\Database\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Jerquin\Database\Models\ExamCategory;

class ExamTaken extends Model
{

    protected $table = 'exam_taken';

    public $guarded = [];

    protected $casts = [
 
    ];


    //  public function examCategoryTaken()
    // {
    //     return $this->hasMany(ExamCategoryTaken::class, 'exam_taken_id', 'id');
    // }
    public function exam()
    {
        return $this->hasOne(ExamList::class, 'id', 'exam_id');
    }
       public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function examCategoryTaken()
    {
        return $this->hasMany(ExamCategoryTaken::class, 'exam_taken_id', 'id')->with(['examCategory']);
    }

}
