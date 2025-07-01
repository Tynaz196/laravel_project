<?php

namespace App\Enums;

enum PostStatus: int
{
    case POST = 0;     // Được đăng
    case NEWPOST = 1;    // Bài viết mới
    case UPDATE = 2;    // Được cập nhật

}
