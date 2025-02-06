<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Site;
use Faker\Factory as Faker;

class SiteSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 5; $i++) {
            Site::create([
                'name' => $faker->company,
                'description' => $faker->address,
            ]);
        }
    }
}
