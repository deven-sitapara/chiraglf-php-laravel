<?php

namespace App\Filament\Admin\Resources\CompanyResource\Pages;

use App\Filament\Admin\Resources\CompanyResource;
use App\Filament\Traits\RedirectsToListingPage;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCompany extends CreateRecord
{
    use RedirectsToListingPage;

    protected static string $resource = CompanyResource::class;
}
