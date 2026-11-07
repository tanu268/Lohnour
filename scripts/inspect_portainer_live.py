import re
import json

db_path = "/var/lib/docker/volumes/portainer_data/_data/portainer.db"
try:
    with open(db_path, "rb") as f:
        data = f.read()
    print(f"Read {len(data)} bytes from {db_path}")

    # Search for any lohnour strings
    lohnour_tokens = set(re.findall(rb"[a-zA-Z0-9_\-\.\/]{3,50}lohnour[a-zA-Z0-9_\-\.\/]{0,50}", data, re.IGNORECASE))
    print(f"Found {len(lohnour_tokens)} tokens matching 'lohnour':")
    for t in sorted(lohnour_tokens):
        print(f"  {t.decode('utf-8', errors='replace')}")

    # Search for stack-related JSON entries
    json_candidates = re.findall(rb"\{[^{}]{10,500}\}", data)
    print(f"\nScanning {len(json_candidates)} JSON objects in portainer.db:")
    stack_entries = []
    for c in json_candidates:
        if b"lohnour" in c.lower() or b"stack" in c.lower():
            try:
                parsed = json.loads(c.decode('utf-8', errors='replace'))
                stack_entries.append(parsed)
            except Exception:
                pass

    print(f"Found {len(stack_entries)} candidate JSON entries:")
    for entry in stack_entries[:10]:
        print(" ", entry)

except Exception as e:
    print(f"Error: {e}")

