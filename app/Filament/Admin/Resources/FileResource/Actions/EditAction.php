<?php

namespace App\Filament\Admin\Resources\FileResource\Actions;

use Filament\Tables\Actions\Action;
use Filament\Support\Colors\Color;

class EditAction extends Action
{
    public static function make(?string $name = 'edit'): static
    {
        return parent::make($name)
            ->color('edit')
          ;
    }
}
