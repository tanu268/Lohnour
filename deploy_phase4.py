import subprocess
import sys
import hashlib
import os
import base64

from deploy_config import get_ssh_config
SSH_KEY, HOST = get_ssh_config()
TARGET_DIR = "/var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/themes/twentytwentyfive-child"
LOCAL_FILE = r"d:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"
REMOTE_TMP = "/tmp/page-agentic-ai-foundations.php"
REMOTE_TARGET = f"{TARGET_DIR}/page-agentic-ai-foundations.php"
REMOTE_BACKUP = f"{TARGET_DIR}/page-agentic-ai-foundations.php.bak_pre_phase4_spacing"

def run_ssh(cmd, stdin_data=None):
    full_cmd = ["ssh", "-T", "-i", SSH_KEY, "-o", "ConnectTimeout=10", HOST, cmd]
    res = subprocess.run(full_cmd, input=stdin_data, capture_output=True, text=True)
    if res.returncode != 0:
        print(f"SSH FAIL: {cmd}\nSTDERR: {res.stderr}\nSTDOUT: {res.stdout}", flush=True)
        sys.exit(1)
    return res.stdout.strip()

print("--- 1. CREATING REMOTE BACKUP ---", flush=True)
backup_cmd = f"sudo cp {REMOTE_TARGET} {REMOTE_BACKUP} && sudo ls -la {REMOTE_BACKUP}"
out = run_ssh(backup_cmd)
print(f"Backup created:\n{out}", flush=True)

print("\n--- 2. UPLOADING LOCAL FILE VIA SSH BASE64 STREAM ---", flush=True)
content = open(LOCAL_FILE, "rb").read()
b64 = base64.b64encode(content).decode("ascii")
run_ssh(f"base64 -d > {REMOTE_TMP}", stdin_data=b64)
print("Uploaded successfully to /tmp.", flush=True)

print("\n--- 3. VERIFYING INTEGRITY AND NO BOM ON SERVER ---", flush=True)
local_md5 = hashlib.md5(content).hexdigest()
server_md5 = run_ssh(f"md5sum {REMOTE_TMP}").split()[0]
print(f"MD5 comparison: local={local_md5} server={server_md5}", flush=True)
if local_md5 != server_md5:
    print("ERROR: MD5 mismatch!", flush=True)
    sys.exit(1)

bom_check = run_ssh(f"od -An -tx1 -N3 {REMOTE_TMP}").replace(" ", "")
if "efbbbf" in bom_check.lower():
    print("ERROR: UTF-8 BOM detected on server!", flush=True)
    sys.exit(1)
print(f"No BOM confirmed (first bytes: {bom_check}).", flush=True)

print("\n--- 4. INSTALLING FILE INTO WORDPRESS THEME ---", flush=True)
install_cmd = f"sudo cp {REMOTE_TMP} {REMOTE_TARGET} && sudo chown www-data:www-data {REMOTE_TARGET} && sudo chmod 664 {REMOTE_TARGET} && sudo ls -la {REMOTE_TARGET}"
install_out = run_ssh(install_cmd)
print(f"Installed:\n{install_out}", flush=True)

print("\n--- 5. FLUSHING WORDPRESS REWRITE RULES ---", flush=True)
flush_out = run_ssh("sudo docker exec lohnour-wordpress-1 wp rewrite flush --allow-root")
print(f"Rewrite flush:\n{flush_out}", flush=True)

print("\nDeployment of Phase 4 completed successfully!", flush=True)
