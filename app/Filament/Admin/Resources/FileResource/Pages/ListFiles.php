<?php

namespace App\Filament\Admin\Resources\FileResource\Pages;

use App\Filament\Admin\Resources\FileResource\FileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFiles extends ListRecords
{
    protected static string $resource = FileResource::class;
    protected static ?string $title = "";
}
