<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BranchResource\Pages;
use App\Filament\Admin\Resources\BranchResource\RelationManagers;
use App\Models\Branch;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Settings';


    public static function getNavigationSort(): ?int
    {
        // get from config
        return config('modelConfig.models.Branch.navigation_sort');
    }

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
                TextColumn::make('created_at')->dateTime()->label('Created At'),
            ])
            ->filters([
                Tables\Filters\Filter::make('branch_name')->label('Branch Name'),
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
