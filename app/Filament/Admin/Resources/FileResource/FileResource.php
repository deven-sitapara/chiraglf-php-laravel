<?php

namespace App\Filament\Admin\Resources\FileResource;

use App\Filament\Admin\Resources\FileResource\Actions\EmailAction;
use App\Filament\Admin\Resources\FileResource\RelationManagers\BTRelationManager;
use App\Filament\Admin\Resources\FileResource\RelationManagers\DocumentRelationManager;
use App\Filament\Admin\Resources\FileResource\RelationManagers\ExtraWorkRelationManager;
use App\Filament\Admin\Resources\FileResource\RelationManagers\SearchRelationManager;
use App\Filament\Admin\Resources\FileResource\RelationManagers\TSRRelationManager;
use App\Filament\Admin\Resources\FileResource\RelationManagers\VRRelationManager;
use App\Models\File;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
            ->columns([
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'login' => 'gray',
                        'queries' => 'warning',
                        'update' => 'info',
                        'handover' => 'success',
                        'close' => 'danger',
                    })
                    ->tooltip(fn($record): ?string => $record->status_message)
                    ->sortable(),
                Tables\Columns\TextColumn::make('file_number')
                    ->label('File No.')
                    ->searchable()
                    ->sortable()
       ,
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
                    ->label('Borrower')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('proposed_owner_name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('property_descriptions')
                    ->label('Address')
                    ->words(5)
                    ->wrap(true)
                    ->tooltip(function ($record): string {
                        return $record->property_descriptions;
                    })
                    ->searchable()
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
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
//                    Tables\Actions\Action::make('tsr')
//                        ->icon('heroicon-o-document')
//                        ->label('Open TSR')
//                        ->action(function ($record, ) {
//                            redirect("/tsr");
//                        }),
                    Tables\Actions\Action::make('handover')
                        ->color('success')
                        ->icon('heroicon-o-check-circle')
                        ->requiresConfirmation()
                        ->modalHeading('Handover File')
                        ->modalDescription('Are you sure you want to handover this file?')
                        ->action(function ($record) {
                            $record->update(['status' => 'handover']);
                        })

                ])
                    ->label('Actions')
                    ->size(ActionSize::Small),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc')
            ->defaultPaginationPageOption(25);
    }

    public static function getRelations(): array
    {
        return [
            TSRRelationManager::class,
            DocumentRelationManager::class,
            SearchRelationManager::class,
            ExtraWorkRelationManager::class,
            BTRelationManager::class,
            VRRelationManager::class,
        ];
    }


    public static function getFileIdField(bool $disabled = false){

        if($disabled){

            // dont allow file selection
            return Select::make('file_id')
                ->relationship('file', 'file_number')
                ->label('File Number')
                ->default(function (RelationManager $livewire  ) {
                    return $livewire->getOwnerRecord()->hasAttribute('id') ?  $livewire->getOwnerRecord()->getAttribute('id') : null;
                })
                ->disabled()
                ->required();

        }

        // allow file selection
        return Select::make('file_id')
            ->relationship('file', 'file_number')
            ->label('File Number')
            ->searchable(true)
            ->required();
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFiles::route('/'),
            'create' => Pages\CreateFile::route('/create'),
            'edit' => Pages\EditFile::route('/{record}/edit'),
//            'view' => Pages\ViewFile::route('/{record}'),
//            'tsr' => Pages\ViewFile::route('/{record}/tsr'),

        ];
    }
}
