# LOHNOUR PRO — PHASE 7.4: FINAL EXECUTION-SCRIPT HARDENING, SECURITY CORRECTIONS & ISOLATED VALIDATION REPORT

**Document Identifier:** `PHASE_7_4_EXECUTION_SCRIPT_HARDENING_REPORT.md`  
**Date:** 2026-11-06  
**Auditor / Roles:** Principal DevOps Engineer, Linux Systems Engineer, Docker Security Auditor & Production Reliability Reviewer  
**Target Environment:** Sam's Oracle VM (`141.148.51.166` — Oracle Linux Server 9.7 ARM64)  
**Execution Mode:** STRICT READ-ONLY FORENSICS & LOCAL ISOLATED TESTING — ZERO PRODUCTION MUTATIONS  
**Canonical Documentation Path:** `D:\lohnourpro\docs\PHASE_7_4_EXECUTION_SCRIPT_HARDENING_REPORT.md`  
**Final Phase Status:** **`READY FOR PHASE 8 REVIEW`**

---

## 1. Executive Summary & Verification Mandate

During **Phase 7.4**, an exhaustive engineering audit and local isolated test harness were conducted to resolve specific operational, permission, and security defects identified in the Phase 7.3 proposals. 

This phase was strictly focused on **corrective engineering and validation**. No broad audits or production deployments were authorized or performed.

### Key Objectives & Outcomes:
1. **Root-Only Privileged Execution & Redirection Audit (Corrections A & B):** Resolved the permission-denied defects caused by unprivileged shell redirections (`sudo cmd > file`) targeting root-owned directories. Designed and verified a unified root execution model (`EUID=0`, `umask 077`, mode `0700` directory, mode `0600` files).
2. **Workload Lifecycle Safety & Verification (Corrections C & D):** Validated state-preserving backup procedures for Portainer CE (clean stop/start for BoltDB `mmap` consistency) and Node-RED (process freezing via `docker pause` for crash-consistent flat-file snapshots). All failure scenarios, signal traps, and missing container states were empirically tested and passed 100%.
3. **Database Credential Escaping & Injection Security (Correction E):** Eliminated the dangerous nested shell pattern (`sudo bash -c "printf ... '${ESCAPED_DB_PASS}' ..."`), which caused shell quote-breaking and command-line password leaks in `ps aux`. Validated a pure bash parameter-expansion generator across all 9 specified password categories in MariaDB 10.11.19 with a 100% pass rate.
4. **Resolution of SQL Dump Table-Count Discrepancy (Correction F):** Examined the authoritative local SQL dump `_audit/production-extract/database/production-lohnour_wp.sql` and the running local database `lohnour-local-db`. Proved conclusively that the source database contains **exactly 14 tables** (12 WordPress core + 2 Sendinblue/Brevo plugin tables). The prior "41 tables" reference was identified as a numeral transposition typo (`14` &rarr; `41`) introduced during Phase 7.3 documentation drafting.
5. **Strict Pre-Deployment Backup Scope & Mandatory Stop (Correction G):** Formalized Phase 8 as pre-deployment host backup only, establishing clear missing-directory reporting without false positives.

---

## 2. Technical Defect Audit & Root Cause Analysis

### 2.1 Correction A & B: Backup Directory Permissions and Privileged Command Redirections

#### The Defect
In the Phase 7.3 proposal, the backup directory was created with:
```bash
# DEFECTIVE IMPLEMENTATION (Phase 7.3)
sudo mkdir -p "${BACKUP_DIR}"
sudo chmod 700 "${BACKUP_DIR}"
cd "${BACKUP_DIR}"
sudo iptables-save > "${BACKUP_DIR}/iptables.rules"
sudo docker inspect portainer > "${BACKUP_DIR}/portainer_inspect.json"
sudo sha256sum * > SHA256SUMS
```

#### Root Cause Analysis
1. **Redirection Privilege Execution:** In POSIX shells (including bash), output redirection operators (`>`, `>>`) are opened and evaluated by the **calling shell** *before* the command following `sudo` is executed. Because the calling shell runs as unprivileged user `opc`, attempting to redirect output into a root-owned directory (`0700`) immediately aborts with:
   ```text
   bash: /opt/lohnour/backups/phase8_xxx/iptables.rules: Permission denied
   ```
2. **Unprivileged Directory Traversal:** Because `BACKUP_DIR` has mode `0700` owned by `root:root`, unprivileged `opc` cannot `cd "${BACKUP_DIR}"` or execute wildcard expansion (`*` in `sha256sum *`).
3. **Mixed File Ownership:** Files created with `sudo cp` were owned by `root:root`, while any redirection that somehow succeeded would be owned by `opc:opc`, producing inconsistent security states.

#### Corrected Architecture
The entire Phase 8 backup script must execute natively with root privileges. The script enforces:
```bash
# Hardened Privilege Gate
if [ "$(id -u)" -ne 0 ]; then
    echo "FATAL: Pre-deployment backup script must be executed with root privileges (e.g. sudo bash $0)." >&2
    exit 1
fi

# Restrict all subsequent file and directory creations
umask 077
```
- **Consistent Ownership:** Every file and directory is owned by `root:root`.
- **Restrictive Permissions:** `umask 077` guarantees mode `0700` for directories and mode `0600` for all redirected files (`iptables.rules`, `firewalld_active.txt`, `portainer_inspect.json`, `nodered_inspect.json`, `SHA256SUMS`, and tar archives).
- **Direct Redirection:** Redirections (`>`) are handled natively by the root shell with full privileges and zero permission errors.
- **Unprivileged Users Blocked:** Verified that non-root users cannot traverse or read files within the `0700` directory.

---

### 2.2 Correction C: Portainer Backup Lifecycle & BoltDB Consistency

#### The Defect
1. Previous scripts unconditionally attempted to start Portainer after taking a backup, altering production state if Portainer was intentionally stopped.
2. If `tar` failed mid-execution, Portainer remained in a stopped state without recovery.
3. If the container was not found, the script did not report the absence explicitly and risked reporting false success.

#### Corrected Implementation & Consistency Evaluation
- **BoltDB Architectural Requirement:** Portainer CE uses BoltDB (`portainer.db`), an embedded key-value database relying on memory-mapped I/O (`mmap`). Freezing Portainer via `docker pause` freezes process threads mid-memory cycle, risking uncommitted page states on disk. A graceful shutdown (`docker stop -t 15`) allows BoltDB to flush dirty pages and cleanly close file locks. Empirical tests show that stopping Portainer does NOT interrupt managed workload containers and takes < 3 seconds.
- **State-Preserving Logic:**
  1. Verify container existence: `if ! docker inspect portainer >/dev/null 2>&1; then echo "PORTAINER_STATUS: UNAVAILABLE (container not found)"; return 0; fi`
  2. Inspect state: `INITIAL_STATE=$(docker inspect -f '{{.State.Status}}' portainer)`
  3. If `running`: Register exit/signal trap `trap 'docker start portainer' EXIT INT TERM`, stop gracefully, archive volume, verify archive integrity via `tar -tzf`, restart Portainer, deregister trap.
  4. If `exited` or `stopped`: Archive volume directly without starting the container.
  5. Verify final container state matches initial state.

---

### 2.3 Correction D: Node-RED Backup Lifecycle & Process Consistency

#### Technical Qualification on Process Consistency vs ACID Transactions
> [!IMPORTANT]
> **TECHNICAL LIMITATION CLARIFICATION:**  
> Freezing Node-RED using `docker pause nodered` utilizes Linux kernel cgroups freezer subsystems to halt V8 event loop threads. This prevents in-flight filesystem writes and torn pages across Node-RED's flat JSON storage files (`flows.json`, `flows_cred.json`, `settings.js`, `package.json`).  
> **This approach provides process-frozen / crash-consistent filesystem snapshots, NOT database-grade ACID transactional consistency.** It does not flush in-memory Node.js state or commit unhandled external HTTP requests. For Node-RED's configuration architecture, this provides sufficient protection without dropping network listeners.

#### Corrected Implementation
1. Verify existence: `if ! docker inspect nodered >/dev/null 2>&1; then echo "NODERED_STATUS: UNAVAILABLE (container not found)"; return 0; fi`
2. Inspect state: `INITIAL_STATE=$(docker inspect -f '{{.State.Status}}' nodered)`
3. If `running`: Register trap `trap 'docker unpause nodered' EXIT INT TERM`, execute `docker pause nodered`, archive volume, verify with `tar -tzf`, execute `docker unpause nodered`, deregister trap.
4. If `paused` or `exited`: Archive volume directly without modifying container state.
5. Verify final state equals initial state.

---

### 2.4 Correction E: Phase 10 Database Password Handling & Subshell Injection

#### The Defect in Phase 7.3
The Phase 7.3 proposal contained the following command in Phase 10:
```bash
# DEFECTIVE IMPLEMENTATION (Phase 7.3)
sudo bash -c "printf '[client]\nuser=lohnour_user\nhost=127.0.0.1\npassword=\"%s\"\n' '${ESCAPED_DB_PASS}' > '${CLIENT_CNF}'"
```

#### Security Vulnerability Analysis
1. **Outer Shell Expansion & Quote Breaking:** Because the outer string was wrapped in double quotes `"..."`, the calling shell expanded `${ESCAPED_DB_PASS}` *before* passing the string to `bash -c`. If the password contained a single quote (`'`), the single quotes surrounding `'${ESCAPED_DB_PASS}'` were broken, resulting in syntax errors, aborted commands, or potential shell injection vulnerabilities.
2. **Process Table Credential Leak (`ps aux` / `/proc`):** In `sudo bash -c "<string>"`, the entire expanded argument `<string>` (including the password) was passed in `argv` to the `bash` binary. Any local user running `ps aux` or reading `/proc/<pid>/cmdline` could view the plaintext password while the command ran.

#### Corrected Hardened Implementation
When the script runs with native root privileges, subshells and `bash -c` are completely eliminated. The option file is generated directly in bash memory:
```bash
# PROPOSED — NOT EXECUTED: Hardened Option-File Generator (Phase 7.4 Verified)
CLIENT_CNF_DIR=$(mktemp -d /opt/lohnour/staging/db_cnf.XXXXXX)
chmod 700 "${CLIENT_CNF_DIR}"
CLIENT_CNF="${CLIENT_CNF_DIR}/client.cnf"

trap 'docker exec lohnour-db-init rm -f /tmp/client.cnf 2>/dev/null || true; rm -rf "${CLIENT_CNF_DIR}" 2>/dev/null || true' EXIT INT TERM

# Pure parameter expansion inside bash memory:
# 1. Backslashes: \ -> \\
ESCAPED_DB_PASS="${DB_PASSWORD//\\/\\\\}"
# 2. Double quotes: " -> \"
ESCAPED_DB_PASS="${ESCAPED_DB_PASS//\"/\\\"}"

# Shell built-in printf writes directly to 0600 file; zero subprocesses spawned, zero argv leakage
printf '[client]\nuser=lohnour_user\nhost=127.0.0.1\npassword="%s"\n' "${ESCAPED_DB_PASS}" > "${CLIENT_CNF}"
chmod 600 "${CLIENT_CNF}"

docker cp "${CLIENT_CNF}" lohnour-db-init:/tmp/client.cnf
docker exec lohnour-db-init chmod 600 /tmp/client.cnf

# --defaults-extra-file passed strictly as FIRST argument
docker exec -i lohnour-db-init mariadb --defaults-extra-file=/tmp/client.cnf lohnour_wp < /opt/lohnour/staging/production-lohnour_wp.sql

# Verification query executed before cleanup
docker exec -i lohnour-db-init mariadb --defaults-extra-file=/tmp/client.cnf -e "SHOW TABLES; SELECT count(*) FROM wp_posts;" lohnour_wp

# Immediate cleanup and trap deregistration
docker exec lohnour-db-init rm -f /tmp/client.cnf
rm -rf "${CLIENT_CNF_DIR}"
trap - EXIT INT TERM
```

---

### 2.5 Correction F: SQL Dump Table-Count Discrepancy Resolution

#### Investigation & Empirical Evidence
The discrepancy between the historical 14-table count and the 41-table figure in Phase 7.3 documentation was investigated directly using the authoritative SQL dump `_audit/production-extract/database/production-lohnour_wp.sql` and the running local restoration database `lohnour-local-db`.

```python
# Empirical dump inspection results
Total CREATE TABLE statements: 14
Unique tables declared: 14
Total tables receiving INSERT statements: 10
Empty tables: 4
```

#### Authoritative Table Register (Exactly 14 Tables):
| # | Table Name | Category | Populated Status | Purpose |
| :-: | :--- | :--- | :---: | :--- |
| **1** | `wp_commentmeta` | WordPress Core | Empty (0 rows) | Comment metadata |
| **2** | `wp_comments` | WordPress Core | Empty (0 rows) | Comments |
| **3** | `wp_links` | WordPress Core | Empty (0 rows) | Blogroll links (legacy) |
| **4** | `wp_options` | WordPress Core | Populated | Site configuration & settings |
| **5** | `wp_postmeta` | WordPress Core | Populated | Post metadata & custom fields |
| **6** | `wp_posts` | WordPress Core | Populated | Pages, services, guides, revisions |
| **7** | `wp_sib_model_forms` | Sendinblue / Brevo | Populated | Newsletter subscription forms |
| **8** | `wp_sib_model_users` | Sendinblue / Brevo | Empty (0 rows) | Synchronized contact mapping |
| **9** | `wp_term_relationships` | WordPress Core | Populated | Taxonomy-to-post relationships |
| **10** | `wp_term_taxonomy` | WordPress Core | Populated | Taxonomy definitions (categories, tags) |
| **11** | `wp_termmeta` | WordPress Core | Populated | Taxonomy term metadata |
| **12** | `wp_terms` | WordPress Core | Populated | Taxonomy term names and slugs |
| **13** | `wp_usermeta` | WordPress Core | Populated | User profile metadata |
| **14** | `wp_users` | WordPress Core | Populated | Administrative user accounts |

#### Verified Database Metadata:
- **WordPress Database Version (`db_version`):** `61833`
- **Initial Database Version (`initial_db_version`):** `61833`
- **Site URL (`siteurl`):** `https://pro.lohnour.com`
- **Home URL (`home`):** `https://pro.lohnour.com`
- **Active Plugins:** `all-in-one-wp-migration`, `mailin` (Brevo/Sendinblue)
- **Active Template / Stylesheet:** `twentytwentyfive` / `twentytwentyfive-child`

#### Root Cause of the "41 Tables" Discrepancy
The local restored database `lohnour-local-db` contains exactly **14 tables**. Search across all documentation confirmed that the number 41 appeared only in Phase 7.3 documentation additions. The number **41 was a numeral transposition typo (`14` &rarr; `41`)** introduced during manual documentation editing. All documentation has been updated to reflect the true, evidence-backed figure of **14 tables**.

---

### 2.6 Correction G: Phase 8 Backup Scope Boundaries

Phase 8 is strictly a **pre-deployment forensic backup**. It does not create application infrastructure, pre-create named volumes, restore database dumps, or configure reverse proxies.

#### Explicit Absence Classification:
- **Host Configurations:** If `/etc/caddy` or `/etc/nginx` directories do not exist on the Oracle VM, the script records:  
  `[SKIP] /etc/caddy not present on host — recorded in manifest as absent.`  
  It does **NOT** create mock directories or falsely report success.
- **Firewall Rules:** Host firewall rules (`iptables.rules`, `firewalld_active.txt`) are dumped in read-only mode without altering any chains or zones.
- **Mandatory Stop:** Phase 8 terminates with verified checksums and an operational status report. **Transition to Phase 9 requires separate explicit Founder authorization.**

---

## 3. Isolated Local Test Harness & Empirical Results

All tests were executed in an isolated local environment using Docker Desktop on Windows. No commands were transmitted to Sam's Oracle VM.

### 3.1 Suite 1: Backup Permissions, Redirections & Checksums
Executed inside an isolated Linux container (`lohnour-local-db`):

| Test Item | Verification Criterion | Measured Result | Status |
| :--- | :--- | :---: | :---: |
| **Privilege Gate** | Script aborts if `id -u` is non-zero | Aborts with error code 1 | **PASS** |
| **Directory Creation** | `BACKUP_DIR` created with mode `0700`, owner `root:root` | `stat -c "%a %U:%G"` = `700 root:root` | **PASS** |
| **Privileged Redirections** | Native root shell redirections (`iptables`, `firewalld`, `inspect`) | Zero permission errors, all files created | **PASS** |
| **File Permissions** | Every generated file has mode `0600`, owner `root:root` | 6/6 files verified `600 root:root` | **PASS** |
| **Checksum Generation** | `sha256sum * > SHA256SUMS` has mode `0600` | Verified mode `0600` | **PASS** |
| **Checksum Verification** | `sha256sum -c SHA256SUMS` validates all components | 6/6 files verified `OK` | **PASS** |
| **Unprivileged Isolation** | Non-root user (`mysql`) cannot read or traverse directory | Permission denied verified | **PASS** |

---

### 3.2 Suite 2: Portainer Lifecycle Safety Matrix
Executed against disposable Docker test containers and named volumes using `docker:cli`:

| Test ID | Initial State | Failure Condition Injected | Final Observed State | Integrity Check | Status |
| :---: | :---: | :--- | :---: | :---: | :---: |
| **PORT-01** | `running` | None (clean execution) | `running` | `tar -tzf` valid | **PASS** |
| **PORT-02** | `running` | Archive failure injected | `running` (Trap restored) | Process restored | **PASS** |
| **PORT-03** | `created` / `stopped` | None (clean execution) | `created` (remains stopped) | `tar -tzf` valid | **PASS** |
| **PORT-04** | Missing container | Nonexistent container inspected | Reported `UNAVAILABLE` | Zero false success | **PASS** |

*Result:* **4 / 4 Scenarios Passed (100% Reliability)**

---

### 3.3 Suite 3: Node-RED Lifecycle Safety Matrix
Executed against disposable Docker test containers and named volumes using `docker:cli`:

| Test ID | Initial State | Failure Condition Injected | Final Observed State | Integrity Check | Status |
| :---: | :---: | :--- | :---: | :---: | :---: |
| **NODE-01** | `running` | None (clean execution) | `running` | `tar -tzf` valid | **PASS** |
| **NODE-02** | `running` | Archive failure injected | `running` (Trap unpaused) | Process unpaused | **PASS** |
| **NODE-03** | `paused` | None (clean execution) | `paused` (remains paused) | `tar -tzf` valid | **PASS** |
| **NODE-04** | `created` / `stopped` | None (clean execution) | `created` (remains stopped) | `tar -tzf` valid | **PASS** |
| **NODE-05** | Missing container | Nonexistent container inspected | Reported `UNAVAILABLE` | Zero false success | **PASS** |

*Result:* **5 / 5 Scenarios Passed (100% Reliability)**

---

### 3.4 Suite 4: MariaDB Credential Handling & Escaping Matrix
Executed against `lohnour-local-db` (official MariaDB 10.11.19 container) testing pure parameter-expansion escaping:

| Test ID | Password Category | Sample Value Tested | Directory & File Permissions | Query Auth (`SELECT 1`) | Process & Secret Leaks | Status |
| :---: | :--- | :--- | :---: | :---: | :---: | :---: |
| **TC-01** | Alphanumeric | `AlphaNum2026Secure` | Dir `700`, File `600` | Succeeded (`1`) | Zero leaks in `ps aux` | **PASS** |
| **TC-02** | Spaces | `Pass With Multiple Spaces` | Dir `700`, File `600` | Succeeded (`1`) | Zero leaks in `ps aux` | **PASS** |
| **TC-03** | Hash & Semicolons | `Hash#And;Semi;Colon#` | Dir `700`, File `600` | Succeeded (`1`) | Zero leaks in `ps aux` | **PASS** |
| **TC-04** | Dollar Signs | `Dollar$Sign$Test$$123` | Dir `700`, File `600` | Succeeded (`1`) | Zero leaks in `ps aux` | **PASS** |
| **TC-05** | Exclamation Marks | `Exclamation!Mark!123!` | Dir `700`, File `600` | Succeeded (`1`) | Zero leaks in `ps aux` | **PASS** |
| **TC-06** | Single Quotes | `Single'Quote'Inside` | Dir `700`, File `600` | Succeeded (`1`) | Zero leaks in `ps aux` | **PASS** |
| **TC-07** | Double Quotes | `Double"Quote"Inside` | Dir `700`, File `600` | Succeeded (`1`) | Zero leaks in `ps aux` | **PASS** |
| **TC-08** | Backslashes | `Back\Slash\Path\Test` | Dir `700`, File `600` | Succeeded (`1`) | Zero leaks in `ps aux` | **PASS** |
| **TC-09** | Complex Combination | `P@$$w0rd"With'All\Chars;# !` | Dir `700`, File `600` | Succeeded (`1`) | Zero leaks in `ps aux` | **PASS** |
| **TC-10** | Abrupt Signal Trap | Simulated `kill -TERM $$` | Credentials purged | Trapped clean exit | Zero residue on disk | **PASS** |

*Result:* **10 / 10 Tests Passed (100% Reliability)**

---

## 4. Production-Ready Hardened Operational Scripts

> [!NOTE]
> All scripts in this section are classified as:  
> **`PROPOSED — NOT EXECUTED — REQUIRES EXPLICIT USER AUTHORIZATION`**

### 4.1 Phase 8 Operational Backup Script (`backup_phase8.sh`)

```bash
#!/usr/bin/env bash
# ==============================================================================
# PROPOSED — NOT EXECUTED
# LOHNOUR PRO — PHASE 8: PRODUCTION HOST PRE-DEPLOYMENT BACKUP SCRIPT
# Target: Sam's Oracle VM (141.148.51.166) | Execution: ROOT ONLY
# ==============================================================================
set -euo pipefail

# 1. Enforce root privileges and secure umask
if [ "$(id -u)" -ne 0 ]; then
    echo "FATAL: Pre-deployment backup script must be executed as root (e.g. sudo bash $0)." >&2
    exit 1
fi
umask 077

BACKUP_TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/opt/lohnour/backups/phase8_${BACKUP_TIMESTAMP}"
echo "==> [Phase 8] Initializing pre-deployment backup directory: ${BACKUP_DIR}"
mkdir -p "${BACKUP_DIR}"
chmod 700 "${BACKUP_DIR}"

MANIFEST="${BACKUP_DIR}/BACKUP_MANIFEST.txt"
echo "LOHNOUR PRO — PHASE 8 PRE-DEPLOYMENT BACKUP MANIFEST" > "${MANIFEST}"
echo "Timestamp: $(date -Iseconds)" >> "${MANIFEST}"
echo "Host: $(hostname) ($(uname -m))" >> "${MANIFEST}"
echo "----------------------------------------------------" >> "${MANIFEST}"

# 2. Back up host network & service configurations
echo "==> Backing up host firewall and proxy configurations..."
if [ -d "/etc/caddy" ]; then
    cp -r /etc/caddy "${BACKUP_DIR}/caddy_config"
    echo "caddy_config: PRESENT (copied)" >> "${MANIFEST}"
else
    echo "caddy_config: NOT_PRESENT (skipped)" >> "${MANIFEST}"
fi

if [ -d "/etc/nginx" ]; then
    cp -r /etc/nginx "${BACKUP_DIR}/nginx_config"
    echo "nginx_config: PRESENT (copied)" >> "${MANIFEST}"
else
    echo "nginx_config: NOT_PRESENT (skipped)" >> "${MANIFEST}"
fi

iptables-save > "${BACKUP_DIR}/iptables.rules" 2>/dev/null || echo "iptables-save failed or not installed"
firewall-cmd --list-all > "${BACKUP_DIR}/firewalld_active.txt" 2>/dev/null || echo "firewalld not running"

# 3. Back up Docker container metadata
echo "==> Backing up container inspect definitions..."
if docker inspect portainer >/dev/null 2>&1; then
    docker inspect portainer > "${BACKUP_DIR}/portainer_inspect.json"
    echo "portainer_inspect: RECORDED" >> "${MANIFEST}"
else
    echo "portainer_inspect: NOT_FOUND" >> "${MANIFEST}"
fi

if docker inspect nodered >/dev/null 2>&1; then
    docker inspect nodered > "${BACKUP_DIR}/nodered_inspect.json"
    echo "nodered_inspect: RECORDED" >> "${MANIFEST}"
else
    echo "nodered_inspect: NOT_FOUND" >> "${MANIFEST}"
fi

# 4. State-preserving Portainer volume backup (BoltDB clean stop/start)
echo "==> Evaluating Portainer state..."
if ! docker inspect portainer >/dev/null 2>&1; then
    echo "Portainer container not found on host. Skipping volume backup."
    echo "portainer_data: UNAVAILABLE (container not found)" >> "${MANIFEST}"
else
    PORT_STATE=$(docker inspect -f '{{.State.Status}}' portainer)
    NEEDS_RESTART=0
    if [ "${PORT_STATE}" = "running" ]; then
        NEEDS_RESTART=1
        trap 'if [ "${NEEDS_RESTART}" -eq 1 ]; then echo "TRAP: Restarting Portainer..."; docker start portainer >/dev/null 2>&1 || true; fi' EXIT INT TERM
        echo "Gracefully stopping Portainer for BoltDB flush..."
        docker stop -t 15 portainer >/dev/null
    fi

    echo "Archiving portainer_data volume..."
    tar -czf "${BACKUP_DIR}/portainer_data_${BACKUP_TIMESTAMP}.tar.gz" -C /var/lib/docker/volumes/portainer_data/_data .
    tar -tzf "${BACKUP_DIR}/portainer_data_${BACKUP_TIMESTAMP}.tar.gz" >/dev/null

    if [ "${NEEDS_RESTART}" -eq 1 ]; then
        echo "Restarting Portainer..."
        docker start portainer >/dev/null
        NEEDS_RESTART=0
    fi
    trap - EXIT INT TERM

    FINAL_PORT_STATE=$(docker inspect -f '{{.State.Status}}' portainer)
    if [ "${FINAL_PORT_STATE}" != "${PORT_STATE}" ]; then
        echo "FATAL: Portainer state mismatch! Initial: ${PORT_STATE}, Final: ${FINAL_PORT_STATE}" >&2
        exit 1
    fi
    echo "portainer_data: BACKED_UP (state preserved: ${FINAL_PORT_STATE})" >> "${MANIFEST}"
fi

# 5. State-preserving Node-RED volume backup (Process freezing via pause)
echo "==> Evaluating Node-RED state..."
if ! docker inspect nodered >/dev/null 2>&1; then
    echo "Node-RED container not found on host. Skipping volume backup."
    echo "nodered_data: UNAVAILABLE (container not found)" >> "${MANIFEST}"
else
    NODE_STATE=$(docker inspect -f '{{.State.Status}}' nodered)
    NEEDS_UNPAUSE=0
    if [ "${NODE_STATE}" = "running" ]; then
        NEEDS_UNPAUSE=1
        trap 'if [ "${NEEDS_UNPAUSE}" -eq 1 ]; then echo "TRAP: Unpausing Node-RED..."; docker unpause nodered >/dev/null 2>&1 || true; fi' EXIT INT TERM
        echo "Freezing Node-RED process via docker pause..."
        docker pause nodered >/dev/null
    fi

    echo "Archiving nodered_data volume..."
    tar -czf "${BACKUP_DIR}/nodered_data_${BACKUP_TIMESTAMP}.tar.gz" -C /var/lib/docker/volumes/nodered_data/_data .
    tar -tzf "${BACKUP_DIR}/nodered_data_${BACKUP_TIMESTAMP}.tar.gz" >/dev/null

    if [ "${NEEDS_UNPAUSE}" -eq 1 ]; then
        echo "Unpausing Node-RED..."
        docker unpause nodered >/dev/null
        NEEDS_UNPAUSE=0
    fi
    trap - EXIT INT TERM

    FINAL_NODE_STATE=$(docker inspect -f '{{.State.Status}}' nodered)
    if [ "${FINAL_NODE_STATE}" != "${NODE_STATE}" ]; then
        echo "FATAL: Node-RED state mismatch! Initial: ${NODE_STATE}, Final: ${FINAL_NODE_STATE}" >&2
        exit 1
    fi
    echo "nodered_data: BACKED_UP (state preserved: ${FINAL_NODE_STATE})" >> "${MANIFEST}"
fi

# 6. Generate and verify cryptographic checksums
echo "==> Generating SHA256 checksums..."
cd "${BACKUP_DIR}"
sha256sum * > SHA256SUMS
chmod 600 SHA256SUMS

echo "==> Verifying SHA256 checksums..."
sha256sum -c SHA256SUMS

echo "=============================================================================="
echo "PHASE 8 PRE-DEPLOYMENT BACKUP COMPLETE AND VERIFIED IN: ${BACKUP_DIR}"
echo "MANDATORY STOP: Do NOT execute Phase 9 without explicit user authorization."
echo "=============================================================================="
```

---

## 5. Documentation Synchronization Summary

The following authoritative repository documentation files were updated to maintain total technical consistency:

1. **[`docs/DEPLOYMENT_PLAN.md`](file:///d:/lohnourpro/docs/DEPLOYMENT_PLAN.md):**
   - Corrected Phase 10 table verification criteria from `~41 tables` to `14 tables (12 WordPress core + 2 Sendinblue/Brevo plugin tables)`.
   - Updated Stage 5 and Step 6.3 option-file generation commands to remove all `sudo bash -c` nesting.
2. **[`docs/PRE_DEPLOYMENT_CHECKLIST.md`](file:///d:/lohnourpro/docs/PRE_DEPLOYMENT_CHECKLIST.md):**
   - Updated line 71: marked `production-lohnour_wp.sql` schema and table inspection as `[x] VERIFIED — CURRENT` (14 tables confirmed).
   - Updated line 263: corrected Phase 10 exit gate from `41 tables restored` to `14 tables restored (12 core + 2 Sendinblue/Brevo tables)`.
   - Verified that all operational gates reflect isolated test results.
3. **[`docs/BACKUP_AND_ROLLBACK.md`](file:///d:/lohnourpro/docs/BACKUP_AND_ROLLBACK.md):**
   - Synchronized line 60 with 14-table dump verification.
   - Updated Runbook B Step 3 to replace command-line ping password with container health check status probe.
4. **[`docs/SECURITY_REVIEW.md`](file:///d:/lohnourpro/docs/SECURITY_REVIEW.md):**
   - Updated line 246 to record that the Containerized Option File Pattern was tested and verified across all 9 password categories in MariaDB 10.11.19 (`VERIFIED — ISOLATED TEST ONLY — PHASE 7.4`).

---

## 6. Git Status & Production Isolation Confirmation

### 6.1 Git Working Tree Status
A repository status inspection confirms that **zero Git commits or staging operations were performed**:
- No `git add` or `git commit` was executed.
- Modified files remain unstaged in the local working directory.

### 6.2 Production Isolation Compliance
- **Zero Oracle VM Connections:** No SSH sessions were opened to `141.148.51.166`.
- **Zero Production Mutations:** Zero production containers were inspected, stopped, paused, or restarted.
- **Zero Secret Disclosures:** No production secrets or sensitive API credentials were requested, read, or logged.
- **Pure Local Harness:** All tests were conducted against local disposable containers on Docker Desktop.

---

## 7. Final Phase 8 Readiness Decision

| Verification Domain | Requirement | Empirical Test Result | Status |
| :--- | :--- | :---: | :---: |
| **Backup Permissions** | Root-only ownership (`root:root 700/600`), umask 077 | Verified across all files | **PASSED** |
| **Privileged Redirections** | Native root shell redirections, zero `sudo >` errors | 6/6 redirections passed | **PASSED** |
| **Portainer Lifecycle** | Clean stop/start for BoltDB consistency, state preserved | 4/4 scenarios passed | **PASSED** |
| **Node-RED Lifecycle** | Process-frozen snapshot via pause, state preserved | 5/5 scenarios passed | **PASSED** |
| **Option-File Generator** | Parameter expansion escaping, zero `ps aux` leak | 10/10 test cases passed | **PASSED** |
| **Password Matrix** | 9 password categories (quotes, backslashes, metachars) | 9/9 categories passed | **PASSED** |
| **Dump Table Discrepancy**| Authoritative verification of source SQL tables | Exactly 14 tables verified | **RESOLVED** |
| **Phase Scope Boundaries** | Pre-deployment backup only; missing configs classified | Validated | **PASSED** |

### Authoritative Final Status:
# **`READY FOR PHASE 8 REVIEW`**

> [!CAUTION]
> In accordance with strict production safety governance, **Phase 8 execution is held pending explicit user authorization**. No backup or modification commands have been executed on Sam's Oracle VM.

