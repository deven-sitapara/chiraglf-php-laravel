<?php

namespace App\Services;

use Exception;
use League\OAuth2\Client\Provider\GenericProvider;
use Microsoft\Graph\Generated\Models\DriveItem;
use Microsoft\Graph\GraphServiceClient;
use Microsoft\Kiota\Authentication\Oauth\AuthorizationCodeContext;
use Microsoft\Kiota\Authentication\Oauth\ClientCredentialContext;

class OneDriveService
{
    private $graph;
    private $accessToken;
    private $drive_id;
    private $base_dir;
    private $root;

    public function __construct()
    {
        $this->initializeGraphClient();
    }

    private function initializeGraphClient(): void
    {
        //initialize drive id
        $this->drive_id = config('services.microsoft.drive_id');
        $this->root = config('services.microsoft.root', 'root:');
        $this->base_dir = config('services.microsoft.base_dir', 'chiraglf-webapp');

        $tokenRequestContext = new ClientCredentialContext(
            config('services.microsoft.tenant_id'),
            config('services.microsoft.client_id'),
            config('services.microsoft.client_secret')
        );


        $this->graph = new GraphServiceClient($tokenRequestContext);
    }

    //private helper functions
    private function getFileDetails($file): array | null
    {

        if ($file) {
            return [
                'id' => $file->getId(),
                'name' => $file->getName(),
                'webUrl' => $file->getWebUrl(),
                'size' => $file->getSize()
            ];
        } else {
            // Log error or handle appropriately
            return null;
        }
    }

    public function getSharableLink($fileDetails): string
    {
        return $fileDetails['webUrl'];
    }

    public function getFileID($fileDetails): string
    {
        return $fileDetails['id'];
    }

    private function getDriveItemIdByPath($path)
    {

        return "{$this->root}/{$this->base_dir}/{$path}:";
    }

    // action methods
    public function uploadFileFromTemplate($localTemplatePath, $oneDrivePath): array | Exception
    {
        try {

            $driveItemId = $this->getDriveItemIdByPath($oneDrivePath);

            $inputStream = \GuzzleHttp\Psr7\Utils::streamFor(fopen($localTemplatePath, 'r'));

            $file = $this->graph->drives()->byDriveId($this->drive_id)->items()->byDriveItemId($driveItemId)->content()->put($inputStream)->wait();

            return $this->getFileDetails($file);
        } catch (\Exception $e) {
            // Log error
            throw new Exception('Error uploading file');
        }
    }
    public function uploadFile($localTemplatePath, $oneDrivePath): array | Exception
    {
        try {

            $driveItemId = $this->getDriveItemIdByPath($oneDrivePath);

            $inputStream = \GuzzleHttp\Psr7\Utils::streamFor(fopen($localTemplatePath, 'r'));

            $file = $this->graph->drives()->byDriveId($this->drive_id)->items()->byDriveItemId($driveItemId)->content()->put($inputStream)->wait();

            return $this->getFileDetails($file);
        } catch (\Exception $e) {
            // Log error
            throw new Exception('Error uploading file');
        }
    }

    public function deleteFile($fileId): bool | Exception
    {
        try {

            $this->graph->drives()
                ->byDriveId($this->drive_id)
                ->items()
                ->byDriveItemId($fileId)
                ->delete()
                ->wait();

            return true;
        } catch (\Exception $e) {
            // Log error
            throw new Exception('Error deleting file');
        }
    }

    public function getFileById($fileId): array | null
    {
        try {

            $file = $this->graph->drives()->byDriveId($this->drive_id)->items()->byDriveItemId($fileId)->get()->wait();

            return $this->getFileDetails($file);
        } catch (\Exception $e) {
            // Log error or handle appropriately
            throw new Exception('Error finding the file');
        }
    }
    public function getFileByPath($path): array | null
    {
        try {

            $fileId = $this->getDriveItemIdByPath($path);
            $file = $this->graph->drives()->byDriveId($this->drive_id)->items()->byDriveItemId($fileId)->get()->wait();

            return $this->getFileDetails($file);
        } catch (\Exception $e) {
            // Log error or handle appropriately
            throw new Exception('Error finding the file');
        }
    }

    public function listFiles($directory): array | null
    {
        try {

            $fileId = $this->getDriveItemIdByPath($directory);
            $files = $this->graph->drives()->byDriveId($this->drive_id)->items()->byDriveItemId($fileId)->children()->get()->wait();

            return $this->getFileDetails($files);
        } catch (\Exception $e) {
            // Log error or handle appropriately
            throw new Exception('Error listing files');
        }
    }
}
