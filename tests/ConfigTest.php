<?php

namespace App\Tests;

use App\Config;
use App\Exception\InvalidFileException;
use App\Exception\InvalidKeyException;
use App\Exception\MissingFileException;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigTest
 * @package App\Tests
 */
class ConfigTest extends TestCase
{
    /**
     * @var Config
     */
    private Config $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = new Config(__DIR__);
    }

    // Loading single file

    public function test_SingleFile_CanBeLoadedCorrectly(): void
    {
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
            ]
        ], $result);
    }

    public function test_SingleFile_WhenMissing_ErrorsCorrectly(): void
    {
        $this->expectException(MissingFileException::class);

        $this->config->load('fixtures/config.missing.json');
    }

    public function test_SingleFile_WhenInvalid_ErrorsCorrectly(): void
    {
        $this->expectException(InvalidFileException::class);

        $this->config->load('fixtures/config.invalid.json');
    }

    // Loading multiple files

    public function test_MultipleFiles_AsMultipleArguments_CanBeLoadedCorrectly(): void
    {
        $this->config->load('fixtures/config.json', 'fixtures/config.local.json');

        $result = $this->config->getAll();

        self::assertEquals([
            'environment' => 'development',
            'database' => [
                'host' => '127.0.0.1',
                'port' => 3306,
                'username' => 'divido',
                'password' => 'divido'
            ],
            'cache' => [
                'redis' => [
                    'host' => '127.0.0.1',
                    'port' => 6379
                ]
            ]
        ], $result);
    }

    public function test_MultipleFiles_AsMultipleMethods_CanBeLoadedCorrectly(): void
    {
        $this->config->load('fixtures/config.json');
        $this->config->load('fixtures/config.local.json');

        $result = $this->config->getAll();

        self::assertEquals([
            'environment' => 'development',
            'database' => [
                'host' => '127.0.0.1',
                'port' => 3306,
                'username' => 'divido',
                'password' => 'divido'
            ],
            'cache' => [
                'redis' => [
                    'host' => '127.0.0.1',
                    'port' => 6379
                ]
            ]
        ], $result);
    }

    public function test_MultipleFiles_AsMultipleArguments_WhenOneFileIsMissing_ErrorsCorrectly(): void
    {
        $this->expectException(MissingFileException::class);

        $this->config->load('fixtures/config.json', 'fixtures/config.missing.json');
    }

    public function test_MultipleFiles_AsMultipleArguments_WhenOneFileIsInvalid_ErrorsCorrectly(): void
    {
        $this->expectException(InvalidFileException::class);

        $this->config->load('fixtures/config.json', 'fixtures/config.invalid.json');
    }

    public function test_MultipleFiles_AsMultipleArguments_WhenAllFilesAreMissing_ErrorsCorrectly(): void
    {
        $this->expectException(MissingFileException::class);

        $this->config->load('fixtures/config.missing.json', 'fixtures/config.also_missing.json');
    }

    public function test_MultipleFiles_AsMultipleArguments_WhenAllFilesAreInvalid_ErrorsCorrectly(): void
    {
        $this->expectException(InvalidFileException::class);

        $this->config->load('fixtures/config.invalid.json', 'fixtures/config.also_invalid.json');
    }

    public function test_MultipleFiles_AsMultipleMethods_WhenOneFileIsMissing_ErrorsCorrectly(): void
    {
        $this->expectException(MissingFileException::class);

        $this->config->load('fixtures/config.json');
        $this->config->load('fixtures/config.missing.json');
    }

    public function test_MultipleFiles_AsMultipleMethods_WhenOneFileIsInvalid_ErrorsCorrectly(): void
    {
        $this->expectException(InvalidFileException::class);

        $this->config->load('fixtures/config.json');
        $this->config->load('fixtures/config.invalid.json');
    }

    public function test_MultipleFiles_AsMultipleMethods_WhenAllFilesAreMissing_ErrorsCorrectly(): void
    {
        $this->expectException(MissingFileException::class);

        $this->config->load('fixtures/config.missing.json');
        $this->config->load('fixtures/config.also_missing.json');
    }

    public function test_MultipleFiles_AsMultipleMethods_WhenAllFilesAreInvalid_ErrorsCorrectly(): void
    {
        $this->expectException(InvalidFileException::class);

        $this->config->load('fixtures/config.invalid.json');
        $this->config->load('fixtures/config.also_invalid.json');
    }

    // Retrieving top-level single key from single file

    public function test_GetTopLevelKey_FromSingleFile(): void
    {
        $this->config->load('fixtures/config.json');

        $result = $this->config->get('environment');

        $this->assertEquals('production', $result);
    }

    public function test_GetTopLevelKey_ThatDoesNotExist_FromSingleFile_ErrorsCorrectly(): void
    {
        $this->expectException(InvalidKeyException::class);

        $this->config->load('fixtures/config.json');

        $this->config->get('unknown');
    }

    // Retrieving top-level section from single file

    public function test_GetTopLevelSection_FromSingleFile(): void
    {
        $this->config->load('fixtures/config.json');

        $result = $this->config->get('database');

        self::assertEquals([
            'host' => 'mysql',
            'port' => 3306,
            'username' => 'divido',
            'password' => 'divido',
        ], $result);
    }

    public function test_GetTopLevelSection_ThatDoesNotExist_FromSingleFile(): void
    {
        $this->expectException(InvalidKeyException::class);

        $this->config->load('fixtures/config.json');

        $this->config->get('logger');
    }

    // Retrieving nested key from single file

    public function test_GetNestedKey_FromSingleFile(): void
    {
        $this->config->load('fixtures/config.json');

        $result = $this->config->get('database.host');

        self::assertEquals('mysql', $result);
    }

    public function test_GetNestedKey_ThatDoesNotExist_FromSingleFile(): void
    {
        $this->expectException(InvalidKeyException::class);

        $this->config->load('fixtures/config.json');

        $this->config->get('logger.path');
    }

    // Retrieving nested section from single file

    public function test_GetNestedSection_FromSingleFile(): void
    {
        $this->config->load('fixtures/config.json');

        $result = $this->config->get('cache.redis');

        self::assertEquals([
            'host' => 'redis',
            'port' => 6379
        ], $result);
    }

    public function test_GetNestedSection_ThatDoesNotExist_FromSingleFile(): void
    {
        $this->expectException(InvalidKeyException::class);

        $this->config->load('fixtures/config.json');

        $this->config->get('cache.memcache');
    }

    // Retrieving top-level single key from multiple files

    public function test_GetTopLevelKey_FromMultipleFiles(): void
    {
        $this->config->load('fixtures/config.json');
        $this->config->load('fixtures/config.local.json');

        $result = $this->config->get('environment');

        self::assertEquals('development', $result);
    }

    public function test_GetTopLevelKey_ThatDoesNotExist_FromMultipleFiles(): void
    {
        $this->expectException(InvalidKeyException::class);

        $this->config->load('fixtures/config.json');
        $this->config->load('fixtures/config.local.json');

        $this->config->get('unknown');
    }

    // Retrieving top-level section from multiple files

    public function test_GetTopLevelSection_FromMultipleFiles(): void
    {
        $this->config->load('fixtures/config.json');
        $this->config->load('fixtures/config.local.json');

        $result = $this->config->get('database');

        self::assertEquals([
            'host' => '127.0.0.1',
            'port' => 3306,
            'username' => 'divido',
            'password' => 'divido',
        ], $result);
    }

    public function test_GetTopLevelSection_ThatDoesNotExist_FromMultipleFiles(): void
    {
        $this->expectException(InvalidKeyException::class);

        $this->config->load('fixtures/config.json');
        $this->config->load('fixtures/config.local.json');

        $this->config->get('logger');
    }

    // Retrieving nested key from multiple files

    public function test_GetNestedKey_FromMultipleFiles(): void
    {
        $this->config->load('fixtures/config.json');
        $this->config->load('fixtures/config.local.json');

        $result = $this->config->get('database.host');

        self::assertEquals('127.0.0.1', $result);
    }

    public function test_GetNestedKey_ThatDoesNotExist_FromMultipleFiles(): void
    {
        $this->expectException(InvalidKeyException::class);

        $this->config->load('fixtures/config.json');
        $this->config->load('fixtures/config.local.json');

        $this->config->get('logger.path');
    }

    // Retrieving nested section from multiple files

    public function test_GetNestedSection_FromMultipleFiles(): void
    {
        $this->config->load('fixtures/config.json');
        $this->config->load('fixtures/config.local.json');

        $result = $this->config->get('cache.redis');

        self::assertEquals([
            'host' => '127.0.0.1',
            'port' => 6379
        ], $result);
    }

    public function test_GetNestedSection_ThatDoesNotExist_FromMultipleFiles(): void
    {
        $this->expectException(InvalidKeyException::class);

        $this->config->load('fixtures/config.json');
        $this->config->load('fixtures/config.local.json');

        $this->config->get('cache.memcache');
    }
}
