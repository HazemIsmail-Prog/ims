<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['id' => '1', 'name' => 'Super Admin'],
        ];
        Role::insert($roles);
        User::find(1)->roles()->sync(1);
    }
}
