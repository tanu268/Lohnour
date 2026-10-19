import subprocess
import sys

cmd = sys.argv[1] if len(sys.argv) > 1 else "echo OK"
ssh_cmd = [
    "ssh",
    "-i", "D:\\Downloads\\ssh-key-2026-10-08.key",
    "-o", "ConnectTimeout=10",
    "ubuntu@193.122.206.31",
    cmd
]
proc = subprocess.run(ssh_cmd, capture_output=True, text=True)
print("STDOUT:\n" + proc.stdout)
if proc.stderr:
    print("STDERR:\n" + proc.stderr)
