"""
Simple .mo file generator for WordPress translations
Converts .po files to .mo format

Usage:
    python generate-mo.py

Requirements:
    pip install polib
"""

try:
    import polib
    import os
    
    print("🔄 Generating .mo files from .po files...")
    print()
    
    # Get languages directory
    lang_dir = os.path.join(os.path.dirname(__file__), 'languages')
    
    if not os.path.exists(lang_dir):
        print("❌ Error: languages/ directory not found")
        exit(1)
    
    # Find all .po files
    po_files = [f for f in os.listdir(lang_dir) if f.endswith('.po')]
    
    if not po_files:
        print("❌ Error: No .po files found in languages/")
        exit(1)
    
    print(f"Found {len(po_files)} .po file(s)")
    print()
    
    # Convert each .po to .mo
    success_count = 0
    for po_file in po_files:
        po_path = os.path.join(lang_dir, po_file)
        mo_file = po_file.replace('.po', '.mo')
        mo_path = os.path.join(lang_dir, mo_file)
        
        try:
            # Load .po file
            po = polib.pofile(po_path)
            
            # Save as .mo
            po.save_as_mofile(mo_path)
            
            # Get file sizes
            po_size = os.path.getsize(po_path)
            mo_size = os.path.getsize(mo_path)
            
            print(f"✅ {po_file} → {mo_file}")
            print(f"   PO: {po_size:,} bytes")
            print(f"   MO: {mo_size:,} bytes")
            print(f"   Size reduction: {((po_size - mo_size) / po_size * 100):.1f}%")
            print()
            
            success_count += 1
            
        except Exception as e:
            print(f"❌ Failed to convert {po_file}: {e}")
            print()
    
    print(f"🎉 Successfully generated {success_count}/{len(po_files)} .mo file(s)")
    print()
    print("✅ Your translation files are ready!")
    
except ImportError:
    print("❌ polib not installed")
    print()
    print("Please install it with:")
    print("   pip install polib")
    print()
    print("Or use Poedit instead:")
    print("   https://poedit.net/")
