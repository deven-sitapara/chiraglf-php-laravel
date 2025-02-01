<?php

namespace App\Filament\Admin\Resources\TSRResource;

use App\Filament\Admin\Resources\FileResource\FileResource;
use App\Forms\Components\OneDriveFileUpload;
use App\Helpers\FileHelper;
use App\Helpers\OneDriveFileHelper;
use App\Models\TSR;
use Exception;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class TSRResource extends Resource
{
    protected static ?string $model = TSR::class;

    protected static ?string $navigationIcon = 'heroicon-m-document-currency-bangladeshi';

    protected static ?string $navigationLabel = 'TSRs';
    protected static ?string $slug = 'tsrs';
    protected static ?string $breadcrumb = 'TSRs';
    protected static ?string $navigationGroup = 'File';
    public static ?int $navigationSort = 2; // Adjust the number to set the order

    public static function form(Form $form): Form
    {
        return self::common_form($form);
    }

    public static function common_form(Form $form, bool $disableForeignKeys = false): Form
    {

        return $form
            ->schema([
                FileResource::getFileIdField($disableForeignKeys),
                TextInput::make('tsr_number')
                    ->helperText('Auto generated after record saved')
                    ->disabled(),
                DatePicker::make('date')
                    ->required()
                    ->default(now()),

                //dont show in create
                FileHelper::fileUploadComponent('search1_file_id', 'search1_file_url', 'Search 1', 'Searches')
                    ->hidden(function ($record) {
                        return !$record;
                    }),
                FileHelper::fileUploadComponent('search2_file_id', 'search2_file_id', 'Search 2', 'Searches')
                    ->hidden(function ($record) {
                        return !$record;
                    }),
                FileHelper::fileUploadComponent('ds_file_id', 'ds_file_id', 'DS File', 'TSR-DS')
                    ->hidden(function ($record) {
                        return !$record;
                    })
            ]);
    }

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

    public static function table(Table $table): Table
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
                        ->action(FileHelper::generateFile('tsr'))
                        ->successNotificationTitle(
                            'TSR File Generated'
                        )
                        ->failureNotificationTitle(
                            'notification_error'
                        ),
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
                                OneDriveFileUpload::make('search1_file_id')
                                    ->label('Search 1')
                                    ->urlField('search1_file_url')
                                    ->directory('TSRs')
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $record) {
                                        return 'TSR-Search1-' . $record->tsr_number . '.' . $file->getClientOriginalExtension();
                                    })

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
                                OneDriveFileUpload::make('search2_file_id')
                                    ->label('Search 2')
                                    ->urlField('search2_file_url')
                                    ->directory('TSRs')
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $record) {
                                        return 'TSR-Search2-' . $record->tsr_number . '.' . $file->getClientOriginalExtension();
                                    })

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
                                OneDriveFileUpload::make('ds_file_id')
                                    ->label('DS File')
                                    ->urlField('ds_file_url')
                                    ->directory('DS')
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $record) {
                                        return 'TSR-DS-' . $record->tsr_number . '.' . $file->getClientOriginalExtension();
                                    })

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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTSRS::route('/'),
            //            'create' => Pages\CreateTSR::route('/create'),
            //            'edit' => Pages\EditTSR::route('/{record}/edit'),
        ];
    }
}
