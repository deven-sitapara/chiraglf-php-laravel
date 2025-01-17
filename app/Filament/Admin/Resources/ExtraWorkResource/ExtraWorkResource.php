<?php

namespace App\Filament\Admin\Resources\ExtraWorkResource;

use App\Filament\Admin\Resources\FileResource\FileResource;
use App\Models\ExtraWork;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class ExtraWorkResource extends Resource
{
    protected static ?string $model = ExtraWork::class;
    protected static ?string $navigationIcon = 'heroicon-m-document-plus';
    protected static ?string $navigationGroup = 'File';

    public static function getNavigationSort(): ?int
    {
        // get from config
        return config('modelConfig.models.ExtraWork.navigation_sort');
    }


    public static function getTableColumns(): array {
        return [
            TextColumn::make('file.id')->label('File Number'),
            TextColumn::make('extra_work_number')->label('Extra Work Number'),
            TextColumn::make('date'),
            TextColumn::make('customer_contact'),
            TextColumn::make('email'),
            TextColumn::make('work_details'),
            TextColumn::make('total_amount'),
            TextColumn::make('received_amount'),

        ];
    }

    public static function form(Form $form, bool $disableForeignKeys = false): Form
    {
        return $form
            ->schema([
                FileResource::getFileIdField($disableForeignKeys),
                TextInput::make('extra_work_number')
                    ->helperText(new HtmlString('Auto generates when saved.'))
                    ->disabled(),
                DatePicker::make('date')
                    ->required()
                    ->default(now()),
                TextInput::make('customer_contact'),
                TextInput::make('email')->email(true),
                TextInput::make('work_details'),
                TextInput::make('total_amount')->numeric(true),
                TextInput::make('received_amount')->numeric(true),
            ]);
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
            ->actions([
                Tables\Actions\ActionGroup::make([
                    //- New
                    //- Edit
                    //- Generate Extra Work,
                    //- Upload 1,
                    //- DS Report Upload
                    //- Upload Search 2
                    Action::make('generate_extra_work')->label('Generate Extra Work'),
                    Action::make('Upload 1')->label('Upload 1'),
                    Action::make('DS Report Upload')->label('DS Report Upload'),
                    Action::make('Upload Search 2')->label('Upload Search 2'),
                ]),
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New Extra Work')
                    ->modelLabel('New Extra Work')
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
//            'create' => Pages\CreateExtraWork::route('/create'),
//            'edit' => Pages\EditExtraWork::route('/{record}/edit'),
        ];
    }
}
