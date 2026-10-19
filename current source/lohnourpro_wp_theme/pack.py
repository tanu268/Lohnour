import os
import zipfile

def create_theme_zip():
    zip_path = 'theme.zip'
    source_dir = '.'
    
    # Exclusions
    exclude_dirs = ['.git', 'my-docs']
    exclude_files = ['verify.py', 'pack.py', 'theme.zip', 'LOHNOUR-PRO-DESIGN-SYSTEM.md', 'PRODUCTION_MIGRATION_FORENSIC_AUDIT.md']
    
    with zipfile.ZipFile(zip_path, 'w', zipfile.ZIP_DEFLATED) as zipf:
        for root, dirs, files in os.walk(source_dir):
            # Exclude directories
            dirs[:] = [d for d in dirs if d not in exclude_dirs]
            
            for file in files:
                if file in exclude_files or file.endswith('.png') and file.startswith('dashboard'):
                    continue
                file_path = os.path.join(root, file)
                arcname = os.path.relpath(file_path, source_dir)
                zipf.write(file_path, arcname)
    
    print(f"Created {zip_path}")

if __name__ == '__main__':
    create_theme_zip()
