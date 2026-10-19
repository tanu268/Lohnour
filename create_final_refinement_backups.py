import subprocess
import sys
import shutil

sys.stdout.reconfigure(encoding='utf-8')

# 1. Local backup
src = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php"
bak = r"D:\lohnourpro\current source\lohnourpro_wp_theme\page-agentic-ai-foundations.php.bak_pre_final_uiux_refinement"
shutil.copy2(src, bak)
print(f"Local backup created: {bak}")

# 2. Remote backup
SSH_KEY = r"D:\Downloads\ssh-key-2026-10-08.key"
HOST = "ubuntu@193.122.206.31"
TARGET_DIR = "/var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/themes/twentytwentyfive-child"

REMOTE_PAGE = f"{TARGET_DIR}/page-agentic-ai-foundations.php"
REMOTE_PAGE_BAK = f"{TARGET_DIR}/page-agentic-ai-foundations.php.bak_pre_final_uiux_refinement"

def run_ssh(cmd):
    full_cmd = ["ssh", "-T", "-i", SSH_KEY, "-o", "ConnectTimeout=15", "-o", "StrictHostKeyChecking=no", HOST, cmd]
    res = subprocess.run(full_cmd, capture_output=True, text=True)
    if res.returncode != 0:
        print(f"SSH FAIL: {cmd}\nSTDERR: {res.stderr}\nSTDOUT: {res.stdout}", flush=True)
        sys.exit(1)
    return res.stdout.strip()

print("--- CREATING REMOTE BACKUP OF PAGE TEMPLATE ---", flush=True)
backup_cmd = f"sudo cp {REMOTE_PAGE} {REMOTE_PAGE_BAK} && sudo ls -la {REMOTE_PAGE_BAK}"
out = run_ssh(backup_cmd)
print(f"Remote backup created:\n{out}", flush=True)
