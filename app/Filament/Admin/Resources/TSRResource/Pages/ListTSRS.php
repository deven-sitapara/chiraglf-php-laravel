<?php

namespace App\Filament\Admin\Resources\TSRResource\Pages;

use App\Filament\Admin\Resources\TSRResource\TSRResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTSRS extends ListRecords
{
    protected static string $resource = TSRResource::class;

    public static ?string $title = '';
}
