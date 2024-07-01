<?php

namespace Jerquin\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model
{
    use Sluggable;

    protected $table = 'categories';

    public $guarded = [];

    protected $casts = [
        'image' => 'json',
    ];

    // protected static function boot()
    // {
    //     parent::boot();
    //     // Order by updated_at desc
    //     static::addGlobalScope('order', function (Builder $builder) {
    //         $builder->orderBy('updated_at', 'desc');
    //     });
    // }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * @return HasMany
     */
    public function children()
    {
        return $this->hasMany('Jerquin\Database\Models\Category', 'parent', 'id')->with('children');;
    }

    /**
     * @return HasOne
     */
    public function parent()
    {
        return $this->hasOne('Jerquin\Database\Models\Category', 'id', 'parent');
    }

        public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_category');
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'category_question');
    }
}
