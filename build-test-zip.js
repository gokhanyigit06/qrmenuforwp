const fs = require('fs');
const path = require('path');
const archiver = require('archiver');

// Create menu-qr-pro.zip for testing
const output = fs.createWriteStream(path.join(__dirname, 'menu-qr-pro-v3.0.0-test.zip'));
const archive = archiver('zip', {
    zlib: { level: 9 }
});

output.on('close', function () {
    console.log('✅ ZIP created: ' + archive.pointer() + ' total bytes');
    console.log('📦 File: menu-qr-pro-v3.0.0-test.zip');
});

archive.on('error', function (err) {
    throw err;
});

archive.pipe(output);

// Files to include
const files = [
    'menu-qr-pro.php',
    'admin-view.php',
    'frontend-view.php',
    'style.css',
    'admin-style.css',
    'script.js',
    'admin-script.js',
    'menu-data.js',
    'admin-i18n.js',
    'uninstall.php',
    'README.md'
];

// Directories to include
const dirs = [
    'includes',
    'admin-pages',
    'assets',
    'languages'
];

// Add files
files.forEach(file => {
    const filePath = path.join(__dirname, file);
    if (fs.existsSync(filePath)) {
        archive.file(filePath, { name: `menu-qr-pro/${file}` });
    }
});

// Add directories
dirs.forEach(dir => {
    const dirPath = path.join(__dirname, dir);
    if (fs.existsSync(dirPath)) {
        archive.directory(dirPath, `menu-qr-pro/${dir}`);
    }
});

archive.finalize();
