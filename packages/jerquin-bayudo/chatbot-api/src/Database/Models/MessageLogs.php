<?php

namespace Jerquin\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MessageLogs extends Model
{
    use Sluggable;

    protected $table = 'message_logs';

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
     * @return HasOne
     */
    public function reciever()
    {
        return $this->hasOne('Jerquin\Database\Models\User', 'id', 'reciever');
    }

    /**
     * @return HasOne
     */
    public function sender()
    {
        return $this->hasOne('Jerquin\Database\Models\User', 'id', 'sender');
    }
}
