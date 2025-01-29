<?php

namespace App\Helpers;

use Closure;
use Exception;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FileHelper
{
    protected static array $allowedFileTypes =
    [
        'application/octet-stream',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', /*  Newer Word Format (.docx): */
        'application/msword', /*  Older Word Format (.doc): */
    ];

    public static function getNewFileNameWithPrefix($prefix, $fieldName, $extension)
    {
        return "{$prefix}-{$fieldName}.{$extension}";
    }

    public static function getNewFileName($fieldName, $extension)
    {
        return "{$fieldName}.{$extension}";
    }

    public static function getUploadPath($directory, $newFileName)
    {
        return  "{$directory}/{$newFileName}";
    }


    public static function fileUploadComponent($fieldName, $urlFieldName, $label, $directory = '')
    {
        return  FileUpload::make($fieldName)
            ->label($label)
            ->nullable()
            ->downloadable()
            // ->moveFiles()
            ->previewable(true)
            // ->acceptedFileTypes(self::$allowedFileTypes)
            ->directory($directory)
            ->getUploadedFileNameForStorageUsing(
                fn(TemporaryUploadedFile $file): string => self::getNewFileName(
                    $fieldName,
                    $file->getClientOriginalExtension()
                )
            )
            ->afterStateUpdated(function ($state, $record) use ($fieldName, $directory, $urlFieldName) {

                // Check if file exists and is valid
                if (!$state instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                    return null;
                }
                $extension = $state->getClientOriginalExtension();
                $localPath = $state->getRealPath();

                $uploadFileArray = self::uploadToOneDrive($localPath, $fieldName, $extension, $directory);

                if ($uploadFileArray) {
                    // $set('tsr_file_format_url', $uploadFileArray['webUrl']);
                    $record->update([
                        "{$fieldName}" => $uploadFileArray['path'],
                        "{$urlFieldName}" => $uploadFileArray['webUrl']
                    ]);
                }
            });
    }



    public static function uploadToOneDrive($localPath, $type, $extension, $directory = ''): array | Exception
    {

        try {

            $newFileName = self::getNewFileName($type, $extension);
            $oneDrivePath = self::getUploadPath($directory, $newFileName);

            return OneDriveFileHelper::storeFile(
                $localPath,
                $oneDrivePath,
                false
            );
        } catch (\Exception $e) {
            // Log error
            return null;
        }
    }
}
