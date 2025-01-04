<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

class BranchFactory extends Factory
{
    protected $model = Branch::class;

    public function definition()
    {
        $cities = ['Rajkot', 'Ahmedabad', 'Jamnagar', 'Morbi', 'Bhavnagar'];

        //        ['branch_name', 'person_name', 'address', 'contact_number', 'email',]
        // CREATE TABLE "branches" ("id" integer primary key autoincrement not null, "branch_name" varchar not null, "person_name" varchar not null, "address" text not null, "contact_number" varchar not null, "email" varchar not null, "created_at" datetime, "updated_at" datetime)
        return [
            'branch_name' => $this->faker->randomElement($cities),
            'person_name' => $this->faker->name,
            'address' => $this->faker->address,
            'contact_number' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
        ];
    }
}
