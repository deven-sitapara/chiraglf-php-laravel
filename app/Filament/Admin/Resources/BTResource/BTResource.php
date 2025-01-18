<?php

namespace App\Filament\Admin\Resources\BTResource;

use App\Filament\Admin\Resources\FileResource\FileResource;
use App\Models\BT;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class BTResource extends Resource
{
    protected static ?string $model = BT::class;

    protected static ?string $navigationIcon = 'heroicon-c-academic-cap';


    protected static ?string $navigationLabel = 'BTs';
    protected static ?string $slug = 'bts';
    protected static ?string $breadcrumb = 'BTs';
    protected static ?string $navigationGroup = 'File';
    public static ?int $navigationSort = 6; // Adjust the number to set the order



    public static function form(Form $form, bool $disableForeignKeys = false): Form
    {
        return $form
            ->schema([
                FileResource::getFileIdField($disableForeignKeys),
                TextInput::make('document_number')
                    ->helperText(new HtmlString('Auto generates when saved.'))
                    ->disabled(),
                DatePicker::make('date')
                    ->required()
                    ->default(now()),
                Select::make('status')
                    ->required()
                    ->options([
                        'Login' => 'Login',
                        'Check Deposit' => 'Check Deposit',
                        'Paper Collection' => 'Paper Collection',
                    ]),
                TextInput::make('status_message')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //BT Number ( Auto Generate like #file-BT-1)
                //File Number  ( Foreign Key from File )
                //Date
                //Status (
                //- Login,
                //- Check Deposit,
                //- Paper Collection
                //)
                //Status Message

                TextColumn::make('file.id')->label('File Number'),
                TextColumn::make('bt_number')->label('BT Number'),
                TextColumn::make('date'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Login' => 'gray',
                        'Check Deposit' => 'warning',
                        'Paper Collection' => 'info',
                        'done' => 'success',
                    })
                    ->tooltip(fn($record): ?string => $record->status_message)
                    ->sortable()

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New BT')
                    ->modelLabel('New BT')
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
            //            'create' => Pages\CreateBT::route('/create'),
            //            'edit' => Pages\EditBT::route('/{record}/edit'),
        ];
    }
}
