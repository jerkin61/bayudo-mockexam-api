<?php

namespace Jerquin\Database\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QuestionFeedback extends Model
{
    use Sluggable;

    protected $table = 'question_feedback';

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
    public function question()
    {
        return $this->belongsTo('App\Models\Question');
    }
 

}
