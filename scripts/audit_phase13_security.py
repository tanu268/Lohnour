import subprocess
import json

def run_cmd(cmd):
    p = subprocess.run(cmd, shell=True, capture_output=True, text=True)
    return p.stdout.strip(), p.stderr.strip(), p.returncode

print("=== PHASE 13 SECURITY AUDIT ===")

# 1. Container port bindings
containers = ["lohnour-db", "lohnour-wordpress", "lohnour-caddy", "portainer", "nodered"]
for c in containers:
    ports, _, _ = run_cmd(f"docker inspect {c} --format '{{{{json .HostConfig.PortBindings}}}}'")
    print(f"Container {c:20} Host PortBindings: {ports}")

# 2. Host listeners
ss_out, _, _ = run_cmd("sudo ss -tulpn | grep -E ':(80|443|1880|3306|8000|9443)\\b'")
print(f"\nHost Listeners:\n{ss_out}")

# 3. .env permissions
env_stat, _, _ = run_cmd("stat -c '%a %U:%G' /opt/lohnour/.env")
print(f"\n.env Permissions: {env_stat}")

# 4. Volumes check
vols, _, _ = run_cmd("docker volume ls --format '{{.Name}}'")
print(f"\nPersistent Volumes:\n{vols}")

# 5. Caddy persistent certificate storage mount
caddy_mounts, _, _ = run_cmd("docker inspect lohnour-caddy --format '{{range .Mounts}}{{.Name}} -> {{.Destination}} ({{.Type}}){{\\n}}{{end}}'")
print(f"\nCaddy Mounts:\n{caddy_mounts}")

# 6. Protected workloads status
portainer_state, _, _ = run_cmd("docker inspect portainer --format '{{.State.Status}} (Up: {{.State.StartedAt}})'")
nodered_state, _, _ = run_cmd("docker inspect nodered --format '{{.State.Status}} (Up: {{.State.StartedAt}})'")
print(f"\nProtected Workloads:")
print(f"  Portainer: {portainer_state}")
print(f"  Node-RED:  {nodered_state}")

# 7. Post deployment resource usage
df_out, _, _ = run_cmd("df -h /")
free_out, _, _ = run_cmd("free -h")
print(f"\nStorage:\n{df_out}")
print(f"\nMemory:\n{free_out}")

