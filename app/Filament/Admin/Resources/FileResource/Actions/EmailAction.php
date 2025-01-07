<?php

namespace App\Filament\Admin\Resources\FileResource\Actions;

use Filament\Tables\Actions\Action;
use Filament\Support\Colors\Color;

class EmailAction extends Action
{
    public static function make(?string $name = 'email'): static
    {
        return parent::make($name)
            ->color('info')
            ->icon('heroicon-o-envelope')
            ->requiresConfirmation()
            ->modalHeading('Send Email')
            ->modalDescription('Are you sure you want to send an email?')
            ->action(function ($record) {
                // Add your email sending logic here
            });
    }
}
