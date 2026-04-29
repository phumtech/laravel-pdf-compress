<?php

namespace PhumTech\PdfCompress;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhumTech\PdfCompress\Drivers\GhostscriptDriver;
use PhumTech\PdfCompress\Drivers\QpdfDriver;
use PhumTech\PdfCompress\Exceptions\BinaryNotFoundException;
use PhumTech\PdfCompress\Exceptions\CompressionException;
use PhumTech\PdfCompress\Objects\CompressionResult;

use Symfony\Component\Process\ExecutableFinder;

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

        $driver->compress($inputPath, $outputPath, $this->quality);

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

    protected function resolveBinPath(string $bin, array $windowsFallbacks = []): string
    {
        $finder = new ExecutableFinder();
        
        // 1. Try to find in system PATH
        $path = $finder->find($bin);
        if ($path) {
            return $path;
        }

        // 2. Try to find in project/package root (bundled binaries)
        $roots = [realpath(__DIR__ . '/..')];
        if (function_exists('base_path')) {
            $roots[] = base_path();
        }
        
        foreach ($roots as $root) {
            if (!$root) continue;

            // Check for qpdf
            if ($bin === 'qpdf' || in_array('qpdf.exe', $windowsFallbacks)) {
                $qpdfPaths = [
                    $root . '/bin/qpdf/bin/qpdf.exe',
                    $root . '/qpdf/bin/qpdf.exe',
                ];

                // Check versioned folders like qpdf_12.3.2
                $versionedFolders = glob($root . '/qpdf*/bin/qpdf.exe');
                if ($versionedFolders) {
                    $qpdfPaths = array_merge($qpdfPaths, $versionedFolders);
                }

                // Check vendor folder
                $qpdfPaths[] = $root . '/vendor/phumtech/laravel-pdf-compress/bin/qpdf/bin/qpdf.exe';
                $vendorVersioned = glob($root . '/vendor/phumtech/laravel-pdf-compress/qpdf*/bin/qpdf.exe');
                if ($vendorVersioned) {
                    $qpdfPaths = array_merge($qpdfPaths, $vendorVersioned);
                }

                foreach ($qpdfPaths as $qpdfPath) {
                    if (file_exists($qpdfPath)) {
                        return $qpdfPath;
                    }
                }
            }

            // Check for Ghostscript
            if ($bin === 'gs' || in_array('gswin64c.exe', $windowsFallbacks)) {
                $gsPaths = [
                    $root . '/bin/gs/bin/gswin64c.exe',
                    $root . '/gs/bin/gswin64c.exe',
                    $root . '/bin/gs/bin/gswin32c.exe',
                ];

                $versionedGs = glob($root . '/gs*/bin/gswin*c.exe');
                if ($versionedGs) {
                    $gsPaths = array_merge($gsPaths, $versionedGs);
                }

                foreach ($gsPaths as $gsPath) {
                    if (file_exists($gsPath)) {
                        return $gsPath;
                    }
                }
            }
        }

        // 3. Fallback to windows specific names in system PATH
        if (DIRECTORY_SEPARATOR === '\\') {
            foreach ($windowsFallbacks as $fallback) {
                $path = $finder->find($fallback);
                if ($path) {
                    return $path;
                }
            }
        }

        return $bin;
    }

    protected function resolveDriver()
    {
        $config = config('pdfcompress');
        $driverName = $this->driver ?? $config['default'];

        $qpdfBin = $this->resolveBinPath($config['drivers']['qpdf']['bin'] ?? 'qpdf', ['qpdf.exe']);
        $gsBin = $this->resolveBinPath($config['drivers']['ghostscript']['bin'] ?? 'gs', ['gswin64c', 'gswin32c', 'gswin64', 'gswin32']);

        if ($driverName === 'auto') {
            $qpdf = new QpdfDriver($qpdfBin, $config['drivers']['qpdf']['timeout'] ?? 60);
            if ($qpdf->isAvailable()) return $qpdf;
            
            $gs = new GhostscriptDriver($gsBin, $config['quality'] ?? [], $config['drivers']['ghostscript']['timeout'] ?? 120);
            if ($gs->isAvailable()) return $gs;

            throw new BinaryNotFoundException("Neither qpdf nor ghostscript binaries were found on the system.");
        }

        if ($driverName === 'qpdf') {
            return new QpdfDriver($qpdfBin, $config['drivers']['qpdf']['timeout'] ?? 60);
        }

        return new GhostscriptDriver($gsBin, $config['quality'] ?? [], $config['drivers']['ghostscript']['timeout'] ?? 120);
    }

    protected function generateOutputPath(string $input): string
    {
        return str_replace('.pdf', '-compressed.pdf', $input);
    }
}
