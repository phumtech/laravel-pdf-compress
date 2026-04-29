<?php

require __DIR__ . '/../vendor/autoload.php';

use PhumTech\PdfCompress\Drivers\QpdfDriver;
use Symfony\Component\Process\Process;

$bin = realpath(__DIR__ . '/../qpdf_12.3.2/bin/qpdf.exe');
$input = realpath(__DIR__ . '/../tests/dummy.pdf');
$output = __DIR__ . '/test_out.pdf';

echo "Bin: $bin\n";
echo "Input: $input\n";

$driver = new QpdfDriver($bin);
$res = $driver->compress($input, $output);

if (!$res) {
    echo "FAILED\n";
    // Let's run it manually to see the output
    $process = new Process([$bin, '--linearize', $input, $output]);
    $process->run();
    echo "Output: " . $process->getOutput() . "\n";
    echo "Error: " . $process->getErrorOutput() . "\n";
} else {
    echo "SUCCESS\n";
}
