
// ==================== Excel Import/Export Functions ====================

// Excel file input handlers
window.importFromExcel = function () {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.xlsx, .xls';
    input.onchange = handleExcelImport;
    input.click();
};

function handleExcelImport(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (event) {
        try {
            const data = new Uint8Array(event.target.result);
            const workbook = XLSX.read(data, { type: 'array' });
            const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
            const jsonData = XLSX.utils.sheet_to_json(firstSheet);

            if (jsonData.length === 0) {
                showNotification('Excel dosyası boş!', 'error');
                return;
            }

            // Process imported data
            const importedProducts = jsonData.map(row => {
                return {
                    id: row.ID || Date.now() + Math.random(),
                    name: row.Name || row.name || '',
                    category: row.Category || row.category || '',
                    price: parseFloat(row.Price || row.price || 0),
                    description: row.Description || row.description || '',
                    image: row.Image || row.image || '',
                    labels: row.Labels ? (typeof row.Labels === 'string' ? row.Labels.split(',').map(l => l.trim()) : []) : []
                };
            });

            // Ask user: Replace or Append?
            showConfirmModal(
                `${importedProducts.length} ürün bulundu. Mevcut ürünleri değiştir mi yoksa ekle mi?<br><br>` +
                `<button class="btn-secondary" onclick="closeConfirmModal(); importAppend(${JSON.stringify(importedProducts).replace(/"/g, '&quot;')})">Ekle</button> ` +
                `<button class="btn-primary" onclick="closeConfirmModal(); importReplace(${JSON.stringify(importedProducts).replace(/"/g, '&quot;')})">Değiştir</button>`,
                function () { /* placeholder */ }
            );

        } catch (error) {
            console.error('Excel import error:', error);
            showNotification('Excel dosyası okunamadı: ' + error.message, 'error');
        }
    };
    reader.readAsArrayBuffer(file);
}

window.importAppend = function (products) {
    if (typeof products === 'string') {
        products = JSON.parse(products);
    }
    adminMenuData = adminMenuData.concat(products);
    saveData().then(() => {
        renderProductsTable();
        showNotification(`${products.length} ürün eklendi!`, 'success');
    });
};

window.importReplace = function (products) {
    if (typeof products === 'string') {
        products = JSON.parse(products);
    }
    adminMenuData = products;
    saveData().then(() => {
        renderProductsTable();
        showNotification(`${products.length} ürün ile değiştirildi!`, 'success');
    });
};

// Download Excel template
window.downloadExcelTemplate = function () {
    const templateData = [
        {
            ID: 1,
            Name: 'Örnek Ürün',
            Category: 'Ana Yemek',
            Price: 150,
            Description: 'Ürün açıklaması buraya',
            Image: '',
            Labels: '🔥 Popüler, 🌶️ Acılı'
        }
    ];

    const ws = XLSX.utils.json_to_sheet(templateData);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Template");
    XLSX.writeFile(wb, "mickeys_menu_template.xlsx");

    showNotification('Şablon indirildi!', 'success');
};
