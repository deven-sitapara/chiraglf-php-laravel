<?php

namespace App\Filament\Admin\Resources\ExtraWorkResource;

use App\Models\ExtraWork;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExtraWorkResource extends Resource
{
    protected static ?string $model = ExtraWork::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'File';

    public static function getNavigationSort(): ?int
    {
        // get from config
        return config('modelConfig.models.ExtraWork.navigation_sort');
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
            'index' => Pages\ListExtraWorks::route('/'),
            'create' => Pages\CreateExtraWork::route('/create'),
            'edit' => Pages\EditExtraWork::route('/{record}/edit'),
        ];
    }
}
