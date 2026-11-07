# LOHNOUR PRO — PHASE 12: PORTAINER STACK DEPLOYMENT REPORT
## Production WordPress Application Stack Deployment & Internal Application Verification

**Document Authority:** Principal DevOps Engineer, Production Deployment Architect, Database Reliability Engineer  
**Execution Timestamp:** November 7, 2026 (13:39:07 IST / 08:09:07 UTC)  
**Governing Standard:** LOHNOUR PRO Fail-Closed Deployment Standard  
**Target Host:** Sam's Oracle VM (`141.148.51.166`, `sams-oracle-vm`, Oracle Linux Server 9.7 ARM64 `aarch64`)  
**Deployment Infrastructure:** Portainer CE / Docker Compose Engine (`v5.1.3`)  
**Stack Name:** `lohnour-stack`  
**Target Compose Path:** `/opt/lohnour/docker-compose.yml`  
**Target Environment Path:** `/opt/lohnour/.env` (`0600 root:root`)  
**Canonical Execution Script:** `/opt/lohnour/phase12_portainer_deploy.sh`  
**Final Status:** `PHASE_12_SUCCESS — WORDPRESS_DEPLOYED_AND_VERIFIED`  

---

## 1. EXECUTIVE SUMMARY & CLOSURE DECISION

Phase 12: Production WordPress Stack Deployment and Internal Verification has been successfully executed and validated on Sam's existing Oracle Cloud VM.

The production Compose configuration attached directly to the pre-populated persistent database volume `lohnour_db_data` (restored and verified in Phase 10) and persistent WordPress file volume `lohnour_wp_data` (restored and verified in Phase 11). Both `lohnour-db` and `lohnour-wordpress` reached healthy status. Zero host ports were published for either MariaDB (port 3306) or WordPress (port 80). Internal application bootstrap confirmed database connectivity, 92 existing posts, 14 database tables, correct siteurl/home settings (`https://pro.lohnour.com`), and 512M PHP runtime limits. Internal HTTP smoke probes across all primary routes returned HTTP 200 with active custom child theme rendering. Existing production workloads (`portainer` on port 9443 and `nodered` on port 1880) remained completely unaffected and continuously healthy.

```
========================================================================================
FINAL PHASE 12 EXECUTION STATUS:
PHASE_12_SUCCESS — WORDPRESS_DEPLOYED_AND_VERIFIED
========================================================================================
```

---

## 2. PREFLIGHT GATES & RESOURCE AUDIT

Prior to stack launch, all infrastructure prerequisites were independently verified:

| Checkpoint | Target Resource | Required State | Observed Live Result | Status |
| :--- | :--- | :--- | :--- | :---: |
| **Env Permissions** | `/opt/lohnour/.env` | `0600 root:root` | `600 root:root` verified | **PASS** |
| **PHP Runtime Config** | `/opt/lohnour/config/uploads.ini` | `0644 root:root`, `container_file_t` | Present, 512M limits, SELinux verified | **PASS** |
| **WordPress Volume** | `lohnour_wp_data` | Pre-populated ($\ge 5$ items) | 421 filesystem entries present | **PASS** |
| **Database Volume** | `lohnour_db_data` | Pre-populated ($\ge 5$ items) | 237 filesystem entries (14 InnoDB `.ibd` tables) | **PASS** |
| **Database Network** | `lohnour_db_network` | `Internal: true`, bridge | Present, isolated bridge | **PASS** |
| **Proxy Network** | `proxy_network` | Bridge network | Present, external bridge | **PASS** |
| **Portainer Workload** | Container `portainer` | Status: `running` | Up 3 hours, HTTP 200 on port 9443 | **PASS** |
| **Node-RED Workload** | Container `nodered` | Status: `running` | Up 5 months (healthy), HTTP 200 on port 1880 | **PASS** |

---

## 3. CANONICAL STACK CONFIGURATION

The deployed `/opt/lohnour/docker-compose.yml` strictly reflects the Phase 12 boundary, omitting public proxy services (reserved for Phase 13):

```yaml
name: lohnour-stack

services:
  db:
    image: mariadb@sha256:0de9840f23463c22eb4892d9bfd63086367e4bffe9b68ff4b62621d6eced854c
    container_name: lohnour-db
    restart: unless-stopped
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

  wordpress:
    image: wordpress:7.1.4-php8.2-apache
    container_name: lohnour-wordpress
    restart: unless-stopped
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

volumes:
  lohnour_wp_data:
    external: true
    name: lohnour_wp_data
  lohnour_db_data:
    external: true
    name: lohnour_db_data

networks:
  proxy_network:
    external: true
    name: proxy_network
  lohnour_db_network:
    external: true
    name: lohnour_db_network
```

---

## 4. ACCEPTANCE VALIDATION RESULTS

All 10 required Phase 12 acceptance criteria were verified live on Sam's Oracle VM:

| # | Acceptance Test | Target Expectation | Observed Live Result | Status |
| :-: | :--- | :--- | :--- | :-: |\n| **1** | **MariaDB Container Health** | Status: `healthy` | `lohnour-db: healthy` (InnoDB initialized) | **PASS** |
| **2** | **WordPress Container Health** | Status: `healthy` | `lohnour-wordpress: healthy` (HTTP probe 200) | **PASS** |
| **3** | **WordPress Volume Attachment** | Mounted at `/var/www/html` | Verified attached to `lohnour_wp_data` | **PASS** |
| **4** | **Database Volume Preservation** | Mounted at `/var/lib/mysql` | Retained 100% of restored data (14 tables) | **PASS** |
| **5** | **Zero Public DB Port Exposure** | Port 3306 not exposed to host | Zero published host ports (`lohnour-db: ''`) | **PASS** |
| **6** | **Zero WordPress Host Port Exposure** | Port 80 not exposed to host | Zero published host ports (`lohnour-wordpress: ''`) | **PASS** |
| **7** | **PHP Injected Runtime Limits** | 512M memory/post/upload | `upload_max_filesize => 512M`, `post_max_size => 512M`, `memory_limit => 512M` | **PASS** |
| **8** | **Internal DNS & TCP Connectivity** | `lohnour-wordpress` $\rightarrow$ `db:3306` | TCP connection to `db:3306` successful | **PASS** |
| **9** | **WordPress Application Bootstrap** | Core loaded, DB queried | `siteurl: https://pro.lohnour.com`, `home: https://pro.lohnour.com`, `posts: 92`, `tables: 14` | **PASS** |
| **10** | **Internal HTTP Ingress Verification** | HTTP 200 over `proxy_network` | HTTP 200, `<title>Lohnour Pro</title>`, zero setup wizard | **PASS** |

---

## 5. EXTENDED ROUTE SMOKE TEST MATRIX

Internal HTTP smoke tests simulated reverse proxy ingress over `proxy_network` (`Host: pro.lohnour.com`, `X-Forwarded-Proto: https`):

| Route | Tested URI | Expected HTTP Code | Observed HTTP Code | Route Description |
| :--- | :--- | :---: | :---: | :--- |
| **Home Page** | `/` | `200` | `200` | Front page rendered via `twentytwentyfive-child` |
| **Authentication** | `/wp-login.php` | `200` | `200` | WordPress administrative login interface |
| **REST API** | `/wp-json/` | `200` | `200` | WordPress Core REST API Index endpoint |
| **About Page** | `/about/` | `200` | `200` | Static About Us page |
| **Services Archive** | `/services/` | `200` | `200` | Custom post type services archive |

---

## 6. WORKLOAD ISOLATION & HOST IMPACT AUDIT

- **Portainer CE Workload:**
  - Status: Running continuously (`Up 3 hours`).
  - Listeners: `0.0.0.0:8000`, `0.0.0.0:9443` (HTTP 200 at `/api/status`).
  - BoltDB database: Intact (`262,144 bytes`).
- **Node-RED Workload:**
  - Status: Running continuously (`Up 5 months (healthy)`).
  - Listener: `0.0.0.0:1880` (HTTP 200).
- **Network Isolation:**
  - `lohnour_db_network`: Strictly isolated internal network. MariaDB is unreachable from host or external networks.
  - `proxy_network`: Ingress bridge shared only between `lohnour-wordpress` and future reverse proxy.
- **Phase 13 Boundary:**
  - Zero deployment of Caddy reverse proxy container.
  - Zero modification to host ports 80/443.
  - Zero modification to public DNS or OCI Security Lists.
  - Zero SSL/TLS certificate generation operations.

---

## 7. FINAL FORMAL DECISION

```
========================================================================================
FINAL FORMAL DECISION:
PHASE_12_SUCCESS — WORDPRESS_DEPLOYED_AND_VERIFIED
========================================================================================
```
