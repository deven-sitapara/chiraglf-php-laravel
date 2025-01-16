<?php

namespace App\Filament\Admin\Resources\FileResource\RelationManagers;

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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('file_id')
                    ->relationship('file', 'file_number')
                    ->label('File Number')
                    ->required(),
                TextInput::make('vr_number')
                    ->disabled(),
                DatePicker::make('date')
                    ->required()
                    ->default(now()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('VRs')
            ->columns([
                TextColumn::make('file.file_number')->label('File Number'),
                TextColumn::make('vr_number')->label('VR Number'),
                TextColumn::make('date')->date(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
