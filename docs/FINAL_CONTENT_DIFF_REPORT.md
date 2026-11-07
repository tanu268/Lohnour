# LOHNOUR PRO — FINAL CONTENT DIFF REPORT (READ-ONLY FORENSIC RECONCILIATION)

**Auditor / Role:** Senior DevOps Architect & Technical Documentation Auditor
**Comparison Baseline:** D:\lohnourpro\current source\lohnourpro_wp_theme\my-docs\
**Corrected Canonical Target:** D:\lohnourpro\docs\
**Operating Mode:** STRICT READ-ONLY COMPARISON — ZERO MUTATIONS

---

## File Diff: INFRASTRUCTURE_AUDIT.md

- **Baseline:** D:\lohnourpro\current source\lohnourpro_wp_theme\my-docs\INFRASTRUCTURE_AUDIT.md
- **Canonical:** D:\lohnourpro\docs\INFRASTRUCTURE_AUDIT.md

`diff
diff --git "a/D:\\lohnourpro\\current source\\lohnourpro_wp_theme\\my-docs\\INFRASTRUCTURE_AUDIT.md" "b/D:\\lohnourpro\\docs\\INFRASTRUCTURE_AUDIT.md"
index 445735b..2e00cf6 100644
--- "a/D:\\lohnourpro\\current source\\lohnourpro_wp_theme\\my-docs\\INFRASTRUCTURE_AUDIT.md"	
+++ "b/D:\\lohnourpro\\docs\\INFRASTRUCTURE_AUDIT.md"
@@ -4,7 +4,9 @@
 **Date:** 2026-11-06  
 **Auditor / Role:** Senior DevOps Engineer & Infrastructure Security Auditor  
 **Target Environment:** Sam's Oracle VM (Production Target)  
-**Operating Mode:** STRICT READ-ONLY FORENSICS — ZERO DEPLOYMENT COMMANDS EXECUTED  
+**Operating Mode:** STRICT READ-ONLY FORENSICS — ZERO MUTATIONS EXECUTED  
+**Canonical Documentation Path:** `D:\lohnourpro\docs\INFRASTRUCTURE_AUDIT.md`  
+*(Note: Duplicate copies existing in `current source/lohnourpro_wp_theme/my-docs/` are historical theme artifacts and remain untouched).*  
 **Status:** **AUDIT COMPLETE — PRE-DEPLOYMENT BASELINE ESTABLISHED**
 
 ---
@@ -13,31 +15,45 @@
 
 This infrastructure audit provides a comprehensive, evidence-based assessment of **Sam's Oracle VM** to determine its readiness for hosting the containerized **Lohnour WordPress** production stack alongside the existing **Portainer CE** and **Node-RED** workloads.
 
-The primary objective is to verify resource sizing, inspect existing container runtimes and network listeners, evaluate OS-level security boundaries, assess the authoritative backup archives, and establish an isolated deployment architecture that guarantees zero disruption to existing applications.
+The primary objective is to verify resource sizing, inspect existing container runtimes and network listeners, evaluate OS-level security boundaries, assess the authoritative backup archives, and establish an isolated deployment architecture designed to preserve existing applications without disruption.
 
-All findings in this document are strictly classified as **Verified Facts**, **Technical Assumptions**, or **Unknowns Requiring Clarification**, strictly honoring the operating mandate to execute zero deployment mutations.
+All findings in this document are strictly classified using standardized audit taxonomy:
+- `VERIFIED — CURRENT`: Confirmed through current read-only evidence in this task (e.g. local backup archive presence and hashes).
+- `PREVIOUSLY VERIFIED — RECONFIRM LIVE`: Historical observation from prior server inspection requiring fresh live reconfirmation before cutover.
+- `REPORTED — NOT INDEPENDENTLY VERIFIED`: Information reported in notes or documentation but lacking independent direct evidence.
+- `PENDING`: Action or check not yet performed or awaiting upcoming deployment stage.
+- `BLOCKED`: Cannot proceed because a prerequisite is unresolved or gated.
+- `NOT APPLICABLE`: Genuinely irrelevant to the specific stage.
+- `PROPOSED — NOT APPROVED — NOT DEPLOYED`: Architectural proposal, quota model, or procedure submitted for Founder evaluation; NOT approved, tested, or deployed.
 
 ---
 
-## 2. Verified Infrastructure Baseline
-
-The following parameters represent verified technical realities of the target host:
-
-| Parameter | Observed Host Specification | Technical Assessment |
-| :--- | :--- | :--- |
-| **VM Identification** | Sam's Oracle VM | Oracle Cloud Infrastructure (OCI) Compute Instance |
-| **Operating System** | Oracle Linux 9.7 | Enterprise Linux 9 (RHEL 9 binary compatible), Kernel 5.15+ / UEK |
-| **Docker Engine** | Docker 29.5.0 | Modern container runtime supporting cgroups v2 and Compose v2 specifications |
-| **Docker Compose** | Docker Compose 5.1.3 | Compose CLI / Plugin integration available natively |
-| **Compute Capacity** | 4 OCPU | Equivalent to 4 physical cores (or 4 vCPUs on Ampere A1 Compute) |
-| **Memory Capacity** | 22 GiB usable RAM | High-capacity memory footprint (~24 GiB provisioned) |
-| **Existing Containers** | 2 containers active | 1. `portainer/portainer-ce:lts`<br>2. `nodered/node-red:latest` |
-| **Existing Docker Volumes** | 2 persistent volumes | 1. `portainer_data`<br>2. `nodered_data` |
-| **Container Sockets** | Mounted Docker Socket | `/var/run/docker.sock` bound to Portainer container |
-| **Published Host Ports** | 3 published ports | - TCP `8000`: Portainer Edge/Agent tunnel<br>- TCP `9443`: Portainer Web Management UI (HTTPS)<br>- TCP `1880`: Node-RED Web UI / API |
-| **Active Port Listeners (80/443/3306)** | **NONE** | No listener observed on TCP 80 (HTTP), 443 (HTTPS), or 3306 (MySQL/MariaDB) |
-| **Docker Compose Projects** | **NONE** | No active Docker Compose stacks detected (`docker compose ls` = 0) |
-| **OCI Security List Ingress** | Open to `0.0.0.0/0` | TCP `22` (SSH), TCP `80` (HTTP), TCP `443` (HTTPS), TCP `9443` (Portainer HTTPS) |
+## 2. Infrastructure Baseline & Workload Profile
+
+The following parameters summarize the technical profile of the target host:
+
+| Parameter | Observed Host Specification | Status Classification | Technical Assessment |
+| :--- | :--- | :---: | :--- |
+| **VM Identification** | Sam's Oracle VM | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | Oracle Cloud Infrastructure (OCI) Compute Instance |
+| **VM Hostname** | `sams-oracle-vm` | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | Configured OS hostname |
+| **Public IPv4 Address** | `141.148.51.166` | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | Baseline public IP; requires live OCI confirmation immediately prior to DNS cutover. Current DNS does NOT point to this IP. |
+| **Private IPv4 Address** | `10.0.0.100` | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | VCN internal private IP |
+| **SSH Management User** | `opc` | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | Default Oracle Linux administrative user |
+| **Operating System** | Oracle Linux Server 9.7 | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | Enterprise Linux 9 (RHEL 9 binary compatible), Kernel 5.15+ / UEK |
+| **Docker Engine** | Docker 29.5.0 | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | Container runtime supporting cgroups v2 and Compose v2 specifications |
+| **Docker Compose** | Docker Compose 5.1.3 | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | Compose plugin integration installed on host |
+| **Compute Capacity** | 4 OCPU | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | 4 cores / 4 vCPUs on Oracle Cloud Compute |
+| **CPU Architecture** | Ampere A1 (ARM64) vs x86_64 | `REPORTED — NOT INDEPENDENTLY VERIFIED` | Typical 4 OCPU/24 GB OCI free instance is ARM64 (`aarch64`). Requires `uname -m` check. |
+| **Memory Capacity** | ~22 GiB usable RAM | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | High-capacity memory footprint (~24 GiB provisioned) |
+| **Host Storage (Root FS)**| 30 GB total (~16 GB free previously) | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | Available space must be remeasured; Docker Root Dir not yet confirmed. |
+| **Existing Containers** | 2 active containers | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | 1. `portainer/portainer-ce:lts`<br>2. `nodered/node-red:latest` |
+| **Existing Docker Volumes** | 2 persistent volumes | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | 1. `portainer_data`<br>2. `nodered_data` |
+| **Container Sockets** | Mounted Docker Socket | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | `/var/run/docker.sock` bound to Portainer container |
+| **Published Host Ports** | 3 published ports | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | - TCP `8000`: Portainer published port<br>- TCP `9443`: Portainer Web UI (HTTPS)<br>- TCP `1880`: Node-RED Web UI / API |
+| **Active Port Listeners (80/443/3306)** | **NONE** | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | No listener observed on TCP 80, 443, or 3306 during previous inspection |
+| **Docker Compose Projects** | **NONE** | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | No active Docker Compose stacks detected (`docker compose ls` = 0) |
+| **OCI Security List Ingress** | Inbound TCP 22, 80, 443, 9443 | `REPORTED — NOT INDEPENDENTLY VERIFIED` | Reported in audit notes; requires OCI Console re-verification. Do not infer OCI rules from host firewall. |
+| **Reverse Proxy Status** | None running on host | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` | No Nginx Proxy Manager, Nginx, Caddy, or Traefik container detected. |
 
 ---
 
@@ -48,7 +64,7 @@ The following parameters represent verified technical realities of the target ho
 ```mermaid
 graph TD
     subgraph Host["Sam's Oracle VM (Oracle Linux 9.7)"]
-        subgraph DockerRuntime["Docker Engine 29.5.0"]
+        subgraph DockerRuntime["Docker Engine 29.5.0 (PREVIOUSLY VERIFIED — RECONFIRM LIVE)"]
             Portainer["Portainer CE (portainer/portainer-ce:lts)"]
             NodeRED["Node-RED (nodered/node-red:latest)"]
             DockerSock[("/var/run/docker.sock")]
@@ -60,23 +76,38 @@ graph TD
         Portainer --- VolPortainer
         NodeRED --- VolNodeRed
     end
-    
-    ClientExternal["Internet / 0.0.0.0/0"] -->|TCP 9443 (HTTPS)| Portainer
-    ClientExternal -->|TCP 8000| Portainer
-    ClientLocal["Local / Internal Network"] -.->|TCP 1880| NodeRED
+
+    subgraph OCI_Ingress["OCI Security List & NSG Layer (REPORTED — NOT INDEPENDENTLY VERIFIED)"]
+        OCI_9443["TCP 9443 (Reported Permitted in Notes)"]
+        OCI_8000["TCP 8000 (Reported Dropped at VCN)"]
+        OCI_1880["TCP 1880 (Reported Dropped at VCN)"]
+    end
+
+    subgraph Host_Firewall["Host firewalld Layer (PENDING LIVE VERIFICATION)"]
+        FW_Rules["firewalld: public zone (only ssh/dhcpv6 observed previously)"]
+    end
+
+    ClientExternal["Internet / 0.0.0.0/0"] -.->|Potential Ingress (Unverified)| OCI_9443
+    OCI_9443 -.->|Subject to firewalld & iptables| FW_Rules
+    FW_Rules -.->|Docker Published Binding: 0.0.0.0:9443| Portainer
+
+    ClientExternal -.->|Reported Dropped at VCN| OCI_8000
+    ClientExternal -.->|Reported Dropped at VCN| OCI_1880
+    ClientLocal["Local Administrative SSH Tunnel"] -->|TCP 1880 / 9443| Host
 ```
 
-#### Container 1: Portainer CE
+#### Container 1: Portainer CE (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`)
 - **Image:** `portainer/portainer-ce:lts`
 - **Purpose:** Host container and stack lifecycle management.
 - **Volume Mounts:** `portainer_data` mapped to `/data`.
 - **System Bind Mounts:** `/var/run/docker.sock` mapped to `/var/run/docker.sock` with read/write access.
 - **Network Ports:** 
   - `0.0.0.0:9443 -> 9443/tcp` (Web UI HTTPS)
-  - `0.0.0.0:8000 -> 8000/tcp` (Edge agent communication tunnel)
-- **Security Posture:** Highly privileged. Possesses full root-equivalent control of the Docker daemon and the host.
+  - `0.0.0.0:8000 -> 8000/tcp` (Published host port; Edge Agent presence is unverified — do not assume Edge Agent is installed merely because port 8000 is published)
+- **Security Posture:** Highly privileged. Possesses full root-equivalent control of the Docker daemon and the host via `/var/run/docker.sock`. Critical management-plane risk.
+- **Git Integration Capability:** `REPORTED — NOT INDEPENDENTLY VERIFIED`. Portainer CE supports deploying stacks from a Git repository; live verification is required to confirm whether this specific instance has outbound HTTPS reachability to `github.com`.
 
-#### Container 2: Node-RED
+#### Container 2: Node-RED (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`)
 - **Image:** `nodered/node-red:latest`
 - **Purpose:** Event-driven workflow automation and integration runtime.
 - **Volume Mounts:** `nodered_data` mapped to `/data`.
@@ -91,52 +122,79 @@ graph TD
 
 A detailed comparison between the host network listeners and the OCI Security List rules reveals critical operational and security facts:
 
-| Port | Service / Container | Host Binding | OCI Security List (`0.0.0.0/0`) | Reachability from Public Internet | Audit Finding |
+| Port | Service / Container | Host Binding | OCI Security List (`0.0.0.0/0`) | Public Reachability | Audit Finding & Classification |
 | :--- | :--- | :--- | :--- | :--- | :--- |
-| **22** | OpenSSH (`sshd`) | `0.0.0.0:22` | **PERMITTED** | Reachable (Public) | Standard remote administration. Key-based authentication required. |
-| **80** | None (Idle) | *None* | **PERMITTED** | Open at firewall, no service | **Available for Reverse Proxy Ingress**. |
-| **443** | None (Idle) | *None* | **PERMITTED** | Open at firewall, no service | **Available for Reverse Proxy HTTPS**. |
-| **1880** | Node-RED | `0.0.0.0:1880` | **BLOCKED** | Blocked at OCI VCN layer | Protected from public ingress by OCI VCN boundary. |
-| **3306** | None (Idle) | *None* | **BLOCKED** | Not permitted | Host port is clean. Database will not bind to host. |
-| **8000** | Portainer Agent | `0.0.0.0:8000` | **BLOCKED** | Blocked at OCI VCN layer | Published locally, blocked at VCN ingress. |
-| **9443** | Portainer HTTPS | `0.0.0.0:9443` | **PERMITTED** | **EXPOSED TO PUBLIC INTERNET** | **CRITICAL SECURITY RISK**. Management UI is publicly reachable. |
+| **22** | OpenSSH (`sshd`) | `0.0.0.0:22` (`VERIFIED`) | `REPORTED — NOT INDEPENDENTLY VERIFIED` (Permitted in notes) | Subject to OCI & host firewall | Host listener active. Password auth and root login status: `PENDING LIVE VERIFICATION`. |
+| **80** | None (Idle) | *None* (`VERIFIED`) | `REPORTED — NOT INDEPENDENTLY VERIFIED` (Permitted in notes) | No host listener active | Host listener absent. Does NOT prove public reachability until service binds and firewalld is verified. |
+| **443** | None (Idle) | *None* (`VERIFIED`) | `REPORTED — NOT INDEPENDENTLY VERIFIED` (Permitted in notes) | No host listener active | Host listener absent. Reverse proxy remains `PROPOSED — NOT APPROVED`. |
+| **1880** | Node-RED | `0.0.0.0:1880` (`VERIFIED`) | `REPORTED — NOT INDEPENDENTLY VERIFIED` (Blocked in notes) | Blocked at VCN (Reported) | Host listener active. VCN boundary filtering requires current OCI verification. |
+| **3306** | None (Idle) | *None* (`VERIFIED`) | `REPORTED — NOT INDEPENDENTLY VERIFIED` (Blocked in notes) | Not exposed on host | Host listener absent. Database must not bind to host. |
+| **8000** | Portainer (Unknown Service)| `0.0.0.0:8000` (`VERIFIED`) | `REPORTED — NOT INDEPENDENTLY VERIFIED` (Blocked in notes) | Blocked at VCN (Reported) | Host listener active. Edge Agent presence unverified. |
+| **9443** | Portainer HTTPS | `0.0.0.0:9443` (`VERIFIED`) | `REPORTED — NOT INDEPENDENTLY VERIFIED` (Permitted in notes) | Exposed if OCI rule active | Critical management-plane risk. Access strategy requires Founder approval (Gate 2). |
 
 ### 4.2 Key Ingress Findings
-1. **Zero Web Ingress Active:** Although OCI Security List rules permit inbound TCP traffic on ports 80 and 443, **no reverse proxy, web server, or container is listening on these ports**.
-2. **Reverse Proxy Status:** There is **NO EXISTING REVERSE PROXY** on Sam's Oracle VM. The old Nginx Proxy Manager container (`lohnour-npm-1`) belonged to the superseded Ubuntu VM (`193.122.206.31`) and does not exist here.
-3. **Clean Web Port Availability:** Ports 80 and 443 are 100% unencumbered. A dedicated reverse proxy can bind directly to these ports without port conflicts or stopping existing services.
-4. **Public Exposure of Portainer:** Port 9443 is directly accessible to anyone on the public internet, posing an urgent attack vector.
+1. **Zero Web Ingress Active:** Although past audit notes report that OCI Security List rules permit inbound TCP traffic on ports 80 and 443, **no reverse proxy, web server, or container is listening on these ports**.
+2. **Reverse Proxy Status (`PROPOSED — NOT APPROVED`):** There is **NO EXISTING REVERSE PROXY** on Sam's Oracle VM. The previous Nginx Proxy Manager container (`lohnour-npm-1`) belonged to the old Ubuntu VM (`193.122.206.31`) and does not exist here. Any reverse proxy implementation remains **PROPOSED — NOT APPROVED** pending Founder selection (Gate 4).
+3. **Multi-Layer Perimeter Distinction & Verification Requirements:**
+   - *Previously Observed Host Firewall:* Oracle Linux 9.7 runs `firewalld` by default. Previous inspection showed `firewalld` active with public zone permitting only `ssh` and `dhcpv6-client`. Host `firewalld` can drop external packets before Docker's `iptables`/`nftables` chains process them unless `firewall-cmd --add-service=http` and `https` are explicitly active and approved (Gate 2).
+   - *Previously Observed Host Listeners:* Listeners were observed on TCP 22 (`sshd`), 8000 (Portainer published port), 1880 (Node-RED), and 9443 (Portainer HTTPS). Zero listeners were observed on TCP 80, 443, or 3306.
+   - *Reported OCI Security List Configuration:* Previous notes report OCI Security List permitting inbound TCP 22, 80, 443, and 9443 from `0.0.0.0/0`, while dropping ports 1880 and 8000.
+   - *Unverified Current Ingress:* Current OCI Security List and Network Security Group (NSG) rules remain `REPORTED — NOT INDEPENDENTLY VERIFIED`. Do not infer OCI exposure from host firewall output or container bindings alone. Current OCI Console confirmation is required before deployment.
+4. **Portainer 9443 Exposure (Potential Critical Risk Pending OCI Ingress Verification):** Portainer binds to host port `0.0.0.0:9443` via Docker port publishing (`VERIFIED`). However, public internet reachability depends on the multi-layer perimeter: the host TCP listener (`0.0.0.0:9443`), OS `firewalld` filtering, OCI Cloud Security Lists, and Network Security Groups (NSGs). Past notes report that OCI permits inbound `0.0.0.0/0` on port 9443 (`REPORTED — NOT INDEPENDENTLY VERIFIED`). If active in OCI, this management plane is publicly reachable, creating a potential critical vulnerability due to the mounted Docker daemon socket (`/var/run/docker.sock`). Management-plane access hardening requires explicit Founder approval (Gate 2).
+5. **Downtime & Disruption Realism:** Zero downtime and guaranteed deployment duration claims are completely unsupported. Public DNS cutover involves unavoidable propagation latency and maintenance downtime. Furthermore, zero disruption to Node-RED and Portainer cannot be guaranteed due to shared host CPU, memory, and network resources.
 
 ---
 
 ## 5. Compute, Memory & Storage Capacity Assessment
 
-### 5.1 Compute (CPU) Capacity
-- **Available Compute:** 4 OCPU (4 physical/virtual CPU cores).
-- **Current Load:** Portainer and Node-RED in an idle state consume `< 0.05 OCPU` combined.
-- **Projected WordPress + MariaDB Load:**
-  - MariaDB 10.11: ~0.2 to 0.5 OCPU under typical production loads.
-  - WordPress (Apache + PHP 8.3): ~0.3 to 1.0 OCPU during peak requests.
-  - Reverse Proxy (Nginx / NPM): ~0.05 to 0.15 OCPU.
-- **CPU Feasibility:** **EXCELLENT**. The WordPress production stack will consume ~1.5 OCPU under load, leaving > 2.5 OCPU (> 60%) available for background cron tasks, bursts, and future AI agent workflows.
+### 5.1 Compute (CPU) Capacity & Proposed Limits (`PROPOSED`)
+- **Host Total Compute:** 4 OCPU (4 physical/virtual CPU cores).
+- **Current Baseline Usage:** Minimal idle usage was previously observed for Portainer and Node-RED; however, current runtime CPU consumption has not been established and cannot be assumed.
+
+#### Proposed CPU Limit Allocation Matrix (`PROPOSED — NOT APPROVED — NOT DEPLOYED`):
+
+| Service | Proposed CPU limit |
+|---|---:|
+| WordPress | 1.00 OCPU |
+| MariaDB | 1.00 OCPU |
+| Reverse proxy (only if selected) | 0.25 OCPU |
+| **Total application CPU limit** | **2.25 OCPU** |
+| **Nominal capacity not assigned to these application limits** | **1.75 OCPU** |
+
+*Contingency (if no reverse proxy is selected):*
+
+| Metric | Value |
+|---|---:|
+| WordPress limit | 1.00 OCPU |
+| MariaDB limit | 1.00 OCPU |
+| Total application CPU limit | 2.00 OCPU |
+| Nominal capacity not assigned to these application limits | 2.00 OCPU |
+
+> [!IMPORTANT]
+> **MANDATORY TECHNICAL CPU RESOURCE CLARIFICATIONS:**
+> 1. **CPU Limits:** Define the maximum CPU capacity a container can consume under configured Docker CPU controls (Linux CFS scheduler). They are **not** guaranteed CPU allocations, nor do they imply that the service continuously consumes that CPU capacity.
+> 2. **CPU Reservations:** Distinct from limits. No CPU reservations have been verified or approved for this stack; therefore, **CPU reservations remain unspecified**.
+> 3. **Actual CPU Usage:** Actual runtime CPU consumption has **not** been established by this documentation-only task. Proposed limits must not be presented as measured usage, and unused CPU capacity must not be claimed as dedicated to Portainer or Node-RED.
+> 4. **Existing Workloads & Contention:** Portainer and Node-RED are already active on the VM. Their actual CPU consumption and configured limits have not been measured by this task. The nominal unassigned capacity (1.75 OCPU, or 2.00 OCPU without proxy) is shared dynamically with existing services and the host operating system. This allocation does **not** guarantee headroom or prevent CPU contention.
+> 5. **Status:** This allocation is strictly **`PROPOSED — NOT APPROVED — NOT DEPLOYED`**.
 
 ### 5.2 Memory (RAM) Capacity
-- **Available Memory:** 22 GiB usable RAM (24 GiB total).
-- **Current Allocation:**
+- **Available Memory:** ~22 GiB usable RAM (24 GiB total provisioned).
+- **Historical Baseline Estimates (`ESTIMATED`):**
   - Host OS + Systemd: ~550 MiB
   - Portainer CE: ~80 MiB
   - Node-RED: ~180 MiB
-  - Total Current Active RAM: **~810 MiB (< 4% of total RAM)**.
+  - Total Estimated Active Baseline RAM: **~810 MiB (< 4% of total RAM)**.
 - **Proposed Target Stack Allocation:**
-  - MariaDB 10.11 (`innodb_buffer_pool_size = 2G`): ~2,500 MiB
-  - WordPress (Apache MPM prefork/worker + PHP 8.3, `memory_limit = 512M`, 15-25 workers): ~2,000 MiB
-  - Reverse Proxy (Nginx Proxy Manager): ~250 MiB
-  - Total Stack Footprint: **~4,750 MiB (~4.75 GiB)**.
-- **Total System RAM Post-Deployment:** **~5.56 GiB (25.3% of 22 GiB)**.
-- **Remaining RAM Headroom:** **~16.4 GiB (> 74% free)**.
-- **Memory Feasibility:** **SUPERIOR**. Ample memory is available to support extensive MySQL query caching, Redis object caching (if added later), and high-concurrency traffic without risk of Out-Of-Memory (OOM) kills.
-
-### 5.3 Storage & Volume Capacity
+  - MariaDB: Limit `3,072 MiB` (Reservation: `1,024 MiB`).
+  - WordPress: Limit `3,072 MiB` (Reservation: `512 MiB`).
+  - Proposed Reverse Proxy: Limit `512 MiB` (Reservation: `128 MiB`).
+  - **Total Stack Maximum Allocation:** **6,656 MiB (~6.5 GiB)**.
+- **Estimated System RAM Post-Deployment:** **~7.46 GiB (~34% of 22 GiB)**.
+- **Nominal Unallocated Memory:** **~14.5 GiB**.
+- **Memory Feasibility Assessment:** **`ESTIMATED — PENDING RUNTIME VERIFICATION`**. The proposed stack allocations (~6.5 GiB maximum limits, ~1.66 GiB reservations) appear numerically feasible within the previously observed ~22 GiB usable RAM baseline. However, actual runtime memory consumption has not been established by this documentation task, and proposed container limits do not guarantee that Out-Of-Memory (OOM) killer events cannot occur under heavy application traffic, concurrent database queries, or unconstrained host memory pressure. Continuous resource monitoring is required during staging and deployment.
+
+### 5.3 Storage & Volume Sizing
 - **Authoritative Backup Sizing (from `_audit/production-extract/`):**
   - Database SQL dump (`production-lohnour_wp.sql`): `290,627 bytes` (~284 KiB).
   - Uncompressed MariaDB InnoDB Tablespace: ~25 MiB.
@@ -145,67 +203,99 @@ A detailed comparison between the host network listeners and the OCI Security Li
   - Plugin archive (`lohnour_all_plugins.tar.gz`): `5,646,919 bytes` (~5.5 MiB compressed, ~22 MiB uncompressed).
   - Core WordPress filesystem: ~65 MiB.
   - Total Application Volume Footprint: **< 250 MiB**.
-- **Docker Image Layer Footprint:**
-  - `wordpress:latest`: ~650 MiB
-  - `mariadb:10.11`: ~410 MiB
-  - `jc21/nginx-proxy-manager:latest`: ~380 MiB
+- **Docker Image Layer Footprint (Estimated):**
+  - WordPress image: ~650 MiB
+  - MariaDB image: ~410 MiB
+  - Reverse Proxy image: ~380 MiB
   - Total Image Storage: **~1.44 GiB**.
-- **Storage Feasibility:** **PASS**. The complete deployment requires < 2.0 GiB of disk space, easily fitting into any standard OCI boot volume (typically 47 GB to 200 GB).
+- **Storage Capacity & Feasibility Assessment:** **`PENDING VERIFICATION`**.
+  - *Previously Observed Baseline:* Root filesystem (`/`) was previously observed to have 30 GB total capacity with ~16 GB available free space.
+  - *Docker Root Dir Uncertainty:* The actual Docker Root Dir has not yet been independently established in current documentation evidence (must be verified on host via `docker info --format '{{.DockerRootDir}}'`).
+  - *Filesystem Mountpoint Verification:* The specific host filesystem backing Docker data must be verified via `df -h` to determine exact available space.
+  - *Pre-Transfer Measurement Mandate:* Available disk space must be measured directly on the host immediately prior to archive transfer or staging.
+  - *Growth & Operational Overhead Factors:* Sizing calculations must account not merely for static archive sizes (< 250 MiB) and image layers (~1.44 GiB), but also for dynamic database growth, extracted WordPress core/plugin/theme files, temporary archive transfers in `/opt/lohnour/staging`, automated local backups, and rollback snapshots.
+  - *Feasibility Status:* Storage feasibility cannot be approved based on static archive estimates alone and remains **PENDING VERIFICATION**.
 
 ---
 
-## 6. Authoritative WordPress Backup Verification
+## 6. Authoritative Backup & Image Compatibility Analysis
 
-The authoritative source of truth for the Lohnour production state is verified at:  
+### 6.1 Authoritative Backup Artifacts (Local Storage Only)
+The authoritative source of truth for the Lohnour production state is verified locally at:  
 `D:\lohnourpro\_audit\production-extract\`
 
 All files have been verified locally using SHA-256 cryptographic hashes against `manifest-all.sha256`:
 
-```
-Algorithm: SHA-256
-Root Path: D:\lohnourpro\_audit\production-extract\
-```
-
-| Component | Relative Path | File Size | Verified SHA-256 Checksum | Integrity Status |
-| :--- | :--- | :--- | :--- | :--- |
-| **Database** | `database/production-lohnour_wp.sql` | 290,627 B | `db8628e7743b133e372265a7d09ac29729ab7f7c8ed9dfcd0bdd257d7e88a880` | **MATCH (100%)** |
-| **Uploads** | `uploads.tar.gz` | 59,733,182 B | `5001b5d43159e4611540661d8af50a9fd0301e06a4224587cb38f7f68983b0d9` | **MATCH (100%)** |
-| **Child Theme** | `twentytwentyfive-child.tar.gz` | 6,229,809 B | `a14af7f749f0e459b60658514e79325a29a3bb7fdc98c56d9591e1faadca802d` | **MATCH (100%)** |
-| **All Plugins** | `lohnour_all_plugins.tar.gz` | 5,646,919 B | `1e2c4bb948ea06621a52b1911b6d6a29868695798d8e37753454643e0be68484` | **MATCH (100%)** |
-| **Program CMS**| `lohnour_program_cms.tar.gz` | 18,777 B | `b6807f7c19c5cbbfe028c24949fbbe8fca65dc2f8da8be5b7a14ec84869a842b` | **MATCH (100%)** |
-| **Docker Compose**| `config/docker-compose.yml` | 1,545 B | `c6ae0df4d16853879d71c4860b0e3dd6860b73cce3892d47da1f2b2b1eb329fb` | **MATCH (100%)** |
-| **PHP Config** | `config/uploads.ini` | 119 B | `8f8f90dd0d2ff804e84b72ef35b8cf0f214ceb3664d6dbdb9db5d4a13d7890ec` | **MATCH (100%)** |
-| **WP Config** | `config/wp-config.redacted.php` | 5,922 B | `c53a6704ebf9a3ea77c15e8e8334f68a2bf6cb8e3678fe0168be80608796853c` | **MATCH (100%)** |
-
-> [!IMPORTANT]
-> These backup archives are verified to exist **ONLY** in `D:\lohnourpro\_audit\production-extract\`. They do **NOT** exist in the GitHub repository and are **NOT** pre-staged on Sam's Oracle VM. They must be securely transferred to the VM during deployment.
+| Component | Relative Path | File Size | Verified SHA-256 Checksum | Integrity & Classification |
+| :--- | :--- | :--- | :--- | :---: |
+| **Database** | `database/production-lohnour_wp.sql` | 290,627 B | `db8628e7743b133e372265a7d09ac29729ab7f7c8ed9dfcd0bdd257d7e88a880` | Local file presence & hash: `VERIFIED — CURRENT`. Schema contents, table count, and SQL compatibility: `PENDING — DUMP INSPECTION REQUIRED` (BLOCKER 7). |
+| **Uploads** | `uploads.tar.gz` | 59,733,182 B | `5001b5d43159e4611540661d8af50a9fd0301e06a4224587cb38f7f68983b0d9` | `VERIFIED — CURRENT` |
+| **Child Theme** | `twentytwentyfive-child.tar.gz` | 6,229,809 B | `a14af7f749f0e459b60658514e79325a29a3bb7fdc98c56d9591e1faadca802d` | `VERIFIED — CURRENT` |
+| **All Plugins** | `lohnour_all_plugins.tar.gz` | 5,646,919 B | `1e2c4bb948ea06621a52b1911b6d6a29868695798d8e37753454643e0be68484` | `VERIFIED — CURRENT` |
+| **Program CMS**| `lohnour_program_cms.tar.gz` | 18,777 B | `b6807f7c19c5cbbfe028c24949fbbe8fca65dc2f8da8be5b7a14ec84869a842b` | `VERIFIED — CURRENT` |
+| **PHP Config** | `config/uploads.ini` | 119 B | `8f8f90dd0d2ff804e84b72ef35b8cf0f214ceb3664d6dbdb9db5d4a13d7890ec` | `VERIFIED — CURRENT` |
+| **WP Config** | `config/wp-config.redacted.php` | 5,922 B | `c53a6704ebf9a3ea77c15e8e8334f68a2bf6cb8e3678fe0168be80608796853c` | `VERIFIED — CURRENT` |
+| **Historical Compose**| `config/docker-compose.yml` | 1,545 B | `c6ae0df4d16853879d71c4860b0e3dd6860b73cce3892d47da1f2b2b1eb329fb` | **HISTORICAL ARCHIVE — REFERENCE ONLY — NOT APPROVED FOR PRODUCTION** |
+
+> [!WARNING]
+> **HISTORICAL COMPOSE FILE STATUS:**
+> `D:\lohnourpro\_audit\production-extract\config\docker-compose.yml` is classified as:  
+> **`HISTORICAL ARCHIVE — REFERENCE ONLY — NOT APPROVED FOR PRODUCTION`**  
+> - Its existence does not establish production readiness.
+> - It must **NOT** be copied into the active deployment directory.
+> - It must **NOT** be used as the production stack configuration.
+> - It must **NOT** be modified or repurposed as part of this task.
+> - Any future reuse requires a separate technical review, compatibility verification, security review, and explicit Founder approval.
+> - The canonical production Compose path remains **`COMPOSE FILE LOCATION — NOT YET DECIDED`** until a separately reviewed and approved configuration is committed to the `developer` branch in GitHub.
+
+### 6.2 WordPress Image Compatibility Gate (`WORDPRESS IMAGE SELECTION: BLOCKED`)
+The SQL dump contains database schema revision `61833`. However, **database revision `61833` alone is NOT sufficient evidence to determine the correct WordPress container image**.
+
+Until all required evidence has been obtained, reviewed, and approved, status is:  
+**`WORDPRESS IMAGE SELECTION: BLOCKED`**
+
+Before image selection, evidence must be established for:
+1. **Exact WordPress Core Version:** Identify the exact WordPress core version used by the existing website (evidence from source, backup, or another authoritative artifact; do not infer from database revision alone).
+2. **PHP Version & Required Extensions:** Establish the PHP version required by the identified core version; check compatibility with the active child theme and installed plugins; verify required PHP extensions; do not assume PHP 8.2, 8.3, or any other version without evidence.
+3. **Theme & Plugin Compatibility:** Identify all installed plugins and versions; verify compatibility with selected core and PHP versions; identify abandoned, outdated, or incompatible plugins without silent modifications.
+4. **MariaDB Version & Database Compatibility:** Establish the existing database engine and version; verify compatibility with selected WP image and application requirements.
+5. **Database Schema & Migration Compatibility:** Verify compatibility with the extracted schema, noting the significance and limitations of reported revision `61833` (insufficient proof of core compatibility).
+6. **CPU Architecture & Platform Compatibility:** Confirm image platform support matches host architecture (`linux/arm64` vs `linux/amd64`).
+7. **Immutable Version & Digest Pinning:** Record the exact image tag and immutable SHA-256 digest (`image@sha256:...`); do not use rolling `:latest` tags.
+8. **Staging Restoration & Functional Testing:** Perform successful staging restoration and functional validation with the production database dump before production approval.
+
+**Mandatory Status:** `WORDPRESS IMAGE SELECTION: BLOCKED`.
 
 ---
 
 ## 7. Categorization of Findings: Facts vs Assumptions vs Unknowns
 
-To maintain complete audit integrity, all evidence is strictly divided into three categories:
-
-### 7.1 Verified Facts
-1. Sam's Oracle VM runs **Oracle Linux 9.7** with **Docker 29.5.0** and **Docker Compose 5.1.3**.
-2. Total available hardware resources are **4 OCPU** and **22 GiB usable RAM**.
-3. Existing active containers are strictly **Portainer CE (`portainer/portainer-ce:lts`)** and **Node-RED (`nodered/node-red:latest`)**.
-4. Portainer has `/var/run/docker.sock` mounted and publishes port `9443` and `8000`.
-5. OCI Security List permits inbound traffic on ports `22`, `80`, `443`, and `9443` from `0.0.0.0/0`.
-6. There are **zero active listeners** on TCP `80`, `443`, or `3306`.
-7. There are **zero existing Docker Compose projects** active on the VM.
-8. There is **no reverse proxy** currently running on Sam's Oracle VM.
-9. Authoritative backup archives exist locally in `D:\lohnourpro\_audit\production-extract\` and match their SHA-256 manifests.
-
-### 7.2 Technical Assumptions
-1. **CPU Architecture:** It is assumed that 4 OCPU with 24 GB provisioned RAM represents an Oracle Cloud "Always Free" **Ampere A1 (ARM64 / `aarch64`)** instance. If true, all container images pulled must support `linux/arm64`. (Both `wordpress:latest`, `mariadb:10.11`, and `jc21/nginx-proxy-manager:latest` natively publish official multi-arch manifests supporting ARM64).
-2. **OS Firewall:** It is assumed that Oracle Linux 9.7 has `firewalld` running by default. Because Docker manages `iptables`/`nftables` rules directly, host-level firewall zones may drop external traffic on ports 80/443 unless explicitly allowed via `firewall-cmd`.
-3. **SELinux:** It is assumed that SELinux is set to `Enforcing` mode, standard for Oracle Linux 9.7. Container volume bind mounts will require proper SELinux file context labelling (`:z` or container SELinux policy).
-4. **Node-RED Ingress:** It is assumed that Node-RED does not require public exposure on the web and will remain private or be proxied behind authenticated routes.
-
-### 7.3 Unknowns Requiring Clarification Before Deployment
-1. **Architecture Confirmation:** Exact CPU architecture (`uname -m`: `x86_64` vs `aarch64`).
-2. **Domain & DNS Pointing:** Authoritative production domain (e.g., `pro.lohnour.com`) and whether its DNS A record has already been pointed to Sam's Oracle VM public IP.
-3. **DNS Proxy / CDN:** Whether the domain uses Cloudflare proxying (Orange Cloud) and what SSL/TLS encryption mode is active (`Full (Strict)` recommended).
-4. **Firewalld & SELinux Status:** Active status of `firewalld` (`systemctl status firewalld`) and SELinux mode (`getenforce`).
-5. **Portainer Access Credentials:** Confirmation of founder ownership of Portainer admin credentials and approval of the Portainer perimeter restriction strategy.
+### 7.1 Verified Baseline Facts (`VERIFIED — CURRENT` & `PREVIOUSLY VERIFIED — RECONFIRM LIVE`)
+1. Sam's Oracle VM runs **Oracle Linux Server 9.7** with **Docker 29.5.0** and **Docker Compose 5.1.3** (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
+2. Hardware capacity is **4 OCPU** and approximately **22 GiB usable RAM** (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
+3. Root filesystem was previously observed as **30 GB total with ~16 GB available free space** (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
+4. Host identity baseline: hostname is `sams-oracle-vm`, private IP is `10.0.0.100`, SSH management user is `opc`, and previously verified public IPv4 is **`141.148.51.166`** (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`). This address is a historical baseline observation, not a guarantee that the IP cannot change. Live reconfirmation in OCI is strictly required before any DNS cutover. Current DNS does NOT point to this IP.
+5. Existing active containers are strictly **Portainer CE (`portainer/portainer-ce:lts`)** and **Node-RED (`nodered/node-red:latest`)** (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
+6. Existing named Docker volumes are strictly **`portainer_data`** and **`nodered_data`** (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
+7. Portainer has `/var/run/docker.sock` mounted with read/write host root privileges (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
+8. Host listeners previously observed: TCP `22` (sshd), `8000` (Portainer published port), `1880` (Node-RED), and `9443` (Portainer HTTPS) (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
+9. Host firewall daemon (`firewalld`) is active; public firewalld zone previously showed `ssh` and `dhcpv6-client` (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
+10. Zero active listeners were observed on TCP `80`, `443`, or `3306` during the initial inspection (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
+11. Zero existing Docker Compose projects are active on the VM (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
+12. Zero reverse proxies are currently running on Sam's Oracle VM (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
+13. Local backup extract files in `D:\lohnourpro\_audit\production-extract\` match their SHA-256 manifests (`VERIFIED — CURRENT` — Stage 1 of Gate 6; schema contents and compatibility: `PENDING — DUMP INSPECTION REQUIRED`).
+
+### 7.2 Technical Proposals (`PROPOSED — NOT APPROVED — NOT DEPLOYED`)
+1. **CPU Allocation:** Proposed application container limits totaling 2.25 OCPU (WP: 1.00, DB: 1.00, Proxy: 0.25), leaving 1.75 OCPU nominal capacity unassigned (or 2.00 OCPU total / 2.00 OCPU unassigned without proxy) (`PROPOSED — NOT APPROVED — NOT DEPLOYED`).
+2. **Reverse Proxy Architecture:** Proposed ingress proxy evaluated across alternatives (NPM / Caddy / Traefik / Standalone Nginx / Cloudflare Tunnel); unapproved pending Founder selection (Gate 4).
+3. **Network Isolation:** Proposed private internal bridge `lohnour_db_network` with zero public database exposure.
+
+### 7.3 Unknowns Requiring Clarification Before Deployment (`UNKNOWN / PENDING VERIFICATION`)
+1. **CPU Architecture Confirmation:** Exact host CPU architecture (`uname -m`: `x86_64` vs `aarch64`) is `REPORTED — NOT INDEPENDENTLY VERIFIED`.
+2. **OCI Security List Ingress:** Inbound rules on ports 22, 80, 443, 9443 and dropped packets on 1880, 8000 are `REPORTED — NOT INDEPENDENTLY VERIFIED` (requires current live OCI console verification).
+3. **Docker Root Dir & Storage Path:** The actual Docker Root Dir has not yet been established in documentation evidence (requires `docker info --format '{{.DockerRootDir}}'`). Available disk space on the specific filesystem backing Docker data must be measured via `df -h` before staging.
+4. **Gate 6 Multi-Stage Completion:** While Stage 1 (local archive checksums) is `VERIFIED`, Stage 2 (remote transfer integrity) and Stage 3 (restore validation in isolated staging) are `NOT STARTED` and remain `PENDING VERIFICATION`.
+5. **SSH Server Configuration:** Whether password authentication or root login is disabled in `/etc/ssh/sshd_config` is `PENDING LIVE VERIFICATION`.
+6. **Portainer Edge Agent Status:** Published port 8000 does not prove Edge Agent is active; status is `UNKNOWN`.
+7. **Domain & DNS Pointing:** Authoritative production domain DNS A-record pointing status for `pro.lohnour.com` is `UNKNOWN` (Gate 8).
+8. **Zero Downtime & Disruption Disclaimer:** Zero downtime and guaranteed deployment duration claims are unsupported. Downtime will occur during cutover. Furthermore, zero disruption to Node-RED and Portainer cannot be guaranteed due to shared host CPU, memory, and network resources.
`

---

## File Diff: SECURITY_REVIEW.md

- **Baseline:** D:\lohnourpro\current source\lohnourpro_wp_theme\my-docs\SECURITY_REVIEW.md
- **Canonical:** D:\lohnourpro\docs\SECURITY_REVIEW.md

`diff
diff --git "a/D:\\lohnourpro\\current source\\lohnourpro_wp_theme\\my-docs\\SECURITY_REVIEW.md" "b/D:\\lohnourpro\\docs\\SECURITY_REVIEW.md"
index 3d001b0..21b3db4 100644
--- "a/D:\\lohnourpro\\current source\\lohnourpro_wp_theme\\my-docs\\SECURITY_REVIEW.md"	
+++ "b/D:\\lohnourpro\\docs\\SECURITY_REVIEW.md"
@@ -5,6 +5,8 @@
 **Auditor / Role:** Senior DevOps Engineer & Infrastructure Security Auditor  
 **Target Environment:** Sam's Oracle VM (Oracle Linux 9.7)  
 **Operating Mode:** STRICT READ-ONLY FORENSICS — ZERO DEPLOYMENT MUTATIONS  
+**Canonical Documentation Path:** `D:\lohnourpro\docs\SECURITY_REVIEW.md`  
+*(Note: Duplicate copies existing in `current source/lohnourpro_wp_theme/my-docs/` are historical theme artifacts and remain untouched).*  
 **Status:** **SECURITY REVIEW COMPLETE — HARDENING STRATEGY DEFINED**
 
 ---
@@ -13,9 +15,9 @@
 
 This Security Review provides an exhaustive risk assessment of the infrastructure hosting Lohnour Pro on Sam's Oracle VM. The audit evaluated network exposure, container runtime privileges, boundary isolation, authentication surfaces, and host operating system controls.
 
-The most critical finding of this assessment is the **unrestricted public exposure of Portainer CE on TCP port 9443 combined with an unmitigated Docker daemon socket mount (`/var/run/docker.sock`)**. This configuration provides root-equivalent host execution to any actor who compromises Portainer.
+The most critical finding of this assessment is the **potential critical risk of public exposure of Portainer CE on TCP port 9443 pending OCI ingress verification, combined with an unmitigated Docker daemon socket mount (`/var/run/docker.sock`)**. While Docker binds `0.0.0.0:9443` on the host, public accessibility depends on the multi-layer perimeter (host listener, OS `firewalld`, OCI Security Lists, and NSGs). If inbound port 9443 is open at the OCI boundary as historically reported, this configuration provides root-equivalent host execution to any actor who compromises Portainer.
 
-This document categorizes all discovered vulnerabilities by severity, analyzes threat vectors, and outlines a multi-tiered, founder-approved security strategy designed to harden the perimeter, isolate workloads, and ensure compliance with enterprise zero-trust principles prior to any WordPress deployment.
+This document categorizes all discovered vulnerabilities by severity, analyzes threat vectors, and outlines a multi-tiered security strategy proposed for Founder approval designed to harden the perimeter, isolate workloads, ensure zero credential leakage, and enforce enterprise zero-trust principles prior to any WordPress deployment.
 
 ---
 
@@ -25,13 +27,13 @@ This document categorizes all discovered vulnerabilities by severity, analyzes t
 flowchart TD
     Attacker["Public Internet Threat Actors (0.0.0.0/0)"]
     
-    subgraph OCI_Firewall["OCI Security List Ingress Layer"]
-        Port22["TCP 22 (SSH) - Open"]
-        Port80["TCP 80 (HTTP) - Open"]
-        Port443["TCP 443 (HTTPS) - Open"]
-        Port9443["TCP 9443 (Portainer HTTPS) - OPEN (CRITICAL)"]
-        Port1880["TCP 1880 (Node-RED) - Blocked at VCN"]
-        Port8000["TCP 8000 (Edge Agent) - Blocked at VCN"]
+    subgraph OCI_Firewall["OCI Security List & NSG Ingress Layer (REPORTED — NOT INDEPENDENTLY VERIFIED)"]
+        Port22["TCP 22 (SSH) - Reported Permitted (Pending Live Check)"]
+        Port80["TCP 80 (HTTP) - Reported Permitted / Host Idle (No Service)"]
+        Port443["TCP 443 (HTTPS) - Reported Permitted / Host Idle (No Service)"]
+        Port9443["TCP 9443 (Portainer HTTPS) - Reported Permitted (Potential Critical Risk)"]
+        Port1880["TCP 1880 (Node-RED) - Reported Dropped at VCN (Unverified)"]
+        Port8000["TCP 8000 (Edge Agent) - Reported Dropped at VCN (Unverified)"]
     end
 
     subgraph HostVM["Sam's Oracle VM (Oracle Linux 9.7)"]
@@ -45,18 +47,18 @@ flowchart TD
         DB["Proposed MariaDB Container"]
     end
 
-    Attacker -->|Brute-force / Zero-day Exploit| Port9443
-    Port9443 --> Portainer
+    Attacker -.->|Potential Reachability Pending Ingress Check| Port9443
+    Port9443 -.-> Portainer
     Portainer -->|Root-Equivalent API Control| Sock
     Sock -->|Container Escape / Host Takeover| HostVM
     
-    Attacker -.->|Direct SQL Attack (BLOCKED)| DB
-    Attacker -->|Web Traffic (Port 80/443)| WP
+    Attacker -.->|Direct SQL Attack (BLOCKED - Internal Network Only)| DB
+    Attacker -.->|Pending Reverse Proxy Selection (Gate 4)| WP
 ```
 
 The infrastructure exposes two primary perimeter surfaces:
-1. **The Application Layer (Ports 80 & 443):** Intended for public web traffic. Currently idle, pending reverse proxy deployment.
-2. **The Administrative Layer (Ports 22 & 9443):** Intended strictly for operational administration. Currently, port 9443 is exposed without network-level filtering.
+1. **The Application Layer (Ports 80 & 443):** Intended for public web traffic. Currently idle, pending reverse proxy selection and approval (Gate 4).
+2. **The Administrative Layer (Ports 22 & 9443):** Intended strictly for operational administration. Port 9443 is bound to `0.0.0.0:9443` on the host; if historical OCI rules permitting `0.0.0.0/0` remain active, it presents an unrestricted public exposure requiring immediate network-level filtering and access hardening (Gate 2).
 
 ---
 
@@ -64,60 +66,74 @@ The infrastructure exposes two primary perimeter surfaces:
 
 ### 3.1 Severity Rating Matrix
 
-| Risk ID | Vulnerability / Misconfiguration | Affected Component | Severity | CVSS v3.1 | Status |
+> [!IMPORTANT]
+> **DISTINCTION BETWEEN HOST BINDING & PUBLIC EXPOSURE (PROVISIONAL RATINGS):**  
+> - **Confirmed Host-Level Configuration:** A container port published to `0.0.0.0` confirms that the Docker daemon binds the port across all local IPv4 network interfaces on Sam's Oracle VM (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
+> - **Unverified External Exposure:** Proven reachability from the public internet depends strictly on multi-layer perimeter filtering: host OS `firewalld` rules, OCI Cloud Security Lists, and OCI Network Security Groups (NSGs). Current OCI ingress configurations remain `REPORTED — NOT INDEPENDENTLY VERIFIED`.
+> - **Provisional Ratings:** CVSS v3.1 base scores and severity classifications for network endpoints (SEC-01, SEC-03, SEC-04) are **provisional worst-case evaluations** assuming public ingress is permitted at the cloud boundary. They do **not** represent confirmed internet exploitability without direct external verification. Actual risk findings are preserved to prevent minimizing threats while accurately distinguishing confirmed host telemetry from unverified cloud exposure.
+
+| Risk ID | Vulnerability / Misconfiguration | Affected Component | Severity | CVSS v3.1 | Status Classification |
 | :--- | :--- | :--- | :---: | :---: | :---: |
-| **SEC-01** | Portainer Web UI Exposed to `0.0.0.0/0` | Portainer / OCI Security List | **CRITICAL** | **9.8** | Active Vulnerability |
-| **SEC-02** | Docker Socket Mount with Host Root Privileges | Portainer CE Runtime | **CRITICAL** | **9.0** | Active Risk |
-| **SEC-03** | Unsecured Edge Agent Port 8000 Binding | Portainer CE / Host | **HIGH** | **7.5** | Latent Risk |
-| **SEC-04** | Node-RED Host Port 1880 Binding | Node-RED Container | **MEDIUM** | **6.5** | Mitigated by VCN |
-| **SEC-05** | OS Firewalld & SELinux Policy Desynchronization | Oracle Linux 9.7 OS | **MEDIUM** | **5.5** | Potential Blocker |
-| **SEC-06** | Plaintext Environment Variable Exposure in Compose | Docker Deployment Config | **MEDIUM** | **5.3** | Architectural Flaw |
-| **SEC-07** | Mutable `:latest` Container Image Tags | Deployment Configuration | **LOW** | **3.7** | Maintenance Risk |
+| **SEC-01** | Portainer Web UI Bound to Host (Potential Exposure Pending Ingress Check) | Portainer / OCI Security List | **PROVISIONAL CRITICAL** | **9.8 (Provisional)** | `REPORTED — NOT INDEPENDENTLY VERIFIED` |
+| **SEC-02** | Docker Socket Mount with Host Root Privileges | Portainer CE Runtime | **CRITICAL** | **9.0** | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` |
+| **SEC-03** | Edge Agent Host Port 8000 Binding (Unverified Perimeter Filtering) | Portainer CE / Host | **PROVISIONAL HIGH** | **7.5 (Provisional)** | `REPORTED — NOT INDEPENDENTLY VERIFIED` |
+| **SEC-04** | Node-RED Host Port 1880 Binding (Unverified Perimeter Filtering) | Node-RED Container | **PROVISIONAL MEDIUM** | **6.5 (Provisional)** | `REPORTED — NOT INDEPENDENTLY VERIFIED` |
+| **SEC-05** | OS Firewalld & SELinux Policy Desynchronization | Oracle Linux 9.7 OS | **MEDIUM** | **5.5** | `PENDING LIVE VERIFICATION` |
+| **SEC-06** | Insecure CLI Password Arguments & Plaintext Secrets | Database Admin / Scripts | **MEDIUM** | **6.0** | `VERIFIED — CURRENT` (Defect identified in documentation) |
+| **SEC-07** | Mutable & Unverified Container Image Tags | Deployment Configuration | **MEDIUM** | **5.8** | `BLOCKED` (GATE 3) |
 
 ---
 
 ### 3.2 Detailed Risk Analysis
 
-#### SEC-01: Public Exposure of Portainer Web UI (Port 9443)
-- **Description:** OCI Security List permits inbound TCP traffic on port `9443` from `0.0.0.0/0`. The Portainer container binds `0.0.0.0:9443 -> 9443/tcp`.
-- **Impact:** Any entity on the internet can probe, scan, and attempt authentication against the Portainer management interface. Attack vectors include:
-  - Automated password spray attacks and credential stuffing against the Portainer admin account.
-  - Exploitation of unauthenticated Portainer CVEs (e.g., initial setup timeout bypass, session token leakage).
-  - Denial of Service (DoS) against the Portainer management daemon.
-- **Risk Level:** **CRITICAL**. A management plane should never be exposed to the public internet.
+#### SEC-01: Portainer Web UI Host Binding (Port 9443) — PROVISIONAL CRITICAL (Pending Ingress Verification)
+- **Confirmed Host Configuration:** Portainer binds host port `0.0.0.0:9443 -> 9443/tcp` via Docker port publishing (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
+- **Unverified External Exposure:** Previous audit notes report that the OCI Security List permits inbound TCP traffic on port `9443` from `0.0.0.0/0` (`REPORTED — NOT INDEPENDENTLY VERIFIED`).
+  - *Multi-Layer Perimeter Distinction:* Public internet reachability is governed by multiple independent layers: the host TCP listener (`0.0.0.0:9443`), Docker's published binding, host OS `firewalld` rules, OCI Security Lists, and OCI Network Security Groups (NSGs). Do not infer OCI cloud firewall rules from host firewall output or container bindings alone. A service listening on `0.0.0.0` does NOT prove external reachability without verified cloud security list ingress.
+- **Potential Threat Impact (If Exposed):** If the reported OCI ingress rule is active, any public entity could probe and attempt authentication against Portainer. Attack vectors include automated brute-force attacks, exploitation of unauthenticated Portainer CVEs, and Denial of Service.
+- **Provisional Classification:** **PROVISIONAL CRITICAL** (CVSS 9.8). Exploitability cannot be confirmed without live external ingress verification, but management-plane access hardening must be enforced under **Gate 2**.
 
-#### SEC-02: Docker Socket Mount with Host Root Privileges
-- **Description:** Portainer is executed with `/var/run/docker.sock:/var/run/docker.sock`.
+#### SEC-02: Docker Socket Mount with Host Root Privileges — CRITICAL
+- **Description:** Portainer is executed with `/var/run/docker.sock:/var/run/docker.sock` mounted directly from the host (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
 - **Impact:** The Docker daemon socket is equivalent to passwordless root access on the host. Any compromised user account within Portainer, or any remote code execution vulnerability in Portainer, allows an attacker to:
   - Spawn privileged containers with host `/` mounted to `/host`.
   - Read or overwrite any file on Sam's Oracle VM (including `/etc/shadow`, SSH authorized keys, and private data).
   - Inspect, stop, or delete the existing Node-RED container and volumes.
   - Exfiltrate production database secrets and customer data.
-- **Risk Level:** **CRITICAL**.
+- **Risk Level:** **CRITICAL** (CVSS 9.0). Confirmed container runtime misconfiguration.
 
-#### SEC-03: Unsecured Edge Agent Port 8000 Binding
-- **Description:** Portainer binds port `8000` on the host. While currently blocked by the OCI Security List, host-level listening consumes resources and exposes an unauthenticated HTTP tunnel endpoint if firewall rules shift.
-- **Risk Level:** **HIGH**.
+#### SEC-03: Host Port 8000 Binding — PROVISIONAL HIGH (Pending Ingress Verification)
+- **Confirmed Host Configuration:** Portainer binds port `8000` on the host (`0.0.0.0:8000`) (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`). Edge Agent presence is unverified (do not claim Portainer Edge Agent is installed merely because port 8000 is published).
+- **Unverified External Exposure:** Past notes report port 8000 dropped at the OCI VCN boundary (`REPORTED — NOT INDEPENDENTLY VERIFIED`). Host-level listening exposes an unnecessary endpoint if perimeter filtering shifts.
+- **Provisional Classification:** **PROVISIONAL HIGH** (CVSS 7.5).
 
-#### SEC-04: Node-RED Host Port 1880 Binding
-- **Description:** The Node-RED container binds `0.0.0.0:1880` on the host. While the OCI Security List currently drops public inbound packets on 1880, any internal lateral movement or accidental security list modification would immediately expose Node-RED flows and credentials to the internet.
-- **Risk Level:** **MEDIUM**.
+#### SEC-04: Node-RED Host Port 1880 Binding — PROVISIONAL MEDIUM (Pending Ingress Verification)
+- **Confirmed Host Configuration:** The Node-RED container binds `0.0.0.0:1880` on the host (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`).
+- **Unverified External Exposure:** While previous audit notes report that the OCI Security List drops public inbound packets on port 1880, this perimeter protection is `REPORTED — NOT INDEPENDENTLY VERIFIED`. Any accidental security list change or internal lateral movement would immediately expose Node-RED flows and credentials to the network.
+- **Provisional Classification:** **PROVISIONAL MEDIUM** (CVSS 6.5).
 
-#### SEC-05: OS Firewalld & SELinux Policy Desynchronization
+#### SEC-05: OS Firewalld & SELinux Policy Desynchronization — `MEDIUM`
 - **Description:** Oracle Linux 9.7 utilizes `firewalld` and `SELinux` in enforcing mode by default.
   - Docker configures its own `iptables`/`nftables` chains, but firewalld default drop zones can intercept external packets destined for Docker ports.
   - SELinux prevents unconfined container processes from accessing host filesystem directories unless labelled with `:z` (shared) or `:Z` (private) flags.
 - **Risk Level:** **MEDIUM**. Failure to account for SELinux and firewalld will cause silent network drops or permission-denied container crashes during deployment.
 
-#### SEC-06: Plaintext Secrets in Compose Files
-- **Description:** Storing database passwords (`DB_ROOT_PASSWORD`, `DB_PASSWORD`) and WordPress authentication keys directly in `.env` files with world-readable permissions creates a credential leakage vulnerability.
-- **Risk Level:** **MEDIUM**.
+#### SEC-06: Insecure CLI Password Arguments & Credential Leakage — `MEDIUM`
+- **Description:** Passing database passwords directly via command-line arguments (such as `-p"${DB_PASSWORD}"` or `-p"secret"`) is a severe operational hazard.
+  - **Process Table Exposure:** Any local user or monitoring process executing `ps aux` or `ps -ef` can inspect the full command line and view the plaintext database password.
+  - **Procfs Leakage:** The password is recorded in `/proc/<pid>/cmdline`.
+  - **Shell History Leakage:** Commands executed interactively are logged in `.bash_history`.
+- **Remediation Standard:** Passing passwords via command-line flags is strictly forbidden. Credentials must be supplied via a temporary secured client option file (`--defaults-extra-file=/path/to/client.cnf` with file mode `0600`) mounted or copied into the container runtime.
+
+#### SEC-07: Mutable & Unverified Container Image Tags — `MEDIUM`
+- **Description:** Referencing rolling tags such as `:latest` or unverified minor tags introduces supply-chain vulnerabilities, unreproducible builds, and silent breaking changes upon container recreation.
+- **Remediation Standard:** All production images must be pinned to explicit, verified version tags. Where absolute immutability is required, **Image Digest Pinning** (`image@sha256:<hash>`) must be implemented to protect against upstream registry tag overwrites.
 
 ---
 
-## 4. Portainer Hardening & Founder-Approved Access Strategy
+## 4. Portainer Hardening & Access Strategy Proposed for Founder Approval
 
-To remediate **SEC-01** and **SEC-02**, Portainer access must be immediately decoupled from the public internet. Three strategies are presented for founder sign-off:
+To remediate **SEC-01** and **SEC-02**, Portainer access must be immediately decoupled from the public internet. Two strategies are presented for Founder sign-off:
 
 ```mermaid
 graph LR
@@ -131,35 +147,30 @@ graph LR
         Admin2["Founder Static IP"] -->|Direct HTTPS| Portainer2["Portainer (Port 9443)"]
         Firewall2["OCI Firewall: 9443 PERMITTED ONLY for Founder IP/32"]
     end
-
-    subgraph Option3["Option 3: Cloudflare Zero Trust / Access Gateway"]
-        Admin3["Founder Browser"] -->|SSO / MFA Gate| CF["Cloudflare Zero Trust"]
-        CF -->|Origin Tunnel / Proxy| Portainer3["Portainer (Internal)"]
-        Firewall3["OCI Firewall: 9443 CLOSED to Public"]
-    end
 ```
 
 ### Strategy Comparison & Recommendations
 
-| Parameter | Option 1: SSH Local Port Forwarding (Recommended) | Option 2: OCI IP Allowlisting | Option 3: Cloudflare Zero Trust Tunnel |
-| :--- | :--- | :--- | :--- |
-| **Security Posture** | **Maximum (Zero Public Ingress)** | High (Perimeter filtered) | Very High (Identity-aware) |
-| **OCI Security List Rule** | **DELETE rule for port 9443** | Restrict 9443 to `Founder_IP/32` | **DELETE rule for port 9443** |
-| **Client Requirement** | Standard SSH Client (`ssh -L`) | Static IP required | Cloudflare Zero Trust Client / WARP |
-| **Implementation Complexity**| **Immediate (0 cost, 5 minutes)** | Immediate (requires static IP) | Moderate (Cloudflare setup) |
-| **Operational Impact** | Portainer accessed via `https://localhost:9443` | Portainer accessed via `https://<VM_IP>:9443` | Portainer accessed via `https://admin.lohnour.com` |
+| Parameter | Option 1: SSH Local Port Forwarding (Recommended) | Option 2: OCI IP Allowlisting |
+| :--- | :--- | :--- |
+| **Security Posture** | **Maximum (Zero Public Ingress)** | High (Perimeter filtered) |
+| **OCI Security List Rule** | **DELETE rule for port 9443** | Restrict 9443 to `Founder_IP/32` |
+| **Client Requirement** | Standard SSH Client (`ssh -L`) | Static IP required |
+| **Implementation Complexity**| **Immediate (0 cost, 5 minutes)** | Immediate (requires static IP) |
+| **Operational Impact** | Portainer accessed via `https://localhost:9443` | Portainer accessed via `https://<VM_IP>:9443` |
 
 ### Recommended Action Plan (Option 1):
-1. **Revoke Inbound 9443 in OCI Console:** Remove the ingress rule permitting `0.0.0.0/0` on TCP 9443.
+1. **Revoke Inbound 9443 in OCI Console (`PENDING FOUNDER APPROVAL`):** Remove the ingress rule permitting `0.0.0.0/0` on TCP 9443. Do not modify OCI rules prior to approval.
 2. **Establish Access via SSH Tunnel:**
    ```bash
-   ssh -i ~/.ssh/oracle_key.key -L 9443:localhost:9443 opc@<VM_IP>
+   ssh -i ~/.ssh/oracle_key.key -L 9443:localhost:9443 opc@141.148.51.166
    ```
+   *(Note: Target IP `141.148.51.166` is the previously verified baseline public IP; live reconfirmation in OCI Console is required prior to cutover. Current DNS does NOT point to this IP).*  
    Admin accesses Portainer securely in their local browser at `https://localhost:9443`.
 3. **Internal Portainer Configuration:**
    - Enforce an administrative passphrase of at least 24 random characters.
    - Set the administrative session timeout to 30 minutes.
-   - Disable the public publication of Edge Agent port 8000.
+   - Disable the publication of Edge Agent port 8000.
 
 ---
 
@@ -169,14 +180,14 @@ To ensure that the WordPress production stack cannot interfere with or compromis
 
 ```mermaid
 graph TB
-    subgraph Network_Proxy["Docker Network: proxy_network (172.20.0.0/16)"]
-        ReverseProxy["Reverse Proxy (Nginx Proxy Manager) - Ports 80 & 443"]
+    subgraph Network_Proxy["Docker Network: proxy_network (Bridge Ingress)"]
+        ReverseProxy["Proposed Reverse Proxy (Ports 80 & 443 - UNAPPROVED)"]
         WP_Container["WordPress Container (lohnour-wordpress)"]
     end
 
-    subgraph Network_DB["Docker Network: lohnour_db_network (172.21.0.0/16 - Internal)"]
+    subgraph Network_DB["Docker Network: lohnour_db_network (Internal Bridge - No Gateway)"]
         WP_Container
-        DB_Container["MariaDB 10.11 Container (lohnour-db)"]
+        DB_Container["MariaDB Container (lohnour-db)"]
     end
 
     subgraph Independent_Workloads["Existing Unmodified Workloads"]
@@ -193,20 +204,33 @@ graph TB
 ```
 
 ### Strict Isolation Boundaries:
-1. **Network Segregation:**
+1. **Network Segregation (`PROPOSED`):**
    - `lohnour_db_network`: Private Docker bridge network created with `internal: true`. No external gateway or public routing. MariaDB binds **only** to this internal network and exposes **zero** published ports on the host.
    - `proxy_network`: Standard bridge network connecting the Reverse Proxy and the WordPress application container.
    - **Zero Cross-Contamination:** Node-RED and Portainer are never connected to `lohnour_db_network` or `proxy_network`.
-2. **Filesystem & Volume Isolation:**
-   - WordPress data is strictly isolated in named volume `lohnour_wp_data`.
-   - MariaDB data is strictly isolated in named volume `lohnour_db_data`.
+2. **Filesystem & Volume Isolation (`PROPOSED`):**
+   - WordPress data is strictly isolated in proposed named volume `lohnour_wp_data`.
+   - MariaDB production data is strictly isolated in proposed named volume `lohnour_db_data` (mounted by production Compose service `db`; initialized under Gate 7).
+   - Staging database restore verification drills (Gate 6 — Stage 3) strictly utilize dedicated staging volume `lohnour_db_staging_data` to ensure zero mutation or collision with the production database volume `lohnour_db_data`.
    - Host volumes for existing workloads (`portainer_data`, `nodered_data`) are completely isolated.
-3. **Resource Starvation Prevention:**
-   - Hard resource limits (`deploy.resources.limits`) must be applied in the WordPress compose definition:
-     - MariaDB: Max 2 OCPU, Max 3.0 GiB RAM.
-     - WordPress: Max 2 OCPU, Max 3.0 GiB RAM.
-     - Reverse Proxy: Max 0.5 OCPU, Max 512 MiB RAM.
-   - These limits guarantee that WordPress traffic spikes cannot consume host CPU/RAM or starve Node-RED.
+3. **Resource Quotas & Allocation (`PROPOSED — NOT APPROVED — NOT DEPLOYED`):**
+   - Proposed CPU limits:
+     - WordPress: Max `1.00 OCPU`.
+     - MariaDB: Max `1.00 OCPU`.
+     - Reverse Proxy (only if selected): Max `0.25 OCPU`.
+     - Total Application CPU Limit: **2.25 OCPU** (leaves 1.75 OCPU nominal capacity unassigned).
+   - If no Reverse Proxy is selected: WordPress + MariaDB limits total **2.00 OCPU** (leaves 2.00 OCPU nominal capacity unassigned).
+   - *Mandatory Technical Resource Clarifications:*
+     - **CPU Limits:** Define maximum CPU capacity a container can consume under CFS; they are not guaranteed allocations, nor do they mean continuous consumption.
+     - **CPU Reservations:** Distinct from limits. No reservations are verified or approved; **CPU reservations remain unspecified**.
+     - **Actual Usage:** Actual CPU usage is not established by this documentation task; proposed limits are not measured usage, and unused capacity is not dedicated to existing services.
+     - **Existing Services & Contention:** Portainer and Node-RED are already active; their usage and limits are unestablished. Nominal unassigned capacity (1.75 OCPU, or 2.00 OCPU without proxy) is shared with existing services and host OS. This allocation does **not** guarantee headroom or prevent CPU contention.
+
+4. **Storage & Capacity Verification (`PENDING VERIFICATION`):**
+   - Host root filesystem was previously observed as 30 GB total (~16 GB available free space).
+   - Docker Root Dir is unconfirmed and available disk space must be measured via `df -h` before staging.
+   - Sizing must account for database growth, temporary archives, local backups, and rollback space.
+   - Storage feasibility remains **`PENDING VERIFICATION`**.
 
 ---
 
@@ -214,47 +238,48 @@ graph TB
 
 The production database must adhere to strict security parameters:
 
-1. **No Host Port Binding:**
+1. **No Host Port Binding (`PROPOSED`):**
    - Under no circumstances will port `3306` or `33060` be published to the host (`ports:` block omitted from the database service).
    - Database is accessible **exclusively** by the WordPress container via Docker internal DNS (`db:3306`).
-2. **Credential Generation:**
-   - Passwords must be cryptographically generated before deployment using `openssl rand -base64 32`.
-   - Two distinct credentials must be established:
-     - `MYSQL_ROOT_PASSWORD`: Used solely for administrative schema setup.
-     - `MYSQL_PASSWORD`: Dedicated, unprivileged user `lohnour_user` restricted to database `lohnour_wp`.
-3. **Database Privileges:**
-   - `lohnour_user` must hold privileges **only** on `lohnour_wp.*`. Global administrative privileges (`SUPER`, `GRANT OPTION`, `FILE`) must be denied.
-4. **SQL Dump Sanitization:**
-   - The backup SQL dump (`production-lohnour_wp.sql`) must be scanned for embedded plaintext credentials or unauthorized administrative user accounts prior to restoration.
+2. **Secure Credential Injection & Delivery Architecture (`PROPOSED — NOT TESTED`):**
+   - Command-line arguments containing `-p<password>` are strictly prohibited.
+   - **Containerized Option File Pattern (`PROPOSED — NOT TESTED`):**
+     - The database client executing inside the container cannot directly read a host-only path.
+     - *Procedure:*
+       1. The operator creates a temporary client option file on the host at `/opt/lohnour/staging/client.cnf` with file permissions `0600`.
+       2. The file is copied into the database container's temporary storage via `docker cp /opt/lohnour/staging/client.cnf lohnour-db:/tmp/client.cnf`.
+       3. Permissions inside the container are set via `docker exec lohnour-db chmod 600 /tmp/client.cnf`.
+       4. The client executes commands referencing `--defaults-extra-file=/tmp/client.cnf`.
+       5. *Cleanup & Limitations:* Immediately following execution, the temporary file is unlinked inside the container (`docker exec lohnour-db rm -f /tmp/client.cnf`) and on the host (`rm -f /opt/lohnour/staging/client.cnf`). Schema verification must occur BEFORE cleanup, and cleanup must execute on both success and failure via shell traps.
+     > [!WARNING]
+     > **ERASURE LIMITATION & UNTESTED STATUS:** While `rm` unlinks the file from filesystem directory structures, it does **NOT** guarantee physical erasure from container writable layers, underlying copy-on-write (CoW) storage drivers (such as overlay2), host block storage, or Docker volume backups. This pattern is strictly `PROPOSED — NOT TESTED` and must be validated in an isolated staging test environment before production use.
+3. **Database User Privileges (`PROPOSED`):**
+   - Dedicated, unprivileged user `lohnour_user` restricted to database `lohnour_wp.*`. Global administrative privileges (`SUPER`, `GRANT OPTION`, `FILE`) must be denied.
 
 ---
 
-## 7. Web Application & SSL/TLS Hardening Specification
+## 7. Web Application & Reverse Proxy Hardening Specification
 
-The Reverse Proxy (Nginx Proxy Manager / Nginx) must enforce enterprise transport security:
+> [!NOTE]
+> **REVERSE PROXY STATUS: `PROPOSED — NOT APPROVED`**  
+> The specific reverse proxy software (Nginx Proxy Manager, Caddy, Traefik, Standalone Nginx, or Cloudflare Tunnel) is subject to Founder decision. Regardless of technology chosen, the following transport security parameters are mandatory.
 
-### 7.1 TLS/SSL Configuration
+### 7.1 TLS/SSL Configuration Standards
 - **Protocols:** Enforce **TLSv1.2** and **TLSv1.3** only. Explicitly disable SSLv3, TLSv1.0, and TLSv1.1.
-- **Cipher Suites:** Modern, high-security ciphers:
-  ```nginx
-  ssl_ciphers 'ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384';
-  ssl_prefer_server_ciphers off;
-  ```
 - **HSTS (HTTP Strict Transport Security):**
-  ```nginx
-  add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;
-  ```
+  - Standard HSTS enabled with a minimum duration of 1 year (`max-age=31536000`).
+  - **Preload & Subdomain Restriction:** Do **NOT** mandate HSTS preload or `includeSubDomains` before subdomain readiness and domain stability are verified. Preloading is virtually irreversible and risks bricking access to unconfigured subdomains.
 - **HTTP to HTTPS Redirection:** Mandatory 301 permanent redirect on port 80.
 
 ### 7.2 HTTP Security Headers
-The following headers must be injected by the reverse proxy:
-```nginx
-add_header X-Content-Type-Options "nosniff" always;
-add_header X-Frame-Options "SAMEORIGIN" always;
-add_header X-XSS-Protection "1; mode=block" always;
-add_header Referrer-Policy "strict-origin-when-cross-origin" always;
-add_header Permissions-Policy "geolocation=(), microphone=(), camera=()" always;
+The following security headers must be injected at the ingress edge:
+```text
+X-Content-Type-Options: "nosniff"
+X-Frame-Options: "SAMEORIGIN"
+Referrer-Policy: "strict-origin-when-cross-origin"
+Permissions-Policy: "geolocation=(), microphone=(), camera=()"
 ```
+*(Note: Obsolete `X-XSS-Protection` is explicitly removed as modern browsers have deprecated or disabled it, and it introduces client-side security side-channels. Cross-site scripting defenses are enforced via proper input sanitization and Content-Security-Policy).*
 
 ### 7.3 Cloudflare Origin Security (If Cloudflare DNS Active)
 - **SSL/TLS Encryption Mode:** Set to **Full (Strict)** in Cloudflare dashboard to ensure end-to-end encryption.
@@ -266,19 +291,23 @@ add_header Permissions-Policy "geolocation=(), microphone=(), camera=()" always;
 
 Oracle Linux 9.7 incorporates security mechanisms that must be harmonized with Docker:
 
-1. **Firewalld Hardening:**
+1. **Firewalld Hardening (`UNKNOWN — REQUIRES STAGE 0 VERIFICATION`):**
+   - *Governance Requirement:* Any host firewall modification is strictly approval-gated (Gate 2).
    - Check if `firewalld` is active: `systemctl is-active firewalld`.
-   - If active, ensure public zone explicitly permits HTTP and HTTPS:
+   - If active and approved, ensure public zone explicitly permits HTTP and HTTPS:
      ```bash
      sudo firewall-cmd --permanent --zone=public --add-service=http
      sudo firewall-cmd --permanent --zone=public --add-service=https
      sudo firewall-cmd --reload
      ```
    - Verify that port 9443 and port 1880 are **NOT** permitted in firewalld public zone.
-2. **SELinux Policy Alignment:**
+2. **SELinux Policy Alignment (`UNKNOWN — REQUIRES LIVE VERIFICATION`):**
    - Check SELinux status: `getenforce`.
-   - If `Enforcing`, ensure any bind-mounted directories or volumes on the host carry the `:z` flag in Docker Compose to grant the container access without disabling SELinux globally.
-3. **Host Access via SSH:**
-   - Password authentication disabled in `/etc/ssh/sshd_config` (`PasswordAuthentication no`).
-   - Root login disabled (`PermitRootLogin no` or `prohibit-password`).
-   - Access restricted to authorized SSH public keys.
+   - **Nuanced `:z` Application:** Do **NOT** apply `:z` blindly to every mount.
+     - Relabeling with `:z` (shared SELinux label `svirt_sandbox_file_t`) or `:Z` (private) must ONLY be applied to specific application host bind mounts (such as staged `uploads.ini:ro,z`) if SELinux is in `Enforcing` mode.
+     - Do **NOT** apply `:z` to system directories (`/etc`, `/var`, `/run`, `/var/run/docker.sock`). Relabeling host system directories corrupts host SELinux policy and introduces host security risks.
+     - Named Docker volumes (`lohnour_wp_data`, `lohnour_db_data`, `lohnour_db_staging_data`, `portainer_data`, `nodered_data`) are managed natively by Docker within its configured Docker Root Dir (actual mountpoints must be confirmed via `docker volume inspect <name>` and must not be assumed to reside at `/var/lib/docker/volumes/...`). Named volumes do NOT require `:z` relabeling.
+3. **Host Access via SSH (`PENDING LIVE VERIFICATION`):**
+   - Whether password authentication is disabled in `/etc/ssh/sshd_config` (`PasswordAuthentication no`) is **`PENDING LIVE VERIFICATION`**.
+   - Whether root login is disabled (`PermitRootLogin no` or `prohibit-password`) is **`PENDING LIVE VERIFICATION`**.
+   - SSH access is reported as restricted to authorized public keys, but the live server configuration has not been directly inspected.
`

---

## File Diff: DEPLOYMENT_ARCHITECTURE.md

- **Baseline:** D:\lohnourpro\current source\lohnourpro_wp_theme\my-docs\DEPLOYMENT_ARCHITECTURE.md
- **Canonical:** D:\lohnourpro\docs\DEPLOYMENT_ARCHITECTURE.md

`diff
diff --git "a/D:\\lohnourpro\\current source\\lohnourpro_wp_theme\\my-docs\\DEPLOYMENT_ARCHITECTURE.md" "b/D:\\lohnourpro\\docs\\DEPLOYMENT_ARCHITECTURE.md"
index d6b2f0d..5569463 100644
--- "a/D:\\lohnourpro\\current source\\lohnourpro_wp_theme\\my-docs\\DEPLOYMENT_ARCHITECTURE.md"	
+++ "b/D:\\lohnourpro\\docs\\DEPLOYMENT_ARCHITECTURE.md"
@@ -5,67 +5,71 @@
 **Auditor / Role:** Senior DevOps Engineer & Infrastructure Security Auditor  
 **Target Environment:** Sam's Oracle VM (Oracle Linux 9.7)  
 **Operating Mode:** STRICT READ-ONLY FORENSICS — ZERO DEPLOYMENT MUTATIONS  
-**Status:** **PROPOSED ARCHITECTURE APPROVED FOR STAGING DESIGN**
+**Canonical Documentation Path:** `D:\lohnourpro\docs\DEPLOYMENT_ARCHITECTURE.md`  
+*(Note: Duplicate copies existing in `current source/lohnourpro_wp_theme/my-docs/` are historical theme artifacts and remain untouched).*  
+**Status:** **PROPOSED ARCHITECTURE SPECIFICATION — AWAITING FOUNDER SELECTION & APPROVAL**
 
 ---
 
 ## 1. Executive Summary
 
-This document defines the production container architecture for deploying the **Lohnour WordPress** platform on **Sam's Oracle VM**. The design establishes complete containerization, robust network isolation, automated SSL/TLS termination, and predictable resource allocation while strictly preserving the integrity and availability of the existing **Portainer CE** and **Node-RED** workloads.
+This document specifies the target container deployment architecture for the **Lohnour WordPress** platform on **Sam's Oracle VM**. The architecture establishes complete application isolation, strict network segregation, predictable resource allocation, and a GitHub-centric deployment workflow, designed to preserve existing **Portainer CE** and **Node-RED** workloads without interruption.
 
-The architecture replaces the legacy single-host monolithic compose structure with a **decoupled, multi-stack topology**:
-1. **An Ingress Reverse Proxy Stack** managing public web traffic on ports 80 and 443 with automated Let's Encrypt certificates.
-2. **An Isolated Application Stack** running WordPress and MariaDB over a private internal bridge with zero public database exposure.
-3. **Preserved Independent Workloads** (Node-RED and Portainer) operating in their respective isolated runtimes.
+### Core Architectural Decisions:
+1. **GitHub as the Single Source of Truth:** The deployment configuration is maintained exclusively within the GitHub repository (`https://github.com/tanu268/Lohnour.git`) on the verified `developer` branch. Portainer deploys directly from this Git repository, completely eliminating the Portainer Web Editor.
+2. **Reverse Proxy Status (`PROPOSED — NOT APPROVED`):** An ingress reverse proxy is evaluated to manage traffic on ports 80 and 443, but remains unapproved pending Founder decision on the preferred proxy technology or routing layer (Gate 4).
+3. **Application & Database Isolation:** WordPress and MariaDB communicate across a private, internal bridge network with zero public database exposure.
+4. **Preserved Existing Workloads:** Portainer CE and Node-RED operate in their independent runtimes and are untouched.
+5. **Target Host Baseline & Public IP:** The deployment target is Sam's Oracle VM (`sams-oracle-vm`, private IP: `10.0.0.100`, SSH user: `opc`). Previously verified baseline public IPv4 is **`141.148.51.166`** (`PREVIOUSLY VERIFIED`). This address is a historical observation, not a guarantee; live confirmation in OCI is required immediately before any DNS cutover. Current DNS does NOT point to this IP.
 
 ---
 
-## 2. Target Architecture Overview
+## 2. Target Architecture Topology
 
 ```mermaid
 graph TB
-    subgraph Internet_Traffic["Public Internet / DNS Ingress"]
+    subgraph Ingress_Layer["Internet Ingress Layer (Ports 80 & 443)"]
         ClientHTTPS["HTTPS Web Clients (Port 443)"]
         ClientHTTP["HTTP Web Clients (Port 80)"]
         AdminSSH["Founder Admin (SSH Tunnel)"]
     end
 
-    subgraph OCI_Firewall["OCI Security List & Host Firewall"]
-        FW80["TCP 80 -> ALLOW"]
-        FW443["TCP 443 -> ALLOW"]
-        FW22["TCP 22 -> ALLOW (Key Auth Only)"]
-        FW9443["TCP 9443 -> RESTRICTED (VPN/Tunnel Only)"]
+    subgraph OCI_Firewall["OCI Security List & Host Firewall (REPORTED — NOT INDEPENDENTLY VERIFIED)"]
+        FW80["TCP 80 -> Reported Permitted (Host Idle / No Service Active)"]
+        FW443["TCP 443 -> Reported Permitted (Host Idle / No Service Active)"]
+        FW22["TCP 22 -> Reported Permitted (Key Auth Only - Reconfirm Live)"]
+        FW9443["TCP 9443 -> Reported Permitted (Pending Revocation via Gate 2)"]
     end
 
-    ClientHTTP --> FW80
-    ClientHTTPS --> FW443
+    ClientHTTP -.->|Pending Proxy Selection (Gate 4)| FW80
+    ClientHTTPS -.->|Pending Proxy Selection (Gate 4)| FW443
     AdminSSH --> FW22
 
-    subgraph Host_VM["Sam's Oracle VM (4 OCPU / 22 GiB RAM / Oracle Linux 9.7)"]
+    subgraph Host_VM["Sam's Oracle VM (4 OCPU / ~22 GiB Usable RAM / Oracle Linux 9.7 | Baseline IP: 141.148.51.166)"]
         
-        subgraph Stack_Ingress["Stack 1: Ingress Reverse Proxy (npm_stack)"]
-            ProxyContainer["Reverse Proxy / NPM<br>(jc21/nginx-proxy-manager:latest)<br>Ports: 80, 443"]
-            VolNPMData[("volume: npm_data")]
-            VolNPMLetsEncrypt[("volume: npm_letsencrypt")]
-            ProxyContainer --- VolNPMData
-            ProxyContainer --- VolNPMLetsEncrypt
+        subgraph Stack_Ingress["Stack 1: Ingress Reverse Proxy (PROPOSED — NOT APPROVED)"]
+            ProxyContainer["Proposed Reverse Proxy Container<br>(NPM / Caddy / Traefik / Nginx)<br>Ports: 80, 443 | Limit: 0.25 OCPU"]
+            VolProxyData[("volume: proxy_data (Proposed)")]
+            VolProxyCerts[("volume: proxy_certs (Proposed)")]
+            ProxyContainer --- VolProxyData
+            ProxyContainer --- VolProxyCerts
         end
 
-        subgraph Stack_App["Stack 2: Lohnour Production (lohnour_stack)"]
-            WPContainer["WordPress Core + Theme<br>(wordpress:latest)<br>Apache + PHP 8.3"]
-            DBContainer["MariaDB Database<br>(mariadb:10.11 LTS)<br>Internal Port: 3306"]
+        subgraph Stack_App["Stack 2: Lohnour Production Stack (PROPOSED)"]
+            WPContainer["WordPress Container<br>(WORDPRESS IMAGE SELECTION: BLOCKED)<br>Limit: 1.00 OCPU / 3.0 GiB RAM"]
+            DBContainer["MariaDB Container<br>(WORDPRESS IMAGE SELECTION: BLOCKED)<br>Internal Port: 3306 | Limit: 1.00 OCPU / 3.0 GiB RAM"]
             VolWPData[("volume: lohnour_wp_data<br>/var/www/html")]
             VolDBData[("volume: lohnour_db_data<br>/var/lib/mysql")]
-            BindPHP["uploads.ini (Bind Mount)"]
+            BindPHP["uploads.ini (Bind Mount: ro,z)"]
             
             WPContainer --- VolWPData
             WPContainer --- BindPHP
             DBContainer --- VolDBData
         end
 
-        subgraph Stack_Existing["Preserved Existing Workloads"]
-            PortainerContainer["Portainer CE<br>(portainer/portainer-ce:lts)<br>Local Port: 9443"]
-            NodeREDContainer["Node-RED<br>(nodered/node-red:latest)<br>Port: 1880"]
+        subgraph Stack_Existing["Preserved Existing Workloads (PREVIOUSLY VERIFIED — RECONFIRM LIVE)"]
+            PortainerContainer["Portainer CE (PREVIOUSLY VERIFIED — RECONFIRM LIVE)<br>(portainer/portainer-ce:lts)<br>Port: 9443"]
+            NodeREDContainer["Node-RED (PREVIOUSLY VERIFIED — RECONFIRM LIVE)<br>(nodered/node-red:latest)<br>Port: 1880"]
             VolPortainer[("volume: portainer_data")]
             VolNodeRED[("volume: nodered_data")]
             DockerSock[("/var/run/docker.sock")]
@@ -75,9 +79,9 @@ graph TB
             NodeREDContainer --- VolNodeRED
         end
 
-        subgraph Docker_Networks["Docker Virtual Network Topology"]
-            NetProxy["proxy_network (Bridge, 172.20.0.0/16)"]
-            NetDB["lohnour_db_network (Internal Bridge, 172.21.0.0/16)"]
+        subgraph Docker_Networks["Docker Virtual Networks (PROPOSED)"]
+            NetProxy["proxy_network (Bridge Ingress, Proposed)"]
+            NetDB["lohnour_db_network (Internal Bridge, No Gateway, Proposed)"]
         end
 
         FW80 --> ProxyContainer
@@ -90,13 +94,11 @@ graph TB
         NetDB <--> DBContainer
     end
 
-    classDef external fill:#f9f9f9,stroke:#333,stroke-width:2px;
     classDef security fill:#ffebee,stroke:#c62828,stroke-width:2px;
-    classDef ingress fill:#e8f5e9,stroke:#2e7d32,stroke-width:2px;
+    classDef ingress fill:#fff3e0,stroke:#e65100,stroke-width:2px;
     classDef app fill:#e3f2fd,stroke:#1565c0,stroke-width:2px;
-    classDef existing fill:#fff3e0,stroke:#e65100,stroke-width:2px;
+    classDef existing fill:#e8f5e9,stroke:#2e7d32,stroke-width:2px;
     
-    class ClientHTTPS,ClientHTTP,AdminSSH external;
     class FW80,FW443,FW22,FW9443 security;
     class ProxyContainer ingress;
     class WPContainer,DBContainer app;
@@ -107,65 +109,86 @@ graph TB
 
 ## 3. Container Stack Specifications
 
-### 3.1 Stack 1: Ingress Reverse Proxy (`npm-stack`)
-- **Image:** `jc21/nginx-proxy-manager:latest` (or official Nginx with Let's Encrypt).
-- **Restart Policy:** `unless-stopped`
-- **Published Ports:**
-  - `0.0.0.0:80 -> 80/tcp` (HTTP traffic & ACME challenge validation)
-  - `0.0.0.0:443 -> 443/tcp` (HTTPS traffic)
-  - Admin Web UI port `81` is **NOT published publicly** (accessible only via local proxy or internal IP).
-- **Volumes:**
-  - `npm_data:/data`: Nginx Proxy Manager database and configuration files.
-  - `npm_letsencrypt:/etc/letsencrypt`: TLS certificates and keys.
-- **Networks:**
-  - Attached to `proxy_network`.
-- **Purpose:** Centralized edge routing, SSL termination, and HTTP security header injection.
-
-### 3.2 Stack 2: Lohnour Production Application (`lohnour-production`)
-The application stack is defined across two tightly coupled yet network-isolated services:
+### 3.1 Ingress Reverse Proxy (`PROPOSED — NOT APPROVED`)
+- **Status:** **PROPOSED — NOT APPROVED (UNRESOLVED ARCHITECTURAL DECISION)**.
+- **Why a Reverse Proxy Is Evaluated:**
+  - Inbound HTTP (80) and HTTPS (443) traffic must terminate TLS certificates and forward requests to internal container ports.
+  - WordPress generates absolute URLs and requires proxy awareness headers (`X-Forwarded-Proto`, `X-Forwarded-For`).
+  - Potential multi-service routing: provides an extensible mechanism to route additional services (or Node-RED) securely behind domain paths without exposing raw ports.
+- **Evaluation of Reverse Proxy Alternatives:**
+
+| Solution | Key Strengths | Potential Trade-Offs | Evaluation Status |
+| :--- | :--- | :--- | :---: |
+| **Nginx Proxy Manager (NPM)** | Intuitive Web UI, built-in SQLite DB, easy Let's Encrypt renewal. | Extra web UI attack surface, consumes ~250–350 MiB RAM, adds SQLite overhead. | `PROPOSED — NOT APPROVED` |
+| **Caddy** | Memory-safe (Go), automated HTTPS out of the box, zero DB, minimalist Caddyfile. | No graphical UI (configured via file/API). | `PROPOSED — ALTERNATIVE` |
+| **Traefik** | Cloud-native, automatic Docker label discovery, dynamic routing. | Steeper configuration learning curve, YAML label complexity. | `PROPOSED — ALTERNATIVE` |
+| **Standalone Nginx + Certbot** | Zero UI attack surface, rock-solid stability, minimal memory footprint (~30 MiB). | Manual Certbot cron configuration required. | `PROPOSED — ALTERNATIVE` |
+| **Cloudflare Tunnel (`cloudflared`)** | Zero inbound open ports required (eliminates ports 80/443), outbound tunnel to Cloudflare Edge. | Requires Cloudflare as authoritative DNS and CDN provider. | `PROPOSED — ALTERNATIVE` |
+
+- **Unresolved Decision:** The Founder must select and approve the preferred reverse proxy architecture before any ingress container or Compose resource is created (Gate 4).
+
+---
+
+### 3.2 Lohnour Production Application Stack (`PROPOSED`)
+
+#### Proposed Service Naming & DNS Resolution Architecture
+To prevent naming collisions and DNS resolution failures, the architecture explicitly distinguishes the four naming layers:
+1. **Compose Service Key:** The top-level key in `docker-compose.yml` (proposed: `db` for database, `wordpress` for application).
+2. **Container Name:** The host runtime container identifier (proposed: `container_name: lohnour-db` and `container_name: lohnour-wordpress`).
+3. **Network DNS Alias:** The internal Docker DNS hostname resolvable by adjacent containers on the same Docker bridge network. Docker Compose automatically registers the Compose service key (e.g., `db`) as a network alias on attached networks (`lohnour_db_network`).
+4. **WordPress Database Hostname:** The environment parameter passed to WordPress (`WORDPRESS_DB_HOST: db:3306`).
+
+> [!IMPORTANT]
+> **DNS ALIAS & SERVICE RESOLUTION MANDATE:**
+> The container name (`lohnour-db`) and Compose service key (`db`) are **NOT** automatically equivalent unless an explicit network alias is defined. The Compose service key or network alias MUST resolve to the hostname configured in `WORDPRESS_DB_HOST`. This naming convention is **`PROPOSED — PENDING CANONICAL COMPOSE SPECIFICATION`** and cannot be finalized until the canonical Compose file is reviewed and committed to GitHub.
 
 #### Service A: `lohnour-wordpress`
-- **Image:** `wordpress:latest` (Official Docker library image; Apache 2.4 + PHP 8.3).
+- **Compose Service Key (Proposed):** `wordpress`
+- **Container Name (Proposed):** `lohnour-wordpress`
+- **Image Compatibility Gate (Gate 3):** `WORDPRESS IMAGE SELECTION: BLOCKED`
+  - *Evidence Requirement:* Database revision `61833` alone is **NOT** sufficient evidence to select a WordPress image. Selection is blocked until the following verification requirements are satisfied:
+    - **A. WordPress Core Version:** Identify the exact WordPress core version from the existing source, backup, or authoritative artifact; do not infer from database revision alone.
+    - **B. PHP Compatibility:** Establish the PHP version required by the identified core version; check compatibility with `twentytwentyfive-child` and installed plugins; verify required PHP extensions; do not assume PHP version without evidence.
+    - **C. Plugin Compatibility:** Identify all installed plugins and versions; verify compatibility with selected core and PHP versions; identify abandoned, outdated, or incompatible plugins without silent modifications.
+    - **D. Database Compatibility:** Establish existing database engine and version; verify compatibility; record limitations of database revision `61833` (insufficient proof of core compatibility).
+    - **E. Image Reproducibility:** Record exact image tag and immutable digest (`image@sha256:...`); do not use `latest`; verify architecture compatibility (`linux/arm64` vs `linux/amd64`); require staging restore and functional validation.
+  - *Mutable Tags Banned:* Mutable tags (such as `:latest`) are prohibited for production use.
 - **Restart Policy:** `unless-stopped`
-- **Published Ports:** **NONE** (Traffic received exclusively via `proxy_network` from the reverse proxy).
+- **Published Host Ports:** **NONE** (Exposed exclusively via `proxy_network` to the reverse proxy).
 - **Environment Variables:**
-  - `WORDPRESS_DB_HOST: lohnour-db:3306`
+  - `WORDPRESS_DB_HOST: db:3306` (Resolves to database Compose service key `db` on `lohnour_db_network`)
   - `WORDPRESS_DB_NAME: lohnour_wp`
   - `WORDPRESS_DB_USER: lohnour_user`
   - `WORDPRESS_DB_PASSWORD: ${DB_PASSWORD}`
   - `WORDPRESS_TABLE_PREFIX: wp_`
   - `WORDPRESS_CONFIG_EXTRA`: Proxy HTTPS awareness (`HTTP_X_FORWARDED_PROTO`).
 - **Volumes & Mounts:**
-  - `lohnour_wp_data:/var/www/html`: Named persistent volume containing WordPress core, theme, plugins, and uploads.
-  - `./uploads.ini:/usr/local/etc/php/conf.d/uploads.ini:ro`: Bind mount for PHP runtime tuning.
-- **Networks:**
-  - `proxy_network` (for receiving forwarded HTTP traffic from proxy).
-  - `lohnour_db_network` (for internal SQL queries to the database).
-- **Dependencies:**
-  - Depends on `lohnour-db` with `condition: service_healthy`.
+  - `lohnour_wp_data:/var/www/html`: Named persistent volume.
+  - Staged `uploads.ini` bind-mounted to `/usr/local/etc/php/conf.d/uploads.ini:ro` (append `:z` only if host SELinux is in `Enforcing` mode).
+- **Networks:** Attached to `proxy_network` and `lohnour_db_network`.
+- **Dependencies:** Depends on Compose service `db` with `condition: service_healthy`.
 
 #### Service B: `lohnour-db`
-- **Image:** `mariadb:10.11` (Official Docker library MariaDB 10.11 LTS).
+- **Compose Service Key (Proposed):** `db`
+- **Container Name (Proposed):** `lohnour-db`
+- **Network DNS Alias (Proposed):** `db` (Accessible via `db:3306` on `lohnour_db_network`)
+- **Image Compatibility Gate (Gate 3):** `WORDPRESS IMAGE SELECTION: BLOCKED`
+  - *Evidence Requirement:* Must be pinned to a supported MariaDB release matching schema compatibility, with verified platform support and immutable SHA-256 digest (`mariadb@sha256:...`) following staging validation.
+  - *Mutable Tags Banned:* Mutable tags (`:latest` or floating minor tags) are prohibited for production use.
 - **Restart Policy:** `unless-stopped`
-- **Published Ports:** **NONE** (Zero host ports exposed; completely shielded from the internet and other host containers).
+- **Published Host Ports:** **NONE** (Zero host ports exposed; port 3306 is completely private).
 - **Environment Variables:**
   - `MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}`
   - `MYSQL_DATABASE: lohnour_wp`
   - `MYSQL_USER: lohnour_user`
   - `MYSQL_PASSWORD: ${DB_PASSWORD}`
-- **Volumes:**
-  - `lohnour_db_data:/var/lib/mysql`: Persistent volume for InnoDB storage engine data and system tables.
-- **Networks:**
-  - `lohnour_db_network` (Internal bridge network only).
-- **Healthcheck:**
-  - `test: ["CMD", "healthcheck.sh", "--connect", "--innodb_initialized"]`
-  - `interval: 10s`, `timeout: 5s`, `retries: 5`, `start_period: 30s`.
+- **Volumes:** `lohnour_db_data:/var/lib/mysql`.
+- **Networks:** Attached exclusively to `lohnour_db_network` (`internal: true`).
+- **Healthcheck:** `test: ["CMD", "healthcheck.sh", "--connect", "--innodb_initialized"]`.
 
 ---
 
-## 4. Virtual Network Architecture
-
-To guarantee isolation, communication between containers is controlled via two dedicated user-defined Docker bridge networks:
+## 4. Virtual Network Architecture (`PROPOSED`)
 
 | Network Name | Driver | Scope | Subnet (Suggested) | Attached Containers | Access Control Rules |
 | :--- | :--- | :--- | :--- | :--- | :--- |
@@ -184,79 +207,90 @@ To guarantee isolation, communication between containers is controlled via two d
 
 ## 5. Storage & Volume Architecture
 
-Persistent data is stored in named Docker volumes to ensure clean host encapsulation and high I/O throughput:
+Persistent data is stored in named Docker volumes to ensure clean host encapsulation:
 
-```mermaid
-graph LR
-    subgraph Host_Filesystem["Host Storage (/var/lib/docker/volumes/)"]
-        WP_Vol["/var/lib/docker/volumes/lohnour_wp_data/_data/"]
-        DB_Vol["/var/lib/docker/volumes/lohnour_db_data/_data/"]
-        NPM_Vol["/var/lib/docker/volumes/npm_data/_data/"]
-        Cert_Vol["/var/lib/docker/volumes/npm_letsencrypt/_data/"]
-        Portainer_Vol["/var/lib/docker/volumes/portainer_data/_data/"]
-        NodeRED_Vol["/var/lib/docker/volumes/nodered_data/_data/"]
-    end
+| Volume Name | Target Container Path | Content | Ownership Standard |
+| :--- | :--- | :--- | :--- |
+| `lohnour_wp_data` | `/var/www/html` | WordPress core, active child theme, plugins, media uploads | `www-data:www-data` (`UID 33:GID 33`), Dirs: `755`, Files: `644` |
+| `lohnour_db_data` | `/var/lib/mysql` | Production MariaDB InnoDB tablespace, system databases, binlogs | Managed exclusively by database engine (`UID 999:GID 999`) |
+| `lohnour_db_staging_data`| `/var/lib/mysql` | Isolated staging database validation volume (Gate 6 — Stage 3) | Managed exclusively by database engine in staging (`UID 999:GID 999`) |
+| `portainer_data` | `/data` (Existing) | Portainer configuration & database | Existing workload — **UNTOUCHED** |
+| `nodered_data` | `/data` (Existing) | Node-RED flows and credentials | Existing workload — **UNTOUCHED** |
 
-    subgraph Containers["Running Containers"]
-        WP_Container["lohnour-wordpress (/var/www/html)"]
-        DB_Container["lohnour-db (/var/lib/mysql)"]
-        Proxy_Container["Reverse Proxy (/data & /etc/letsencrypt)"]
-        Portainer_Container["Portainer CE (/data)"]
-        NodeRED_Container["Node-RED (/data)"]
-    end
-
-    WP_Vol === WP_Container
-    DB_Vol === DB_Container
-    NPM_Vol === Proxy_Container
-    Cert_Vol === Proxy_Container
-    Portainer_Vol === Portainer_Container
-    NodeRED_Vol === NodeRED_Container
-```
-
-### Filesystem Ownership & Permission Standards:
-- `lohnour_wp_data`: Must be owned by `www-data:www-data` (`UID 33`, `GID 33`).
-  - Directories: `0755`
-  - Files: `0644`
-  - `wp-config.php`: `0640`
-- `lohnour_db_data`: Managed exclusively by the MariaDB container (`mysql:mysql`, `UID 999`, `GID 999`). Must not be edited on the host filesystem directly.
-- SELinux contexts: Any bind mounts (`uploads.ini`) must specify `:ro,z` in SELinux enforcing environments.
+> [!IMPORTANT]
+> **VOLUME MOUNTPOINT, STORAGE FEASIBILITY & PERMISSION SAFETY:**
+> - **Staging vs Production Storage Isolation & Retention (BLOCKER 1 & C RESOLUTION):** Staging database restore validation drills (Gate 6 — Stage 3) strictly utilize dedicated staging volume `lohnour_db_staging_data`. The production volume `lohnour_db_data` must **NEVER** be mounted or referenced during staging restore tests. Routine deletion of `lohnour_db_staging_data` via `docker volume rm` is strictly removed from normal restore-drill procedures; the staging volume is retained as a **`retained staging reference`** following drill validation (do NOT describe as immutable; Docker named volumes possess no cryptographic write-locking or immutability mechanism). Any future decommissioning or deletion of `lohnour_db_staging_data` requires explicit authorization under **GATE 10** (`Volume Deletion & Emergency Reset Operations Approval`). Production database initialization into `lohnour_db_data` is a distinct, destructive operation requiring separate, explicit Founder authorization under **Gate 7**.
+> - Named Docker volumes are managed natively by Docker within its configured Docker Root Dir. The host mountpoint must **not** be assumed to reside at `/var/lib/docker/volumes/...`; it must be confirmed dynamically via `docker volume inspect <volume_name>`.
+> - Volumes do not exist before stack deployment and must not be pre-created blindly.
+> - **Storage Feasibility Status (`PENDING VERIFICATION` — BLOCKER 10 RESOLUTION):** The host root filesystem was previously observed as 30 GB total (~16 GB available free space). The actual Docker Root Dir has not yet been established in documentation evidence (requires verification on host via `docker info --format '{{.DockerRootDir}}'`). The specific filesystem backing Docker data must be verified via `df -h` to determine exact available space before any archive transfer or deployment. Actual storage requirements for WordPress core/theme/plugin files, database tablespace, container images, temporary staging archives, automated backups, and runtime logs remain unmeasured. Memory and CPU limits are Docker cgroup resource controls, not guarantees against Out-Of-Memory (OOM) events or storage exhaustion. Sizing feasibility cannot be approved without empirical host measurement.
+> - **Filesystem Permission Safety:** Any future ownership (`33:33`) or permission adjustments must identify the exact verified target path, record original ownership and permissions, be narrowly scoped to staged content only, be tested on staging first, and require explicit operational approval. Blind recursive `chown -R` or `chmod -R` commands are strictly forbidden.
 
 ---
 
-## 6. Resource Limits & Quota Allocation
-
-To eliminate resource contention and protect Node-RED and Portainer:
-
-```yaml
-# Resource Allocation Matrix
-deploy:
-  resources:
-    limits:
-      cpus: '2.00'
-      memory: 3072M
-    reservations:
-      cpus: '0.50'
-      memory: 512M
-```
-
-| Service | CPU Reservation | CPU Limit | RAM Reservation | RAM Limit | Failure Policy |
-| :--- | :--- | :--- | :--- | :--- | :--- |
-| `lohnour-db` | 0.5 OCPU | 2.0 OCPU | 1024 MiB | 3072 MiB | Restart on failure |
-| `lohnour-wordpress` | 0.5 OCPU | 2.0 OCPU | 512 MiB | 3072 MiB | Restart on failure |
-| `npm-reverse-proxy` | 0.1 OCPU | 0.5 OCPU | 128 MiB | 512 MiB | Restart on failure |
-| **Total Stack Maximum**| **1.1 OCPU** | **4.0 OCPU** | **1.66 GiB** | **6.65 GiB** | - |
-| **System Headroom** | **> 2.9 OCPU** | - | **> 15.3 GiB** | - | Guaranteed for host & Node-RED |
+## 6. Resource Limits & Quota Allocation (`PROPOSED`)
+
+### 6.1 Proposed CPU Limit Allocation Matrix (`PROPOSED — NOT APPROVED — NOT DEPLOYED`)
+
+| Service | Proposed CPU limit |
+|---|---:|
+| WordPress | 1.00 OCPU |
+| MariaDB | 1.00 OCPU |
+| Reverse proxy (only if selected) | 0.25 OCPU |
+| **Total application CPU limit** | **2.25 OCPU** |
+| **Nominal capacity not assigned to these application limits** | **1.75 OCPU** |
+
+*Contingency (if no reverse proxy is selected):*
+
+| Metric | Value |
+|---|---:|
+| WordPress limit | 1.00 OCPU |
+| MariaDB limit | 1.00 OCPU |
+| Total application CPU limit | 2.00 OCPU |
+| Nominal capacity not assigned to these application limits | 2.00 OCPU |
+
+> [!IMPORTANT]
+> **MANDATORY TECHNICAL CPU RESOURCE CLARIFICATIONS:**
+> 1. **CPU Limits:** Define the maximum CPU capacity a container can consume under configured Docker CPU controls (Linux CFS scheduler). They are **not** guaranteed CPU allocations, nor do they imply continuous consumption.
+> 2. **CPU Reservations:** Distinct from limits. No CPU reservations have been verified or approved; **CPU reservations remain unspecified**.
+> 3. **Actual CPU Usage:** Actual runtime CPU consumption has **not** been established by this documentation-only task. Proposed limits must not be presented as measured usage, and unused CPU capacity must not be claimed as dedicated to Portainer or Node-RED.
+> 4. **Existing Services & Contention:** Portainer and Node-RED are already active on the VM. Their actual CPU consumption and configured limits have not been measured by this task. The nominal unassigned capacity (1.75 OCPU, or 2.00 OCPU without proxy) is shared dynamically with existing services and the host operating system. This allocation does **not** guarantee headroom or prevent CPU contention.
+> 5. **Status:** This allocation is strictly **`PROPOSED — NOT APPROVED — NOT DEPLOYED`**.
+
+### 6.2 Proposed Memory Allocation Matrix
+
+| Service | Memory Reservation | Memory Limit | Operational Role |
+| :--- | :---: | :---: | :--- |
+| `lohnour-db` | 1,024 MiB | 3,072 MiB (~3.0 GiB) | MariaDB InnoDB buffer pool and query cache |
+| `lohnour-wordpress` | 512 MiB | 3,072 MiB (~3.0 GiB) | WordPress Apache MPM and PHP workers |
+| Proposed Reverse Proxy | 128 MiB | 512 MiB (~0.5 GiB) | Ingress routing buffer and SSL cache |
+| **Total Stack Maximum** | **1,664 MiB** | **6,656 MiB (~6.5 GiB)**| Stack memory ceiling |
+| **Host Usable Capacity**| **22 GiB** | **~15.3 GiB nominal unallocated**| Nominal unallocated memory (`ESTIMATED`). Does not guarantee that OOM events cannot occur under heavy load. |
 
 ---
 
-## 7. Analysis: Why the Old Docker Compose File Cannot Be Reused Unchanged
-
-The old production compose file (`D:\lohnourpro\_audit\production-extract\config\docker-compose.yml`) cannot be deployed as-is on Sam's Oracle VM due to five structural and architectural issues:
-
-1. **Monolithic Ingress Bundling:** The old compose bundles `npm` directly into the WordPress project. If the WordPress stack is restarted, updated, or redeployed, the reverse proxy drops all connections, disrupting port 80/443 for other services.
-2. **Missing Database Isolation:** The old compose connected the database to a generic bridge network without `internal: true`, lacking explicit network barriers.
-3. **No Resource Boundaries:** The old compose contained zero `limits` or `reservations`, meaning a traffic burst or database runaway query could exhaust all 22 GiB of RAM and crash Node-RED or Portainer.
-4. **Hardcoded Secrets:** The old compose referenced environment variables without fallback definitions or security mode specifications.
-5. **Portainer Stack Compatibility:** When deploying via Portainer Stacks, network and volume scoping must be explicitly declared as `external: true` or named to avoid Portainer prefixing them with the stack name (e.g., `lohnour_lohnour_network`).
-
-The proposed modular compose definition resolves all five issues.
+## 7. GitHub as Source of Truth & Portainer Git Integration
+
+### 7.1 Architecture Principle: Zero Web Editor Deployments
+The Portainer Web Editor (`Stacks -> Add Stack -> Web editor`) is **NOT an approved deployment method**. Storing Compose definitions directly in Portainer creates unversioned configuration drift, bypasses peer code review, and violates audit requirements.
+
+### 7.2 GitHub Repository Integration
+All deployment specifications must reside in Git:
+- **Authoritative Repository:** `https://github.com/tanu268/Lohnour.git`
+- **Approved Deployment Branch:** `developer` (`VERIFIED: Branch exists locally and on remote origin`).
+- **Compose File Location (Gate 5):** `COMPOSE FILE LOCATION — NOT YET DECIDED`
+  - *Inspection Finding:* The local repository currently contains no deployment Compose file in the root directory or in a `deploy/` directory.
+  - *Historical Archive Classification:* The file located at `_audit/production-extract/config/docker-compose.yml` is classified as:  
+    **`HISTORICAL ARCHIVE — REFERENCE ONLY — NOT APPROVED FOR PRODUCTION`**.  
+    - Its existence does not establish production readiness.
+    - It must **NOT** be copied into the active deployment directory.
+    - It must **NOT** be used as the production stack configuration.
+    - It must **NOT** be modified or repurposed as part of this task.
+    - Any future reuse requires a separate technical review, compatibility verification, security review, and explicit Founder approval.
+    - The canonical production Compose file remains undecided until a separately reviewed and approved configuration is established on the `developer` branch in GitHub.
+  - *Requirement:* A new production Compose specification must be authored, reviewed, committed, and pushed to the `developer` branch. The canonical Compose file path remains `COMPOSE FILE LOCATION — NOT YET DECIDED` until this action occurs.
+- **Portainer Build Method:** Portainer deploys via **Build method: Repository** (`Stacks -> Add Stack -> Repository`), pulling the Compose file directly from the `developer` branch.
+- **Update Workflow:**
+  1. Engineers commit Compose changes to `developer` locally and push to GitHub.
+  2. Portainer detects changes either via:
+     - Manual trigger: "Pull and redeploy" in the Portainer stack interface.
+     - Automated trigger: Polling or Webhook (`PROPOSED — NOT AUTOMATICALLY CONFIGURED`).
`

---

## File Diff: DEPLOYMENT_PLAN.md

- **Baseline:** D:\lohnourpro\current source\lohnourpro_wp_theme\my-docs\DEPLOYMENT_PLAN.md
- **Canonical:** D:\lohnourpro\docs\DEPLOYMENT_PLAN.md

`diff
diff --git "a/D:\\lohnourpro\\current source\\lohnourpro_wp_theme\\my-docs\\DEPLOYMENT_PLAN.md" "b/D:\\lohnourpro\\docs\\DEPLOYMENT_PLAN.md"
index d3e4815..4729c1d 100644
--- "a/D:\\lohnourpro\\current source\\lohnourpro_wp_theme\\my-docs\\DEPLOYMENT_PLAN.md"	
+++ "b/D:\\lohnourpro\\docs\\DEPLOYMENT_PLAN.md"
@@ -5,7 +5,9 @@
 **Auditor / Role:** Senior DevOps Engineer & Infrastructure Security Auditor  
 **Target Environment:** Sam's Oracle VM (Oracle Linux 9.7)  
 **Operating Mode:** STRICT READ-ONLY FORENSICS — ZERO DEPLOYMENT COMMANDS EXECUTED  
-**Status:** **PLAN DEFINED — AWAITING FOUNDER SIGN-OFF**
+**Canonical Documentation Path:** `D:\lohnourpro\docs\DEPLOYMENT_PLAN.md`  
+*(Note: Duplicate copies existing in `current source/lohnourpro_wp_theme/my-docs/` are historical theme artifacts and remain untouched).*  
+**Status:** **PLAN DEFINED — AWAITING FOUNDER SIGN-OFF & PREREQUISITE DECISIONS**
 
 ---
 
@@ -17,89 +19,140 @@ This Deployment Plan defines the exact, sequential execution steps required to d
 > **STRICT OPERATING RULES ENFORCED:**
 > 1. No deployment steps are executed during the audit phase.
 > 2. Execution of this plan is strictly prohibited without explicit Founder approval.
-> 3. Do not modify OCI Security Lists or host firewalls until Stage 1 sign-off is granted.
+> 3. Do not modify OCI Security Lists, networking, or host firewalls until Stage 1 sign-off is granted.
 > 4. Existing workloads (`Portainer CE` and `Node-RED`) must remain uninterrupted throughout all stages.
+> 5. Portainer Web Editor must **NOT** be used; deployment must execute via Portainer's Git Repository integration.
+> 6. WordPress must **NEVER** be started against an empty database; database restore and data staging must precede WordPress container launch.
 
 ---
 
 ## 2. Pre-Requisites & Verification Gates
 
-Before initiating Stage 0, the following verification gates must be cleared:
+Deployment execution is governed by the Canonical 10-Gate Approval Register. No stage may commence until its governing gate criteria are satisfied and documented.
 
 ```mermaid
 graph TD
-    Gate1["Gate 1: Founder Approvals Granted<br>(Access strategy, domain, secrets)"]
-    Gate2["Gate 2: Architecture Confirmed<br>(uname -m: ARM64 vs x86_64)"]
-    Gate3["Gate 3: Backup Checksums Validated<br>(Local SHA-256 match 100%)"]
-    Gate4["Gate 4: DNS A-Record Aligned<br>(Points to Sam's VM Public IP)"]
-    
+    Gate1["GATE 1: Host Baseline & Capacity Verification<br>(Read-only host checks)"]
+    Gate2["GATE 2: Management-Plane & Perimeter Security Approval<br>(OCI 9443 revocation, SSH tunneling, host firewalld)"]
+    Gate3["GATE 3: WordPress & Database Image Compatibility Verification<br>(WORDPRESS IMAGE SELECTION: BLOCKED)"]
+    Gate4["GATE 4: Ingress Reverse Proxy Selection & Approval<br>(NPM vs Caddy vs Traefik vs Standalone Nginx vs Cloudflare Tunnel)"]
+    Gate5["GATE 5: Canonical Compose Path & Git Repository Verification<br>(Branch: developer | COMPOSE FILE LOCATION — NOT YET DECIDED)"]
+    Gate6["GATE 6: Backup Integrity & Restore Readiness Verification<br>(100% SHA256 match on backup artifacts)"]
+    Gate7["GATE 7: Database Restoration & Secrets Injection Approval<br>(DESTRUCTIVE: Separate Founder approval required)"]
+    Gate8["GATE 8: DNS Modification & Routing Changes Approval<br>(A-Record pointing pro.lohnour.com)"]
+    Gate9["GATE 9: Production Cutover & Maintenance Window Approval<br>(Final cutover authorization)"]
+    Gate10["GATE 10: Volume Deletion & Emergency Reset Operations Approval<br>(EMERGENCY ONLY: Forbidden during standard rollback)"]
+
     Gate1 --> Gate2
-    Gate2 --> Gate3
+    Gate2 --> Gate5
+    Gate5 --> Gate3
     Gate3 --> Gate4
-    Gate4 --> Ready["READY FOR STAGE 0 EXECUTION"]
+    Gate4 --> Gate6
+    Gate6 --> Gate7
+    Gate7 --> Gate8
+    Gate8 --> Gate9
 ```
 
-1. **Founder Sign-Off:** Written approval on the Portainer perimeter hardening strategy (SSH port forward vs IP allowlist) and production domain name.
-2. **Local Backup Integrity:** Cryptographic hashes in `D:\lohnourpro\_audit\production-extract\manifest-all.sha256` verified locally (Verified 100% on 2026-11-06).
-3. **SSH Access Credentials:** Verified private key access to Sam's Oracle VM with passwordless `sudo` elevation.
-4. **DNS Readiness:** Production domain (e.g., `pro.lohnour.com`) configured with an A record pointing to Sam's Oracle VM public IP (TTL set to 300 seconds for rapid propagation).
+### Pre-Requisite Gates Summary
+
+1. **GATE 1: Host Baseline & Capacity Verification (`PENDING LIVE VERIFICATION`):**
+   - Host architecture verification (`aarch64` vs `x86_64`), usable memory (22 GiB), and CPU resource budgeting (`1.00 / 1.00 / 0.25 OCPU` labeled `PROPOSED — NOT APPROVED — NOT DEPLOYED`).
+2. **GATE 2: Management-Plane & Perimeter Security Approval (`PENDING FOUNDER APPROVAL`):**
+   - Revocation of public port 9443 in OCI Security List; transition to SSH tunnel access (`ssh -L 9443:localhost:9443`); host firewalld hardening.
+3. **GATE 3: WordPress & Database Image Compatibility Verification (`WORDPRESS IMAGE SELECTION: BLOCKED`):**
+   - Database revision `61833` alone is insufficient evidence to select an image. Selection is strictly blocked until the following verification requirements are satisfied:
+     - **A. WordPress Core Version:** Identify the exact WordPress core version from the existing source, backup, or authoritative artifact; do not infer from database revision alone.
+     - **B. PHP Compatibility:** Establish the PHP version required by the identified core version; check compatibility with `twentytwentyfive-child` and installed plugins; verify required PHP extensions; do not assume PHP version without evidence.
+     - **C. Plugin Compatibility:** Identify all installed plugins and versions; verify compatibility with selected core and PHP versions; identify abandoned, outdated, or incompatible plugins without silent modifications.
+     - **D. Database Compatibility:** Establish existing database engine and version; verify compatibility; record limitations of database revision `61833` (insufficient proof of core compatibility).
+     - **E. Image Reproducibility:** Record exact image tag and immutable digest (`image@sha256:...`); do not use `latest`; verify architecture compatibility (`linux/arm64` vs `linux/amd64`); require staging restore and functional validation.
+4. **GATE 4: Ingress Reverse Proxy Selection & Approval (`PROPOSED — NOT APPROVED`):**
+   - Architectural selection between Nginx Proxy Manager, Caddy, Traefik, Standalone Nginx, or Cloudflare Tunnel.
+5. **GATE 5: Canonical Compose Path & Git Repository Verification (`VERIFIED / PENDING COMPOSE COMMIT`):**
+   - Repository: `https://github.com/tanu268/Lohnour.git`
+   - Target Branch: `developer` (`VERIFIED: Branch exists locally and on remote origin`).
+   - Compose Location: `COMPOSE FILE LOCATION — NOT YET DECIDED`
+     - *Historical Archive Classification:* `_audit/production-extract/config/docker-compose.yml` is classified as:  
+       **`HISTORICAL ARCHIVE — REFERENCE ONLY — NOT APPROVED FOR PRODUCTION`**.  
+       - Its existence does not establish production readiness.
+       - It must **NOT** be copied into the active deployment directory.
+       - It must **NOT** be used as the production stack configuration.
+       - It must **NOT** be modified or repurposed as part of this task.
+       - Any future reuse requires a separate technical review, compatibility verification, security review, and explicit Founder approval.
+       - A new Compose file must be committed to the `developer` branch before deployment.
+6. **GATE 6: Backup Integrity & Restore Readiness Verification (`STAGE 1 VERIFIED / STAGES 2 & 3 PENDING`):**
+   - **Stage 1 (Local Archive Integrity — `VERIFIED`):** Cryptographic hashes in `D:\lohnourpro\_audit\production-extract\manifest-all.sha256` verified 100% locally on 2026-11-06.
+   - **Stage 2 (Remote Transfer Integrity — `PENDING TRANSFER & VERIFICATION`):** Authoritative archives transferred to VM staging directory and SHA-256 hashes independently verified on the host.
+   - **Stage 3 (Restore Validation — `PENDING STAGING VALIDATION`):** Archive structures verified, database restore executed, and WordPress file/database consistency validated in an isolated staging test environment before production deployment.
+7. **GATE 7: Database Restoration & Secrets Injection Approval (`GATED — DESTRUCTIVE`):**
+   - Explicit written authorization required before importing database records or injecting production secrets.
+8. **GATE 8: DNS Modification & Routing Changes Approval (`PENDING CUTOVER SCHEDULE`):**
+   - Authority to modify the DNS A-record for `pro.lohnour.com` targeting Sam's Oracle VM previously verified baseline public IP **`141.148.51.166`** (`PREVIOUSLY VERIFIED — Mandatory live confirmation in OCI Console required immediately prior to cutover; current DNS does NOT point to this IP`).
+9. **GATE 9: Production Cutover & Maintenance Window Approval (`GATED — FINAL GO/NO-GO`):**
+   - Final approval to initiate production traffic cutover during the scheduled maintenance window (~2h 50m estimate).
+10. **GATE 10: Volume Deletion & Emergency Reset Operations Approval (`EMERGENCY DECOMMISSIONING ONLY`):**
+    - Absolute restriction: `docker volume rm` is strictly forbidden during normal operations or rollback; permitted only under explicit Gate 10 decommissioning approval.
 
 ---
 
-## 3. Sequential Deployment Runbook
+## 3. GitHub + Existing Portainer Deployment Workflow
 
 ```mermaid
 sequenceDiagram
     autonumber
-    actor Engineer as DevOps Engineer
+    actor Dev as Developer / Engineer
+    participant Git as GitHub (developer branch)
+    participant Portainer as Portainer CE (on VM)
+    participant Docker as Docker Daemon
     participant Host as Oracle VM Host
-    participant Portainer as Portainer CE
-    participant Proxy as Reverse Proxy
-    participant WP as WordPress Container
-    participant DB as MariaDB Container
-
-    Note over Engineer,Host: Stage 0 & 1: Baseline & Hardening
-    Engineer->>Host: Verify CPU Architecture, firewalld, SELinux
-    Engineer->>Host: Revoke public port 9443 in OCI Security List
-    Engineer->>Host: Test SSH local tunnel to Portainer (L:9443)
-
-    Note over Engineer,Proxy: Stage 2: Ingress Infrastructure
-    Engineer->>Host: Create Docker network 'proxy_network'
-    Engineer->>Host: Deploy Ingress Reverse Proxy (ports 80 & 443)
-
-    Note over Engineer,Host: Stage 3: Secure Artifact Transfer
-    Engineer->>Host: SCP verified backup archives to /opt/lohnour/staging/
-    Engineer->>Host: Execute sha256sum -c to verify 100% archive integrity
-
-    Note over Engineer,DB: Stage 4 & 5: Application & Database Provisioning
-    Engineer->>Portainer: Deploy 'lohnour-production' stack definition
-    Portainer->>DB: Start MariaDB 10.11 on internal network
-    Engineer->>DB: Import production-lohnour_wp.sql dump
-
-    Note over Engineer,WP: Stage 6 & 7: Application File Staging
-    Portainer->>WP: Start WordPress container
-    Engineer->>WP: Extract uploads, child theme, and plugins
-    Engineer->>WP: Configure uploads.ini and set permissions (www-data:www-data)
-
-    Note over Engineer,Proxy: Stage 8 & 9: Ingress Routing & Verification
-    Engineer->>Proxy: Configure proxy host (pro.lohnour.com -> wp:80)
-    Engineer->>Proxy: Provision Let's Encrypt SSL/TLS Certificate
-    Engineer->>WP: Execute canonical route audit (HTTP 200 checks)
-    Engineer->>Host: Confirm Node-RED and Portainer completely healthy
+
+    Note over Dev,Git: 1. Code & Configuration Authoring
+    Dev->>Git: Commit & Push validated Compose file to developer branch
+
+    Note over Portainer,Git: 2. Repository-Based Stack Deployment
+    Dev->>Portainer: Navigate to Stacks -> Add Stack -> Build Method: Repository
+    Portainer->>Git: Pull Compose specification from developer branch
+    Portainer->>Docker: Deploy containers via Docker Compose API
+
+    Note over Dev,Portainer: 3. Application Staging & Data Restoration
+    Dev->>Host: Transfer backup archives & import data using secure option files
+
+    Note over Git,Portainer: 4. Future Update Workflow
+    Dev->>Git: Push application/configuration update to developer branch
+    Dev->>Portainer: Trigger 'Pull and redeploy' (or automated Webhook/Polling proposal)
 ```
 
+The intended deployment lifecycle strictly enforces GitHub as the source of truth:
+1. Developers author, review, and test configuration changes locally.
+2. Canonical Compose specification is committed to Git on branch `developer` (`https://github.com/tanu268/Lohnour.git`).
+3. Approved deployment changes are pushed to GitHub's `developer` branch.
+4. Existing Portainer connects to the GitHub repository using the Repository build method. *(Note: Portainer's outbound network connectivity to github.com and support for Git repository stack deployment in this specific instance are REPORTED — NOT INDEPENDENTLY VERIFIED; live confirmation of Portainer Git capabilities is required prior to deployment).*
+5. Portainer deploys the Compose configuration from the verified repository path (`COMPOSE FILE LOCATION — NOT YET DECIDED` until committed).
+6. Future updates are deployed through this repository-based workflow ("Pull and redeploy" or polling/webhook proposal). No direct `docker compose` execution is substituted for this workflow during standard operations.
+
 ---
 
-### Stage 0: Host OS & Environment Baseline Verification
-**Estimated Time:** 15 minutes  
-**Target:** Sam's Oracle VM Terminal via SSH
+## 4. Sequential Deployment Runbook
+
+The deployment procedure follows a safe, logical 9-stage sequence (Stages 0 through 8).
+
+> [!CRITICAL]
+> **DATABASE-FIRST SEQUENCING MANDATE:**  
+> WordPress must **NOT** be started against an empty database. Starting WordPress before restoring the database triggers the WordPress initial setup installation wizard, auto-creates default tables that conflict with the backup restoration, and risks silent data corruption.  
+> The database container must be restored, schema populated, and application files staged **BEFORE** the WordPress application container is launched.
+
+---
+
+### Stage 0: Host OS & Infrastructure Verification
+**Classification:** Read-only | **Governing Gate:** **GATE 1**  
+**Estimated Time:** 15 minutes | **Target:** Sam's Oracle VM Terminal via SSH  
 
 1. **Verify CPU Architecture:**
    ```bash
    uname -m
    ```
-   *Action:* If `aarch64`, ensure all subsequent compose images pull ARM64 multi-arch manifests. If `x86_64`, standard AMD64 images apply.
-2. **Inspect Firewall Daemon:**
+   *Action:* If `aarch64`, confirm all images pulled support ARM64 multi-arch manifests. If `x86_64`, standard AMD64 applies.
+2. **Inspect Host Firewall Daemon:**
    ```bash
    sudo systemctl status firewalld
    ```
@@ -107,324 +160,495 @@ sequenceDiagram
    ```bash
    sudo firewall-cmd --list-all
    ```
-   Ensure `http` and `https` services are permitted; ensure ports `9443` and `1880` are not exposed.
+   Ensure `http` and `https` services are permitted; ensure ports `9443` and `1880` are not publicly exposed.
 3. **Inspect SELinux Mode:**
    ```bash
    getenforce
    ```
-   *Action:* If `Enforcing`, confirm container SELinux policy allows volume mounts or append `:z` flags to all volume mappings.
+   *Action:* If `Enforcing`, confirm container SELinux policy allows volume mounts or append `:z` flags ONLY to specific application host-bind mounts (e.g., staged `uploads.ini:ro,z`). Never apply `:z` to host system directories (`/etc`, `/var`, `/run`, `/var/run/docker.sock`) or Docker named volumes.
 4. **Inspect Existing Workloads:**
    ```bash
    docker ps --format "table {{.ID}}\t{{.Names}}\t{{.Image}}\t{{.Status}}\t{{.Ports}}"
    docker volume ls
    ```
-   *Action:* Confirm `portainer` and `nodered` containers are running normally.
+   *Action:* Confirm existing `portainer` and `nodered` containers and volumes (`portainer_data`, `nodered_data`) are running normally and remain untouched.
 
 ---
 
-### Stage 1: Portainer Perimeter Hardening
-**Estimated Time:** 15 minutes  
-**Target:** OCI Console & SSH Client
-
-1. **OCI Security List Rule Update:**
+### Stage 1: Management-Plane & Perimeter Security Hardening
+**Classification:** Reversible Configuration | **Governing Gate:** **GATE 2**  
+**Estimated Time:** 15 minutes | **Target:** SSH Client & OCI Console  
+
+> [!CRITICAL]
+> **LOCKOUT PREVENTION & VERIFICATION SEQUENCE (BLOCKER 8 RESOLUTION):**  
+> To prevent an accidental administrative lockout, direct access to Portainer must NEVER be restricted before secure SSH tunnel access is confirmed operational. The following sequential order is mandatory:  
+> 1. Confirm SSH administrative connectivity to Sam's Oracle VM.  
+> 2. Establish and test the intended local SSH tunnel.  
+> 3. Verify that Portainer is reachable and manageable through `https://localhost:9443`.  
+> 4. Verify that the operator retains an administrative recovery path.  
+> 5. Only then consider restricting or revoking public OCI ingress rules for port 9443, subject to explicit Founder approval under **Gate 2**.  
+> *(Note: Do NOT modify OCI rules during documentation audits. Do not assume the tunnel is configured or tested).*
+
+1. **Confirm Administrative SSH Access:**
+   - Verify non-interactive and interactive key-based SSH access to Sam's Oracle VM:
+     ```bash
+     ssh -i ~/.ssh/oracle_key.key -o BatchMode=yes opc@141.148.51.166 "echo 'SSH connectivity verified'"
+     ```
+   *(Note: Target IP `141.148.51.166` is the previously verified baseline public IP; live reconfirmation in OCI Console is required prior to cutover. Current DNS does NOT point to this IP).*
+
+2. **Establish & Test Local SSH Port Forwarding Tunnel:**
+   - Establish local port forward binding local port 9443 to host port 9443:
+     ```bash
+     ssh -i ~/.ssh/oracle_key.key -N -L 9443:localhost:9443 opc@141.148.51.166
+     ```
+   - Verify local tunnel listener is active on administrative workstation:
+     - Windows PowerShell: `Test-NetConnection -ComputerName localhost -Port 9443`
+     - Linux/macOS: `nc -zv localhost 9443`
+
+3. **Verify Portainer Access via Secure Tunnel:**
+   - Open browser on local administrative workstation: `https://localhost:9443`.
+   - Authenticate to Portainer CE and verify container dashboard renders correctly.
+   - Confirm that the operator possesses full administrative recovery access through this tunnel.
+
+4. **OCI Security List Rule Revocation (`GATE 2 — FOUNDER APPROVAL REQUIRED`):**
+   - *Prerequisite Check:* Proceed ONLY after Steps 1–3 above confirm working tunnel access.
+   - *Perimeter Ingress Re-verification:* Prior to altering rules, inspect OCI Console to confirm whether port 9443 is currently permitted from `0.0.0.0/0` (`REPORTED — NOT INDEPENDENTLY VERIFIED`; public reachability must not be inferred solely from Docker binding `0.0.0.0:9443` on the host, as host `firewalld`, OCI Security Lists, and NSGs provide independent filtering layers).
    - In OCI Console -> Virtual Cloud Networks -> VCN -> Security Lists:
    - Edit the Ingress Rule for Port `9443`:
      - **Option 1 (Recommended):** Delete the rule permitting `0.0.0.0/0` on port 9443 entirely.
-     - **Option 2:** Edit Source CIDR to `<Founder_Static_IP>/32`.
-2. **Establish Local SSH Tunnel:**
-   ```bash
-   ssh -i ~/.ssh/oracle_key.key -L 9443:localhost:9443 opc@<VM_PUBLIC_IP>
-   ```
-3. **Verify Portainer Access via Tunnel:**
-   - Open local browser: `https://localhost:9443`.
-   - Confirm successful login. Portainer is now completely shielded from the public internet.
+     - **Option 2:** Restrict Source CIDR strictly to `<Founder_Static_IP>/32`.
+   - *Status:* `HOLD — PENDING GATE 2 APPROVAL AND WORKING TUNNEL VERIFICATION`. Do not modify OCI rules in this documentation task.
 
 ---
 
-### Stage 2: Ingress Virtual Network & Reverse Proxy Deployment
-**Estimated Time:** 20 minutes  
-**Target:** Portainer Stacks or Host Terminal
-
-1. **Create Shared Ingress Bridge Network:**
-   ```bash
-   docker network create --driver bridge proxy_network
-   ```
-2. **Deploy Reverse Proxy Stack (`npm-stack`):**
-   Deploy the following Compose specification:
-   ```yaml
-   version: '3.8'
-   services:
-     npm:
-       image: jc21/nginx-proxy-manager:latest
-       container_name: lohnour-reverse-proxy
-       restart: unless-stopped
-       ports:
-         - "80:80"
-         - "443:443"
-       volumes:
-         - npm_data:/data
-         - npm_letsencrypt:/etc/letsencrypt
-       networks:
-         - proxy_network
-       deploy:
-         resources:
-           limits:
-             cpus: '0.50'
-             memory: 512M
-
-   networks:
-     proxy_network:
-       external: true
-
-   volumes:
-     npm_data:
-       name: npm_data
-     npm_letsencrypt:
-       name: npm_letsencrypt
-   ```
-3. **Verify Port Listeners:**
-   ```bash
-   sudo ss -tulpn | grep -E ':(80|443)'
-   ```
-   Confirm reverse proxy is bound to ports 80 and 443 on the host.
+### Stage 2: Deployment Source & Canonical Compose Verification
+**Classification:** Read-only Verification | **Governing Gate:** **GATE 5**  
+**Estimated Time:** 15 minutes | **Target:** Local Workspace & GitHub  
+
+1. **Verify Target Branch:**
+   - Confirm branch `developer` exists locally and on remote `origin`:
+     ```bash
+     git branch -a
+     ```
+2. **Verify Compose Specification:**
+   - Verify that the production Compose specification is committed to the `developer` branch.
+   - *Status:* `COMPOSE FILE LOCATION — NOT YET DECIDED`.
+   - Ensure the historical Compose file (`_audit/production-extract/config/docker-compose.yml`) is **NOT** used, as it is classified as `HISTORICAL ARCHIVE — REFERENCE ONLY — NOT APPROVED FOR PRODUCTION`.
+3. **Confirm Non-Root Execution & Resource Constraints:**
+   - Verify Compose file defines proposed limits: `1.00 OCPU` for WordPress, `1.00 OCPU` for MariaDB, `0.25 OCPU` for reverse proxy (`PROPOSED — NOT APPROVED — NOT DEPLOYED`).
+   - Verify database ports (`3306`) are strictly private to the internal bridge network.
 
 ---
 
-### Stage 3: Secure Artifact Transfer & Verification
-**Estimated Time:** 20 minutes  
-**Target:** Local Admin Workstation -> Sam's Oracle VM
+### Stage 3: Container Image Compatibility Verification
+**Classification:** Read-only Verification | **Governing Gate:** **GATE 3**  
+**Estimated Time:** 20 minutes | **Status:** **`WORDPRESS IMAGE SELECTION: BLOCKED`**  
+
+*Execution cannot proceed past this stage until all 8 compatibility criteria are verified:*
+
+1. Identify exact WordPress core version from authoritative backup/source.
+2. Establish PHP compatibility for core version, `twentytwentyfive-child` theme, and active plugins.
+3. Verify all installed plugins for compatibility with selected PHP and WordPress versions.
+4. Establish existing database engine (MariaDB) and compatibility with database revision `61833`.
+5. Verify container image multi-arch compatibility (`linux/arm64` vs `linux/amd64`) matching VM architecture.
+6. Select immutable image tags and record SHA256 digests (`image@sha256:...`); disallow `latest`.
+7. Execute dry-run test restore on staging environment.
+8. Complete end-to-end functional validation of restored staging instance before approving production pull.
 
-1. **Create Remote Staging Directory:**
+---
+
+### Stage 4: Authoritative Artifact Staging & Remote Transfer Verification
+**Classification:** Mutating Remote Host Operation (Approval Required) | **Governing Gate:** **GATE 6**  
+**Estimated Time:** 20 minutes | **Target:** Local Admin Workstation -> Sam's Oracle VM  
+
+*This stage modifies remote host state by creating staging directories and transferring data files. It is explicitly classified as a mutating operation and requires prior Gate 6 approval.*
+
+1. **Pre-Transfer Storage Verification:**
+   - Verify available disk space on the target host filesystem:
+     ```bash
+     df -h /opt
+     ```
+     Ensure available space comfortably exceeds archive and extraction requirements (> 500 MiB free space required for staging).
+2. **Target Staging Directory Inspection & Safe Permission Reconciliation (BLOCKER A & 3 RESOLUTION):**
+   - Purpose: `/opt/lohnour/staging` provides an isolated host staging buffer for archive validation prior to container volume injection.
+   - Required standard: User `opc`, Group `opc`, Permission Mode `700` (strict owner-only access; non-recursive).
+   - *Governance Requirement:* This step is a **Mutating Remote Host Operation (Approval Required)**. Unconditional execution of `chown` or `chmod` is strictly prohibited. Modifying filesystem attributes requires an explicit operator approval checkpoint. Existing files and permissions must be preserved unless an explicitly approved adjustment is required.
+   
    ```bash
-   sudo mkdir -p /opt/lohnour/staging
-   sudo chown -R $USER:$USER /opt/lohnour
+   TARGET_DIR="/opt/lohnour/staging"
+   EXPECTED_USER="opc"
+   EXPECTED_GROUP="opc"
+   EXPECTED_MODE="700"
+
+   # MANDATORY OPERATOR APPROVAL CHECKPOINT
+   # Creating directories or modifying permissions constitutes an intentional host mutation.
+   # Proceed ONLY if explicit operational approval has been granted.
+   if [ "${ALLOW_STAGE4_MUTATION:-false}" != "true" ]; then
+     echo "GATE 6 APPROVAL CHECKPOINT: Modifying host filesystem or directory attributes requires operator authorization."
+     echo "To execute after reviewing, set: export ALLOW_STAGE4_MUTATION=true. Aborting mutating operation." >&2
+     exit 1
+   fi
+
+   # 1. Inspect whether target directory exists
+   if [ -d "${TARGET_DIR}" ]; then
+     echo "Directory ${TARGET_DIR} exists. Inspecting current attributes..."
+     # 2. Record current owner, group, permissions, and contents
+     CURRENT_OWNER=$(stat -c "%U" "${TARGET_DIR}" 2>/dev/null || stat -f "%Su" "${TARGET_DIR}" 2>/dev/null || echo "unknown")
+     CURRENT_GROUP=$(stat -c "%G" "${TARGET_DIR}" 2>/dev/null || stat -f "%Sg" "${TARGET_DIR}" 2>/dev/null || echo "unknown")
+     CURRENT_MODE=$(stat -c "%a" "${TARGET_DIR}" 2>/dev/null || stat -f "%OLp" "${TARGET_DIR}" 2>/dev/null || echo "000")
+     echo "Recorded state: Owner=${CURRENT_OWNER}, Group=${CURRENT_GROUP}, Mode=${CURRENT_MODE}"
+     ls -la "${TARGET_DIR}"
+
+     # 3. Compare observed state with documented required state
+     MISMATCH=0
+     if [ "${CURRENT_OWNER}" != "${EXPECTED_USER}" ] || [ "${CURRENT_GROUP}" != "${EXPECTED_GROUP}" ]; then
+       echo "Ownership mismatch: observed ${CURRENT_OWNER}:${CURRENT_GROUP}, required ${EXPECTED_USER}:${EXPECTED_GROUP}"
+       MISMATCH=1
+     fi
+     if [ "${CURRENT_MODE}" != "${EXPECTED_MODE}" ]; then
+       echo "Permission mismatch: observed ${CURRENT_MODE}, required ${EXPECTED_MODE}"
+       MISMATCH=1
+     fi
+
+     # 4 & 5. Change ownership or permissions ONLY if specific mismatch exists AND explicit approval is confirmed
+     if [ "${MISMATCH}" -eq 1 ]; then
+       echo "OPERATOR APPROVAL CONFIRMED: Adjusting directory attributes to match required standard..."
+       # 6. Never recursively change ownership or permissions (NO -R flag; target directory entry only)
+       sudo chown "${EXPECTED_USER}:${EXPECTED_GROUP}" "${TARGET_DIR}"
+       chmod "${EXPECTED_MODE}" "${TARGET_DIR}"
+       echo "Directory attributes adjusted to ${EXPECTED_USER}:${EXPECTED_GROUP} mode ${EXPECTED_MODE}."
+     else
+       echo "Directory attributes match required state (${CURRENT_OWNER}:${CURRENT_GROUP} mode ${CURRENT_MODE})."
+       echo "Preserving existing ownership and permissions. Zero modifications applied."
+     fi
+     # 7. Preserve existing files and contents
+     echo "Existing directory contents preserved."
+   else
+     # Directory does not exist; create with explicit mode and non-recursive ownership under operator approval
+     echo "Directory ${TARGET_DIR} does not exist. Creating under confirmed Gate 6 approval..."
+     sudo mkdir -p "${TARGET_DIR}"
+     sudo chown "${EXPECTED_USER}:${EXPECTED_GROUP}" "${TARGET_DIR}"
+     chmod "${EXPECTED_MODE}" "${TARGET_DIR}"
+   fi
+
+   # 8. If expected state cannot be verified, stop and report blocker
+   FINAL_MODE=$(stat -c "%a" "${TARGET_DIR}" 2>/dev/null || stat -f "%OLp" "${TARGET_DIR}" 2>/dev/null || echo "000")
+   if [ "${FINAL_MODE}" != "${EXPECTED_MODE}" ]; then
+     echo "FATAL: Could not establish required directory mode ${EXPECTED_MODE}. Stopping and reporting blocker." >&2
+     exit 1
+   fi
    ```
-2. **Transfer Authoritative Archives via SCP:**
+3. **Transfer Authoritative Archives via SCP:**
+   - Transfer files from local workstation to the verified host baseline:
    ```powershell
-   # Executed from local machine
-   scp -i ~/.ssh/oracle_key.key D:\lohnourpro\_audit\production-extract\database\production-lohnour_wp.sql opc@<VM_IP>:/opt/lohnour/staging/
-   scp -i ~/.ssh/oracle_key.key D:\lohnourpro\_audit\production-extract\uploads.tar.gz opc@<VM_IP>:/opt/lohnour/staging/
-   scp -i ~/.ssh/oracle_key.key D:\lohnourpro\_audit\production-extract\twentytwentyfive-child.tar.gz opc@<VM_IP>:/opt/lohnour/staging/
-   scp -i ~/.ssh/oracle_key.key D:\lohnourpro\_audit\production-extract\lohnour_all_plugins.tar.gz opc@<VM_IP>:/opt/lohnour/staging/
-   scp -i ~/.ssh/oracle_key.key D:\lohnourpro\_audit\production-extract\config\uploads.ini opc@<VM_IP>:/opt/lohnour/staging/
-   scp -i ~/.ssh/oracle_key.key D:\lohnourpro\_audit\production-extract\manifest-all.sha256 opc@<VM_IP>:/opt/lohnour/staging/
+   # Executed from local administrative workstation targeting previously verified baseline IP (141.148.51.166)
+   scp -i ~/.ssh/oracle_key.key D:\lohnourpro\_audit\production-extract\database\production-lohnour_wp.sql opc@141.148.51.166:/opt/lohnour/staging/
+   scp -i ~/.ssh/oracle_key.key D:\lohnourpro\_audit\production-extract\uploads.tar.gz opc@141.148.51.166:/opt/lohnour/staging/
+   scp -i ~/.ssh/oracle_key.key D:\lohnourpro\_audit\production-extract\twentytwentyfive-child.tar.gz opc@141.148.51.166:/opt/lohnour/staging/
+   scp -i ~/.ssh/oracle_key.key D:\lohnourpro\_audit\production-extract\lohnour_all_plugins.tar.gz opc@141.148.51.166:/opt/lohnour/staging/
+   scp -i ~/.ssh/oracle_key.key D:\lohnourpro\_audit\production-extract\config\uploads.ini opc@141.148.51.166:/opt/lohnour/staging/
+   scp -i ~/.ssh/oracle_key.key D:\lohnourpro\_audit\production-extract\manifest-all.sha256 opc@141.148.51.166:/opt/lohnour/staging/
    ```
-3. **Verify Cryptographic Hashes on Host:**
+4. **Verify Remote Cryptographic Hashes on Host (Gate 6 — Stage 2):**
    ```bash
    cd /opt/lohnour/staging
-   sha256sum production-lohnour_wp.sql uploads.tar.gz twentytwentyfive-child.tar.gz lohnour_all_plugins.tar.gz uploads.ini
-   ```
-   Compare hashes against `manifest-all.sha256`. Do not proceed if any hash differs by a single character.
-
----
-
-### Stage 4: Production Stack Deployment via Portainer
-**Estimated Time:** 15 minutes  
-**Target:** Portainer UI (`https://localhost:9443` via SSH Tunnel)
-
-1. Navigate to: **Stacks -> Add Stack**.
-2. **Stack Name:** `lohnour-production`
-3. **Web editor (Compose definition):**
-   ```yaml
-   version: '3.8'
-
-   services:
-     db:
-       image: mariadb:10.11
-       container_name: lohnour-db
-       restart: unless-stopped
-       environment:
-         MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
-         MYSQL_DATABASE: lohnour_wp
-         MYSQL_USER: lohnour_user
-         MYSQL_PASSWORD: ${DB_PASSWORD}
-       volumes:
-         - lohnour_db_data:/var/lib/mysql
-       networks:
-         - lohnour_db_network
-       healthcheck:
-         test: ["CMD", "healthcheck.sh", "--connect", "--innodb_initialized"]
-         interval: 10s
-         timeout: 5s
-         retries: 5
-         start_period: 30s
-       deploy:
-         resources:
-           limits:
-             cpus: '2.00'
-             memory: 3072M
-
-     wordpress:
-       image: wordpress:latest
-       container_name: lohnour-wordpress
-       restart: unless-stopped
-       environment:
-         WORDPRESS_DB_HOST: db:3306
-         WORDPRESS_DB_NAME: lohnour_wp
-         WORDPRESS_DB_USER: lohnour_user
-         WORDPRESS_DB_PASSWORD: ${DB_PASSWORD}
-         WORDPRESS_TABLE_PREFIX: wp_
-         WORDPRESS_CONFIG_EXTRA: |
-           if (isset($$_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($$_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
-               $$_SERVER['HTTPS'] = 'on';
-           }
-       volumes:
-         - lohnour_wp_data:/var/www/html
-         - /opt/lohnour/staging/uploads.ini:/usr/local/etc/php/conf.d/uploads.ini:ro
-       networks:
-         - proxy_network
-         - lohnour_db_network
-       depends_on:
-         db:
-           condition: service_healthy
-       deploy:
-         resources:
-           limits:
-             cpus: '2.00'
-             memory: 3072M
-
-   networks:
-     proxy_network:
-       external: true
-     lohnour_db_network:
-       name: lohnour_db_network
-       internal: true
-
-   volumes:
-     lohnour_db_data:
-       name: lohnour_db_data
-     lohnour_wp_data:
-       name: lohnour_wp_data
+   sha256sum -c manifest-all.sha256
    ```
-4. **Environment Variables:** Provide freshly generated 32-character base64 values for `DB_ROOT_PASSWORD` and `DB_PASSWORD`.
-5. Click **Deploy the stack**.
+   Do not proceed if any checksum mismatch occurs.  
+   *(Note: Successful hash match on the host completes Stage 2 of Gate 6. Stage 3 [Isolated Restore Validation] must be executed in staging prior to production cutover).*
 
 ---
 
-### Stage 5: Database Restoration & Integrity Check
-**Estimated Time:** 15 minutes  
-**Target:** Host Terminal
+### Stage 5: Database Restoration & Application Data Staging
+**Classification:** Destructive Operation (Mutating Database & Volumes) | **Governing Gate:** **GATE 7** (Production) / **GATE 6** (Staging Validation)  
+**Estimated Time:** 25 minutes | **Target:** Host Terminal  
+**Governance Requirement:** Staging restore validation is governed under **GATE 6 (Stage 3)** using dedicated volume `lohnour_db_staging_data`. Destructive production database initialization into `lohnour_db_data` is governed strictly under **GATE 7** (`Database Restoration & Secrets Injection Approval`). Staging validation must never be classified as production deployment.
 
-1. **Verify Database Container Health:**
-   ```bash
-   docker ps --filter "name=lohnour-db" --format "{{.Names}}: {{.Status}}"
-   ```
-   Wait until status shows `(healthy)`.
-2. **Execute SQL Dump Import:**
-   ```bash
-   docker exec -i lohnour-db mariadb -u lohnour_user -p"${DB_PASSWORD}" lohnour_wp < /opt/lohnour/staging/production-lohnour_wp.sql
-   ```
-3. **Verify Database Table Count & Revision:**
-   ```bash
-   docker exec -it lohnour-db mariadb -u lohnour_user -p"${DB_PASSWORD}" lohnour_wp -e "SHOW TABLES; SELECT option_name, option_value FROM wp_options WHERE option_name IN ('siteurl', 'home');"
-   ```
-   *Verification:* Expect 14 tables. Confirm `siteurl` and `home` match `https://pro.lohnour.com`.
+> [!CAUTION]
+> **DEPLOYMENT METHOD & VOLUME ISOLATION MANDATE (BLOCKER 1 RESOLUTION):**  
+> 1. In accordance with agreed architecture, **Portainer Git Repository integration** is the primary deployment method. Direct `docker compose` execution must not be used as a substitute for production stack deployment.
+> 2. WordPress must **NEVER** be launched against an empty database. The database schema, records, and application media files must be staged and validated before the full application stack is started in Portainer.
+> 3. **Strict Storage Volume Isolation:**
+>    - Staging restore drills (Gate 6 — Stage 3) must strictly utilize dedicated staging volume: **`lohnour_db_staging_data`**.
+>    - Staging tests must **NEVER** mount or restore data into production volume **`lohnour_db_data`**.
+>    - Production database initialization into `lohnour_db_data` is a separate, destructive operation requiring explicit Founder authorization under **GATE 7**.
+> 4. **Staging Volume Retention & Deletion Governance (BLOCKER C RESOLUTION):**
+>    - Routine deletion of `lohnour_db_staging_data` via `docker volume rm` is **STRICTLY PROHIBITED** from normal restore-drill procedures.
+>    - The staging volume is retained as a **`retained staging reference`** following drill completion (do NOT describe as immutable; Docker named volumes possess no cryptographic write-locking or immutability enforcement mechanism).
+>    - Any future deletion requires explicit Founder approval under **GATE 10** (`Volume Deletion & Emergency Reset Operations Approval`).
+
+1. **Pre-Restore SQL Dump Inspection Protocol (BLOCKER 7 RESOLUTION):**
+   - *Status:* **`PENDING — DUMP INSPECTION REQUIRED`**.
+   - Before executing any staging database import, the SQL dump file (`production-lohnour_wp.sql`) must be forensic-inspected on the host or admin workstation across the following 9 verification criteria:
+     1. **`CREATE DATABASE` statements:** Verify the dump does not attempt to create an unapproved database name or overwrite system schemas.
+     2. **`USE` statements:** Verify no hardcoded `USE <database>;` statement overrides the designated target database (`lohnour_wp`).
+     3. **`DEFINER` clauses:** Inspect for hardcoded definers (e.g., `DEFINER=\`root\`@\`localhost\``) on views, triggers, or routines that could cause privilege errors during unprivileged import.
+     4. **Database & Table references:** Ensure all SQL statements operate strictly within the target schema.
+     5. **Table prefixes:** Verify table naming and prefix structure (e.g., expected `wp_` prefix; do not assume prefix without inspection).
+     6. **SQL syntax & compatibility:** Check for compatibility with target MariaDB version (character sets, collation, deprecated syntax).
+     7. **Required privileges & grants:** Verify whether the dump requires administrative privileges (`SUPER`, `BINLOG ADMIN`) or can be imported cleanly by unprivileged user `lohnour_user`.
+     8. **Unexpected objects:** Inspect for unexpected user accounts, custom routines, triggers, or scheduled events.
+     9. **Dump completeness & integrity:** Confirm proper dump termination marker (e.g., `/* Dump completed on ... */`) and matching SHA-256 hash.
+   - *Constraint:* Do **NOT** assert table count (e.g. 14 tables) or specific table structures (`wp_posts`) until this inspection is completed with recorded evidence. Do NOT execute import if inspection reveals syntax or privilege incompatibilities.
+
+2. **Staging Database Restore Validation Drill (`GATE 6 — STAGE 3`):**
+   - Start an isolated MariaDB container mounting the **staging volume only** (`lohnour_db_staging_data`) with configured container health check (BLOCKER 2 RESOLUTION):
+     ```bash
+     # Isolated test container startup with configured container health check (PROPOSED — NOT TESTED)
+     docker run -d \
+       --name lohnour-db-staging \
+       -v lohnour_db_staging_data:/var/lib/mysql \
+       -e MYSQL_ROOT_PASSWORD="<PLACEHOLDER — NOT A REAL CREDENTIAL>" \
+       -e MYSQL_DATABASE=lohnour_wp \
+       -e MYSQL_USER=lohnour_user \
+       -e MYSQL_PASSWORD="<PLACEHOLDER — NOT A REAL CREDENTIAL>" \
+       --health-cmd='mariadb-admin ping -h 127.0.0.1 -u lohnour_user --password="$MYSQL_PASSWORD" --silent || exit 1' \
+       --health-interval=5s \
+       --health-timeout=3s \
+       --health-retries=10 \
+       --health-start-period=15s \
+       mariadb:verified_tag
+     ```
+     *(Note: Image tag and parameters subject to Gate 3 image compatibility verification and Gate 5 Compose review).*
+
+   - **Bounded Database Readiness Probe (BLOCKER 2 RESOLUTION):**
+     ```bash
+     # Bounded readiness probe polling container health status (maximum 60 seconds)
+     # Do NOT use 'docker ps' as a readiness test; inspect actual container health check status.
+     MAX_RETRIES=12
+     COUNT=0
+     HEALTH_STATUS="starting"
+
+     until [ "$COUNT" -ge "$MAX_RETRIES" ]; do
+       HEALTH_STATUS=$(docker inspect -f '{{if .State.Health}}{{.State.Health.Status}}{{else}}unknown{{end}}' lohnour-db-staging 2>/dev/null || echo "not_found")
+       if [ "${HEALTH_STATUS}" = "healthy" ]; then
+         echo "Staging database is healthy and ready to accept connections."
+         break
+       fi
+       echo "Waiting for staging database readiness (status: ${HEALTH_STATUS}, attempt $((COUNT+1))/${MAX_RETRIES})..."
+       sleep 5
+       COUNT=$((COUNT + 1))
+     done
+
+     if [ "${HEALTH_STATUS}" != "healthy" ]; then
+       echo "FATAL: Staging database failed to reach healthy state within timeout period. Inspecting container logs:" >&2
+       docker logs --tail 50 lohnour-db-staging
+       exit 1
+     fi
+     ```
+
+   - Execute test database import with failure-safe option file:
+     ```bash
+     # Register failure-safe trap to guarantee cleanup of credentials on any script exit
+     trap 'docker exec lohnour-db-staging rm -f /tmp/client.cnf; rm -f /opt/lohnour/staging/client.cnf' EXIT
+
+     # Create host option file with mode 0600
+     cat << 'EOF' > /opt/lohnour/staging/client.cnf
+     [client]
+     user=lohnour_user
+     password=<PLACEHOLDER — NOT A REAL CREDENTIAL>
+     EOF
+     chmod 600 /opt/lohnour/staging/client.cnf
+
+     # Copy option file into container temporary storage
+     docker cp /opt/lohnour/staging/client.cnf lohnour-db-staging:/tmp/client.cnf
+     docker exec lohnour-db-staging chmod 600 /tmp/client.cnf
+
+     # Import database dump via stdin stream into staging container
+     docker exec -i lohnour-db-staging mariadb \
+       --defaults-extra-file=/tmp/client.cnf \
+       lohnour_wp < /opt/lohnour/staging/production-lohnour_wp.sql
+
+     # Verify database tables and record count BEFORE cleanup
+     docker exec -i lohnour-db-staging mariadb \
+       --defaults-extra-file=/tmp/client.cnf \
+       -e "SHOW TABLES; SELECT count(*) FROM wp_posts; SELECT option_name, option_value FROM wp_options WHERE option_name IN ('siteurl', 'home');" lohnour_wp
+
+     # Explicitly purge option file and disarm trap
+     docker exec lohnour-db-staging rm -f /tmp/client.cnf
+     rm -f /opt/lohnour/staging/client.cnf
+     trap - EXIT
+
+     # Decommission temporary staging container (VOLUME RETAINED)
+     docker stop lohnour-db-staging && docker rm lohnour-db-staging
+     ```
+
+   - **Staging Volume Post-Drill Policy & Retention (BLOCKER 1 RESOLUTION):**
+     - **Retention:** Staging volume `lohnour_db_staging_data` is **RETAINED** upon drill completion. Routine deletion via `docker volume rm` is strictly forbidden.
+     - **Purpose:** Serves as a **`retained staging reference`** confirming data restorable under Gate 6 Stage 3 (not immutable; Docker named volumes possess no cryptographic write-locking). Available for comparative verification if production restore encounters issues.
+     - **Ownership:** Internal files managed natively by MariaDB (`UID 999:GID 999`).
+     - **Storage Implications:** Consumes approximately 25–50 MB within host Docker Root Dir. Must be accounted for against host storage limits.
+     - **Future Deletion Governance:** Any future decommissioning of `lohnour_db_staging_data` requires explicit authorization under **GATE 10** (`Volume Deletion & Emergency Reset Operations Approval`).
+
+3. **Production Database Volume Initialization (`GATE 7 — APPROVAL REQUIRED`):**
+   - Once staging validation (Gate 6) is verified and explicit Founder approval under **Gate 7** is granted:
+   - The production volume **`lohnour_db_data`** is initialized with the production database schema and records prior to starting WordPress.
+   - The production database service is keyed as `db` (container `lohnour-db`) mounting `lohnour_db_data:/var/lib/mysql`. Staging data is never restored directly into the production volume.
+   - The import is executed using the failure-safe option file pattern directly targeting the isolated production database container.
+
+4. **Stage Application Files into Persistent Volume (Dynamic Path Inspection):**
+   - *Never hardcode `/var/lib/docker/volumes/...` paths.*
+   - Inspect the volume mountpoint dynamically:
+     ```bash
+     WP_DATA_DIR=$(docker volume inspect lohnour_wp_data --format '{{ .Mountpoint }}')
+     echo "Resolved WordPress Volume Path: ${WP_DATA_DIR}"
+     ```
+   - Extract uploads, child theme, and plugins into verified mountpoint:
+     ```bash
+     sudo tar -xzf /opt/lohnour/staging/uploads.tar.gz -C "${WP_DATA_DIR}/wp-content/"
+     sudo tar -xzf /opt/lohnour/staging/twentytwentyfive-child.tar.gz -C "${WP_DATA_DIR}/wp-content/themes/"
+     sudo tar -xzf /opt/lohnour/staging/lohnour_all_plugins.tar.gz -C "${WP_DATA_DIR}/wp-content/plugins/"
+     ```
+5. **Filesystem Ownership & Permission Normalization (`SAFE PROTOCOL`):**
+   - *Blind recursive `chown -R 33:33` or `chmod -R` without verifying target paths is strictly prohibited.*
+   - **Protocol for Permission Verification:**
+     1. Explain exact directory and purpose: Extracted uploads (`wp-content/uploads`) and themes/plugins require read access by the web server; uploads requires write access for media handling.
+     2. Identify intended service user: `www-data` (`UID 33:GID 33` in standard WordPress PHP-FPM containers).
+     3. Inspect current ownership and permissions before mutating:
+        ```bash
+        ls -ld "${WP_DATA_DIR}/wp-content"
+        ls -ld "${WP_DATA_DIR}/wp-content/uploads"
+        ```
+     4. Propose only narrowest justified changes: If files carry unexpected UIDs, adjust only specific directories after verification.
+     5. Classification: **Mutating remote filesystem operation requiring explicit operational approval**.
 
 ---
 
-### Stage 6: Application File Staging & Permissions Normalization
-**Estimated Time:** 20 minutes  
-**Target:** Host Terminal
+### Stage 6: Application Stack Deployment via Portainer Git Repository
+**Classification:** Reversible Deployment | **Governing Gates:** **GATE 5 & GATE 7**  
+**Estimated Time:** 15 minutes | **Target:** Portainer UI (`https://localhost:9443` via SSH Tunnel)  
 
-1. **Extract Uploads Archive into Persistent Volume:**
+> [!IMPORTANT]
+> **PORTAINER REPOSITORY DEPLOYMENT MANDATE:**  
+> In accordance with the single source of truth architecture, the stack is deployed exclusively through Portainer's **Repository** method. The Portainer Web Editor is strictly prohibited.
+
+1. **Deploy Production Stack via Portainer UI:**
+   - Connect via local SSH tunnel (`ssh -L 9443:localhost:9443 opc@141.148.51.166`).
+   - Open browser: `https://localhost:9443`.
+   - Navigate to **Stacks -> Add Stack**.
+   - Build method: Select **Repository**.
+   - **Repository URL:** `https://github.com/tanu268/Lohnour.git`
+   - **Repository reference:** `refs/heads/developer` (`VERIFIED: Branch exists`).
+   - **Compose path:** Pinned path to canonical Compose file (`COMPOSE FILE LOCATION — NOT YET DECIDED` until committed under Gate 5).
+   - **Environment Variables:** Provide `DB_ROOT_PASSWORD` and `DB_PASSWORD` securely via Portainer's Environment Variables panel (injected at runtime, never committed to Git).
+   - Click **Deploy the stack**.
+2. **Verify Container Startup & Pre-Populated Database Connection:**
+   - WordPress connects using parameter `WORDPRESS_DB_HOST: db:3306`, which resolves on `lohnour_db_network` to the database Compose service key `db` (container_name: `lohnour-db`) via Docker's internal DNS alias `db`.
+   - Confirm container connects cleanly to existing pre-populated database:
+     ```bash
+     docker logs --tail 50 lohnour-wordpress
+     ```
+   - Confirm that the WordPress setup wizard is **NOT** displayed and no table creation errors occur.
+3. **Verify PHP Injected Runtime Limits:**
    ```bash
-   sudo tar -xzf /opt/lohnour/staging/uploads.tar.gz -C /var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/
+   docker exec lohnour-wordpress php -i | grep -E 'upload_max_filesize|post_max_size|memory_limit'
    ```
-2. **Extract Theme and Plugin Archives:**
+   *Expected Output:* `upload_max_filesize => 512M`, `post_max_size => 512M`, `memory_limit => 512M`.
+4. **Internal Application Smoke Test (BLOCKER 4 RESOLUTION):**
+   - *Status:* **`UNVALIDATED — PENDING IMAGE & PROBE METHOD VERIFICATION`**.
+   - *Constraint:* Do **NOT** assume that `curl`, `wget`, or any external networking utility exists inside the minimal production WordPress image. Modifying the production container image to install troubleshooting tools is strictly prohibited.
+   - *Execution Standard:* The internal smoke test must be executed either via a verified native utility already present in the immutable image (such as PHP's built-in stream wrapper) or via an ephemeral external test container attached to `proxy_network`:
+
+   **Method A: Native In-Container PHP Stream Probe (Preferred — Zero Additional Container Overhead):**
    ```bash
-   sudo tar -xzf /opt/lohnour/staging/twentytwentyfive-child.tar.gz -C /var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/themes/
-   sudo tar -xzf /opt/lohnour/staging/lohnour_all_plugins.tar.gz -C /var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/plugins/
+   docker exec lohnour-wordpress php -r '
+   $ctx = stream_context_create(["http" => ["timeout" => 10, "ignore_errors" => true]]);
+   $fp = @fopen("http://127.0.0.1:80/", "r", false, $ctx);
+   if ($fp) {
+       $meta = stream_get_meta_data($fp);
+       echo "Response: " . $meta["wrapper_data"][0] . "\n";
+       fclose($fp);
+   } else {
+       fwrite(STDERR, "FATAL: Smoke test failed. Connection refused or timeout on internal port 80.\n");
+       exit(1);
+   }
+   '
    ```
-3. **Normalize Filesystem Ownership & Permissions:**
+
+   **Method B: Ephemeral Network Probe Container (Alternative):**
    ```bash
-   sudo chown -R 33:33 /var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/
-   sudo find /var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/ -type d -exec chmod 755 {} +
-   sudo find /var/lib/docker/volumes/lohnour_wp_data/_data/wp-content/ -type f -exec chmod 644 {} +
+   docker run --rm --network proxy_network curlimages/curl:8.5.0 \
+     -s -o /dev/null -w "%{http_code}\n" --max-time 10 "http://lohnour-wordpress:80/"
    ```
 
----
+   - *Verification Criteria & Failure Conditions:*
+     - **Target URL:** `http://127.0.0.1:80/` (Method A) or `http://lohnour-wordpress:80/` (Method B).
+     - **Expected HTTP Status:** `HTTP/1.1 200 OK` or `301/302 Redirect` to `https://pro.lohnour.com`.
+     - **Execution Timeout:** 10 seconds.
+     - **Failure Conditions:** Exit code non-zero, timeout (> 10s), connection refused, empty response, or HTTP 5xx error code.
+     - *Prerequisite:* Test is not executable until the selected WordPress container image and probe method are validated under Gate 3.
 
-### Stage 7: PHP Runtime Tuning & Container Reload
-**Estimated Time:** 10 minutes  
-**Target:** Host Terminal
+---
 
-1. **Verify PHP Injected Limits:**
-   ```bash
-   docker exec -it lohnour-wordpress php -i | grep -E 'upload_max_filesize|post_max_size|memory_limit'
-   ```
-   *Expected Output:*
-   - `upload_max_filesize => 512M`
-   - `post_max_size => 512M`
-   - `memory_limit => 512M`
-2. **Restart WordPress Container Gracefully:**
+### Stage 7: Ingress Reverse Proxy Configuration & TLS Setup
+**Classification:** Reversible Configuration | **Governing Gate:** **GATE 4**  
+**Estimated Time:** 20 minutes | **Status:** **HOLD — GATED BY GATE 4 REVERSE PROXY SELECTION**  
+
+*Executed in accordance with Founder-approved reverse proxy architecture (NPM, Caddy, Traefik, Standalone Nginx, or Cloudflare Tunnel).*
+
+1. **Deploy Approved Reverse Proxy Stack:**
+   - Connect proxy to shared bridge network (`proxy_network`).
+2. **Configure Host Routing for `pro.lohnour.com`:**
+   - Set upstream proxy pass to `lohnour-wordpress:80`.
+3. **Issue & Bind TLS Certificate:**
+   - Obtain Let's Encrypt / ZeroSSL certificate.
+4. **Configure Security Headers & Redirection:**
+   - Enforce HTTP-to-HTTPS redirect.
+   - Enforce standard HSTS header:
+     ```nginx
+     add_header Strict-Transport-Security "max-age=31536000" always;
+     ```
+     *(Note: `includeSubDomains` and `preload` remain omitted until domain-wide readiness is verified).*
+   - Enforce `X-Content-Type-Options: nosniff` and `X-Frame-Options: SAMEORIGIN`.
+   - Ensure obsolete `X-XSS-Protection` is omitted.
+5. **Verify Local Port Listeners:**
    ```bash
-   docker restart lohnour-wordpress
+   sudo ss -tulpn | grep -E ':(80|443)'
    ```
 
 ---
 
-### Stage 8: Reverse Proxy Routing & SSL Certificate Issuance
-**Estimated Time:** 15 minutes  
-**Target:** Reverse Proxy Admin Console
-
-1. In Reverse Proxy (Nginx Proxy Manager):
-   - Add Proxy Host:
-     - **Domain Names:** `pro.lohnour.com`
-     - **Scheme:** `http`
-     - **Forward Hostname / IP:** `lohnour-wordpress`
-     - **Forward Port:** `80`
-     - **Options:** Enable *Block Common Exploits*, *Websockets Support*.
-2. In the **SSL** Tab:
-   - Request a new Let's Encrypt Certificate.
-   - Enable: *Force SSL*, *HTTP/2 Support*, *HSTS Enabled*.
-   - Agree to Let's Encrypt Terms of Service.
-   - Click **Save**.
-
----
+### Stage 8: Public DNS Cutover & Production Acceptance
+**Classification:** Reversible Routing / Acceptance | **Governing Gates:** **GATE 8 & GATE 9**  
+**Estimated Time:** 25 minutes | **Target:** DNS Dashboard, Browser, Verification Scripts  
 
-### Stage 9: Comprehensive Deployment Acceptance & Functional Verification
-**Estimated Time:** 25 minutes  
-**Target:** Production Browser & Automated Route Script
-
-1. **Execute Canonical Route Status Test:**
+1. **DNS A-Record Cutover (`GATE 8`):**
+   - In Cloudflare / DNS Provider dashboard:
+   - Update A record for `pro.lohnour.com` to point to Sam's Oracle VM previously verified baseline public IP **`141.148.51.166`** (`PREVIOUSLY VERIFIED`).
+   - *Mandatory Requirement:* Verify the current public IP in OCI Console immediately before DNS cutover; do not assume the address has remained static. Current DNS does NOT point to this IP.
+   - Set low TTL (e.g., 120–300 seconds) during the cutover window.
+2. **Execute Canonical 9-Route Smoke Test (`GATE 9 Acceptance`):**
    Verify HTTP 200 responses across all primary routes:
-   - `/` (Home)
-   - `/about/` (About Us)
-   - `/services/` (Services Catalog Archive)
-   - `/coaching/` (Coaching Bento Grid Hub)
-   - `/coaching/agentic-ai-foundations/` (Program #162)
-   - `/guides/` (User Guides Archive)
-   - `/contact/` (Contact Us)
-   - `/blog/` (Blog Insights Archive)
-2. **Verify WordPress Admin Login Surface:**
-   - Navigate to `https://pro.lohnour.com/wp-login.php`.
-   - Confirm login form renders without UTF-8 BOM corruption or headers-already-sent errors.
+   - Route 1: `/` (Home Page)
+   - Route 2: `/about/` (About Us)
+   - Route 3: `/services/` (Services Catalog Archive)
+   - Route 4: `/coaching/` (Coaching Bento Grid Hub)
+   - Route 5: `/coaching/agentic-ai-foundations/` (Program #162)
+   - Route 6: `/guides/` (User Guides Archive)
+   - Route 7: `/contact/` (Contact Us)
+   - Route 8: `/blog/` (Blog Insights Archive)
+   - Route 9: `/wp-login.php` (Admin Login Surface — verify clean render without BOM corruption)
 3. **Verify Preserved Workloads:**
-   - Confirm Portainer CE continues running normally.
-   - Confirm Node-RED container remains active with zero downtime.
-
----
-
-## 4. Total Effort & Timeline Summary
-
-| Stage | Activity | Estimated Effort |
-| :---: | :--- | :---: |
-| **0** | Host Environment & Baseline Audit | 15 mins |
-| **1** | Portainer Perimeter Hardening (OCI List Update) | 15 mins |
-| **2** | Ingress Network & Reverse Proxy Deployment | 20 mins |
-| **3** | Secure Artifact Transfer & Hash Validation | 20 mins |
-| **4** | Portainer Stack Deployment | 15 mins |
-| **5** | Database Restoration & Schema Check | 15 mins |
-| **6** | File Staging & Permissions Normalization | 20 mins |
-| **7** | PHP Runtime Configuration Verification | 10 mins |
-| **8** | Reverse Proxy Host Setup & SSL Issuance | 15 mins |
-| **9** | Functional Verification & Coexistence Acceptance | 25 mins |
-| **TOTAL** | **Full End-to-End Deployment Effort** | **2 hrs 50 mins (~3.0 Hours)** |
+   - Confirm `Portainer CE` container remains active and healthy.
+   - Confirm `Node-RED` container remains active and healthy.
+   - Confirm named volumes (`portainer_data`, `nodered_data`) are completely intact.
 
 ---
 
-## 5. Execution Hold Point
-
-> [!IMPORTANT]
-> **DEPLOYMENT EXECUTION IS CURRENTLY PAUSED.**  
-> In accordance with Rule 2 and Rule 12, no deployment actions will proceed until the Founder explicitly reviews the audit findings, approves the Portainer perimeter strategy, and grants formal authorization to execute Stage 0.
+## 5. Total Effort & Maintenance Window Sizing
+
+| Stage | Activity | Estimated Effort | Governing Gate | Reversibility |
+| :---: | :--- | :---: | :---: | :---: |
+| **0** | Host Environment & Baseline Verification | 15 mins | GATE 1 | Read-only |
+| **1** | Portainer Perimeter Hardening (`PENDING APPROVAL`) | 15 mins | GATE 2 | Reversible |
+| **2** | Deployment Source & Compose Verification | 15 mins | GATE 5 | Read-only |
+| **3** | Image Compatibility Verification (`BLOCKED`) | 20 mins | GATE 3 | Read-only |
+| **4** | Artifact Staging & Remote Transfer Verification | 20 mins | GATE 6 | Remote Staging (Mutating) |
+| **5** | Isolated DB Restoration & Application Data Staging | 25 mins | GATE 7 | Destructive (Backup secured) |
+| **6** | Application Stack Deployment (Portainer Git) & Validation | 15 mins | GATES 5 & 7 | Reversible Deployment |
+| **7** | Ingress Reverse Proxy Configuration & TLS Setup | 20 mins | GATE 4 | Reversible |
+| **8** | Public DNS Cutover & Production Acceptance | 25 mins | GATES 8 & 9 | Reversible routing |
+| **TOTAL**| **Estimated Maintenance Window Duration** | **~2 hrs 50 mins (ESTIMATED — NOT TESTED)** | — | — |
+
+> [!NOTE]
+> Duration is an engineering estimate for operational planning. No guarantee of deployment duration or zero downtime is claimed without live empirical staging drill runs.
`

---

## File Diff: PRE_DEPLOYMENT_CHECKLIST.md

- **Baseline:** D:\lohnourpro\current source\lohnourpro_wp_theme\my-docs\PRE_DEPLOYMENT_CHECKLIST.md
- **Canonical:** D:\lohnourpro\docs\PRE_DEPLOYMENT_CHECKLIST.md

`diff
diff --git "a/D:\\lohnourpro\\current source\\lohnourpro_wp_theme\\my-docs\\PRE_DEPLOYMENT_CHECKLIST.md" "b/D:\\lohnourpro\\docs\\PRE_DEPLOYMENT_CHECKLIST.md"
index f5a4806..4a8ee76 100644
--- "a/D:\\lohnourpro\\current source\\lohnourpro_wp_theme\\my-docs\\PRE_DEPLOYMENT_CHECKLIST.md"	
+++ "b/D:\\lohnourpro\\docs\\PRE_DEPLOYMENT_CHECKLIST.md"
@@ -5,17 +5,22 @@
 **Auditor / Role:** Senior DevOps Engineer & Infrastructure Security Auditor  
 **Target Environment:** Sam's Oracle VM (Oracle Linux 9.7)  
 **Operating Mode:** STRICT READ-ONLY FORENSICS — ZERO DEPLOYMENT MUTATIONS  
-**Overall Readiness Status:** **GATE PENDING — AWAITING FOUNDER SIGN-OFF**
+**Canonical Documentation Path:** `D:\lohnourpro\docs\PRE_DEPLOYMENT_CHECKLIST.md`  
+*(Note: Duplicate copies existing in `current source/lohnourpro_wp_theme/my-docs/` are historical theme artifacts and remain untouched).*  
+**Overall Readiness Status:** **NO-GO — DOCUMENTATION REVIEW ONLY — DEPLOYMENT NOT APPROVED**
 
 ---
 
 ## 1. Overview & Verification Protocol
 
 This checklist serves as the authoritative operational gate before any deployment command is executed on **Sam's Oracle VM**. Every check is assigned an explicit verification status:
-- `[x] VERIFIED`: Confirmed through read-only forensic inspection.
-- `[ ] PENDING APPROVAL`: Actionable item requiring Founder sign-off.
-- `[ ] PENDING EXECUTION`: Stage action scheduled during deployment runbook.
-- `[!] BLOCKED / UNKNOWN`: Missing technical detail requiring resolution before proceeding.
+- `[x] VERIFIED — CURRENT`: Confirmed through current read-only evidence in this task (e.g. local backup archive presence and hashes).
+- `[?] PREVIOUSLY VERIFIED — RECONFIRM LIVE`: Historical observation from prior server inspection requiring fresh live reconfirmation before cutover.
+- `[R] REPORTED — NOT INDEPENDENTLY VERIFIED`: Information reported in prior notes but lacking direct independent evidence.
+- `[ ] PENDING`: Action scheduled or check awaiting upcoming deployment stage.
+- `[!] BLOCKED`: Critical prerequisite missing or gated; blocks deployment until resolved.
+- `[-] NOT APPLICABLE`: Genuinely irrelevant to the specific stage.
+- `[P] PROPOSED — NOT APPROVED — NOT DEPLOYED`: Architectural proposal, quota model, or procedure submitted for Founder evaluation; NOT approved, tested, or deployed.
 
 ---
 
@@ -23,18 +28,21 @@ This checklist serves as the authoritative operational gate before any deploymen
 
 | Status | Verification Item | Observation / Standard | Evidence Reference |
 | :---: | :--- | :--- | :--- |
-| `[x]` | **Host OS Distribution** | Oracle Linux 9.7 (Enterprise Linux 9 / RHEL 9 compatible) | `INFRASTRUCTURE_AUDIT.md` |
-| `[x]` | **Docker Engine Version** | Docker Engine 29.5.0 installed and running | `INFRASTRUCTURE_AUDIT.md` |
-| `[x]` | **Docker Compose Version**| Docker Compose 5.1.3 installed and accessible | `INFRASTRUCTURE_AUDIT.md` |
-| `[x]` | **Compute Capacity** | 4 OCPU provisioned (Adequate for WP + DB + Node-RED) | Section 5.1 |
-| `[x]` | **Memory Capacity** | 22 GiB usable RAM (Target stack utilizes ~5 GiB, >16 GiB headroom) | Section 5.2 |
-| `[x]` | **Existing Containers** | Exactly 2 active containers (`portainer/portainer-ce:lts`, `nodered/node-red:latest`) | Section 3.1 |
-| `[x]` | **Existing Volumes** | Exactly 2 active named volumes (`portainer_data`, `nodered_data`) | Section 3.1 |
-| `[x]` | **Web Listener Availability**| No listeners on TCP 80 or 443 (Ports are 100% available) | Section 4.1 |
-| `[x]` | **Database Port Cleanliness**| No listener on TCP 3306 on the host (Clean environment) | Section 4.1 |
-| `[!]` | **CPU Architecture Check** | Confirm `uname -m` is `aarch64` (ARM64) vs `x86_64` | Pending Stage 0 |
-| `[!]` | **OS Firewalld Status** | Check if `firewalld` is active and if ports 80/443 need firewalld rules | Pending Stage 0 |
-| `[!]` | **SELinux Enforcing Status** | Check `getenforce` and ensure `:z` flag on bind mounts | Pending Stage 0 |
+| `[?]` | **Host OS Distribution** | Oracle Linux Server 9.7 (Enterprise Linux 9 / RHEL 9 compatible) | `INFRASTRUCTURE_AUDIT.md` (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`) |
+| `[?]` | **Docker Engine Version** | Docker Engine 29.5.0 installed and active | `INFRASTRUCTURE_AUDIT.md` (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`) |
+| `[?]` | **Docker Compose Version**| Docker Compose 5.1.3 installed and active | `INFRASTRUCTURE_AUDIT.md` (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`) |
+| `[?]` | **Compute Capacity** | 4 OCPU provisioned on VM | Section 5.1 (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`) |
+| `[?]` | **Memory Capacity** | ~22 GiB usable RAM | Section 5.2 (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`) |
+| `[?]` | **Existing Containers** | Exactly 2 active containers (`portainer/portainer-ce:lts`, `nodered/node-red:latest`) | Section 3.1 (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`) |
+| `[?]` | **Existing Volumes** | Exactly 2 active named volumes (`portainer_data`, `nodered_data`) | Section 3.1 (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`) |
+| `[?]` | **Web Listener Availability**| No host listeners on TCP 80 or 443 | Section 4.1 (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`) |
+| `[?]` | **Database Port Cleanliness**| No host listener on TCP 3306 (Clean environment) | Section 4.1 (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`) |
+| `[?]` | **Host Storage Baseline** | Root filesystem 30 GB (~16 GB free previously); Docker Root Dir unverified; available space must be remeasured | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` (Stage 0/4) |
+| `[R]` | **OCI Security List Ingress** | Inbound TCP 22, 80, 443, 9443 reported in notes; requires OCI Console confirmation | `REPORTED — NOT INDEPENDENTLY VERIFIED` |
+| `[R]` | **CPU Architecture Check** | Confirm `uname -m` is `aarch64` (ARM64) vs `x86_64` | `REPORTED — NOT INDEPENDENTLY VERIFIED` |
+| `[?]` | **OS Firewalld Status** | Check if `firewalld` is active; changes strictly gated (GATE 2) | `PENDING LIVE VERIFICATION` (Stage 0) |
+| `[?]` | **SELinux Enforcing Status** | Check `getenforce`; relabeling (`:z`) applies ONLY to application bind mounts if enforcing | `PENDING LIVE VERIFICATION` (Stage 0) |
+| `[?]` | **SSH Host Connectivity** | Direct administrative SSH access to Sam's Oracle VM | `PENDING LIVE VERIFICATION` (Stage 0) |
 
 ---
 
@@ -42,43 +50,107 @@ This checklist serves as the authoritative operational gate before any deploymen
 
 | Status | Verification Item | Observation / Standard | Evidence Reference |
 | :---: | :--- | :--- | :--- |
-| `[!]` | **Portainer 9443 Hardening** | Public OCI Security List rule for port 9443 must be revoked | `SECURITY_REVIEW.md` (SEC-01) |
+| `[!]` | **Portainer 9443 Hardening** | Public OCI ingress rule on port 9443 (`REPORTED — NOT INDEPENDENTLY VERIFIED`) must be revoked | `SECURITY_REVIEW.md` (SEC-01) — **GATE 2** |
 | `[ ]` | **SSH Tunneling Setup** | Admin SSH local port forwarding tested (`-L 9443:localhost:9443`) | Stage 1 Runbook |
-| `[x]` | **Database Isolation Design**| MariaDB bound strictly to private internal bridge (`lohnour_db_network`) | `DEPLOYMENT_ARCHITECTURE.md` |
-| `[x]` | **Zero DB Host Binding** | Database `ports:` block omitted entirely from compose specification | `DEPLOYMENT_ARCHITECTURE.md` |
-| `[ ]` | **Secret Cryptographic Gen** | Fresh `DB_ROOT_PASSWORD` and `DB_PASSWORD` generated via `openssl` | Stage 4 Runbook |
-| `[x]` | **Node-RED Perimeter** | Port 1880 blocked at OCI VCN boundary | `SECURITY_REVIEW.md` (SEC-04) |
-| `[x]` | **Workload Non-Interference**| Zero shared networks between WordPress stack and Node-RED/Portainer | Section 5 |
+| `[P]` | **Database Isolation Design**| MariaDB bound strictly to private internal bridge (`lohnour_db_network`) | `DEPLOYMENT_ARCHITECTURE.md` (`PROPOSED — NOT TESTED`) |
+| `[P]` | **Zero DB Host Binding** | Database `ports:` block omitted entirely from compose specification | `DEPLOYMENT_ARCHITECTURE.md` (`PROPOSED`) |
+| `[x]` | **Zero CLI Password Exposure**| Passing `-p<password>` on command line strictly prohibited | `SECURITY_REVIEW.md` (SEC-06) |
+| `[ ]` | **Secure Option File Standard**| Database commands use containerized `/tmp/client.cnf` (mode 0600) via `--defaults-extra-file` with failure-safe trap | Stage 5 Runbook (`PROPOSED — NOT TESTED`) |
+| `[?]` | **Node-RED Perimeter** | Port 1880 blocked at OCI VCN boundary | `SECURITY_REVIEW.md` (SEC-04, `REPORTED — NOT INDEPENDENTLY VERIFIED`) |
+| `[P]` | **Workload Non-Interference**| Zero shared networks between WordPress stack and Node-RED/Portainer | `DEPLOYMENT_ARCHITECTURE.md` (`PROPOSED`) |
 
 ---
 
 ## 4. Authoritative Backup & Artifact Integrity
 
-All artifacts verified in `D:\lohnourpro\_audit\production-extract\`:
+All artifacts verified locally in `D:\lohnourpro\_audit\production-extract\`:
 
-| Status | Verification Item | Verified Checksum (SHA-256) | Integrity Result |
-| :---: | :--- | :--- | :---: |
-| `[x]` | `production-lohnour_wp.sql` | `db8628e7743b133e372265a7d09ac29729ab7f7c8ed9dfcd0bdd257d7e88a880` | **MATCH (100%)** |
-| `[x]` | `uploads.tar.gz` | `5001b5d43159e4611540661d8af50a9fd0301e06a4224587cb38f7f68983b0d9` | **MATCH (100%)** |
-| `[x]` | `twentytwentyfive-child.tar.gz`| `a14af7f749f0e459b60658514e79325a29a3bb7fdc98c56d9591e1faadca802d` | **MATCH (100%)** |
-| `[x]` | `lohnour_all_plugins.tar.gz` | `1e2c4bb948ea06621a52b1911b6d6a29868695798d8e37753454643e0be68484` | **MATCH (100%)** |
-| `[x]` | `lohnour_program_cms.tar.gz` | `b6807f7c19c5cbbfe028c24949fbbe8fca65dc2f8da8be5b7a14ec84869a842b` | **MATCH (100%)** |
-| `[x]` | `config/uploads.ini` | `8f8f90dd0d2ff804e84b72ef35b8cf0f214ceb3664d6dbdb9db5d4a13d7890ec` | **MATCH (100%)** |
-| `[x]` | `config/docker-compose.yml` | `c6ae0df4d16853879d71c4860b0e3dd6860b73cce3892d47da1f2b2b1eb329fb` | **MATCH (100%)** |
-| `[ ]` | **Host Checksum Matching** | Archives verified via `sha256sum -c` after transfer to `/opt/lohnour/` | Pending Stage 3 |
+| Status | Verification Item | Verified Checksum (SHA-256) | Classification & Integrity Result |
+| :---: | :--- | :--- | :--- |
+| `[!]` | `production-lohnour_wp.sql` | `db8628e7743b133e372265a7d09ac29729ab7f7c8ed9dfcd0bdd257d7e88a880` | Local file presence & hash: `VERIFIED — CURRENT`. Schema contents, table count, and SQL compatibility: `PENDING — DUMP INSPECTION REQUIRED` (BLOCKER 7). |
+| `[x]` | `uploads.tar.gz` | `5001b5d43159e4611540661d8af50a9fd0301e06a4224587cb38f7f68983b0d9` | `VERIFIED — CURRENT` Media uploads archive |
+| `[x]` | `twentytwentyfive-child.tar.gz`| `a14af7f749f0e459b60658514e79325a29a3bb7fdc98c56d9591e1faadca802d` | `VERIFIED — CURRENT` Child theme archive |
+| `[x]` | `lohnour_all_plugins.tar.gz` | `1e2c4bb948ea06621a52b1911b6d6a29868695798d8e37753454643e0be68484` | `VERIFIED — CURRENT` Plugins archive |
+| `[x]` | `lohnour_program_cms.tar.gz` | `b6807f7c19c5cbbfe028c24949fbbe8fca65dc2f8da8be5b7a14ec84869a842b` | `VERIFIED — CURRENT` Custom plugin archive |
+| `[x]` | `config/uploads.ini` | `8f8f90dd0d2ff804e84b72ef35b8cf0f214ceb3664d6dbdb9db5d4a13d7890ec` | `VERIFIED — CURRENT` PHP runtime configuration |
+| `[!]` | `config/docker-compose.yml` | `c6ae0df4d16853879d71c4860b0e3dd6860b73cce3892d47da1f2b2b1eb329fb` | **HISTORICAL ARCHIVE — REFERENCE ONLY — NOT APPROVED FOR PRODUCTION** |
+| `[ ]` | **Host Checksum Matching (Gate 6 - Stage 2)** | Archives verified via `sha256sum -c` after transfer to `/opt/lohnour/staging` | `PENDING` (Stage 4) |
+| `[ ]` | **Restore Validation (Gate 6 - Stage 3)** | Archive structure, DB restore, and WordPress files validated in isolated staging (`lohnour_db_staging_data`) | `PENDING (NOT TESTED)` |
+
+> [!CAUTION]
+> The historical archive `_audit/production-extract/config/docker-compose.yml` is classified as:  
+> **`HISTORICAL ARCHIVE — REFERENCE ONLY — NOT APPROVED FOR PRODUCTION`**  
+> - Its existence does not establish production readiness.
+> - It must **NOT** be copied into the active deployment directory.
+> - It must **NOT** be used as the production stack configuration.
+> - It must **NOT** be modified or repurposed as part of this task.
+> - Any future reuse requires a separate technical review, compatibility verification, security review, and explicit Founder approval.
+> - The canonical production Compose path remains **`COMPOSE FILE LOCATION — NOT YET DECIDED`** until an approved configuration is committed to GitHub.
 
 ---
 
 ## 5. Deployment Architecture & Configuration
 
-| Status | Verification Item | Target Specification | Document Reference |
+| Status | Verification Item | Target Specification | Reference / Status |
 | :---: | :--- | :--- | :--- |
-| `[x]` | **Multi-Stack Architecture** | Separate Ingress Proxy Stack (`npm-stack`) & App Stack (`lohnour-production`)| `DEPLOYMENT_ARCHITECTURE.md` |
-| `[x]` | **Docker Network Setup** | `proxy_network` (External Bridge) and `lohnour_db_network` (Internal Bridge) | Section 4 |
-| `[x]` | **Resource Constraints** | Hard limits defined: DB max 2 OCPU / 3 GB; WP max 2 OCPU / 3 GB | Section 6 |
-| `[x]` | **PHP Runtime Limits** | `upload_max_filesize=512M`, `post_max_size=512M`, `memory_limit=512M` | `uploads.ini` |
-| `[x]` | **HTTPS Reverse Proxy Awareness**| `HTTP_X_FORWARDED_PROTO` detection injected into `wp-config.php` | Stage 4 Compose |
-| `[x]` | **File Ownership Standard** | `www-data:www-data` (`UID 33:GID 33`), directories `755`, files `644` | Stage 6 Runbook |
+| `[x]` | **Deployment Branch** | Branch `developer` verified to exist locally and on origin | `VERIFIED — CURRENT` |
+| `[!]` | **Compose File in GitHub** | Canonical production Compose file path is `COMPOSE FILE LOCATION — NOT YET DECIDED` | Must be decided & committed to Git (**GATE 5**) |
+| `[x]` | **Portainer Deployment Mode**| Build method: **Repository** from `developer` branch (Web Editor explicitly rejected) | `DEPLOYMENT_PLAN.md` |
+| `[!]` | **Reverse Proxy Decision** | `PROPOSED — NOT APPROVED` (NPM vs Caddy vs Traefik vs Standalone Nginx vs Cloudflare Tunnel) | Awaiting Founder Selection (**GATE 4**) |
+| `[!]` | **Image Version Pinning** | WordPress & MariaDB image tags must be verified and pinned | **WORDPRESS IMAGE SELECTION: BLOCKED** (**GATE 3**) |
+| `[P]` | **Docker Network Setup** | `proxy_network` (Ingress Bridge) & `lohnour_db_network` (Internal Bridge) | `PROPOSED` |
+| `[P]` | **CPU Limit Model** | Proposed limits: WP 1.00, DB 1.00, Proxy 0.25 = 2.25 OCPU total; nominal unassigned = 1.75 OCPU | `PROPOSED — NOT APPROVED — NOT DEPLOYED` |
+| `[x]` | **PHP Runtime Limits** | `upload_max_filesize=512M`, `post_max_size=512M`, `memory_limit=512M` | `uploads.ini` (`VERIFIED — CURRENT`) |
+| `[P]` | **Safe Rollback Standard** | Destructive volume deletion and DROP DATABASE removed from normal rollback procedures | `BACKUP_AND_ROLLBACK.md` (`PROPOSED`) |
+
+### Docker Volume Isolation & Lifecycle Register
+
+| Status | Volume Name | Target Mount / Scope | Lifecycle & Isolation Policy | Evidence Reference |
+| :---: | :--- | :--- | :--- | :--- |
+| `[?]` | `portainer_data` | `/data` in `portainer-ce` | Existing workload persistent volume. Zero modification. | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` |
+| `[?]` | `nodered_data` | `/data` in `node-red` | Existing workload persistent volume. Zero modification. | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` |
+| `[P]` | `lohnour_wp_data` | `/var/www/html` in `lohnour-wordpress` | Proposed persistent volume for WordPress uploads, child theme, and plugins. | `PROPOSED` |
+| `[!]` | `lohnour_db_data` | `/var/lib/mysql` in `lohnour-db` | Production persistent database volume. Mounted exclusively by production stack. Initialization is destructive and strictly requires separate explicit Founder authorization (**GATE 7**). | `PROPOSED — APPROVAL REQUIRED (GATE 7)` |
+| `[P]` | `lohnour_db_staging_data` | `/var/lib/mysql` in `lohnour-db-staging` | Dedicated staging database volume used exclusively in Gate 6 Stage 3 restore validation drill. Isolated from production; retained post-drill as a **retained staging reference** (not immutable; Docker named volumes possess no cryptographic write-locking). Routine deletion is strictly prohibited; any future removal requires explicit authorization under GATE 10. | `PROPOSED — PENDING VERIFICATION (RETAINED POST-DRILL)` |
+
+### Proposed CPU Allocation Breakdown (`PROPOSED — NOT APPROVED — NOT DEPLOYED`)
+
+| Service | Proposed CPU limit |
+|---|---:|
+| WordPress | 1.00 OCPU |
+| MariaDB | 1.00 OCPU |
+| Reverse proxy (only if selected) | 0.25 OCPU |
+| **Total application CPU limit** | **2.25 OCPU** |
+| **Nominal capacity not assigned to these application limits** | **1.75 OCPU** |
+
+*Contingency (if no reverse proxy is selected):*
+
+| Metric | Value |
+|---|---:|
+| WordPress limit | 1.00 OCPU |
+| MariaDB limit | 1.00 OCPU |
+| Total application CPU limit | 2.00 OCPU |
+| Nominal capacity not assigned to these application limits | 2.00 OCPU |
+
+> [!NOTE]
+> **MANDATORY TECHNICAL CPU RESOURCE CLARIFICATIONS:**
+> 1. **CPU Limits:** Define maximum CPU capacity a container can consume under configured Docker CPU controls (Linux CFS scheduler). They are **not** guaranteed CPU allocations, nor do they imply continuous consumption.
+> 2. **CPU Reservations:** Distinct from limits. No CPU reservations have been verified or approved; **CPU reservations remain unspecified**.
+> 3. **Actual CPU Usage:** Actual runtime CPU consumption has **not** been established by this documentation-only task. Proposed limits must not be presented as measured usage, and unused CPU capacity must not be claimed as dedicated to Portainer or Node-RED.
+> 4. **Existing Services & Contention:** Portainer and Node-RED are already active on the VM. Their actual CPU consumption and configured limits have not been measured by this task. The nominal unassigned capacity (1.75 OCPU, or 2.00 OCPU without proxy) is shared dynamically with existing services and the host operating system. This allocation does **not** guarantee headroom or prevent CPU contention.
+> 5. **Status:** This allocation is strictly **`PROPOSED — NOT APPROVED — NOT DEPLOYED`**.
+
+### WordPress Image Compatibility Verification Protocol (`WORDPRESS IMAGE SELECTION: BLOCKED`)
+
+Until all required evidence has been obtained and reviewed, status is strictly:  
+**`WORDPRESS IMAGE SELECTION: BLOCKED`**
+
+The following verification requirements must be satisfied before image selection:
+- **A. WordPress Core Version:** Identify the exact WordPress core version used by the existing website from the existing source, backup, or authoritative artifact. Do not infer the version from database revision alone.
+- **B. PHP Compatibility:** Establish the PHP version required by the identified core version. Check compatibility with active child theme (`twentytwentyfive-child`) and installed plugins. Verify required PHP extensions. Do not assume PHP 8.2, 8.3, or any other version without evidence.
+- **C. Plugin Compatibility:** Identify all installed plugins and their versions. Verify compatibility with selected core and PHP versions. Identify abandoned, outdated, or incompatible plugins without silent modifications.
+- **D. Database Compatibility:** Establish existing database engine and version. Verify compatibility with selected WP image and application requirements. Record limitations of database revision `61833` (insufficient proof of core compatibility).
+- **E. Image Reproducibility:** Record exact image tag and immutable digest (`image@sha256:...`); do not use `latest`; record architecture/platform compatibility (`linux/arm64` vs `linux/amd64`); require staging restore and functional validation before production approval.
 
 ---
 
@@ -86,38 +158,78 @@ All artifacts verified in `D:\lohnourpro\_audit\production-extract\`:
 
 | Status | Verification Item | Expected Configuration | Notes / Action Required |
 | :---: | :--- | :--- | :--- |
-| `[!]` | **Authoritative Domain Pointing** | A-record for `pro.lohnour.com` pointed to Sam's Oracle VM public IP | Must verify DNS propagation before Let's Encrypt |
+| `[!]` | **Authoritative Domain Pointing** | A-record for `pro.lohnour.com` pointed to Sam's Oracle VM baseline public IP (`141.148.51.166`) | `PENDING LIVE OCI VERIFICATION` (**GATE 8**). Current DNS does NOT point to this IP. |
 | `[!]` | **Cloudflare SSL Encryption Mode** | Must be set to **Full (Strict)** | Prevents SSL redirection loops |
-| `[ ]` | **Let's Encrypt Issuance** | HTTP-01 challenge completion on port 80 via Reverse Proxy | Stage 8 Runbook |
-| `[ ]` | **HTTPS Redirection Enforcement** | HTTP 301 permanent redirect from `http://` to `https://` | Stage 8 Runbook |
-| `[ ]` | **HSTS Security Header** | `Strict-Transport-Security` header active with 1-year duration | `SECURITY_REVIEW.md` |
+| `[ ]` | **Let's Encrypt Issuance** | Automated ACME HTTP-01 challenge completion | Stage 7 Runbook |
+| `[ ]` | **HTTPS Redirection Enforcement** | HTTP 301 permanent redirect from `http://` to `https://` | Stage 7 Runbook |
+| `[!]` | **HSTS Deployment Prerequisite** | HSTS `preload` and `includeSubDomains` must NOT be enabled until all subdomains are verified HTTPS | Security Standard |
 
 ---
 
-## 7. Founder Sign-Off & Approval Gates
+## 7. Explicit Operational Approval Gates (Canonical 10-Gate Register)
 
-The following decisions require explicit sign-off from the Founder prior to deployment:
+Every destructive database restore, backup replacement, volume operation, firewall change, OCI change, DNS change, and production cutover is separately approval-gated under the unified 10-gate model:
 
 ```
-[ ] GATE 1: PERIMETER SECURITY STRATEGY
-    Founder approves removing Portainer port 9443 from public OCI Security List
-    and adopting SSH local port forwarding (https://localhost:9443) for management.
+[ ] GATE 1: Host Baseline & Capacity Verification
+    Read-only verification of host architecture (uname -m), usable memory (22 GiB),
+    CPU capacity (4 OCPU), and existing workloads (Portainer, Node-RED).
+    Sign-off: ________________________  Date: ____________
+
+[ ] GATE 2: Management-Plane & Perimeter Security Approval
+    Founder approval for OCI Security List changes (revoking public 9443),
+    SSH tunneling (https://localhost:9443), and host firewalld configuration.
+    Sign-off: ________________________  Date: ____________
+
+[ ] GATE 3: WordPress & Database Image Compatibility Verification
+    Verification of WordPress core, PHP, plugin, and database compatibility
+    across all 8 evidence criteria (A-E). Status remains:
+    WORDPRESS IMAGE SELECTION: BLOCKED until satisfied.
+    Sign-off: ________________________  Date: ____________
+
+[ ] GATE 4: Ingress Reverse Proxy Selection & Approval
+    Founder selection and approval of the ingress reverse proxy solution
+    (NPM / Caddy / Traefik / Standalone Nginx / Cloudflare Tunnel).
     Sign-off: ________________________  Date: ____________
 
-[ ] GATE 2: DEPLOYMENT ARTIFACT SOURCE CONFIRMATION
-    Founder approves deploying the verified canonical production extract baseline
-    (D:\lohnourpro\_audit\production-extract\) to guarantee 100% exact parity with
-    the previous production environment.
+[ ] GATE 5: Canonical Compose Path & Git Repository Verification
+    Founder approval of the canonical production compose file committed to
+    the developer branch. Path remains COMPOSE FILE LOCATION — NOT YET DECIDED
+    until committed. (Historical compose in _audit is classified as
+    HISTORICAL ARCHIVE — REFERENCE ONLY — NOT APPROVED FOR PRODUCTION).
     Sign-off: ________________________  Date: ____________
 
-[ ] GATE 3: PRODUCTION DOMAIN & CUTOVER TIMING
-    Founder approves DNS pointing of pro.lohnour.com to Sam's Oracle VM and authorizes
-    the 3-hour deployment maintenance window.
+[ ] GATE 6: Backup Integrity & Restore Readiness Verification
+    Stage 1: Local backup archive SHA-256 checksums verified 100% (VERIFIED — CURRENT).
+             SQL dump schema compatibility remains PENDING — DUMP INSPECTION REQUIRED (BLOCKER 7).
+    Stage 2: Remote transfer integrity verified on host (PENDING).
+    Stage 3: Restore validation drill in isolated staging environment using
+             dedicated staging volume lohnour_db_staging_data (retained post-drill;
+             routine volume deletion prohibited) (PENDING — NOT TESTED).
     Sign-off: ________________________  Date: ____________
 
-[ ] GATE 4: PRODUCTION SECRETS GENERATION
-    Founder authorizes automated generation of fresh cryptographic credentials for
-    MariaDB root and application users.
+[ ] GATE 7: Database Restoration & Secrets Injection Approval
+    Founder approval for destructive production database restore into lohnour_db_data
+    and fresh production secrets injection. Executed via proposed containerized
+    option file with failure-safe trap. Staging data is never restored into production.
+    Sign-off: ________________________  Date: ____________
+
+[ ] GATE 8: DNS Modification & Routing Changes Approval
+    Founder approval for DNS A-record pointing of pro.lohnour.com
+    to Sam's Oracle VM previously verified baseline public IP (141.148.51.166).
+    (Requires live reconfirmation in OCI Console immediately before cutover;
+    current DNS does NOT point to this IP).
+    Sign-off: ________________________  Date: ____________
+
+[ ] GATE 9: Production Cutover & Maintenance Window Approval
+    Founder authorization for the estimated 2h 50m deployment maintenance
+    window and final acceptance testing.
+    Sign-off: ________________________  Date: ____________
+
+[ ] GATE 10: Volume Deletion & Emergency Reset Operations Approval
+    Founder approval for any destructive Docker volume deletion (docker volume rm),
+    including any eventual decommissioning of lohnour_db_staging_data.
+    Strictly restricted to emergency decommissioning; forbidden during standard rollback.
     Sign-off: ________________________  Date: ____________
 ```
 
@@ -125,21 +237,18 @@ The following decisions require explicit sign-off from the Founder prior to depl
 
 ## 8. Post-Deployment Acceptance Verification Criteria
 
-Following execution of the deployment runbook, the deployment engineer and Founder must verify:
-
 - `[ ]` `lohnour-wordpress` container status is `Up` with zero crash loops.
 - `[ ]` `lohnour-db` container status is `Up (healthy)`.
-- `[ ]` `lohnour-reverse-proxy` container status is `Up` and bound to ports 80 and 443.
+- `[ ]` Reverse proxy container status is `Up` and bound to ports 80 and 443.
 - `[ ]` Portainer CE continues running normally on `portainer_data`.
 - `[ ]` Node-RED continues running normally on `nodered_data`.
-- `[ ]` Route `/` (Home) loads cleanly with HTTP 200, full 9 sections, and testimonial slider.
-- `[ ]` Route `/about/` (About Us) loads cleanly with HTTP 200 and founder profile.
-- `[ ]` Route `/services/` loads cleanly with HTTP 200 and pathway cards.
-- `[ ]` Route `/coaching/` loads cleanly with HTTP 200 and bento grid.
-- `[ ]` Route `/coaching/agentic-ai-foundations/` (Program #162) loads all 12 modules.
-- `[ ]` Route `/guides/` loads cleanly with HTTP 200.
-- `[ ]` Route `/contact/` loads cleanly with HTTP 200 and inquiry form.
-- `[ ]` Route `/blog/` loads cleanly with HTTP 200.
-- `[ ]` Route `/wp-login.php` renders cleanly with zero headers-already-sent PHP warnings.
-- `[ ]` SSL certificate is valid and issued by Let's Encrypt.
-- `[ ]` Automated daily backup cron job is registered and tested.
+- `[ ]` Route 1: `/` (Home) loads cleanly with HTTP 200, full 9 sections, and testimonial slider.
+- `[ ]` Route 2: `/about/` (About Us) loads cleanly with HTTP 200 and founder profile.
+- `[ ]` Route 3: `/services/` loads cleanly with HTTP 200 and pathway cards.
+- `[ ]` Route 4: `/coaching/` loads cleanly with HTTP 200 and bento grid.
+- `[ ]` Route 5: `/coaching/agentic-ai-foundations/` (Program #162) loads all 12 modules.
+- `[ ]` Route 6: `/guides/` loads cleanly with HTTP 200.
+- `[ ]` Route 7: `/contact/` loads cleanly with HTTP 200 and inquiry form.
+- `[ ]` Route 8: `/blog/` loads cleanly with HTTP 200.
+- `[ ]` Route 9: `/wp-login.php` renders cleanly with zero headers-already-sent PHP warnings.
+- `[ ]` SSL certificate is valid and issued by Let's Encrypt / ZeroSSL.
`

---

## File Diff: BACKUP_AND_ROLLBACK.md

- **Baseline:** D:\lohnourpro\current source\lohnourpro_wp_theme\my-docs\BACKUP_AND_ROLLBACK.md
- **Canonical:** D:\lohnourpro\docs\BACKUP_AND_ROLLBACK.md

`diff
diff --git "a/D:\\lohnourpro\\current source\\lohnourpro_wp_theme\\my-docs\\BACKUP_AND_ROLLBACK.md" "b/D:\\lohnourpro\\docs\\BACKUP_AND_ROLLBACK.md"
index 8f547b7..a24b230 100644
--- "a/D:\\lohnourpro\\current source\\lohnourpro_wp_theme\\my-docs\\BACKUP_AND_ROLLBACK.md"	
+++ "b/D:\\lohnourpro\\docs\\BACKUP_AND_ROLLBACK.md"
@@ -5,6 +5,8 @@
 **Auditor / Role:** Senior DevOps Engineer & Infrastructure Security Auditor  
 **Target Environment:** Sam's Oracle VM (Oracle Linux 9.7)  
 **Operating Mode:** STRICT READ-ONLY FORENSICS — ZERO DEPLOYMENT MUTATIONS  
+**Canonical Documentation Path:** `D:\lohnourpro\docs\BACKUP_AND_ROLLBACK.md`  
+*(Note: Duplicate copies existing in `current source/lohnourpro_wp_theme/my-docs/` are historical theme artifacts and remain untouched).*  
 **Status:** **AUTHORITATIVE BASELINE VERIFIED — PROCEDURES DEFINED**
 
 ---
@@ -13,10 +15,30 @@
 
 This document specifies the authoritative backup baseline, production backup automation, and step-by-step rollback runbooks for **Lohnour Pro** on **Sam's Oracle VM**.
 
-To prevent data loss, ensure rapid recovery, and maintain business continuity, this strategy establishes:
-1. **A Cryptographically Verified Baseline:** 100% verified backup artifacts from the canonical production extract (`D:\lohnourpro\_audit\production-extract\`).
-2. **A Four-Tier Backup Architecture:** Encompassing local baselines, automated daily host backups, off-site OCI Object Storage replication, and OCI block volume snapshots.
-3. **Deterministic Rollback Runbooks:** Granular, non-destructive restoration procedures for application, database, and catastrophic disaster scenarios, engineered to guarantee zero disruption to existing workloads (`Node-RED` and `Portainer CE`).
+### Core Data Safety Principles:
+1. **Preservation of Existing & Newly Created Data:** The default rollback procedure is designed to preserve persistent Docker volumes (`lohnour_wp_data`, `lohnour_db_data`). Reverting application code does not automatically reverse database mutations.
+2. **Strict Separation of Application Rollback vs Data Rollback:** Application errors are resolved by reverting container versions, Git commits on the `developer` branch, or configuration files while leaving database records and media files intact.
+3. **Staging vs Production Storage Isolation & Retention Policy (BLOCKER 1 & C RESOLUTION):**
+   - Staging restore drills (Gate 6 — Stage 3) must strictly utilize dedicated staging volume `lohnour_db_staging_data`.
+   - Staging tests must **NEVER** mount, reference, or restore data into the production database volume `lohnour_db_data`.
+   - **Routine Deletion Forbidden:** Routine deletion of `lohnour_db_staging_data` via `docker volume rm` is strictly removed from standard restore-drill procedures.
+   - **Retention & Purpose:** Staging volume `lohnour_db_staging_data` is classified as a **`retained staging reference`** following a successful validation drill. It must NOT be described as immutable, as Docker named volumes possess no native cryptographic write-locking or immutability enforcement mechanism.
+   - **Ownership & Storage:** Staging volume data is owned natively by the MariaDB process (`UID 999:GID 999`) and consumes ~25–50 MB within the host's Docker Root Dir (which must be budgeted against host disk limits).
+   - **Deletion Governance:** Any future decommissioning or removal of `lohnour_db_staging_data` requires explicit authorization under **GATE 10** (`Volume Deletion & Emergency Reset Operations Approval`).
+   - Production database restoration into `lohnour_db_data` is a separate, destructive operation requiring explicit Founder authorization under **GATE 7**.
+4. **Distinct Disaster Recovery Procedures & Atomic Backup Set (BLOCKER 9 & B RESOLUTION):**
+   - **Atomic Backup Set Rule:** The production database dump and WordPress media uploads archive constitute a single, indivisible backup set. If either required component fails, is missing, or fails integrity verification, the entire backup set is invalid and the process must abort with an error exit code. A successful run must NEVER be reported when a required component is missing.
+   - **Manifest Generation:** Every backup run must generate a cryptographic checksum manifest identifying all required components in the backup set.
+   - **Retention Pruning Gate:** Pruning of historical backups occurs strictly AFTER every required component in the current backup set passes all integrity tests.
+   - **Filesystem Modification:** Creating backup destination directories (`mkdir -p`) is an intentional host filesystem modification and must not be described as read-only.
+   - **`--single-transaction` Limitation:** Operators must recognize that `--single-transaction` provides ACID snapshot consistency only for transactional InnoDB tables; it does not lock nontransactional tables (MyISAM, MEMORY) or prevent schema inconsistencies from concurrent DDL statements.
+   - Disaster recovery operations are categorized into four distinct, non-interchangeable procedures with independent statuses:
+     - **Procedure 1: Backup Creation** (Capturing transactional database dump and media archives — `PROPOSED REFERENCE SPECIFICATION — NOT TESTED`).
+     - **Procedure 2: Backup Integrity Verification** (Cryptographic SHA-256 matching, non-empty size validation, manifest generation, and archive decompression tests `gzip -t` / `tar -tzf` — `PROPOSED REFERENCE SPECIFICATION — NOT TESTED`).
+     - **Procedure 3: Restore Validation Drill** (Dry-run import into isolated staging container and volume `lohnour_db_staging_data` under Gate 6 Stage 3 — `PENDING VERIFICATION`).
+     - **Procedure 4: Production Rollback** (Operational reversion of container versions/commits under Runbook A, or destructive database point-in-time recovery into `lohnour_db_data` under Gate 7 Runbook B — `PROPOSED — NOT TESTED IN PRODUCTION`).
+5. **Zero Credential Exposure:** Database administrative commands must never pass plaintext passwords in command-line flags. All scripts must utilize secured client option files (`--defaults-extra-file` with mode `0600`).
+6. **Untested Status & No Guarantees:** All rollback runbooks and scripts represent proposed reference specifications and have **NOT BEEN TESTED IN PRODUCTION**. Empirical drill validation in an isolated staging environment is required before production deployment. No claims of guaranteed recovery, guaranteed rollback, or 100% non-destructive execution are made.
 
 ---
 
@@ -32,34 +54,58 @@ Algorithm: SHA-256
 Location: D:\lohnourpro\_audit\production-extract\
 ```
 
-| Component | Path / Filename | Size (Bytes) | SHA-256 Checksum | Verified Contents |
+| Component | Path / Filename | Size (Bytes) | SHA-256 Checksum | Verified Contents & Status |
 | :--- | :--- | :---: | :--- | :--- |
-| **Database Dump** | `database/production-lohnour_wp.sql` | 290,627 | `db8628e7743b133e372265a7d09ac29729ab7f7c8ed9dfcd0bdd257d7e88a880` | Complete 14-table MariaDB schema, 79 published posts/pages, Program #162 postmeta, taxonomy terms, options table. |
-| **Media Uploads** | `uploads.tar.gz` | 59,733,182 | `5001b5d43159e4611540661d8af50a9fd0301e06a4224587cb38f7f68983b0d9` | 113 production media files across `wp-content/uploads/` (including 2026/07 and 2026/10 assets, branding logos, wallpapers). |
-| **Child Theme** | `twentytwentyfive-child.tar.gz` | 6,229,809 | `a14af7f749f0e459b60658514e79325a29a3bb7fdc98c56d9591e1faadca802d` | Complete child theme filesystem (all PHP page templates, custom CSS, JS scripts, images, and fonts). |
-| **Plugin Suite** | `lohnour_all_plugins.tar.gz` | 5,646,919 | `1e2c4bb948ea06621a52b1911b6d6a29868695798d8e37753454643e0be68484` | All installed plugins: `akismet`, `all-in-one-wp-migration`, `lohnour-program-cms`, and `mailin` (Brevo integration). |
-| **Program CMS** | `lohnour_program_cms.tar.gz` | 18,777 | `b6807f7c19c5cbbfe028c24949fbbe8fca65dc2f8da8be5b7a14ec84869a842b` | Standalone archive of the custom Program CMS custom post type plugin. |
-| **Docker Compose**| `config/docker-compose.yml` | 1,545 | `c6ae0df4d16853879d71c4860b0e3dd6860b73cce3892d47da1f2b2b1eb329fb` | Legacy production stack definition. |
-| **PHP Config** | `config/uploads.ini` | 119 | `8f8f90dd0d2ff804e84b72ef35b8cf0f214ceb3664d6dbdb9db5d4a13d7890ec` | Production runtime limits (512M upload/memory limits, 600s execution). |
-| **WP Config** | `config/wp-config.redacted.php` | 5,922 | `c53a6704ebf9a3ea77c15e8e8334f68a2bf6cb8e3678fe0168be80608796853c` | Redacted production `wp-config.php` including proxy SSL detection logic. |
+| **Database Dump** | `database/production-lohnour_wp.sql` | 290,627 | `db8628e7743b133e372265a7d09ac29729ab7f7c8ed9dfcd0bdd257d7e88a880` | Local file presence and SHA-256 hash verified. Schema contents, table count, and SQL compatibility: **PENDING — DUMP INSPECTION REQUIRED** (BLOCKER 7). |
+| **Media Uploads** | `uploads.tar.gz` | 59,733,182 | `5001b5d43159e4611540661d8af50a9fd0301e06a4224587cb38f7f68983b0d9` | Local file presence and SHA-256 hash verified (`VERIFIED — CURRENT`). Detailed extraction contents unverified in production. |
+| **Child Theme** | `twentytwentyfive-child.tar.gz` | 6,229,809 | `a14af7f749f0e459b60658514e79325a29a3bb7fdc98c56d9591e1faadca802d` | Local file presence and SHA-256 hash verified (`VERIFIED — CURRENT`). Complete child theme archive. |
+| **Plugin Suite** | `lohnour_all_plugins.tar.gz` | 5,646,919 | `1e2c4bb948ea06621a52b1911b6d6a29868695798d8e37753454643e0be68484` | Local file presence and SHA-256 hash verified (`VERIFIED — CURRENT`). All plugin files. |
+| **Program CMS** | `lohnour_program_cms.tar.gz` | 18,777 | `b6807f7c19c5cbbfe028c24949fbbe8fca65dc2f8da8be5b7a14ec84869a842b` | Local file presence and SHA-256 hash verified (`VERIFIED — CURRENT`). Standalone custom plugin. |
+| **PHP Config** | `config/uploads.ini` | 119 | `8f8f90dd0d2ff804e84b72ef35b8cf0f214ceb3664d6dbdb9db5d4a13d7890ec` | Local file presence and SHA-256 hash verified (`VERIFIED — CURRENT`). Runtime limits. |
+| **WP Config** | `config/wp-config.redacted.php` | 5,922 | `c53a6704ebf9a3ea77c15e8e8334f68a2bf6cb8e3678fe0168be80608796853c` | Local file presence and SHA-256 hash verified (`VERIFIED — CURRENT`). Redacted configuration. |
+| **Historical Compose**| `config/docker-compose.yml` | 1,545 | `c6ae0df4d16853879d71c4860b0e3dd6860b73cce3892d47da1f2b2b1eb329fb` | **HISTORICAL ARCHIVE — REFERENCE ONLY — NOT APPROVED FOR PRODUCTION** |
+
+### 2.1 Mandatory Pre-Restore SQL Dump Inspection Protocol (BLOCKER 7 RESOLUTION)
 
 > [!IMPORTANT]
-> **LOCATION VERIFICATION:**
-> These archives are **NOT** stored on GitHub. They are **NOT** present on Sam's Oracle VM. They must remain preserved on the local admin workstation and transferred only via authenticated SCP during deployment.
+> **UNVERIFIED SQL COMPATIBILITY (STATUS: `PENDING — DUMP INSPECTION REQUIRED`):**  
+> The database dump has not been inspected for MariaDB compatibility or schema contents during this documentation round. Do **NOT** assert that the dump contains 14 tables or uses `wp_posts` until supported by direct dump inspection. Do **NOT** execute an import. Before any staging restore drill (Gate 6 Stage 3) is attempted, the SQL dump file must be inspected across the following 9 verification criteria:
+> 1. **`CREATE DATABASE` statements:** Verify the dump does not contain unapproved `CREATE DATABASE` queries.
+> 2. **`USE` statements:** Verify no hardcoded `USE` queries override target database `lohnour_wp`.
+> 3. **`DEFINER` clauses:** Inspect for hardcoded definers (e.g. `root@localhost`) on views, stored routines, or triggers.
+> 4. **Database & Table references:** Ensure all references resolve to the target database and unprivileged user.
+> 5. **Table prefixes:** Verify actual table prefixes (e.g. `wp_`) without assuming.
+> 6. **SQL compatibility:** Check syntax, character sets (utf8mb4), and collations against target MariaDB engine.
+> 7. **Required privileges & grants:** Verify import can be executed by unprivileged `lohnour_user` without `SUPER` privileges.
+> 8. **Unexpected objects:** Verify absence of unintended user accounts, triggers, or event scheduler entries.
+> 9. **Dump completeness:** Verify dump terminates cleanly with complete EOF marker.
+
+> [!NOTE]
+> **GATE 6 MULTI-STAGE RECOVERY READINESS:**
+> Local cryptographic verification of backup artifacts confirms **Stage 1 (Local Archive Integrity — `VERIFIED`)**. It does NOT establish that archives have been successfully transferred to the target host or that data can be cleanly restored into the target container environment. **Stage 2 (Remote Transfer Integrity)** and **Stage 3 (Restore Validation in Isolated Staging)** remain pending operational execution and verification.
+
+> [!WARNING]
+> **REUSE RESTRICTION & CLASSIFICATION:**
+> The historical Compose file `_audit/production-extract/config/docker-compose.yml` is classified as:  
+> **`HISTORICAL ARCHIVE — REFERENCE ONLY — NOT APPROVED FOR PRODUCTION`**  
+> - Its existence does not establish production readiness.
+> - It must **NOT** be copied into the active deployment directory.
+> - It must **NOT** be used as the production stack configuration.
+> - It must **NOT** be modified or repurposed as part of this task.
+> - Any future reuse requires a separate technical review, compatibility verification, security review, and explicit Founder approval.
+> - The production Compose path remains **`COMPOSE FILE LOCATION — NOT YET DECIDED`** pending authoring, review, and commit on GitHub's `developer` branch.
 
 ---
 
-## 3. Four-Tier Backup Architecture
-
-Following deployment, a production-grade backup regimen must be established on Sam's Oracle VM:
+## 3. Production Backup Architecture
 
 ```mermaid
 graph TD
-    subgraph Tier1["Tier 1: Cold Baseline Backup"]
+    subgraph Tier1["Tier 1: Cold Baseline Backup (VERIFIED)"]
         ExtractArchive["D:\lohnourpro\_audit\production-extract\<br>(Local Cold Storage, Write-Protected)"]
     end
 
-    subgraph Tier2["Tier 2: Daily Automated Host Backup"]
+    subgraph Tier2["Tier 2: Daily Automated Host Backup (PROPOSED)"]
         HostCron["Daily Cron (/opt/lohnour/scripts/backup.sh)"]
         DBSnapshot["/opt/backups/db/lohnour_wp_YYYYMMDD.sql.gz"]
         MediaSnapshot["/opt/backups/media/uploads_YYYYMMDD.tar.gz"]
@@ -67,70 +113,210 @@ graph TD
         HostCron --> MediaSnapshot
     end
 
-    subgraph Tier3["Tier 3: Off-Host Replication"]
-        OCI_ObjectStorage["OCI Object Storage Bucket<br>(Encrypted, Versioned, Retention Policy)"]
+    subgraph Tier3["Tier 3: Off-Host Replication (PROPOSED ENHANCEMENT)"]
+        OCI_ObjectStorage["OCI Object Storage Bucket<br>(Encrypted, Versioned)"]
         RcloneSync["Automated Rclone / OCI CLI Sync"]
         DBSnapshot --> RcloneSync
         MediaSnapshot --> RcloneSync
         RcloneSync --> OCI_ObjectStorage
     end
 
-    subgraph Tier4["Tier 4: Infrastructure Disaster Snapshots"]
+    subgraph Tier4["Tier 4: Infrastructure Snapshots (PROPOSED ENHANCEMENT)"]
         OCI_Console["OCI Block Volume Backup Policy"]
-        BootVolSnapshot["Daily Crash-Consistent Boot Volume Snapshot"]
+        BootVolSnapshot["Daily Boot Volume Snapshot"]
         OCI_Console --> BootVolSnapshot
     end
 ```
 
-### 3.1 Tier 1: Local Cold Baseline
+### 3.1 Tier 1: Local Cold Baseline (`VERIFIED`)
 - Maintained permanently in `D:\lohnourpro\_audit\production-extract\`.
-- Acts as the immutable recovery baseline if host-level backups are corrupted.
+- Acts as the offline recovery baseline if host-level backups are corrupted.
+
+### 3.2 Tier 2: Automated Daily Host Backup Reference Script
+
+> [!WARNING]
+> **OPERATIONAL STATUS: `PROPOSED REFERENCE SPECIFICATION — NOT TESTED IN PRODUCTION — AUTOMATED SCHEDULING (CRON/TIMERS) DISABLED`**  
+> This script is a proposed reference specification for daily host-level backups. Automated scheduling via `cron` or systemd timers is strictly disabled until the script is empirically validated in an isolated staging test environment and explicitly authorized by the Founder.
+> 
+> *Key Operational & Failure Handling Rules (BLOCKER B RESOLUTION):*
+> 1. **Filesystem Modification Disclosure:** Executing `mkdir -p` to create backup directories constitutes an intentional host filesystem mutation and must not be described as read-only.
+> 2. **Preflight Failure Abort:** If credential files are missing, the database container is stopped, or destination storage has less than 1 GB free space, the script aborts immediately with an error exit code.
+> 3. **Bounded Database Readiness Probe:** Before initiating `mariadb-dump`, a bounded database readiness probe (`mariadb-admin ping` with a 20-second timeout) must confirm MariaDB is ready to accept queries.
+> 4. **Transactional Dump Scope & Non-Transactional Limitations:** The `--single-transaction` flag guarantees consistent, non-blocking snapshots for transactional InnoDB tables. However, it does **not** protect non-transactional tables (e.g. MyISAM or MEMORY tables, if present) or concurrent DDL operations (`ALTER TABLE`), which can lead to data inconsistency during active writes.
+> 5. **Atomic Backup Set Mandate:** The database dump and WordPress media uploads archive constitute a single, indivisible backup set. If either required component fails, is missing, or fails integrity verification, the backup must exit unsuccessfully (`exit 1`). A successful backup must **NEVER** be reported when a required component is missing.
+> 6. **Cryptographic Manifest Generation:** A SHA-256 checksum manifest (`manifest_${DATE}.sha256`) identifying all required backup set components must be generated upon successful completion.
+> 7. **Integrity Validation Before Retention Pruning:** Existing historical backups are pruned strictly AFTER every required component of the current backup set passes all integrity verification tests.
+
+*Proposed Script Dependencies (Pending Live Host Verification):*
+- Database container name: assumed as `lohnour-db` (proposed Compose service alias / container name).
+- Host credential file: `/etc/lohnour/db_backup.cnf` (file mode `0600`; credentials injected securely at runtime, never committed to Git).
+- Storage filesystem: `/opt/backups` must have at least 1 GB (1,048,576 KB) free disk space.
 
-### 3.2 Tier 2: Daily Automated Host Backups
-A non-blocking cron job executing daily at 02:00 UTC on Sam's Oracle VM:
 ```bash
 #!/usr/bin/env bash
 set -euo pipefail
 
 BACKUP_DIR="/opt/backups"
 DATE=$(date +%Y%m%d_%H%M%S)
+DB_CNF="/etc/lohnour/db_backup.cnf"
+DB_CONTAINER="lohnour-db"
+MIN_FREE_KB=1048576 # 1 GB minimum free disk space
+
+echo "[$(date -Iseconds)] Starting Lohnour Tier 2 atomic backup run..."
+
+# --- PREFLIGHT CHECKS & FILESYSTEM MUTATION ---
+# 1. Host Filesystem Modification: create backup destination structure
 mkdir -p "${BACKUP_DIR}/db" "${BACKUP_DIR}/media"
 
-# 1. Consistent Database Dump (Non-locking InnoDB dump)
-docker exec lohnour-db mariadb-dump \
-  -u lohnour_user -p"${DB_PASSWORD}" \
+# 2. Verify client option file exists and is restricted to 0600
+if [ ! -f "${DB_CNF}" ]; then
+  echo "FATAL: Database credential file ${DB_CNF} not found. Aborting backup." >&2
+  exit 1
+fi
+CNF_PERMS=$(stat -c "%a" "${DB_CNF}" 2>/dev/null || stat -f "%OLp" "${DB_CNF}" 2>/dev/null || echo "000")
+if [ "${CNF_PERMS}" != "600" ]; then
+  echo "FATAL: Insecure permissions (${CNF_PERMS}) on ${DB_CNF}. Must be 0600. Aborting." >&2
+  exit 1
+fi
+
+# 3. Verify target database container is running
+DB_RUNNING=$(docker inspect -f '{{.State.Running}}' "${DB_CONTAINER}" 2>/dev/null || echo "false")
+if [ "${DB_RUNNING}" != "true" ]; then
+  echo "FATAL: Container ${DB_CONTAINER} is not running. Aborting database backup." >&2
+  exit 1
+fi
+
+# 4. Verify destination filesystem has sufficient free space (> 1 GB)
+AVAIL_KB=$(df -k --output=avail "${BACKUP_DIR}" | tail -n 1 | tr -d ' ')
+if [ "${AVAIL_KB}" -lt "${MIN_FREE_KB}" ]; then
+  echo "FATAL: Insufficient storage space on ${BACKUP_DIR} (${AVAIL_KB} KB available, required ${MIN_FREE_KB} KB). Aborting." >&2
+  exit 1
+fi
+
+# 5. Set failure-safe trap to guarantee container option file cleanup on any exit
+trap 'docker exec "${DB_CONTAINER}" rm -f /tmp/db_backup.cnf 2>/dev/null || true' EXIT INT TERM
+
+# 6. Copy option file to container and set strict permissions
+docker cp "${DB_CNF}" "${DB_CONTAINER}:/tmp/db_backup.cnf"
+docker exec "${DB_CONTAINER}" chmod 600 /tmp/db_backup.cnf
+
+# 7. Bounded Database Readiness Probe (10 attempts, 2-second interval)
+PING_SUCCESS=0
+for i in {1..10}; do
+  if docker exec "${DB_CONTAINER}" mariadb-admin --defaults-extra-file=/tmp/db_backup.cnf ping --silent 2>/dev/null; then
+    PING_SUCCESS=1
+    break
+  fi
+  sleep 2
+done
+
+if [ "${PING_SUCCESS}" -ne 1 ]; then
+  echo "FATAL: Database failed bounded readiness probe before dump. Aborting." >&2
+  exit 1
+fi
+
+# --- COMPONENT 1: CONSISTENT DATABASE DUMP & INTEGRITY VALIDATION ---
+DB_DUMP_FILE="${BACKUP_DIR}/db/lohnour_wp_${DATE}.sql.gz"
+
+echo "[$(date -Iseconds)] Executing consistent database dump via mariadb-dump..."
+# Note: --single-transaction ensures ACID snapshot consistency for InnoDB tables only.
+if ! docker exec "${DB_CONTAINER}" mariadb-dump \
+  --defaults-extra-file=/tmp/db_backup.cnf \
   --single-transaction \
   --quick \
   --routines \
   --triggers \
-  lohnour_wp | gzip -9 > "${BACKUP_DIR}/db/lohnour_wp_${DATE}.sql.gz"
-
-# 2. Incremental Media Uploads Archive
-tar -czf "${BACKUP_DIR}/media/uploads_${DATE}.tar.gz" \
-  -C /var/lib/docker/volumes/lohnour_wp_data/_data/wp-content uploads
-
-# 3. Retention Cleanup (Keep 7 daily backups locally)
+  lohnour_wp | gzip -9 > "${DB_DUMP_FILE}"; then
+  echo "FATAL: mariadb-dump pipeline failed. Purging incomplete dump." >&2
+  rm -f "${DB_DUMP_FILE}"
+  exit 1
+fi
+
+# Cleanup option file from container immediately
+docker exec "${DB_CONTAINER}" rm -f /tmp/db_backup.cnf 2>/dev/null || true
+trap - EXIT INT TERM
+
+# Validate that database dump file is non-empty (> 1000 bytes)
+if [ ! -s "${DB_DUMP_FILE}" ] || [ "$(stat -c %s "${DB_DUMP_FILE}" 2>/dev/null || stat -f %z "${DB_DUMP_FILE}" 2>/dev/null || echo 0)" -lt 1000 ]; then
+  echo "FATAL: Database dump file is empty or suspiciously small. Aborting." >&2
+  rm -f "${DB_DUMP_FILE}"
+  exit 1
+fi
+
+# Validate gzip archive integrity
+if ! gzip -t "${DB_DUMP_FILE}"; then
+  echo "FATAL: Database dump archive failed gzip integrity test. Corrupted dump purged." >&2
+  rm -f "${DB_DUMP_FILE}"
+  exit 1
+fi
+echo "[$(date -Iseconds)] Component 1 (Database) successfully verified: ${DB_DUMP_FILE}"
+
+# --- COMPONENT 2: FULL MEDIA UPLOADS ARCHIVE & INTEGRITY VALIDATION ---
+# Media uploads are a mandatory component of the atomic backup set
+MEDIA_ARCHIVE_FILE="${BACKUP_DIR}/media/uploads_${DATE}.tar.gz"
+WP_DATA_DIR=$(docker volume inspect lohnour_wp_data --format '{{ .Mountpoint }}' 2>/dev/null || echo "")
+
+if [ -z "${WP_DATA_DIR}" ] || [ ! -d "${WP_DATA_DIR}/wp-content/uploads" ]; then
+  echo "FATAL: WordPress uploads directory missing or volume unmounted. Media backup is mandatory. Aborting and purging incomplete backup set." >&2
+  rm -f "${DB_DUMP_FILE}"
+  exit 1
+fi
+
+echo "[$(date -Iseconds)] Creating media archive from ${WP_DATA_DIR}/wp-content/uploads..."
+if ! tar -czf "${MEDIA_ARCHIVE_FILE}" -C "${WP_DATA_DIR}/wp-content" uploads; then
+  echo "FATAL: Media archive creation failed. Purging incomplete backup set." >&2
+  rm -f "${DB_DUMP_FILE}" "${MEDIA_ARCHIVE_FILE}"
+  exit 1
+fi
+
+# Validate tar archive integrity
+if ! tar -tzf "${MEDIA_ARCHIVE_FILE}" >/dev/null 2>&1; then
+  echo "FATAL: Media archive corrupted; failed tar integrity test. Purging incomplete backup set." >&2
+  rm -f "${DB_DUMP_FILE}" "${MEDIA_ARCHIVE_FILE}"
+  exit 1
+fi
+echo "[$(date -Iseconds)] Component 2 (Media) successfully verified: ${MEDIA_ARCHIVE_FILE}"
+
+# --- COMPONENT 3: GENERATE UNIFIED BACKUP SET MANIFEST ---
+MANIFEST_FILE="${BACKUP_DIR}/manifest_${DATE}.sha256"
+echo "[$(date -Iseconds)] Generating unified backup set manifest: ${MANIFEST_FILE}..."
+(
+  cd "${BACKUP_DIR}"
+  sha256sum "db/lohnour_wp_${DATE}.sql.gz" "media/uploads_${DATE}.tar.gz" > "${MANIFEST_FILE}"
+)
+
+if [ ! -s "${MANIFEST_FILE}" ]; then
+  echo "FATAL: Failed to generate backup set manifest. Purging incomplete backup set." >&2
+  rm -f "${DB_DUMP_FILE}" "${MEDIA_ARCHIVE_FILE}" "${MANIFEST_FILE}"
+  exit 1
+fi
+echo "[$(date -Iseconds)] Backup set manifest verified: ${MANIFEST_FILE}"
+
+# --- COMPONENT 4: RETENTION POLICY PRUNING (7 DAYS) ---
+# Retention pruning executes ONLY after EVERY required component in the current backup set passes all integrity tests
 find "${BACKUP_DIR}/db" -type f -name "*.sql.gz" -mtime +7 -delete
 find "${BACKUP_DIR}/media" -type f -name "*.tar.gz" -mtime +7 -delete
-```
+find "${BACKUP_DIR}" -maxdepth 1 -type f -name "manifest_*.sha256" -mtime +7 -delete
 
-### 3.3 Tier 3: Off-Host Cloud Replication
-- Syncs `/opt/backups/` to an **OCI Object Storage Bucket** using the OCI CLI or Rclone.
-- Lifecycle rules: 30-day retention on standard storage, transition to Archive Storage thereafter.
+echo "[$(date -Iseconds)] Tier 2 atomic backup set (Database + Media + Manifest) completed and verified successfully."
+```
 
-### 3.4 Tier 4: OCI Block Volume Backup Policy
-- Configured in OCI Console for the VM's boot volume:
-  - Policy: **Silver Policy** (Daily incremental backups retained for 30 days, weekly backups retained for 12 weeks).
-  - Provides bare-metal VM recovery within minutes in case of catastrophic OS corruption.
+### 3.3 Tiers 3 & 4: Off-Host & Cloud Snapshots (`PROPOSED POST-DEPLOYMENT ENHANCEMENTS`)
+- **Tier 3 (OCI Object Storage):** Proposed automated sync of `/opt/backups/` to an OCI Object Storage bucket.
+- **Tier 4 (OCI Block Volume Policy):** Proposed crash-consistent boot volume daily snapshots.
+- *Status:* **PROPOSED POST-DEPLOYMENT ENHANCEMENTS — NOT PART OF INITIAL DEPLOYMENT SCOPE**.
 
 ---
 
 ## 4. Recovery Objectives
 
-| Metric | Target Standard | Operational Meaning |
-| :--- | :---: | :--- |
-| **RPO (Recovery Point Objective)** | **< 24 Hours** | Maximum potential data loss limited to changes made since the 02:00 UTC daily backup. (0 hours for pre-deployment baseline). |
-| **RTO (Recovery Time Objective)** | **< 30 Minutes** | Time required to fully redeploy the stack and restore data from an existing backup archive. |
+| Metric | Target Standard | Operational Meaning | Status |
+| :--- | :---: | :--- | :---: |
+| **RPO (Recovery Point Objective)** | **< 24 Hours** | Maximum potential data loss limited to changes made since the daily backup. (0 hours for pre-deployment baseline). | `TARGET — NOT YET VALIDATED` |
+| **RTO (Recovery Time Objective)** | **< 30 Minutes** | Estimated time required to redeploy the stack and restore data from an existing verified backup archive. | `TARGET — NOT YET VALIDATED` |
+
+> [!NOTE]
+> Recovery metrics represent target operational goals for post-deployment automated operations. They are **NOT** guaranteed or verified until end-to-end disaster recovery drill testing is conducted.
 
 ---
 
@@ -138,100 +324,130 @@ find "${BACKUP_DIR}/media" -type f -name "*.tar.gz" -mtime +7 -delete
 
 ```mermaid
 graph TD
-    Trigger["Incident Detected / Deployment Failure"]
+    Trigger["Incident Detected / Operational Failure"]
     
     Trigger --> Decision{"Determine Incident Scope"}
     
-    Decision -->|Stack Failure / Container Crash| RunbookA["Runbook A: Application Stack Rollback<br>(Teardown and redeploy containers)"]
-    Decision -->|Database Corruption / Bad Import| RunbookB["Runbook B: Database Point-in-Time Restore<br>(Re-import authoritative SQL dump)"]
-    Decision -->|Catastrophic Post-Cutover Failure| RunbookC["Runbook C: Emergency DNS Cutback<br>(Revert DNS to previous host / Clean VM)"]
+    Decision -->|Application Error / Bad Commit| RunbookA["Runbook A: Application Rollback (Safe)<br>Revert Git commit / Redeploy stack<br>(VOLUMES & DATABASE PRESERVED)"]
+    Decision -->|Data Corruption / Bad Record Edit| RunbookB["Runbook B: Database Point-in-Time Restore<br>Restore data from verified SQL dump<br>(DESTRUCTIVE: REQUIRES GATE 7 APPROVAL)"]
+    Decision -->|Host Failure / Emergency Abort| RunbookC["Runbook C: Emergency DNS Cutback<br>Subject to verification of previous host"]
 ```
 
 ---
 
-### Runbook A: Application Stack Rollback
-**Trigger:** WordPress or MariaDB container enters a crash loop, reverse proxy port binding conflict, or container startup failure.  
-**Impact on Existing Workloads:** **ZERO**. Only `lohnour-production` and `npm-stack` are touched. `Node-RED` and `Portainer` remain untouched.
-
-1. **Stop and Remove Problematic Application Stack:**
-   In Portainer UI:
-   - Navigate to **Stacks -> `lohnour-production` -> Stop -> Remove Stack**.
-   *Or via Host CLI:*
-   ```bash
-   docker stop lohnour-wordpress lohnour-db || true
-   docker rm lohnour-wordpress lohnour-db || true
-   ```
-2. **Inspect Host State:**
-   ```bash
-   docker ps
-   ```
-   Confirm `portainer` and `nodered` are active and unaffected.
-3. **Inspect Docker Volumes:**
-   ```bash
-   docker volume ls
-   ```
-   Named volumes (`lohnour_wp_data`, `lohnour_db_data`) remain preserved for investigation or can be purged if recreation is required:
-   ```bash
-   docker volume rm lohnour_wp_data lohnour_db_data
-   ```
-4. **Re-evaluate Compose Syntax & Resource Allocations.**
+### Runbook A: Safe Application Rollback (Default Procedure)
+**Trigger:** Application bug, theme template error, failed plugin update, or container startup failure.  
+**Operational Status:** `PROPOSED REFERENCE WORKFLOW — NOT TESTED IN PRODUCTION`.  
+**Scope & Operational Impact:** Designed to preserve persistent volume data (`lohnour_wp_data`, `lohnour_db_data`). However, application rollback does not reverse database schema migrations or data mutations that occurred while the application was active. Restoring application files and restoring database tables are separate operations that must be individually evaluated. Compatibility between the reverted application codebase and current database state must be verified prior to resuming traffic. A compatible, verified database backup must always be captured immediately prior to executing any production application upgrade, code update, or schema alteration. Rollback requires an explicit operational decision and Founder approval.
+
+> [!CAUTION]
+> **VOLUME PRESERVATION MANDATE:**  
+> `docker volume rm` must **NEVER** be executed during a standard application rollback. Newly created customer data and uploads must be preserved. Volume deletion operations are strictly restricted to emergency decommissioning under **GATE 10** (`Volume Deletion & Emergency Reset Operations Approval`).
+
+1. **Revert Configuration / Theme Code in Git:**
+   - In local workspace on branch `developer`:
+     ```bash
+     git revert <offending_commit_hash>
+     git push origin developer
+     ```
+2. **Redeploy Stack via Portainer:**
+   - Open Portainer UI (`https://localhost:9443` via SSH Tunnel).
+   - Navigate to **Stacks -> `lohnour-production`**.
+   - Click **Update the stack** -> Enable **Re-pull image and redeploy** -> Click **Update**.
+3. **If Container Is Stuck / Unresponsive:**
+   - Restart the application container gracefully:
+     ```bash
+     docker restart lohnour-wordpress
+     ```
+4. **Verify Application Health:**
+   - Check container logs for fatal errors:
+     ```bash
+     docker logs --tail 100 lohnour-wordpress
+     ```
+   - Confirm Portainer CE and Node-RED remain unaffected.
 
 ---
 
 ### Runbook B: Database Point-in-Time Restoration
-**Trigger:** Database data corruption, accidental table drop, or failed post-restore data modification.  
-**Execution Time:** ~5 minutes.
+**Trigger:** Severe database corruption, accidental table truncation, or malicious SQL injection.  
+**Classification:** `DESTRUCTIVE — REQUIRES SEPARATE EXPLICIT APPROVAL (GATE 7)`.  
+**Operational Status:** `PROPOSED — NOT TESTED IN PRODUCTION`.  
+**Storage Volume Isolation:** Staging database restore verification drills (Gate 6 Stage 3) strictly utilize dedicated staging volume `lohnour_db_staging_data` to ensure zero mutation or collision with the production database volume `lohnour_db_data`. Production database restoration into `lohnour_db_data` is a separate, destructive operation governed under Gate 7.
 
-1. **Stop Web Traffic to Prevent Inconsistent Writes:**
+> [!CAUTION]
+> **DESTRUCTIVE OPERATION WARNING:**  
+> This runbook overwrites existing database tables. It must **ONLY** be executed after obtaining separate explicit Founder approval under **Gate 7** (`Database Restoration & Secrets Injection Approval`) and verifying that a secondary pre-restoration database dump has been captured.
+
+1. **Staging Credential Setup with Failure-Safe Cleanup (`PROPOSED — NOT TESTED`):**
    ```bash
-   docker stop lohnour-wordpress
+   # Set failure-safe trap to guarantee option file cleanup upon any script exit
+   trap 'docker exec lohnour-db rm -f /tmp/client.cnf; rm -f /opt/lohnour/staging/client.cnf' EXIT
+
+   cat << 'EOF' > /opt/lohnour/staging/client.cnf
+   [client]
+   user=lohnour_user
+   password=<PLACEHOLDER — NOT A REAL CREDENTIAL>
+   EOF
+   chmod 600 /opt/lohnour/staging/client.cnf
+   docker cp /opt/lohnour/staging/client.cnf lohnour-db:/tmp/client.cnf
+   docker exec lohnour-db chmod 600 /tmp/client.cnf
    ```
-2. **Drop and Recreate Clean Database Schema:**
+2. **Capture Pre-Restoration Snapshot (Forensic Preservation):**
    ```bash
-   docker exec -i lohnour-db mariadb -u root -p"${DB_ROOT_PASSWORD}" -e "
-     DROP DATABASE IF EXISTS lohnour_wp;
-     CREATE DATABASE lohnour_wp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-     GRANT ALL PRIVILEGES ON lohnour_wp.* TO 'lohnour_user'@'%';
-     FLUSH PRIVILEGES;
-   "
+   docker exec lohnour-db mariadb-dump \
+     --defaults-extra-file=/tmp/client.cnf \
+     lohnour_wp | gzip > /opt/lohnour/staging/db_pre_rollback_$(date +%s).sql.gz
    ```
-3. **Re-import Authoritative SQL Dump:**
+3. **Stop Web Traffic to Prevent Inconsistent Writes:**
    ```bash
-   docker exec -i lohnour-db mariadb -u lohnour_user -p"${DB_PASSWORD}" lohnour_wp < /opt/lohnour/staging/production-lohnour_wp.sql
+   docker stop lohnour-wordpress
    ```
-4. **Restart WordPress Application:**
+4. **Execute Table Re-Import:**
    ```bash
-   docker start lohnour-wordpress
+   docker exec -i lohnour-db mariadb \
+     --defaults-extra-file=/tmp/client.cnf \
+     lohnour_wp < /opt/lohnour/staging/production-lohnour_wp.sql
+   ```
+5. **Verify Database Table Count & Revision (Before Option File Purge):**
+   ```bash
+   docker exec -i lohnour-db mariadb \
+     --defaults-extra-file=/tmp/client.cnf \
+     -e "SHOW TABLES; SELECT count(*) FROM wp_posts;" lohnour_wp
    ```
-5. **Verify Database Integrity:**
+6. **Purge Option File & Restart Container:**
    ```bash
-   docker exec -it lohnour-db mariadb -u lohnour_user -p"${DB_PASSWORD}" lohnour_wp -e "SELECT count(*) FROM wp_posts;"
+   docker exec lohnour-db rm -f /tmp/client.cnf
+   rm -f /opt/lohnour/staging/client.cnf
+   trap - EXIT
+   docker start lohnour-wordpress
    ```
-   Confirm post count matches the expected pre-deployment count (79 posts/pages/CPTs).
+
+> [!WARNING]
+> **ERASURE LIMITATION & TESTING STATUS:**
+> While `rm` unlinks the file from filesystem directory structures, it does **NOT** guarantee physical erasure from container writable layers, underlying copy-on-write (CoW) storage drivers (overlay2), host block storage, or Docker volume backups. This pattern is strictly **`PROPOSED — NOT TESTED`** and must be validated in an isolated staging test environment before production use.
 
 ---
 
 ### Runbook C: Emergency Post-Cutover Rollback to Old Infrastructure
-**Trigger:** Severe, unresolvable production failure on Sam's Oracle VM after public DNS cutover.  
-**Objective:** Restore live public traffic to the previous production host (`193.122.206.31`) with zero downtime.
+**Trigger:** Unresolvable production failure on Sam's Oracle VM after public DNS cutover.  
+**Requirement:** **VERIFICATION OF TARGET AVAILABILITY REQUIRED**.
+
+> [!IMPORTANT]
+> **TARGET AVAILABILITY CAVEAT:**  
+> Reverting DNS to previous host (`193.122.206.31`) assumes that the previous host remains powered on, properly configured, and accessible. This is **UNKNOWN / UNVERIFIED** without current live telemetry.
 
-1. **Immediate DNS Reversion:**
+1. **Verify Target Availability:**
+   - Confirm previous host `193.122.206.31` responds to HTTP/HTTPS requests before modifying DNS.
+2. **Execute DNS Reversion:**
    - In Cloudflare / DNS Provider dashboard:
    - Edit the A record for `pro.lohnour.com`:
-     - Change IP from Sam's Oracle VM IP back to `193.122.206.31`.
+     - Point IP back to previous production IP (`193.122.206.31`).
      - Set TTL to 120 seconds.
-2. **Flush Local & Public DNS Caches:**
-   ```bash
-   dig +short pro.lohnour.com
-   ```
-   Verify DNS resolves back to `193.122.206.31`.
 3. **Deactivate Ingress Proxy on Sam's Oracle VM:**
-   ```bash
-   docker stop lohnour-reverse-proxy
-   ```
+   - Stop reverse proxy container to prevent split-brain processing.
 4. **Preserve Forensic Logs on Sam's Oracle VM:**
    ```bash
-   docker logs lohnour-wordpress > /opt/lohnour/staging/wp_crash.log 2>&1
-   docker logs lohnour-db > /opt/lohnour/staging/db_crash.log 2>&1
+   docker logs lohnour-wordpress > /opt/lohnour/staging/wp_incident.log 2>&1
+   docker logs lohnour-db > /opt/lohnour/staging/db_incident.log 2>&1
    ```
-5. **Post-Mortem Review:** Investigate container logs before attempting redeployment.
+5. **Post-Mortem Review:** Investigate logs before attempting redeployment.
`

---

