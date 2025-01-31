<?php

namespace App\Filament\Admin\Resources\ExtraWorkResource;

use App\Filament\Admin\Resources\FileResource\FileResource;
use App\Forms\Components\OneDriveFileUpload;
use App\Helpers\OneDriveFileHelper;
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
use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ExtraWorkResource extends Resource
{
    protected static ?string $model = ExtraWork::class;
    protected static ?string $navigationIcon = 'heroicon-m-document-plus';
    protected static ?string $navigationGroup = 'File';
    public static ?int $navigationSort = 5; // Adjust the number to set the order


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
                [
                    TextColumn::make('file.id')->label('File Number'),
                    TextColumn::make('extra_work_number')->label('Extra Work Number'),
                    TextColumn::make('date'),
                    TextColumn::make('customer_contact'),
                    TextColumn::make('email'),
                    TextColumn::make('work_details'),
                    TextColumn::make('total_amount'),
                    TextColumn::make('received_amount'),

                ]
            )
            ->filters([
                //
            ])
            ->heading('Extra Works')
            ->actions([

                // view files
                Tables\Actions\Action::make('ew_file_url')
                    ->label('')
                    ->tooltip('Open Extra Work File')
                    ->icon('heroicon-o-eye')
                    ->hidden(function ($record) {
                        return !$record->ew_file_url;
                    })
                    ->url(fn($record) => $record->ew_file_url)
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('search1_file_url')
                    ->label('')
                    ->tooltip('Open Search 1')
                    ->icon('heroicon-o-eye')
                    ->hidden(function ($record) {
                        return !$record->search1_file_url;
                    })
                    ->url(fn($record) => $record->search1_file_url)
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('ds_file_url')
                    ->label('')
                    ->tooltip('Open DS Report')
                    ->icon('heroicon-o-eye')
                    ->hidden(function ($record) {
                        return !$record->ds_file_url;
                    })
                    ->url(fn($record) => $record->ds_file_url)
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('search2_file_url')
                    ->label('')
                    ->tooltip('Open Search 2')
                    ->icon('heroicon-o-eye')
                    ->hidden(function ($record) {
                        return !$record->search2_file_url;
                    })
                    ->url(fn($record) => $record->search2_file_url)
                    ->openUrlInNewTab(),


                //Actions
                Tables\Actions\ActionGroup::make([
                    //- New
                    //- Edit
                    //- Generate Extra Work,
                    //- Upload Search 1,
                    //- DS Report Upload
                    //- Upload Search 2

                    Tables\Actions\Action::make('ew_file_path') // Generate Extra Work
                        ->label('Generate Extra Work File')
                        ->icon('heroicon-o-document')
                        ->hidden(function ($record) {
                            return $record->ew_file_url;
                        })
                        ->requiresConfirmation('Are you sure you want to generate Extra Work file?')
                        ->action(self::generateEWFile())
                        ->successNotificationTitle('File generated successfully')
                        ->failureNotificationTitle('Failed to generate file'),
                    Tables\Actions\Action::make('search1_file_path') // Search 1 File Upload
                        ->label('Search 1 Upload')
                        ->modelLabel('Search 1 File Upload')
                        // ->hidden(function ($record) {
                        //     return !$record->search1_file_url;
                        // })
                        ->form(fn($form) => $form
                            ->schema([
                                OneDriveFileUpload::make('search1_file_path')
                                    ->label('Search 1 File')
                                    ->urlField('search1_file_url')
                                    ->directory('ExtraWorks')
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $record) {
                                        return 'ExtraWorks-Search1-' . $record->extra_work_number . '.' . $file->getClientOriginalExtension();
                                    })
                            ]))
                        ->icon('heroicon-s-document-arrow-up'),
                    Tables\Actions\Action::make('ds_report_upload') // DS Report Upload
                        ->label('DS Report Upload')
                        ->modelLabel('DS Report File Upload')
                        // ->hidden(function ($record) {
                        //     return !$record->search1_file_url;
                        // })
                        ->form(fn($form) => $form
                            ->schema([
                                OneDriveFileUpload::make('ds_file_path')
                                    ->label('DS Report File')
                                    ->urlField('ds_file_url')
                                    ->directory('ExtraWorks')
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $record) {
                                        return 'ExtraWorks-DS-' . $record->extra_work_number . '.' . $file->getClientOriginalExtension();
                                    })
                            ]))
                        ->icon('heroicon-s-document-arrow-up'),
                    Tables\Actions\Action::make('search2_file_path') // Search 2 File Upload
                        ->label('Search 2 Upload')
                        ->modelLabel('Search 2 File Upload')
                        // ->hidden(function ($record) {
                        //     return !$record->search1_file_url;
                        // })
                        ->form(fn($form) => $form
                            ->schema([
                                OneDriveFileUpload::make('search2_file_path')
                                    ->label('Search 2 File')
                                    ->urlField('search2_file_url')
                                    ->directory('ExtraWorks')
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $record) {
                                        return 'ExtraWorks-Search2-' . $record->extra_work_number . '.' . $file->getClientOriginalExtension();
                                    })
                            ]))
                        ->icon('heroicon-s-document-arrow-up'),
                ]),
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New Extra Work')
                    ->modelLabel('New Extra Work')
                    ->createAnother(false)
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

    public static function  generateEWFile()
    {

        return function ($record) {

            //step 1 get file content from tsr_file_format of selected company of current tsr file id record
            $company = $record->file->company;
            $ew_format_local_path = storage_path("app/public/" . $company->ew_file_format); // tsr_file_format/testing-company-tsr_file_format.docx this is default document format
            Log::info($ew_format_local_path);

            // get file extension from file name using laravel

            $extension = (new File($ew_format_local_path))->extension();

            // step 2 copy file to create new tsrs/{tsr_number}.docx file
            $oneDrivePath = "ExtraWorks/{$record->extra_work_number}." . $extension;

            // step 3 upload to onedrive

            $fileData = OneDriveFileHelper::storeFile($ew_format_local_path, $oneDrivePath, false);

            Log::info($fileData);
            // step 4 get sharable link



            $record->update([
                'ew_file_path' => $fileData['path'],
                'ew_file_url' => $fileData['webUrl']
            ]);
        };
    }
}
