# Lohnour Pro — Three-Way Forensic Reconciliation Audit
## Local Repository vs. GitHub Remote vs. Live Production Oracle VM

**Auditor:** Principal DevOps Engineer, Senior Git Forensics Engineer  
**Execution Timestamp:** 2026-11-07 16:55:00 UTC  
**Target Environments:**
1. **Local Workstation:** `D:\lohnourpro` (Branch: `developer`, Commit: `0b615fa`)
2. **GitHub Repository:** `https://github.com/tanu268/Lohnour.git` (Branch: `developer`, HEAD: `0b615fa`)
3. **Production VM:** `sams-oracle-vm` (`141.148.51.166`, Oracle Linux 9.7 ARM64, Path: `/opt/lohnour`)

---

## 1. Executive Summary

A comprehensive, read-only three-way forensic audit was conducted across the local development workspace, the authoritative GitHub remote repository, and the live production environment.

### Critical Takeaways:
1. **Production Deployment Reality:**
   - Production `/opt/lohnour` is **NOT** a Git repository (`NO_GIT` confirmed).
   - Production Docker Compose is managed directly on the host VM via Compose CLI and Portainer CE.
   - Production was **not** deployed via GitHub Actions or automated Portainer Webhooks.
2. **Infrastructure Configuration Parity:**
   - The production Compose manifest (`/opt/lohnour/docker-compose.yml`) and reverse proxy configuration (`/opt/lohnour/Caddyfile`) match the local workspace versions **100% byte-for-byte** (SHA-256 verified).
   - Prior to this assignment, these canonical infrastructure manifests were untracked in GitHub.
3. **WordPress Theme Parity:**
   - Comparison of the live production WordPress container (`lohnour-wordpress` volume `lohnour_wp_data`) against local `current source/lohnourpro_wp_theme` revealed **1,044 exact file matches**.
   - The 29 modified files in the local theme directory are the exact files running live on `https://pro.lohnour.com`.
4. **Historical Credential Discovery:**
   - Commit `5dc2188` (present on both remote `main` and `developer`) contained hardcoded credentials in `verify.py` and other legacy scripts.
   - Commit `754abcc` removed the strings from the active tree and transitioned to environment variables.
   - Live credential rotation instructions have been established in [docs/ENVIRONMENT_VARIABLES.md](ENVIRONMENT_VARIABLES.md).

---

## 2. Three-Way Reconciliation Matrix

| File / Component Path | Local State | GitHub (`developer`) | Production VM | Classification | Analysis & Action |
|---|---|---|---|---|---|
| `docker-compose.yml` | Present (Untracked) | Absent | Present (`/opt/lohnour/`) | **Production & Local Only** | Track in Git root and `infrastructure/`. |
| `Caddyfile` | Present (Untracked) | Absent | Present (`/opt/lohnour/`) | **Production & Local Only** | Track in Git root and `infrastructure/`. |
| `config/uploads.ini` | Present (Untracked) | Absent | Present (`/opt/lohnour/config/`) | **Production & Local Only** | Track in Git root and `infrastructure/config/`. |
| `docker-compose.local.yml` | Present (Untracked) | Absent | Absent | **Local Only** | Track in Git for reproducible dev onboarding. |
| `.env` | Ignored (`.gitignore`) | Absent | Present (`0600 root:root`) | **Production & Local Only** | **MUST REMAIN UNTRACKED.** Protected by `.gitignore`. |
| `lohnour_wp_data` (Volume) | Absent | Absent | Active Volume (157 MB) | **Production Only** | Runtime state. Never track in Git. |
| `lohnour_db_data` (Volume) | Absent | Absent | Active Volume (124 MB) | **Production Only** | Runtime database files. Never track in Git. |
| `caddy_data` / `caddy_config` | Absent | Absent | Active Volumes | **Production Only** | ACME certificates & keys. Never track in Git. |
| `twentytwentyfive-child` | Present (Modified) | Present (`0b615fa`) | Active in `lohnour_wp_data` | **Identical to Prod / Ahead of Git** | Matches live production container (1,044 exact matches). Preserved in working tree. |
| `lohnour-program-cms` | Present (Tracked) | Present (`0b615fa`) | Active in `lohnour_wp_data` | **Identical** | Fully synchronized. |
| `docs/` | Present (Untracked) | Absent | Absent | **Local Only** | Track in Git. Contains authoritative operational documentation. |
| `scripts/` | Present (Untracked) | Absent | Present (`/opt/lohnour/`) | **Production & Local Only** | Track in Git. Contains verified recovery scripts. |
| `README.md` | Modified | Outdated (1 line) | Absent | **Local Only** | Track in Git. Comprehensive architectural overview. |
| `CONTRIBUTING.md` | Created | Absent | Absent | **Local Only** | Track in Git. Team onboarding & branch conventions. |

---

## 3. Cryptographic Evidence Table

| Asset | Local Workstation SHA-256 | Production Host SHA-256 | Parity Verdict |
|---|---|---|---|
| `Caddyfile` | `db959f1dd0642616c38e01022aa05fa01499bd67a8c042318fbccf550f17b7ad` | `db959f1dd0642616c38e01022aa05fa01499bd67a8c042318fbccf550f17b7ad` | **100% MATCH** |
| `docker-compose.yml` | `f8d4258ff9c0ad011360c3b84b6747958d0ee6b549dea29ab626db5421fe62b2` | `f8d4258ff9c0ad011360c3b84b6747958d0ee6b549dea29ab626db5421fe62b2` | **100% MATCH** |
| `config/uploads.ini` | `5295a683f06d81de64ae9f26eae152439d019489e1956f718090f7ba0f562e18` | `5295a683f06d81de64ae9f26eae152439d019489e1956f718090f7ba0f562e18` | **100% MATCH** |
| `functions.php` (Theme) | `b1283bcbe7bf9f7cd8f2a32d93fa09dc9379ed4880514c80b995cdb198e6c660` | `b1283bcbe7bf9f7cd8f2a32d93fa09dc9379ed4880514c80b995cdb198e6c660` | **100% MATCH** |
| `page-about.php` (Theme) | `04b6fe1844798ee129bc2e69f1efd8ad5507db95b202f7c2d2cc5f94ab2add9e` | `04b6fe1844798ee129bc2e69f1efd8ad5507db95b202f7c2d2cc5f94ab2add9e` | **100% MATCH** |

---

## 4. Current vs. Target Deployment Architecture

### Current Deployment Architecture
Deployments to Sam's Oracle VM are performed out-of-band via SSH and shell scripts. The host directory `/opt/lohnour` is manually synchronized and has no connection to GitHub webhooks or Portainer Git Stacks.

### Recommended Target Deployment Architecture
1. **GitHub as Source of Truth:** `developer` for staging integration, `main` for tagged production releases.
2. **Automated CI Checks:** GitHub Actions pipeline validates Docker Compose syntax, Caddy syntax, PHP syntax, and secret leaks on every PR.
3. **Controlled Manual Production Deployment (Option A):** Maintain manual trigger via secure SSH deployment scripts rather than automated Portainer webhooks.
   - *Rationale:* Zero risk of unreviewed production restarts, zero credential storage on GitHub, zero impact on existing Portainer/Node-RED workloads.
