<?php

namespace App\Filament\Admin\Resources\SearchResource\Pages;

use App\Filament\Admin\Resources\SearchResource;
use App\Filament\Traits\RedirectsToListingPage;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSearch extends CreateRecord
{
    use RedirectsToListingPage;

    protected static string $resource = SearchResource::class;
}
