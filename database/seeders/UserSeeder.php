<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@libraflow.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Marie Biblio',
            'email'    => 'biblio@libraflow.com',
            'password' => Hash::make('password'),
            'role'     => 'bibliothecaire',
        ]);

        User::create([
            'name'     => 'Jean Lecteur',
            'email'    => 'lecteur@libraflow.com',
            'password' => Hash::make('password'),
            'role'     => 'lecteur',
        ]);
    }
}
