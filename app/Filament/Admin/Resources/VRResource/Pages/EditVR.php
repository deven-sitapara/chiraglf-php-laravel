<?php

namespace App\Filament\Admin\Resources\VRResource\Pages;

use App\Filament\Admin\Resources\VRResource\VRResource;
use App\Filament\Admin\Traits\RedirectsToListingPage;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVR extends EditRecord
{
    use RedirectsToListingPage;

    protected static string $resource = VRResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
