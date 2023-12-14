<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Provider\ar_EG\Person;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'mariusz',
            'email' => 'mariusz@mariusz.com',
            'password' => bcrypt('mariusz')
        ]);

        $role = Role::create(['name' => 'Admin']);
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);

        // $role2 = Role::create(['name' => 'Product']);
        // $permissions2 = Permission::pluck('id', 'id')->array[]
    }
}

/*
php artisan migrate:reset - Wycofanie wszystkich migracji.

php artisan migrate
php artisan make:seeder PermissionTableSeeder
php artisan db:seed --class=PermissionTableSeeder
php artisan db:seed --class=CreateAdminUserSeeder

php artisan migrate:fresh --seed --seeder=CreateAdminUserSeeder
*/
