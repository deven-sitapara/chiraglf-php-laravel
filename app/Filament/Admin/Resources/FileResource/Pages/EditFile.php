<?php

namespace App\Filament\Admin\Resources\FileResource\Pages;

use App\Filament\Admin\Resources\FileResource\FileResource;
use App\Filament\Admin\Traits\RedirectsToListingPage;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFile extends EditRecord
{
    use RedirectsToListingPage;

    protected static string $resource = FileResource::class;
    // protected static ?string $title = 'Edit File 1';

    public function getHeading(): string
    {
        return 'Edit File #' . $this->getRecord()->id; // Assuming 'id' is the file ID
    }

    protected function getHeaderActions(): array
    {
        return [
            //            Actions\DeleteAction::make(),
        ];
    }
}
