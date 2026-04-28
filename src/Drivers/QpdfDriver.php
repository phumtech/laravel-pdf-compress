<?php

namespace PhumTech\PdfCompress\Drivers;

use PhumTech\PdfCompress\Contracts\CompressionDriver;
use Symfony\Component\Process\Process;

class QpdfDriver implements CompressionDriver
{
    public function __construct(protected string $bin, protected int $timeout = 60) {}

    public function compress(string $input, string $output, string $quality = 'balanced', array $options = []): bool
    {
        $command = [$this->bin, '--linearize', '--replace-input', $input, '--output', $output];
        
        if ($input === $output) {
            $command = [$this->bin, '--linearize', $input, '--replace-input'];
        }

        $process = new Process($command);
        $process->setTimeout($this->timeout);
        $process->run();

        return $process->isSuccessful();
    }

    public function isAvailable(): bool
    {
        $process = new Process([$this->bin, '--version']);
        $process->run();
        return $process->isSuccessful();
    }
}
