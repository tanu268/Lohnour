import subprocess

SSH_KEY = r"D:\Downloads\ssh-key-2026-10-08.key"
HOST = "ubuntu@193.122.206.31"

def run_ssh(cmd):
    full_cmd = ["ssh", "-T", "-i", SSH_KEY, "-o", "ConnectTimeout=15", "-o", "StrictHostKeyChecking=no", HOST, cmd]
    res = subprocess.run(full_cmd, capture_output=True, text=True)
    if res.returncode != 0:
        print(f"SSH FAIL: {cmd}\nSTDERR: {res.stderr}\nSTDOUT: {res.stdout}")
    return res.stdout.strip()

print("--- Checking existing posts with slug 'faqs' ---")
check_cmd = "sudo docker exec lohnour-wordpress-1 wp post list --post_type=page --name=faqs --allow-root"
print(run_ssh(check_cmd))

print("\n--- Creating /faqs/ page ---")
content = "Find answers to common questions about Lohnour learning programs, executive coaching, and technical curriculums. For personalized guidance or cohort admissions, please contact our team."
create_cmd = f"sudo docker exec lohnour-wordpress-1 wp post create --post_type=page --post_title='Frequently Asked Questions' --post_name='faqs' --post_content='{content}' --post_status=publish --allow-root"
print(run_ssh(create_cmd))

print("\n--- Flushing rewrite rules and cache ---")
print(run_ssh("sudo docker exec lohnour-wordpress-1 wp rewrite flush --allow-root"))
print(run_ssh("sudo docker exec lohnour-wordpress-1 wp cache flush --allow-root"))
