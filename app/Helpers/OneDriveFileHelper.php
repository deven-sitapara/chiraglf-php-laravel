<?php

namespace App\Helpers;

use App\Services\OneDriveService;
use Illuminate\Support\Facades\Storage;

class OneDriveFileHelper
{


    public static function storeFile(
        string $localPath,
        string $oneDrivePath,
        bool $unlinkLocalFile = false
    ) {

        try {

            $uploadResult = (new OneDriveService())->uploadFileFromTemplate(
                $localPath,
                $oneDrivePath
            );

            // remove the file from the local storage
            $unlinkLocalFile && unlink($localPath);

            // output
            // return [
            //     'id' => $file->getId(),
            //     'name' => $file->getName(),
            //     'webUrl' => $file->getWebUrl(),
            //     'size' => $file->getSize()
            // ];
            return $uploadResult;
        } catch (\Exception $e) {
            // Log error
            return null;
        }
    }
}
