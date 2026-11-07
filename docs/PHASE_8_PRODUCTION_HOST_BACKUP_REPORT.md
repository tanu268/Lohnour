# LOHNOUR PRO — PHASE 8 PRODUCTION HOST BACKUP EXECUTION REPORT

**Canonical Acceptance Authority:** Principal DevOps Engineer, Linux Systems Engineer, Production Reliability Engineer, Security Auditor  
**Execution Date:** November 7, 2026  
**Execution Timezone:** UTC (`04:46:30Z`) / Local IST (`10:16:30+05:30`)  
**Final Status:** `PHASE_8_CLOSED — HOST_BACKUP_VERIFIED`  
**Target Host:** Sam's Oracle VM (`141.148.51.166`, Oracle Linux Server 9.7 ARM64, `sams-oracle-vm`)  
**Local Canonical Script:** [`scripts/phase8_backup.sh`](file:///d:/lohnourpro/scripts/phase8_backup.sh)  
**Remote Installed Script:** `/opt/lohnour/phase8_backup.sh` (Owner: `root:root`, Permissions: `0700`)  
**Verified Script SHA-256:** `a5b4c44efc7e254a8a04c8f53fb8a0847301ce6fbf1d874531309f476cad9056`  
**Backup Directory:** `/opt/lohnour/backups/phase8_20261107_044630`  

---

## 1. EXECUTIVE SUMMARY & FORMAL CLOSURE

Phase 8 of the Lohnour Pro deployment initiative — **Production Host Pre-Deployment Backup** — has been **successfully executed, independently verified, and formally closed** on Sam's live Oracle VM.

All seven sequential safety gates (Gate A through Gate G) were strictly satisfied:
1. **Gate A (Local Script Acceptance):** Verified 14/14 automated test scenarios passed with 100% success rate in isolated Docker; exact byte-for-byte fidelity confirmed against canonical documentation.
2. **Gate B (Live Production Preflight):** Host identity, ARM64 architecture, Docker 29.5.0, Portainer and Node-RED health, 16,296 MB available disk space (>1,500 MB minimum), and all four Phase 6 staging artifacts verified. Aborted historical backup preserved untouched.
3. **Gate C (Script Installation & Hash Verification):** Transferred canonical script; verified remote SHA-256 matched local hash `a5b4c44efc7e254a8a04c8f53fb8a0847301ce6fbf1d874531309f476cad9056` bit-for-bit.
4. **Gate D (Execution):** Executed `sudo bash /opt/lohnour/phase8_backup.sh`. Command exited cleanly with exit code `0`. Portainer shutdown stopped in 0s with exit code 2 and full Go PID-1 SIGTERM corroboration; BoltDB initialized cleanly post-restart. Node-RED frozen via cgroups pause, archived, and restored to running.
5. **Gate E (Independent Verification):** All 11 payload artifacts passed independent `sha256sum -c SHA256SUMS` verification; manifest passed `sha256sum -c BACKUP_MANIFEST.sha256`; volume tarballs verified structurally sound via `tar -tzf`.
6. **Gate F (Live Service Restoration):** Both Portainer and Node-RED independently confirmed active, running, and responding HTTP 200 on local ports 9443 and 1880 respectively. Docker daemon, networks, and volumes remain identical to pre-execution baseline.
7. **Gate G (Documentation & Stop):** Detailed execution evidence compiled, checklist and deployment plan synchronized, and mandatory operational stop enforced.

```
========================================================================================
FINAL PHASE 8 CLOSURE STATUS: PHASE_8_CLOSED — HOST_BACKUP_VERIFIED
========================================================================================
```

---

## 2. PREFLIGHT LIVE HOST EVIDENCE

Captured prior to backup execution on Sam's Oracle VM:
```
Hostname:        sams-oracle-vm
Kernel:          Linux sams-oracle-vm 6.12.0-201.74.2.2.el9uek.aarch64 #1 SMP Thu Apr 30 16:38:02 PDT 2026 aarch64
Operating Sys:   Oracle Linux Server 9.7
Public IPv4:     141.148.51.166
Private IPv4:    10.0.0.100 (interface enp0s6)
Docker Version:  29.5.0 (API 1.54, containerd v2.2.3, runc 1.3.5)
Storage Root:    16,296 MB Available (Threshold: 1,500 MB Minimum) -> PASS
Workloads:       portainer (794093300b7f), nodered (3a4cbf5e7f4d)
Volumes:         portainer_data, nodered_data
Networks:        bridge, host, none
Staging Dir:     /opt/lohnour/staging (All 4 artifacts verified OK against SHA256SUMS)
Aborted Run:     /opt/lohnour/backups/phase8_20261107_032351 (Preserved, not modified)
Running Procs:   Zero competing backup processes active
```

---

## 3. SCRIPT INSTALLATION & CRYPTOGRAPHIC VERIFICATION

- **Local Canonical Checksum:**
  `a5b4c44efc7e254a8a04c8f53fb8a0847301ce6fbf1d874531309f476cad9056  scripts/phase8_backup.sh`
- **Remote Installed Checksum:**
  `a5b4c44efc7e254a8a04c8f53fb8a0847301ce6fbf1d874531309f476cad9056  /opt/lohnour/phase8_backup.sh`
- **Ownership & Permissions:**
  `root:root`, mode `0700` (`-rwx------`)
- **Fidelity:** Exact match confirmed prior to execution.

---

## 4. EXECUTION TIMELINE & LOG TELEMETRY

**Command Executed:** `sudo bash /opt/lohnour/phase8_backup.sh`  
**Execution Start:** `2026-11-07T04:46:30Z`  
**Execution End:** `2026-11-07T04:46:35Z` (Duration: ~5 seconds)  
**Return Code:** `0`  

### Live Execution Output
```
==> [1/5] Backing up host reverse proxy configurations...
==> [2/5] Capturing host firewall rules...
==> [3/5] Verifying baseline containers and recording inspect metadata...
==> [4/5] Evaluating Portainer lifecycle state...
Portainer initial state: running
Stopping portainer (15s timeout) for BoltDB flush...
Portainer gracefully stopped (exit_code=2: Go PID-1 SIGTERM runtime exit with full corroborating evidence).
Archiving Portainer volume from /var/lib/docker/volumes/portainer_data/_data...
Restarting portainer...
==> [PORTAINER HEALTH] BoltDB datastore successfully verified healthy post-restart.
==> [5/5] Evaluating Node-RED lifecycle state...
Node-RED initial state: running
Freezing Node-RED process via docker pause...
Archiving Node-RED volume from /var/lib/docker/volumes/nodered_data/_data...
Unpausing nodered...
==> Generating SHA256 checksums across all backup payload artifacts...
==> Verifying payload SHA256 checksums...
==> Generating and verifying manifest SHA256 checksum...
==============================================================================
PHASE 8 PRE-DEPLOYMENT BACKUP COMPLETE AND VERIFIED IN: /opt/lohnour/backups/phase8_20261107_044630
STATUS: BACKUP_VERIFIED
MANDATORY STOP: Do NOT execute Phase 9 without explicit user authorization.
==============================================================================
```

---

## 5. INDEPENDENT BACKUP PAYLOAD VERIFICATION

**Directory:** `/opt/lohnour/backups/phase8_20261107_044630`  
**Permissions:** `0700` (`drwx------ root:root`)

### 5.1 Artifact Inventory & Cryptographic Checksums
All checksums independently verified with `sha256sum -c SHA256SUMS` and `sha256sum -c BACKUP_MANIFEST.sha256`:

| Artifact Name | Size (Bytes) | SHA-256 Checksum | Verification |
|---------------|:------------:|------------------|:------------:|
| `portainer_data_20261107_044630.tar.gz` | 21,554 | `ccb271c9dc7a8de725f3ea7d8e6c2014e2ffe173f730bb134f35ad7dee31dff2` | **OK** |
| `nodered_data_20261107_044630.tar.gz` | 11,016 | `965cee56a6acf885bbd4ffd780ceabc12d112b9a46c8b216d2e6d189e60d0ff0` | **OK** |
| `portainer_inspect.json` | 12,784 | `a99ff4279e2d17d25ce54e8fea47a995754cfbc7e660578f7a05fb45c63c30cd` | **OK** |
| `nodered_inspect.json` | 11,589 | `b332e26d5e7ea2a29201837b3c291a7276bbd14a6757adefb2146fa41058be51` | **OK** |
| `iptables.rules` | 5,735 | `d31edb79659c0926baf973b9bb6749bdce4a604921c4db6815d48aa44c85d0bf` | **OK** |
| `ip6tables.rules` | 4,148 | `68809039e0ffd57c2dfc921a9ea42cc034fadf5ba0aaafb435e4d903dcd88f5d` | **OK** |
| `firewalld_runtime_zones.txt` | 2,581 | `b895a8b7b823f75859158c2afc32b24a3a0039a1b989a5295db04246cf2113e2` | **OK** |
| `firewalld_permanent_zones.txt` | 2,550 | `faa62ad48797e106e2402ee7626efadc15a5aea0384b3aa819db3b90ff90e56f` | **OK** |
| `firewalld_active_summary.txt` | 246 | `5b5a0ff62179bd650852c20aba603c0d36f84914c6ec96fe3b1ea47e47a861b0` | **OK** |
| `firewalld_default_zone.txt` | 7 | `cde1925e8f892f5ddaadebfecbdd060398f5d76d3d19224f60d08343d3bbde20` | **OK** |
| `firewalld_direct_rules.txt` | 5 | `af5d8a21858f42808c9bcc17012b77bcc2fd6ab3b071dbd4a05f13f410470541` | **OK** |
| `BACKUP_MANIFEST.txt` | 1,425 | `06bfe4f37d7ae54ffc18f2ae6cc93918a1e0e448b2e638013483453c65fc082a` | **OK** |
| `SHA256SUMS` | 1,038 | (Internal checksum index covering 11 payload artifacts) | **OK** |
| `BACKUP_MANIFEST.sha256` | 86 | (Cryptographic signature of BACKUP_MANIFEST.txt) | **OK** |

### 5.2 Archive Structural Verification
Both gzip archives were verified structurally intact via `tar -tzf`:
- **`portainer_data_20261107_044630.tar.gz`:** Confirmed inclusion of `./portainer.db`, `./portainer.key`, `./portainer.pub`, `./certs/`, `./tls/`, `./compose/`, and `./docker_config/`.
- **`nodered_data_20261107_044630.tar.gz`:** Confirmed inclusion of `./flows.json`, `./settings.js`, `./package.json`, `./.config.runtime.json`, `./.config.nodes.json`, and `./flows_cred.json`.

### 5.3 Verbatim Backup Manifest Contents
```
==============================================================================
LOHNOUR PRO — PHASE 8 PRODUCTION PRE-DEPLOYMENT BACKUP MANIFEST
Timestamp:           2026-11-07T04:46:30+00:00
Host:                sams-oracle-vm (aarch64)
Docker Version:      29.5.0
Storage Available:   16296 MB (Minimum Required: 1500 MB)
==============================================================================
caddy_config: NOT_PRESENT (skipped)
nginx_config: NOT_PRESENT (skipped)
firewalld_direct_rules: None
firewalld: RUNNING (runtime, permanent, direct, default-zone captured)
iptables: CAPTURED (saved to iptables.rules)
ip6tables: CAPTURED (saved to ip6tables.rules)
portainer_inspect: RECORDED
nodered_inspect: RECORDED
portainer_initial_state: running
portainer_stop_classification: GO_PID1_SIGTERM_EXIT_2 (corroborated: duration=0s, signal=SIGTERM, entrypoint=["/portainer"])
portainer_boltdb_status: VERIFIED_HEALTHY
portainer_final_state: running
portainer_volume: BACKED_UP (21554 bytes, integrity verified)
nodered_initial_state: running
nodered_final_state: running
nodered_volume: BACKED_UP (11016 bytes, integrity verified)
nodered_consistency_note: Process-frozen snapshot via cgroups pause (crash-consistent flat JSONs; not ACID database transaction).
payload_sha256_verification: ALL_CHECKSUMS_VERIFIED
final_status: BACKUP_VERIFIED
==============================================================================
```

---

## 6. POST-EXECUTION WORKLOAD RESTORATION EVIDENCE

Independent inspection was executed following backup completion:

### 6.1 Portainer
- **Container ID:** `794093300b7f`
- **State:** `running` (`Running: true, Paused: false, Restarting: false, ExitCode: 0`)
- **StartedAt:** `2026-11-07T04:46:33.03685529Z`
- **Local HTTPS Health:** Responds with HTTP `200` on `https://localhost:9443`.
- **BoltDB Health Check:** Container logs since restart timestamp confirm clean initialization with zero corruption:
  ```
  2026/11/07 04:46AM INF github.com/portainer/portainer/api/database/boltdb/db.go:137 > loading PortainerDB | filename=portainer.db
  2026/11/07 04:46AM INF github.com/portainer/portainer/api/chisel/service.go:198 > found Chisel private key file on disk | private-key=/data/chisel/private-key.pem
  2026/11/07 04:46:33 server: Reverse tunnelling enabled
  2026/11/07 04:46:33 server: Listening on http://0.0.0.0:8000
  2026/11/07 04:46AM INF github.com/portainer/portainer/api/datastore/postinit/migrate_post_init.go:114 > Executing post init migration for environment 3 |
  2026/11/07 04:46AM INF github.com/portainer/portainer/api/cmd/portainer/main.go:643 > starting Portainer | version=2.39.2
  2026/11/07 04:46AM INF github.com/portainer/portainer/api/http/server.go:367 > starting HTTPS server | bind_address=:9443
  ```

### 6.2 Node-RED
- **Container ID:** `3a4cbf5e7f4d`
- **State:** `running` (`Running: true, Paused: false`)
- **Docker Health Status:** `healthy` (`FailingStreak: 0`)
- **Local HTTP Health:** Responds with HTTP `200` on `http://localhost:1880`.
- **Volume Mount:** `nodered_data -> /data` verified attached and intact.

### 6.3 Host & Docker Environment
- **Docker Daemon:** Active (`29.5.0`).
- **Existing Networks:** `bridge`, `host`, `none` preserved.
- **Existing Volumes:** `nodered_data`, `portainer_data` preserved.
- **Unexpected Containers:** None created, none removed. Pre-execution baseline preserved 100%.

---

## 7. REMAINING OPERATIONAL RISKS & LIMITATIONS

1. **Node-RED Snapshot Semantics:** As documented in the manifest, Node-RED persistent volume backup was captured via process-freeze (`docker pause`). This produces a crash-consistent filesystem snapshot of flat JSON flow files, not an ACID relational database transaction.
2. **BoltDB Verification Depth:** The post-restart BoltDB check evaluates log output for initialization success and absence of panic/corruption strings. While highly effective, log-based verification is not a substitute for low-level b-tree structural walking.
3. **Off-Host Replication:** The verified backup is stored on the Oracle VM local root filesystem at `/opt/lohnour/backups/phase8_20261107_044630`. In a subsequent administrative window, copying this directory off-host via SCP is recommended for disaster-recovery defense in depth.

---

## 8. MANDATORY FINAL STOP

Phase 8 is **FORMALLY CLOSED AND VERIFIED**.

In accordance with strict operational boundaries:
- **Zero Phase 9 operations have been started.**
- **No Docker Compose stacks or containers have been deployed.**
- **No WordPress or Caddy installation has been performed.**
- **No database restorations or modifications have occurred.**
- **No Git commits or staging operations have been executed.**
- **Antigravity yields control to the user.**

```
========================================================================================
FINAL PHASE 8 STATUS: PHASE_8_CLOSED — HOST_BACKUP_VERIFIED
========================================================================================
```
