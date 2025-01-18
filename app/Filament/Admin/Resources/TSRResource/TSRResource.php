<?php

namespace App\Filament\Admin\Resources\TSRResource;

use App\Filament\Admin\Resources\FileResource\FileResource;
use App\Models\TSR;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TSRResource extends Resource
{
    protected static ?string $model = TSR::class;

    protected static ?string $navigationIcon = 'heroicon-m-document-currency-bangladeshi';

    protected static ?string $navigationLabel = 'TSRs';
    protected static ?string $slug = 'tsrs';
    protected static ?string $breadcrumb = 'TSRs';
    protected static ?string $navigationGroup = 'File';
    public static ?int $navigationSort = 2; // Adjust the number to set the order



    public static function form(Form $form): Form
    {
        return self::common_form($form);
    }

    public static function common_form(Form $form, bool $disableForeignKeys = false): Form
    {

        return $form
            ->schema([
                FileResource::getFileIdField($disableForeignKeys),
                TextInput::make('tsr_number')
                    ->helperText('Auto generated after record saved')
                    ->disabled(),
                DatePicker::make('date')
                    ->required()
                    ->default(now()),
            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('file.id')->label('File Number'),
                TextColumn::make('tsr_number')->label('TSR Number'),
                TextColumn::make('date')->date(),
            ])
            //            ->filters([
            //                SelectFilter::make('file_id')
            //                    ->relationship('file', 'file_number'),
            //            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New TSR')
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
            //            'create' => Pages\CreateTSR::route('/create'),
            //            'edit' => Pages\EditTSR::route('/{record}/edit'),
        ];
    }
}
