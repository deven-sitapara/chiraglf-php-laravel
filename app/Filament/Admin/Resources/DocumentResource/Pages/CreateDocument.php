<?php

namespace App\Filament\Admin\Resources\DocumentResource\Pages;

use App\Filament\Admin\Resources\DocumentResource;
use App\Filament\Traits\RedirectsToListingPage;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDocument extends CreateRecord
{
    use RedirectsToListingPage;

    protected static string $resource = DocumentResource::class;
}
