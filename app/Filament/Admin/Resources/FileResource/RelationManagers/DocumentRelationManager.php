<?php

namespace App\Filament\Admin\Resources\FileResource\RelationManagers;

use App\Models\File;
use Barryvdh\Debugbar\Facades\Debugbar;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;

class DocumentRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

//    protected static ?string $badge = 'new';


    public function form(Form $form): Form
    {



//        Debugbar::info('record id' . print_r(request()->segments(),true));

        return $form
            ->schema([
                Select::make('file_id')
                    ->relationship('file', 'file_number')
                    ->label('File Number')
                    ->disabled()
                    ->default(fn (RelationManager $livewire) => $livewire->getOwnerRecord()->getAttribute('id'))  // This will get '101' from the URL
                    ->required(),
                TextInput::make('document_number')
                    ->helperText(new HtmlString('Auto generates when saved.'))
                    ->disabled(),
                DatePicker::make('date')
                    ->required()
                    ->default(now()),
                Select::make('type')
                    ->required()
                    ->options([
                        'MOD' => 'MOD',
                        'Release Deed' => 'Release Deed',
                        'Sale Deed' => 'Sale Deed',
                        'Declaration Deed' => 'Declaration Deed',
                        'Rectification Deed' => 'Rectification Deed',
                        'Other Documents' => 'Other Documents',

                    ]),
                TextInput::make('executing_party_name'),
                TextInput::make('executing_party_mobile'),
                TextInput::make('contact_person'),
                TextInput::make('contact_person_mobile'),


             ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Documents')
            ->columns([
                Tables\Columns\TextColumn::make('Documents'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
