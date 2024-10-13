<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use DB;
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $nid = $this->generateUniqueNID();
        return [
            'vaccine_center_id' => $this->faker->numberBetween(1, 15),
			'name' => $this->faker->firstName() . " " . $this->faker->lastName(),
			'email' => $this->faker->unique()->safeEmail(),
			'nid' => $nid,
			'mobile' => $this->faker->phoneNumber(),
			'status' => $this->faker->randomElement(["Not scheduled"]),
			'scheduled_date' => now()->addDays($this->faker->randomElement([2, 3, 4])) // Randomly add 2, 3, or 4 days
        ];
    }

    private function generateUniqueNID()
    {
        $existingNIDs = DB::table('users')->pluck('nid')->toArray(); // Get existing NIDs from the database

        do {
            // Generate the first digit (1-9)
            $firstDigit = $this->faker->numberBetween(1, 9);
            // Generate the remaining 9 digits (0-9)
            $remainingDigits = $this->faker->numerify('#########'); // Generates 9 digits in total
            $nid = $firstDigit . $remainingDigits; // Combine first digit with remaining digits
        } while (in_array($nid, $existingNIDs)); // Check for uniqueness

        return $nid;
    }
}
