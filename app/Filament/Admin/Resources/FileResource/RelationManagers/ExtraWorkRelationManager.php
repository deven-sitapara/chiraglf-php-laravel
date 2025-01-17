<?php

namespace App\Filament\Admin\Resources\FileResource\RelationManagers;

use App\Filament\Admin\Resources\ExtraWorkResource\ExtraWorkResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExtraWorkRelationManager extends RelationManager
{
    protected static string $relationship = 'extra_works';

    public function form(Form $form): Form
    {
        return ExtraWorkResource::form($form,true);
    }

    public function table(Table $table): Table
    {
        return ExtraWorkResource::table($table);
    }
}
