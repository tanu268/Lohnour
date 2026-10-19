import subprocess
import sys

from deploy_config import get_ssh_config
SSH_KEY, HOST = get_ssh_config()

cmd = sys.argv[1] if len(sys.argv) > 1 else "echo OK"
ssh_cmd = [
    "ssh",
    "-i", SSH_KEY,
    "-o", "ConnectTimeout=10",
    HOST,
    cmd
]
proc = subprocess.run(ssh_cmd, capture_output=True, text=True)
print("STDOUT:\n" + proc.stdout)
if proc.stderr:
    print("STDERR:\n" + proc.stderr)
