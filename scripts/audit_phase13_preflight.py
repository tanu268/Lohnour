import subprocess

ssh_key = r"D:\Downloads\ssh-key-2026-05-11.key"
host = "opc@141.148.51.166"

script = """
echo "=== 1. HOST IDENTITY & PUBLIC IP ==="
curl -s https://ifconfig.me; echo ""
uname -m -r
cat /etc/os-release | grep -E "PRETTY_NAME|VERSION="

echo "=== 2. DOCKER & COMPOSE VERSIONS ==="
sudo docker --version
sudo docker compose version

echo "=== 3. CONTAINER INVENTORY ==="
sudo docker ps -a --format "table {{.Names}}\t{{.Status}}\t{{.Image}}\t{{.Ports}}"

echo "=== 4. VOLUME INVENTORY ==="
sudo docker volume ls

echo "=== 5. NETWORK INVENTORY ==="
sudo docker network ls

echo "=== 6. DISK SPACE & MEMORY HEADROOM ==="
df -h /
free -h
nproc

echo "=== 7. PORT LISTENERS (80, 443, 9443, 1880, 3306) ==="
sudo ss -tulpn | grep -E ':(80|443|9443|1880|3306)' || echo "NO_MATCHING_LISTENERS"

echo "=== 8. FIREWALLD & SELINUX STATUS ==="
sudo systemctl status firewalld --no-pager | head -n 5
sudo firewall-cmd --list-all || echo "FIREWALLD_NOT_RUNNING"
getenforce

echo "=== 9. EXISTING CADDY STATE ==="
which caddy 2>/dev/null || echo "caddy binary: not on host"
sudo docker ps -a --filter "name=caddy"
sudo ls -la /etc/caddy 2>/dev/null || echo "/etc/caddy does not exist"

echo "=== 10. DNS RESOLUTION FOR pro.lohnour.com ==="
dig +short pro.lohnour.com || nslookup pro.lohnour.com
"""

p = subprocess.run(['ssh', '-i', ssh_key, '-o', 'BatchMode=yes', host, script],
                   capture_output=True, text=True)
print(p.stdout)
if p.stderr:
    print("STDERR:", p.stderr)

