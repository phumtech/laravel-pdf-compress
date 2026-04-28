<?php

namespace PhumTech\PdfCompress;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhumTech\PdfCompress\Drivers\GhostscriptDriver;
use PhumTech\PdfCompress\Drivers\QpdfDriver;
use PhumTech\PdfCompress\Exceptions\BinaryNotFoundException;
use PhumTech\PdfCompress\Exceptions\CompressionException;
use PhumTech\PdfCompress\Objects\CompressionResult;

class PdfCompress
{
    protected ?string $input = null;
    protected ?string $output = null;
    protected string $quality = 'balanced';
    protected ?string $driver = null;
    protected ?string $disk = null;
    protected bool $overwrite = false;

    public function input(string $path): self
    {
        $this->input = $path;
        return $this;
    }

    public function output(string $path): self
    {
        $this->output = $path;
        return $this;
    }

    public function storage(string $path): self
    {
        $this->input = $path;
        $this->disk = $this->disk ?? 'local';
        return $this;
    }

    public function disk(string $name): self
    {
        $this->disk = $name;
        return $this;
    }

    public function quality(string $level): self
    {
        $this->quality = $level;
        return $this;
    }

    public function driver(string $name): self
    {
        $this->driver = $name;
        return $this;
    }

    public function overwrite(bool $value = true): self
    {
        $this->overwrite = $value;
        return $this;
    }

    public function compress(): CompressionResult
    {
        $start = microtime(true);
        
        $inputPath = $this->resolveInputPath();
        $outputPath = $this->output ?? ($this->overwrite ? $inputPath : $this->generateOutputPath($inputPath));

        $driver = $this->resolveDriver();

        if (!$driver->compress($inputPath, $outputPath, $this->quality)) {
            throw new CompressionException("Failed to compress PDF using " . get_class($driver));
        }

        $result = new CompressionResult(
            $inputPath,
            $outputPath,
            filesize($inputPath),
            filesize($outputPath),
            microtime(true) - $start
        );

        return $result;
    }

    protected function resolveInputPath(): string
    {
        if ($this->disk) {
            $tempDir = config('pdfcompress.temp_path');
            if (!file_exists($tempDir)) {
                @mkdir($tempDir, 0777, true);
            }
            $temp = $tempDir . '/' . Str::random(10) . '.pdf';
            file_put_contents($temp, Storage::disk($this->disk)->get($this->input));
            return $temp;
        }

        return $this->input;
    }

    protected function resolveDriver()
    {
        $config = config('pdfcompress');
        $driverName = $this->driver ?? $config['default'];

        if ($driverName === 'auto') {
            $qpdf = new QpdfDriver($config['drivers']['qpdf']['bin']);
            if ($qpdf->isAvailable()) return $qpdf;
            
            $gs = new GhostscriptDriver($config['drivers']['ghostscript']['bin'], $config['quality']);
            if ($gs->isAvailable()) return $gs;

            throw new BinaryNotFoundException("Neither qpdf nor ghostscript binaries were found on the system.");
        }

        if ($driverName === 'qpdf') {
            return new QpdfDriver($config['drivers']['qpdf']['bin'], $config['drivers']['qpdf']['timeout']);
        }

        return new GhostscriptDriver($config['drivers']['ghostscript']['bin'], $config['quality'], $config['drivers']['ghostscript']['timeout']);
    }

    protected function generateOutputPath(string $input): string
    {
        return str_replace('.pdf', '-compressed.pdf', $input);
    }
}
