<?php

namespace App\Parsers;

use App\Exception\InvalidFileException;

/**
 * JsonParser
 *
 * This class is responsible for parsing JSON files.
 */
class JsonParser implements Parser
{
    /**
     * @param string $path
     * @return array
     */
    public function parse(string $path)
    {
        $fileContents = file_get_contents($path);
        $jsonContents = json_decode($fileContents, true);
        if (!json_last_error() == JSON_ERROR_NONE) {
            throw new InvalidFileException(
                "File at path $path does not contain valid JSON."
            );
        }

        return $jsonContents;
    }
}
