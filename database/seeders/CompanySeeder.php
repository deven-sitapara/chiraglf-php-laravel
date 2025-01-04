<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CompanySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run()
    {
        $this->command->warn(PHP_EOL . 'Creating Companies...');
        // This will create the companies and persist them in the database
        Company::factory(5)->create();


        $this->command->info('Companies created.');
    }
}
