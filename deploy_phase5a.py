import subprocess
import sys
import hashlib
import os
import base64

SSH_KEY = r"D:\Downloads\ssh-key-2026-10-08.key"
HOST = "ubuntu@193.122.206.31"
TARGET_DIR = "/var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/themes/twentytwentyfive-child"
REMOTE_IMG_DIR = f"{TARGET_DIR}/assets/images"

LOCAL_PAGE = r"d:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"
LOCAL_LIGHT_IMG = r"d:\lohnourpro\current source\lohnourpro_wp_theme\assets\images\agentic-ai-foundations-light.jpeg"
LOCAL_DARK_IMG = r"d:\lohnourpro\current source\lohnourpro_wp_theme\assets\images\agentic-ai-foundations-dark.png"

REMOTE_PAGE = f"{TARGET_DIR}/page-agentic-ai-foundations.php"
REMOTE_PAGE_BAK = f"{TARGET_DIR}/page-agentic-ai-foundations.php.bak_pre_phase5a_banner"

def run_ssh(cmd, stdin_data=None):
    full_cmd = ["ssh", "-T", "-i", SSH_KEY, "-o", "ConnectTimeout=15", HOST, cmd]
    res = subprocess.run(full_cmd, input=stdin_data, capture_output=True, text=True)
    if res.returncode != 0:
        print(f"SSH FAIL: {cmd}\nSTDERR: {res.stderr}\nSTDOUT: {res.stdout}", flush=True)
        sys.exit(1)
    return res.stdout.strip()

print("--- 1. CREATING REMOTE BACKUP OF PAGE TEMPLATE ---", flush=True)
backup_cmd = f"sudo cp {REMOTE_PAGE} {REMOTE_PAGE_BAK} && sudo ls -la {REMOTE_PAGE_BAK}"
out = run_ssh(backup_cmd)
print(f"Backup created:\n{out}", flush=True)

print("\n--- 2. ENSURING REMOTE ASSET DIRECTORY EXISTS ---", flush=True)
run_ssh(f"sudo mkdir -p {REMOTE_IMG_DIR}")

def upload_file(local_path, remote_dest):
    print(f"Uploading {os.path.basename(local_path)} ({os.path.getsize(local_path)} bytes)...", flush=True)
    content = open(local_path, "rb").read()
    b64 = base64.b64encode(content).decode("ascii")
    tmp_path = f"/tmp/{os.path.basename(local_path)}"
    run_ssh(f"base64 -d > {tmp_path}", stdin_data=b64)
    
    # Check MD5
    local_md5 = hashlib.md5(content).hexdigest()
    server_md5 = run_ssh(f"md5sum {tmp_path}").split()[0]
    print(f"  MD5: local={local_md5} server={server_md5}", flush=True)
    if local_md5 != server_md5:
        print(f"ERROR: MD5 mismatch on {tmp_path}!", flush=True)
        sys.exit(1)
        
    # Copy to destination and fix permissions
    run_ssh(f"sudo cp {tmp_path} {remote_dest} && sudo chown www-data:www-data {remote_dest} && sudo chmod 664 {remote_dest} && sudo rm {tmp_path}")
    print(f"  Installed {remote_dest} successfully.", flush=True)

print("\n--- 3. UPLOADING BANNER IMAGES ---", flush=True)
upload_file(LOCAL_LIGHT_IMG, f"{REMOTE_IMG_DIR}/agentic-ai-foundations-light.jpeg")
upload_file(LOCAL_DARK_IMG, f"{REMOTE_IMG_DIR}/agentic-ai-foundations-dark.png")

print("\n--- 4. UPLOADING PAGE TEMPLATE ---", flush=True)
upload_file(LOCAL_PAGE, REMOTE_PAGE)

# BOM check on remote template
bom_check = run_ssh(f"od -An -tx1 -N3 {REMOTE_PAGE}").replace(" ", "")
if "efbbbf" in bom_check.lower():
    print("ERROR: UTF-8 BOM detected on server!", flush=True)
    sys.exit(1)
print(f"No BOM confirmed on server (first bytes: {bom_check}).", flush=True)

print("\n--- 5. FLUSHING WORDPRESS REWRITE RULES ---", flush=True)
flush_out = run_ssh("sudo docker exec lohnour-wordpress-1 wp rewrite flush --allow-root")
print(f"Rewrite flush:\n{flush_out}", flush=True)

print("\n--- 6. VERIFYING REMOTE THEME ASSETS LIST ---", flush=True)
list_out = run_ssh(f"sudo ls -lh {REMOTE_IMG_DIR}")
print(f"Remote images:\n{list_out}", flush=True)

print("\nDeployment of Phase 5A completed successfully!", flush=True)
