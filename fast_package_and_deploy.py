import os
import sys
import tarfile
import subprocess

sys.stdout.reconfigure(encoding='utf-8')

SSH_KEY = r"D:\Downloads\ssh-key-2026-10-08.key"
HOST = "ubuntu@193.122.206.31"
WP_CONTAINER = "lohnour-wordpress-1"
REMOTE_PLUGIN_DIR = "/var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/plugins"
REMOTE_THEME_DIR = "/var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/themes/twentytwentyfive-child"

# 1. Create plugin tarball
plugin_src = r"d:\lohnourpro\current source\lohnour-program-cms"
plugin_tar = r"d:\lohnourpro\lohnour-program-cms.tar.gz"

print("--- 1. PACKAGING PLUGIN ---", flush=True)
with tarfile.open(plugin_tar, "w:gz") as tar:
    tar.add(plugin_src, arcname="lohnour-program-cms")
print(f"Created {plugin_tar} ({os.path.getsize(plugin_tar)} bytes)", flush=True)

# 2. Create theme additions tarball
theme_src = r"d:\lohnourpro\current source\lohnourpro_wp_theme"
theme_tar = r"d:\lohnourpro\lohnour-theme-additions.tar.gz"

print("--- 2. PACKAGING THEME ADDITIONS ---", flush=True)
with tarfile.open(theme_tar, "w:gz") as tar:
    tar.add(os.path.join(theme_src, "single-lohnour_program.php"), arcname="single-lohnour_program.php")
    tar.add(os.path.join(theme_src, "assets", "css", "program-single.css"), arcname="assets/css/program-single.css")
    tar.add(os.path.join(theme_src, "assets", "js", "program-single.js"), arcname="assets/js/program-single.js")
print(f"Created {theme_tar} ({os.path.getsize(theme_tar)} bytes)", flush=True)

# 3. SCP both files to /tmp/
print("--- 3. SCP TARBALLS TO REMOTE SERVER ---", flush=True)
scp_cmd = [
    "scp",
    "-i", SSH_KEY,
    "-o", "ConnectTimeout=15",
    plugin_tar,
    theme_tar,
    f"{HOST}:/tmp/"
]
res = subprocess.run(scp_cmd, capture_output=True, text=True)
if res.returncode != 0:
    print(f"SCP FAILED!\nSTDOUT: {res.stdout}\nSTDERR: {res.stderr}", flush=True)
    sys.exit(1)
print("SCP SUCCESSFUL!", flush=True)

# 4. Extract and set permissions via SSH
print("--- 4. EXTRACTING AND INSTALLING ---", flush=True)
remote_script = f"""
sudo tar -xzf /tmp/lohnour-program-cms.tar.gz -C {REMOTE_PLUGIN_DIR}/
sudo chown -R www-data:www-data {REMOTE_PLUGIN_DIR}/lohnour-program-cms
sudo chmod -R 755 {REMOTE_PLUGIN_DIR}/lohnour-program-cms

sudo tar -xzf /tmp/lohnour-theme-additions.tar.gz -C {REMOTE_THEME_DIR}/
sudo chown -R www-data:www-data {REMOTE_THEME_DIR}/assets/css/program-single.css {REMOTE_THEME_DIR}/assets/js/program-single.js {REMOTE_THEME_DIR}/single-lohnour_program.php
sudo chmod 644 {REMOTE_THEME_DIR}/assets/css/program-single.css {REMOTE_THEME_DIR}/assets/js/program-single.js {REMOTE_THEME_DIR}/single-lohnour_program.php

rm -f /tmp/lohnour-program-cms.tar.gz /tmp/lohnour-theme-additions.tar.gz
"""

ssh_cmd = [
    "ssh",
    "-i", SSH_KEY,
    "-o", "ConnectTimeout=15",
    HOST,
    remote_script
]
res = subprocess.run(ssh_cmd, capture_output=True, text=True)
if res.returncode != 0:
    print(f"EXTRACTION FAILED!\nSTDOUT: {res.stdout}\nSTDERR: {res.stderr}", flush=True)
    sys.exit(1)
print("EXTRACTION SUCCESSFUL!", flush=True)

# 5. PHP Syntax check inside container
print("--- 5. CHECKING PHP SYNTAX ---", flush=True)
syntax_cmd = [
    "ssh", "-i", SSH_KEY, HOST,
    f"sudo docker exec {WP_CONTAINER} php -l /var/www/html/wp-content/plugins/lohnour-program-cms/lohnour-program-cms.php && "
    f"sudo docker exec {WP_CONTAINER} php -l /var/www/html/wp-content/plugins/lohnour-program-cms/inc/class-program-cpt.php && "
    f"sudo docker exec {WP_CONTAINER} php -l /var/www/html/wp-content/plugins/lohnour-program-cms/inc/class-program-assets.php && "
    f"sudo docker exec {WP_CONTAINER} php -l /var/www/html/wp-content/plugins/lohnour-program-cms/inc/class-program-renderer.php && "
    f"sudo docker exec {WP_CONTAINER} php -l /var/www/html/wp-content/plugins/lohnour-program-cms/inc/class-program-meta-boxes.php && "
    f"sudo docker exec {WP_CONTAINER} php -l /var/www/html/wp-content/themes/twentytwentyfive-child/single-lohnour_program.php"
]
res = subprocess.run(syntax_cmd, capture_output=True, text=True)
print(res.stdout, flush=True)
if res.returncode != 0:
    print(f"SYNTAX ERROR!\nSTDERR: {res.stderr}", flush=True)
    sys.exit(1)

# 6. Activate Plugin & Flush Rewrites
print("--- 6. ACTIVATING PLUGIN & FLUSHING REWRITES ---", flush=True)
act_cmd = [
    "ssh", "-i", SSH_KEY, HOST,
    f"sudo docker exec -u www-data {WP_CONTAINER} wp --path=/var/www/html plugin activate lohnour-program-cms && "
    f"sudo docker exec -u www-data {WP_CONTAINER} wp --path=/var/www/html rewrite flush && "
    f"sudo docker exec -u www-data {WP_CONTAINER} wp --path=/var/www/html post-type get lohnour_program"
]
res = subprocess.run(act_cmd, capture_output=True, text=True)
print(res.stdout, flush=True)
if res.returncode != 0:
    print(f"ACTIVATION ERROR!\nSTDERR: {res.stderr}", flush=True)
    sys.exit(1)

print("--- DEPLOYMENT COMPLETED SUCCESSFULLY ---", flush=True)
