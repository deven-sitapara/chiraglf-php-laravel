<?php

namespace App\Filament\Admin\Resources\BTResource\Pages;

use App\Filament\Admin\Resources\BTResource\BTResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBTS extends ListRecords
{
    protected static string $resource = BTResource::class;

    public static ?string $title = '';
}
