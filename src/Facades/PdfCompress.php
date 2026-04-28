<?php

namespace PhumTech\PdfCompress\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \PhumTech\PdfCompress\PdfCompress input(string $path)
 * @method static \PhumTech\PdfCompress\PdfCompress output(string $path)
 * @method static \PhumTech\PdfCompress\PdfCompress storage(string $path)
 * @method static \PhumTech\PdfCompress\PdfCompress disk(string $name)
 * @method static \PhumTech\PdfCompress\PdfCompress quality(string $level)
 * @method static \PhumTech\PdfCompress\PdfCompress driver(string $name)
 * @method static \PhumTech\PdfCompress\PdfCompress overwrite(bool $value = true)
 * @method static \PhumTech\PdfCompress\Objects\CompressionResult compress()
 */
class PdfCompress extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'pdf-compress';
    }
}
