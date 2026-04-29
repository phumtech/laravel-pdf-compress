# 🚀 Laravel PDF Compress

### High-Performance, SEO-Optimized PDF Optimization for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/phumtech/laravel-pdf-compress.svg?style=flat-square)](https://packagist.org/packages/phumtech/laravel-pdf-compress)
[![Laravel Version Support](https://img.shields.io/badge/laravel-10%2F11%2F12-red.svg?style=flat-square)](https://laravel.com)
[![PHP Version Support](https://img.shields.io/badge/php-8.1%2B-8892bf.svg?style=flat-square)](https://php.net)
[![License](https://img.shields.io/packagist/l/phumtech/laravel-pdf-compress.svg?style=flat-square)](LICENSE)

**Laravel PDF Compress** is a professional-grade package designed to reduce and optimize PDF file sizes seamlessly. By leveraging powerful system-level binaries like **qpdf** and **Ghostscript**, it achieves compression ratios that native PHP libraries simply cannot reach.

---

## ✨ Features

*   **Dual-Engine Support**: Harness the power of `qpdf` for structural optimization and `Ghostscript` for maximum file size reduction.
*   **Intelligent Auto-Detection**: Automatically finds binaries in your system PATH or local project folders.
*   **Bundled Binary Ready**: Support for portable binaries (ideal for shared hosting or Windows environments).
*   **Fluent API**: Clean, chainable syntax that follows Laravel best practices.
*   **Laravel Storage Integration**: Works out-of-the-box with `Local`, `S3`, and other custom disks.
*   **Memory Efficient**: Executes via `Symfony/Process`, preventing PHP memory limit issues even with massive PDFs.
*   **Quality Presets**: Ready-to-use `low`, `balanced`, and `high` modes.

---

## 🛠 Requirements

*   **PHP** 8.1 or higher
*   **Laravel** 10.x, 11.x, or 12.x
*   System binaries: **qpdf** and/or **Ghostscript**

---

## 📦 Installation

```bash
composer require phumtech/laravel-pdf-compress
```

### ⚙️ System Binary Setup

This package requires underlying binaries to function. You have two ways to set them up:

#### 1. System-Wide Installation (Recommended)

*   **Ubuntu/Debian**: `sudo apt install qpdf ghostscript`
*   **MacOS**: `brew install qpdf ghostscript`
*   **Windows**: Download and add the `bin` folders to your system **PATH**.

#### 2. Bundled Binaries (Portable)

If you cannot install binaries system-wide (e.g., shared hosting), simply place the binary folders in your **project root**. The package will automatically scan for:
*   `qpdf_*` (e.g., `qpdf_12.3.2/bin/qpdf.exe`)
*   `gs*` (e.g., `gs10.03.0/bin/gswin64c.exe`)
*   `bin/qpdf` or `bin/gs`

---

## 📖 Usage

### Basic Compression

Automatically selects the best available driver:

```php
use PhumTech\PdfCompress\Facades\PdfCompress;

$result = PdfCompress::input('document.pdf')
    ->output('optimized.pdf')
    ->compress();

echo "Reduced by: {$result->percentage}%";
```

### Using Laravel Storage

```php
$result = PdfCompress::storage('reports/july.pdf')
    ->disk('s3')
    ->quality('high')
    ->compress();
```

### Advanced Configuration

```php
PdfCompress::input('original.pdf')
    ->driver('ghostscript') // 'qpdf' or 'ghostscript'
    ->quality('low')        // 'low', 'balanced', 'high'
    ->overwrite()           // Overwrite input file
    ->compress();
```

---

## ⚙️ Configuration

Publish the config file to customize paths and presets:

```bash
php artisan vendor:publish --tag="pdfcompress-config"
```

In `config/pdfcompress.php`, you can define custom binary paths and DPI settings.

---

## 🧪 Testing

```bash
composer test
```

---

## 🤝 Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

---

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

Copyright (c) 2026 **PhumTech**.
