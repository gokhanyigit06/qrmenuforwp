@echo off
echo Creating ZIP...

cd /d C:\Users\PC\.gemini\antigravity\scratch\qrmenuforwp

REM Clean old
rmdir /s /q prod-build 2>nul
del menu-qr-pro-v3-FINAL.zip 2>nul

REM Create structure
mkdir prod-build\menu-qr-pro 2>nul
mkdir prod-build\menu-qr-pro\includes 2>nul
mkdir prod-build\menu-qr-pro\admin-pages 2>nul
mkdir prod-build\menu-qr-pro\languages 2>nul
mkdir prod-build\menu-qr-pro\assets\js 2>nul

REM Copy main files
copy menu-qr-pro.php prod-build\menu-qr-pro\
copy admin-view.php prod-build\menu-qr-pro\
copy frontend-view.php prod-build\menu-qr-pro\
copy uninstall.php prod-build\menu-qr-pro\
copy style.css prod-build\menu-qr-pro\
copy script.js prod-build\menu-qr-pro\
copy admin-style.css prod-build\menu-qr-pro\
copy admin-script.js prod-build\menu-qr-pro\
copy menu-data.js prod-build\menu-qr-pro\
copy admin-i18n.js prod-build\menu-qr-pro\
copy readme.txt prod-build\menu-qr-pro\

REM Copy includes
copy includes\*.php prod-build\menu-qr-pro\includes\

REM Copy admin-pages
copy admin-pages\*.php prod-build\menu-qr-pro\admin-pages\

REM Copy languages
copy languages\*.po prod-build\menu-qr-pro\languages\ 2>nul
copy languages\*.pot prod-build\menu-qr-pro\languages\ 2>nul
copy languages\*.mo prod-build\menu-qr-pro\languages\ 2>nul

REM Copy assets
copy assets\js\*.js prod-build\menu-qr-pro\assets\js\ 2>nul

echo.
echo Files copied!
echo Creating ZIP with PowerShell...

powershell -Command "Compress-Archive -Path 'prod-build\menu-qr-pro' -DestinationPath 'menu-qr-pro-v3-FINAL.zip' -Force"

echo.
echo Done!
echo ZIP: menu-qr-pro-v3-FINAL.zip
