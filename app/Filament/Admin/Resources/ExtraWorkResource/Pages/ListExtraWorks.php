<?php

namespace App\Filament\Admin\Resources\ExtraWorkResource\Pages;

use App\Filament\Admin\Resources\ExtraWorkResource\ExtraWorkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExtraWorks extends ListRecords
{
    protected static string $resource = ExtraWorkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
