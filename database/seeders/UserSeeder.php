<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create manager user
        User::updateOrCreate([
            'email' => 'muhammad@gmail.com',
        ], [
            'name' => 'Muhammad Esmail',
            'username' => 'muhammad',
            'email' => 'muhammad@gmail.com',
            'password' => 'muhammad',
        ]);
        foreach (range(1, 5) as $index) {
            User::updateOrCreate([
                'username' => fake()->unique()->userName(),
                'email' => fake()->unique()->freeEmail(),
            ], [
                'name' => fake()->name(),
                'phone' => fake()->phoneNumber(),
                'password' => 'muhammad',
                'status' => fake()->randomElement(StatusEnum::toArray()),
            ]);
        }
    }
}
