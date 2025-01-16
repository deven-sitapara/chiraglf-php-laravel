<?php

namespace App\Filament\Admin\Resources\ExtraWorkResource\Pages;

use App\Filament\Admin\Resources\ExtraWorkResource\ExtraWorkResource;
use App\Filament\Admin\Traits\RedirectsToListingPage;
use Filament\Resources\Pages\CreateRecord;

class CreateExtraWork extends CreateRecord
{

    use RedirectsToListingPage;

    protected static string $resource = ExtraWorkResource::class;
}
