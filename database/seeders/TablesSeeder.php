<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class TablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::insert([
            [
                'name' => 'Roles',
                'status' => '0',
                'is_del' => '0',
            ],
            [
                'name' => 'Employees',
                'status' => '0',
                'is_del' => '0',
            ],
            [
                'name' => 'Commissions',
                'status' => '0',
                'is_del' => '0',
            ],
            [
                'name' => 'Projects',
                'status' => '0',
                'is_del' => '0',
            ],
            [
                'name' => 'Admin Settings',
                'status' => '0',
                'is_del' => '0',
            ],
        ]);

        Role::insert([
            [
                'parent_id' => null,
                'permission_ids' => json_encode(array('4')),
                'name' => 'HOD',
                'status' => 0,
                'is_del' => 0
            ],
            [
                'parent_id' => '1',
                'permission_ids' => json_encode(array('4')),
                'name' => 'Manager',
                'status' => 0,
                'is_del' => 0
            ],
            [
                'parent_id' => '2',
                'permission_ids' => json_encode(array('4')),
                'name' => 'Employee',
                'status' => 0,
                'is_del' => 0
            ],
        ]);
    }
}
