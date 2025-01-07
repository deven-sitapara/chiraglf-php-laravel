<?php

namespace App\Filament\Admin\Resources\FileResource\Pages;

use App\Filament\Admin\Resources\FileResource;
use App\Filament\Traits\RedirectsToListingPage;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFile extends CreateRecord
{
    use RedirectsToListingPage;

    protected static string $resource = FileResource::class;
}
