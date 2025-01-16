<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource\UserResource;
use App\Filament\Admin\Traits\RedirectsToListingPage;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    use RedirectsToListingPage;

    protected static string $resource = UserResource::class;
}
