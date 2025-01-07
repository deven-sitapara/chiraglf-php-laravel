<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition()
    {

        // 'name',  'emails',   'tsr_fee', 'vr_fee', 'document_fee', 'bt_fee'
        return [
            'name' => $this->faker->company,
            'emails' => json_encode([$this->faker->email, $this->faker->email]),
            'tsr_fee' => $this->faker->numberBetween(0, 1000),
            'vr_fee' => $this->faker->numberBetween(0, 5000),
            'document_fee' => $this->faker->numberBetween(0, 5000),
            'bt_fee' => $this->faker->numberBetween(0, 5000),
            'tsr_file_format' => '',
            'document_file_format' => '',
            'vr_file_format' => '',
            'search_file_format' => '',
            'ew_file_format' => '',
        ];
    }
}
