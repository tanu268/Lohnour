import subprocess

ssh_key = r"D:\Downloads\ssh-key-2026-05-11.key"
host = "opc@141.148.51.166"

script = """
sudo python3 -c '
import re, json

with open("/var/lib/docker/volumes/portainer_data/_data/portainer.db", "rb") as f:
    content = f.read()

# Look for json-like structures
matches = re.findall(b"\\{[^{}]{10,}\\}", content)
found_stacks = []
for m in matches:
    try:
        data = json.loads(m.decode("utf-8", errors="ignore"))
        if any(k in str(data).lower() for k in ["stack", "compose", "lohnour"]):
            found_stacks.append(data)
    except Exception:
        pass

print(f"Total matching JSON records found: {len(found_stacks)}")
for s in found_stacks:
    print("--- RECORD ---")
    print(json.dumps(s, indent=2))
'
"""

p = subprocess.run(['ssh', '-i', ssh_key, '-o', 'BatchMode=yes', host, script],
                   capture_output=True, text=True)
print(p.stdout)
if p.stderr:
    print("STDERR:", p.stderr)

