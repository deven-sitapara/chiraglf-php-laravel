<?php

namespace App\Filament\Admin\Resources\SearchResource\Pages;

use App\Filament\Admin\Resources\SearchResource\SearchResource;
use App\Filament\Admin\Traits\RedirectsToListingPage;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSearch extends EditRecord
{
    use RedirectsToListingPage;

    protected static string $resource = SearchResource::class;

 }
