<?php

namespace App\Filament\Admin\Resources\SearchResource\Pages;

use App\Filament\Admin\Resources\SearchResource\SearchResource;
use App\Filament\Admin\Traits\RedirectsToListingPage;
use Filament\Resources\Pages\CreateRecord;

class CreateSearch extends CreateRecord
{
    use RedirectsToListingPage;

    protected static string $resource = SearchResource::class;
}
