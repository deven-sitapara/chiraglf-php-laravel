<?php

namespace App\Filament\Admin\Resources\CompanyResource\Pages;

use App\Filament\Admin\Resources\CompanyResource\CompanyResource;
use App\Filament\Admin\Traits\RedirectsToListingPage;
use Filament\Resources\Pages\CreateRecord;

class CreateCompany extends CreateRecord
{
    use RedirectsToListingPage;

    protected static string $resource = CompanyResource::class;

    // creaete function to handle file operations
    protected function beforeCreate($record)
    {
        // dd($record);
    }
}
