<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Enums\UserStatus;

class User extends Authenticatable
{
   
     protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'status',
    ];

    protected $casts = [
        'status' => UserStatus::class,
    ];

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => bcrypt($value), 
        );
    }

   
}
