"""
Lohnour Deployment & Remote Configuration Helper
Safely loads credentials and paths from environment or local .env
"""
import os
import sys

def load_local_env():
    """Load variables from .env if present in root or parent directories."""
    env_path = os.path.join(os.path.dirname(os.path.abspath(__file__)), ".env")
    if os.path.exists(env_path):
        try:
            with open(env_path, "r", encoding="utf-8") as f:
                for line in f:
                    line = line.strip()
                    if line and not line.startswith("#") and "=" in line:
                        k, v = line.split("=", 1)
                        os.environ.setdefault(k.strip(), v.strip().strip('"').strip("'"))
        except Exception:
            pass

load_local_env()

def get_ssh_config(require_key=True):
    """
    Retrieve SSH_KEY and HOST configuration from environment.
    Validates presence and existence of key if require_key is True.
    """
    ssh_key = os.environ.get("LOHNOUR_SSH_KEY")
    host = os.environ.get("LOHNOUR_SSH_HOST", "ubuntu@193.122.206.31")

    if require_key:
        if not ssh_key:
            print("ERROR: Environment variable LOHNOUR_SSH_KEY is not set.", file=sys.stderr)
            print("Please set LOHNOUR_SSH_KEY in your environment or in a local .env file pointing to your private SSH key.", file=sys.stderr)
            sys.exit(1)
        if not os.path.exists(ssh_key):
            print(f"ERROR: SSH key file not found at: {ssh_key}", file=sys.stderr)
            sys.exit(1)

    return ssh_key, host
