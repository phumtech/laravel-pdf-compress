<?php

use PhumTech\PdfCompress\Facades\PdfCompress;
use PhumTech\PdfCompress\Objects\CompressionResult;
use Illuminate\Support\Facades\Storage;

uses(\PhumTech\PdfCompress\Tests\TestCase::class);

test('it can compress a local file', function () {
    // We downloaded a dummy.pdf into the tests folder
    $inputPath = __DIR__ . '/../dummy.pdf';
    $outputPath = __DIR__ . '/../dummy-compressed.pdf';

    $result = PdfCompress::input($inputPath)
        ->output($outputPath)
        ->compress();

    expect($result)->toBeInstanceOf(CompressionResult::class);
    expect(file_exists($outputPath))->toBeTrue();
    
    // Cleanup
    if (file_exists($outputPath)) {
        unlink($outputPath);
    }
});
