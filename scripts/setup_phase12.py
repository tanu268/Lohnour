import subprocess
import os
import sys

def main():
    # Read credentials from local .env
    env_vars = {}
    with open(r'd:\lohnourpro\.env', 'r', encoding='utf-8') as f:
        for line in f:
            line = line.strip()
            if '=' in line and not line.startswith('#'):
                k, v = line.split('=', 1)
                env_vars[k.strip()] = v.strip()

    required = ['DB_NAME', 'DB_USER', 'DB_PASSWORD', 'DB_ROOT_PASSWORD']
    for r in required:
        if r not in env_vars:
            raise ValueError(f'Missing required env var: {r}')

    ssh_key = r'D:\Downloads\ssh-key-2026-05-11.key'
    host = 'opc@141.148.51.166'

    # 1. Write /opt/lohnour/.env via SSH stdin
    print('[1/4] Writing /opt/lohnour/.env on remote host...')
    # Docker Compose expands $VAR in .env files; escape literal '$' as '$$' so Compose unescapes to '$'
    db_pass_escaped = env_vars["DB_PASSWORD"].replace("$", "$$")
    db_root_pass_escaped = env_vars["DB_ROOT_PASSWORD"].replace("$", "$$")

    remote_cmd = 'sudo sh -c "cat > /opt/lohnour/.env && chown root:root /opt/lohnour/.env && chmod 600 /opt/lohnour/.env"'
    p = subprocess.Popen(['ssh', '-i', ssh_key, '-o', 'BatchMode=yes', host, remote_cmd],
                         stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    env_payload = f'DB_NAME={env_vars["DB_NAME"]}\nDB_USER={env_vars["DB_USER"]}\nDB_PASSWORD={db_pass_escaped}\nDB_ROOT_PASSWORD={db_root_pass_escaped}\n'.encode('utf-8')
    stdout, stderr = p.communicate(input=env_payload)
    if p.returncode != 0:
        print('Failed to write .env:', stderr.decode())
        sys.exit(1)
    print('  Successfully written and permissioned /opt/lohnour/.env (0600 root:root)')

    # 2. Write /opt/lohnour/docker-compose.yml
    print('[2/4] Writing /opt/lohnour/docker-compose.yml...')
    compose_content = """name: lohnour-stack

services:
  # ----------------------------------------------------------------------------
  # Database Engine: MariaDB 10.11 LTS
  # ----------------------------------------------------------------------------
  db:
    image: mariadb@sha256:0de9840f23463c22eb4892d9bfd63086367e4bffe9b68ff4b62621d6eced854c
    container_name: lohnour-db
    restart: unless-stopped
    # Port 3306 is strictly private to lohnour_db_network; zero host ports exposed
    environment:
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD:?DB_ROOT_PASSWORD is required}
      MYSQL_DATABASE: ${DB_NAME:-lohnour_wp}
      MYSQL_USER: ${DB_USER:-lohnour_user}
      MYSQL_PASSWORD: ${DB_PASSWORD:?DB_PASSWORD is required}
    volumes:
      - lohnour_db_data:/var/lib/mysql
    networks:
      - lohnour_db_network
    healthcheck:
      test: ["CMD", "healthcheck.sh", "--connect", "--innodb_initialized"]
      interval: 10s
      timeout: 5s
      retries: 5
      start_period: 20s

  # ----------------------------------------------------------------------------
  # Web Application: WordPress (Apache runtime)
  # ----------------------------------------------------------------------------
  wordpress:
    image: wordpress:7.1.4-php8.2-apache
    container_name: lohnour-wordpress
    restart: unless-stopped
    # Application port is strictly private to the container network; zero host ports exposed
    environment:
      WORDPRESS_DB_HOST: db:3306
      WORDPRESS_DB_NAME: ${DB_NAME:-lohnour_wp}
      WORDPRESS_DB_USER: ${DB_USER:-lohnour_user}
      WORDPRESS_DB_PASSWORD: ${DB_PASSWORD:?DB_PASSWORD is required}
      WORDPRESS_TABLE_PREFIX: wp_
    volumes:
      - lohnour_wp_data:/var/www/html
      - /opt/lohnour/config/uploads.ini:/usr/local/etc/php/conf.d/uploads.ini:ro
    networks:
      - proxy_network
      - lohnour_db_network
    healthcheck:
      test: ["CMD-SHELL", "curl -f -s -H 'Host: pro.lohnour.com' http://localhost/ || exit 1"]
      interval: 15s
      timeout: 5s
      retries: 5
      start_period: 30s
    depends_on:
      db:
        condition: service_healthy

# ==============================================================================
# Volume Declarations (Pre-created production persistent volumes)
# ==============================================================================
volumes:
  lohnour_wp_data:
    external: true
    name: lohnour_wp_data
  lohnour_db_data:
    external: true
    name: lohnour_db_data

# ==============================================================================
# Virtual Network Declarations (Pre-created production networks)
# ==============================================================================
networks:
  proxy_network:
    external: true
    name: proxy_network
  lohnour_db_network:
    external: true
    name: lohnour_db_network
"""
    remote_cmd = 'sudo sh -c "cat > /opt/lohnour/docker-compose.yml && chown root:root /opt/lohnour/docker-compose.yml && chmod 644 /opt/lohnour/docker-compose.yml"'
    p = subprocess.Popen(['ssh', '-i', ssh_key, '-o', 'BatchMode=yes', host, remote_cmd],
                         stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    stdout, stderr = p.communicate(input=compose_content.encode('utf-8'))
    if p.returncode != 0:
        print('Failed to write docker-compose.yml:', stderr.decode())
        sys.exit(1)
    print('  Successfully written and permissioned /opt/lohnour/docker-compose.yml (0644 root:root)')

    # 3. Transfer /opt/lohnour/phase12_portainer_deploy.sh
    print('[3/4] Transferring phase12_portainer_deploy.sh...')
    scp_res = subprocess.run(['scp', '-i', ssh_key, '-o', 'BatchMode=yes',
                             r'd:\lohnourpro\scripts\phase12_portainer_deploy.sh',
                             f'{host}:/home/opc/phase12_portainer_deploy.sh'],
                            capture_output=True, text=True)
    if scp_res.returncode != 0:
        print('SCP failed:', scp_res.stderr)
        sys.exit(1)
    
    # 4. Verify & move to /opt/lohnour/
    print('[4/4] Verifying and moving script on remote host...')
    remote_setup = 'sudo mv /home/opc/phase12_portainer_deploy.sh /opt/lohnour/phase12_portainer_deploy.sh && sudo chown root:root /opt/lohnour/phase12_portainer_deploy.sh && sudo chmod 700 /opt/lohnour/phase12_portainer_deploy.sh && sudo bash -n /opt/lohnour/phase12_portainer_deploy.sh && ls -la /opt/lohnour'
    p = subprocess.run(['ssh', '-i', ssh_key, '-o', 'BatchMode=yes', host, remote_setup],
                       capture_output=True, text=True)
    if p.returncode != 0:
        print('Remote script setup failed:', p.stderr)
        sys.exit(1)
    print(p.stdout)
    print('Setup completed successfully!')

if __name__ == '__main__':
    main()

