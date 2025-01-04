<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\VRResource\Pages;
use App\Filament\Admin\Resources\VRResource\RelationManagers;
use App\Models\VR;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VRResource extends Resource
{
    protected static ?string $model = VR::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'VRs';
    protected static ?string $slug = 'vrs';
    protected static ?string $breadcrumb = 'VRs';
    protected static ?string $navigationGroup = 'File';


    public static function getNavigationSort(): ?int
    {
        // get from config
        return config('modelConfig.models.VR.navigation_sort');
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
            'index' => Pages\ListVRS::route('/'),
            'create' => Pages\CreateVR::route('/create'),
            'edit' => Pages\EditVR::route('/{record}/edit'),
        ];
    }
}
