<?php

namespace App\Filament\Admin\Traits;

use Filament\Notifications\Notification;

trait HandleFileOperations
{
    // on before file record created on create document page
    protected function beforeCreateFileRecord($record)
    {
        // dd($record);
    }

    // on after file record edited
    protected function afterEditFileRecord($record)
    {
        // dd($record);
    }
}
