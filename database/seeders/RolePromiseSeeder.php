<?php

namespace Database\Seeders;

use App\Models\Promise;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePromiseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Lấy tất cả các Role và Promise
         $roles = Role::all();
         $promises = Promise::all();
 
         // Tạo liên kết ngẫu nhiên giữa roles và promises
         foreach ($roles as $role) {
             $role->promises()->attach($promises->random(2)->pluck('id')->toArray()); // Gán ngẫu nhiên 2 promises cho mỗi role
         }
    }
}
