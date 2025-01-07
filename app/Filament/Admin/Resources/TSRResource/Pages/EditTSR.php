<?php

namespace App\Filament\Admin\Resources\TSRResource\Pages;

use App\Filament\Admin\Resources\TSRResource;
use App\Filament\Traits\RedirectsToListingPage;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTSR extends EditRecord
{
    use RedirectsToListingPage;

    protected static string $resource = TSRResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
