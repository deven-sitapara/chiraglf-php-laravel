<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\File;

class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition(): array
    {
        return [
            'branch_id' => Branch::inRandomOrder()->first()->id,
            'company_id' => Company::inRandomOrder()->first()->id,
            'file_number' => $this->faker->unique()->numberBetween(1, 1000000),
            'date' => $this->faker->dateTime(),
            'company_reference_number' => $this->faker->unique()->numerify('REF-####'),
            'borrower_name' => $this->faker->name,
            'proposed_owner_name' => $this->faker->name,
            'property_descriptions' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(array_keys(File::STATUS_OPTIONS)),
            'status_message' => $this->faker->sentence,
        ];
    }
}
