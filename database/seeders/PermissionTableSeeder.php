<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'admin', //1

            'zwykly-user', //2

            'role-list', //3
            'role-create', //4
            'role-edit', //5
            'role-delete', //6
            'product-list', //7
            'product-create', //8
            'product-edit', //9
            'product-delete', //10
            'word-list', //11
            'word-create', //12
            'word-edit', //13
            'word-delete', //14

            'slowka-index', //15
            'slowka-list', //16
            'slowka-create', //17
            'slowka-edit', //18
            'slowka-delete', //19



        ];

        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}
