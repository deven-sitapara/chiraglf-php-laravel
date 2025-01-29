<?php

namespace App\Filament\Admin\Resources\CompanyResource;

use App\Helpers\FileHelper;
use App\Helpers\OneDriveFileHelper;
use App\Models\Company;
use App\Services\OneDriveService;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;
    protected static ?string $navigationIcon = 'heroicon-c-building-library';
    protected static ?string $navigationGroup = 'Settings';
    public static ?int $navigationSort = 9; // Adjust the number to set the order



    public static function form(Form $form): Form
    {

        return $form
            ->columns(1)
            ->schema([
                //

                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Basic Information')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Company Name')
                                    ->required()
                                    ->maxLength(255),
                                //multiple emails
                                Forms\Components\Repeater::make('emails')
                                    ->label('Emails')
                                    ->schema([
                                        Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->unique()
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->minItems(0)
                                    ->maxItems(10)
                                    ->required(), // Ensure the emails field is required
                            ]),
                        Tabs\Tab::make('Price Information')
                            ->schema([

                                Forms\Components\TextInput::make('tsr_fee')->label('TSR Fee')->required()->numeric()->maxLength(5),
                                Forms\Components\TextInput::make('vr_fee')->label('VR Fee')->required()->numeric()->maxLength(5),
                                Forms\Components\TextInput::make('document_fee')->label('Document Fee')->required()->numeric()->maxLength(5),
                                Forms\Components\TextInput::make('bt_fee')->label('BT Fee')->required()->numeric()->maxLength(5),

                            ]),
                        Tabs\Tab::make('File Formats')
                            ->schema([

                                self::companyFileFormatUploadComponent('tsr_file_format', 'TSR File Default Format'),
                                self::companyFileFormatUploadComponent('document_file_format', 'Document File Default Format'),
                                self::companyFileFormatUploadComponent('vr_file_format', 'VR File Default Format'),
                                self::companyFileFormatUploadComponent('search_file_format', 'Search File Default Format'),
                                self::companyFileFormatUploadComponent('ew_file_format', 'Extra Work File Default Format'),

                            ]),
                    ]),

            ]);
    }

    public static function companyFileFormatUploadComponent($fieldName, $label)
    {
        // $_prefix = $prefix();
        return  FileUpload::make($fieldName)
            ->label($label)
            ->nullable()
            ->downloadable()
            // ->moveFiles()
            ->previewable(true)
            // ->acceptedFileTypes(self::$allowedFileTypes)
            ->directory('company_formats')
            ->getUploadedFileNameForStorageUsing(
                fn($get, TemporaryUploadedFile $file): string => self::getNewFileNameWithPrefix(
                    \Illuminate\Support\Str::slug($get('name')), // company name as prefix
                    $fieldName,
                    $file->getClientOriginalExtension()
                )
            )
            ->afterStateUpdated(self::companyUploadToOneDrive($fieldName, 'company_formats'));
    }

    public static function getNewFileNameWithPrefix($prefix, $fieldName, $extension)
    {
        return "{$prefix}-{$fieldName}.{$extension}";
    }

    public static function companyUploadToOneDrive($type, $directory = 'company_formats'): Closure
    {

        return function ($state, $get, $set, $record) use ($type, $directory) {

            // dd($state);

            // Check if file exists and is valid
            if (!$state instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                return null;
            }

            // Log::info("get('name')");
            // Log::info($get('name'));
            $_prefix = \Illuminate\Support\Str::slug($get('name')); // companyname
            $extension = $state->getClientOriginalExtension();

            try {

                $localPath = $state->getRealPath();
                $newFileName = self::getNewFileNameWithPrefix($_prefix, $type, $extension);
                $oneDrivePath = "{$directory}/{$newFileName}";


                $uploadFileArray = OneDriveFileHelper::storeFile(
                    $localPath,
                    $oneDrivePath,
                    false
                );

                if ($uploadFileArray) {
                    // $set('tsr_file_format_url', $uploadFileArray['webUrl']);
                    // $record->update([
                    //     'tsr_file_format_url' => $uploadFileArray['webUrl']
                    // ]);
                }
                // Log::info(__FILE__ . ' / ' . __FUNCTION__);
                // Log::info(print_r($uploadFileArray, true));
                // Log::info(print_r($state, true));

                return $uploadFileArray;
            } catch (\Exception $e) {
                // Log error
                return null;
            }
        };
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // add fields

                Tables\Columns\TextColumn::make('name')
                    ->label('Company Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('emails')->searchable()
                    ->label('Emails')
                    ->formatStateUsing(function ($state) {

                        $json = '[' . $state . ']';

                        try {
                            // Decode JSON to array
                            $array = json_decode($json, true);

                            // Check if decoding succeeded
                            if ($array === null) {
                                return null;
                                // throw new Exception('Invalid JSON');
                            }

                            // Use Laravel collection to process // Output: test@test.com,dev@dev.com
                            return $commaSeparated = collect($array)
                                ->pluck('email') // Extract 'email' values
                                ->implode(',');  // Convert to comma-separated string

                        } catch (\Exception $e) {
                            return 'Error: ' . $e->getMessage();
                        }
                    }),
                Tables\Columns\TextColumn::make('tsr_fee')->label('TSR Fee'),
                Tables\Columns\TextColumn::make('vr_fee')->label('VR Fee'),
                Tables\Columns\TextColumn::make('document_fee')->label('Document Fee'),
                Tables\Columns\TextColumn::make('bt_fee')->label('BT Fee'),
            ])
            ->filters([])
            ->searchDebounce('800ms')
            ->searchPlaceholder('Search (Company, Email)')
            ->heading('Companies')

            ->headerActions([

                Tables\Actions\CreateAction::make(),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCompanies::route('/'),
            //            'create' => Pages\CreateCompany::route('/create'),
            //            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
