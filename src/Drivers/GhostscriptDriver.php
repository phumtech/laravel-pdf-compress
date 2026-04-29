<?php

namespace PhumTech\PdfCompress\Drivers;

use PhumTech\PdfCompress\Contracts\CompressionDriver;
use Symfony\Component\Process\Process;

class GhostscriptDriver implements CompressionDriver
{
    public function __construct(
        protected string $bin, 
        protected array $qualityMap,
        protected int $timeout = 120
    ) {}

    public function compress(string $input, string $output, string $quality = 'balanced', array $options = []): bool
    {
        $gsQuality = $this->qualityMap[$quality] ?? '/ebook';

        $command = [
            $this->bin,
            '-sDEVICE=pdfwrite',
            '-dCompatibilityLevel=1.4',
            '-dPDFSETTINGS=' . $gsQuality,
            '-dNOPAUSE',
            '-dQUIET',
            '-dBATCH',
            '-sOutputFile=' . $output,
            $input
        ];

        $process = new Process($command);
        $process->setTimeout($this->timeout);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \PhumTech\PdfCompress\Exceptions\CompressionException(
                "Ghostscript failed with error: " . $process->getErrorOutput() . " (Exit Code: " . $process->getExitCode() . ")"
            );
        }

        return true;
    }

    public function isAvailable(): bool
    {
        // Try -v first as it's common for Ghostscript
        $process = new Process([$this->bin, '-v']);
        $process->run();
        
        if ($process->isSuccessful()) {
            return true;
        }

        // Fallback to --version
        $process = new Process([$this->bin, '--version']);
        $process->run();
        
        return $process->isSuccessful();
    }
}
