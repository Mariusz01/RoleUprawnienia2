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
            'word-aktualizuj', //15

            'slowka-index', //16
            'slowka-list', //17
            'slowka-create', //18
            'slowka-edit', //19
            'slowka-delete', //20

            'nauka-index', //21
            'nauka-list', //22
            'nauka-create', //23
            'nauka-edit', //24
            'nauka-delete', //25



        ];

        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}
