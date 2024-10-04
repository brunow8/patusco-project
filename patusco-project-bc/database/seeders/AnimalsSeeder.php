<?php

namespace Database\Seeders;

use App\Models\Animal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AnimalsSeeder extends Seeder
{
    public function run()
    {
        // Define sample names and breeds
        $names = [
            'Bella',
            'Max',
            'Charlie',
            'Luna',
            'Lucy',
            'Cooper',
            'Daisy',
            'Buddy',
            'Molly',
            'Bailey',
            'Rocky',
            'Teddy',
            'Zoe',
            'Chester',
            'Leo',
            'Chloe',
            'Maggie',
            'Buster',
            'Nala',
            'Rex'
        ];

        for ($i = 0; $i < 50; $i++) {
            Animal::create([
                'user_id' => rand(7, 27),
                'name' => $names[array_rand($names)],
                'breed' => Str::random(8),
                'animal_type_id' => rand(1, 25),
                'birthday' => Carbon::parse(now()->subYears(rand(1, 10)))
            ]);
        }
    }
}
