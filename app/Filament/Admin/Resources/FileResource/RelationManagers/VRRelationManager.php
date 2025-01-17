<?php

namespace App\Filament\Admin\Resources\FileResource\RelationManagers;

use App\Filament\Admin\Resources\FileResource\FileResource;
use App\Filament\Admin\Resources\VRResource\VRResource;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VRRelationManager extends RelationManager
{
    protected static string $relationship = 'vrs';

    protected static ?string $title = 'VRs';

    public function form(Form $form, bool $disableForeignKeys = false ): Form
    {
        return VRResource::form($form, true);
    }

    public function table(Table $table): Table
    {
        return VRResource::table($table);
    }
}
