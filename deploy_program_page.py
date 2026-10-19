import subprocess
import sys
import hashlib
import os
import base64

SSH_KEY = r"D:\Downloads\ssh-key-2026-10-08.key"
HOST = "ubuntu@193.122.206.31"
TARGET_DIR = "/var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/themes/twentytwentyfive-child"

files_to_deploy = [
    (r"d:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php", "/tmp/page-agentic-ai-foundations.php", f"{TARGET_DIR}/page-agentic-ai-foundations.php"),
    (r"d:\lohnourpro\current source\lohnourpro_wp_theme\page-coaching.php", "/tmp/page-coaching.php", f"{TARGET_DIR}/page-coaching.php"),
]

def run_ssh(cmd, stdin_data=None):
    full_cmd = ["ssh", "-T", "-i", SSH_KEY, "-o", "ConnectTimeout=15", HOST, cmd]
    res = subprocess.run(full_cmd, input=stdin_data, capture_output=True, text=True)
    if res.returncode != 0:
        print(f"SSH FAIL: {cmd}\nSTDERR: {res.stderr}\nSTDOUT: {res.stdout}", flush=True)
        sys.exit(1)
    return res.stdout.strip()

print("--- 1. UPLOADING FILES VIA SSH BASE64 STREAM ---", flush=True)
for local_path, tmp_path, _ in files_to_deploy:
    print(f"Transferring {local_path} -> {tmp_path}", flush=True)
    content = open(local_path, "rb").read()
    b64 = base64.b64encode(content).decode("ascii")
    run_ssh(f"base64 -d > {tmp_path}", stdin_data=b64)
    print("  Transferred successfully.", flush=True)

print("\n--- 2. VERIFYING INTEGRITY AND NO BOM ON SERVER ---", flush=True)
for local_path, tmp_path, target_path in files_to_deploy:
    local_md5 = hashlib.md5(open(local_path, "rb").read()).hexdigest()
    server_md5 = run_ssh(f"md5sum {tmp_path}").split()[0]
    print(f"MD5 match for {os.path.basename(local_path)}: local={local_md5} server={server_md5}", flush=True)
    if local_md5 != server_md5:
        print(f"ERROR: MD5 mismatch for {local_path}", flush=True)
        sys.exit(1)
    
    # Check BOM on server
    bom_check = run_ssh(f"head -c 3 {tmp_path} | xxd -p")
    if bom_check.lower() == "efbbbf":
        print(f"ERROR: UTF-8 BOM detected in {tmp_path}!", flush=True)
        sys.exit(1)
    print(f"  No BOM confirmed in {tmp_path} (first bytes: {bom_check})", flush=True)

print("\n--- 3. INSTALLING FILES INTO THEME DIRECTORY ---", flush=True)
for _, tmp_path, target_path in files_to_deploy:
    print(f"Copying {tmp_path} -> {target_path}", flush=True)
    run_ssh(f"sudo cp {tmp_path} {target_path} && sudo chown www-data:www-data {target_path} && sudo chmod 664 {target_path}")

print("\n--- 4. CREATING WORDPRESS AGENTIC AI FOUNDATIONS PAGE IN DB ---", flush=True)
list_out = run_ssh("sudo docker exec lohnour-wordpress-1 wp post list --post_type=page --name=agentic-ai-foundations --fields=ID,post_name,post_parent,post_status --allow-root")
print(f"Current wp post list result:\n{list_out}", flush=True)

if "agentic-ai-foundations" not in list_out:
    print("Creating Agentic AI Foundations page under parent 150 (coaching)...", flush=True)
    create_out = run_ssh("sudo docker exec lohnour-wordpress-1 wp post create --post_type=page --post_title='Agentic AI Foundations' --post_name='agentic-ai-foundations' --post_parent=150 --post_status='publish' --page_template='page-agentic-ai-foundations.php' --allow-root")
    print(f"Create output: {create_out}", flush=True)
else:
    print("Agentic AI Foundations page already exists in wp_posts.", flush=True)

print("\n--- 5. FLUSHING REWRITE RULES ---", flush=True)
flush_out = run_ssh("sudo docker exec lohnour-wordpress-1 wp rewrite flush --allow-root")
print(f"Rewrite flush output: {flush_out}", flush=True)

print("\nDeployment completed successfully!", flush=True)
