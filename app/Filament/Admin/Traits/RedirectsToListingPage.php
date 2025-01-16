<?php

namespace App\Filament\Admin\Traits;

use Filament\Notifications\Notification;

trait RedirectsToListingPage
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();

        // Return null to prevent the default notification
        return null;
    }
}
