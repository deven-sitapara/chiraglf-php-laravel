<?php

namespace App\Filament\Admin\Resources\FileResource\RelationManagers;

use App\Filament\Admin\Resources\BTResource\BTResource;
use App\Filament\Admin\Resources\FileResource\FileResource;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class BTRelationManager extends RelationManager
{
    protected static string $relationship = 'bts';
    protected static ?string $title = 'BTs';  // More descriptive alternative


    public function form(Form $form): Form
    {
        return BTResource::form($form, true);
    }

    public function table(Table $table): Table
    {
        return BTResource::table($table);
    }
}
