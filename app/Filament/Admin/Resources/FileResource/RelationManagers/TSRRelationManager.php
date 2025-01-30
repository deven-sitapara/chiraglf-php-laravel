<?php

namespace App\Filament\Admin\Resources\FileResource\RelationManagers;

use App\Filament\Admin\Resources\CompanyResource\CompanyResource;
use App\Filament\Admin\Resources\TSRResource\TSRResource;
use App\Helpers\FileHelper;
use App\Helpers\OneDriveFileHelper;
use App\Services\OneDriveService;
use Faker\Provider\ar_EG\Text;
use Filament\Actions\Action as ActionsAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Column;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TSRRelationManager extends RelationManager
{
    protected static string $relationship = 'tsrs';

    protected static ?string $title = 'TSRs';  // More descriptive alternative

    public function form(Form $form): Form
    {
        return TSRResource::common_form($form, true);
    }
    public function add_query_form(Form $form, $record): Form
    {
        return $form
            ->schema([
                Textarea::make('query')
                    ->label('Query')
                    ->required()
                    ->default($record->query)
                    ->placeholder('Enter query here...')
                    ->columnSpanFull()
                    ->maxLength(500)

            ]);
    }
    public static function search1_upload_form($form)
    {
        // Log::info(print_r($form->getState(), true));
        return $form
            ->schema([
                FileHelper::fileUploadComponent(
                    'search1_file_id',
                    'search1_file_url',
                    'Search 1',
                    'Searches'
                )
            ]);
    }



    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('file.file_number')->label('File Number'),
                TextColumn::make('tsr_number')->label('TSR Number'),
                TextColumn::make('date')->date(),
                TextColumn::make('query')->label('Query')
                    ->badge()
                    ->words(20)
                    ->color('danger')
                    ->tooltip(function ($record): string {
                        return (string) $record->query;
                    })
            ])
            ->actions([

                //TSR: open generated tsr file
                Tables\Actions\Action::make('tsr_file_url')
                    ->label('')
                    ->tooltip('Open TSR')
                    ->icon('heroicon-o-eye')
                    ->hidden(function ($record) {
                        return !$record->tsr_file_url;
                    })
                    ->url(fn($record) => $record->tsr_file_url)
                    ->openUrlInNewTab(),

                // SEARCH: open search1 file
                Tables\Actions\Action::make('search1_file_url')
                    ->label('')
                    ->tooltip('Open Search1')
                    ->icon('heroicon-c-document-magnifying-glass')
                    ->hidden(function ($record) {
                        return !$record->search1_file_url;
                    })
                    ->url(fn($record) => $record->search1_file_url)
                    ->openUrlInNewTab(),

                // SEARCH: open search2 file
                Tables\Actions\Action::make('search2_file_url')
                    ->label('')
                    ->tooltip('Open Search2')
                    ->icon('heroicon-c-document-magnifying-glass')
                    ->hidden(function ($record) {
                        return !$record->search2_file_url;
                    })
                    ->url(fn($record) => $record->search2_file_url)
                    ->openUrlInNewTab(),
                // DS: open ds file
                Tables\Actions\Action::make('ds_file_url')
                    ->label('')
                    ->tooltip('DS File')
                    ->icon('heroicon-s-finger-print')
                    ->hidden(function ($record) {
                        return !$record->ds_file_url;
                    })
                    ->url(fn($record) => $record->ds_file_url)
                    ->openUrlInNewTab(),

                //
                //Action
                ActionGroup::make([
                    Tables\Actions\Action::make('generate_file')
                        ->label('Generate TSR File')
                        ->icon('heroicon-o-document')
                        ->hidden(function ($record) {
                            return $record->tsr_file_id;
                        })
                        ->requiresConfirmation('Are you sure you want to generate TSR file?')
                        ->action(self::generateTSRFile()),
                    Tables\Actions\Action::make('open_tsr_file')
                        ->label('Open TSR File')
                        ->icon('heroicon-o-eye')
                        ->hidden(function ($record) {
                            return !$record->tsr_file_id;
                        })
                        ->url(function ($record) {
                            return $record->tsr_file_url;
                        })
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('search1_upload')
                        ->label('Search 1 Upload')
                        ->modelLabel('Search 1 Upload')
                        // ->hidden(function ($record) {
                        //     return !$record->search1_file_url;
                        // })
                        ->form(fn($form) => $form
                            ->schema([
                                FileHelper::fileUploadComponent(
                                    'search1_file_id',
                                    'search1_file_url',
                                    'Search 1',
                                    'Searches'
                                )
                            ]))
                        ->icon('heroicon-c-document-magnifying-glass'),
                    Tables\Actions\Action::make('search2_upload')
                        ->label('Search 2 Upload')
                        ->modelLabel('Search 2 Upload')
                        // ->hidden(function ($record) {
                        //     return !$record->search2_file_url;
                        // })
                        ->form(fn($form) => $form
                            ->schema([
                                FileHelper::fileUploadComponent(
                                    'search2_file_id',
                                    'search2_file_url',
                                    'Search 2',
                                    'Searches'
                                )
                            ]))
                        ->icon('heroicon-c-document-magnifying-glass'),
                    Tables\Actions\Action::make('ds_file_upload')
                        ->label('DS File Upload')
                        ->modelLabel('DS File Upload')
                        // ->hidden(function ($record) {
                        //     return !$record->search2_file_url;
                        // })
                        ->form(fn($form) => $form
                            ->schema([
                                FileHelper::fileUploadComponent(
                                    'ds_file_id',
                                    'ds_file_url',
                                    'DS File',
                                    'DS'
                                )
                            ]))
                        ->icon('heroicon-s-finger-print'),

                    Tables\Actions\Action::make('add_query')->label('Add Query')
                        ->modelLabel('Add Query')
                        ->form(fn($form, $record) => self::add_query_form($form, $record))
                        ->icon('heroicon-c-exclamation-triangle')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->hidden(fn($record) => !empty($record->query))
                        ->action(function ($data, $record) {
                            $record->update([
                                'query' => $data['query']
                            ]);
                        }),
                    Tables\Actions\Action::make('resolve_query')
                        ->label('Resolve Query')
                        ->icon('heroicon-c-exclamation-triangle')
                        ->requiresConfirmation()
                        ->hidden(fn($record) => empty($record->query))
                        ->color('success')
                        ->action(function ($record) {
                            $record->update([
                                'query' => null
                            ]);
                        })
                        ->hidden(fn($record) => empty($record->query)),
                    // Tables\Actions\Action::make('ds_report_upload')->label('DS Report Upload'),

                ]),
                Tables\Actions\EditAction::make()
                    ->modalHeading(fn($record) => 'Edit TSR #' . $record->tsr_number),
                //                 Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New TSR')
                    ->modalHeading('New TSR')

            ])
            ->emptyStateHeading('No TSR yet')
            ->emptyStateDescription('Once you create your first TSR, it will appear here.')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function  generateTSRFile()
    {

        return function ($record) {

            //step 1 get file content from tsr_file_format of selected company of current tsr file id record
            $company = $record->file->company;
            $tsr_format_local_path = storage_path("app/public/" . $company->tsr_file_format); // tsr_file_format/testing-company-tsr_file_format.docx this is default document format
            Log::info($tsr_format_local_path);

            // get file extension from file name using laravel

            $extension = (new File($tsr_format_local_path))->extension();

            // step 2 copy file to create new tsrs/{tsr_number}.docx file
            $oneDrivePath = "TSRs/{$record->tsr_number}." . $extension;

            // step 3 upload to onedrive

            $fileData = OneDriveFileHelper::storeFile($tsr_format_local_path, $oneDrivePath, false);

            Log::info($fileData);
            // step 4 get sharable link


            $fileUrl = (new OneDriveService())->getSharableLink($fileData);
            $fileId = (new OneDriveService())->getFileID($fileData);

            $record->update([
                'tsr_file_id' => $fileId,
                'tsr_file_url' => $fileUrl
            ]);
            // step 5 open in new tab
            Log::info($fileUrl);
            // return redirect()->away($fileUrl);
        };
    }
}
