<?php

namespace Jerquin\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Report extends Model
{
    use Sluggable;

    protected $table = 'reports';

    public $guarded = [];

    protected $casts = [
        // 'image' => 'json',
    ];

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
        // return $this->hasMany('Jerquin\Database\Models\Category', 'parent', 'id')->with('children');;
    }

    /**
     * @return HasOne
     */
    public function parent()
    {
        // return $this->hasOne('Jerquin\Database\Models\Category', 'id', 'parent');
    }
    /**
     * @return HasOne
     */
    public function user()
    {
        return $this->hasOne('Jerquin\Database\Models\User', 'id', 'user');
    }
}
