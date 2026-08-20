<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'], // Find by username
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@sdnsusukan08pagi.sch.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );
    }
}
