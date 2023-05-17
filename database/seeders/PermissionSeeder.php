<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'dashboard_menu',
                'section_name' => 'dashboard',
                'description' => 'Dashboard Menu',
            ],

            //roles
            [
                'name' => 'roles_menu',
                'section_name' => 'roles',
                'description' => 'Roles Menu',
            ],
            [
                'name' => 'roles_create',
                'section_name' => 'roles',
                'description' => 'Roles Create',
            ],
            [
                'name' => 'roles_edit',
                'section_name' => 'roles',
                'description' => 'Roles Edit',
            ],
            [
                'name' => 'roles_delete',
                'section_name' => 'roles',
                'description' => 'Roles Delete',
            ],

            //permissions
            [
                'name' => 'permissions_menu',
                'section_name' => 'permissions',
                'description' => 'Permissions Menu',
            ],
            [
                'name' => 'permissions_create',
                'section_name' => 'permissions',
                'description' => 'Permissions Create',
            ],
            [
                'name' => 'permissions_edit',
                'section_name' => 'permissions',
                'description' => 'Permissions Edit',
            ],
            [
                'name' => 'permissions_delete',
                'section_name' => 'permissions',
                'description' => 'Permissions Delete',
            ],

            //users

            [
                'name' => 'users_menu',
                'section_name' => 'users',
                'description' => 'Users Menu',
            ],
            [
                'name' => 'users_create',
                'section_name' => 'users',
                'description' => 'Users Create',
            ],
            [
                'name' => 'users_edit',
                'section_name' => 'users',
                'description' => 'Users Edit',
            ],
            [
                'name' => 'users_delete',
                'section_name' => 'users',
                'description' => 'Users Delete',
            ],


            //stores

            [
                'name' => 'stores_menu',
                'section_name' => 'stores',
                'description' => 'Stores Menu',
            ],
            [
                'name' => 'stores_view',
                'section_name' => 'stores',
                'description' => 'Stores View',
            ],
            [
                'name' => 'stores_create',
                'section_name' => 'stores',
                'description' => 'Stores Create',
            ],
            [
                'name' => 'stores_edit',
                'section_name' => 'stores',
                'description' => 'Stores Edit',
            ],
            [
                'name' => 'stores_delete',
                'section_name' => 'stores',
                'description' => 'Stores Delete',
            ],


            //items

            [
                'name' => 'items_menu',
                'section_name' => 'items',
                'description' => 'Items Menu',
            ],
            [
                'name' => 'items_view',
                'section_name' => 'items',
                'description' => 'Items View',
            ],
            [
                'name' => 'items_create',
                'section_name' => 'items',
                'description' => 'Items Create',
            ],
            [
                'name' => 'items_edit',
                'section_name' => 'items',
                'description' => 'Items Edit',
            ],
            [
                'name' => 'items_delete',
                'section_name' => 'items',
                'description' => 'Items Delete',
            ],


            //transactions

            [
                'name' => 'transactions_menu',
                'section_name' => 'transactions',
                'description' => 'Transactions Menu',
            ],
            [
                'name' => 'transactions_view',
                'section_name' => 'transactions',
                'description' => 'Transactions View',
            ],
            [
                'name' => 'transactions_create',
                'section_name' => 'transactions',
                'description' => 'Transactions Create',
            ],
            [
                'name' => 'transactions_edit',
                'section_name' => 'transactions',
                'description' => 'Transactions Edit',
            ],
            [
                'name' => 'transactions_delete',
                'section_name' => 'transactions',
                'description' => 'Transactions Delete',
            ],
        ];

        DB::table('permission_role')->delete();
        DB::table('permissions')->delete();
        Permission::insert($permissions);

        // Attach All Created Permissions to the Super Admin Role
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            Role::find(1)->permissions()->attach($permission->id);
        }
    }
}
