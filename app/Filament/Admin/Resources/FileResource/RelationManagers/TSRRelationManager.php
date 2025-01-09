<?php

namespace App\Filament\Admin\Resources\FileResource\RelationManagers;

use App\Filament\Admin\Resources\TSRResource\Pages\ListTSRS;
use App\Models\TSR;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TSRsRelationManager extends RelationManager
{
    protected static string $relationship = 'tsrs';






    public function form(Form $form): Form
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
                DatePicker::make('date')
                    ->required()
                    ->default(now()),
            ]);
    }

    public function table(Table $table): Table
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

    // public static function getPages(): array
    // {
    //     return [
    //         'index' => ListTSRS::route('/'),
    //         'create' =>  ListTSRS::route('/create'),
    //         'edit' =>  ListTSRS::route('/{record}/edit'),
    //         'view' =>  ListTSRS::route('/{record}'),
    //         // 'create-trs' => '',

    //     ];
    // }
}
