<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'lectio',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
            'role' => 'admin',
        ]);

        $this->call([
            BookSeeder::class,
        ]);

    }
}
