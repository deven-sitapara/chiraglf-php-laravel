<?php

namespace App\Filament\Admin\Resources\DocumentResource\Pages;

use App\Filament\Admin\Resources\DocumentResource\DocumentResource;
use App\Filament\Admin\Traits\RedirectsToListingPage;
use Filament\Resources\Pages\CreateRecord;

class CreateDocument extends CreateRecord
{
    use RedirectsToListingPage;

    protected static string $resource = DocumentResource::class;

    
}
