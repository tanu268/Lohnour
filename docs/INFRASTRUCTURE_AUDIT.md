# LOHNOUR PRO — ORACLE VPS & DOCKER INFRASTRUCTURE PRE-DEPLOYMENT AUDIT

**Document Identifier:** `INFRASTRUCTURE_AUDIT.md`  
**Date:** 2026-11-06  
**Auditor / Role:** Senior DevOps Engineer & Infrastructure Security Auditor  
**Target Environment:** Sam's Oracle VM (Production Target)  
**Operating Mode:** STRICT READ-ONLY FORENSICS — ZERO MUTATIONS EXECUTED  
**Canonical Documentation Path:** `D:\lohnourpro\docs\INFRASTRUCTURE_AUDIT.md`  
*(Note: Duplicate copies existing in `current source/lohnourpro_wp_theme/my-docs/` are historical theme artifacts and remain untouched).*  
**Status:** **AUDIT COMPLETE — PRE-DEPLOYMENT BASELINE ESTABLISHED**

---

## 1. Executive Summary

This infrastructure audit provides a comprehensive, evidence-based assessment of **Sam's Oracle VM** to determine its readiness for hosting the containerized **Lohnour WordPress** production stack alongside the existing **Portainer CE** and **Node-RED** workloads.

The primary objective is to verify resource sizing, inspect existing container runtimes and network listeners, evaluate OS-level security boundaries, assess the authoritative backup archives, and establish an isolated deployment architecture designed to preserve existing applications without disruption.

All findings in this document are strictly classified using standardized audit taxonomy:
- `VERIFIED — CURRENT`: Confirmed through current read-only evidence in this task (e.g. local backup archive presence and hashes).
- `PREVIOUSLY VERIFIED — RECONFIRM LIVE`: Historical observation from prior server inspection requiring fresh live reconfirmation before cutover.
- `REPORTED — NOT INDEPENDENTLY VERIFIED`: Information reported in notes or documentation but lacking independent direct evidence.
- `PENDING`: Action or check not yet performed or awaiting upcoming deployment stage.
- `BLOCKED`: Cannot proceed because a prerequisite is unresolved or gated.
- `NOT APPLICABLE`: Genuinely irrelevant to the specific stage.
- `PROPOSED — NOT APPROVED — NOT DEPLOYED`: Architectural proposal, quota model, or procedure submitted for Founder evaluation; NOT approved, tested, or deployed.

---

## 2. Infrastructure Baseline & Workload Profile

The following parameters summarize the technical profile of the target host:

| Parameter | Observed Host Specification | Status Classification | Technical Assessment |
| :--- | :--- | :---: | :--- |
| **VM Identification** | Sam's Oracle VM | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | Oracle Cloud Infrastructure (OCI) Compute Instance |
| **VM Hostname** | `sams-oracle-vm` | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | Configured OS hostname |
| **Public IPv4 Address** | `141.148.51.166` | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | Baseline public IP; requires live OCI confirmation immediately prior to DNS cutover. Current DNS does NOT point to this IP. |
| **Private IPv4 Address** | `10.0.0.100` | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | VCN internal private IP |
| **SSH Management User** | `opc` | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | Default Oracle Linux administrative user |
| **Operating System** | Oracle Linux Server 9.7 | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | Enterprise Linux 9 (RHEL 9 binary compatible), Kernel 5.15+ / UEK |
| **Docker Engine** | Docker 29.5.0 | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | Container runtime supporting cgroups v2 and Compose v2 specifications |
| **Docker Compose** | Docker Compose 5.1.3 | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | Compose plugin integration installed on host |
| **Compute Capacity** | 4 OCPU | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | 4 cores / 4 vCPUs on Oracle Cloud Compute |
| **CPU Architecture** | Ampere A1 (ARM64) vs x86_64 | `REPORTED — NOT INDEPENDENTLY VERIFIED` | Typical 4 OCPU/24 GB OCI free instance is ARM64 (`aarch64`). Requires `uname -m` check. |
| **Memory Capacity** | ~22 GiB usable RAM | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | High-capacity memory footprint (~24 GiB provisioned) |
| **Host Storage (Root FS)**| 30 GB total (~16 GB free previously) | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | Available space must be remeasured; Docker Root Dir not yet confirmed. |
| **Existing Containers** | 2 active containers | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | 1. `portainer/portainer-ce:lts`<br>2. `nodered/node-red:latest` |
| **Existing Docker Volumes** | 2 persistent volumes | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | 1. `portainer_data`<br>2. `nodered_data` |
| **Container Sockets** | Mounted Docker Socket | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | `/var/run/docker.sock` bound to Portainer container |
| **Published Host Ports** | 3 published ports | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | - TCP `8000`: Portainer published port<br>- TCP `9443`: Portainer Web UI (HTTPS)<br>- TCP `1880`: Node-RED Web UI / API |
| **Active Port Listeners (80/443/3306)** | **NONE** | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | No listener observed on TCP 80, 443, or 3306 during previous inspection |
| **Docker Compose Projects** | **NONE** | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | No active Docker Compose stacks detected (`docker compose ls` = 0) |
| **OCI Security List Ingress** | Inbound TCP 22, 80, 443, 9443 | `REPORTED — NOT INDEPENDENTLY VERIFIED` | Reported in audit notes; requires OCI Console re-verification. Do not infer OCI rules from host firewall. |
| **Reverse Proxy Status** | None running on host | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | No Nginx Proxy Manager, Nginx, Caddy, or Traefik container detected. |

---

## 3. Workload & Container Runtime Inventory

### 3.1 Existing Active Containers

```mermaid
graph TD
    subgraph Host["Sam's Oracle VM (Oracle Linux 9.7)"]
        subgraph DockerRuntime["Docker Engine 29.5.0 (PREVIOUSLY VERIFIED — RECONFIRM LIVE)"]
            Portainer["Portainer CE (portainer/portainer-ce:lts)"]
            NodeRED["Node-RED (nodered/node-red:latest)"]
            DockerSock[("/var/run/docker.sock")]
            VolPortainer[("volume: portainer_data")]
            VolNodeRed[("volume: nodered_data")]
        end
        
        Portainer -->|Read/Write Control| DockerSock
        Portainer --- VolPortainer
        NodeRED --- VolNodeRed
    end

    subgraph OCI_Ingress["OCI Security List & NSG Layer (REPORTED — NOT INDEPENDENTLY VERIFIED)"]
        OCI_9443["TCP 9443 (Reported Permitted in Notes)"]
        OCI_8000["TCP 8000 (Reported Dropped at VCN)"]
        OCI_1880["TCP 1880 (Reported Dropped at VCN)"]
    end

    subgraph Host_Firewall["Host firewalld Layer (PENDING LIVE VERIFICATION)"]
        FW_Rules["firewalld: public zone (only ssh/dhcpv6 observed previously)"]
    end

    ClientExternal["Internet / 0.0.0.0/0"] -.->|Potential Ingress (Unverified)| OCI_9443
    OCI_9443 -.->|Subject to firewalld & iptables| FW_Rules
    FW_Rules -.->|Docker Published Binding: 0.0.0.0:9443| Portainer

    ClientExternal -.->|Reported Dropped at VCN| OCI_8000
    ClientExternal -.->|Reported Dropped at VCN| OCI_1880
    ClientLocal["Local Administrative SSH Tunnel"] -->|TCP 1880 / 9443| Host
```

#### Container 1: Portainer CE (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`)
- **Image:** `portainer/portainer-ce:lts`
- **Purpose:** Host container and stack lifecycle management.
- **Volume Mounts:** `portainer_data` mapped to `/data`.
- **System Bind Mounts:** `/var/run/docker.sock` mapped to `/var/run/docker.sock` with read/write access.
- **Network Ports:** 
  - `0.0.0.0:9443 -> 9443/tcp` (Web UI HTTPS)
  - `0.0.0.0:8000 -> 8000/tcp` (Published host port; Edge Agent presence is unverified — do not assume Edge Agent is installed merely because port 8000 is published)
- **Security Posture:** Highly privileged. Possesses full root-equivalent control of the Docker daemon and the host via `/var/run/docker.sock`. Critical management-plane risk.
- **Git Integration Capability:** `REPORTED — NOT INDEPENDENTLY VERIFIED`. Portainer CE supports deploying stacks from a Git repository; live verification is required to confirm whether this specific instance has outbound HTTPS reachability to `github.com`.

#### Container 2: Node-RED (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`)
- **Image:** `nodered/node-red:latest`
- **Purpose:** Event-driven workflow automation and integration runtime.
- **Volume Mounts:** `nodered_data` mapped to `/data`.
- **Network Ports:** `0.0.0.0:1880 -> 1880/tcp`.
- **Security Posture:** Currently published on port 1880. OCI Security List does not permit inbound port 1880 from `0.0.0.0/0` (traffic is dropped at the OCI VCN boundary unless accessed locally, via VPN, or through an SSH tunnel).

---

## 4. Network Ingress & Listener Forensic Analysis

### 4.1 Host Port Listener Audit

A detailed comparison between the host network listeners and the OCI Security List rules reveals critical operational and security facts:

| Port | Service / Container | Host Binding | OCI Security List (`0.0.0.0/0`) | Public Reachability | Audit Finding & Classification |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **22** | OpenSSH (`sshd`) | `0.0.0.0:22` (`VERIFIED`) | `REPORTED — NOT INDEPENDENTLY VERIFIED` (Permitted in notes) | Subject to OCI & host firewall | Host listener active. Password auth and root login status: `PENDING LIVE VERIFICATION`. |
| **80** | None (Idle) | *None* (`VERIFIED`) | `REPORTED — NOT INDEPENDENTLY VERIFIED` (Permitted in notes) | No host listener active | Host listener absent. Does NOT prove public reachability until service binds and firewalld is verified. |
| **443** | None (Idle) | *None* (`VERIFIED`) | `REPORTED — NOT INDEPENDENTLY VERIFIED` (Permitted in notes) | No host listener active | Host listener absent. Reverse proxy remains `PROPOSED — NOT APPROVED`. |
| **1880** | Node-RED | `0.0.0.0:1880` (`VERIFIED`) | `REPORTED — NOT INDEPENDENTLY VERIFIED` (Blocked in notes) | Blocked at VCN (Reported) | Host listener active. VCN boundary filtering requires current OCI verification. |
| **3306** | None (Idle) | *None* (`VERIFIED`) | `REPORTED — NOT INDEPENDENTLY VERIFIED` (Blocked in notes) | Not exposed on host | Host listener absent. Database must not bind to host. |
| **8000** | Portainer (Unknown Service)| `0.0.0.0:8000` (`VERIFIED`) | `REPORTED — NOT INDEPENDENTLY VERIFIED` (Blocked in notes) | Blocked at VCN (Reported) | Host listener active. Edge Agent presence unverified. |
| **9443** | Portainer HTTPS | `0.0.0.0:9443` (`VERIFIED`) | `REPORTED — NOT INDEPENDENTLY VERIFIED` (Permitted in notes) | Exposed if OCI rule active | Critical management-plane risk. Access strategy requires Founder approval (Gate 2). |

### 4.2 Key Ingress Findings
1. **Zero Web Ingress Active:** Although past audit notes report that OCI Security List rules permit inbound TCP traffic on ports 80 and 443, **no reverse proxy, web server, or container is listening on these ports**.
2. **Reverse Proxy Status (`PROPOSED — NOT APPROVED`):** There is **NO EXISTING REVERSE PROXY** on Sam's Oracle VM. The previous Nginx Proxy Manager container (`lohnour-npm-1`) belonged to the old Ubuntu VM (`193.122.206.31`) and does not exist here. Any reverse proxy implementation remains **PROPOSED — NOT APPROVED** pending Founder selection (Gate 4).
3. **Multi-Layer Perimeter Distinction & Verification Requirements:**
   - *Previously Observed Host Firewall:* Oracle Linux 9.7 runs `firewalld` by default. Previous inspection showed `firewalld` active with public zone permitting only `ssh` and `dhcpv6-client`. Host `firewalld` can drop external packets before Docker's `iptables`/`nftables` chains process them unless `firewall-cmd --add-service=http` and `https` are explicitly active and approved (Gate 2).
   - *Previously Observed Host Listeners:* Listeners were observed on TCP 22 (`sshd`), 8000 (Portainer published port), 1880 (Node-RED), and 9443 (Portainer HTTPS). Zero listeners were observed on TCP 80, 443, or 3306.
   - *Reported OCI Security List Configuration:* Previous notes report OCI Security List permitting inbound TCP 22, 80, 443, and 9443 from `0.0.0.0/0`, while dropping ports 1880 and 8000.
   - *Unverified Current Ingress:* Current OCI Security List and Network Security Group (NSG) rules remain `REPORTED — NOT INDEPENDENTLY VERIFIED`. Do not infer OCI exposure from host firewall output or container bindings alone. Current OCI Console confirmation is required before deployment.
4. **Portainer 9443 Exposure (Potential Critical Risk Pending OCI Ingress Verification):** Portainer binds to host port `0.0.0.0:9443` via Docker port publishing (`VERIFIED`). However, public internet reachability depends on the multi-layer perimeter: the host TCP listener (`0.0.0.0:9443`), OS `firewalld` filtering, OCI Cloud Security Lists, and Network Security Groups (NSGs). Past notes report that OCI permits inbound `0.0.0.0/0` on port 9443 (`REPORTED — NOT INDEPENDENTLY VERIFIED`). If active in OCI, this management plane is publicly reachable, creating a potential critical vulnerability due to the mounted Docker daemon socket (`/var/run/docker.sock`). Management-plane access hardening requires explicit Founder approval (Gate 2).
5. **Downtime & Disruption Realism:** Zero downtime and guaranteed deployment duration claims are completely unsupported. Public DNS cutover involves unavoidable propagation latency and maintenance downtime. Furthermore, zero disruption to Node-RED and Portainer cannot be guaranteed due to shared host CPU, memory, and network resources.

---

## 5. Compute, Memory & Storage Capacity Assessment

### 5.1 Compute (CPU) Capacity & Proposed Limits (`PROPOSED`)
- **Host Total Compute:** 4 OCPU (4 physical/virtual CPU cores).
- **Current Baseline Usage:** Minimal idle usage was previously observed for Portainer and Node-RED; however, current runtime CPU consumption has not been established and cannot be assumed.

#### Proposed CPU Limit Allocation Matrix (`PROPOSED — NOT APPROVED — NOT DEPLOYED`):

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
> 1. **CPU Limits:** Define the maximum CPU capacity a container can consume under configured Docker CPU controls (Linux CFS scheduler). They are **not** guaranteed CPU allocations, nor do they imply that the service continuously consumes that CPU capacity.
> 2. **CPU Reservations:** Distinct from limits. No CPU reservations have been verified or approved for this stack; therefore, **CPU reservations remain unspecified**.
> 3. **Actual CPU Usage:** Actual runtime CPU consumption has **not** been established by this documentation-only task. Proposed limits must not be presented as measured usage, and unused CPU capacity must not be claimed as dedicated to Portainer or Node-RED.
> 4. **Existing Workloads & Contention:** Portainer and Node-RED are already active on the VM. Their actual CPU consumption and configured limits have not been measured by this task. The nominal unassigned capacity (1.75 OCPU, or 2.00 OCPU without proxy) is shared dynamically with existing services and the host operating system. This allocation does **not** guarantee headroom or prevent CPU contention.
> 5. **Status:** This allocation is strictly **`PROPOSED — NOT APPROVED — NOT DEPLOYED`**.

### 5.2 Memory (RAM) Capacity
- **Available Memory:** ~22 GiB usable RAM (24 GiB total provisioned).
- **Historical Baseline Estimates (`ESTIMATED`):**
  - Host OS + Systemd: ~550 MiB
  - Portainer CE: ~80 MiB
  - Node-RED: ~180 MiB
  - Total Estimated Active Baseline RAM: **~810 MiB (< 4% of total RAM)**.
- **Proposed Target Stack Allocation:**
  - MariaDB: Limit `3,072 MiB` (Reservation: `1,024 MiB`).
  - WordPress: Limit `3,072 MiB` (Reservation: `512 MiB`).
  - Proposed Reverse Proxy: Limit `512 MiB` (Reservation: `128 MiB`).
  - **Total Stack Maximum Allocation:** **6,656 MiB (~6.5 GiB)**.
- **Estimated System RAM Post-Deployment:** **~7.46 GiB (~34% of 22 GiB)**.
- **Nominal Unallocated Memory:** **~14.5 GiB**.
- **Memory Feasibility Assessment:** **`ESTIMATED — PENDING RUNTIME VERIFICATION`**. The proposed stack allocations (~6.5 GiB maximum limits, ~1.66 GiB reservations) appear numerically feasible within the previously observed ~22 GiB usable RAM baseline. However, actual runtime memory consumption has not been established by this documentation task, and proposed container limits do not guarantee that Out-Of-Memory (OOM) killer events cannot occur under heavy application traffic, concurrent database queries, or unconstrained host memory pressure. Continuous resource monitoring is required during staging and deployment.

### 5.3 Storage & Volume Sizing
- **Authoritative Backup Sizing (from `_audit/production-extract/`):**
  - Database SQL dump (`production-lohnour_wp.sql`): `290,627 bytes` (~284 KiB).
  - Uncompressed MariaDB InnoDB Tablespace: ~25 MiB.
  - Uploads / Media archive (`uploads.tar.gz`): `59,733,182 bytes` (~57 MiB compressed, ~85 MiB uncompressed).
  - Child Theme archive (`twentytwentyfive-child.tar.gz`): `6,229,809 bytes` (~6 MiB compressed, ~15 MiB uncompressed).
  - Plugin archive (`lohnour_all_plugins.tar.gz`): `5,646,919 bytes` (~5.5 MiB compressed, ~22 MiB uncompressed).
  - Core WordPress filesystem: ~65 MiB.
  - Total Application Volume Footprint: **< 250 MiB**.
- **Docker Image Layer Footprint (Estimated):**
  - WordPress image: ~650 MiB
  - MariaDB image: ~410 MiB
  - Reverse Proxy image: ~380 MiB
  - Total Image Storage: **~1.44 GiB**.
- **Storage Capacity & Feasibility Assessment:** **`PENDING VERIFICATION`**.
  - *Previously Observed Baseline:* Root filesystem (`/`) was previously observed to have 30 GB total capacity with ~16 GB available free space.
  - *Docker Root Dir Uncertainty:* The actual Docker Root Dir has not yet been independently established in current documentation evidence (must be verified on host via `docker info --format '{{.DockerRootDir}}'`).
  - *Filesystem Mountpoint Verification:* The specific host filesystem backing Docker data must be verified via `df -h` to determine exact available space.
  - *Pre-Transfer Measurement Mandate:* Available disk space must be measured directly on the host immediately prior to archive transfer or staging.
  - *Growth & Operational Overhead Factors:* Sizing calculations must account not merely for static archive sizes (< 250 MiB) and image layers (~1.44 GiB), but also for dynamic database growth, extracted WordPress core/plugin/theme files, temporary archive transfers in `/opt/lohnour/staging`, automated local backups, and rollback snapshots.
  - *Feasibility Status:* Storage feasibility cannot be approved based on static archive estimates alone and remains **PENDING VERIFICATION**.

---

## 6. Authoritative Backup & Image Compatibility Analysis

### 6.1 Authoritative Backup Artifacts (Local Storage Only)
The authoritative source of truth for the Lohnour production state is verified locally at:  
`D:\lohnourpro\_audit\production-extract\`

All files have been verified locally using SHA-256 cryptographic hashes against `manifest-all.sha256`:

| Component | Relative Path | File Size | Verified SHA-256 Checksum | Integrity & Classification |
| :--- | :--- | :--- | :--- | :---: |
| **Database** | `database/production-lohnour_wp.sql` | 290,627 B | `db8628e7743b133e372265a7d09ac29729ab7f7c8ed9dfcd0bdd257d7e88a880` | Local file presence & hash: `VERIFIED — CURRENT`. Schema contents, table count, and SQL compatibility: `PENDING — DUMP INSPECTION REQUIRED` (BLOCKER 7). |
| **Uploads** | `uploads.tar.gz` | 59,733,182 B | `5001b5d43159e4611540661d8af50a9fd0301e06a4224587cb38f7f68983b0d9` | `VERIFIED — CURRENT` |
| **Child Theme** | `twentytwentyfive-child.tar.gz` | 6,229,809 B | `a14af7f749f0e459b60658514e79325a29a3bb7fdc98c56d9591e1faadca802d` | `VERIFIED — CURRENT` |
| **All Plugins** | `lohnour_all_plugins.tar.gz` | 5,646,919 B | `1e2c4bb948ea06621a52b1911b6d6a29868695798d8e37753454643e0be68484` | `VERIFIED — CURRENT` |
| **Program CMS**| `lohnour_program_cms.tar.gz` | 18,777 B | `b6807f7c19c5cbbfe028c24949fbbe8fca65dc2f8da8be5b7a14ec84869a842b` | `VERIFIED — CURRENT` |
| **PHP Config** | `config/uploads.ini` | 119 B | `8f8f90dd0d2ff804e84b72ef35b8cf0f214ceb3664d6dbdb9db5d4a13d7890ec` | `VERIFIED — CURRENT` |
| **WP Config** | `config/wp-config.redacted.php` | 5,922 B | `c53a6704ebf9a3ea77c15e8e8334f68a2bf6cb8e3678fe0168be80608796853c` | `VERIFIED — CURRENT` |
| **Historical Compose**| `config/docker-compose.yml` | 1,545 B | `c6ae0df4d16853879d71c4860b0e3dd6860b73cce3892d47da1f2b2b1eb329fb` | **HISTORICAL ARCHIVE — REFERENCE ONLY — NOT APPROVED FOR PRODUCTION** |

> [!WARNING]
> **HISTORICAL COMPOSE FILE STATUS:**
> `D:\lohnourpro\_audit\production-extract\config\docker-compose.yml` is classified as:  
> **`HISTORICAL ARCHIVE — REFERENCE ONLY — NOT APPROVED FOR PRODUCTION`**  
> - Its existence does not establish production readiness.
> - It must **NOT** be copied into the active deployment directory.
> - It must **NOT** be used as the production stack configuration.
> - It must **NOT** be modified or repurposed as part of this task.
> - Any future reuse requires a separate technical review, compatibility verification, security review, and explicit Founder approval.
> - The canonical production Compose path remains **`COMPOSE FILE LOCATION — NOT YET DECIDED`** until a separately reviewed and approved configuration is committed to the `developer` branch in GitHub.

### 6.2 WordPress Image Compatibility Gate (`WORDPRESS IMAGE SELECTION: BLOCKED`)
The SQL dump contains database schema revision `61833`. However, **database revision `61833` alone is NOT sufficient evidence to determine the correct WordPress container image**.

Until all required evidence has been obtained, reviewed, and approved, status is:  
**`WORDPRESS IMAGE SELECTION: BLOCKED`**

Before image selection, evidence must be established for:
1. **Exact WordPress Core Version:** Identify the exact WordPress core version used by the existing website (evidence from source, backup, or another authoritative artifact; do not infer from database revision alone).
2. **PHP Version & Required Extensions:** Establish the PHP version required by the identified core version; check compatibility with the active child theme and installed plugins; verify required PHP extensions; do not assume PHP 8.2, 8.3, or any other version without evidence.
3. **Theme & Plugin Compatibility:** Identify all installed plugins and versions; verify compatibility with selected core and PHP versions; identify abandoned, outdated, or incompatible plugins without silent modifications.
4. **MariaDB Version & Database Compatibility:** Establish the existing database engine and version; verify compatibility with selected WP image and application requirements.
5. **Database Schema & Migration Compatibility:** Verify compatibility with the extracted schema, noting the significance and limitations of reported revision `61833` (insufficient proof of core compatibility).
6. **CPU Architecture & Platform Compatibility:** Confirm image platform support matches host architecture (`linux/arm64` vs `linux/amd64`).
7. **Immutable Version & Digest Pinning:** Record the exact image tag and immutable SHA-256 digest (`image@sha256:...`); do not use rolling `:latest` tags.
8. **Staging Restoration & Functional Testing:** Perform successful staging restoration and functional validation with the production database dump before production approval.

**Mandatory Status:** `WORDPRESS IMAGE SELECTION: BLOCKED`.

---

## 7. Categorization of Findings: Facts vs Assumptions vs Unknowns

### 7.1 Verified Baseline Facts (`VERIFIED — CURRENT` & `PREVIOUSLY VERIFIED — RECONFIRM LIVE`)
1. Sam's Oracle VM runs **Oracle Linux Server 9.7** with **Docker 29.5.0** and **Docker Compose 5.1.3** (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
2. Hardware capacity is **4 OCPU** and approximately **22 GiB usable RAM** (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
3. Root filesystem was previously observed as **30 GB total with ~16 GB available free space** (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
4. Host identity baseline: hostname is `sams-oracle-vm`, private IP is `10.0.0.100`, SSH management user is `opc`, and previously verified public IPv4 is **`141.148.51.166`** (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`). This address is a historical baseline observation, not a guarantee that the IP cannot change. Live reconfirmation in OCI is strictly required before any DNS cutover. Current DNS does NOT point to this IP.
5. Existing active containers are strictly **Portainer CE (`portainer/portainer-ce:lts`)** and **Node-RED (`nodered/node-red:latest`)** (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
6. Existing named Docker volumes are strictly **`portainer_data`** and **`nodered_data`** (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
7. Portainer has `/var/run/docker.sock` mounted with read/write host root privileges (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
8. Host listeners previously observed: TCP `22` (sshd), `8000` (Portainer published port), `1880` (Node-RED), and `9443` (Portainer HTTPS) (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
9. Host firewall daemon (`firewalld`) is active; public firewalld zone previously showed `ssh` and `dhcpv6-client` (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
10. Zero active listeners were observed on TCP `80`, `443`, or `3306` during the initial inspection (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
11. Zero existing Docker Compose projects are active on the VM (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
12. Zero reverse proxies are currently running on Sam's Oracle VM (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
13. Local backup extract files in `D:\lohnourpro\_audit\production-extract\` match their SHA-256 manifests (`VERIFIED — CURRENT` — Stage 1 of Gate 6; schema contents and compatibility: `PENDING — DUMP INSPECTION REQUIRED`).

### 7.2 Technical Proposals (`PROPOSED — NOT APPROVED — NOT DEPLOYED`)
1. **CPU Allocation:** Proposed application container limits totaling 2.25 OCPU (WP: 1.00, DB: 1.00, Proxy: 0.25), leaving 1.75 OCPU nominal capacity unassigned (or 2.00 OCPU total / 2.00 OCPU unassigned without proxy) (`PROPOSED — NOT APPROVED — NOT DEPLOYED`).
2. **Reverse Proxy Architecture:** Proposed ingress proxy evaluated across alternatives (NPM / Caddy / Traefik / Standalone Nginx / Cloudflare Tunnel); unapproved pending Founder selection (Gate 4).
3. **Network Isolation:** Proposed private internal bridge `lohnour_db_network` with zero public database exposure.

### 7.3 Unknowns Requiring Clarification Before Deployment (`UNKNOWN / PENDING VERIFICATION`)
1. **CPU Architecture Confirmation:** Exact host CPU architecture (`uname -m`: `x86_64` vs `aarch64`) is `REPORTED — NOT INDEPENDENTLY VERIFIED`.
2. **OCI Security List Ingress:** Inbound rules on ports 22, 80, 443, 9443 and dropped packets on 1880, 8000 are `REPORTED — NOT INDEPENDENTLY VERIFIED` (requires current live OCI console verification).
3. **Docker Root Dir & Storage Path:** The actual Docker Root Dir has not yet been established in documentation evidence (requires `docker info --format '{{.DockerRootDir}}'`). Available disk space on the specific filesystem backing Docker data must be measured via `df -h` before staging.
4. **Gate 6 Multi-Stage Completion:** While Stage 1 (local archive checksums) is `VERIFIED`, Stage 2 (remote transfer integrity) and Stage 3 (restore validation in isolated staging) are `NOT STARTED` and remain `PENDING VERIFICATION`.
5. **SSH Server Configuration:** Whether password authentication or root login is disabled in `/etc/ssh/sshd_config` is `PENDING LIVE VERIFICATION`.
6. **Portainer Edge Agent Status:** Published port 8000 does not prove Edge Agent is active; status is `UNKNOWN`.
7. **Domain & DNS Pointing:** Authoritative production domain DNS A-record pointing status for `pro.lohnour.com` is `UNKNOWN` (Gate 8).
8. **Zero Downtime & Disruption Disclaimer:** Zero downtime and guaranteed deployment duration claims are unsupported. Downtime will occur during cutover. Furthermore, zero disruption to Node-RED and Portainer cannot be guaranteed due to shared host CPU, memory, and network resources.
