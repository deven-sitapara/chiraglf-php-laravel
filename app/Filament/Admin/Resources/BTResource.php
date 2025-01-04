<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BTResource\Pages;
use App\Filament\Admin\Resources\BTResource\RelationManagers;
use App\Models\BT;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BTResource extends Resource
{
    protected static ?string $model = BT::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    protected static ?string $navigationLabel = 'BTs';
    protected static ?string $slug = 'bts';
    protected static ?string $breadcrumb = 'BTs';
    protected static ?string $navigationGroup = 'File';


    public static function getNavigationSort(): ?int
    {
        // get from config
        return config('modelConfig.models.BT.navigation_sort');
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
            'index' => Pages\ListBTS::route('/'),
            'create' => Pages\CreateBT::route('/create'),
            'edit' => Pages\EditBT::route('/{record}/edit'),
        ];
    }
}
