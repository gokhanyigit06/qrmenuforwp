# Clean ZIP Creator - Only essential files

Write-Host "🚀 Creating CLEAN plugin ZIP..." -ForegroundColor Green

$source = "C:\Users\PC\.gemini\antigravity\scratch\qrmenuforwp"
$temp = "$source\temp-plugin"
$plugin = "$temp\menu-qr-pro"
$zip = "$source\menu-qr-pro-clean.zip"

# Clean old files
if (Test-Path $zip) { Remove-Item $zip -Force }
if (Test-Path $temp) { Remove-Item $temp -Recurse -Force }

# Create structure
New-Item -ItemType Directory -Path $plugin -Force | Out-Null

# Essential PHP files
$phpFiles = @(
    "menu-qr-pro.php",
    "admin-view.php",
    "frontend-view.php",
    "uninstall.php"
)

# Essential JS/CSS files  
$assetFiles = @(
    "style.css",
    "script.js",
    "admin-style.css",
    "admin-script.js",
    "menu-data.js",
    "admin-i18n.js"
)

# Copy main files
foreach ($file in ($phpFiles + $assetFiles)) {
    if (Test-Path "$source\$file") {
        Copy-Item "$source\$file" -Destination $plugin
        Write-Host "  ✓ $file" -ForegroundColor Gray
    }
}

# Copy essential directories
$dirs = @("includes", "admin-pages", "languages", "assets")

foreach ($dir in $dirs) {
    if (Test-Path "$source\$dir") {
        Copy-Item "$source\$dir" -Destination $plugin -Recurse -Force
        Write-Host "  ✓ $dir/" -ForegroundColor Gray
    }
}

# Create ZIP
Compress-Archive -Path $plugin -DestinationPath $zip -Force

# Stats
$size = (Get-Item $zip).Length / 1KB
Write-Host ""
Write-Host "✅ Clean ZIP created!" -ForegroundColor Green
Write-Host "   File: menu-qr-pro-clean.zip" -ForegroundColor White
Write-Host "   Size: $([math]::Round($size, 2)) KB" -ForegroundColor White
Write-Host ""

# Cleanup
Remove-Item $temp -Recurse -Force

Write-Host "📦 Ready to upload to WordPress!" -ForegroundColor Cyan
