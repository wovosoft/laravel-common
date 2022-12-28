<?php

namespace Wovosoft\LaravelCommon\Helpers;

use Illuminate\Support\Collection;

class CsvToCollection
{
    public static function convert(string $path): Collection
    {
        $file_to_read = fopen($path, 'r');
        $index = 0;
        $header = null;
        $rows = collect([]);
        while (!feof($file_to_read)) {
            $line = fgetcsv($file_to_read, 1000, ',');;
            if ($index === 0) {
                $header = $line;
            } else {
                $row = [];
                foreach ($header as $k => $h) {
                    $key = str($h)->lower()->value();
                    if (gettype($line) === "array") {
                        $row[$key] = $line[$k];
                    }
                }

                if (count($row)) {
                    $rows->add($row);
                }
            }
            $index++;
        }

        fclose($file_to_read);

        return $rows;
    }
}
