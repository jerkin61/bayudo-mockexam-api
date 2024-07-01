<?php

namespace Jerquin\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogItem extends Model
{
    protected $table = 'blog_items';

    public $guarded = [];

    
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
