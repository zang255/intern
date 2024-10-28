<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create([
            'name'=> 'index',
            'description'=>'index permissions',
        ]);
        Permission::create([
            'name'=> 'show',
            'description'=>'show permissions',
        ]);
        Permission::create([
            'name'=> 'create',
            'description'=>'create permissions',
        ]);
        Permission::create([
            'name'=> 'update',
            'description'=>'update permissions',
        ]);
        Permission::create([
            'name'=> 'delete',
            'description'=>'delete permissions',
        ]);
    }
}
