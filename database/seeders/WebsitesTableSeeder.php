<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\Website;

class WebsitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Website::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 50; $i++) {
            Website::create([
                'address' => $faker->domainName
            ]);
        }
    }
}
