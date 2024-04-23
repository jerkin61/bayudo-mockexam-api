<?php

namespace Jerquin\Database\Models;

use App\Enums\RoleType;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use HasApiTokens;


    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();
        // Order by updated_at desc
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('updated_at', 'desc');
        });
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    /**
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

 
    /**
     * @return HasOne
     */
    public function examTaken(): HasMany
    {
        return $this->hasMany(ExamTaken::class, 'user_id');
    }
    /**
     * @return HasOne
     */
    public function examCategoryTaken(): HasMany
    {
        return $this->hasMany(ExamCategoryTaken::class, 'user_id');
    }
    
    /**
     * @return BelongsToMany
     */
    public function group(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }

}
