<?php

namespace App\Filament\Admin\Resources\TSRResource\Pages;

use App\Filament\Admin\Resources\TSRResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTSR extends EditRecord
{
    protected static string $resource = TSRResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
