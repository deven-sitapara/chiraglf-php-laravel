<?php

namespace App\Filament\Admin\Resources\FileResource\Pages;

use App\Filament\Admin\Resources\FileResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;

class ViewFile extends ViewRecord
{
    protected static string $resource = FileResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('File Details')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('file_number')
                                    ->label('File Number'),
                                TextEntry::make('date')
                                    ->date(),
                                TextEntry::make('company_reference_number')
                                    ->label('Company Reference'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('borrower_name'),
                                TextEntry::make('proposed_owner_name'),
                            ]),
                        TextEntry::make('property_descriptions')
                            ->columnSpanFull(),
                    ]),

                Grid::make(2)
                    ->schema([
                        Section::make('Company Information')
                            ->schema([
                                TextEntry::make('company.name'),
                                TextEntry::make('branch.name'),
                            ]),
                        Section::make('Status')
                            ->schema([
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'login' => 'gray',
                                        'queries' => 'warning',
                                        'update' => 'info',
                                        'handover' => 'success',
                                        'close' => 'danger',
                                    }),
                                TextEntry::make('status_message'),
                            ]),
                    ]),
            ]);
    }
}
