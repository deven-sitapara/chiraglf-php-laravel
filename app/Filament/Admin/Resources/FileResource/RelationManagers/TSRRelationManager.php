<?php

namespace App\Filament\Admin\Resources\FileResource\RelationManagers;

use App\Filament\Admin\Resources\FileResource\Actions\EditAction;
use App\Filament\Admin\Resources\TSRResource\Pages\EditTSR;
use App\Filament\Admin\Resources\TSRResource\Pages\ListTSRS;
use App\Filament\Admin\Resources\TSRResource\TSRResource;
use App\Models\Deployment;
use App\Models\TSR;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\ComponentAttributeBag;

class TSRRelationManager extends RelationManager
{
    protected static string $relationship = 'tsrs';

    protected static ?string $title = 'TSRs';  // More descriptive alternative

    public function form(Form $form): Form
    {
        return TSRResource::common_form( $form, true);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('file.file_number')->label('File Number'),
                TextColumn::make('tsr_number')->label('TSR Number'),
                TextColumn::make('date')->date(),
            ])
             ->actions([

                 ActionGroup::make([
                     Tables\Actions\Action::make('generate_file')->label('Generate/Open TSR'),
                     Tables\Actions\Action::make('search1_upload')->label('Search 1 Upload'),
                     Tables\Actions\Action::make('search2_upload')->label('Search 1 Upload'),
                     Tables\Actions\Action::make('search2_upload')->label('Add Query'),
                     Tables\Actions\Action::make('ds_report_upload')->label('DS Report Upload'),

                 ]),
                 Tables\Actions\EditAction::make(),
//                 Tables\Actions\DeleteAction::make(),
             ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New TSR')
                ->modelLabel('New TSR')
            ])
            ->emptyStateHeading('No TSR yet')
            ->emptyStateDescription('Once you create your first TSR, it will appear here.')
             ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


}
