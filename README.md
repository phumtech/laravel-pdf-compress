# Laravel PDF Compressor

### Fast, Lightweight & Production Ready PDF Optimization Tool

[![Latest Version on Packagist](https://img.shields.io/packagist/v/phumtech/laravel-pdf-compress.svg?style=flat-square)](https://packagist.org/packages/phumtech/laravel-pdf-compress)
[![Laravel Version Support](https://img.shields.io/badge/laravel-10%2F11%2F12-red.svg?style=flat-square)](https://laravel.com)
[![PHP Version Support](https://img.shields.io/badge/php-8.1%2B-8892bf.svg?style=flat-square)](https://php.net)
[![Total Downloads](https://img.shields.io/packagist/dt/phumtech/laravel-pdf-compress.svg?style=flat-square)](https://packagist.org/packages/phumtech/laravel-pdf-compress)
[![License](https://img.shields.io/packagist/l/phumtech/laravel-pdf-compress.svg?style=flat-square)](LICENSE.md)

**Laravel PDF Compressor** is a high-performance, SEO-optimized package designed to reduce PDF file sizes seamlessly within Laravel applications. It bridges the gap between PHP and powerful system-level tools like **qpdf** and **Ghostscript**, ensuring reliable compression that native PHP libraries often fail to achieve.

---

## 🚀 Features

*   **Optimized PDF Compression**: Choose between lossless structural optimization and high-ratio lossy compression.
*   **Dual Engine Architecture**: Supports both `qpdf` (fast/structural) and `Ghostscript` (maximum reduction).
*   **Intelligent Auto-Detection**: Automatically finds binaries or falls back to your configured default.
*   **Fluent Laravel API**: Elegant, readable syntax that follows Laravel's best practices.
*   **Storage Integration**: Compress files directly from `Local`, `S3`, or any custom Laravel disks.
*   **Queue & Batch Friendly**: Designed for background processing without memory leaks.
*   **Detailed Results**: Returns before/after file sizes and percentage saved.
*   **Quality Presets**: `low`, `balanced`, and `high` modes for easy configuration.

---

## 🛠 Requirements

*   PHP 8.1 or higher
*   Laravel 10, 11, or 12
*   One or both of the following system binaries:
    *   **qpdf** (Recommended for structural optimization)
    *   **Ghostscript** (Recommended for high compression)

---

## 📦 Installation

Install the package via composer:

```bash
composer require phumtech/laravel-pdf-compress
```

### System Dependencies

#### Ubuntu / Debian
```bash
sudo apt update
sudo apt install qpdf ghostscript
```

#### MacOS
```bash
brew install qpdf ghostscript
```

#### Windows
1. Download **qpdf**: [releases](https://github.com/qpdf/qpdf/releases)
2. Download **Ghostscript**: [downloads](https://ghostscript.com/releases/gsdnld.html)
3. Ensure the `bin` folders are added to your System PATH.

---

## 📖 Quick Usage

### Basic Compression
```php
use PhumTech\PdfCompress\Facades\PdfCompress;

PdfCompress::input('document.pdf')
    ->output('document-compressed.pdf')
    ->compress();
```

### Using Laravel Storage
```php
PdfCompress::storage('invoices/inv-001.pdf')
    ->disk('s3')
    ->quality('balanced')
    ->compress();
```

### Getting Results
```php
$result = PdfCompress::input($file)->compress();

echo "Saved: " . $result->percentage . "%";
echo "New size: " . $result->formattedNewSize;
```

---

## ⚙️ Configuration

Publish the config file:
```bash
php artisan vendor:publish --tag="pdfcompress-config"
```

The config allows you to set default drivers, paths to binaries, and custom quality presets.

---

## 🏗 Architecture

This package uses a **Driver Pattern**. It executes system binaries via `Symfony/Process`, which is significantly more memory-efficient and reliable than PHP-only solutions for large PDF files.

---

## ❓ FAQ

**Is it free?**
Yes, it's open-source under the MIT license.

**Does it work offline?**
Yes, all compression happens on your own server.

**What if qpdf is missing?**
The package will attempt to use Ghostscript if installed, or throw a clear `BinaryNotFoundException`.

---

## 🔒 Security

*   Uses `Symfony/Process` to escape all arguments, preventing shell injection.
*   No file data is sent to external APIs.

---

## 🔍 SEO Keywords
Laravel PDF compressor, PDF optimizer, compress PDF Laravel package, qpdf Laravel, ghostscript PDF compression, reduce PDF size Laravel, PHP PDF compression tool, PhumTech PDF.

---

## 🤝 Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

---

## 📄 License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
Copyright (c) 2026 **PhumTech**.
