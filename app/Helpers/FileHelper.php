<?php

namespace App\Helpers;

use Closure;
use Exception;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Http\File;
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

    public static function generateFile($type)
    {
        return function ($record) use ($type) {
            try {
                $config = self::getFileConfig($type);

                // Get company and file path
                $company = $record->file->company;
                $format_local_path = storage_path("app/public/" . $company->{$config['format_field']});

                Log::info($format_local_path);

                $typeUpper = str($type)->upper();

                // Validate file existence
                if (!is_file($format_local_path) || !file_exists($format_local_path)) {

                    Notification::make()->danger()->title("{$typeUpper} Format File does not exist in company.")->send();
                    // throw new Exception('File does not exist');
                    return;
                }

                // Get file extension and create OneDrive path
                $extension = (new File($format_local_path))->extension();
                $oneDrivePath = "{$config['folder']}/{$record->{$config['number_field']}}.$extension";

                // Upload to OneDrive
                $fileData = OneDriveFileHelper::storeFile($format_local_path, $oneDrivePath, false);
                Log::info($fileData);

                // Update record
                $record->update([
                    $config['path_field'] => $fileData['path'],
                    $config['url_field'] => $fileData['webUrl']
                ]);

                Notification::make()->success()->title("{$typeUpper} File geneated succesfully ")->send();
            } catch (\Exception $e) {
                Log::error("File Generation Error: " . $e->getMessage());
                Notification::make()
                    ->danger()
                    ->title('Error generating file')
                    ->body($e->getMessage())
                    ->send();
            }
        };
    }

    protected static function getFileConfig($type)
    {
        $configs = [
            //file type
            'tsr' => [
                'format_field' => 'tsr_file_format', // company file format
                'folder' => 'TSRs',                // One Drive Folder
                'number_field' => 'tsr_number',     // Model number field ex. TSR:tsr_number
                'path_field' => 'tsr_file_id',     // One Drive path field to store path like TSRs/104-TS-1.docx or Searches/Searches-DS-104-SR-1.docx
                'url_field' => 'tsr_file_url'        // One Drive url field to store url like https://one-drive-url/TSRs/104-TS-1.docx
            ],
            'document' => [
                'format_field' => 'document_file_format', // company file format
                'folder' => 'Documents',                // One Drive Folder
                'number_field' => 'document_number',     // Model number field ex. TSR:tsr_number
                'path_field' => 'document_file_path',     // One Drive path field to store path like TSRs/104-TS-1.docx or Searches/Searches-DS-104-SR-1.docx
                'url_field' => 'document_file_url'        // One Drive url field to store url like https://one-drive-url/TSRs/104-TS-1.docx
            ],
            'extra_work' => [
                'format_field' => 'ew_file_format',
                'folder' => 'ExtraWorks',
                'number_field' => 'extra_work_number',
                'path_field' => 'ew_file_path',
                'url_field' => 'ew_file_url'
            ],
            'search' => [
                'format_field' => 'search_file_format',
                'folder' => 'Searches',
                'number_field' => 'search_number',
                'path_field' => 'search_path',
                'url_field' => 'search_url'
            ],
            'vr' => [
                'format_field' => 'vr_file_format',
                'folder' => 'VRs',
                'number_field' => 'vr_number',
                'path_field' => 'vr_file_path',
                'url_field' => 'vr_file_url'
            ]
        ];

        return $configs[strtolower($type)] ?? throw new \Exception('Invalid file type');
    }
}
