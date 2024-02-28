<?php

namespace Jerquin\Database\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shop extends Model
{
    use Sluggable;

    protected $table = 'shop';

    public $guarded = [];

    protected $casts = [
        'logo' => 'json',
        'cover_image' => 'json',
        'address' => 'json',
        'company_settings' => 'json',
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



    public function company()
    {
        return $this->belongsTo(Company::class, 'owner_id');
    }

    public function products() : HasMany
    {
        return $this->hasMany(Product::class, 'shop_id');
    }


}
