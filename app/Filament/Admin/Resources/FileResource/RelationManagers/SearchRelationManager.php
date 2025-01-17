<?php

namespace App\Filament\Admin\Resources\FileResource\RelationManagers;

use App\Filament\Admin\Resources\SearchResource\SearchResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SearchRelationManager extends RelationManager
{
    protected static string $relationship = 'Searches';

    public function form(Form $form): Form
    {
        return SearchResource::form($form,true);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Searches')
            ->columns(
                SearchResource::getTableColumns()
            )
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
