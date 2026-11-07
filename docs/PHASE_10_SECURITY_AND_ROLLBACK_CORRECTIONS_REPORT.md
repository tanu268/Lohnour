# LOHNOUR PRO — PHASE 10 EXECUTION PLAN SECURITY AND ROLLBACK CORRECTIONS REPORT

**Canonical Authority:** Principal Database Reliability Engineer, Senior MariaDB Engineer, Linux Production Engineer, Docker Security Auditor  
**Date:** November 7, 2026  
**Final Status:** `PHASE_10_PLAN_CORRECTED — READY_FOR_SEPARATE_AUTHORIZATION`  
**Execution Boundary:** STRICTLY LOCAL & ISOLATED DOCKER VALIDATION — ZERO PRODUCTION VM MUTATIONS  
**Governing Documents:**  
- [`docs/PHASE_10_PRE_EXECUTION_FORENSIC_REPORT.md`](file:///d:/lohnourpro/docs/PHASE_10_PRE_EXECUTION_FORENSIC_REPORT.md)  
- [`docs/PHASE_10_EXECUTION_PLAN_DRAFT.md`](file:///d:/lohnourpro/docs/PHASE_10_EXECUTION_PLAN_DRAFT.md)  
- [`scripts/test_phase10_scenarios.sh`](file:///d:/lohnourpro/scripts/test_phase10_scenarios.sh)  

---

## 1. EXECUTIVE SUMMARY & READINESS DECISION

A comprehensive engineering review and isolated testing cycle has been executed to resolve five critical security, lifecycle, and rollback defects identified in the preliminary Phase 10 execution plan.

All five defects have been completely eliminated, replaced with production-grade reliability patterns, and verified across **11 automated isolated test scenarios with a 100% pass rate** on the exact target engine version `mariadb:10.11.19`:

1. **Secret Exposure Eliminated:** Plaintext password arguments in `docker run` were replaced with native MariaDB `_FILE` environment variables (`MARIADB_ROOT_PASSWORD_FILE`, `MARIADB_PASSWORD_FILE`) backed by host secret files mounted read-only (`:ro`). Direct entrypoint inspection and `docker inspect` telemetry confirmed **zero plaintext credential leakage**.
2. **Unconditional Volume Deletion Removed:** The dangerous automatic wildcard wipe (`rm -rf .../_data/*`) was completely excised. The failure policy now guarantees that on any abort or error, the database volume is safely preserved as `RECOVERY_FAILED_DATA_PRESERVED` for forensics. Destructive resets strictly require human authorization.
3. **Idempotent Hardened Cleanup Implemented:** A unified `cleanup_db_recovery()` function registered on `EXIT INT TERM` handles all termination modes, safely stops and removes temporary containers, unlinks host secret files, verifies absence, and raises `CLEANUP_INCOMPLETE — HUMAN_INTERVENTION_REQUIRED` if any credential file persists.
4. **Immediate Pre-Import Dual-Gates Established:** Added immediate pre-start checks (volume existence, empty skeleton, internal network isolation, zero host port listeners, dump SHA-256 match, Phase 8 backup validation) and immediate pre-import checks (container health, client option file authentication, zero pre-existing tables in `lohnour_wp`).
5. **Exact Forensic Import Acceptance Enforced:** The 8 forensic criteria established in the forensic audit (14 tables, 12 core + 2 Brevo, 92 `wp_posts`, `db_version = 61833`, `siteurl = https://pro.lohnour.com`, 2 users) are validated on the live database post-import.

```
========================================================================================
FINAL STATUS: PHASE_10_PLAN_CORRECTED — READY_FOR_SEPARATE_AUTHORIZATION
MANDATORY OPERATIONAL STOP: Do NOT execute Phase 10 without explicit user authorization.
========================================================================================
```

---

## 2. DEFECT ANALYSIS & HARDENED DESIGN RESOLUTIONS

### Defect 1: Plaintext Password Environment Exposure
- **Original Defect:** Preliminary drafts proposed passing plaintext passwords directly via `docker run`:
  `-e MYSQL_ROOT_PASSWORD="${DB_ROOT_PASSWORD}" -e MYSQL_PASSWORD="${DB_PASSWORD}"`.
- **Security Vulnerability:** Plaintext passwords remained visible in `docker inspect lohnour-db-init`, container JSON metadata on disk (`/var/lib/docker/containers/*`), host process tree (`ps aux`), and terminal command telemetry.
- **Investigation & Supporting Evidence:**
  Direct inspection of `/usr/local/bin/docker-entrypoint.sh` inside the official `mariadb:10.11.19` image revealed:
  ```bash
  file_env() {
      local var="$1"
      local fileVar="${var}_FILE"
      local def="${2:-}"
      if [ "${!var:-}" ] && [ "${!fileVar:-}" ]; then
          mysql_error "Both $var and $fileVar are set (but are exclusive)"
      fi
      local val="$def"
      if [ "${!var:-}" ]; then
          val="${!var}"
      elif [ "${!fileVar:-}" ]; then
          val="$(< "${!fileVar}")"
      fi
      export "$var"="$val"
      unset "$fileVar"
  }
  ```
  The entrypoint natively supports `MARIADB_ROOT_PASSWORD_FILE` and `MARIADB_PASSWORD_FILE` via `_mariadb_file_env`.
- **Corrected Design:**
  1. Ephemeral host directory created outside Git working tree: `SEC_DIR=$(mktemp -d /tmp/lohnour_db_sec.XXXXXX)`, mode `0700`.
  2. Secret files written with mode `0600`: `root_password`, `user_password`, `client.cnf`.
  3. Mounted read-only into container: `-v "${SEC_DIR}:/run/secrets:ro"`.
  4. Container invocation uses file paths:
     `-e MARIADB_ROOT_PASSWORD_FILE=/run/secrets/root_password`
     `-e MARIADB_PASSWORD_FILE=/run/secrets/user_password`
     `-e MARIADB_DATABASE=lohnour_wp`
     `-e MARIADB_USER=lohnour_user`.
  5. Verified via `docker inspect -f '{{json .Config.Env}}'`: Only file path strings are present. Zero plaintext secrets leak into container metadata.

---

### Defect 2: Unconditional Wildcard Volume Deletion
- **Original Defect:** Section 4 of preliminary drafts contained:
  `sudo rm -rf /var/lib/docker/volumes/lohnour_db_data/_data/*`.
- **Reliability Hazard:** Automatically wiping volume storage on failure destroys partial database state required for root-cause diagnosis and runs a dangerous wildcard command against host storage.
- **Corrected Failure Policy:**
  1. Every automatic volume wipe command has been completely excised.
  2. If container startup, authentication, SQL import, or table validation fails, the temporary container is safely stopped and decommissioned (`docker stop; docker rm`), leaving `/var/lib/docker/volumes/lohnour_db_data/_data` intact.
  3. The volume state is classified and reported as:
     **`RECOVERY_FAILED_DATA_PRESERVED`**.
  4. Destructive resets require explicit, documented human authorization.
  5. Source artifacts (`/opt/lohnour/staging/production-lohnour_wp.sql` and `/opt/lohnour/backups/phase8_20261107_044630`) remain permanently immutable.

---

### Defect 3: Brittle Cleanup & Secret Retention
- **Original Defect:** Cleanup relied on chained shell commands; if interrupted by SIGINT, SIGTERM, or an uncaught subshell error, temporary secret files could persist unmonitored on the host.
- **Corrected Design:**
  1. An idempotent cleanup function `cleanup_db_recovery()` is bound to `EXIT INT TERM`.
  2. Decommissions container: `docker stop "$INIT_CONTAINER" 2>/dev/null; docker rm -f "$INIT_CONTAINER" 2>/dev/null`.
  3. Deletes host secret directory: `rm -rf "$SEC_DIR" 2>/dev/null`.
  4. Performs post-cleanup verification:
     If `test -d "$SEC_DIR"` or `docker inspect "$INIT_CONTAINER"` succeeds, the cleanup is flagged incomplete:
     **`CLEANUP_INCOMPLETE — HUMAN_INTERVENTION_REQUIRED`** (Exit code `2`).
  5. Tested and verified in Scenario 9 and Scenario 10.

---

### Defect 4: Missing Immediate Pre-Import Gates
- **Original Defect:** Preliminary drafts jumped directly into container startup without re-verifying host storage, container conflicts, or backup health immediately before execution.
- **Corrected Design:**
  - **Immediate Gate A (Pre-Start):**
    - `lohnour_db_data` inspected: driver `local`, empty skeleton (`total 0`, 0 items). If non-empty, aborts immediately.
    - `lohnour_db_network` inspected: driver `bridge`, `Internal: true`, 0 containers attached.
    - Zero container name collisions (`lohnour-db-init` and `lohnour-db` must not exist).
    - Zero host port exposure (`-p 3306:3306` strictly forbidden).
    - SQL dump SHA-256 reconfirmed against staging manifest (`db8628e7743b133e372265a7d09ac29729ab7f7c8ed9dfcd0bdd257d7e88a880`).
    - Phase 8 backup integrity reconfirmed (`sha256sum -c SHA256SUMS`).
  - **Immediate Gate B (Pre-Import):**
    - Container reported `healthy` via `healthcheck.sh --connect --innodb_initialized`.
    - Client connection verified via `--defaults-extra-file=/run/secrets/client.cnf` (`SELECT 1;`).
    - Target database `lohnour_wp` confirmed present and 100% empty (`information_schema.tables` == 0).

---

### Defect 5: Ambiguous Acceptance Baseline
- **Original Defect:** Historical references mentioned 41 tables or did not enforce record counts.
- **Corrected Design:**
  Rigid verification of the 8 forensic criteria derived from the authoritative SQL dump:
  1. Total unique tables == **14**.
  2. WordPress core tables == **12**.
  3. Sendinblue / Brevo plugin tables == **2** (`wp_sib_model_forms`, `wp_sib_model_users`).
  4. `wp_posts` row count == **92**.
  5. `db_version` == **`61833`** (never downgraded).
  6. `siteurl` == **`https://pro.lohnour.com`**.
  7. `home` == **`https://pro.lohnour.com`**.
  8. `wp_users` count == **2**.

---

## 3. AUTOMATED TEST SUITE: SCENARIOS & RESULTS

The hardened execution logic was evaluated against **11 mandatory test scenarios** using isolated disposable Docker volumes, networks, and containers running image `mariadb:10.11.19` (Digest `sha256:d769e37960821dfdcf096954f6e7ac89f747e116c1bba98e48edd265dfc7deae`). Zero production VM resources were touched.

### Execution Log Summary (`scripts/test_phase10_scenarios.sh`)
```
=================================================================
PHASE 10 AUTOMATED ACCEPTANCE TEST SUITE (11 MANDATORY SCENARIOS)
Target Engine: mariadb:10.11.19
=================================================================

--> Running Scenario 1: Successful MariaDB Init via _FILE
[PASS] Scenario 1 - Container healthy, zero secrets in Config.Env

--> Running Scenario 2: Successful SQL Import & Acceptance Validation
[PASS] Scenario 2 - Import rc=0, Tables=14, Posts=92, db_ver=61833, SiteURL verified, Users=2

--> Running Scenario 3: Incorrect SQL Checksum Abort
[PASS] Scenario 3 - Mismatch correctly caught, import aborted

--> Running Scenario 4: Invalid Credentials Failure
[PASS] Scenario 4 - Authentication denied cleanly, password not printed in error

--> Running Scenario 5: Container Startup Failure & Volume Preservation
[PASS] Scenario 5 - Startup failure handled, volume preserved

--> Running Scenario 6: Import Failure & RECOVERY_FAILED_DATA_PRESERVED
[PASS] Scenario 6 - Import failed as expected (rc=1), volume data preserved (11 items preserved)

--> Running Scenario 7: Wrong Table Count Detection
[PASS] Scenario 7 - Table count mismatch caught (11 != 14), marked FAILED

--> Running Scenario 8: Wrong wp_posts Count Detection
[PASS] Scenario 8 - wp_posts count mismatch caught (50 != 92), marked FAILED

--> Running Scenario 9: Cleanup Failure Reporting
[PASS] Scenario 9 - Cleanup verification correctly flags CLEANUP_INCOMPLETE — HUMAN_INTERVENTION_REQUIRED when unverified

--> Running Scenario 10: Interruption Trap Handling
[PASS] Scenario 10 - Trap executed cleanly, secret directory wiped upon process exit

--> Running Scenario 11: Unexpected Non-Empty Volume Abort
[PASS] Scenario 11 - Pre-gate detected non-empty volume (1 items present), aborted before MariaDB start

=================================================================
PHASE 10 ACCEPTANCE TEST RESULTS: 11 / 11 PASSED
ALL 11 MANDATORY SCENARIOS PASSED WITH 100% SUCCESS RATE.
=================================================================
```

### Detailed Scenario Matrix
| Scenario | Test Objective | Observed Behavior | Verdict |
| :-: | :--- | :--- | :---: |
| **1** | Successful MariaDB Init via `_FILE` | Container reached `healthy` in 5 retries; inspect shows 0 plaintext passwords | **PASS** |
| **2** | Full SQL Import & Validation | All 14 tables, 92 posts, `db_version: 61833`, URLs, and 2 users verified live | **PASS** |
| **3** | Corrupt SQL Checksum Pre-Gate | SHA-256 mismatch detected immediately; execution halted before container start | **PASS** |
| **4** | Invalid Credentials Rejection | Authentication failed cleanly (`Access denied`); password omitted from error log | **PASS** |
| **5** | Container Startup Failure | Invalid daemon flag caught; volume preserved on disk, zero data wiped | **PASS** |
| **6** | SQL Syntax Import Failure | Malformed SQL caused exit code 1; volume preserved as `RECOVERY_FAILED_DATA_PRESERVED` | **PASS** |
| **7** | Wrong Table Count Post-Gate | Table count mismatch (11 != 14) detected; script halted with validation failure | **PASS** |
| **8** | Wrong `wp_posts` Count Post-Gate | Post count mismatch (50 != 92) detected; script halted with validation failure | **PASS** |
| **9** | Cleanup Incomplete Flagging | Secret dir persistence detected; raised `CLEANUP_INCOMPLETE — HUMAN_INTERVENTION_REQUIRED` | **PASS** |
| **10** | Signal Interruption Trap | `EXIT INT TERM` trap executed cleanly; secret directory wiped upon exit | **PASS** |
| **11** | Non-Empty Volume Pre-Gate | Pre-gate detected seeded file in volume; aborted before starting MariaDB | **PASS** |

---

## 4. REMAINING RISKS & PRODUCTION PRECONDITIONS

| Risk Category | Severity | Analysis & Mitigation |
| :--- | :---: | :--- |
| **MariaDB Image on Target Host** | `LOW` | `mariadb:10.11.19` is not yet pulled on Sam's Oracle VM. Preflight will pull the official ARM64 image from Docker Hub prior to container start. |
| **Downstream WordPress Image Gate** | `MEDIUM` | In `DEPLOYMENT_PLAN.md`, `GATE 3: WordPress & Database Image Compatibility Verification` is marked `BLOCKED` for WordPress image digest pinning. This does NOT block Phase 10 (which runs MariaDB only), but must be resolved before Phase 12 stack launch. |
| **Production Storage State** | `ZERO` | Target volume `lohnour_db_data` verified empty skeleton (`total 0`). Zero live database data exists to overwrite. |
| **Network Perimeter** | `ZERO` | Target network `lohnour_db_network` verified `Internal: true` with zero published host ports. |

---

## 5. FINAL READINESS VERDICT & MANDATORY STOP

The Phase 10 execution plan has been hardened, security-certified, and validated across all failure scenarios.

```
========================================================================================
FINAL READINESS STATUS: PHASE_10_PLAN_CORRECTED — READY_FOR_SEPARATE_AUTHORIZATION
MANDATORY OPERATIONAL STOP: Do NOT execute Phase 10 without explicit user authorization.
========================================================================================
```

