<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(int $eventCount = 20, int $ticketCount = 5): void
    {
        // if categories is empty,run category seeder first
        if (Category::count() === 0) {
            $this->call(CategorySeeder::class);
        }

        //FAKER INSTANCE
        $faker = Factory::create();

        //Create event based on event count
        for ($i = 0; $i < $eventCount; $i++) {
            //create event
            $event = Event::create([
                'name' => $faker->sentence(2),
                'slug' => $faker->unique()->slug(2),
                'headline' => $faker->sentence(7),
                'description' => $faker->paragraph,
                'start_time' => $faker->dateTimeBetween('+1 month', '+6 month'),
                'location' => $faker->address,
                'duration' => $faker->numberBetween(1, 2), // DURATION 1 SAMPAI 2 JAM
                'category_id' => Category::inRandomOrder()->first()->id,
                'type' => $faker->randomElement(['online', 'offline']),
                'is_popular' => $faker->boolean(20),
            ]);

            //CREATE TICKET BASED ON TICKET COUTN
            for ($j = 0; $j < $ticketCount; $j++) {
                $event->tickets()->create([
                    'name' => $faker->sentence(3),
                    'price' => $faker->numberBetween(100, 1000),
                    'quantity' => $faker->numberBetween(10, 100),
                    'max_buy' => $faker->numberBetween(1, 10),
                ]);
            }
        }
    }
}
