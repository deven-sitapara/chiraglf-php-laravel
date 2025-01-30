<?php

namespace App\Filament\Components;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Button;
use Filament\Notifications\Notification;
use Livewire\Component;

// TODO: to be use in future
class FileUploadComponent extends Component
{
    public $file;

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateUpdated(function ($state, $livewire) {
            if (!$state) return;

            try {
                $service = new OneDriveService();
                $uploadedFile = $service->upload($state->getRealPath(), $state->getClientOriginalName());

                $livewire->data['file_name'] = $state->getClientOriginalName();
                $livewire->data['one_drive_id'] = $uploadedFile['id'];
                $livewire->data['one_drive_path'] = $uploadedFile['webUrl'];
            } catch (\Exception $e) {
                $this->getContainer()->getForm()->addError('file', $e->getMessage());
            }
        });
    }

    protected function getFormSchema(): array
    {
        return [
            FileUpload::make('file')
                ->label('Upload File')
                ->required()
                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png']) // Specify accepted file types
                ->maxSize(1024) // Max size in KB
                ->reactive(),

        ];
    }

    public function uploadFile()
    {
        $this->validate([
            'file' => 'required|file|max:1024|mimes:pdf,jpeg,png', // Validation rules
        ]);

        try {
            // Use OneDriveService to handle the file upload
            $oneDriveService = new \App\Services\OneDriveService();
            $oneDriveService->upload($this->file);

            Notification::make()
                ->title('Success')
                ->body('File uploaded successfully!')
                ->success()
                ->send();

            $this->reset('file');
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Failed to upload file: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function render()
    {
        //ignore if not needed
        return view('filament.components.file-upload-component');
        // <div>
        //     {{ $this->form }}
        // </div>

    }
}
