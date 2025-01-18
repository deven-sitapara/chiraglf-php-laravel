<?php

namespace App\Filament\Admin\Resources\SearchResource;

use App\Filament\Admin\Resources\FileResource\FileResource;
use App\Models\Search;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use phpDocumentor\Reflection\Types\Self_;

class SearchResource extends Resource
{
    protected static ?string $model = Search::class;
    protected static ?string $navigationIcon = 'heroicon-c-document-magnifying-glass';
    protected static ?string $navigationGroup = 'File';
    public static ?int $navigationSort = 3; // Adjust the number to set the order





    public static function form(Form $form, bool $disableForeignKeys = false): Form
    {
        return $form
            ->schema([
                FileResource::getFileIdField($disableForeignKeys),
                TextInput::make('search_number')
                    ->helperText(new HtmlString('Auto generates when saved.'))
                    ->disabled(),
                DatePicker::make('date')
                    ->required()
                    ->default(now()),

                TextInput::make('years_required')
                    ->numeric()
                    ->inputMode('decimal')
                    ->required()
            ]);
    }

    public static function getTableColumns(): array
    {
        return [
            TextColumn::make('file.id')->label('File Number'),
            TextColumn::make('search_number')->label('Search Number'),
            TextColumn::make('date'),
            TextColumn::make('years_required'),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(
                self::getTableColumns()
            )
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New Search')->modelLabel('New Search')

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
            //            'create' => Pages\CreateSearch::route('/create'),
            //            'edit' => Pages\EditSearch::route('/{record}/edit'),
        ];
    }
}
