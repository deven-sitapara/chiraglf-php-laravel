<?php

// upload file to OneDrive

use App\Services\OneDriveService;
use Microsoft\Kiota\Abstractions\ApiException;



// write only test case for all upload , get and delete file
it('One Drive Test', function () {

    $graph = new \App\Services\OneDriveService();

    expect($graph)->toBeInstanceOf(\App\Services\OneDriveService::class);

    // upload file to OneDrive
    $localTemplatePath = storage_path('app/public/document_file_format/document_file_format-2025-01-23-14-01-48-howell-johnsCousine.docx');
    $oneDrivePath = 'document_file_format-2025-01-23-14-01-48-howell-johnsCousine.docx';

    $file = $graph->uploadFileFromTemplate($localTemplatePath, $oneDrivePath);
    expect($file)->toBeArray(); // Assert that the result is an array
    if ($file) {
        expect($file)->toHaveKeys(['id', 'name', 'webUrl', 'size']); //Assert that the array contains the expected keys
        expect($file['id'])->toBeString(); // Assert that 'id' is a string
        expect($file['name'])->toBeString();  // Assert that 'name' is a string
        expect($file['webUrl'])->toBeString(); // Assert that 'webUrl' is a string
        expect($file['size'])->toBeInt(); // Assert that 'size' is an integer
    }


    // get a file by id
    $fileId = $file['id']; // Replace with a valid file ID for your test
    $file = $graph->getFileById($fileId);
    expect($file)->toBeArray(); // Assert that the result is an array

    if ($file) {
        expect($file)->toHaveKeys(['id', 'name', 'webUrl', 'size']); //Assert that the array contains the expected keys
        expect($file['id'])->toBeString(); // Assert that 'id' is a string
        expect($file['name'])->toBeString();  // Assert that 'name' is a string
        expect($file['webUrl'])->toBeString(); // Assert that 'webUrl' is a string
        expect($file['size'])->toBeInt(); // Assert that 'size' is an integer
    }

    $fileId = $file['id'];

    $result = $graph->deleteFile($fileId);

    expect($result)->toBeTrue(); // Assert that the result is null

})->only();

it('returns null if file is not found or an error occurs', function () {

    $invalidFileId = 'invalid_file_id'; // Use an invalid file ID

    $file = $this->graph->getFileById($invalidFileId);

    expect($file)->toBeNull(); // Assert that the result is null
});
