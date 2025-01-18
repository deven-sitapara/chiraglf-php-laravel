<?php

namespace App\Filament\Admin\Resources\VRResource;

use App\Filament\Admin\Resources\FileResource\FileResource;
use App\Models\VR;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VRResource extends Resource
{
    protected static ?string $model = VR::class;

    protected static ?string $navigationIcon = 'heroicon-s-shield-check';
    protected static ?string $title = 'VRs';

    protected static ?string $navigationLabel = 'VRs';
    protected static ?string $slug = 'vrs';
    protected static ?string $breadcrumb = 'VRs';

    protected static ?string $navigationGroup = 'File';
    public static ?int $navigationSort = 7; // Adjust the number to set the order






    public static function form(Form $form, bool $disableForeignKeys = false): Form
    {
        return $form
            ->schema([

                FileResource::getFileIdField($disableForeignKeys),
                TextInput::make('vr_number')
                    ->helperText('Auto generated after record saved')
                    ->disabled(),
                DatePicker::make('date')
                    ->required()
                    ->default(now()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('VRs')
            ->columns([
                TextColumn::make('file.file_number')->label('File Number'),
                TextColumn::make('vr_number')->label('VR Number'),
                TextColumn::make('date')->date(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New VR')->modelLabel('New VR')
            ])
            ->actions([
                //- New
                //- Edit
                //- Generate VR / Open
                //- Add Queries
                //- DS Report Upload
                Tables\Actions\ActionGroup::make([

                    Tables\Actions\Action::make('Generate VR')->label('Generate/Open VR'),
                    Tables\Actions\Action::make('Add Queries')->label('Add Queries'),
                    Tables\Actions\Action::make('DS Report Upload')->label('DS Report Upload'),
                ]),

                Tables\Actions\EditAction::make(),
                //                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListVRS::route('/'),
            'create' => Pages\CreateVR::route('/create'),
            'edit' => Pages\EditVR::route('/{record}/edit'),
        ];
    }
}
