<?php

namespace App\Parser;

use App\Exception\InvalidFileException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * YamlParser
 *
 * This class is responsible for parsing YAML files.
 */
class YamlParser implements Parser
{
    /**
     * @param string $path
     * @return array
     * @throws InvalidFileException
     */
    public function parse(string $path)
    {
        try {
            $fileContents = file_get_contents($path);
            $yamlContents = Yaml::parse($fileContents);
        } catch (ParseException $exception) {
            throw new InvalidFileException(
                "File at path $path does not contain valid YAML."
            );
        }

        return $yamlContents;
    }
}
