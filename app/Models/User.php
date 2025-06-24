<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Enums\UserStatus;

class User extends Authenticatable
{
//    {
//     protected $table = 'nguoi_dung'; // ánh xạ model User tới bảng 'nguoi_dung'
// }

     protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'status',
    ];

     protected $casts = [
        'status' => UserStatus::class,
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

   
}
