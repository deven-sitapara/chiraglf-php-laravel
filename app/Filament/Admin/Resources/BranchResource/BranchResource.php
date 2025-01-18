<?php

namespace App\Filament\Admin\Resources\BranchResource;

use App\Models\Branch;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Settings';

    public static ?int $navigationSort = 8; // Adjust the number to set the order



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('branch_name')->required()->maxLength(255),
                TextInput::make('person_name')->required()->maxLength(255),
                TextInput::make('address')->maxLength(500),
                TextInput::make('contact_number')->tel()->maxLength(15),
                TextInput::make('email')->email()->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch_name')->sortable()->searchable(),
                TextColumn::make('person_name')->sortable()->searchable(),
                TextColumn::make('contact_number')->label('Contact'),
                TextColumn::make('email'),
                // TextColumn::make('created_at')->dateTime()->label('Created At'),
            ])
            ->heading("Branches")
            ->paginated(false)
            ->filters([
                Tables\Filters\Filter::make('branch_name')->label('Branch Name'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
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
            'index' => Pages\ListBranches::route('/'),
            'create' => Pages\CreateBranch::route('/create'),
            'edit' => Pages\EditBranch::route('/{record}/edit'),
        ];
    }
}
