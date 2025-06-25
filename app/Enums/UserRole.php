<?php

namespace App\Enums;

enum UserRole: string
{
    case USER = '0';
    case ADMIN = '1';
}