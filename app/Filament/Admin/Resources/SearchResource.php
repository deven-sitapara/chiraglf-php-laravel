<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SearchResource\Pages;
use App\Filament\Admin\Resources\SearchResource\RelationManagers;
use App\Models\Search;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SearchResource extends Resource
{
    protected static ?string $model = Search::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'File';


    public static function getNavigationSort(): ?int
    {
        // get from config
        return config('modelConfig.models.Search.navigation_sort');
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSearches::route('/'),
            'create' => Pages\CreateSearch::route('/create'),
            'edit' => Pages\EditSearch::route('/{record}/edit'),
        ];
    }
}
