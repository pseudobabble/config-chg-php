<?php

namespace App\Tests\ExtraCredit;

use App\Config;
use App\Exception\InvalidFileException;
use App\Exception\MissingFileException;
use PHPUnit\Framework\TestCase;

class YAMLSupportTest extends TestCase
{
    /**
     * @var Config
     */
    private Config $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = new Config(dirname(__DIR__));
    }

    // Loading single file

    public function test_SingleYAMLFile_CanBeLoadedCorrectly(): void
    {
        $this->config->load('fixtures/config.yml');

        $result = $this->config->getAll();

        self::assertEquals([
            'app_name' => 'Authentication API',
            'dependencies' => [
                'mysql',
                'redis'
            ]
        ], $result);
    }

    public function test_SingleYAMLFile_WhenMissing_ErrorsCorrectly(): void
    {
        $this->expectException(MissingFileException::class);

        $this->config->load('fixtures/config.missing.yml');
    }

    public function test_SingleYAMLFile_WhenInvalid_ErrorsCorrectly(): void
    {
        $this->expectException(InvalidFileException::class);

        $this->config->load('fixtures/config.invalid.yml');
    }

    // Loading multiple files in the same format

    public function test_MultipleFilesOfSameFormat_AsMultipleArguments_CanBeLoadedCorrectly(): void
    {
        $this->config->load('fixtures/config.yml', 'fixtures/config.extra.yml');

        $result = $this->config->getAll();

        self::assertEquals([
            'app_name' => 'Authentication API',
            'dependencies' => [
                'mysql',
                'redis'
            ],
            'description' => "This should be a block of text, it's only here to demonstrate\nthat we can actually parse more of the quirks of YAML."
        ], $result);
    }

    public function test_MultipleFilesOfSameFormat_AsMultipleMethods_CanBeLoadedCorrectly(): void
    {
        $this->config->load('fixtures/config.yml');
        $this->config->load('fixtures/config.extra.yml');

        $result = $this->config->getAll();

        self::assertEquals([
            'app_name' => 'Authentication API',
            'dependencies' => [
                'mysql',
                'redis'
            ],
            'description' => "This should be a block of text, it's only here to demonstrate\nthat we can actually parse more of the quirks of YAML."
        ], $result);
    }

    public function test_MultipleFilesOfSameFormat_AsMultipleArguments_WhenOneFileIsMissing_ErrorsCorrectly(): void
    {
        $this->expectException(MissingFileException::class);

        $this->config->load('fixtures/config.yml', 'fixtures/config.missing.yml');
    }

    public function test_MultipleFilesOfSameFormat_AsMultipleArguments_WhenOneFileIsInvalid_ErrorsCorrectly(): void
    {
        $this->expectException(InvalidFileException::class);

        $this->config->load('fixtures/config.yml', 'fixtures/config.invalid.yml');
    }

    public function test_MultipleFilesOfSameFormat_AsMultipleArguments_WhenAllFilesAreMissing_ErrorsCorrectly(): void
    {
        $this->expectException(MissingFileException::class);

        $this->config->load('fixtures/config.missing.yml', 'fixtures/config.also_missing.yml');
    }

    public function test_MultipleFilesOfSameFormat_AsMultipleArguments_WhenAllFilesAreInvalid_ErrorsCorrectly(): void
    {
        $this->expectException(InvalidFileException::class);

        $this->config->load('fixtures/config.invalid.yml', 'fixtures/config.also_invalid.yml');
    }

    public function test_MultipleFilesOfSameFormat_AsMultipleMethods_WhenOneFileIsMissing_ErrorsCorrectly(): void
    {
        $this->expectException(MissingFileException::class);

        $this->config->load('fixtures/config.yml');
        $this->config->load('fixtures/config.missing.yml');
    }

    public function test_MultipleFilesOfSameFormat_AsMultipleMethods_WhenOneFileIsInvalid_ErrorsCorrectly(): void
    {
        $this->expectException(InvalidFileException::class);

        $this->config->load('fixtures/config.yml');
        $this->config->load('fixtures/config.invalid.yml');
    }

    public function test_MultipleFilesOfSameFormat_AsMultipleMethods_WhenAllFilesAreMissing_ErrorsCorrectly(): void
    {
        $this->expectException(MissingFileException::class);

        $this->config->load('fixtures/config.missing.yml');
        $this->config->load('fixtures/config.also_missing.yml');
    }

    public function test_MultipleFilesOfSameFormat_AsMultipleMethods_WhenAllFilesAreInvalid_ErrorsCorrectly(): void
    {
        $this->expectException(InvalidFileException::class);

        $this->config->load('fixtures/config.invalid.yml');
        $this->config->load('fixtures/config.also_invalid.yml');
    }

    // Loading files in multiple formats

    public function test_MultipleFilesWithMultipleFormats_AsMultipleArguments_CanBeLoadedCorrectly(): void
    {
        $this->config->load('fixtures/config.yml', 'fixtures/config.json');

        $result = $this->config->getAll();

        self::assertEquals([
            'environment' => 'production',
            'database' => [
                'host' => 'mysql',
                'port' => 3306,
                'username' => 'divido',
                'password' => 'divido'
            ],
            'cache' => [
                'redis' => [
                    'host' => 'redis',
                    'port' => 6379
                ]
            ],
            'app_name' => 'Authentication API',
            'dependencies' => [
                'mysql',
                'redis'
            ]
        ], $result);
    }

    public function test_MultipleFilesWithMultipleFormats_AsMultipleMethods_CanBeLoadedCorrectly(): void
    {
        $this->config->load('fixtures/config.yml');
        $this->config->load('fixtures/config.json');

        $result = $this->config->getAll();

        self::assertEquals([
            'environment' => 'production',
            'database' => [
                'host' => 'mysql',
                'port' => 3306,
                'username' => 'divido',
                'password' => 'divido'
            ],
            'cache' => [
                'redis' => [
                    'host' => 'redis',
                    'port' => 6379
                ]
            ],
            'app_name' => 'Authentication API',
            'dependencies' => [
                'mysql',
                'redis'
            ]
        ], $result);
    }

    public function test_MultipleFilesWithMultipleFormats_AsMultipleArguments_WhenOneFileIsMissing_ErrorsCorrectly(): void
    {
        $this->expectException(MissingFileException::class);

        $this->config->load('fixtures/config.yml', 'fixtures/config.missing.json');
    }

    public function test_MultipleFilesWithMultipleFormats_AsMultipleArguments_WhenOneFileIsInvalid_ErrorsCorrectly(): void
    {
        $this->expectException(InvalidFileException::class);

        $this->config->load('fixtures/config.json', 'fixtures/config.invalid.yml');
    }

    public function test_MultipleFilesWithMultipleFormats_AsMultipleArguments_WhenAllFilesAreMissing_ErrorsCorrectly(): void
    {
        $this->expectException(MissingFileException::class);

        $this->config->load('fixtures/config.missing.yml', 'fixtures/config.also_missing.json');
    }

    public function test_MultipleFilesWithMultipleFormats_AsMultipleArguments_WhenAllFilesAreInvalid_ErrorsCorrectly(): void
    {
        $this->expectException(InvalidFileException::class);

        $this->config->load('fixtures/config.invalid.yml', 'fixtures/config.also_invalid.json');
    }

    public function test_MultipleFilesWithMultipleFormats_AsMultipleMethods_WhenOneFileIsMissing_ErrorsCorrectly(): void
    {
        $this->expectException(MissingFileException::class);

        $this->config->load('fixtures/config.yml');
        $this->config->load('fixtures/config.missing.json');
    }

    public function test_MultipleFilesWithMultipleFormats_AsMultipleMethods_WhenOneFileIsInvalid_ErrorsCorrectly(): void
    {
        $this->expectException(InvalidFileException::class);

        $this->config->load('fixtures/config.yml');
        $this->config->load('fixtures/config.invalid.json');
    }

    public function test_MultipleFilesWithMultipleFormats_AsMultipleMethods_WhenAllFilesAreMissing_ErrorsCorrectly(): void
    {
        $this->expectException(MissingFileException::class);

        $this->config->load('fixtures/config.missing.yml');
        $this->config->load('fixtures/config.also_missing.json');
    }

    public function test_MultipleFilesWithMultipleFormats_AsMultipleMethods_WhenAllFilesAreInvalid_ErrorsCorrectly(): void
    {
        $this->expectException(InvalidFileException::class);

        $this->config->load('fixtures/config.invalid.yml');
        $this->config->load('fixtures/config.also_invalid.json');
    }
}
