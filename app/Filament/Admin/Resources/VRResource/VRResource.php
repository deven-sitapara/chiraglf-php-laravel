<?php

namespace App\Filament\Admin\Resources\VRResource;

use App\Filament\Admin\Resources\FileResource\FileResource;
use App\Forms\Components\OneDriveFileUpload;
use App\Helpers\FileHelper;
use App\Helpers\OneDriveFileHelper;
use App\Models\VR;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class VRResource extends Resource
{
    protected static ?string $model = VR::class;

    protected static ?string $navigationIcon = 'heroicon-s-shield-check';
    protected static ?string $slug = 'vrs';
    protected static ?string $navigationGroup = 'File';
    public static ?int $navigationSort = 7; // Adjust the number to set the order
    protected static ?string $modelLabel = 'VR';
    protected static ?string $navigationLabel = 'VRs';


    public static function add_query_form(Form $form, $record): Form
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

    public static function form(Form $form, bool $disableForeignKeys = false): Form
    {
        return $form
            ->schema([

                FileResource::getFileIdField($disableForeignKeys),
                TextInput::make('vr_number')
                    ->helperText('Auto generated after record saved')
                    ->disabled(),
                DatePicker::make('date')
                    ->required()
                    ->default(now()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('VRs')
            ->columns([
                TextColumn::make('file.file_number')->label('File Number'),
                TextColumn::make('vr_number')->label('VR Number'),
                TextColumn::make('date')->date(),
                TextColumn::make('query')->label('Query')
                    ->badge()
                    ->words(20)
                    ->color('danger')
                    ->tooltip(function ($record): string {
                        return (string) $record->query;
                    })
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New VR')
            ])
            ->heading('VRs')
            ->modelLabel('VR')
            ->actions([

                Tables\Actions\Action::make('vr_file_url')
                    ->label('')
                    ->tooltip('Open VR File')
                    ->icon('heroicon-o-eye')
                    ->hidden(function ($record) {
                        return !$record->vr_file_url;
                    })
                    ->url(fn($record) => $record->vr_file_url)
                    ->openUrlInNewTab(),

                //ds file
                Tables\Actions\Action::make('ds_file_url')
                    ->label('')
                    ->tooltip('Open DS Report')
                    ->icon('heroicon-o-eye')
                    ->hidden(function ($record) {
                        return !$record->ds_file_url;
                    })
                    ->url(fn($record) => $record->ds_file_url)
                    ->openUrlInNewTab(),

                //- New
                //- Edit
                //- Generate VR / Open
                //- Add Queries
                //- DS Report Upload
                Tables\Actions\ActionGroup::make([

                    Tables\Actions\Action::make('vr_file_path') // Generate VR File
                        ->label('Generate VR File')
                        ->icon('heroicon-o-document')
                        ->hidden(function ($record) {
                            return $record->vr_file_url;
                        })
                        ->requiresConfirmation('Are you sure you want to generate VR file?')
                        ->action(FileHelper::generateFile('vr'))
                        ->successNotificationTitle('File generated successfully')
                        ->failureNotificationTitle('Failed to generate file'),

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
                                        return 'ExtraWorks-DS-' . $record->vr_number . '.' . $file->getClientOriginalExtension();
                                    })
                            ]))
                        ->icon('heroicon-s-document-arrow-up'),


                ]),
                Tables\Actions\EditAction::make(),
                //                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListVRS::route('/'),
        ];
    }
}
