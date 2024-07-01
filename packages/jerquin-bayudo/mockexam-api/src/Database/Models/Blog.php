<?php

namespace Jerquin\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blogs';

    public $guarded = [];


    public function categories()
    {
        return $this->belongsToMany(Category::class, 'blog_category');
    }

    public function blogItems()
    {
        return $this->hasMany(BlogItem::class)->orderBy('order');
    }
}
