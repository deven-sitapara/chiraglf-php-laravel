<?php

namespace App\Filament\Admin\Resources\DocumentResource\Pages;

use App\Filament\Admin\Resources\DocumentResource;
use App\Filament\Traits\RedirectsToListingPage;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocument extends EditRecord
{
    use RedirectsToListingPage;

    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
