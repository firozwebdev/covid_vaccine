<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VaccineCenter;

class VaccineCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //VaccineCenter::factory()->count(50)->create();
        VaccineCenter::create(['name' => 'Center 1', 'daily_limit' => 100]);
        VaccineCenter::create(['name' => 'Center 2', 'daily_limit' => 20]);
        VaccineCenter::create(['name' => 'Center 3', 'daily_limit' => 45]);
        VaccineCenter::create(['name' => 'Center 4', 'daily_limit' => 30]);
        VaccineCenter::create(['name' => 'Center 5', 'daily_limit' => 35]);
        VaccineCenter::create(['name' => 'Center 6', 'daily_limit' => 35]);
        VaccineCenter::create(['name' => 'Center 7', 'daily_limit' => 35]);
        VaccineCenter::create(['name' => 'Center 8', 'daily_limit' => 35]);
        VaccineCenter::create(['name' => 'Center 9', 'daily_limit' => 35]);
        VaccineCenter::create(['name' => 'Center 10', 'daily_limit' => 35]);
        VaccineCenter::create(['name' => 'Center 11', 'daily_limit' => 35]);
        VaccineCenter::create(['name' => 'Center 12', 'daily_limit' => 35]);
        VaccineCenter::create(['name' => 'Center 13', 'daily_limit' => 35]);
        VaccineCenter::create(['name' => 'Center 14', 'daily_limit' => 35]);
        VaccineCenter::create(['name' => 'Center 15', 'daily_limit' => 35]);
    }
}
