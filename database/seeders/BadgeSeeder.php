<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/BadgeSeeder.php

public function run()
{
    Badge::insert([
        ['name' => 'Silver', 'min_point' => 1, 'max_point' => 20],
        ['name' => 'Gold', 'min_point' => 21, 'max_point' => 30],
        ['name' => 'Platinum', 'min_point' => 31, 'max_point' => 50],
        ['name' => 'Diamond', 'min_point' => 51, 'max_point' => 999],
    ]);
}

}
