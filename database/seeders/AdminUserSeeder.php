<?php

namespace Database\Seeders;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@khgc.com'],
            [
                'first_name' => 'Admin',
                'last_name'  => 'Super',
                'password'   => Hash::make('Abcd@1234'),
                'role'       => UserRole::ADMIN->value,
                'status'     => '1', 
            ]
        );
    }
}