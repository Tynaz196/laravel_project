<?php

namespace App\Enums;

enum PostStatus: int
{
    case PENDING = 0;     // chờ phê duyệt
    case APPROVED = 1;    // Đã được phê duyệt
    case DENNIED = 2;    // từ chối

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Chờ phê duyệt',
            self::APPROVED => 'Đã phê duyệt',
            self::DENNIED => 'Từ chối',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PENDING => 'bg-warning',
            self::APPROVED => 'bg-success',
            self::DENNIED => 'bg-danger',
        };
    }
}
