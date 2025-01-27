<?php

namespace App\Filament\Admin\Resources\FileResource\RelationManagers;

use App\Filament\Admin\Resources\TSRResource\TSRResource;
use App\Helpers\OneDriveFileHelper;
use App\Services\OneDriveService;
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

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('file.file_number')->label('File Number'),
                TextColumn::make('tsr_number')->label('TSR Number'),
                TextColumn::make('date')->date(),
            ])
            ->actions([

                Tables\Actions\Action::make('tsr_file_url')
                    ->label('')
                    ->tooltip('Open TSR')
                    ->icon('heroicon-o-eye')
                    ->hidden(function ($record) {
                        return !$record->tsr_file_url;
                    })
                    ->url(fn($record) => $record->tsr_file_url)
                    ->openUrlInNewTab(),
                ActionGroup::make([


                    Tables\Actions\Action::make('generate_file')
                        ->label('Generate TSR File')
                        ->hidden(function ($record) {
                            return $record->tsr_file_id;
                        })
                        ->action(function ($record) {

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
                        }),
                    Tables\Actions\Action::make('open_tsr_file')
                        ->label('Open TSR File')
                        ->hidden(function ($record) {
                            return !$record->tsr_file_id;
                        })
                        ->url(function ($record) {
                            return $record->tsr_file_url;
                        })
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('search1_upload')->label('Search 1 Upload'),
                    Tables\Actions\Action::make('search2_upload')->label('Search 1 Upload'),
                    Tables\Actions\Action::make('search2_upload')->label('Add Query'),
                    Tables\Actions\Action::make('ds_report_upload')->label('DS Report Upload'),

                ]),
                Tables\Actions\EditAction::make(),
                //                 Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New TSR')
                    ->modelLabel('New TSR')
            ])
            ->emptyStateHeading('No TSR yet')
            ->emptyStateDescription('Once you create your first TSR, it will appear here.')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
