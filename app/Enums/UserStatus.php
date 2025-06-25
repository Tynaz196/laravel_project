<?php

namespace App\Enums;

enum UserStatus: string
{
    case PENDING = '0';     // Chờ phê duyệt
    case APPROVED = '1';    // Được phê duyệt
    case REJECTED = '2';    // Bị từ chối
    case BLOCKED = '3';     // Bị khoá

}



//create lable