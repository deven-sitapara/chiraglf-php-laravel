<?php

namespace App\Filament\Admin\Resources\ExtraWorkResource\Pages;

use App\Filament\Admin\Resources\ExtraWorkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExtraWork extends EditRecord
{
    protected static string $resource = ExtraWorkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
