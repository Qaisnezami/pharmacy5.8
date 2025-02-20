<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name'=>'Read']);
        Permission::create(['name'=>'Create']);
        Permission::create(['name'=>'Update']);
        Permission::create(['name'=>'Delete']);
    }
}
