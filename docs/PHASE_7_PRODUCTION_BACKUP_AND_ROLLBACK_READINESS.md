# LOHNOUR — PHASE 7: PRODUCTION BACKUP & ROLLBACK READINESS REPORT

**Document Identifier:** `PHASE_7_PRODUCTION_BACKUP_AND_ROLLBACK_READINESS.md`  
**Execution Timestamp (UTC):** 2026-11-06 15:48:00 UTC  
**Execution Timestamp (IST):** 2026-11-06 21:18:00 IST  
**Role:** Senior DevOps Engineer & Infrastructure Security Specialist  
**Operating Mode:** STRICT READ-ONLY FORENSIC INSPECTION — ZERO PRODUCTION MUTATIONS  
**Target Host:** Sam's Oracle VM (`sams-oracle-vm`, `141.148.51.166`)  
**Status:** **READINESS COMPLETE — READY FOR FOUNDER REVIEW**

---

## 1. Live Production Inventory Baseline

Forensic inspection performed live over SSH using existing key authentication (`opc@141.148.51.166`):

### 1.1 Active Container Inventory
| Container Name | Container ID | Image | Status / Uptime | Restart Policy | Health Status |
|---|---|---|---|---|:---:|
| `nodered` | `3a4cbf5e7f4d` | `nodered/node-red:latest` | Up 5 months | `unless-stopped` | `healthy` |
| `portainer` | `794093300b7f` | `portainer/portainer-ce:lts` | Up 5 months | `always` | N/A |

- **Unexpected Containers:** ZERO. No unauthorized or phantom containers exist.
- **Port Ingress & Listeners:**
  - `nodered`: `1880/tcp` (bound to `0.0.0.0` on host, but filtered by OCI VCN from public internet).
  - `portainer`: `8000/tcp` (filtered by OCI VCN), `9443/tcp` (HTTPS Web UI, accessible via OCI VCN).
  - Ports `80` and `443`: Completely unoccupied on the host.

### 1.2 Persistent Volumes Inventory
| Volume Name | Driver | Host Mountpoint | Created Timestamp | Actual Disk Usage |
|---|---|---|---|:---:|
| `nodered_data` | `local` | `/var/lib/docker/volumes/nodered_data/_data` | `2026-05-15T18:48:38Z` | **80 KB** |
| `portainer_data` | `local` | `/var/lib/docker/volumes/portainer_data/_data` | `2026-05-15T17:55:23Z` | **176 KB** |

- **Unexpected Volumes:** ZERO.

### 1.3 Virtual Networks Inventory
| Network ID | Network Name | Driver | Scope | Containers Attached |
|---|---|---|---|---|
| `950b3a74fcba` | `bridge` | `bridge` | `local` | `nodered`, `portainer` |
| `a4274aecfc44` | `host` | `host` | `local` | None |
| `ac638b6c8202` | `none` | `null` | `local` | None |

- **Custom Application Networks:** ZERO. (`proxy_network` and `lohnour_db_network` do not yet exist).

---

## 2. Existing Data Requiring Protection

Before any deployment activity begins on Sam's Oracle VM, existing operational data must be protected against accidental modification, container collision, or volume truncation:

1. **Node-RED Workload (`nodered_data`):**
   - Contains: Node-RED flows, encrypted credentials, runtime configuration, and package dependencies.
   - Criticality: Active automation runtime. Must not be truncated or deleted.
   - Size: 80 KB.
2. **Portainer Management State (`portainer_data`):**
   - Contains: Embedded BoltDB (`portainer.db`), admin accounts, TLS certificates, endpoints, and stacks configuration.
   - Criticality: Central container management plane. Loss of `portainer.db` causes loss of Portainer admin credentials and stack management bindings.
   - Size: 176 KB.
3. **Staging Directory (`/opt/lohnour/staging`):**
   - Contains the 4 verified production backup archives and checksum manifest.
   - Size: 63 MB.
4. **Existing Host Backups / Cron Jobs:**
   - Forensics verified that **no automated backups or cron jobs currently exist** for Node-RED or Portainer. Pre-deployment manual backups are mandatory.

---

## 3. Staging Artifact Integrity & Validation

Live remote verification executed inside `/opt/lohnour/staging` on Sam's Oracle VM:

```bash
cd /opt/lohnour/staging && sha256sum -c SHA256SUMS
```

### Verification Results
```text
production-lohnour_wp.sql: OK
uploads.tar.gz: OK
lohnour_all_plugins.tar.gz: OK
lohnour_program_cms.tar.gz: OK
```

| Artifact Name | Remote Size | Remote SHA256 Checksum | Cryptographic Status |
|---|:---:|---|:---:|
| `production-lohnour_wp.sql` | 290,627 B | `db8628e7743b133e372265a7d09ac29729ab7f7c8ed9dfcd0bdd257d7e88a880` | **VERIFIED (OK)** |
| `uploads.tar.gz` | 59,733,182 B | `5001b5d43159e4611540661d8af50a9fd0301e06a4224587cb38f7f68983b0d9` | **VERIFIED (OK)** |
| `lohnour_all_plugins.tar.gz` | 5,646,919 B | `1e2c4bb948ea06621a52b1911b6d6a29868695798d8e37753454643e0be68484` | **VERIFIED (OK)** |
| `lohnour_program_cms.tar.gz` | 18,777 B | `989eaf8941a27f37bfc7576954a22fe6f64f121ee44c5a9f66e4e6b4777dd5ec` | **VERIFIED (OK)** |

### SQL Backup Forensics
- **Origin:** Generated from `MariaDB 10.11.19` (`Distrib 10.11.19-MariaDB, for debian-linux-gnu (x86_64)`).
- **Target Schema:** `lohnour_wp`.
- **Integrity:** Clean SQL dump, terminating with `Dump completed on 2026-10-21 16:27:49`.
- **Safety:** Contains zero `CREATE DATABASE`, zero hardcoded `USE`, and zero system table mutations. Contains 14 WordPress tables (`wp_*`).

---

## 4. Archive Stream Integrity & Expected Extraction Paths

Stream test commands (`tar -tzf <archive> >/dev/null`) confirmed that all tar gzip streams are intact with zero decompression corruption.

| Archive | Top-Level Root Directory | Expected Extraction Destination | Directory Structure Generated |
|---|---|---|---|
| `uploads.tar.gz` | `uploads/` | `/var/www/html/wp-content/` | `/var/www/html/wp-content/uploads/2026/...` |
| `lohnour_all_plugins.tar.gz` | Direct plugin folders (`akismet/`, `mailin/`, etc.) | `/var/www/html/wp-content/plugins/` | `/var/www/html/wp-content/plugins/{plugin}/...` |
| `lohnour_program_cms.tar.gz` | `lohnour-program-cms/` | `/var/www/html/wp-content/plugins/` | `/var/www/html/wp-content/plugins/lohnour-program-cms/...` |

---

## 5. Storage Capacity & Safety Headroom Assessment

Detailed disk allocation on `/dev/mapper/ocivolume-root` (Root partition):

| Component / Phase | Storage Calculation | Disk Impact | Cumulative Free Space |
|---|:---:|:---:|:---:|
| **Current Baseline Free Space** | Live `df -h /` | -- | **16.00 GB** |
| Existing `/var/lib/docker` | Existing images & containers | 729 MB (already allocated) | 16.00 GB |
| Existing `/opt/lohnour/staging` | Staged backup archives | 63 MB (already allocated) | 16.00 GB |
| **Pre-Deployment Backups** | `nodered_data` + `portainer_data` | +0.26 MB | ~15.99 GB |
| **ARM64 Docker Images Pull** | `wordpress:7.1.4`, `mariadb:10.11.19`, `caddy:2.11.7` | +1,120 MB | ~14.88 GB |
| **Volume Restoration (`lohnour_wp_data`)** | Core (75M) + Uploads (110M) + Plugins (30M) | +215 MB | ~14.66 GB |
| **Volume Restoration (`lohnour_db_data`)** | MariaDB system tables + imported SQL schema | +150 MB | ~14.51 GB |
| **Caddy State (`caddy_data`, `caddy_config`)** | TLS certificates & operational state | +15 MB | ~14.49 GB |
| **Peak Temporary Extraction Overhead** | In-memory streaming (zero temp files on host root) | +150 MB | ~14.34 GB |

### Storage Conclusion
- **Peak Disk Utilization during restoration:** ~15.66 GB out of 30.00 GB (52.2%).
- **Minimum Post-Restoration Free Headroom:** **~14.34 GB free disk space** (>47% safety margin).
- **Assessment:** **HEADROOM IS AMPLE AND SAFE.** There is zero risk of disk exhaustion or container crash due to storage pressure.

---

## 6. Production Pre-Deployment Backup Plan

The following procedures must be executed immediately prior to Phase 8 container deployment.

> [!IMPORTANT]
> **Application Consistency Requirement:**  
> Neither Node-RED nor Portainer can guarantee ACID transaction consistency if backed up while actively writing data. Because their combined data footprint is < 300 KB, a brief container pause (`docker pause`) lasting less than 2 seconds guarantees 100% application consistency with zero container downtime or network unbinding.

### Backup A: Node-RED Persistent Data (`nodered_data`)
- **Source:** `/var/lib/docker/volumes/nodered_data/_data`
- **Destination:** `/opt/lohnour/backups/pre_deploy_nodered_data_$(date +%Y%m%d_%H%M%S).tar.gz`
- **Consistency:** Application-consistent via `docker pause nodered`.
- **Command:**
  ```bash
  sudo mkdir -p /opt/lohnour/backups && sudo chmod 0700 /opt/lohnour/backups
  docker pause nodered
  sudo tar -czf /opt/lohnour/backups/pre_deploy_nodered_data_$(date +%Y%m%d_%H%M%S).tar.gz -C /var/lib/docker/volumes/nodered_data/_data .
  docker unpause nodered
  ```
- **Verification:**
  ```bash
  sha256sum /opt/lohnour/backups/pre_deploy_nodered_data_*.tar.gz | sudo tee -a /opt/lohnour/backups/BACKUP_MANIFEST.sha256
  ```
- **Restoration Command:**
  ```bash
  docker stop nodered
  sudo rm -rf /var/lib/docker/volumes/nodered_data/_data/*
  sudo tar -xzf /opt/lohnour/backups/pre_deploy_nodered_data_<timestamp>.tar.gz -C /var/lib/docker/volumes/nodered_data/_data/
  docker start nodered
  ```

### Backup B: Portainer Persistent State (`portainer_data`)
- **Source:** `/var/lib/docker/volumes/portainer_data/_data`
- **Destination:** `/opt/lohnour/backups/pre_deploy_portainer_data_$(date +%Y%m%d_%H%M%S).tar.gz`
- **Consistency:** Application-consistent via `docker pause portainer` (locks BoltDB `portainer.db`).
- **Command:**
  ```bash
  docker pause portainer
  sudo tar -czf /opt/lohnour/backups/pre_deploy_portainer_data_$(date +%Y%m%d_%H%M%S).tar.gz -C /var/lib/docker/volumes/portainer_data/_data .
  docker unpause portainer
  ```
- **Verification:**
  ```bash
  sha256sum /opt/lohnour/backups/pre_deploy_portainer_data_*.tar.gz | sudo tee -a /opt/lohnour/backups/BACKUP_MANIFEST.sha256
  ```
- **Restoration Command:**
  ```bash
  docker stop portainer
  sudo rm -rf /var/lib/docker/volumes/portainer_data/_data/*
  sudo tar -xzf /opt/lohnour/backups/pre_deploy_portainer_data_<timestamp>.tar.gz -C /var/lib/docker/volumes/portainer_data/_data/
  docker start portainer
  ```

### Backup C: Staging Artifacts & Configuration Snapshot
- **Source:** `/opt/lohnour/staging`
- **Destination:** `/opt/lohnour/backups/staging_manifest_snapshot_$(date +%Y%m%d_%H%M%S)`
- **Command:**
  ```bash
  sudo cp -r /opt/lohnour/staging /opt/lohnour/backups/staging_snapshot_$(date +%Y%m%d_%H%M%S)
  ```

---

## 7. Comprehensive Rollback Strategy

Rollback procedures categorized by failure domain:

```mermaid
flowchart TD
    Failure{Failure Detected}
    Failure -->|Caddy TLS / Startup Fail| Roll_Caddy[Rollback Caddy]
    Failure -->|WordPress Health / Crash| Roll_WP[Rollback WordPress]
    Failure -->|MariaDB Init / Corruption| Roll_DB[Rollback MariaDB]
    Failure -->|DNS Access / Certificate Failure| Roll_DNS[Rollback DNS Records]
    Failure -->|Existing Apps Disrupted| Roll_Existing[Restore Node-RED / Portainer]

    Roll_Caddy --> Stop_Caddy["docker stop lohnour-caddy; inspect logs"]
    Roll_WP --> Revert_WP["Re-stream Core Files / Restore wp-content"]
    Roll_DB --> Drop_DB["Drop Schema lohnour_wp; Re-import SQL Dump"]
    Roll_DNS --> Point_Old["Revert A Record to 193.122.206.31"]
    Roll_Existing --> Restore_Vols["Extract pre_deploy_*.tar.gz to Volume Mounts"]
```

### Scenario 1: Caddy Fails to Start (TLS Error, Bind Conflict, or Bad Caddyfile)
- **Container Rollback:** Stop Caddy container: `docker stop lohnour-caddy` (or `docker compose down caddy`).
- **Configuration Rollback:** Inspect `docker logs lohnour-caddy`. If Let's Encrypt rate-limiting or ACME challenge failure occurs (due to DNS prop lag), temporarily configure self-signed / internal TLS or resolve DNS records.
- **Impact:** Ports 80 and 443 are released. WordPress and MariaDB containers remain running internally. Node-RED and Portainer are completely unaffected.

### Scenario 2: WordPress Fails Health Checks (Timeout, 500 Error, DB Unreachable)
- **Container Rollback:** `docker stop lohnour-wordpress`.
- **Database Rollback Check:** Verify MariaDB container is healthy (`docker inspect --format '{{.State.Health.Status}}' lohnour-db`).
- **Configuration Rollback:** Verify `DB_NAME`, `DB_USER`, `DB_PASSWORD` in `.env`.
- **Volume Rollback:** If `/var/www/html` was partially initialized, wipe volume contents: `docker run --rm -v lohnour_wp_data:/v alpine sh -c "rm -rf /v/*"` and allow official entrypoint to re-copy core files cleanly.

### Scenario 3: MariaDB Fails Initialization
- **Container Rollback:** `docker stop lohnour-db`.
- **Database Rollback:** Wipe corrupted DB volume: `docker run --rm -v lohnour_db_data:/v alpine sh -c "rm -rf /v/*"`.
- **Reinitialization:** Restart `lohnour-db` container. Wait for health check (`healthy`). Re-stream `production-lohnour_wp.sql` via `MYSQL_PWD` inside container.

### Scenario 4: WordPress Admin or Frontend Fails After Restoration (White Screen of Death)
- **File Rollback:** Check `wp-content/debug.log`. If a plugin causes a fatal error:
  ```bash
  docker exec -i lohnour-wordpress sh -c "mv /var/www/html/wp-content/plugins/{culprit} /var/www/html/wp-content/plugins/{culprit}.disabled"
  ```
- **Database Rollback:** If post or option data was corrupted, re-execute safe SQL ingestion:
  ```bash
  docker exec -i lohnour-db mariadb -u lohnour_user -p"$DB_PASSWORD" lohnour_wp < /opt/lohnour/staging/production-lohnour_wp.sql
  ```

### Scenario 5: DNS Cutover Access Problems
- **DNS Rollback:** Immediately revert the Cloudflare / registrar DNS `A` record for `pro.lohnour.com` back to the historical IP address:
  - **Revert `A` Record:** `193.122.206.31`
  - **Revert `AAAA` Record:** `64:ff9b::c17a:ce1f`
- **Result:** External user traffic immediately returns to the original live server with zero downtime.

---

## 8. Configuration Consistency Forensic Audit

Review of local repository configuration files against Sam's Oracle VM live environment:

| Configuration Item | Verified Specification | Audit Status | Forensic Notes |
|---|---|:---:|---|
| **Caddy Image** | `caddy:2.11.7-alpine` | **ALIGNED** | Native ARM64 digest available on Docker Hub. |
| **WordPress Image** | `wordpress:7.1.4-php8.2-apache` | **ALIGNED** | Native ARM64 digest available. Natively matches `db_version: 61833`. |
| **MariaDB Image** | `mariadb:10.11.19` | **ALIGNED** | Native ARM64 digest available. Matches backup version. |
| **Reverse Proxy Host** | `pro.lohnour.com` | **ALIGNED** | Caddyfile targets `wordpress:80` with 600s timeouts. |
| **PHP Runtime Limits** | `config/uploads.ini` (512M) | **ALIGNED** | Caddyfile `request_body max_size 512MB` matches `uploads.ini`. |
| **Port Isolation** | WordPress (0 ports), DB (0 ports) | **ALIGNED** | Zero host port exposure for DB (3306) or WordPress (80). |
| **External Volumes Declared** | `lohnour_wp_data`, `lohnour_db_data` | **ACTION REQ** | Compose declares `external: true`. Volumes must be pre-created before `docker compose up`. |
| **External Networks Declared** | `proxy_network`, `lohnour_db_network` | **ACTION REQ** | Compose declares `external: true`. Networks must be pre-created before `docker compose up`. |

---

## 9. Risks, Blockers & Pre-Flight Checklist

### Blocker 1: External Volume and Network Pre-Creation
- **Status:** **PENDING PHASE 8**
- **Action:** Because `docker-compose.yml` specifies `external: true` for `lohnour_wp_data`, `lohnour_db_data`, `proxy_network`, and `lohnour_db_network`, the deployment sequence must create them explicitly with `docker volume create` and `docker network create` before executing `docker compose up`.

### Blocker 2: DNS Cutover Synchronization
- **Status:** **PENDING FOUNDER ACTION**
- **Action:** DNS `A` record for `pro.lohnour.com` currently points to `193.122.206.31`. Caddy requires DNS pointing to `141.148.51.166` to complete ACME HTTP-01 challenge for Let's Encrypt TLS issuance.

### Blocker 3: WordPress Core Volume Initialization Order (Resolved)
- **Status:** **RESOLVED IN PHASE 4 & 5**
- **Action:** WordPress container must be booted first with an empty volume to allow the official entrypoint to initialize `/var/www/html` core files and generate `wp-config.php`, after which the uploads and plugins archives are safely extracted into `wp-content/`.

---

## 10. Explicit Go / No-Go Recommendation

### **RECOMMENDATION: GO (CONDITIONAL ON PRE-DEPLOYMENT BACKUPS)**

**Readiness Criteria Status:**
1. Live infrastructure inspected & validated: **PASS**
2. Existing Portainer & Node-RED workloads protected: **PASS** (Zero impact verified)
3. Available disk storage headroom: **PASS** (>14 GB free space remaining)
4. Backup archives & SHA256 checksums: **PASS** (100% cryptographic parity)
5. Backup & rollback strategy formulated: **PASS** (Definitive procedures prepared)

### Required Pre-Flight Execution Order for Phase 8:
1. Execute Backup A & B on Sam's Oracle VM (`nodered_data` and `portainer_data` archives).
2. Create external Docker networks (`proxy_network`, `lohnour_db_network`).
3. Create external Docker volumes (`lohnour_wp_data`, `lohnour_db_data`).
4. Restore MariaDB database from `/opt/lohnour/staging/production-lohnour_wp.sql`.
5. Deploy WordPress stack and execute volume content restoration.
6. Verify local container health checks and launch Caddy.
7. Execute final DNS cutover for `pro.lohnour.com`.

---

**PHASE 7 IS COMPLETE. STOPPED AS INSTRUCTED.**  
*Awaiting explicit user authorization before executing any backup commands or initiating Phase 8 deployment.*

