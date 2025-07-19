<?php

namespace App\Enums;

enum UserStatus: int
{
    case PENDING = 0;     // Chờ phê duyệt
    case APPROVED = 1;    // Được phê duyệt
    case REJECTED = 2;    // Bị từ chối
    case BLOCKED = 3;     // Bị khoá

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Chờ phê duyệt',
            self::APPROVED => 'Đã phê duyệt',
            self::REJECTED => 'Bị từ chối',
            self::BLOCKED => 'Bị khoá',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PENDING => 'badge-warning',
            self::APPROVED => 'badge-success',
            self::REJECTED => 'badge-danger',
            self::BLOCKED => 'badge-dark',
        };
    }
}
