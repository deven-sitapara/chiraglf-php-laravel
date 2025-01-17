<?php

namespace App\Filament\Admin\Resources\ExtraWorkResource\Pages;

use App\Filament\Admin\Resources\ExtraWorkResource\ExtraWorkResource;
use App\Filament\Admin\Traits\RedirectsToListingPage;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExtraWork extends EditRecord
{
    use RedirectsToListingPage;

    protected static string $resource = ExtraWorkResource::class;


}
