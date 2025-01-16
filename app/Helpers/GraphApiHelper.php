<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class GraphApiHelper
{
    private $baseUrl = 'https://graph.microsoft.com/v1.0';
    private $clientId;
    private $clientSecret;
    private $tenantId;
    protected $accessToken;

    public function __construct()
    {
        $this->clientId = env('GRAPH_CLIENT_ID');
        $this->clientSecret = env('GRAPH_CLIENT_SECRET');
        $this->tenantId = env('GRAPH_TENANT_ID');
        $this->accessToken = $this->_getAccessToken();
    }

    protected function _getAccessToken()
    {
        $url = "https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token";

        $response = Http::asForm()->post($url, [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'scope' => 'https://graph.microsoft.com/.default',
        ]);

//        print_r($response->json());
        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        throw new \Exception("Failed to authenticate with Microsoft Graph API.");
    }

    protected function _getHeaders()
    {
        return [
            'Authorization' => "Bearer {$this->accessToken}",
            'Content-Type' => 'application/json',
        ];
    }

    public function renameFile($fileId, $newName)
    {
        $url = "{$this->baseUrl}/me/drive/items/{$fileId}";
        $response = Http::withHeaders($this->_getHeaders())->patch($url, [
            'name' => $newName,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception("Failed to rename file: " . $response->body());
    }

    public function createDirectory($parentId, $folderName)
    {
        $url = "{$this->baseUrl}/me/drive/items/{$parentId}/children";
        $response = Http::withHeaders($this->_getHeaders())->post($url, [
            'name' => $folderName,
            'folder' => new \stdClass(),
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception("Failed to create directory: " . $response->body());
    }

    public function createBlankDocx($parentId, $fileName)
    {
        $url = "{$this->baseUrl}/me/drive/items/{$parentId}/children/{$fileName}:/content";
        $response = Http::withHeaders($this->_getHeaders())->put($url, '');

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception("Failed to create blank DOCX file: " . $response->body());
    }

    public function uploadPdf($parentId, $fileName, $filePath)
    {
        $url = "{$this->baseUrl}/me/drive/items/{$parentId}:/$fileName:/content";
        $fileContent = file_get_contents($filePath);

        $response = Http::withHeaders($this->_getHeaders())->put($url, $fileContent);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception("Failed to upload PDF file: " . $response->body());
    }

    public function getSharableLink($fileId)
    {
        $url = "{$this->baseUrl}/me/drive/items/{$fileId}/createLink";
        $response = Http::withHeaders($this->_getHeaders())->post($url, [
            'type' => 'view',
        ]);

        if ($response->successful()) {
            return $response->json()['link']['webUrl'];
        }

        throw new \Exception("Failed to generate sharable link: " . $response->body());
    }


    /**
     * List all root directories
     */
    public function listRootDirectories()
    {
        $url = "{$this->baseUrl}/me/drive/root/children";
        $response = Http::withHeaders($this->_getHeaders())
            ->get($url, [
                '$filter' => 'folder ne null',
                '$select' => 'id,name,folder,parentReference'
            ]);

        if ($response->successful()) {
            return $response->json()['value'];
        }

        throw new \Exception("Failed to list root directories: " . $response->body());
    }

    /**
     * List subdirectories of a specific directory
     */
    public function listSubDirectories($parentId)
    {
        $url = "{$this->baseUrl}/me/drive/items/{$parentId}/children";
        $response = Http::withHeaders($this->_getHeaders())
            ->get($url, [
                '$filter' => 'folder ne null',
                '$select' => 'id,name,folder,parentReference'
            ]);

        if ($response->successful()) {
            return $response->json()['value'];
        }

        throw new \Exception("Failed to list subdirectories: " . $response->body());
    }

    /**
     * List all files in a specific directory
     */
    public function listFiles($directoryId)
    {
        $url = "{$this->baseUrl}/me/drive/items/{$directoryId}/children";
        $response = Http::withHeaders($this->_getHeaders())
            ->get($url, [
                '$filter' => 'file ne null',
                '$select' => 'id,name,file,size,webUrl,lastModifiedDateTime'
            ]);

        if ($response->successful()) {
            return $response->json()['value'];
        }

        throw new \Exception("Failed to list files: " . $response->body());
    }

    /**
     * Get directory details including parent information
     */
    public function getDirectoryDetails($directoryId)
    {
        $url = "{$this->baseUrl}/me/drive/items/{$directoryId}";
        $response = Http::withHeaders($this->_getHeaders())
            ->get($url, [
                '$expand' => 'parentReference'
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception("Failed to get directory details: " . $response->body());
    }

    /**
     * Get parent directory hierarchy
     */
    public function getParentHierarchy($directoryId)
    {
        $hierarchy = [];
        $currentDir = $this->getDirectoryDetails($directoryId);

        while (isset($currentDir['parentReference']) && isset($currentDir['parentReference']['id'])) {
            $hierarchy[] = [
                'id' => $currentDir['id'],
                'name' => $currentDir['name']
            ];

            $currentDir = $this->getDirectoryDetails($currentDir['parentReference']['id']);
        }

        // Add root directory
        $hierarchy[] = [
            'id' => $currentDir['id'],
            'name' => $currentDir['name']
        ];

        return array_reverse($hierarchy);
    }

    /**
     * Search for files and folders
     */
    public function searchItems($query, $itemType = null)
    {
        $url = "{$this->baseUrl}/me/drive/root/search(q='{$query}')";

        $params = ['$select' => 'id,name,file,folder,webUrl,lastModifiedDateTime'];

        if ($itemType === 'file') {
            $params['$filter'] = 'file ne null';
        } elseif ($itemType === 'folder') {
            $params['$filter'] = 'folder ne null';
        }

        $response = Http::withHeaders($this->_getHeaders())
            ->get($url, $params);

        if ($response->successful()) {
            return $response->json()['value'];
        }

        throw new \Exception("Failed to search items: " . $response->body());
    }



}
