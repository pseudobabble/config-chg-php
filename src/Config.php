<?php

namespace App;

use App\Exception\InvalidFileException;
use App\Exception\MissingFileException;
use App\Exception\InvalidKeyException;
use App\Parsers\JsonParser;
use App\Parsers\YamlParser;

/**
 * Config
 *
 * This class is responsible for parsing configuration files.
 */
class Config
{
    /**
     * @var string
     */
    private string $baseDirectory;
    /**
     * @var array|string[]
     */
    private array $allowedExtensions = ['json', 'yml', 'yaml'];
    /**
     * @var array|string[]
     */
    private array $parsers = [
        'json' => JsonParser::class,
        'yml' => YamlParser::class,
        'yaml' => YamlParser::class
    ];
    /**
     * @var array|mixed[]
     */
    private array $config = [];

    /**
     * Config constructor.
     * @param string $baseDirectory
     */
    public function __construct(string $baseDirectory)
    {
        $this->baseDirectory = $baseDirectory;
    }

    /**
     * @param string|array $relativePaths
     * @throws InvalidFileException
     * @throws MissingFileException
     */
    public function load(...$relativePaths)
    {
        foreach ($relativePaths as $relativePath) {
            $path = $this->baseDirectory . '/' . $relativePath;
            $this->validatePath($path);

            $fileType = $this->getExtension($relativePath);
            $parser = $this->getParser($fileType);
            $config = $parser->parse($path, $fileType);

            $this->config = array_replace_recursive($this->config, $config);
        }
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->config;
    }

    /**
     * @param string $configKey
     * @return mixed
     */
    public function get(string $configKey)
    {
        $keyPath = explode('.', $configKey);

        $config = $this->config;
        foreach ($keyPath as $key) {
            if (!in_array($key, array_keys($config))) {
                throw new InvalidKeyException(
                    "$configKey was not found in the configuration file"
                );
            }

            $config = $config[$key];
        }

        return $config;
    }

    /**
     * @param string $path
     * @throws MissingFileException
     */
    private function validatePath(string $path)
    {
        if (!file_exists($path)) {
            throw new MissingFileException($path);
        }
    }

    private function getExtension(string $path)
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        $allowedExtensions = implode(', ', $this->allowedExtensions);
        if (!in_array($extension, $this->allowedExtensions)) {
            throw new InvalidFileException(
                "The file at path $path is not allowed." .
                " Allowed file types are $allowedExtensions"
            );
        }

        return $extension;
    }

    /**
     * @param string $fileType
     * @return mixed
     */
    private function getParser(string $fileType)
    {
        return new $this->parsers[$fileType];
    }
}
