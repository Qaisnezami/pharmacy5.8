<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name'=>'Dashboard']);
        Role::create(['name'=>'Add Medicines']);
        Role::create(['name'=>'Medicine Group']);
        Role::create(['name'=>'Manufacturer']);
        Role::create(['name'=>'Classification']);
        Role::create(['name'=>'Doctors']);
        Role::create(['name'=>'Suppliers']);
        Role::create(['name'=>'Barcode']);
        Role::create(['name'=>'Company']);
        Role::create(['name'=>'Purchase']);
        Role::create(['name'=>'Purchase Report']);
        Role::create(['name'=>'Counter Sales']);
        Role::create(['name'=>'Counter Sales Report']);
        Role::create(['name'=>'Low Stock']);
        Role::create(['name'=>'Item Expiry Date']);
        Role::create(['name'=>'Inventory Reports']);
        Role::create(['name'=>'Expenses Category']);
        Role::create(['name'=>'Expenses']);
        Role::create(['name'=>'Users']);
        Role::create(['name'=>'Profile']);
        Role::create(['name'=>'Setting']);
        Role::create(['name'=>'Order']);
        Role::create(['name'=>'Order Report']);
        $user = User::create(['name'=>'Admin','email'=>'admin@me.com','password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','photo'=>'','email_verified_at' => now(),'remember_token' => Str::random(10),]);
        $user->roles()->sync(['19']);
    }
}
