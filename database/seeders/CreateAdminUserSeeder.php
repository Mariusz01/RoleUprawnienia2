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

        $role2 = Role::create(['name' => 'Product']);
        $selectedPermissionIds = [5, 6, 7, 8]; // Identyfikatory wierszy, których kolumnę chcesz pobrać
        $selectedColumnValues = Permission::whereIn('id', $selectedPermissionIds)->pluck('id');
        $role2->syncPermissions($selectedColumnValues);

        \App\Models\User::factory(10)->create()->each(function($user){
            // Przypisanie roli 'User'
            $user->assignRole('Product');
            // Przypisanie uprawnienia 'edit articles'
            // $user->givePermissionTo('edit articles');
        });
        \App\Models\User::factory(10)->create()->each(function($user){
            // Przypisanie roli 'User'
            $user->assignRole('Admin');
            // Przypisanie uprawnienia 'edit articles'
            // $user->givePermissionTo('edit articles');
        });

        // factory(User::class, 10)->create()->each(function ($user) {
        //     // Przypisanie roli 'User'
        //     $user->assignRole('User');

        //     // Przypisanie uprawnienia 'edit articles'
        //     $user->givePermissionTo('edit articles');
        // });

    }
}

/*
php artisan migrate:reset - Wycofanie wszystkich migracji.

php artisan migrate
php artisan make:seeder PermissionTableSeeder
php artisan db:seed --class=PermissionTableSeeder
php artisan db:seed --class=CreateAdminUserSeeder

// W pliku DatabseSeeder wprowadziłem w jakiej kolejności ma uruchamiać
php artisan migrate:fresh --seed --seeder=DatabaseSeeder
*/
