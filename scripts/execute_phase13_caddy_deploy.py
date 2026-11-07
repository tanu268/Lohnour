import subprocess
import sys

ssh_key = r"D:\Downloads\ssh-key-2026-05-11.key"
host = "opc@141.148.51.166"

import time

def run_ssh(cmd, fail_on_error=True, max_retries=3):
    print(f"\n>>> Running remote: {cmd}")
    for attempt in range(1, max_retries + 1):
        p = subprocess.run(['ssh', '-i', ssh_key, '-o', 'BatchMode=yes', '-o', 'ConnectTimeout=10', host, cmd],
                           capture_output=True, text=True)
        if p.returncode == 255 and attempt < max_retries:
            print(f"Warning: SSH error 255 on attempt {attempt}, retrying in 3s...")
            time.sleep(3)
            continue
        if p.stdout:
            print("STDOUT:\n" + p.stdout)
        if p.stderr:
            print("STDERR:\n" + p.stderr)
        if fail_on_error and p.returncode != 0:
            print(f"FATAL: Command failed with exit code {p.returncode}")
            sys.exit(p.returncode)
        return p.stdout, p.stderr, p.returncode

def scp_file(local_path, remote_path, max_retries=3):
    print(f"\n>>> Uploading {local_path} to {remote_path}...")
    for attempt in range(1, max_retries + 1):
        p = subprocess.run(['scp', '-i', ssh_key, '-o', 'BatchMode=yes', '-o', 'ConnectTimeout=10', local_path, f"{host}:{remote_path}"],
                           capture_output=True, text=True)
        if p.returncode != 0 and attempt < max_retries:
            print(f"Warning: SCP failed on attempt {attempt}, retrying in 3s...")
            time.sleep(3)
            continue
        if p.returncode != 0:
            print(f"FATAL: SCP failed: {p.stderr}")
            sys.exit(p.returncode)
        print("Upload complete.")
        return

print("==============================================================================")
print("PHASE 13: CADDY REVERSE PROXY DEPLOYMENT")
print("==============================================================================")

# Step 1: Backup docker-compose.yml on remote
run_ssh("sudo cp -p /opt/lohnour/docker-compose.yml /opt/lohnour/docker-compose.yml.bak_pre_phase13 && sudo ls -la /opt/lohnour/docker-compose.yml.bak_pre_phase13")

# Step 2: Upload Caddyfile to /tmp and move to /opt/lohnour/Caddyfile
scp_file("d:/lohnourpro/Caddyfile", "/tmp/Caddyfile")
run_ssh("sudo mv /tmp/Caddyfile /opt/lohnour/Caddyfile && sudo chown root:root /opt/lohnour/Caddyfile && sudo chmod 644 /opt/lohnour/Caddyfile && sudo ls -la /opt/lohnour/Caddyfile")

# Step 3: Validate Caddyfile with immutable image
run_ssh("sudo docker run --rm -v /opt/lohnour/Caddyfile:/etc/caddy/Caddyfile:ro caddy@sha256:78bd35025c7ba85961dd149ae670315129954e77abe2383ac47c89106b35ade9 caddy validate --config /etc/caddy/Caddyfile")

# Step 4: Upload updated docker-compose.yml to /tmp and move to /opt/lohnour/docker-compose.yml
scp_file("d:/lohnourpro/docker-compose.yml", "/tmp/docker-compose.yml")
run_ssh("sudo mv /tmp/docker-compose.yml /opt/lohnour/docker-compose.yml && sudo chown root:root /opt/lohnour/docker-compose.yml && sudo chmod 644 /opt/lohnour/docker-compose.yml && sudo ls -la /opt/lohnour/docker-compose.yml")

# Step 5: Validate Compose configuration
run_ssh("cd /opt/lohnour && sudo docker compose -p lohnour-stack -f /opt/lohnour/docker-compose.yml config --quiet && echo 'Docker Compose syntax is VALID'")

# Step 6: Deploy Caddy service only
run_ssh("cd /opt/lohnour && sudo docker compose -p lohnour-stack -f /opt/lohnour/docker-compose.yml up -d caddy")

# Step 7: Inspect container inventory and verify no disruption to existing services
run_ssh("sudo docker ps --format 'table {{.Names}}\t{{.Status}}\t{{.Image}}\t{{.Ports}}'")

# Step 8: Verify listeners on ports 80 and 443
run_ssh("sudo ss -tulpn | grep -E ':(80|443)\\b'")

# Step 9: View Caddy startup logs
run_ssh("sudo docker logs --tail 30 lohnour-caddy")
