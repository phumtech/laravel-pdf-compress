# Changelog

All notable changes to `phumtech/laravel-pdf-compress` will be documented in this file.

## 1.0.2 - 2026-04-29

- Enhanced binary detection with glob support for versioned folders (e.g., qpdf_12.3.2)
- Added automatic local detection for Ghostscript binaries
- Improved README with modern styling and detailed setup instructions
- Refined availability checks for Ghostscript driver
- Added support for detecting binaries in Laravel's project root and vendor folders

## 1.0.0 - 2026-04-27

- Initial release
- Support for qpdf and Ghostscript engines
- Fluent API for PDF compression
- Laravel Storage integration
- Quality presets (low, balanced, high)
