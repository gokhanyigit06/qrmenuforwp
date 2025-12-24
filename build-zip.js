const fs = require('fs');
const path = require('path');
const archiver = require('archiver');

const PLUGIN_NAME = 'menu-qr-pro';
const VERSION = '2.0.1';
const OUTPUT_DIR = 'dist';
const ZIP_NAME = `${PLUGIN_NAME}-${VERSION}.zip`;

// Files and directories to include
const INCLUDES = [
    'menu-qr-pro.php',
    'frontend-view.php',
    'admin-view.php',
    'uninstall.php',
    'style.css',
    'script.js',
    'admin-style.css',
    'admin-script.js',
    'admin-i18n.js',
    'menu-data.js',
    'languages/',
    'readme.txt',
    'LICENSE'
];

// Files and directories to exclude
const EXCLUDES = [
    'node_modules',
    '.git',
    '.gitignore',
    'package.json',
    'package-lock.json',
    'build-zip.js',
    '*.md',
    'dist',
    '.DS_Store',
    'Thumbs.db'
];

console.log(`📦 Building ${PLUGIN_NAME} v${VERSION}...`);

// Create dist directory if it doesn't exist
if (!fs.existsSync(OUTPUT_DIR)) {
    fs.mkdirSync(OUTPUT_DIR);
    console.log(`✓ Created ${OUTPUT_DIR}/ directory`);
}

// Create zip file
const output = fs.createWriteStream(path.join(OUTPUT_DIR, ZIP_NAME));
const archive = archiver('zip', {
    zlib: { level: 9 } // Maximum compression
});

output.on('close', function() {
    const sizeInMB = (archive.pointer() / 1024 / 1024).toFixed(2);
    console.log(`✓ ${ZIP_NAME} created successfully`);
    console.log(`✓ Total size: ${sizeInMB} MB`);
    console.log(`✓ Location: ${path.join(OUTPUT_DIR, ZIP_NAME)}`);
    console.log('\n🎉 Package ready for distribution!');
});

archive.on('error', function(err) {
    throw err;
});

archive.pipe(output);

// Add files to archive
INCLUDES.forEach(item => {
    const itemPath = path.join(__dirname, item);
    
    if (fs.existsSync(itemPath)) {
        const stats = fs.statSync(itemPath);
        
        if (stats.isDirectory()) {
            archive.directory(itemPath, `${PLUGIN_NAME}/${item}`);
            console.log(`  Adding directory: ${item}`);
        } else {
            archive.file(itemPath, { name: `${PLUGIN_NAME}/${item}` });
            console.log(`  Adding file: ${item}`);
        }
    } else {
        console.warn(`  ⚠ Skipped (not found): ${item}`);
    }
});

archive.finalize();
