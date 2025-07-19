<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserStatus;
use App\Enums\UserRole;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable
{

    use HasFactory, Notifiable;
    //    {
    //     protected $table = 'cache'; // ánh xạ model cache tới bảng 'cache'
    // }

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'status',
        'role',
        'address',
    ];

    protected $casts = [
        'status' => UserStatus::class,
        'role' => UserRole::class,
    ];



    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getNameAttribute(): string
    {
        return  $this->last_name . ' ' . $this->first_name;
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
