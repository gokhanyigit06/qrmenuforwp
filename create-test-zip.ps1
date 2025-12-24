# Menu QR Pro - Test ZIP Creator
# PowerShell Script

Write-Host "🚀 Menu QR Pro v3.0.0 - Test ZIP Creator" -ForegroundColor Green
Write-Host ""

$sourceDir = "C:\Users\PC\.gemini\antigravity\scratch\qrmenuforwp"
$tempDir = "$sourceDir\temp-build"
$pluginDir = "$tempDir\menu-qr-pro"
$zipFile = "$sourceDir\menu-qr-pro-v3.0.0-test.zip"

# Clean up old files
if (Test-Path $zipFile) {
    Remove-Item $zipFile -Force
    Write-Host "✓ Old ZIP removed" -ForegroundColor Yellow
}

if (Test-Path $tempDir) {
    Remove-Item $tempDir -Recurse -Force
}

# Create temp directory
New-Item -ItemType Directory -Path $pluginDir -Force | Out-Null
Write-Host "✓ Temp directory created" -ForegroundColor Green

# Copy files
Write-Host ""
Write-Host "📦 Copying files..." -ForegroundColor Cyan

# Main files
$mainFiles = @(
    "menu-qr-pro.php",
    "admin-view.php",
    "frontend-view.php",
    "style.css",
    "admin-style.css",
    "script.js",
    "admin-script.js",
    "menu-data.js",
    "admin-i18n.js",
    "uninstall.php",
    "README.md",
    "readme.txt"
)

foreach ($file in $mainFiles) {
    $source = "$sourceDir\$file"
    if (Test-Path $source) {
        Copy-Item $source -Destination $pluginDir -Force
        Write-Host "  ✓ $file" -ForegroundColor Gray
    }
}

# Directories
$dirs = @("includes", "admin-pages", "assets", "languages")

foreach ($dir in $dirs) {
    $source = "$sourceDir\$dir"
    if (Test-Path $source) {
        Copy-Item $source -Destination $pluginDir -Recurse -Force
        Write-Host "  ✓ $dir/" -ForegroundColor Gray
    }
}

# Create ZIP
Write-Host ""
Write-Host "🗜️  Creating ZIP file..." -ForegroundColor Cyan

Compress-Archive -Path $pluginDir -DestinationPath $zipFile -Force

# Get file size
$fileSize = (Get-Item $zipFile).Length
$fileSizeMB = [math]::Round($fileSize / 1MB, 2)

Write-Host ""
Write-Host "✅ SUCCESS!" -ForegroundColor Green
Write-Host ""
Write-Host "📦 ZIP file created:" -ForegroundColor Cyan
Write-Host "   File: menu-qr-pro-v3.0.0-test.zip" -ForegroundColor White
Write-Host "   Size: $fileSizeMB MB ($fileSize bytes)" -ForegroundColor White
Write-Host "   Path: $zipFile" -ForegroundColor White
Write-Host ""

# Clean up temp
Remove-Item $tempDir -Recurse -Force
Write-Host "✓ Temp files cleaned up" -ForegroundColor Green

Write-Host ""
Write-Host "🎉 Ready for testing!" -ForegroundColor Green
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "  1. Upload to WordPress: Plugins → Add New → Upload Plugin"
Write-Host "  2. Activate the plugin"
Write-Host "  3. Visit: QR Menu Panel"
Write-Host ""
