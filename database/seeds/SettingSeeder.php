<?php

use App\Serial;
use App\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create(['name'=>'Pharmacy','logo'=>'pharmacy1.png','background'=>'ph1.jpg']);
        Serial::create(['serial'=>'']);
    }
}
