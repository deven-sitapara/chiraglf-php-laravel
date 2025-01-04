<?php

namespace App\Filament\Admin\Resources\VRResource\Pages;

use App\Filament\Admin\Resources\VRResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVR extends EditRecord
{
    protected static string $resource = VRResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
