<?php

use PhumTech\PdfCompress\Facades\PdfCompress;
use PhumTech\PdfCompress\Objects\CompressionResult;
use Illuminate\Support\Facades\Storage;

uses(\PhumTech\PdfCompress\Tests\TestCase::class);

test('it can compress a local file', function () {
    // This test would require actual binaries to be present.
    // In a real CI environment, we install them.
    // For now, we mock the driver behavior if needed or assume environment is ready.
    
    // Create a dummy PDF or use a sample
    // $result = PdfCompress::input('test.pdf')->output('test-out.pdf')->compress();
    // expect($result)->toBeInstanceOf(CompressionResult::class);
})->todo();
