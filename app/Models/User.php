<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\StatusEnum;
use App\Traits\WithSearchable;
use Exception;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\InteractsWithMedia;
use Throwable;

class User extends Authenticatable
{
    use CanResetPassword;
    use HasFactory;
    use Notifiable;
    use WithSearchable;

    protected $fillable = [
        'name',
        'username',
        'phone',
        'email',
        'password',
        'status',
        'user_type',
    ];

    protected $searchable = ['name', 'username', 'phone', 'email'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Favorite, $this>
     */
    public function favorites(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function active($query): mixed
    {
        return $query->where('status', StatusEnum::Active);
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function inactive($query): mixed
    {
        return $query->where('status', StatusEnum::Inactive);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => StatusEnum::class,
        ];
    }
}
