<?php

namespace App\Filament\Admin\Resources\VRResource\Pages;

use App\Filament\Admin\Resources\VRResource\VRResource;
use App\Filament\Admin\Traits\RedirectsToListingPage;
use Filament\Resources\Pages\CreateRecord;

class CreateVR extends CreateRecord
{
    use RedirectsToListingPage;

    protected static string $resource = VRResource::class;
}
