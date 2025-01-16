<?php

namespace App\Filament\Admin\Resources\FileResource\Pages;

use App\Filament\Admin\Resources\FileResource\FileResource;
use App\Filament\Admin\Traits\RedirectsToListingPage;
use Filament\Resources\Pages\CreateRecord;

class CreateFile extends CreateRecord
{
    use RedirectsToListingPage;

    protected static string $resource = FileResource::class;
}
