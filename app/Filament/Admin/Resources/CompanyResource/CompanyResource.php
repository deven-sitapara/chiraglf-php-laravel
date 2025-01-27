<?php

namespace App\Filament\Admin\Resources\CompanyResource;

use App\Helpers\OneDriveFileHelper;
use App\Models\Company;
use App\Services\OneDriveService;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;
    protected static ?string $navigationIcon = 'heroicon-c-building-library';
    protected static ?string $navigationGroup = 'Settings';
    public static ?int $navigationSort = 9; // Adjust the number to set the order


    protected static array $allowedFileTypes =
    [
        'application/octet-stream',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', /*  Newer Word Format (.docx): */
        'application/msword', /*  Older Word Format (.doc): */
    ];

    public static function uploadFileComponent($form, $fieldName, $label)
    {
 
        return Forms\Components\FileUpload::make($fieldName)
            ->label($label)
            ->nullable()
            ->downloadable()
            // ->moveFiles()
            ->previewable(true)
            // ->acceptedFileTypes(self::$allowedFileTypes)
            ->directory($fieldName)
            ->getUploadedFileNameForStorageUsing(
                fn(TemporaryUploadedFile $file): string => self::getNewFileName(
                    \Illuminate\Support\Str::slug($form->getRawState()['name']),
                    $fieldName,
                    $file->getClientOriginalExtension()
                )
            )
            ->afterStateUpdated(self::uploadToOneDrive($form, $fieldName));
    }

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
                                self::uploadFileComponent($form, 'tsr_file_format', 'TSR File Default Format'),
                                self::uploadFileComponent($form, 'document_file_format', 'Document File Default Format'),
                                self::uploadFileComponent($form, 'vr_file_format', 'VR File Default Format'),
                                self::uploadFileComponent($form, 'search_file_format', 'Search File Default Format'),
                                self::uploadFileComponent($form, 'ew_file_format', 'Extra Work File Default Format'),


                            ]),
                    ]),

            ]);
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

    public static function getUploadPath($directory, $newFileName)
    {
        return  "{$directory}/{$newFileName}";
    }
    public static function getNewFileName($companySlug, $type, $extension)
    {
        return "{$companySlug}-{$type}.{$extension}";
    }


    public static function uploadToOneDrive($form, $type, $directory = 'company_formats'): Closure
    {

        return function ($state) use ($form, $type, $directory) {

            // dd($state);

            // Check if file exists and is valid
            if (!$state instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                return null;
            }

            $companySlug = \Illuminate\Support\Str::slug($form->getRawState()['name']);
            $extension = $state->getClientOriginalExtension();

            try {

                $localPath = $state->getRealPath();
                $newFileName = self::getNewFileName($companySlug, $type, $extension);
                $oneDrivePath = self::getUploadPath($directory, $newFileName);

                return OneDriveFileHelper::storeFile(
                    $localPath,
                    $oneDrivePath,
                    false
                );
            } catch (\Exception $e) {
                // Log error
                return null;
            }
        };
    }
}
