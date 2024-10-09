<?php

namespace Database\Seeders;

use App\Models\Promise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromiseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promise::insert([
            ['description' => 'Promise 1'],
            ['description' => 'Promise 2'],
            
        ]);

    }
}
