<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource\UserResource;
use App\Filament\Admin\Traits\RedirectsToListingPage;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    use RedirectsToListingPage;

    protected static string $resource = UserResource::class;

//    protected function getHeaderActions(): array
//    {
//        return [
//            Actions\DeleteAction::make(),
//        ];
//    }
}
