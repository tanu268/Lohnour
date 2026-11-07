# LOHNOUR PRO — PHASE 9 PRODUCTION INFRASTRUCTURE & VOLUME PREPARATION REPORT

**Canonical Acceptance Authority:** Principal DevOps Engineer, Senior Linux Systems Engineer, Docker Infrastructure Architect, Production Reliability Engineer, Security Auditor  
**Execution Date:** November 7, 2026  
**Execution Timezone:** UTC (`05:14:41Z`) / Local IST (`10:44:41+05:30`)  
**Final Status:** `PHASE_9_CLOSED — PRODUCTION_INFRASTRUCTURE_VERIFIED`  
**Target Host:** Sam's Oracle VM (`141.148.51.166`, Oracle Linux Server 9.7 ARM64, `sams-oracle-vm`)  
**Preceding Verified Baseline:** Phase 8 Backup (`/opt/lohnour/backups/phase8_20261107_044630`, SHA256 verified 100%)  
**Execution Boundary:** Controlled Production Infrastructure Provisioning. ZERO container deployments, ZERO database restorations, ZERO archive extractions, ZERO Git commits or staging (`git add` prohibited).

---

## 1. EXECUTIVE SUMMARY & FORMAL CLOSURE

Phase 9 of the Lohnour Pro deployment initiative — **Production Infrastructure & Docker Volume/Network Preparation** — has been **successfully executed, independently verified, and formally closed** on Sam's live Oracle Linux VM.

All four required production Docker resources were created with exact canonical specifications, verified via `docker inspect` telemetry, confirmed accessible on the host filesystem, and audited for zero container attachments and zero IPAM subnet collisions:

1. **Volume `lohnour_wp_data`:** Successfully created (`driver: local`, `scope: local`). Mountpoint `/var/lib/docker/volumes/lohnour_wp_data/_data` verified accessible with `0755 root:root` permissions and confirmed 100% empty skeleton (`total 0`, 0 bytes seeded).
2. **Volume `lohnour_db_data`:** Successfully created (`driver: local`, `scope: local`). Mountpoint `/var/lib/docker/volumes/lohnour_db_data/_data` verified accessible with `0755 root:root` permissions and confirmed 100% empty skeleton (`total 0`, 0 bytes seeded).
3. **Network `proxy_network`:** Successfully created standard bridge (`driver: bridge`, `scope: local`, `internal: false`). Assigned non-overlapping subnet `172.18.0.0/16` with gateway `172.18.0.1`. Containers map confirmed empty (`Containers: {}`).
4. **Network `lohnour_db_network`:** Successfully created internal bridge (`driver: bridge`, `scope: local`, `internal: true`). Assigned non-overlapping subnet `172.19.0.0/16` with gateway `172.19.0.1`. Strict WAN/external isolation enforced (`Internal: true`). Containers map confirmed empty (`Containers: {}`).

### Workload & System Integrity
- **Portainer (`portainer`):** Continuously running (`Up 30+ minutes`), responding `HTTP 200` on `https://localhost:9443`.
- **Node-RED (`nodered`):** Continuously running and healthy (`Up 5 months (healthy)`), responding `HTTP 200` on `http://localhost:1880`.
- **Phase 8 Backup:** `/opt/lohnour/backups/phase8_20261107_044630` re-verified with 100% cryptographic checksum validity via `sha256sum -c SHA256SUMS`.
- **Scope Integrity:** Strictly zero application containers running (no MariaDB, no WordPress, no Caddy); zero database schemas or tables initialized; zero uploads, themes, or plugin archives extracted; zero DNS or firewall rules manually altered; zero Git commits or staging created.

```
========================================================================================
FINAL PHASE 9 CLOSURE STATUS: PHASE_9_CLOSED — PRODUCTION_INFRASTRUCTURE_VERIFIED
MANDATORY STOP: Do NOT execute Phase 10 without explicit user authorization.
========================================================================================
```

---

## 2. PRE-CHANGE PRODUCTION ENVIRONMENT BASELINE

Prior to initiating any resource creation, the target host was subjected to read-only preflight forensics:

### 2.1 Host & Compute Telemetry
```
Hostname:        sams-oracle-vm
Kernel:          Linux sams-oracle-vm 6.12.0-201.74.2.2.el9uek.aarch64 #1 SMP Thu Apr 30 16:38:02 PDT 2026 aarch64
Operating Sys:   Oracle Linux Server 9.7 (UEK 9.7)
Architecture:    aarch64 (ARM64)
Public IPv4:     141.148.51.166
Private IPv4:    10.0.0.100/24 (interface enp0s6)
System Uptime:   Up 179 days, 12 hours
Load Average:    0.00, 0.00, 0.00
Storage Root:    16,296 MB Available (46% used of 30 GB on /dev/mapper/ocivolume-root)
Inodes Root:     15,241,060 Free (2% used of 15,466,496)
```

### 2.2 Baseline Workload Status
```
NAMES       STATUS                  PORTS
nodered     Up 5 months (healthy)   0.0.0.0:1880->1880/tcp, [::]:1880->1880/tcp
portainer   Up 28 minutes           0.0.0.0:8000->8000/tcp, [::]:8000->8000/tcp, 0.0.0.0:9443->9443/tcp, [::]:9443->9443/tcp, 9000/tcp
```
- Portainer Endpoint Check: `curl -k -s -o /dev/null -w "%{http_code}" https://localhost:9443` -> `200`
- Node-RED Endpoint Check: `curl -s -o /dev/null -w "%{http_code}" http://localhost:1880` -> `200`

### 2.3 Phase 8 Recovery Baseline Verification
The Phase 8 recovery point was validated prior to resource creation:
```bash
sudo bash -c 'cd /opt/lohnour/backups/phase8_20261107_044630 && sha256sum -c SHA256SUMS'
```
```
./firewalld_active_summary.txt: OK
./firewalld_default_zone.txt: OK
./firewalld_direct_rules.txt: OK
./firewalld_permanent_zones.txt: OK
./firewalld_runtime_zones.txt: OK
./ip6tables.rules: OK
./iptables.rules: OK
./nodered_data_20261107_044630.tar.gz: OK
./nodered_inspect.json: OK
./portainer_data_20261107_044630.tar.gz: OK
./portainer_inspect.json: OK
```
Result: 11/11 payload files verified 100% cryptographically intact.

### 2.4 Pre-Creation Resource Classification
```
Resource Name         Pre-Creation Classification
-------------------   ---------------------------
lohnour_wp_data       ABSENT (Verified via docker volume ls)
lohnour_db_data       ABSENT (Verified via docker volume ls)
proxy_network         ABSENT (Verified via docker network ls)
lohnour_db_network    ABSENT (Verified via docker network ls)
```

---

## 3. PRODUCTION RESOURCE CREATION OPERATIONS

The four required production Docker resources were created sequentially on Sam's Oracle VM. Zero secrets were passed or exposed.

### 3.1 Executed Commands
```bash
# 1. Create WordPress application data volume
sudo docker volume create lohnour_wp_data

# 2. Create MariaDB database data volume
sudo docker volume create lohnour_db_data

# 3. Create standard proxy bridge network
sudo docker network create proxy_network

# 4. Create internal-only database bridge network
sudo docker network create lohnour_db_network --internal
```

### 3.2 Command Execution Telemetry
```
Command 1: sudo docker volume create lohnour_wp_data
Output:    lohnour_wp_data
Exit Code: 0

Command 2: sudo docker volume create lohnour_db_data
Output:    lohnour_db_data
Exit Code: 0

Command 3: sudo docker network create proxy_network
Output:    5c22411adcdc6079e3322be88749ec3a7cced42f137aced36fdc4970f8714323
Exit Code: 0

Command 4: sudo docker network create lohnour_db_network --internal
Output:    78c5f5622d18a904271f3fbf2de42267280c2f5adc66fefc421cf3f4a0a218f6
Exit Code: 0
```

---

## 4. INDEPENDENT POST-CREATION TELEMETRY & VERIFICATION

### 4.1 Volume Telemetry: `lohnour_wp_data`
```bash
sudo docker volume inspect lohnour_wp_data
```
```json
[
    {
        "CreatedAt": "2026-11-07T05:14:29Z",
        "Driver": "local",
        "Labels": null,
        "Mountpoint": "/var/lib/docker/volumes/lohnour_wp_data/_data",
        "Name": "lohnour_wp_data",
        "Options": null,
        "Scope": "local"
    }
]
```
Mountpoint Verification:
```bash
sudo ls -la /var/lib/docker/volumes/lohnour_wp_data/_data
```
```
total 0
drwxr-xr-x. 2 root root  6 Nov  7 05:14 .
drwx-----x. 3 root root 19 Nov  7 05:14 ..
```
- **Filesystem Path:** `/var/lib/docker/volumes/lohnour_wp_data/_data`
- **Permissions:** `0755` (`drwxr-xr-x`)
- **Ownership:** `root:root`
- **Content:** Empty skeleton (`total 0`, 0 entries). Verified zero files or archives extracted.

---

### 4.2 Volume Telemetry: `lohnour_db_data`
```bash
sudo docker volume inspect lohnour_db_data
```
```json
[
    {
        "CreatedAt": "2026-11-07T05:14:29Z",
        "Driver": "local",
        "Labels": null,
        "Mountpoint": "/var/lib/docker/volumes/lohnour_db_data/_data",
        "Name": "lohnour_db_data",
        "Options": null,
        "Scope": "local"
    }
]
```
Mountpoint Verification:
```bash
sudo ls -la /var/lib/docker/volumes/lohnour_db_data/_data
```
```
total 0
drwxr-xr-x. 2 root root  6 Nov  7 05:14 .
drwx-----x. 3 root root 19 Nov  7 05:14 ..
```
- **Filesystem Path:** `/var/lib/docker/volumes/lohnour_db_data/_data`
- **Permissions:** `0755` (`drwxr-xr-x`)
- **Ownership:** `root:root`
- **Content:** Empty skeleton (`total 0`, 0 entries). Verified zero database tables initialized or imported.

---

### 4.3 Network Telemetry: `proxy_network`
```bash
sudo docker network inspect proxy_network
```
```json
[
    {
        "Name": "proxy_network",
        "Id": "5c22411adcdc6079e3322be88749ec3a7cced42f137aced36fdc4970f8714323",
        "Created": "2026-11-07T05:14:40.855591233Z",
        "Scope": "local",
        "Driver": "bridge",
        "EnableIPv4": true,
        "EnableIPv6": false,
        "IPAM": {
            "Driver": "default",
            "Options": {},
            "Config": [
                {
                    "Subnet": "172.18.0.0/16",
                    "Gateway": "172.18.0.1"
                }
            ]
        },
        "Internal": false,
        "Attachable": false,
        "Ingress": false,
        "ConfigFrom": {
            "Network": ""
        },
        "ConfigOnly": false,
        "Options": {},
        "Labels": {},
        "Containers": {},
        "Status": {
            "IPAM": {
                "Subnets": {
                    "172.18.0.0/16": {
                        "IPsInUse": 3,
                        "DynamicIPsAvailable": 65533
                    }
                }
            }
        }
    }
]
```
- **Network ID:** `5c22411adcdc6079e3322be88749ec3a7cced42f137aced36fdc4970f8714323`
- **Driver:** `bridge`
- **Subnet:** `172.18.0.0/16`
- **Gateway:** `172.18.0.1`
- **Internal Flag:** `false` (Standard bridge allowing ingress reverse proxy connectivity to WordPress)
- **Container Attachments:** `Containers: {}` (Zero containers attached)

---

### 4.4 Network Telemetry: `lohnour_db_network`
```bash
sudo docker network inspect lohnour_db_network
```
```json
[
    {
        "Name": "lohnour_db_network",
        "Id": "78c5f5622d18a904271f3fbf2de42267280c2f5adc66fefc421cf3f4a0a218f6",
        "Created": "2026-11-07T05:14:41.072100779Z",
        "Scope": "local",
        "Driver": "bridge",
        "EnableIPv4": true,
        "EnableIPv6": false,
        "IPAM": {
            "Driver": "default",
            "Options": {},
            "Config": [
                {
                    "Subnet": "172.19.0.0/16",
                    "Gateway": "172.19.0.1"
                }
            ]
        },
        "Internal": true,
        "Attachable": false,
        "Ingress": false,
        "ConfigFrom": {
            "Network": ""
        },
        "ConfigOnly": false,
        "Options": {},
        "Labels": {},
        "Containers": {},
        "Status": {
            "IPAM": {
                "Subnets": {
                    "172.19.0.0/16": {
                        "IPsInUse": 3,
                        "DynamicIPsAvailable": 65533
                    }
                }
            }
        }
    }
]
```
- **Network ID:** `78c5f5622d18a904271f3fbf2de42267280c2f5adc66fefc421cf3f4a0a218f6`
- **Driver:** `bridge`
- **Subnet:** `172.19.0.0/16`
- **Gateway:** `172.19.0.1`
- **Internal Flag:** `true` (CRITICAL: Enforces strict isolated datastore boundary with zero external or host-routed egress/ingress)
- **Container Attachments:** `Containers: {}` (Zero containers attached)

---

## 5. IPAM & SUBNET NON-OVERLAP AUDIT

Docker's local IPAM manager allocated subnets sequentially without conflicting with host networks or default Docker interfaces:

| Interface / Network | Subnet / CIDR | Gateway / Local IP | Type / Scope | Collision Status |
| :--- | :--- | :--- | :--- | :---: |
| `enp0s6` (Host Physical) | `10.0.0.0/24` | `10.0.0.100` | OCI VCN Private Subnet | **CLEAN (No overlap)** |
| `lo` (Host Loopback) | `127.0.0.0/8` | `127.0.0.1` | Host Local Loopback | **CLEAN (No overlap)** |
| `docker0` (Default Bridge) | `172.17.0.0/16` | `172.17.0.1` | Docker Default Bridge | **CLEAN (No overlap)** |
| `br-5c22411adcdc` (`proxy_network`) | `172.18.0.0/16` | `172.18.0.1` | User Bridge (External Ingress) | **CLEAN (No overlap)** |
| `br-78c5f5622d18` (`lohnour_db_network`) | `172.19.0.0/16` | `172.19.0.1` | User Bridge (`internal: true`) | **CLEAN (No overlap)** |

### Kernel Routing Table Verification
```
default via 10.0.0.1 dev enp0s6 proto dhcp src 10.0.0.100 metric 100 
10.0.0.0/24 dev enp0s6 proto kernel scope link src 10.0.0.100 metric 100 
169.254.0.0/16 dev enp0s6 proto dhcp scope link src 10.0.0.100 metric 100 
172.17.0.0/16 dev docker0 proto kernel scope link src 172.17.0.1 
172.18.0.0/16 dev br-5c22411adcdc proto kernel scope link src 172.18.0.1 linkdown 
172.19.0.0/16 dev br-78c5f5622d18 proto kernel scope link src 172.19.0.1 linkdown 
```
Both bridge interfaces are down/linkdown as expected since no active container endpoints are connected.

---

## 6. WORKLOAD AND STORAGE HEALTH VERIFICATION

### 6.1 Docker Disk Consumption
```bash
sudo docker system df
```
```
TYPE            TOTAL     ACTIVE    SIZE      RECLAIMABLE
Images          2         2         1.143GB   0B (0%)
Containers      2         2         0B        0B
Local Volumes   4         2         310kB     0B (0%)
Build Cache     0         0         0B        0B
```
- Total Volumes: 4 (`portainer_data`, `nodered_data`, `lohnour_wp_data`, `lohnour_db_data`)
- Active Volumes: 2 (`portainer_data`, `nodered_data`)
- Inactive Empty Volumes: 2 (`lohnour_wp_data`, `lohnour_db_data`)
- Reclaimable Disk Space: 0B (No orphaned resources or dangling layers created)

### 6.2 Host Workload Telemetry Post-Creation
```bash
sudo docker ps --format 'table {{.Names}}\t{{.Status}}\t{{.Ports}}'
```
```
NAMES       STATUS                  PORTS
nodered     Up 5 months (healthy)   0.0.0.0:1880->1880/tcp, [::]:1880->1880/tcp
portainer   Up 30 minutes           0.0.0.0:8000->8000/tcp, [::]:8000->8000/tcp, 0.0.0.0:9443->9443/tcp, [::]:9443->9443/tcp, 9000/tcp
```
- `curl -k -s -o /dev/null -w "%{http_code}" https://localhost:9443` -> `200 OK`
- `curl -s -o /dev/null -w "%{http_code}" http://localhost:1880` -> `200 OK`

---

## 7. SCOPE BOUNDARY ADHERENCE MATRIX

| Boundary Constraint | Status | Evidence / Verification |
| :--- | :---: | :--- |
| **No MariaDB / WordPress / Caddy Containers** | **ENFORCED** | Exactly 2 containers active on host (`portainer`, `nodered`). Zero new containers started. |
| **No Database Schema or Table Restoration** | **ENFORCED** | `lohnour_db_data/_data` confirmed 100% empty (`total 0`). Zero SQL executed. |
| **No Application Archive Extraction** | **ENFORCED** | `lohnour_wp_data/_data` confirmed 100% empty (`total 0`). Zero files staged. |
| **No Reverse Proxy / SSL / DNS Changes** | **ENFORCED** | Host ports 80/443 remain unbound; DNS untouched; Caddy not deployed. |
| **No Host Firewall Rule Alterations** | **ENFORCED** | `firewall-cmd --direct --get-all-rules` returns None. Interfaces assigned automatically by Docker daemon. |
| **No Git Commits or Staging (`git add`)** | **ENFORCED** | Working tree inspected; zero files staged in git index. |
| **Existing Workload Zero Disruption** | **ENFORCED** | Portainer and Node-RED remained running and healthy (HTTP 200). |
| **Phase 8 Recovery Point Integrity** | **ENFORCED** | `/opt/lohnour/backups/phase8_20261107_044630` verified 100% intact via `sha256sum -c`. |

---

## 8. PHASE 9 ACCEPTANCE CRITERIA MATRIX

| Check | Criterion Description | Observed Result | Verdict |
| :---: | :--- | :--- | :---: |
| 1 | Target volume `lohnour_wp_data` created | Driver: `local`, Scope: `local`, Name: `lohnour_wp_data` | **PASS** |
| 2 | Target volume `lohnour_db_data` created | Driver: `local`, Scope: `local`, Name: `lohnour_db_data` | **PASS** |
| 3 | Target network `proxy_network` created | Driver: `bridge`, Subnet: `172.18.0.0/16`, Internal: `false` | **PASS** |
| 4 | Target network `lohnour_db_network` created | Driver: `bridge`, Subnet: `172.19.0.0/16`, Internal: `true` | **PASS** |
| 5 | Volume mountpoints accessible | `/var/lib/docker/volumes/{wp,db}_data/_data` exist, `0755 root:root` | **PASS** |
| 6 | Volume mountpoints empty skeletons | `total 0`, 0 entries, 0 bytes seeded | **PASS** |
| 7 | Zero containers attached to new networks | `Containers: {}` in both network inspect JSONs | **PASS** |
| 8 | Subnet allocation non-overlapping | `172.18.0.0/16` and `172.19.0.0/16` disjoint from host & bridge | **PASS** |
| 9 | Existing workloads uninterrupted | Portainer & Node-RED HTTP 200, 0 error spikes | **PASS** |
| 10 | Strict scope boundaries respected | Zero containers, zero DB restore, zero file extraction, zero git add | **PASS** |

---

## 9. SIGN-OFF & OPERATIONAL STOP

Phase 9 is formally closed as **VERIFIED AND COMPLETE**.

```
========================================================================================
PHASE CLOSURE: PHASE_9_CLOSED — PRODUCTION_INFRASTRUCTURE_VERIFIED
NEXT PHASE:    PHASE 10 (Production Database Recovery & Table Validation)
AUTHORIZATION: GATED — MANDATORY OPERATIONAL STOP
               Awaiting explicit user authorization before initiating Phase 10.
========================================================================================
```

