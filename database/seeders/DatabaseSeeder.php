<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create(['name'=>'admin']);
        Role::create(['name'=>'branch']);
        Role::create(['name'=>'customer']);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // $adminRole=Role::findByName('admin');
        // $permissions=['view_user','add_user','delete_user'];
        // foreach ($permissions as $permission){
        //     Permission::create(['name'=>$permission]);
        // }
        // $adminRole->syncPermissions($permissions);
        $adminRole=Role::findByName('admin');
        $permissions=['view_user','add_user','delete_user'];
        foreach ($permissions as $permission){
            Permission::create(['name'=>$permission]);
        }
        $adminRole->syncPermissions($permissions);
    }
}
