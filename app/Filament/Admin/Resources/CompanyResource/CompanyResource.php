<?php

namespace App\Filament\Admin\Resources\CompanyResource;

use App\Models\Company;
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
        'application/msword', /*  Older Word Format (.doc): */
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' /*  Newer Word Format (.docx): */
    ];




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
                                    ->minItems(1)
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

                                Forms\Components\FileUpload::make('tsr_file_format')
                                    ->label('TSR File Default Format')
                                    ->nullable()
                                    ->downloadable()
                                    ->moveFiles()
                                    ->acceptedFileTypes(self::$allowedFileTypes)
                                    //->storeFiles(false)  # Preventing files from being stored permanently
                                    ->directory('tsr_file_format')
                                    ->getUploadedFileNameForStorageUsing(
                                        fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                            ->prepend(
                                                'tsr_file_format-'
                                                    . now()->format('Y-m-d-H-i-s')
                                                    . '-'
                                                    . \Illuminate\Support\Str::slug($form->getRawState()['name'])  /* Company Name */
                                            ),
                                    ),
                                Forms\Components\FileUpload::make('document_file_format')
                                    ->label('Document File Default Format')
                                    ->nullable()
                                    ->downloadable()
                                    ->moveFiles()
                                    ->acceptedFileTypes(self::$allowedFileTypes)
                                    //->storeFiles(false)  # Preventing files from being stored permanently
                                    ->directory('document_file_format')
                                    ->getUploadedFileNameForStorageUsing(
                                        fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                            ->prepend(
                                                'document_file_format-'
                                                    . now()->format('Y-m-d-H-i-s')
                                                    . '-'
                                                    . \Illuminate\Support\Str::slug($form->getRawState()['name']) /* Company Name */
                                            ),
                                    ),
                                Forms\Components\FileUpload::make('vr_file_format')
                                    ->label('VR File Default Format')
                                    ->nullable()
                                    ->downloadable()
                                    ->moveFiles()
                                    ->acceptedFileTypes(self::$allowedFileTypes)
                                    //->storeFiles(false)  # Preventing files from being stored permanently
                                    ->directory('vr_file_format')
                                    ->getUploadedFileNameForStorageUsing(
                                        fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                            ->prepend(
                                                'vr_file_format-'
                                                    . now()->format('Y-m-d-H-i-s')
                                                    . '-'
                                                    . \Illuminate\Support\Str::slug($form->getRawState()['name']) /* Company Name */
                                            ),
                                    ),
                                Forms\Components\FileUpload::make('search_file_format')
                                    ->label('Search File Default Format')
                                    ->nullable()
                                    ->downloadable()
                                    ->moveFiles()
                                    ->acceptedFileTypes(self::$allowedFileTypes)
                                    //->storeFiles(false)  # Preventing files from being stored permanently
                                    ->directory('search_file_format')
                                    ->getUploadedFileNameForStorageUsing(
                                        fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                            ->prepend(
                                                'search_file_format-'
                                                    . now()->format('Y-m-d-H-i-s')
                                                    . '-'
                                                    . \Illuminate\Support\Str::slug($form->getRawState()['name']) /* Company Name */
                                            ),
                                    ),
                                Forms\Components\FileUpload::make('ew_file_format')
                                    ->label('Extra Work File Default Format')
                                    ->nullable()
                                    ->downloadable()
                                    ->moveFiles()
                                    ->acceptedFileTypes(self::$allowedFileTypes)
                                    //->storeFiles(false)  # Preventing files from being stored permanently
                                    ->directory('ew_file_format')
                                    ->getUploadedFileNameForStorageUsing(
                                        fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                            ->prepend(
                                                'ew_file_format-'
                                                    . now()->format('Y-m-d-H-i-s')
                                                    . '-'
                                                    . \Illuminate\Support\Str::slug($form->getRawState()['name']) /* Company Name */
                                            ),
                                    )
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
}
