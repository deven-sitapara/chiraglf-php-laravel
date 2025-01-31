<?php

namespace App\Filament\Admin\Resources\DocumentResource;

use App\Filament\Admin\Resources\FileResource\FileResource;
use App\Forms\Components\OneDriveFileUpload;
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
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
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

                Tables\Actions\Action::make('rr_file_url')
                    ->label('')
                    ->tooltip('Open RR File')
                    ->icon('heroicon-o-eye')
                    ->hidden(function ($record) {
                        return !$record->rr_file_url;
                    })
                    ->url(fn($record) => $record->rr_file_url)
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('stamp_duty_file_url')
                    ->label('')
                    ->tooltip('Open Stamp Duty File')
                    ->icon('heroicon-o-eye')
                    ->hidden(function ($record) {
                        return !$record->stamp_duty_file_url;
                    })
                    ->url(fn($record) => $record->stamp_duty_file_url)
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('token_file_url')
                    ->label('')
                    ->tooltip('Open Token File')
                    ->icon('heroicon-o-eye')
                    ->hidden(function ($record) {
                        return !$record->token_file_url;
                    })
                    ->url(fn($record) => $record->token_file_url)
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('appointment_file_url')
                    ->label('')
                    ->tooltip('Open Appointment File')
                    ->icon('heroicon-o-eye')
                    ->hidden(function ($record) {
                        return !$record->appointment_file_url;
                    })
                    ->url(fn($record) => $record->appointment_file_url)
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('reappointment_file_url')
                    ->label('')
                    ->tooltip('Open ReAppointment File')
                    ->icon('heroicon-o-eye')
                    ->hidden(function ($record) {
                        return !$record->reappointment_file_url;
                    })
                    ->url(fn($record) => $record->reappointment_file_url)
                    ->openUrlInNewTab(),



                Tables\Actions\ActionGroup::make(
                    [



                        //- New
                        //- Edit
                        //- RR Upload
                        //• Stamp Duty Upload
                        //• Token File Upload
                        //• Appointment File Upload
                        //• ReAppointment File Upload
                        Tables\Actions\Action::make('rr_upload')
                            ->label('RR File Upload')
                            ->modelLabel('RR File Upload')
                            // ->hidden(function ($record) {
                            //     return !$record->search1_file_url;
                            // })
                            ->form(fn($form) => $form
                                ->schema([
                                    OneDriveFileUpload::make('rr_file_path')
                                        ->label('RR File')
                                        ->urlField('rr_file_url')
                                        ->directory('Documents')
                                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $record) {
                                            return 'Documents-RR-' . $record->document_number . '.' . $file->getClientOriginalExtension();
                                        })
                                ]))
                            ->icon('heroicon-s-document-arrow-up'),
                        Tables\Actions\Action::make('stamp_duty_upload') // Stamp Duty Upload
                            ->label('Stamp Duty Upload')
                            ->modelLabel('Stamp Duty Upload')
                            // ->hidden(function ($record) {
                            //     return !$record->search1_file_url;
                            // })
                            ->form(fn($form) => $form
                                ->schema([
                                    OneDriveFileUpload::make('stamp_duty_file_path')
                                        ->label('Stamp Duty File')
                                        ->urlField('stamp_duty_file_url')
                                        ->directory('Documents')
                                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $record) {
                                            return 'Documents-StampDuty-' . $record->document_number . '.' . $file->getClientOriginalExtension();
                                        })
                                ]))
                            ->icon('heroicon-s-document-arrow-up'),
                        Tables\Actions\Action::make('token_file_upload') // Token File Upload
                            ->label('Token File Upload')
                            ->modelLabel('Token File Upload')
                            // ->hidden(function ($record) {
                            //     return !$record->search1_file_url;
                            // })
                            ->form(fn($form) => $form
                                ->schema([
                                    OneDriveFileUpload::make('token_file_path')
                                        ->label('Token File')
                                        ->urlField('token_file_url')
                                        ->directory('Documents')
                                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $record) {
                                            return 'Documents-Token-' . $record->document_number . '.' . $file->getClientOriginalExtension();
                                        })
                                ]))
                            ->icon('heroicon-s-document-arrow-up'),
                        Tables\Actions\Action::make('appointment_file_upload') // Appointment File Upload
                            ->label('Appointment File Upload')
                            ->modelLabel('Appointment File Upload')
                            // ->hidden(function ($record) {
                            //     return !$record->search1_file_url;
                            // })
                            ->form(fn($form) => $form
                                ->schema([
                                    OneDriveFileUpload::make('appointment_file_path')
                                        ->label('Appointment File')
                                        ->urlField('appointment_file_url')
                                        ->directory('Documents')
                                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $record) {
                                            return 'Documents-Appointment-' . $record->document_number . '.' . $file->getClientOriginalExtension();
                                        })
                                ]))
                            ->icon('heroicon-s-document-arrow-up'),
                        Tables\Actions\Action::make('reappointment_file_upload') // ReAppointment File Upload
                            ->label('ReAppointment File Upload')
                            ->modelLabel('ReAppointment File Upload')
                            // ->hidden(function ($record) {
                            //     return !$record->search1_file_url;
                            // })
                            ->form(fn($form) => $form
                                ->schema([
                                    OneDriveFileUpload::make('reappointment_file_path')
                                        ->label('ReAppointment File')
                                        ->urlField('reappointment_file_url')
                                        ->directory('Documents')
                                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $record) {
                                            return 'Documents-ReAppointment-' . $record->document_number . '.' . $file->getClientOriginalExtension();
                                        })
                                ]))
                            ->icon('heroicon-s-document-arrow-up'),


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
