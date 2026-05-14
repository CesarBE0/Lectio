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
        User::updateOrCreate(
            ['email' => 'bibliotecalectio@gmail.com'],
            [
                'name' => 'Administrador Lectio',
                'password' => bcrypt('12345678'),
                'role' => 'admin',
            ]
        );

        $this->call([
            BookSeeder::class,
        ]);

    }
}
