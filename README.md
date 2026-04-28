# Laravel PDF Compress

### Fast, Lightweight & Production-Ready PDF Optimization Tool for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/phumtech/laravel-pdf-compress.svg?style=flat-square)](https://packagist.org/packages/phumtech/laravel-pdf-compress)
[![Laravel Version Support](https://img.shields.io/badge/laravel-10%2F11%2F12-red.svg?style=flat-square)](https://laravel.com)
[![PHP Version Support](https://img.shields.io/badge/php-8.1%2B-8892bf.svg?style=flat-square)](https://php.net)
[![Total Downloads](https://img.shields.io/packagist/dt/phumtech/laravel-pdf-compress.svg?style=flat-square)](https://packagist.org/packages/phumtech/laravel-pdf-compress)
[![License](https://img.shields.io/packagist/l/phumtech/laravel-pdf-compress.svg?style=flat-square)](LICENSE)

**Laravel PDF Compress** is a high-performance, SEO-optimized package designed to reduce and optimize PDF file sizes seamlessly within Laravel applications. By bridging the gap between PHP and powerful system-level binaries like **qpdf** and **Ghostscript**, it ensures reliable compression that native PHP libraries often fail to achieve. 

Whether you need lossless structural optimization for archiving or high-ratio lossy compression for web delivery, this package provides a fluent, Laravel-friendly API to handle your PDF compression needs effortlessly.

---

## 🚀 Features

* **Optimized PDF Compression**: Choose between lossless structural optimization and high-ratio lossy compression to suit your business logic.
* **Dual Engine Architecture**: Supports both `qpdf` (fast, structural optimization) and `Ghostscript` (maximum size reduction).
* **Intelligent Auto-Detection**: Automatically discovers binaries on your system or falls back to your configured defaults.
* **Fluent Laravel API**: Elegant, readable, and chainable syntax that follows Laravel's best practices.
* **Storage Integration**: Compress files directly from `Local`, `S3`, or any custom Laravel disks using native filesystem integration.
* **Queue & Batch Friendly**: Memory-efficient execution designed for background processing and heavy workloads without memory leaks.
* **Detailed Results**: Returns comprehensive compression objects including before/after file sizes, percentage saved, and execution paths.
* **Quality Presets**: Built-in `low`, `balanced`, and `high` modes for rapid configuration.

---

## 🛠 Requirements

* **PHP** 8.1 or higher
* **Laravel** 10.x, 11.x, or 12.x
* One or both of the following system binaries:
  * **qpdf** (Recommended for lossless structural optimization)
  * **Ghostscript** (Recommended for high compression and DPI reduction)

---

## 📦 Installation

Install the package via Composer:

```bash
composer require phumtech/laravel-pdf-compress
```

### System Dependencies Installation

To use this package, you must install the underlying system binaries.

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
1. Download **qpdf**: [qpdf releases](https://github.com/qpdf/qpdf/releases)
2. Download **Ghostscript**: [Ghostscript downloads](https://ghostscript.com/releases/gsdnld.html)
3. Ensure the `bin` folders of both applications are added to your System PATH environment variable.

---

## 📖 Usage

The package provides a fluent facade `PdfCompress` to handle your files.

### Basic Compression

Optimize a PDF with default settings (automatically selects the best available driver):

```php
use PhumTech\PdfCompress\Facades\PdfCompress;

$result = PdfCompress::input('path/to/large-document.pdf')
    ->output('path/to/optimized-document.pdf')
    ->compress();

echo "Saved: {$result->percentage}%";
```

### Using Laravel Storage Disks

You can seamlessly compress files located on your Laravel storage disks (e.g., S3, local, public):

```php
$result = PdfCompress::storage('invoices/inv-001.pdf')
    ->disk('s3') // Optional: defaults to your default filesystem disk
    ->quality('balanced')
    ->compress();
```

### Advanced Configuration

Force a specific driver or quality preset:

```php
PdfCompress::input('heavy-report.pdf')
    ->driver('ghostscript') // 'qpdf' or 'ghostscript'
    ->quality('low') // 'low', 'balanced', or 'high'
    ->output('light-report.pdf')
    ->compress();
```

### Handling the Compression Result

The `compress()` method returns a `CompressionResult` object containing valuable metrics:

```php
$result = PdfCompress::input('original.pdf')->compress();

if ($result->isSuccessful()) {
    echo "Original Size: " . $result->formattedOriginalSize;
    echo "New Size: " . $result->formattedNewSize;
    echo "Reduction: " . $result->percentage . "%";
    echo "Saved to: " . $result->outputPath;
}
```

---

## ⚙️ Configuration

You can publish the configuration file to customize default behaviors, paths, and presets:

```bash
php artisan vendor:publish --tag="pdfcompress-config"
```

This will create a `config/pdfcompress.php` file where you can define:
- Default compression driver.
- Custom binary paths (useful for shared hosting or Docker environments).
- Ghostscript DPI settings for each quality preset.

---

## 🏗 Architecture & Performance

This package implements a **Driver Pattern**, executing system binaries via the robust `Symfony/Process` component. This architecture is significantly more memory-efficient and reliable than purely PHP-based PDF manipulation libraries (like FPDI/TCPDF or mPDF) when dealing with large files, preventing `Allowed memory size exhausted` errors.

---

## ❓ FAQ

**Is it free?**
Yes, it is entirely open-source under the MIT license.

**Does it work offline?**
Absolutely. All compression is executed locally on your server using system binaries. No data is sent to third-party APIs.

**What happens if a binary is missing?**
If you enforce a driver (e.g., `qpdf`) and it's not installed, the package throws a clear `BinaryNotFoundException`. If using auto-detect, it will safely fall back to the next available driver.

---

## 🔒 Security

* **No Shell Injection**: Uses `Symfony/Process` to properly escape all CLI arguments.
* **Data Privacy**: No file data is transmitted to external servers or cloud APIs. Everything stays on your machine.

---

## 🧪 Testing

```bash
composer test
```

---

## 🤝 Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

---

## 📄 License

The MIT License (MIT). Please see the [License File](LICENSE) for more information.

Copyright (c) 2026 **PhumTech**.
