<?php

namespace App\Filament\Admin\Resources\VRResource\Pages;

use App\Filament\Admin\Resources\VRResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVRS extends ListRecords
{
    protected static string $resource = VRResource::class;

    public static ?string $title = 'VRs';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
