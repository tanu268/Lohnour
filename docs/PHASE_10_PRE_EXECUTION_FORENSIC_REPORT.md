# LOHNOUR PRO — PHASE 10 PRE-EXECUTION FORENSIC RECONCILIATION REPORT
## Database Recovery Readiness Audit | READ-ONLY | ZERO PRODUCTION MUTATIONS

**Canonical Acceptance Authority:** Principal Database Reliability Engineer, Senior MariaDB Engineer, Linux Production Engineer, Docker Security Auditor, Disaster Recovery Specialist  
**Audit Date:** November 7, 2026  
**Audit Timezone:** UTC (`05:58:00Z`) / Local IST (`11:28:00+05:30`)  
**Audit Boundary:** STRICT READ-ONLY FORENSICS — ZERO PRODUCTION DATABASE MUTATIONS  
**Target Host:** Sam's Oracle VM (`141.148.51.166`, `sams-oracle-vm`, Oracle Linux Server 9.7 ARM64 `aarch64`)  
**Primary Artifact Under Audit:** `/opt/lohnour/staging/production-lohnour_wp.sql`  
**Final Readiness Status:** `PHASE_10_READY_FOR_SEPARATE_AUTHORIZATION`  

---

## 1. EXECUTIVE SUMMARY & READINESS DECISION

A comprehensive, evidence-based, read-only forensic audit has been conducted on the database recovery artifacts, Docker storage volumes, virtual network configurations, and deployment documentation prior to authorizing Phase 10 (*Production Database Recovery & Table Validation*).

### Audit Verdict: `PHASE_10_READY_FOR_SEPARATE_AUTHORIZATION`

The prerequisite conditions for Phase 10 execution readiness have been rigorously satisfied:
1. **Cryptographic Integrity Confirmed:** The staging SQL dump `/opt/lohnour/staging/production-lohnour_wp.sql` is 100% byte-for-byte identical to local source extract `_audit/production-extract/database/production-lohnour_wp.sql` (290,627 bytes, SHA-256 `db8628e7743b133e372265a7d09ac29729ab7f7c8ed9dfcd0bdd257d7e88a880`), passing all local and remote checksum manifests.
2. **Table Discrepancy Conclusively Resolved:** The physical dump contains **exactly 14 unique tables** (12 WordPress core + 2 Sendinblue/Brevo plugin tables). The historical reference to "41 tables" was a numeral transposition typo (`14` &rarr; `41`) introduced during Phase 7.3 documentation drafting and previously identified in Phase 7.4.
3. **Database Schema Atonomy:** The dump contains zero `CREATE DATABASE` and zero `USE` statements. It is completely schema-agnostic and will import safely into `lohnour_wp` (the canonical production target) or `lohnour_wp_recovery`.
4. **Volume & Network Infrastructure Verified:** Target volume `lohnour_db_data` exists on Sam's Oracle VM and was re-verified live as a clean, 100% empty skeleton (`total 0`, `0755 root:root`). Target network `lohnour_db_network` is strictly isolated (`Internal: true`, bridge, `172.19.0.0/16`, zero container attachments).
5. **Workloads & System Baseline Intact:** Portainer and Node-RED remain continuously running and healthy (HTTP 200). Zero application containers or database processes are currently running.
6. **Credential Security Architecture Validated:** MariaDB client option file methodology (`--defaults-extra-file=/tmp/client.cnf`, permissions `0600` inside `0700` isolated directory) with parameter expansion escaping (`\"` and `\\`) and failure-safe trap cleanup eliminates CLI argument leakage and process table sniffing (`ps`).

```
========================================================================================
FINAL AUDIT STATUS: PHASE_10_READY_FOR_SEPARATE_AUTHORIZATION
MANDATORY OPERATIONAL STOP: Do NOT execute Phase 10 without explicit user authorization.
========================================================================================
```

---

## 2. SQL DUMP FORENSIC INSPECTION & PAYLOAD INTEGRITY

### 2.1 File Location, Size, and Cryptographic Checksums
The SQL payload was audited across both local and production storage locations:

| Property | Local Source Extract | Remote Staged File (Oracle VM) | Compliance Status |
| :--- | :--- | :--- | :---: |
| **Filesystem Path** | `D:\lohnourpro\_audit\production-extract\database\production-lohnour_wp.sql` | `/opt/lohnour/staging/production-lohnour_wp.sql` | **RESOLVED** *(Note: Staged directly in `/opt/lohnour/staging/`)* |
| **File Size** | `290,627 bytes` | `290,627 bytes` | **EXACT MATCH** |
| **Total Lines** | 984 lines | 984 lines | **EXACT MATCH** |
| **SHA-256 Hash** | `db8628e7743b133e372265a7d09ac29729ab7f7c8ed9dfcd0bdd257d7e88a880` | `db8628e7743b133e372265a7d09ac29729ab7f7c8ed9dfcd0bdd257d7e88a880` | **EXACT MATCH** |
| **Authoritative Manifest** | Matches `_audit/production-extract/manifest-all.sha256` | Matches `/opt/lohnour/staging/SHA256SUMS` | **100% VERIFIED** |
| **Readability** | UTF-8 plain text SQL | UTF-8 plain text SQL | **VALID & UNCORRUPTED** |

---

### 2.2 SQL Structure & DDL Syntax Analysis
The dump text was parsed without executing or importing:

- **`CREATE DATABASE` Statements:** Exactly `0`. The dump does not attempt to create a database.
- **`USE` Statements:** Exactly `0`. The dump contains zero database switching directives. (Matches of the string "use" occur only as normal English words in blog post content).
- **`CREATE TABLE` Statements:** Exactly `14`.
- **`DROP TABLE IF EXISTS` Statements:** Exactly `14` (one per table, immediately preceding each `CREATE TABLE`).
- **Duplicate Table Definitions:** Exactly `0`.
- **Table Name Prefix:** `wp_` across all 14 tables.
- **Storage Engine:** `ENGINE=InnoDB` across all 14 tables.
- **Default Character Set:** `DEFAULT CHARSET=utf8mb4` across all 14 tables.
- **Table Collation:**
  - `utf8mb4_unicode_520_ci` on all 12 WordPress core tables.
  - `utf8mb4_general_ci` on the 2 Sendinblue/Brevo plugin tables (`wp_sib_model_forms`, `wp_sib_model_users`).
- **Foreign Keys:** `0` (Standard WordPress architecture uses application-level referential integrity).
- **Auto-Increment Primary Keys:** Present on 13 tables (`wp_term_relationships` uses a composite primary key `(object_id, term_taxonomy_id)`).
- **Database Views:** `0`.
- **Stored Procedures / Routines:** `0`.
- **Triggers:** `0`.
- **Events:** `0`.

---

## 3. TABLE COUNT DISCREPANCY RESOLUTION & FULL INVENTORY

### 3.1 Discrepancy Root Cause Analysis
- **The Conflict:** Historical Phase 7.3 documentation drafts referred to `41 tables` or `~41 tables`, whereas later execution summaries and Phase 7.4 reports specified `14 tables (12 core + 2 Sendinblue/Brevo)`.
- **The Empirical Evidence:** Direct AST/regex parsing of `production-lohnour_wp.sql` and historical verification of `lohnour-local-db` proves that the dump contains **exactly 14 tables**.
- **Root Cause:** A numeral transposition typo (`14` &rarr; `41`) was mistakenly introduced in Phase 7.3 markdown drafting. This was documented in `PHASE_7_4_EXECUTION_SCRIPT_HARDENING_REPORT.md` (lines 195–196) and is now confirmed by direct forensic dump inspection.
- **Conclusion:** The claim of 14 tables is **100% supported by physical evidence**. The 41-table claim is completely false.

### 3.2 Complete Authoritative Table Register (14 Tables)

| # | Table Name | Classification | Rows in Dump | Engine | Charset / Collation | Functional Purpose |
| :-: | :--- | :---: | :-: | :---: | :---: | :--- |
| 1 | `wp_commentmeta` | Core | 0 | InnoDB | `utf8mb4_unicode_520_ci` | Comment metadata (empty) |
| 2 | `wp_comments` | Core | 0 | InnoDB | `utf8mb4_unicode_520_ci` | Blog comments (empty) |
| 3 | `wp_links` | Core | 0 | InnoDB | `utf8mb4_unicode_520_ci` | Legacy blogroll links (empty) |
| 4 | `wp_options` | Core | 171 | InnoDB | `utf8mb4_unicode_520_ci` | Site settings, URLs, theme configs, db_version |
| 5 | `wp_postmeta` | Core | 133 | InnoDB | `utf8mb4_unicode_520_ci` | Custom fields, featured images, CPT metadata |
| 6 | `wp_posts` | Core | 92 | InnoDB | `utf8mb4_unicode_520_ci` | Pages, blog posts, revisions, custom post types |
| 7 | `wp_sib_model_forms` | Plugin (Brevo) | 1 | InnoDB | `utf8mb4_general_ci` | Sendinblue/Brevo newsletter subscription forms |
| 8 | `wp_sib_model_users` | Plugin (Brevo) | 0 | InnoDB | `utf8mb4_general_ci` | Sendinblue/Brevo local subscriber cache (empty) |
| 9 | `wp_term_relationships` | Core | 15 | InnoDB | `utf8mb4_unicode_520_ci` | Post-to-taxonomy category/tag mapping |
| 10 | `wp_term_taxonomy` | Core | 11 | InnoDB | `utf8mb4_unicode_520_ci` | Taxonomy types (category, post_tag, nav_menu) |
| 11 | `wp_termmeta` | Core | 5 | InnoDB | `utf8mb4_unicode_520_ci` | Taxonomy term metadata |
| 12 | `wp_terms` | Core | 11 | InnoDB | `utf8mb4_unicode_520_ci` | Taxonomy terms |
| 13 | `wp_usermeta` | Core | 45 | InnoDB | `utf8mb4_unicode_520_ci` | User capabilities, dashboard profiles |
| 14 | `wp_users` | Core | 2 | InnoDB | `utf8mb4_unicode_520_ci` | Administrator & user account records |

**Summary Totals:**
- Total Unique Tables: **14**
- WordPress Core Tables: **12**
- Third-Party Plugin Tables: **2** (`wp_sib_model_forms`, `wp_sib_model_users` belonging to Brevo/Sendinblue)
- Custom Application Tables: **0**
- Tables with Data: **10**
- Empty Tables: **4** (`wp_commentmeta`, `wp_comments`, `wp_links`, `wp_sib_model_users`)
- Total Record Tuples Across Dump: **486 rows**

---

## 4. WORDPRESS DATABASE VERSION & OPTIONS AUDIT

Forensic extraction of `wp_options` records from the dump yields:

| Option ID | Option Name | Option Value | Autoload | Verification Status |
| :-: | :--- | :--- | :---: | :---: |
| 47 | `db_version` | **`61833`** | `on` | **VERIFIED** |
| 2 | `siteurl` | **`https://pro.lohnour.com`** | `on` | **VERIFIED (Production HTTPS)** |
| 3 | `home` | **`https://pro.lohnour.com`** | `on` | **VERIFIED (Production HTTPS)** |

### 4.1 Schema Version Analysis (`61833` vs `58975`)
- In WordPress core (`wp-includes/version.php`), `$wp_db_version` identifies the database schema revision.
- `58975`: Corresponds to the WordPress 6.7.x release branch.
- `61833`: Corresponds to the WordPress 7.1.x release branch.
- In `docker-compose.yml`, the WordPress service is configured with `image: wordpress:7.1.4-php8.2-apache`.
- **Significance for Phase 10:** Phase 10 operates exclusively within MariaDB to populate and validate the database schema. It does NOT invoke PHP or WordPress runtime code. The dump's `db_version: 61833` is fully compatible with MariaDB 10.11 InnoDB storage.
- **Governance Gate Check:** In `DEPLOYMENT_PLAN.md`, `GATE 3: WordPress & Database Image Compatibility Verification` is marked `BLOCKED` because WordPress 7.1.4 requires immutable SHA-256 digest pinning prior to stack deployment in Phase 12. This does NOT block Phase 10 database initialization, but establishes that `db_version` must NOT be artificially altered or downgraded.

---

## 5. `WP_POSTS` RECORD VALIDATION BREAKDOWN

The `wp_posts` table contains **exactly 92 row tuples** formatted as an extended multi-row `INSERT INTO wp_posts VALUES (...)` statement:

### 5.1 Post Types Breakdown (Total: 92)
- `attachment` (Media library images & assets): **20**
- `revision` (Content revisions): **32**
- `page` (Static core pages): **9**
- `post` (Blog articles): **8**
- `user_guide` (Custom Post Type): **8**
- `lohnour_service` (Custom Post Type): **5**
- `lohnour_case_study` (Custom Post Type): **2**
- `lohnour_testimonial` (Custom Post Type): **2**
- `lohnour_workshop` (Custom Post Type): **2**
- `lohnour_program` (Custom Post Type): **1**
- `wp_navigation` (Site navigation element): **1**
- `wp_global_styles` (Theme user style configuration): **1**
- `custom_css` (Custom CSS record): **1**

### 5.2 Post Statuses Breakdown (Total: 92)
- `publish` (Publicly visible posts, pages, CPTs): **23**
- `inherit` (Revisions & attachments attached to parent posts): **52**
- `draft` (Unpublished drafts): **17**

**Post ID Boundary:** Minimum Post ID = `2`, Maximum Post ID = `164`. Note that Post ID 1 (`Hello world!`) was previously confirmed deleted in historical Phase 5/6 cleanups.

---

## 6. DATABASE SCHEMA & STORAGE ARCHITECTURE MAPPING

| Component | Canonical Production Value | Evidence Reference | Status / Compliance |
| :--- | :--- | :--- | :---: |
| **Docker Volume** | `lohnour_db_data` | `docker-compose.yml` (line 101), VM inspect | **VERIFIED (Empty Skeleton)** |
| **MariaDB Data Path** | `/var/lib/mysql` | `docker-compose.yml` (line 83) | **VERIFIED (Standard)** |
| **Production Schema** | `lohnour_wp` | `docker-compose.yml` (line 79), `.env.example` | **CANONICAL TARGET** |
| **Recovery Schema** | `lohnour_wp_recovery` | `PHASE_7_2_FINAL_ROLLBACK_CORRECTIONS.md` | **ROLLBACK CONTINGENCY ONLY** |
| **Database User** | `lohnour_user` | `docker-compose.yml` (line 80), `.env.example` | **VERIFIED (Unprivileged)** |
| **Target Character Set** | `utf8mb4` | SQL dump DDL, MariaDB default | **VERIFIED** |
| **Target Collation** | `utf8mb4_unicode_520_ci` | SQL dump DDL, MariaDB default | **VERIFIED** |
| **WordPress DB_NAME** | `lohnour_wp` | `docker-compose.yml` (line 40) | **ALIGNED** |
| **Database Network** | `lohnour_db_network` | `docker-compose.yml` (line 85), VM inspect | **VERIFIED (Internal Bridge)** |
| **MariaDB Engine** | `mariadb:10.11.19` (LTS) | `docker-compose.yml` (line 73) | **VERIFIED** |

### Reconciliation of `lohnour_wp` vs `lohnour_wp_recovery`
- In `PHASE_7_2_FINAL_ROLLBACK_CORRECTIONS.md`, `lohnour_wp_recovery` was proposed as a non-destructive rollback design for a live system already hosting an active `lohnour_wp` database.
- On Sam's Oracle VM today, `lohnour_db_data` is a **fresh, empty volume** (`total 0`, 0 bytes). There is NO existing database container, NO existing data, and NO `lohnour_wp` schema to overwrite.
- Restoring directly into `lohnour_wp` during Phase 10 fulfills the canonical architecture of `docker-compose.yml` without requiring post-restore Compose configuration edits.

---

## 7. DOCUMENTATION CONFLICT RESOLUTION MATRIX

| Item | Source A | Source B | Actual Evidence | Authoritative Resolution |
| :--- | :--- | :--- | :--- | :--- |
| **Table Count** | `PHASE_7_3_SAFETY_CORRECTIONS_REPORT.md`: "41 tables" | `PHASE_7_4_EXECUTION_SCRIPT_HARDENING_REPORT.md`: "14 tables" | Physical SQL dump has exactly 14 unique `CREATE TABLE` statements. | **14 Tables** (12 core + 2 Sendinblue/Brevo). The 41-table figure was a numeral transposition typo (`14` &rarr; `41`). |
| **Remote Dump Path** | Prompt / historical notes: `/opt/lohnour/staging/database/production-lohnour_wp.sql` | Live VM check: `/opt/lohnour/staging/production-lohnour_wp.sql` | No `database/` directory exists in remote staging. File is directly in `/opt/lohnour/staging/`. | Authoritative remote path is **`/opt/lohnour/staging/production-lohnour_wp.sql`**. |
| **Database Schema** | `PHASE_7_2_FINAL_ROLLBACK_CORRECTIONS.md`: `lohnour_wp_recovery` | `docker-compose.yml`, `DEPLOYMENT_PLAN.md`: `lohnour_wp` | `production-lohnour_wp.sql` has no `USE` or `CREATE DATABASE`. Volume is currently empty. | Restore directly into **`lohnour_wp`**. Use `lohnour_wp_recovery` only for future rollback drills when `lohnour_wp` already contains live data. |
| **Database Version** | Prompt: WP 6.7.11 expects `58975` | `production-lohnour_wp.sql` option 47 contains `61833` | Option 47 in SQL dump contains `db_version: 61833`. `docker-compose.yml` pins `wordpress:7.1.4`. | Retain **`61833`**. Do not downgrade `db_version`. Retain GATE 3 `BLOCKED` for WordPress container until digest is verified. |
| **Client Option File Syntax** | Phase 7.3 original: `cat << "EOF"` (prevented variable expansion) | `PHASE_7_3_SAFETY_CORRECTIONS_REPORT.md`: `printf` with parameter expansion | Quoted `"EOF"` wrote literal `"$DB_ROOT_PASSWORD"`. Parameter expansion escaping works cleanly. | Use **`printf` with parameter expansion escaping (`\"` and `\\`)** inside `0700` dir with `0600` file permissions. |
| **Database Container Healthcheck** | `DEPLOYMENT_PLAN.md` line 540: `mariadb-admin ping ... -p"$MYSQL_PASSWORD"` | `docker-compose.yml` line 87: `healthcheck.sh --connect --innodb_initialized` | Official MariaDB image provides `healthcheck.sh` natively without exposing passwords on CLI. | Use **`healthcheck.sh --connect --innodb_initialized`** for the init container. |

---

## 8. PHASE 9 INFRASTRUCTURE REVALIDATION (LIVE HOST EVIDENCE)

Captured via read-only SSH on Sam's Oracle VM (`141.148.51.166`) on November 7, 2026:

### 8.1 Docker Volume State
- `lohnour_wp_data`: Driver `local`, Mountpoint `/var/lib/docker/volumes/lohnour_wp_data/_data`, permissions `0755 root:root`, **`total 0` (empty skeleton)**.
- `lohnour_db_data`: Driver `local`, Mountpoint `/var/lib/docker/volumes/lohnour_db_data/_data`, permissions `0755 root:root`, **`total 0` (empty skeleton)**.

### 8.2 Docker Network State
- `proxy_network`: Driver `bridge`, Subnet `172.18.0.0/16`, Gateway `172.18.0.1`, Internal `false`, `Containers: {}` (0 attached).
- `lohnour_db_network`: Driver `bridge`, Subnet `172.19.0.0/16`, Gateway `172.19.0.1`, Internal `true`, `Containers: {}` (0 attached).

### 8.3 Host Workload Integrity
- `portainer`: `Up 41 minutes`, ports 8000, 9443, 9000. Responding `HTTP 200` on `https://localhost:9443`.
- `nodered`: `Up 5 months (healthy)`, port 1880. Responding `HTTP 200` on `http://localhost:1880`.
- Unexpected Containers: `0` (Exactly 2 running containers).
- Docker Server: `29.5.0`, daemon healthy.

---

## 9. CREDENTIAL HANDLING & SECURITY REVIEW

The proposed Phase 10 execution pattern was verified against production security standards:

1. **Option File Isolation:** The option file is written to an ephemeral host directory created via `mktemp -d /tmp/lohnour_db_sec.XXXXXX` with mode `0700`.
2. **File Permissions:** Inside the container, `/tmp/client.cnf` is restricted to `0600` (`-rw-------`) owned by container root.
3. **Zero CLI Password Exposure:** The client invocation `mariadb --defaults-extra-file=/tmp/client.cnf` takes zero password flags on the command line. Process listings (`ps aux`, `docker top`) reveal no credentials.
4. **Shell History Protection:** Because credentials are read from environment variables injected interactively or via Portainer and passed through `printf`, zero passwords enter bash history (`.bash_history`).
5. **Special Character Handling:** Parameter expansion escaping:
   ```bash
   ESCAPED_DB_PASS="${DB_PASSWORD//\\/\\\\}"
   ESCAPED_DB_PASS="${ESCAPED_DB_PASS//\"/\\\"}"
   ```
   correctly escapes backslashes and double quotes, ensuring characters such as `;`, `#`, `&`, `!`, or spaces do not truncate or corrupt option-file parsing.
6. **Failure-Safe Trap Cleanup:**
   ```bash
   trap 'docker exec lohnour-db-init rm -f /tmp/client.cnf 2>/dev/null || true; rm -rf "${CLIENT_CNF_DIR}" 2>/dev/null || true' EXIT INT TERM
   ```
   guarantees that even if the import fails, is interrupted (`Ctrl+C`), or receives `SIGTERM`, all credential files on host and container are wiped immediately.
7. **Git Safety:** The temporary directory is created under `/tmp` on the host, completely outside the Git working tree. Zero secrets can be tracked or committed to Git.

---

## 10. RISKS, REMAINING BLOCKERS & GOVERNANCE GATES

| Item | Risk Level | Description & Status | Mitigation / Prerequisite |
| :--- | :---: | :--- | :--- |
| **GATE 3: WordPress Image Selection** | `MEDIUM` | Core image is listed as `wordpress:7.1.4-php8.2-apache`. Image digest is not yet pinned. Status is `BLOCKED`. | Informational for Phase 10; strictly blocks Phase 12 stack launch. MariaDB init container can proceed independently. |
| **MariaDB Image Digest Pinning** | `LOW` | `docker-compose.yml` specifies `mariadb:10.11.19`. Image is not yet pulled on Oracle VM. | Pull `mariadb:10.11.19` during Phase 10 preflight and record its immutable ARM64 digest (`mariadb@sha256:...`). |
| **Database Overwrite Protection** | `ZERO` | Concern that restoring into `lohnour_wp` overwrites live data. | Disproved: `lohnour_db_data` is 100% empty skeleton (`total 0`). Zero live data exists. |
| **Network Leakage** | `ZERO` | Concern that database port 3306 might be exposed publicly. | Disproved: `lohnour-db-init` attaches exclusively to `lohnour_db_network` (`Internal: true`) with zero published host ports. |

---

## 11. CONCLUSION & SIGN-OFF

The pre-execution forensic audit confirms that all prerequisites, storage volumes, network boundaries, cryptographic checksums, table inventories, and security architectures are verified and consistent.

**Phase 10 is certified ready for execution, pending separate explicit user authorization.**

```
========================================================================================
FINAL AUDIT DECISION: PHASE_10_READY_FOR_SEPARATE_AUTHORIZATION
MANDATORY STOP: Awaiting explicit user authorization before initiating Phase 10.
========================================================================================
```

