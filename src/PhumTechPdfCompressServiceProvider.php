<?php

namespace PhumTech\PdfCompress;

use Illuminate\Support\ServiceProvider;

class PhumTechPdfCompressServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/pdfcompress.php' => config_path('pdfcompress.php'),
            ], 'pdfcompress-config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/pdfcompress.php', 'pdfcompress');

        $this->app->singleton('pdf-compress', function () {
            return new PdfCompress();
        });
    }
}
