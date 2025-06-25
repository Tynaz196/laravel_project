<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Enums\UserStatus;
use App\Enums\UserRole;

class User extends Authenticatable
{
//    {
//     protected $table = 'cache'; // ánh xạ model cache tới bảng 'cache'
// }

     protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'status',
    ];

     protected $casts = [
        'status' => UserStatus::class,
        'role' => UserRole::class,
    ];

  

    protected $hidden = [
        'password', 'remember_token',
    ];

   public function getNameAttribute(): string
{
    return "{$this->first_name} {$this->last_name}";
}
}
