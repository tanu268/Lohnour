import subprocess
from deploy_config import get_ssh_config
SSH_KEY, HOST = get_ssh_config()

# 1. First, check bytes before modification
check_cmd = "sudo docker exec lohnour-wordpress-1 od -A x -t x1 /var/www/html/wp-content/themes/twentytwentyfive-child/inc/contact-form.php | head -n 2"
res = subprocess.run(["ssh", "-i", SSH_KEY, HOST, check_cmd], capture_output=True, text=True)
print("BEFORE:\n" + res.stdout)

# 2. Backup and strip exactly the first 3 bytes (BOM)
strip_script = """
set -e
sudo docker exec lohnour-wordpress-1 cp /var/www/html/wp-content/themes/twentytwentyfive-child/inc/contact-form.php /var/www/html/wp-content/themes/twentytwentyfive-child/inc/contact-form.php.bak
sudo docker exec lohnour-wordpress-1 sh -c 'tail -c +4 /var/www/html/wp-content/themes/twentytwentyfive-child/inc/contact-form.php.bak > /var/www/html/wp-content/themes/twentytwentyfive-child/inc/contact-form.php'
"""
res2 = subprocess.run(["ssh", "-i", SSH_KEY, HOST, strip_script], capture_output=True, text=True)
print("STRIP RESULT:\n" + res2.stdout)
if res2.stderr:
    print("STRIP STDERR:\n" + res2.stderr)

# 3. Verify bytes after modification
res3 = subprocess.run(["ssh", "-i", SSH_KEY, HOST, check_cmd], capture_output=True, text=True)
print("AFTER:\n" + res3.stdout)

# 4. Also check Apache error log
log_cmd = "sudo docker exec lohnour-wordpress-1 tail -n 10 /var/log/apache2/error.log"
res4 = subprocess.run(["ssh", "-i", SSH_KEY, HOST, log_cmd], capture_output=True, text=True)
print("APACHE LOG:\n" + res4.stdout)
