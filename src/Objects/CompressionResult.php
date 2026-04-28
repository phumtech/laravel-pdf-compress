<?php

namespace PhumTech\PdfCompress\Objects;

/**
 * @property-read float $percentage
 * @property-read string $formattedNewSize
 */
class CompressionResult
{
    public function __construct(
        public string $inputPath,
        public string $outputPath,
        public int $originalSize,
        public int $newSize,
        public float $duration
    ) {}

    public function __get($name)
    {
        if ($name === 'percentage') {
            if ($this->originalSize === 0) return 0.0;
            return round((($this->originalSize - $this->newSize) / $this->originalSize) * 100, 2);
        }

        if ($name === 'formattedNewSize') {
            return $this->formatBytes($this->newSize);
        }

        return null;
    }

    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
