<?php

namespace App\Filament\Admin\Resources\DocumentResource;

use App\Filament\Admin\Resources\FileResource\FileResource;
use App\Models\Document;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use phpDocumentor\Reflection\Types\Self_;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;
    protected static ?string $navigationIcon = 'heroicon-s-clipboard-document';
    protected static ?string $navigationGroup = 'File';
    public static ?int $navigationSort = 4; // Adjust the number to set the order



    public static function form(Form $form, bool $disableForeignKeys = false): Form
    {

        //        Debugbar::info('record id' . print_r(request()->segments(),true));

        return $form
            ->schema([
                FileResource::getFileIdField($disableForeignKeys),
                TextInput::make('document_number')
                    ->helperText(new HtmlString('Auto generates when saved.'))
                    ->disabled(),
                DatePicker::make('date')
                    ->required()
                    ->default(now()),
                Select::make('type')
                    ->required()
                    ->options([
                        'MOD' => 'MOD',
                        'Release Deed' => 'Release Deed',
                        'Sale Deed' => 'Sale Deed',
                        'Declaration Deed' => 'Declaration Deed',
                        'Rectification Deed' => 'Rectification Deed',
                        'Other Documents' => 'Other Documents',

                    ]),
                TextInput::make('executing_party_name'),
                TextInput::make('executing_party_mobile'),
                TextInput::make('contact_person'),
                TextInput::make('contact_person_mobile'),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(
                [
                    TextColumn::make('file.id')->label('File Number'),
                    TextColumn::make('document_number')->label('Document Number'),
                    TextColumn::make('date'),
                    TextColumn::make('type'),
                    TextColumn::make('executing_party_name'),
                    TextColumn::make('executing_party_mobile'),
                    TextColumn::make('contact_person'),
                    TextColumn::make('contact_person_mobile')

                ]
            )
            ->filters([
                //
            ])
            ->heading('Documents')
            ->actions([

                Tables\Actions\ActionGroup::make(
                    [
                        //- New
                        //- Edit
                        //- RR Upload
                        //• Stamp Duty Upload
                        //• Token File Upload
                        //• Appointment File Upload
                        //• ReAppointment File Upload

                        Tables\Actions\Action::make('rr_upload')->label('RR Upload'),
                        Tables\Actions\Action::make('Stamp Duty Upload')->label('Stamp Duty Upload'),
                        Tables\Actions\Action::make('Token File Upload')->label('Token File Upload'),
                        Tables\Actions\Action::make('Appointment File Upload')->label('Appointment File Upload'),
                        Tables\Actions\Action::make('ReAppointment File Upload')->label('ReAppointment File Upload'),

                    ]
                ),
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New Document')
                    ->modelLabel('New Document')
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
            'index' => Pages\ListDocuments::route('/'),
            //            'create' => Pages\CreateDocument::route('/create'),
            //            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
