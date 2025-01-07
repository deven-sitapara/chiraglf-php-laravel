<?php

namespace App\Filament\Admin\Resources\FileResource\Actions;

use Filament\Tables\Actions\Action;
use Filament\Support\Colors\Color;

class HandoverAction extends Action
{
    public static function make(?string $name = 'handover'): static
    {
        return parent::make($name)
            ->color('success')
            ->icon('heroicon-o-check-circle')
            ->requiresConfirmation()
            ->modalHeading('Handover File')
            ->modalDescription('Are you sure you want to handover this file?')
            ->action(function ($record) {
                $record->update(['status' => 'handover']);
            });
    }
}
