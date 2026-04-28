<?php

namespace PhumTech\PdfCompress\Contracts;

interface CompressionDriver
{
    /**
     * Compress the PDF file.
     *
     * @param string $input
     * @param string $output
     * @param string $quality
     * @param array $options
     * @return bool
     */
    public function compress(string $input, string $output, string $quality = 'balanced', array $options = []): bool;

    /**
     * Check if the driver binary is available on the system.
     *
     * @return bool
     */
    public function isAvailable(): bool;
}
