import subprocess
import sys
import hashlib
import os

sys.stdout.reconfigure(encoding='utf-8')

from deploy_config import get_ssh_config
SSH_KEY, HOST = get_ssh_config()
TARGET_DIR = "/var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/themes/twentytwentyfive-child"

LOCAL_PAGE = r"d:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"
REMOTE_PAGE = f"{TARGET_DIR}/page-agentic-ai-foundations.php"

def run_ssh(cmd):
    full_cmd = ["ssh", "-T", "-i", SSH_KEY, "-o", "ConnectTimeout=15", "-o", "StrictHostKeyChecking=no", HOST, cmd]
    res = subprocess.run(full_cmd, capture_output=True, text=True)
    if res.returncode != 0:
        print(f"SSH FAIL: {cmd}\nSTDERR: {res.stderr}\nSTDOUT: {res.stdout}", flush=True)
        sys.exit(1)
    return res.stdout.strip()

print("--- 1. UPLOADING PAGE TEMPLATE VIA SCP ---", flush=True)
scp_cmd = ["scp", "-i", SSH_KEY, "-o", "ConnectTimeout=15", "-o", "StrictHostKeyChecking=no", LOCAL_PAGE, f"{HOST}:/tmp/page-agentic-ai-foundations.php"]
res = subprocess.run(scp_cmd, capture_output=True, text=True)
if res.returncode != 0:
    print(f"SCP FAIL:\nSTDERR: {res.stderr}\nSTDOUT: {res.stdout}", flush=True)
    sys.exit(1)
print("SCP upload complete.", flush=True)

tmp_path = "/tmp/page-agentic-ai-foundations.php"
content = open(LOCAL_PAGE, "rb").read()
local_md5 = hashlib.md5(content).hexdigest()
server_md5 = run_ssh(f"md5sum {tmp_path}").split()[0]
print(f"MD5 comparison: local={local_md5} server={server_md5}", flush=True)
if local_md5 != server_md5:
    print("ERROR: MD5 mismatch!", flush=True)
    sys.exit(1)

print("\n--- 2. INSTALLING PAGE TEMPLATE IN PRODUCTION DIRECTORY ---", flush=True)
install_cmd = f"sudo cp {tmp_path} {REMOTE_PAGE} && sudo chown www-data:www-data {REMOTE_PAGE} && sudo chmod 664 {REMOTE_PAGE} && rm -f {tmp_path}"
run_ssh(install_cmd)
installed_stat = run_ssh(f"sudo ls -la {REMOTE_PAGE}")
print(f"Installed status:\n{installed_stat}", flush=True)

print("\n--- 3. CHECKING BOM AND SYNTAX ---", flush=True)
bom_check = run_ssh(f"sudo od -An -tx1 -N3 {REMOTE_PAGE}").strip()
print(f"BOM check bytes: {bom_check} (expected: 3c 3f 70)")
if bom_check != "3c 3f 70":
    print("ERROR: Remote file has unexpected header bytes or BOM!", flush=True)
    sys.exit(1)

# Check PHP syntax inside Docker container
php_syntax = run_ssh("sudo docker exec lohnour-wordpress-1 php -l /var/www/html/wp-content/themes/twentytwentyfive-child/page-agentic-ai-foundations.php")
print(f"PHP syntax check: {php_syntax}", flush=True)
if "No syntax errors detected" not in php_syntax:
    print("ERROR: Syntax error detected!", flush=True)
    sys.exit(1)

print("\n--- 4. FLUSHING WORDPRESS OBJECT CACHE ---", flush=True)
flush_out = run_ssh("sudo docker exec lohnour-wordpress-1 wp rewrite flush --allow-root && sudo docker exec lohnour-wordpress-1 wp cache flush --allow-root")
print(f"Cache flush result:\n{flush_out}", flush=True)

print("\nPRODUCTION DEPLOYMENT COMPLETED SUCCESSFULLY!", flush=True)
