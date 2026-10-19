import subprocess
import sys
import hashlib
import os
import base64

from deploy_config import get_ssh_config
SSH_KEY, HOST = get_ssh_config()
TARGET_DIR = "/var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/themes/twentytwentyfive-child"

LOCAL_PAGE = r"d:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"
REMOTE_PAGE = f"{TARGET_DIR}/page-agentic-ai-foundations.php"
REMOTE_PAGE_BAK = f"{TARGET_DIR}/page-agentic-ai-foundations.php.bak_pre_curriculum_nav_removal"

def run_ssh(cmd, stdin_data=None):
    full_cmd = ["ssh", "-T", "-i", SSH_KEY, "-o", "ConnectTimeout=15", "-o", "StrictHostKeyChecking=no", HOST, cmd]
    res = subprocess.run(full_cmd, input=stdin_data, capture_output=True, text=True)
    if res.returncode != 0:
        print(f"SSH FAIL: {cmd}\nSTDERR: {res.stderr}\nSTDOUT: {res.stdout}", flush=True)
        sys.exit(1)
    return res.stdout.strip()

print("--- 1. CREATING REMOTE BACKUP OF PAGE TEMPLATE ---", flush=True)
backup_cmd = f"sudo cp {REMOTE_PAGE} {REMOTE_PAGE_BAK} && sudo ls -la {REMOTE_PAGE_BAK}"
out = run_ssh(backup_cmd)
print(f"Backup created:\n{out}", flush=True)

print("\n--- 2. UPLOADING PAGE TEMPLATE ---", flush=True)
content = open(LOCAL_PAGE, "rb").read()
b64 = base64.b64encode(content).decode("ascii")
tmp_path = "/tmp/page-agentic-ai-foundations.php"
run_ssh(f"base64 -d > {tmp_path}", stdin_data=b64)

local_md5 = hashlib.md5(content).hexdigest()
server_md5 = run_ssh(f"md5sum {tmp_path}").split()[0]
print(f"MD5 comparison: local={local_md5} server={server_md5}", flush=True)
if local_md5 != server_md5:
    print("ERROR: MD5 mismatch!", flush=True)
    sys.exit(1)

run_ssh(f"sudo cp {tmp_path} {REMOTE_PAGE} && sudo chown www-data:www-data {REMOTE_PAGE} && sudo chmod 664 {REMOTE_PAGE} && sudo rm {tmp_path}")
print(f"Installed {REMOTE_PAGE} successfully.", flush=True)

print("\n--- 3. CHECKING BOM ON REMOTE FILE ---", flush=True)
bom_check = run_ssh(f"sudo od -An -tx1 -N3 {REMOTE_PAGE}").replace(" ", "")
if "efbbbf" in bom_check.lower():
    print("ERROR: UTF-8 BOM detected on server!", flush=True)
    sys.exit(1)
print(f"No BOM confirmed on server (first bytes: {bom_check}).", flush=True)

print("\n--- 4. CHECKING PHP SYNTAX IN DOCKER CONTAINER ---", flush=True)
syntax_check = run_ssh("sudo docker exec lohnour-wordpress-1 php -l /var/www/html/wp-content/themes/twentytwentyfive-child/page-agentic-ai-foundations.php")
print(f"PHP syntax output: {syntax_check}", flush=True)
if "No syntax errors detected" not in syntax_check:
    print("ERROR: PHP syntax check failed!", flush=True)
    sys.exit(1)

print("\n--- 5. FLUSHING WORDPRESS REWRITE & OBJECT CACHE ---", flush=True)
flush_out = run_ssh("sudo docker exec lohnour-wordpress-1 wp rewrite flush --allow-root")
print(f"Rewrite flush:\n{flush_out}", flush=True)
cache_out = run_ssh("sudo docker exec lohnour-wordpress-1 wp cache flush --allow-root")
print(f"Cache flush:\n{cache_out}", flush=True)

print("\nDeployment of curriculum navigation removal completed successfully!", flush=True)
