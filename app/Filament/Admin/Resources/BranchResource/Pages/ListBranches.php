<?php

namespace App\Filament\Admin\Resources\BranchResource\Pages;

use App\Filament\Admin\Resources\BranchResource\BranchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBranches extends ListRecords
{
    protected static string $resource = BranchResource::class;

    protected static ?string $title = "";
}
