# Lohnour Pro — Emergency Rollback & Recovery Runbook

**Target Host:** Sam's Oracle Cloud VM (`141.148.51.166` / `sams-oracle-vm`)  
**Production Directory:** `/opt/lohnour`  
**Governing Standard:** LOHNOUR PRO Fail-Closed Deployment Standard  
**Live Production URL:** `https://pro.lohnour.com`

---

## 1. Rollback Trigger Criteria

An immediate, emergency rollback must be initiated if any of the following occur during or immediately after a deployment:

1. **Route Failure:** Any of the 9 canonical application routes return `HTTP 5xx`, `HTTP 404`, or a blank white screen of death.
2. **Database Integrity Violation:** Table count drops below 14 or post count in `wp_posts` deviates unexpectedly from verified baseline (92 posts).
3. **Fatal PHP Errors:** Uncaught exceptions or syntax errors appear in container logs (`docker logs lohnour-wordpress`).
4. **TLS / Ingress Disruption:** Let's Encrypt TLS handshake fails or Caddy crashes.
5. **Workload Disruption:** Portainer CE (port 9443) or Node-RED (port 1880) become unreachable or restart.

---

## 2. Rollback Decision Matrix

| Failure Type | Affected Component | Fast-Path Rollback Action | Estimated Recovery Time |
|---|---|---|---|
| **Theme / File Bug** | `twentytwentyfive-child` | Restore theme files from latest pre-deploy archive | < 1 minute |
| **Compose / Config Bug** | `docker-compose.yml` | Restore `docker-compose.yml.bak_pre_phase13` & `up -d` | < 1 minute |
| **Ingress / Caddy Bug** | `Caddyfile` | Restore pre-deployment `Caddyfile` & `caddy reload` | < 30 seconds |
| **Database Corruption** | `lohnour-db` | Restore SQL snapshot from `/opt/lohnour/backups/` | < 3 minutes |
| **Catastrophic Failure** | All containers / volumes | Full Phase 8 / Phase 10-12 restore script execution | < 5 minutes |

---

## 3. Fast-Path Rollback Procedures

### Scenario A: Rollback Theme or Plugin Update

If the deployment introduced a theme defect or broken template:

1. Locate the pre-deployment theme backup on the server:
   ```bash
   ssh -i ~/.ssh/oracle_key.key opc@141.148.51.166 "ls -lat /opt/lohnour/backups/*theme*"
   ```

2. Restore the previous theme files into volume `lohnour_wp_data`:
   ```bash
   ssh -i ~/.ssh/oracle_key.key opc@141.148.51.166 "
     sudo rm -rf /var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/themes/twentytwentyfive-child/*
     sudo tar -xzf /opt/lohnour/backups/<PRE_DEPLOY_THEME_ARCHIVE>.tar.gz -C /var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/themes/twentytwentyfive-child/
     sudo chown -R 33:33 /var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/themes/twentytwentyfive-child/
     sudo find /var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/themes/twentytwentyfive-child/ -type d -exec chmod 755 {} +
     sudo find /var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/themes/twentytwentyfive-child/ -type f -exec chmod 644 {} +
   "
   ```

---

### Scenario B: Rollback Docker Compose or Caddy Configuration

If a container configuration change caused a container restart loop or networking error:

1. Restore the verified backup Compose file:
   ```bash
   ssh -i ~/.ssh/oracle_key.key opc@141.148.51.166 "
     cd /opt/lohnour
     sudo cp docker-compose.yml.bak_pre_phase13 docker-compose.yml
     sudo docker compose up -d
   "
   ```

2. If Caddy failed to reload, revert `Caddyfile`:
   ```bash
   ssh -i ~/.ssh/oracle_key.key opc@141.148.51.166 "
     cd /opt/lohnour
     sudo cp Caddyfile.bak Caddyfile
     sudo docker exec lohnour-caddy caddy reload --config /etc/caddy/Caddyfile
   "
   ```

---

### Scenario C: Rollback Production Database

If an errant script or database migration corrupted data in `lohnour_db_data`:

1. Identify the most recent clean checkpoint snapshot:
   ```bash
   ssh -i ~/.ssh/oracle_key.key opc@141.148.51.166 "ls -lat /opt/lohnour/backups/*.sql"
   ```

2. Restore the database directly inside the container without recreating volumes:
   ```bash
   ssh -i ~/.ssh/oracle_key.key opc@141.148.51.166 "
     DB_PASS=\$(sudo grep DB_ROOT_PASSWORD /opt/lohnour/.env | cut -d= -f2)
     sudo docker exec -i lohnour-db mysql -u root -p\"\${DB_PASS}\" lohnour_wp < /opt/lohnour/backups/<CLEAN_SNAPSHOT>.sql
   "
   ```

3. Verify database integrity post-restore:
   ```bash
   ssh -i ~/.ssh/oracle_key.key opc@141.148.51.166 "
     sudo python3 /opt/lohnour/scripts/inspect_wp_live.py
   "
   ```
   Must output: `tables: 14`, `posts: 92`, `siteurl: https://pro.lohnour.com`.

---

## 4. Post-Rollback Verification & Handover

After executing any rollback procedure:

1. **External Route Test:** Run `python scripts/test_9_canonical_routes.py` from client workstation. Verify 9/9 return `HTTP 200`.
2. **Container Health:** Check `sudo docker ps` to confirm zero restarts (`RestartCount: 0`).
3. **Notify Stakeholders:** Provide an incident summary with timestamps, root cause, and recovery confirmation.
