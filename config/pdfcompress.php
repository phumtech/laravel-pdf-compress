<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Driver
    |--------------------------------------------------------------------------
    | Options: "qpdf", "ghostscript", "auto"
    | "auto" will prioritize qpdf and fallback to ghostscript if available.
    */
    'default' => env('PDF_COMPRESS_DRIVER', 'auto'),

    'drivers' => [
        'qpdf' => [
            'bin' => env('QPDF_BIN_PATH', 'qpdf'),
            'timeout' => 60,
        ],
        'ghostscript' => [
            'bin' => env('GS_BIN_PATH', 'gs'),
            'timeout' => 120,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Quality Presets (Ghostscript only)
    |--------------------------------------------------------------------------
    */
    'quality' => [
        'low'      => '/screen',   // 72 dpi
        'balanced' => '/ebook',    // 150 dpi
        'high'     => '/printer',  // 300 dpi
    ],

    'temp_path' => storage_path('app/temp/pdf-compress'),
];
