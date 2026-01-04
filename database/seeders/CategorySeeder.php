<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            ['id' => 1, 'name' => 'Teknologi & IT', 'slug' => 'teknologi-it'],
            ['id' => 2, 'name' => 'Musik & Konser', 'slug' => 'musik-konser'],
            ['id' => 3, 'name' => 'Seminar & Webinar', 'slug' => 'seminar-webinar'],
            ['id' => 4, 'name' => 'Workshop & Kursus', 'slug' => 'workshop-kursus'],
            ['id' => 5, 'name' => 'Olahraga & Kesehatan', 'slug' => 'olahraga-kesehatan'],
            ['id' => 6, 'name' => 'Seni & Budaya', 'slug' => 'seni-budaya'],
            ['id' => 7, 'name' => 'Bisnis & Entrepreneur', 'slug' => 'bisnis-entrepreneur'],
            ['id' => 8, 'name' => 'Pendidikan & Karir', 'slug' => 'pendidikan-karir'],
            ['id' => 9, 'name' => 'Sosial & Komunitas', 'slug' => 'sosial-komunitas'],
            ['id' => 10, 'name' => 'Kuliner & Food Fest', 'slug' => 'kuliner-food-fest'],
            ['id' => 11, 'name' => 'Game & E-Sports', 'slug' => 'game-esports'],
            ['id' => 12, 'name' => 'Keagamaan', 'slug' => 'keagamaan'],
            ['id' => 13, 'name' => 'Fashion & Kecantikan', 'slug' => 'fashion-kecantikan'],
            ['id' => 14, 'name' => 'Pameran & Expo', 'slug' => 'pameran-expo'],
            ['id' => 15, 'name' => 'Wisata & Alam', 'slug' => 'wisata-alam'],
        ]);
    }
}
