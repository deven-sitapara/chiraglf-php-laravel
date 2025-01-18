<?php

namespace App\Filament\Admin\Resources\CompanyResource\Pages;

use App\Filament\Admin\Resources\CompanyResource\CompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanies extends ListRecords
{
    protected static string $resource = CompanyResource::class;
    protected static ?string $title = "";

}
