<?php

namespace App\Filament\Admin\Resources\CompanyResource\Pages;

use App\Filament\Admin\Resources\CompanyResource\CompanyResource;
use App\Filament\Admin\Traits\RedirectsToListingPage;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompany extends EditRecord
{
    use RedirectsToListingPage;

    protected static string $resource = CompanyResource::class;


}
