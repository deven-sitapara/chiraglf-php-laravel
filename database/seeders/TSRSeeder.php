<?php

namespace Database\Seeders;

use App\Models\TSR;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TSRSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TSR::factory(10)->create();
    }
}
