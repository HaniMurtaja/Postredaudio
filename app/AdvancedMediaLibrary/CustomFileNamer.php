<?php

namespace App\AdvancedMediaLibrary;

use Spatie\MediaLibrary\Support\FileNamer\FileNamer;
use Spatie\MediaLibrary\Conversions\Conversion;

class CustomFileNamer extends FileNamer
{
    public function originalFileName(string $fileName): string
    {
        $extLength = strlen(pathinfo($fileName, PATHINFO_EXTENSION));

        $baseName = substr($fileName, 0, strlen($fileName) - ($extLength ? $extLength + 1 : 0));

        return hash('md5', $baseName).'_'.time();
    }


    public function conversionFileName(string $fileName, Conversion $conversion): string
    {
        $strippedFileName = pathinfo($fileName, PATHINFO_FILENAME);

        return "{$strippedFileName}_{$conversion->getName()}";
    }

    public function responsiveFileName(string $fileName): string
    {
        return pathinfo($fileName, PATHINFO_FILENAME);
    }
}
