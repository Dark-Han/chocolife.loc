<?php

namespace App\Factories;

use Exception;

class FileStaticFactory
{
    public static function build(string $type)
    {
        //Можно и switch case
        if ($type === 'csv') {
            return new \App\Services\CsvFile();
        } else {
            throw new Exception("Service $type was not found");
        }
    }
}
