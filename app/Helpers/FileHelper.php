<?php

namespace App\Helpers;


class FileHelper
{
    public static function getNewFileName($name, $fieldName, $extension)
    {
        return "{$name}-{$fieldName}.{$extension}";
    }
}

