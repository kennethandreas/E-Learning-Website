<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Test Student
        User::create([
            'name' => 'Siswa Test',
            'username' => 'testsiswa',
            'email' => 'siswa1@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'nisn' => '123456789012',
            'kelas' => '1A',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'tanggal_lahir' => '2010-01-01',
            'tentang_aku' => 'Siswa test untuk testing login',
            'email_orang_tua' => 'ortu@example.com',
            'nomor_telepon_orang_tua' => '081234567891',
        ]);

        // Test Teacher
        User::create([
            'name' => 'Guru Test',
            'username' => 'testguru',
            'email' => 'guru1@example.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'phone' => '081234567892',
            'address' => 'Jl. Guru No. 1',
        ]);
    }
}
