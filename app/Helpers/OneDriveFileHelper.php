<?php

namespace App\Helpers;

use App\Services\OneDriveService;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OneDriveFileHelper
{


    public static function storeFile(
        string $localPath,
        string $oneDrivePath,
        bool $unlinkLocalFile = false
    ): array | Exception {

        try {

            $uploadFileArray = (new OneDriveService())->uploadFileFromTemplate(
                $localPath,
                $oneDrivePath
            );

            // remove the file from the local storage
            $unlinkLocalFile && unlink($localPath);

            // Log::info(__FILE__ . ' / ' . __FUNCTION__);
            // Log::info(print_r($uploadFileArray, true));
            // output
            // return [
            //     'id' => $file->getId(),
            //     'name' => $file->getName(),
            //     'webUrl' => $file->getWebUrl(),
            //     'size' => $file->getSize()
            // ];
            return $uploadFileArray;
        } catch (\Exception $e) {
            // Log error
            return null;
        }
    }
}
