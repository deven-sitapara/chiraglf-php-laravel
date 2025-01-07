<?php

namespace App\Filament\Admin\Resources\ExtraWorkResource\Pages;

use App\Filament\Admin\Resources\ExtraWorkResource;
use App\Filament\Traits\RedirectsToListingPage;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExtraWork extends CreateRecord
{

    use RedirectsToListingPage;

    protected static string $resource = ExtraWorkResource::class;
}
