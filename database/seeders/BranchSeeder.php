<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{

    public function run()
    {
        // Generate 5 branches using the factory
        // Branch::factory()->count(5)->create();

        $this->command->warn(PHP_EOL . 'Creating Branches...');
        Branch::factory(5)->create();

        $this->command->info('Branches created.');
    }
}
