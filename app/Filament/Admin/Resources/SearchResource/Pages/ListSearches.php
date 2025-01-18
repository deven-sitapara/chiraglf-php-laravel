<?php

namespace App\Filament\Admin\Resources\SearchResource\Pages;

use App\Filament\Admin\Resources\SearchResource\SearchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSearches extends ListRecords
{
    protected static string $resource = SearchResource::class;
    protected static ?string $title = "";
}
