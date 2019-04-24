<?php

namespace Olekjs\LaravelAthenaPdf\Tests;


class TestCase extends \Orchestra\Testbench\TestCase
{


    protected function emptyTempDirectory()
    {
        $tempDirPath = __DIR__.'/temp';
        $files = scandir($tempDirPath);
        foreach ($files as $file) {
            if (!in_array($file, ['.', '..', '.gitignore'])) {
                unlink("{$tempDirPath}/{$file}");
            }
        }
    }

    public function assertMimeType($expectedMimeType, $path)
    {
        $actualMimeType = mime_content_type($path);
        $this->assertEquals($expectedMimeType, $actualMimeType, 'MimeType did not match');
    }

    public function skipIfNotRunningonMacOS()
    {
        if (PHP_OS !== 'Darwin') {
            $this->markTestSkipped('Skipping because not running MacOS');
        }
    }
}
