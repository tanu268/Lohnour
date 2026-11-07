# LOHNOUR PRO — DEPLOYMENT ARCHITECTURE SPECIFICATION

**Document Identifier:** `DEPLOYMENT_ARCHITECTURE.md`  
**Date:** 2026-11-06  
**Auditor / Role:** Senior DevOps Engineer & Infrastructure Security Auditor  
**Target Environment:** Sam's Oracle VM (Oracle Linux 9.7)  
**Operating Mode:** STRICT READ-ONLY FORENSICS — ZERO DEPLOYMENT MUTATIONS  
**Canonical Documentation Path:** `D:\lohnourpro\docs\DEPLOYMENT_ARCHITECTURE.md`  
*(Note: Duplicate copies existing in `current source/lohnourpro_wp_theme/my-docs/` are historical theme artifacts and remain untouched).*  
**Status:** **PROPOSED ARCHITECTURE SPECIFICATION — AWAITING FOUNDER SELECTION & APPROVAL**

---

## 1. Executive Summary

This document specifies the target container deployment architecture for the **Lohnour WordPress** platform on **Sam's Oracle VM**. The architecture establishes complete application isolation, strict network segregation, predictable resource allocation, and a GitHub-centric deployment workflow, designed to preserve existing **Portainer CE** and **Node-RED** workloads without interruption.

### Core Architectural Decisions:
1. **GitHub as the Single Source of Truth:** The deployment configuration is maintained exclusively within the GitHub repository (`https://github.com/tanu268/Lohnour.git`) on the verified `developer` branch. Portainer deploys directly from this Git repository, completely eliminating the Portainer Web Editor.
2. **Reverse Proxy Status (`PROPOSED — NOT APPROVED`):** An ingress reverse proxy is evaluated to manage traffic on ports 80 and 443, but remains unapproved pending Founder decision on the preferred proxy technology or routing layer (Gate 4).
3. **Application & Database Isolation:** WordPress and MariaDB communicate across a private, internal bridge network with zero public database exposure.
4. **Preserved Existing Workloads:** Portainer CE and Node-RED operate in their independent runtimes and are untouched.
5. **Target Host Baseline & Public IP:** The deployment target is Sam's Oracle VM (`sams-oracle-vm`, private IP: `10.0.0.100`, SSH user: `opc`). Previously verified baseline public IPv4 is **`141.148.51.166`** (`PREVIOUSLY VERIFIED`). This address is a historical observation, not a guarantee; live confirmation in OCI is required immediately before any DNS cutover. Current DNS does NOT point to this IP.

---

## 2. Target Architecture Topology

```mermaid
graph TB
    subgraph Ingress_Layer["Internet Ingress Layer (Ports 80 & 443)"]
        ClientHTTPS["HTTPS Web Clients (Port 443)"]
        ClientHTTP["HTTP Web Clients (Port 80)"]
        AdminSSH["Founder Admin (SSH Tunnel)"]
    end

    subgraph OCI_Firewall["OCI Security List & Host Firewall (REPORTED — NOT INDEPENDENTLY VERIFIED)"]
        FW80["TCP 80 -> Reported Permitted (Host Idle / No Service Active)"]
        FW443["TCP 443 -> Reported Permitted (Host Idle / No Service Active)"]
        FW22["TCP 22 -> Reported Permitted (Key Auth Only - Reconfirm Live)"]
        FW9443["TCP 9443 -> Reported Permitted (Pending Revocation via Gate 2)"]
    end

    ClientHTTP -.->|Pending Proxy Selection (Gate 4)| FW80
    ClientHTTPS -.->|Pending Proxy Selection (Gate 4)| FW443
    AdminSSH --> FW22

    subgraph Host_VM["Sam's Oracle VM (4 OCPU / ~22 GiB Usable RAM / Oracle Linux 9.7 | Baseline IP: 141.148.51.166)"]
        
        subgraph Stack_Ingress["Stack 1: Ingress Reverse Proxy (PROPOSED — NOT APPROVED)"]
            ProxyContainer["Proposed Reverse Proxy Container<br>(NPM / Caddy / Traefik / Nginx)<br>Ports: 80, 443 | Limit: 0.25 OCPU"]
            VolProxyData[("volume: proxy_data (Proposed)")]
            VolProxyCerts[("volume: proxy_certs (Proposed)")]
            ProxyContainer --- VolProxyData
            ProxyContainer --- VolProxyCerts
        end

        subgraph Stack_App["Stack 2: Lohnour Production Stack (PROPOSED)"]
            WPContainer["WordPress Container<br>(WORDPRESS IMAGE SELECTION: BLOCKED)<br>Limit: 1.00 OCPU / 3.0 GiB RAM"]
            DBContainer["MariaDB Container<br>(WORDPRESS IMAGE SELECTION: BLOCKED)<br>Internal Port: 3306 | Limit: 1.00 OCPU / 3.0 GiB RAM"]
            VolWPData[("volume: lohnour_wp_data<br>/var/www/html")]
            VolDBData[("volume: lohnour_db_data<br>/var/lib/mysql")]
            BindPHP["uploads.ini (Bind Mount: ro,z)"]
            
            WPContainer --- VolWPData
            WPContainer --- BindPHP
            DBContainer --- VolDBData
        end

        subgraph Stack_Existing["Preserved Existing Workloads (PREVIOUSLY VERIFIED — RECONFIRM LIVE)"]
            PortainerContainer["Portainer CE (PREVIOUSLY VERIFIED — RECONFIRM LIVE)<br>(portainer/portainer-ce:lts)<br>Port: 9443"]
            NodeREDContainer["Node-RED (PREVIOUSLY VERIFIED — RECONFIRM LIVE)<br>(nodered/node-red:latest)<br>Port: 1880"]
            VolPortainer[("volume: portainer_data")]
            VolNodeRED[("volume: nodered_data")]
            DockerSock[("/var/run/docker.sock")]

            PortainerContainer --- VolPortainer
            PortainerContainer --- DockerSock
            NodeREDContainer --- VolNodeRED
        end

        subgraph Docker_Networks["Docker Virtual Networks (PROPOSED)"]
            NetProxy["proxy_network (Bridge Ingress, Proposed)"]
            NetDB["lohnour_db_network (Internal Bridge, No Gateway, Proposed)"]
        end

        FW80 --> ProxyContainer
        FW443 --> ProxyContainer
        FW22 -.->|SSH Port Forward: 9443| PortainerContainer

        ProxyContainer <-->|Proxy Ingress HTTP| NetProxy
        NetProxy <--> WPContainer
        WPContainer <-->|Internal MySQL Queries| NetDB
        NetDB <--> DBContainer
    end

    classDef security fill:#ffebee,stroke:#c62828,stroke-width:2px;
    classDef ingress fill:#fff3e0,stroke:#e65100,stroke-width:2px;
    classDef app fill:#e3f2fd,stroke:#1565c0,stroke-width:2px;
    classDef existing fill:#e8f5e9,stroke:#2e7d32,stroke-width:2px;
    
    class FW80,FW443,FW22,FW9443 security;
    class ProxyContainer ingress;
    class WPContainer,DBContainer app;
    class PortainerContainer,NodeREDContainer existing;
```

---

## 3. Container Stack Specifications

### 3.1 Ingress Reverse Proxy (`PROPOSED — NOT APPROVED`)
- **Status:** **PROPOSED — NOT APPROVED (UNRESOLVED ARCHITECTURAL DECISION)**.
- **Why a Reverse Proxy Is Evaluated:**
  - Inbound HTTP (80) and HTTPS (443) traffic must terminate TLS certificates and forward requests to internal container ports.
  - WordPress generates absolute URLs and requires proxy awareness headers (`X-Forwarded-Proto`, `X-Forwarded-For`).
  - Potential multi-service routing: provides an extensible mechanism to route additional services (or Node-RED) securely behind domain paths without exposing raw ports.
- **Evaluation of Reverse Proxy Alternatives:**

| Solution | Key Strengths | Potential Trade-Offs | Evaluation Status |
| :--- | :--- | :--- | :---: |
| **Nginx Proxy Manager (NPM)** | Intuitive Web UI, built-in SQLite DB, easy Let's Encrypt renewal. | Extra web UI attack surface, consumes ~250–350 MiB RAM, adds SQLite overhead. | `PROPOSED — NOT APPROVED` |
| **Caddy** | Memory-safe (Go), automated HTTPS out of the box, zero DB, minimalist Caddyfile. | No graphical UI (configured via file/API). | `PROPOSED — ALTERNATIVE` |
| **Traefik** | Cloud-native, automatic Docker label discovery, dynamic routing. | Steeper configuration learning curve, YAML label complexity. | `PROPOSED — ALTERNATIVE` |
| **Standalone Nginx + Certbot** | Zero UI attack surface, rock-solid stability, minimal memory footprint (~30 MiB). | Manual Certbot cron configuration required. | `PROPOSED — ALTERNATIVE` |
| **Cloudflare Tunnel (`cloudflared`)** | Zero inbound open ports required (eliminates ports 80/443), outbound tunnel to Cloudflare Edge. | Requires Cloudflare as authoritative DNS and CDN provider. | `PROPOSED — ALTERNATIVE` |

- **Unresolved Decision:** The Founder must select and approve the preferred reverse proxy architecture before any ingress container or Compose resource is created (Gate 4).

---

### 3.2 Lohnour Production Application Stack (`PROPOSED`)

#### Proposed Service Naming & DNS Resolution Architecture
To prevent naming collisions and DNS resolution failures, the architecture explicitly distinguishes the four naming layers:
1. **Compose Service Key:** The top-level key in `docker-compose.yml` (proposed: `db` for database, `wordpress` for application).
2. **Container Name:** The host runtime container identifier (proposed: `container_name: lohnour-db` and `container_name: lohnour-wordpress`).
3. **Network DNS Alias:** The internal Docker DNS hostname resolvable by adjacent containers on the same Docker bridge network. Docker Compose automatically registers the Compose service key (e.g., `db`) as a network alias on attached networks (`lohnour_db_network`).
4. **WordPress Database Hostname:** The environment parameter passed to WordPress (`WORDPRESS_DB_HOST: db:3306`).

> [!IMPORTANT]
> **DNS ALIAS & SERVICE RESOLUTION MANDATE:**
> The container name (`lohnour-db`) and Compose service key (`db`) are **NOT** automatically equivalent unless an explicit network alias is defined. The Compose service key or network alias MUST resolve to the hostname configured in `WORDPRESS_DB_HOST`. This naming convention is **`PROPOSED — PENDING CANONICAL COMPOSE SPECIFICATION`** and cannot be finalized until the canonical Compose file is reviewed and committed to GitHub.

#### Service A: `lohnour-wordpress`
- **Compose Service Key (Proposed):** `wordpress`
- **Container Name (Proposed):** `lohnour-wordpress`
- **Image Compatibility Gate (Gate 3):** `WORDPRESS IMAGE SELECTION: BLOCKED`
  - *Evidence Requirement:* Database revision `61833` alone is **NOT** sufficient evidence to select a WordPress image. Selection is blocked until the following verification requirements are satisfied:
    - **A. WordPress Core Version:** Identify the exact WordPress core version from the existing source, backup, or authoritative artifact; do not infer from database revision alone.
    - **B. PHP Compatibility:** Establish the PHP version required by the identified core version; check compatibility with `twentytwentyfive-child` and installed plugins; verify required PHP extensions; do not assume PHP version without evidence.
    - **C. Plugin Compatibility:** Identify all installed plugins and versions; verify compatibility with selected core and PHP versions; identify abandoned, outdated, or incompatible plugins without silent modifications.
    - **D. Database Compatibility:** Establish existing database engine and version; verify compatibility; record limitations of database revision `61833` (insufficient proof of core compatibility).
    - **E. Image Reproducibility:** Record exact image tag and immutable digest (`image@sha256:...`); do not use `latest`; verify architecture compatibility (`linux/arm64` vs `linux/amd64`); require staging restore and functional validation.
  - *Mutable Tags Banned:* Mutable tags (such as `:latest`) are prohibited for production use.
- **Restart Policy:** `unless-stopped`
- **Published Host Ports:** **NONE** (Exposed exclusively via `proxy_network` to the reverse proxy).
- **Environment Variables:**
  - `WORDPRESS_DB_HOST: db:3306` (Resolves to database Compose service key `db` on `lohnour_db_network`)
  - `WORDPRESS_DB_NAME: lohnour_wp`
  - `WORDPRESS_DB_USER: lohnour_user`
  - `WORDPRESS_DB_PASSWORD: ${DB_PASSWORD}`
  - `WORDPRESS_TABLE_PREFIX: wp_`
  - `WORDPRESS_CONFIG_EXTRA`: Proxy HTTPS awareness (`HTTP_X_FORWARDED_PROTO`).
- **Volumes & Mounts:**
  - `lohnour_wp_data:/var/www/html`: Named persistent volume (explicitly pre-created under Stage 6 Step 6.1 prior to stack launch; must be declared `external: true` in Compose).
  - PHP Configuration Bind Mount: Do NOT assume a fixed internal Portainer clone path (such as `/data/compose/<stack-id>/...`). Portainer's working directory and relative path resolution behavior vary across versions and host configurations. The actual resolution of relative bind mount `./config/uploads.ini:/usr/local/etc/php/conf.d/uploads.ini:ro` must be empirically tested during staging verification. If relative bind mount resolution fails or behaves unpredictably, an explicit verified host configuration path (e.g. `/opt/lohnour/config/uploads.ini`) must be used as the documented fallback. Append `:z` only if host SELinux is in `Enforcing` mode.
- **Networks:** Attached to `proxy_network` and `lohnour_db_network`.
- **Dependencies:** Depends on Compose service `db` with `condition: service_healthy`.
- **Application Smoke Test Probe Distinctions:**
  - *Apache-based images (`wordpress:apache`):* Internal HTTP request probe on port 80 inspecting HTTP response code and HTML title.
  - *PHP-FPM images (`wordpress:fpm`):* FastCGI socket connectivity (port 9000) only tests the FPM daemon listener. To verify actual application health and database connectivity, execute an in-container bootstrap probe running `php -r "require '/var/www/html/wp-load.php'; global \$wpdb; echo 'DB_OK:' . \$wpdb->get_var('SELECT count(*) FROM wp_posts');"`.
  - *Reverse Proxy Ingress:* Verified via external ephemeral curl probe container attached to `proxy_network` testing HTTP 200 response.

#### Canonical Compose External Resource Specification Requirement
To ensure the future Compose specification explicitly reuses the pre-created Docker volumes and networks, the Compose file committed to GitHub under Gate 5 MUST define:

```yaml
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

> [!IMPORTANT]
> **RESOURCE REUSE VERIFICATION MANDATE:**  
> Do NOT claim resource reuse until the actual Compose configuration proves it. If `external: true` (or the top-level external definition) is omitted in `docker-compose.yml`, Docker Compose will default to creating project-prefixed resources (e.g. `lohnour-stack_lohnour_wp_data`), thereby bypassing pre-created volumes entirely and leaving WordPress pointing to empty volumes. Resource reuse CANNOT be claimed or verified until the canonical `docker-compose.yml` is inspected, committed, and proven to contain these exact declarations.

#### Service B: `lohnour-db`
- **Compose Service Key (Proposed):** `db`
- **Container Name (Proposed):** `lohnour-db`
- **Network DNS Alias (Proposed):** `db` (Accessible via `db:3306` on `lohnour_db_network`)
- **Image Compatibility Gate (Gate 3):** `WORDPRESS IMAGE SELECTION: BLOCKED`
  - *Evidence Requirement:* Must be pinned to a supported MariaDB release matching schema compatibility, with verified platform support and immutable SHA-256 digest (`mariadb@sha256:...`) following staging validation.
  - *Mutable Tags Banned:* Mutable tags (`:latest` or floating minor tags) are prohibited for production use.
- **Restart Policy:** `unless-stopped`
- **Published Host Ports:** **NONE** (Zero host ports exposed; port 3306 is completely private).
- **Environment Variables:**
  - `MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}`
  - `MYSQL_DATABASE: lohnour_wp`
  - `MYSQL_USER: lohnour_user`
  - `MYSQL_PASSWORD: ${DB_PASSWORD}`
- **Volumes:** `lohnour_db_data:/var/lib/mysql`.
- **Networks:** Attached exclusively to `lohnour_db_network` (`internal: true`).
- **Healthcheck:** `test: ["CMD", "healthcheck.sh", "--connect", "--innodb_initialized"]`.

---

## 4. Virtual Network Architecture (`PROPOSED`)

| Network Name | Driver | Scope | Subnet (Suggested) | Attached Containers | Access Control Rules |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `proxy_network` | `bridge` | Ingress | `172.20.0.0/16` | Reverse Proxy, `lohnour-wordpress` | Accessible to reverse proxy for forwarding requests. MariaDB and Node-RED are barred from this network. |
| `lohnour_db_network` | `bridge` | Internal | `172.21.0.0/16` | `lohnour-wordpress`, `lohnour-db` | Created with `internal: true`. No external gateway or public routing. Reverse proxy is barred from this network. |

### Network Communication Rules:
1. **Reverse Proxy -> WordPress:** Permitted on port 80 via `proxy_network`.
2. **WordPress -> Database:** Permitted on port 3306 via `lohnour_db_network`.
3. **Public -> Database:** **DENIED** (No network path exists).
4. **Proxy -> Database:** **DENIED** (No shared network).
5. **WordPress -> Node-RED:** **DENIED** (Zero shared networks).
6. **WordPress -> Portainer:** **DENIED** (Zero shared networks).

---

## 5. Storage & Volume Architecture

Persistent data is stored in named Docker volumes to ensure clean host encapsulation:

| Volume Name | Target Container Path | Content | Ownership Standard |
| :--- | :--- | :--- | :--- |
| `lohnour_wp_data` | `/var/www/html` | WordPress core, active child theme, plugins, media uploads | Image runtime UID/GID (verified dynamically, e.g. `33:33`). Write access restricted strictly to `wp-content/uploads/` (`755`/`644`); core, child theme, and plugins remain root-owned and read-only |
| `lohnour_db_data` | `/var/lib/mysql` | Production MariaDB InnoDB tablespace, system databases, binlogs | Managed exclusively by database engine (`UID 999:GID 999`) |
| `lohnour_db_staging_data`| `/var/lib/mysql` | Isolated staging database validation volume (Gate 6 — Stage 3) | Managed exclusively by database engine in staging (`UID 999:GID 999`) |
| `portainer_data` | `/data` (Existing) | Portainer configuration & database | Existing workload — **UNTOUCHED** |
| `nodered_data` | `/data` (Existing) | Node-RED flows and credentials | Existing workload — **UNTOUCHED** |

> [!IMPORTANT]
> **VOLUME MOUNTPOINT, STORAGE FEASIBILITY & PERMISSION SAFETY:**
> - **Staging vs Production Storage Isolation & Retention (BLOCKER 1 & C RESOLUTION):** Staging database restore validation drills (Gate 6 — Stage 3) strictly utilize dedicated staging volume `lohnour_db_staging_data`. The production volume `lohnour_db_data` must **NEVER** be mounted or referenced during staging restore tests. Routine deletion of `lohnour_db_staging_data` via `docker volume rm` is strictly removed from normal restore-drill procedures; the staging volume is retained as a **`retained staging reference`** following drill validation (do NOT describe as immutable; Docker named volumes possess no cryptographic write-locking or immutability mechanism). Any future decommissioning or deletion of `lohnour_db_staging_data` requires explicit authorization under **GATE 10** (`Volume Deletion & Emergency Reset Operations Approval`). Production database initialization into `lohnour_db_data` is a distinct, destructive operation requiring separate, explicit Founder authorization under **Gate 7**.
> - Named Docker volumes are managed natively by Docker within its configured Docker Root Dir. The host mountpoint must **not** be assumed to reside at `/var/lib/docker/volumes/...`; it must be confirmed dynamically via `docker volume inspect <volume_name>`.
> - **Controlled Volume Pre-Creation & Lifecycle Architecture (BLOCKER 1 RESOLUTION):** Named persistent volumes (`lohnour_wp_data`, `lohnour_db_data`) must be explicitly pre-created via controlled operational commands (`docker volume create`) under Stage 6 Step 6.1 prior to full Portainer stack launch. This deliberate pre-creation enables the creation of required directory skeletons (`wp-content/themes`, `plugins`, `uploads`), permits safe media archive extraction, and allows isolated production database initialization before WordPress starts. This guarantees WordPress never launches against an uninitialized database or empty volume.
> - **Storage Feasibility Status (`PENDING VERIFICATION` — BLOCKER 10 RESOLUTION):** The host root filesystem was previously observed as 30 GB total (~16 GB available free space). The actual Docker Root Dir has not yet been established in documentation evidence (requires verification on host via `docker info --format '{{.DockerRootDir}}'`). The specific filesystem backing Docker data must be verified via `df -h` to determine exact available space before any archive transfer or deployment. Actual storage requirements for WordPress core/theme/plugin files, database tablespace, container images, temporary staging archives, automated backups, and runtime logs remain unmeasured. Memory and CPU limits are Docker cgroup resource controls, not guarantees against Out-Of-Memory (OOM) events or storage exhaustion. Sizing feasibility cannot be approved without empirical host measurement.
> - **Filesystem Permission Safety & Restricted Write Scope:** Remove unverified broad recursive `chown -R 33:33`. The actual runtime UID/GID must be dynamically inspected from the selected WordPress image (`WP_UID`/`WP_GID`). Write permissions must be strictly restricted to `wp-content/uploads/` (mode `755` dirs, `644` files). Executable application code (WordPress core, `wp-content/themes`, `wp-content/plugins`) must remain owned by root and read-only to the web server process to mitigate web application remote code execution attacks. Blind recursive `chown -R` or `chmod -R` commands across the entire volume are strictly prohibited.

---

## 6. Resource Limits & Quota Allocation (`PROPOSED`)

### 6.1 Proposed CPU Limit Allocation Matrix (`PROPOSED — NOT APPROVED — NOT DEPLOYED`)

| Service | Proposed CPU limit |
|---|---:|
| WordPress | 1.00 OCPU |
| MariaDB | 1.00 OCPU |
| Reverse proxy (only if selected) | 0.25 OCPU |
| **Total application CPU limit** | **2.25 OCPU** |
| **Nominal capacity not assigned to these application limits** | **1.75 OCPU** |

*Contingency (if no reverse proxy is selected):*

| Metric | Value |
|---|---:|
| WordPress limit | 1.00 OCPU |
| MariaDB limit | 1.00 OCPU |
| Total application CPU limit | 2.00 OCPU |
| Nominal capacity not assigned to these application limits | 2.00 OCPU |

> [!IMPORTANT]
> **MANDATORY TECHNICAL CPU RESOURCE CLARIFICATIONS:**
> 1. **CPU Limits:** Define the maximum CPU capacity a container can consume under configured Docker CPU controls (Linux CFS scheduler). They are **not** guaranteed CPU allocations, nor do they imply continuous consumption.
> 2. **CPU Reservations:** Distinct from limits. No CPU reservations have been verified or approved; **CPU reservations remain unspecified**.
> 3. **Actual CPU Usage:** Actual runtime CPU consumption has **not** been established by this documentation-only task. Proposed limits must not be presented as measured usage, and unused CPU capacity must not be claimed as dedicated to Portainer or Node-RED.
> 4. **Existing Services & Contention:** Portainer and Node-RED are already active on the VM. Their actual CPU consumption and configured limits have not been measured by this task. The nominal unassigned capacity (1.75 OCPU, or 2.00 OCPU without proxy) is shared dynamically with existing services and the host operating system. This allocation does **not** guarantee headroom or prevent CPU contention.
> 5. **Status:** This allocation is strictly **`PROPOSED — NOT APPROVED — NOT DEPLOYED`**.

### 6.2 Proposed Memory Allocation Matrix

| Service | Memory Reservation | Memory Limit | Operational Role |
| :--- | :---: | :---: | :--- |
| `lohnour-db` | 1,024 MiB | 3,072 MiB (~3.0 GiB) | MariaDB InnoDB buffer pool and query cache |
| `lohnour-wordpress` | 512 MiB | 3,072 MiB (~3.0 GiB) | WordPress Apache MPM and PHP workers |
| Proposed Reverse Proxy | 128 MiB | 512 MiB (~0.5 GiB) | Ingress routing buffer and SSL cache |
| **Total Stack Maximum** | **1,664 MiB** | **6,656 MiB (~6.5 GiB)**| Stack memory ceiling |
| **Host Usable Capacity**| **22 GiB** | **~15.3 GiB nominal unallocated**| Nominal unallocated memory (`ESTIMATED`). Does not guarantee that OOM events cannot occur under heavy load. |

---

## 7. GitHub as Source of Truth & Portainer Git Integration

### 7.1 Architecture Principle: Zero Web Editor Deployments
The Portainer Web Editor (`Stacks -> Add Stack -> Web editor`) is **NOT an approved deployment method**. Storing Compose definitions directly in Portainer creates unversioned configuration drift, bypasses peer code review, and violates audit requirements.

### 7.2 GitHub Repository Integration
All deployment specifications must reside in Git:
- **Authoritative Repository:** `https://github.com/tanu268/Lohnour.git`
- **Approved Deployment Branch:** `developer` (`VERIFIED: Branch exists locally and on remote origin`).
- **Compose File Location (Gate 5):** `COMPOSE FILE LOCATION — NOT YET DECIDED`
  - *Inspection Finding:* The local repository currently contains no deployment Compose file in the root directory or in a `deploy/` directory.
  - *Historical Archive Classification:* The file located at `_audit/production-extract/config/docker-compose.yml` is classified as:  
    **`HISTORICAL ARCHIVE — REFERENCE ONLY — NOT APPROVED FOR PRODUCTION`**.  
    - Its existence does not establish production readiness.
    - It must **NOT** be copied into the active deployment directory.
    - It must **NOT** be used as the production stack configuration.
    - It must **NOT** be modified or repurposed as part of this task.
    - Any future reuse requires a separate technical review, compatibility verification, security review, and explicit Founder approval.
    - The canonical production Compose file remains undecided until a separately reviewed and approved configuration is established on the `developer` branch in GitHub.
  - *Requirement:* A new production Compose specification must be authored, reviewed, committed, and pushed to the `developer` branch. The canonical Compose file path remains `COMPOSE FILE LOCATION — NOT YET DECIDED` until this action occurs.
- **Portainer Build Method:** Portainer deploys via **Build method: Repository** (`Stacks -> Add Stack -> Repository`), pulling the Compose file directly from the `developer` branch.
- **Update Workflow:**
  1. Engineers commit Compose changes to `developer` locally and push to GitHub.
  2. Portainer detects changes either via:
     - Manual trigger: "Pull and redeploy" in the Portainer stack interface.
     - Automated trigger: Polling or Webhook (`PROPOSED — NOT AUTOMATICALLY CONFIGURED`).
