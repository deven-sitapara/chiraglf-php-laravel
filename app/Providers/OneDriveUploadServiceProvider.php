<?php

namespace App\Providers;

use App\Services\OneDriveService;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;


class OneDriveUploadServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the upload service as a singleton
        Storage::extend('onedrive', function ($app, $config) {
            $service = new OneDriveService();

            return new Filesystem(new class($service) {
                protected $service;

                public function __construct(OneDriveService $service)
                {
                    $this->service = $service;
                }

                public function write($path, $contents, $config)
                {
                    $tempFile = tmpfile();
                    fwrite($tempFile, $contents);
                    $this->service->uploadFile(stream_get_meta_data($tempFile)['uri'], $path);
                    fclose($tempFile);

                    return true;
                }

                public function read($path)
                {
                    return $this->service->getFileByPath($path);
                    // Implementation for reading files from OneDrive
                }

                public function listContents($directory = '', $recursive = false)
                {
                    return $this->service->listFiles($directory);
                }

                public function delete($path)
                {
                    return $this->service->deleteFile($path);
                }
            });
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Additional boot logic if needed
    }
}
