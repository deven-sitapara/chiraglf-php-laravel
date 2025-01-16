<?php

namespace App\Filament\Admin\Resources\FileResource\Pages;

use App\Filament\Admin\Resources\FileResource\Actions\EditAction;
use App\Filament\Admin\Resources\FileResource\FileResource;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewFile extends ViewRecord
{
    protected static string $resource = FileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Go back
            Action::make('back')
                ->label('Go Back')
                ->extraAttributes(['onclick' => 'window.history.back()'])
                ->button()
                ->color('secondary')
                ->icon('heroicon-o-arrow-left'),
            //
            // Create TSR

//            Action::make('createTSR')
//                ->label('Create TSR')
//                ->icon('heroicon-o-plus')
//                ->form([
//                    DatePicker::make('date')
//                        ->required()
//                        ->default(now()),
//                ])
//                ->action(function (array $data): void {
//                    $this->record->tsrs()->create([
//                        'date' => $data['date'],
//                    ]);
//                    $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
//                }),
            //
            // Create VR
//            Action::make('createVR')
//                ->label('Create VR')
//                ->icon('heroicon-o-plus')
//                ->form([
//                    DatePicker::make('date')
//                        ->required()
//                        ->default(now()),
//                ])
//                ->action(function (array $data): void {
//                    $this->record->vrs()->create([
//                        'date' => $data['date'],
//                    ]);
//                    $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
//                }),
            //
            // Edit File
            \Filament\Actions\EditAction::make()
                ->label('Edit File')
                ->icon('heroicon-o-pencil-square')

        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('File Details')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('id')->label('File Number'),
                                TextEntry::make('date')->date(),
                                TextEntry::make('company.name'),
                                TextEntry::make('company_reference_number')->label('Company Reference'),
                            ]),
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('branch.branch_name'),
                                TextEntry::make('borrower_name'),
                                TextEntry::make('proposed_owner_name'),
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'login' => 'gray',
                                        'queries' => 'warning',
                                        'update' => 'info',
                                        'handover' => 'success',
                                        'close' => 'danger',
                                    }),
                            ]),
                        Grid::make(2)->schema([
                            TextEntry::make('status_message'),
                            TextEntry::make('property_descriptions')
                        ]),
                    ]),


            ]);
    }
}
