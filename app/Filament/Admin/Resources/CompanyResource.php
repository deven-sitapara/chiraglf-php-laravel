<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CompanyResource\Pages;
use App\Filament\Admin\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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


                        try {
                            // Decode JSON to array
                            $array = json_decode($state, true);

                            // Check if decoding succeeded
                            if ($array === null) {
                                throw new Exception('Invalid JSON');
                            }

                            // Use Laravel collection to process
                            return   collect($array)->implode(', ');
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
