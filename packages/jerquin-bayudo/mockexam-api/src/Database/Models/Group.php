<?php

namespace Jerquin\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    protected $table = 'groups';

    public $guarded = [];

    protected $casts = [
    
    ];

    /**
     * @return BelongsTo
     */
    public function members()
    {
        return $this->belongsToMany(User::class);
    }
    
    public function exams()
    {
        return $this->belongsToMany(ExamList::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
