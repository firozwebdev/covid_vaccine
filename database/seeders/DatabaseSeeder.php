<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(VaccineCenterSeeder::class);
        //$this->call(UserSeeder::class);

        User::factory()->create([
            'name' => 'Sabuz',
            'email' => 'smskushtia@gmail.com',
            'nid' => '1234567890',
            'mobile' => '01754165234',
            'vaccine_center_id' => 1
        
        ]);
        User::factory()->create([
            'name' => 'Firoz',
            'email' => 'everythingkst@gmail.com',
            'nid' => '1244567890',
            'mobile' => '01754165234',
            'vaccine_center_id' => 1
        
        ]);
        User::factory()->create([
            'name' => 'Hasin',
            'email' => 'everythingkst@gmail.com',
            'nid' => '1754165234',
            'mobile' => '1204567890',
            'vaccine_center_id' => 1
        
        ]);
        User::factory()->create([
            'name' => 'Taher',
            'email' => 'smskushtia@gmail.com',
            'nid' => '1934567890',
            'mobile' => '01754165234',
            'vaccine_center_id' => 1
        
        ]);
    }
}
