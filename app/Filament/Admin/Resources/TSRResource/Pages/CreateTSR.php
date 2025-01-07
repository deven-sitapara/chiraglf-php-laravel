<?php

namespace App\Filament\Admin\Resources\TSRResource\Pages;

use App\Filament\Admin\Resources\TSRResource;
use App\Filament\Traits\RedirectsToListingPage;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTSR extends CreateRecord
{
    use RedirectsToListingPage;

    protected static string $resource = TSRResource::class;
}
