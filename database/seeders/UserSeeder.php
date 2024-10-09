<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::insert([
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' =>  Hash::make('12345'),
                'role_id' => 1, 
            ],
            [
                'name' => 'Zang',
                'email' => 'zang@gmail.com',
                'password' => Hash::make('12345'),
                'role_id' => 2, 
            ],
        ]);
    }
}
