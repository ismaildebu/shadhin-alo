<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Article\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $districts = [
            ['name' => 'খুলনা', 'slug' => 'khulna'],
            ['name' => 'যশোর', 'slug' => 'jashore'],
            ['name' => 'সাতক্ষীরা', 'slug' => 'satkhira'],
            ['name' => 'ঝিনাইদহ', 'slug' => 'jhenaidah'],
            ['name' => 'নড়াইল', 'slug' => 'narail'],
            ['name' => 'কুষ্টিয়া', 'slug' => 'kushtia'],
            ['name' => 'মাগুরা', 'slug' => 'magura'],
            ['name' => 'বাগেরহাট', 'slug' => 'bagerhat'],
            ['name' => 'চুয়াডাঙ্গা', 'slug' => 'chuadanga'],
            ['name' => 'মেহেরপুর', 'slug' => 'meherpur'],
        ];

        foreach ($districts as $index => $district) {
            District::firstOrCreate(
                ['slug' => $district['slug']],
                [
                    'name' => $district['name'],
                    'division' => 'খুলনা বিভাগ',
                    'order' => $index + 1,
                ]
            );
        }
    }
}