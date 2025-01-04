<?php

namespace App\Filament\Admin\Resources\TSRResource\Pages;

use App\Filament\Admin\Resources\TSRResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTSRS extends ListRecords
{
    protected static string $resource = TSRResource::class;

    public static ?string $title = 'TSRs';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
