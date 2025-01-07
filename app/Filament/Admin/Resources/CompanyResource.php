<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CompanyResource\Pages;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Settings';


    public static function getNavigationSort(): ?int
    {
        // get from config
        return config('modelConfig.models.Company.navigation_sort');
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
                Forms\Components\TextInput::make('tsr_fee')->label('TSR Fee')->required()->numeric()->maxLength(5),
                Forms\Components\TextInput::make('vr_fee')->label('VR Fee')->required()->numeric()->maxLength(5),
                Forms\Components\TextInput::make('document_fee')->label('Document Fee')->required()->numeric()->maxLength(5),
                Forms\Components\TextInput::make('bt_fee')->label('BT Fee')->required()->numeric()->maxLength(5),




                Forms\Components\FileUpload::make('tsr_file_format')
                    ->label('TSR File')
                    ->nullable(),
                Forms\Components\FileUpload::make('document_file_format')
                    ->label('Document File')
                    ->nullable(),
                Forms\Components\FileUpload::make('vr_file_format')
                    ->label('VR File')
                    ->nullable(),
                Forms\Components\FileUpload::make('search_file_format')
                    ->label('Search File')
                    ->nullable(),
                Forms\Components\FileUpload::make('ew_file_format')
                    ->label('Extra Work File')
                    ->nullable(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // add fields

                Tables\Columns\TextColumn::make('name')->label('Company Name'),
                Tables\Columns\TextColumn::make('emails')
                    ->label('Emails')
                    ->formatStateUsing(function ($state) {

                        $json = '[' . $state . ']';

                        try {
                            // Decode JSON to array
                            $array = json_decode($json, true);

                            // Check if decoding succeeded
                            if ($array === null) {
                                // throw new Exception('Invalid JSON');
                            }

                            // Use Laravel collection to process
                            return $commaSeparated = collect($array)
                                ->pluck('email') // Extract 'email' values
                                ->implode(',');  // Convert to comma-separated string

                            return $commaSeparated; // Output: test@test.com,dev@dev.com
                        } catch (Exception $e) {
                            return 'Error: ' . $e->getMessage();
                        }
                    }),
                Tables\Columns\TextColumn::make('tsr_fee')->label('TSR Fee'),
                Tables\Columns\TextColumn::make('vr_fee')->label('VR Fee'),
                Tables\Columns\TextColumn::make('document_fee')->label('Document Fee'),
                Tables\Columns\TextColumn::make('bt_fee')->label('BT Fee'),
                // Tables\Columns\TextColumn::make('created_at')->dateTime(),
                // Tables\Columns\TextColumn::make('updated_at')->dateTime(),



            ])
            ->filters([
                //
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
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
