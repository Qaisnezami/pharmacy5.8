<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(PermissionSeeder::class);
        // $this->call(MedicineSeeder::class);
        // factory(App\Stock::class, 500)->create();
        // factory(App\Counter_sale::class, 50000)->create();
        // factory(App\Counter_sale_detail::class, 50000)->create();
        // factory(App\Purchase_detail::class, 500)->create();
    }
}
