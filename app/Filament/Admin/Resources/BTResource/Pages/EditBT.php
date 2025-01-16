<?php

namespace App\Filament\Admin\Resources\BTResource\Pages;

use App\Filament\Admin\Resources\BTResource\BTResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBT extends EditRecord
{
    protected static string $resource = BTResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
