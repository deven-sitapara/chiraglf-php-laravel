<?php

namespace App\Filament\Admin\Resources\SearchResource;

use App\Filament\Admin\Resources\FileResource\FileResource;
use App\Forms\Components\OneDriveFileUpload;
use App\Helpers\OneDriveFileHelper;
use App\Models\Search;
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

    public static function table(Table $table, bool $disableForeignKeys = false): Table
    {
        return $table
            ->columns(
                [

                    TextColumn::make('file.id')->label('File Number'),
                    TextColumn::make('search_number')->label('Search Number'),
                    TextColumn::make('date'),
                    TextColumn::make('years_required'),
                ]
            )
            ->filters([
                //
            ])
            ->heading('Searches')
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New Search')->modelLabel('New Search')

            ])
            ->actions([

                Tables\Actions\Action::make('search_url')
                    ->label('')
                    ->tooltip('Open Search File')
                    ->icon('heroicon-o-eye')
                    ->hidden(function ($record) {
                        return !$record->search_url;
                    })
                    ->url(fn($record) => $record->search_url)
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

                Tables\Actions\ActionGroup::make(
                    [
                        // - Generate Search
                        // - Search 1 Upload
                        // - DS Report Upload


                        Tables\Actions\Action::make('generate_file') // Generate Search
                            ->label('Generate Search File')
                            ->icon('heroicon-o-document')
                            ->hidden(function ($record) {
                                return $record->search_url;
                            })
                            ->requiresConfirmation('Are you sure you want to generate Search file?')
                            ->action(self::generateSearchFile())
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
                                        ->directory('Searches')
                                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $record) {
                                            return 'Searches-Search1-' . $record->search_number . '.' . $file->getClientOriginalExtension();
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
                                        ->directory('Searches')
                                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $record) {
                                            return 'Searches-DS-' . $record->search_number . '.' . $file->getClientOriginalExtension();
                                        })
                                ]))
                            ->icon('heroicon-s-document-arrow-up'),





                    ]
                ),

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


    public static function  generateSearchFile()
    {

        return function ($record) {

            //step 1 get file content from tsr_file_format of selected company of current tsr file id record
            $company = $record->file->company;
            $search_file_format_local_path = storage_path("app/public/" . $company->search_file_format); // tsr_file_format/testing-company-tsr_file_format.docx this is default document format
            Log::info($search_file_format_local_path);

            // get file extension from file name using laravel

            $extension = (new File($search_file_format_local_path))->extension();

            // step 2 copy file to create new tsrs/{tsr_number}.docx file
            $oneDrivePath = "Searches/{$record->search_number}." . $extension;

            // step 3 upload to onedrive

            $fileData = OneDriveFileHelper::storeFile($search_file_format_local_path, $oneDrivePath, false);

            Log::info($fileData);
            // step 4 get sharable link


            $record->update([
                'search_path' => $fileData['path'],
                'search_url' => $fileData['webUrl']
            ]);
        };
    }
}
