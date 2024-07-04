<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use App\Stock;
use App\Medicine;
use App\Purchase;
use App\Counter_sale;
use App\Purchase_detail;
use Illuminate\Support\Str;
use App\Counter_sale_detail;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

$factory->define(Medicine::class, function (Faker $faker) {
    return [
        'barcode'=> rand(0,1000000),
        'name' => $faker->name,
        'group_id' => 1,
        'classification_id' => 1,
        'manufacturer_id' => 1,
        'doctor_id' => 1,
        'user_id' => 1,
        'isactive' => 1,
        'approve' => 1,
    ];
});
$factory->define(Purchase::class, function (Faker $faker) {
    return [
        'BillNo'=> rand(0,1000000),
        'supplier_id' => 1,
        'date' => now(),
        'user_id' => 1,
        'total' => 500,
        'remind' => 0,
        'discount' => 0,
        'paid' => 500,
        'approve' => 0,
    ];
});
$factory->define(Purchase_detail::class, function (Faker $faker) {
    return [
        'purchase_id' => rand(0,2000),
        'medicine_id' => rand(0,2000),
        'batch_no' => Str::random(10),
        'expiry_date' => now(),
        'quantity' => 10,
        'free_quantity' => 1,
        'rate' => 500,
        'amount' => 500,
        'MRP' => 600,
        'approve' => 1,
    ];
});
$factory->define(Stock::class, function (Faker $faker) {
    return [
        'purchase_detail_id' => rand(0,2000),
        'medicine_id' => rand(0,2000),
        'batch_no' => Str::random(10),
        'expiry_date' => now(),
        'quantity' => 10,
        'notify' => 0,
        'MRP' => 600,
    ];
});

$factory->define(Counter_sale::class, function (Faker $faker) {
    return [
        'BillNo'=> rand(0,1000000),
        'doctor_id' => 1,
        'patient_name' => $faker->name,
        'date' => now(),
        'user_id' => 1,
        'total' => 500,
        'remaind' => 0,
        'discount' => 0,
        'paid' => 500,
    ];
});

$factory->define(Counter_sale_detail::class, function (Faker $faker) {
    return [
        'counter_sale_id' => rand(0,2000),
        'medicine_id' => rand(0,2000),
        'stock_id' => rand(0,2000),
        'expiry_date' => now(),
        'quantity' => 10,
        'amount' => 500,
        'MRP' => 600,
        'status' => 0,
        'user_id' => 1,
    ];
});
