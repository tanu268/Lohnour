# Lohnour Pro — Environment Variables Reference

This document provides an exhaustive inventory and explanation of all environment variables used across the **Lohnour Pro** ecosystem.

---

## 1. Production Variables Reference (`/opt/lohnour/.env`)

On the production Oracle Cloud VM, variables are stored in `/opt/lohnour/.env` with strict permissions (`0600 root:root`). These are injected into containers by `docker-compose.yml`.

| Variable Name | Required? | Default Value | Description |
|---|---|---|---|
| `DB_NAME` | Optional | `lohnour_wp` | Target MariaDB database schema name for WordPress. |
| `DB_USER` | Optional | `lohnour_user` | Dedicated unprivileged database user account for WordPress. |
| `DB_PASSWORD` | **REQUIRED** | *None* | High-entropy password for `DB_USER`. |
| `DB_ROOT_PASSWORD` | **REQUIRED** | *None* | Administrative root password for the MariaDB database engine. |

> [!CAUTION]
> Real values for `DB_PASSWORD` and `DB_ROOT_PASSWORD` must NEVER be committed to version control. They exist only on the production VM in `/opt/lohnour/.env`.

---

## 2. Local Development Variables (`.env`)

For developers running `docker-compose.local.yml` locally:

| Variable Name | Required? | Default Value | Description |
|---|---|---|---|
| `LOCAL_DB_NAME` | Optional | `lohnour_local_wp` | Local MariaDB schema name. |
| `LOCAL_DB_USER` | Optional | `lohnour_local_user` | Local database user account. |
| `LOCAL_DB_PASSWORD` | **REQUIRED** | *None* | Password for local MariaDB user. |
| `LOCAL_DB_ROOT_PASSWORD` | **REQUIRED** | *None* | Password for local MariaDB root user. |

---

## 3. Remote Deployment & Automation Variables

Used by deployment and verification scripts (e.g., `scripts/setup_phase12.py`, `run_ssh.py`):

| Variable Name | Required? | Default Value | Description |
|---|---|---|---|
| `LOHNOUR_SSH_KEY` | Optional | *None* | Absolute path to the operator's private SSH key (`.key` or `.pem`). |
| `LOHNOUR_SSH_HOST` | Optional | `opc@141.148.51.166` | Target SSH connection string (`user@ip`). |
| `LOHNOUR_TEST_USER` | Optional | *None* | WordPress administrator username for automated Playwright login tests. |
| `LOHNOUR_TEST_PASSWORD` | Optional | *None* | WordPress administrator password for automated testing. |
| `LOHNOUR_TEST_URL` | Optional | `http://localhost:8081/wp-admin` | Target login URL for automated Playwright end-to-end tests. |

---

## 4. Credential Rotation Protocols

### Database Password Rotation
To rotate `DB_PASSWORD`:
1. Generate a new high-entropy password:
   ```bash
   openssl rand -hex 24
   ```
2. Update the user password in MariaDB:
   ```bash
   sudo docker exec -it lohnour-db mysql -u root -p lohnour_wp -e "ALTER USER 'lohnour_user'@'%' IDENTIFIED BY '<NEW_PASSWORD>'; FLUSH PRIVILEGES;"
   ```
3. Update `/opt/lohnour/.env` with the new value.
4. Restart the WordPress container to ingest the new environment:
   ```bash
   sudo docker compose -f /opt/lohnour/docker-compose.yml up -d wordpress
   ```

### WordPress Admin Credential Rotation
If an administrative credential is compromised or flagged during security audits:
1. Rotate the password via WP-CLI directly inside the production container:
   ```bash
   sudo docker exec lohnour-wordpress wp user update samarpit --user_pass='<NEW_STRONG_SECURE_PASSWORD>' --allow-root
   ```
2. Verify authentication by logging into `https://pro.lohnour.com/wp-login.php`.
