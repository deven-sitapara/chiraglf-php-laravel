<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\FileResource\Pages;
use App\Filament\Admin\Resources\FileResource\RelationManagers;
use App\Filament\Admin\Resources\FileResource\Actions\EmailAction;
use App\Filament\Admin\Resources\FileResource\Actions\HandoverAction;
use App\Models\File;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ActionGroup;

class FileResource extends Resource
{
    protected static ?string $model = File::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'File';

    public static function getNavigationSort(): ?int
    {
        // get from config
        return config('modelConfig.models.File.navigation_sort');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->columns(2)
                    ->schema([
                        Forms\Components\DateTimePicker::make('date')
                            ->required()
                            ->readOnly()
                            ->default(now()),
                        Forms\Components\Select::make('branch_id')
                            ->relationship('branch', 'branch_name')
                            ->required()
                            ->preload(),

                        Forms\Components\Select::make('company_id')
                            ->relationship('company', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('company_reference_number')
                            ->required()
                            ->maxLength(255),
                    ]),

                Forms\Components\Section::make('Property Details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('borrower_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('proposed_owner_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('property_descriptions')
                            ->required()
                            ->placeholder('Enter detailed property description here...')
                            ->columnSpanFull()
                            ->maxLength(65535),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('file_number')
                    ->label('File No.')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('File number copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('branch.branch_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('borrower_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'login' => 'gray',
                        'queries' => 'warning',
                        'update' => 'info',
                        'handover' => 'success',
                        'close' => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(File::STATUS_OPTIONS),
                Tables\Filters\SelectFilter::make('branch')
                    ->relationship('branch', 'branch_name'),
                Tables\Filters\SelectFilter::make('company')
                    ->searchable(true)
                    ->relationship('company', 'name'),
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('date_from'),
                        Forms\Components\DatePicker::make('date_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })
            ])
            ->actions([
                ActionGroup::make([
                    EmailAction::make(),
                    HandoverAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
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
            'index' => Pages\ListFiles::route('/'),
            'create' => Pages\CreateFile::route('/create'),
            'edit' => Pages\EditFile::route('/{record}/edit'),
        ];
    }
}
