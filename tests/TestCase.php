<?php

namespace PhumTech\PdfCompress\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use PhumTech\PdfCompress\PhumTechPdfCompressServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            PhumTechPdfCompressServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('pdfcompress.temp_path', __DIR__ . '/temp');
    }
}
