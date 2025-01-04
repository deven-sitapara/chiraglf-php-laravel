<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TSRResource\Pages;
use App\Filament\Admin\Resources\TSRResource\RelationManagers;
use App\Models\TSR;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TSRResource extends Resource
{
    protected static ?string $model = TSR::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'TSRs';
    protected static ?string $slug = 'tsrs';
    protected static ?string $breadcrumb = 'TSRs';
    protected static ?string $navigationGroup = 'File';


    public static function getNavigationSort(): ?int
    {
        // get from config
        return config('modelConfig.models.TSR.navigation_sort');
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('file_id')
                    ->relationship('file', 'file_number')
                    ->label('File Number')
                    ->required(),
                TextInput::make('tsr_number')
                    ->disabled()
                    ->default(fn() => '#file-TS-' . now()->timestamp),
                DatePicker::make('date')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('file.file_number')->label('File Number'),
                TextColumn::make('tsr_number')->label('TSR Number'),
                TextColumn::make('date')->date(),
            ])
            ->filters([
                SelectFilter::make('file_id')
                    ->relationship('file', 'file_number'),
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
            'index' => Pages\ListTSRS::route('/'),
            'create' => Pages\CreateTSR::route('/create'),
            'edit' => Pages\EditTSR::route('/{record}/edit'),
        ];
    }
}
