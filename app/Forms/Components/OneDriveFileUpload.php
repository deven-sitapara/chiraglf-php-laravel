<?php

namespace App\Forms\Components;

use App\Helpers\OneDriveFileHelper;
use Closure;
use Exception;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\FileUpload;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class OneDriveFileUpload extends FileUpload
{
    // protected string $directory = null;
    protected string $urlField = '';
    protected Closure $fileNameCallback;

    public static function make(string $name): static
    {
        $static = parent::make($name);

        return $static
            ->nullable()
            ->downloadable()
            // ->moveFiles()
            ->previewable(true)
            // ->acceptedFileTypes(self::$allowedFileTypes)
            ->afterStateUpdated(function ($state, $record) use ($static) {

                // Check if file exists and is valid
                if (!$state instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                    return null;
                }
                $localPath = $state->getRealPath();
                $fieldName = $static->name;
                $directory = $static->directory;
                $urlFieldName = $static->urlField;


                $newFileName = $static->getUploadedFileNameForStorageUsing
                    ? $static->evaluate($static->getUploadedFileNameForStorageUsing, [
                        'file' => $state,
                        'record' => $record
                    ])
                    : $state->hashName();

                $oneDrivePath = "{$directory}/{$newFileName}";

                try {
                    $uploadFileArray =  OneDriveFileHelper::storeFile(
                        $localPath,
                        $oneDrivePath,
                        false
                    );

                    if ($uploadFileArray) {
                        // $set('tsr_file_format_url', $uploadFileArray['webUrl']);
                        $record->update([
                            "{$fieldName}" => $uploadFileArray['path'],
                            "{$urlFieldName}" => $uploadFileArray['webUrl']
                        ]);
                    }
                } catch (\Exception $e) {
                    // Log error
                    return null;
                }
            });
    }


    public function urlField(string $field): static
    {
        $this->urlField = $field;
        return $this;
    }
}
