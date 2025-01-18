<?php

namespace App\Filament\Admin\Resources\UserResource;

use App\Filament\Admin\Resources\UserResource\Pages\CreateUser;
use App\Filament\Admin\Resources\UserResource\Pages\EditUser;
use App\Filament\Admin\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Settings';
    public static ?int $navigationSort = 10; // Adjust the number to set the order

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('email')->email()->required(),
                TextInput::make('password')
                    ->password()
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->same('passwordConfirmation'),
                TextInput::make('passwordConfirmation')
                    ->password()
                    ->dehydrated(false)
                    ->required(fn(string $operation): bool => $operation === 'create'),
                Select::make('branch_id')
                    ->relationship('branch', 'branch_name')
                    ->nullable()
                    ->required(),
                Select::make('role')
                    ->options([
                        'Admin' => 'Admin',
                        'Manager' => 'Manager',
                        'Staff' => 'Staff',
                    ])
                    ->default('Staff')
                    ->required(),
            ]);
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('branch.branch_name')->label('Branch')->sortable(),
                TextColumn::make('role')->sortable(),
                TextColumn::make('created_at')->dateTime()->label('Created At'),
            ])
            ->filters([
                SelectFilter::make('branch_id')
                    ->relationship('branch', 'branch_name')
                    ->label('Branch'),
                SelectFilter::make('role')
                    ->options([
                        'Admin' => 'Admin',
                        'Manager' => 'Manager',
                        'Staff' => 'Staff',
                    ]),
            ])
            ->paginated(false)
            ->heading('Users')
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
            'index' => ListUsers::route('/'),
//            'create' => CreateUser::route('/create'),
//            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
