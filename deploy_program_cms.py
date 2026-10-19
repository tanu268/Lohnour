import subprocess
import sys
import hashlib
import os
import base64

sys.stdout.reconfigure(encoding='utf-8')

SSH_KEY = r"D:\Downloads\ssh-key-2026-10-08.key"
HOST = "ubuntu@193.122.206.31"

WP_CONTAINER = "lohnour-wordpress-1"
REMOTE_PLUGIN_DIR = "/var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/plugins/lohnour-program-cms"
REMOTE_THEME_DIR = "/var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/themes/twentytwentyfive-child"

def run_ssh(cmd, stdin_data=None):
    full_cmd = ["ssh", "-T", "-i", SSH_KEY, "-o", "ConnectTimeout=15", HOST, cmd]
    res = subprocess.run(full_cmd, input=stdin_data, capture_output=True, text=True)
    if res.returncode != 0:
        print(f"SSH FAIL: {cmd}\nSTDERR: {res.stderr}\nSTDOUT: {res.stdout}", flush=True)
        sys.exit(1)
    return res.stdout.strip()

def upload_file(local_path, remote_dest):
    print(f"Uploading {os.path.basename(local_path)} -> {remote_dest}...", flush=True)
    remote_parent = os.path.dirname(remote_dest).replace("\\", "/")
    run_ssh(f"sudo mkdir -p {remote_parent}")
    
    with open(local_path, "rb") as f:
        content = f.read()
    b64 = base64.b64encode(content).decode("ascii")
    tmp_path = f"/tmp/{os.path.basename(local_path)}"
    run_ssh(f"base64 -d > {tmp_path}", stdin_data=b64)
    
    # Verify MD5
    local_md5 = hashlib.md5(content).hexdigest()
    server_md5 = run_ssh(f"md5sum {tmp_path}").split()[0]
    if local_md5 != server_md5:
        print(f"ERROR: MD5 mismatch for {local_path}!", flush=True)
        sys.exit(1)
        
    run_ssh(f"sudo cp {tmp_path} {remote_dest} && sudo chown www-data:www-data {remote_dest} && sudo chmod 664 {remote_dest} && sudo rm {tmp_path}")
    print(f"  OK (MD5: {local_md5})", flush=True)

print("--- 1. CREATING REMOTE PLUGIN DIRECTORIES ---", flush=True)
run_ssh(f"sudo mkdir -p {REMOTE_PLUGIN_DIR}/inc {REMOTE_PLUGIN_DIR}/assets/admin/css {REMOTE_PLUGIN_DIR}/assets/admin/js {REMOTE_PLUGIN_DIR}/templates")
run_ssh(f"sudo mkdir -p {REMOTE_THEME_DIR}/assets/css {REMOTE_THEME_DIR}/assets/js")

print("\n--- 2. UPLOADING PLUGIN FILES ---", flush=True)
plugin_base = r"d:\lohnourpro\current source\lohnour-program-cms"
files_to_upload = [
    (os.path.join(plugin_base, "lohnour-program-cms.php"), f"{REMOTE_PLUGIN_DIR}/lohnour-program-cms.php"),
    (os.path.join(plugin_base, "inc", "class-program-cpt.php"), f"{REMOTE_PLUGIN_DIR}/inc/class-program-cpt.php"),
    (os.path.join(plugin_base, "inc", "class-program-assets.php"), f"{REMOTE_PLUGIN_DIR}/inc/class-program-assets.php"),
    (os.path.join(plugin_base, "inc", "class-program-renderer.php"), f"{REMOTE_PLUGIN_DIR}/inc/class-program-renderer.php"),
    (os.path.join(plugin_base, "inc", "class-program-meta-boxes.php"), f"{REMOTE_PLUGIN_DIR}/inc/class-program-meta-boxes.php"),
    (os.path.join(plugin_base, "assets", "admin", "css", "admin-program.css"), f"{REMOTE_PLUGIN_DIR}/assets/admin/css/admin-program.css"),
    (os.path.join(plugin_base, "assets", "admin", "js", "admin-program.js"), f"{REMOTE_PLUGIN_DIR}/assets/admin/js/admin-program.js"),
    (os.path.join(plugin_base, "templates", "single-lohnour_program.php"), f"{REMOTE_PLUGIN_DIR}/templates/single-lohnour_program.php"),
]

for lpath, rdest in files_to_upload:
    upload_file(lpath, rdest)

print("\n--- 3. UPLOADING THEME TEMPLATE & ASSETS ---", flush=True)
theme_base = r"d:\lohnourpro\current source\lohnourpro_wp_theme"
theme_files = [
    (os.path.join(theme_base, "single-lohnour_program.php"), f"{REMOTE_THEME_DIR}/single-lohnour_program.php"),
    (os.path.join(theme_base, "assets", "css", "program-single.css"), f"{REMOTE_THEME_DIR}/assets/css/program-single.css"),
    (os.path.join(theme_base, "assets", "js", "program-single.js"), f"{REMOTE_THEME_DIR}/assets/js/program-single.js"),
]

for lpath, rdest in theme_files:
    upload_file(lpath, rdest)

print("\n--- 4. SYNTAX CHECK ALL PHP FILES IN DOCKER ---", flush=True)
check_cmds = [
    f"docker exec {WP_CONTAINER} php -l /var/www/html/wp-content/plugins/lohnour-program-cms/lohnour-program-cms.php",
    f"docker exec {WP_CONTAINER} php -l /var/www/html/wp-content/plugins/lohnour-program-cms/inc/class-program-cpt.php",
    f"docker exec {WP_CONTAINER} php -l /var/www/html/wp-content/plugins/lohnour-program-cms/inc/class-program-assets.php",
    f"docker exec {WP_CONTAINER} php -l /var/www/html/wp-content/plugins/lohnour-program-cms/inc/class-program-renderer.php",
    f"docker exec {WP_CONTAINER} php -l /var/www/html/wp-content/plugins/lohnour-program-cms/inc/class-program-meta-boxes.php",
    f"docker exec {WP_CONTAINER} php -l /var/www/html/wp-content/themes/twentytwentyfive-child/single-lohnour_program.php",
]

for c in check_cmds:
    out = run_ssh(f"sudo {c}")
    print(f"  {out}", flush=True)

print("\n--- 5. ACTIVATE PLUGIN VIA WP-CLI ---", flush=True)
act_out = run_ssh(f"sudo docker exec -u www-data {WP_CONTAINER} wp --path=/var/www/html plugin activate lohnour-program-cms")
print(f"Plugin activation: {act_out}", flush=True)

print("\n--- 6. FLUSH REWRITE RULES VIA WP-CLI ---", flush=True)
flush_out = run_ssh(f"sudo docker exec -u www-data {WP_CONTAINER} wp --path=/var/www/html rewrite flush")
print(f"Rewrite flush: {flush_out}", flush=True)

print("\n--- 7. VERIFY POST TYPES IN WP-CLI ---", flush=True)
pts_out = run_ssh(f"sudo docker exec -u www-data {WP_CONTAINER} wp --path=/var/www/html post-type list --fields=name,label,public,hierarchical")
print(f"{pts_out}", flush=True)
