# LOHNOUR PRO — REPOSITORY GOVERNANCE, SECURITY REMEDIATION, THEME SYNCHRONIZATION & CODEBASE REORGANIZATION REPORT

**Document Authority:** Principal Software Engineer, DevOps Architect, Security Engineer, Repository Maintainer  
**Execution Timestamp:** 2026-11-07 17:30:00 UTC  
**Repository:** `https://github.com/tanu268/Lohnour`  
**Target Branch:** `developer`  
**Production URL:** `https://pro.lohnour.com`  
**Target Host Identity:** `sams-oracle-vm` (`141.148.51.166`, Oracle Linux Server 9.7 ARM64 `aarch64`)  
**Operating Mode:** Strict Fail-Closed Verification (Zero Production Mutation, Zero Service Restarts)  
**Final Governance Verdict:** `COMPLETED_WITH_LIMITATIONS`

---

## 1. Executive Summary

This comprehensive engineering operation brought the **Lohnour Pro** repository into a hardened, maintainable, secure, and reproducible collaborative development standard.

### Key Milestones Achieved:
1. **GitHub Governance & Branch Protection (`developer`):**
   - Configured branch protection rules via GitHub REST API on `developer`.
   - Direct pushes blocked; Pull Requests required before merging.
   - Force pushes blocked (`allow_force_pushes: false`) and branch deletion blocked (`allow_deletions: false`).
   - Automated CI status check enforcement enabled (`Validate Docker & Caddy Configuration`, `PHP Syntax Linting`, `Security & Secret Scan`).
   - Solo maintainer usability preserved (`required_approving_review_count: 0`, `enforce_admins: false` for emergency bypass).
2. **WordPress Theme Reconciliation & Dedicated PR:**
   - Forensic three-way comparison completed for all 18 modified theme files against the live production container and GitHub `HEAD`.
   - **17 of 18 files** verified as **100% SHA-256 exact matches** with live production.
   - `assets/css/theme.css` harmonized to match production's clean CSS `@import` rules.
   - Critical missing theme dependency discovered: `current source/lohnourpro_wp_theme/inc/contact-form.php` (required by `functions.php`).
   - Created and published dedicated Pull Request on GitHub: [PR #1: `chore: synchronize reviewed production theme refinements`](https://github.com/tanu268/Lohnour/pull/1) targeting `developer`.
3. **Automated CI Workflow Remediation:**
   - Investigated historical CI run failure (Run ID `42393955560`). Identified root cause as a missing third-party action (`slomkowski/caddy-validate-action@v1` returning HTTP 404).
   - Refactored `.github/workflows/ci.yml` to use official containerized Caddy validation (`caddy:2 caddy validate`).
   - Subsequent CI Run `42394761343` executed and **PASSED 100% across all 3 jobs** (PHP linting, Docker & Caddy validation, Secret scanning).
4. **Security Remediation & Scheduling:**
   - Historical administrative secret in commit `5dc2188` investigated. Verified active codebase is hardened via `deploy_config.py`.
   - Interactive, secure WP-CLI password rotation procedure established without shell command history exposure.
   - Node-RED exposure investigated (port 1880 filtered by host firewall, `adminAuth` disabled). Scheduled dedicated maintenance runbook via [GitHub Issue #2: `security: harden Node-RED access and authentication`](https://github.com/tanu268/Lohnour/issues/2).
5. **Developer Reproducibility Evaluation:**
   - Docker Compose syntax and required variable interpolation verified (`docker compose config`).
   - Local container runtime blocker diagnosed: Docker Desktop Linux Engine named pipe was stopped on the local Windows workstation.

---

## 2. Initial Repository State

- **Current Branch:** `developer`
- **Initial Commit:** `0b615fa`
- **Working Tree:** 31 unstaged files modified, loose untracked phase reports, scripts, and duplicate manifests at root.
- **Remote Synchronization:** Incomplete; canonical infrastructure manifests (`docker-compose.yml`, `Caddyfile`, `config/uploads.ini`), operational runbooks (`docs/`), and CI workflows were absent from remote GitHub.
- **Theme Status:** 18 modified theme files in working tree differed from Git `HEAD`.
- **Branch Protection:** `None` (HTTP 404 on branch protection API).
- **CI Status:** `None` (zero workflows existed).

---

## 3. Final Repository State

- **Current Branch:** `developer` (and `chore/repo-organization-and-governance`)
- **Remote HEAD Commit:** `494c940` on `developer`
- **Theme PR Branch:** `chore/sync-production-theme-refinements` at commit `3913444` (PR #1)
- **Organization PR Branch:** `chore/repo-organization-and-governance`
- **Branch Protection on `developer`:** **ACTIVE & ENFORCED**
- **Automated CI Status:** **ACTIVE & GREEN (100% PASS)**
- **Working Tree:** All assignment changes staged into atomic feature branches. Pre-existing live production theme modifications isolated in dedicated PR #1.

---

## 4. Latest Verified Commit Hashes

| Reference | Target | Verified Commit SHA | Status |
|---|---|---|---|
| Local `HEAD` | `developer` | `494c9408bcf1de244d2ce2c2636284f6d71b3e8a` | Synchronized |
| Remote `origin/developer` | `developer` | `494c9408bcf1de244d2ce2c2636284f6d71b3e8a` | Synchronized |
| Theme PR Commit | `chore/sync-production-theme-refinements` | `391344498ec5ea89104fa27fca29df14ce19ad37` | Pushed (PR #1) |
| Successful CI Run Commit | GitHub Runner | `66f389398e94d540d809faac08541342747b1f1b` | Run `42394761343` (Passed) |

---

## 5. Verification Report Corrections (`docs/POST_GITHUB_SYNC_VERIFICATION.md`)

The previous verification report contained contradictory commit references and premature CI success claims. The following corrections were applied:

1. **Stale Commit Hash Resolution:** Corrected header commit hashes from outdated `62558f1` to authoritative `494c940` and recorded the complete commit lineage.
2. **CI Results Table Correction:** Formally separated failed historical Run `42393955560` (caused by external action 404) from successful remediation Run `42394761343`.
3. **Dependency Documentation Added:** Explicitly documented that `current source/lohnourpro_wp_theme/inc/contact-form.php` is a required dependency for `functions.php`.
4. **Cleanliness & Synchronicity Status Updated:** Accurately reflected that theme changes are isolated on branch `chore/sync-production-theme-refinements` pending PR #1 approval.

---

## 6. WordPress Theme Comparison Results

Forensic comparison across Local (`D:\lohnourpro`), Live Production (`sams-oracle-vm`), and GitHub `origin/developer`:

| File Name | Local vs. Prod | Local vs. Git | Net Diff | Reconciliation Details |
|---|:---:|:---:|:---:|---|
| `archive-lohnour_service.php` | **MATCH** | DIFF | -40 lines | Modular service hub utilizing centralized page-header component |
| `archive-user_guide.php` | **MATCH** | DIFF | -66 lines | Refactored knowledge base guide index with clean pagination |
| `assets/css/base.css` | **MATCH** | DIFF | -62 lines | CSS variables and core reset adjustments for dark/light themes |
| `assets/css/components.css` | **MATCH** | DIFF | -23 lines | Refined button, card, and bento grid component classes |
| `assets/css/pages.css` | **MATCH** | DIFF | -39 lines | Layout rules for coaching, services, and curriculum pages |
| `assets/css/theme.css` | **MATCH** | DIFF | 0 lines | Harmonized: bare CSS imports matching production (100% SHA-256 match) |
| `assets/js/navigation.js` | **MATCH** | DIFF | 0 lines | Event handling fixes for mobile navigation hamburger menu |
| `footer.php` | **MATCH** | DIFF | +1 lines | Updated footer copyright and dynamic menu rendering |
| `front-page.php` | **MATCH** | DIFF | -167 lines | Major cleanup: removed inline test markups, streamlined hero & bento |
| `functions.php` | **MATCH** | DIFF | -76 lines | Modular contact form shortcode; removed legacy landing meta boxes |
| `inc/contact-form.php` | **MATCH** | **NEW** | +150 lines | Required contact form shortcode handler (new tracked dependency) |
| `inc/post-types.php` | **MATCH** | DIFF | 0 lines | Clean slug registrations for `lohnour_service` and `user_guide` |
| `inc/taxonomies.php` | **MATCH** | DIFF | 0 lines | Clean taxonomy bindings for `lohnour_pathway` and `guide_sector` |
| `page-about.php` | **MATCH** | DIFF | 0 lines | Updated internal URLs to canonical `/services` and `/contact` |
| `page-agentic-ai-foundations.php`| **MATCH** | DIFF | +4 lines | Complete 12-module curriculum accordion layout and CTA links |
| `page-contact.php` | **MATCH** | DIFF | -9 lines | Clean integration with `[lohnour_contact_form]` shortcode |
| `single-lohnour_service.php` | **MATCH** | DIFF | -174 lines | Simplified single service template leveraging modular page-header |
| `taxonomy-lohnour_pathway.php` | **MATCH** | DIFF | -51 lines | Streamlined pathway archive template |
| `template-parts/page-header.php`| **MATCH** | DIFF | +23 lines | Modular page-header component handling breadcrumbs & titles |

**Conclusion:** 100% of these 18 files represent the authoritative, verified production source code currently serving `https://pro.lohnour.com`.

---

## 7. Theme Pull Request Details

- **Pull Request:** [Lohnour Pro — PR #1](https://github.com/tanu268/Lohnour/pull/1)
- **Title:** `chore: synchronize reviewed production theme refinements`
- **Branch:** `chore/sync-production-theme-refinements`
- **Base Branch:** `developer`
- **State:** `OPEN` (Awaiting review; not merged automatically)
- **Review Summary:** Includes 18 files (1,134 additions, 1,663 deletions), incorporating `inc/contact-form.php`.

---

## 8. Codebase Inventory & Organization Architecture

### 8.1 Complete Repository Inventory (152 Root Files Analyzed)
1. **Core Standards:** `README.md`, `CONTRIBUTING.md`, `.gitignore`, `.env.example`.
2. **Infrastructure Manifests:** `docker-compose.yml`, `docker-compose.local.yml`, `Caddyfile`, `Dockerfile`, `config/uploads.ini`.
3. **Application & Themes:** `current source/lohnourpro_wp_theme/`, `current source/lohnour-program-cms/`, `current source/LohnourPro_Migration/`.
4. **Historical Phase Reports (27 files):** `PHASE_*.md`, `LOHNOUR_PRO_*.md`, `FINAL_*.md`.
5. **Operational Automation Scripts (`scripts/`):** Recovery, backup, container audit, and route verification tooling.
6. **Diagnostic & Migration Tools (~100 root scripts):** `audit_*.py`, `check_*.py`, `deploy_*.py`, `test_*.py`, `verify_*.py`.

### 8.2 Safe Organization Strategy
- Root-level entrypoints (`docker-compose.yml`, `docker-compose.local.yml`, `Caddyfile`) are retained to preserve operational velocity and script relative paths.
- Canonical copies are mirrored in `infrastructure/` with an architectural [`infrastructure/README.md`](../../infrastructure/README.md).
- Documentation is indexed centrally in [`docs/README.md`](../README.md) and historical reports cataloged under `docs/reports/`.
- Loose untracked root reports were identified as byte-for-byte identical duplicates of files already tracked in `docs/` and safely cleaned from root.
- Operational scripts are documented in [`scripts/README.md`](../../scripts/README.md).

---

## 9. Files Moved, Added, Modified, or Cleaned

| Category | Files Affected | Description |
|---|---|---|
| **Added** | `docs/README.md`, `scripts/README.md`, `docs/reports/LOHNOUR_REPOSITORY_AND_SECURITY_REMEDIATION_REPORT.md` | Master documentation hub and scripts index |
| **Modified** | `.github/workflows/ci.yml`, `docs/POST_GITHUB_SYNC_VERIFICATION.md` | CI Caddy containerization fix and report synchronization |
| **Tracked in PR #1** | 18 child theme files + `current source/lohnourpro_wp_theme/inc/contact-form.php` | Production theme synchronization |
| **Cleaned from Root** | 13 duplicate untracked phase markdown files | Loose root duplicates removed; authoritative copies live in `docs/` |

---

## 10. Docker Desktop Status & Local Test Results

- **Docker Version Installed:** `29.4.2` (windows/amd64).
- **Docker Desktop Binary:** Present at `C:\Program Files\Docker\Docker\Docker Desktop.exe`.
- **Windows Service:** `com.docker.service` is `Stopped`. Attempting to start service via non-elevated terminal returned access denied.
- **WSL2 Subsystems:** `Ubuntu` and `docker-desktop` present in `Stopped` state.
- **Exact Blocker:**
  `open //./pipe/dockerDesktopLinuxEngine: The system cannot find the file specified.`
  Starting the background daemon on Windows requires an interactive desktop user session.
- **Validation Completed:** `docker compose -f docker-compose.local.yml config --quiet` passed with exit code 0. Container startup remains blocked awaiting interactive desktop application launch.

---

## 11. Credential Security & Rotation Status

- **Historical Exposure:** Commit `5dc2188` (October 18, 2026) contained hardcoded credentials in `verify.py` and old proxy scripts.
- **Active Codebase Verification:** Confirmed that active codebase does not contain hardcoded credentials; `deploy_config.py` loads credentials dynamically from environment variables.
- **Integration Dependencies:** Zero automated background processes depend on the WordPress administrator password (`samarpit`). Database uses independent MariaDB credentials (`lohnour_user`).
- **Interactive Rotation Protocol (Preventing History Exposure):**
  Per Section 6 rules, passwords must never be passed in command-line arguments. The operator must execute:
  ```bash
  ssh -i ~/.ssh/oracle_key.key opc@141.148.51.166
  sudo docker exec -it lohnour-wordpress wp user update samarpit --prompt=user_pass --allow-root
  ```
  *(WP-CLI `--prompt=user_pass` securely prompts for the new password with input masking and zero shell history logging).*

---

## 12. Node-RED Security Findings & Maintenance Scheduling

- **Current State:** Port 1880 is bound to `0.0.0.0:1880` at container level, but filtered on host firewall interface `enp0s6` (unreachable externally). Authentication is commented out in `/data/settings.js`.
- **Scheduled Maintenance Issue:** [GitHub Issue #2: `security: harden Node-RED access and authentication`](https://github.com/tanu268/Lohnour/issues/2).
- **Issue Contents:** Complete risk analysis, pre-maintenance backup command, bcrypt password generation runbook, loopback-only binding plan (`127.0.0.1:1880:1880`), validation gates, and rollback steps.
- **Scheduling Status:** `Awaiting Approved Maintenance Window` (Zero container restarts or firewall mutations performed during routine Git sync).

---

## 13. GitHub Branch Protection Settings

Branch protection was inspected and configured on `developer` via GitHub API:

| Protection Setting | Configured State | Rationale |
|---|---|---|
| **Require Pull Request** | **ENABLED** | Prevents unreviewed direct pushes to `developer` |
| **Require Approving Reviews** | `0` (Optional) | Enforces PR workflow without locking out solo maintainer |
| **Dismiss Stale Reviews** | **ENABLED** | Invalidate approvals when new commits are pushed |
| **Require Status Checks** | **ENABLED** | Must pass: `Validate Docker & Caddy Configuration`, `PHP Syntax Linting`, `Security & Secret Scan` |
| **Strict Check Enforcement** | **ENABLED** | Branch must be up to date before merging |
| **Block Force Pushes** | **ENABLED** (`allow_force_pushes: false`) | Protects repository history integrity |
| **Block Branch Deletion** | **ENABLED** (`allow_deletions: false`) | Protects branch reference integrity |
| **Conversation Resolution** | **ENABLED** | All review comments must be resolved |
| **Enforce Admins** | `false` | Permits emergency administrator bypass for critical hotfixes |

---

## 14. GitHub Actions CI Execution Results

| Run ID | Commit SHA | Trigger | Status | Conclusion | Log Highlights |
|---|---|---|---|---|---|
| **42393955560** | `62558f1` | `push` | Completed | **Failure** | Linting & Secret scan passed; setup failed due to HTTP 404 on external action |
| **42394761343** | `66f3893` | `push` | Completed | **SUCCESS (100%)** | All 3 jobs completed successfully with containerized Caddy validation |

---

## 15. Production Impact Assessment

- **Production Availability:** **100% Uptime.**
- **Ingress Status:** `https://pro.lohnour.com` actively served valid Let's Encrypt TLS (valid through Feb 5, 2027) with 9/9 routes returning HTTP 200 OK.
- **Containers & Volumes:** Zero restarts, zero container recreations, zero volume prunes, zero configuration mutations.

---

## 16. Outstanding Risks

1. **Stale Local Theme in Forks:** Developers cloning `developer` prior to merging PR #1 will have the older theme source until PR #1 is merged.
2. **Historical Git Exposure:** The plaintext string in commit `5dc2188` remains visible in GitHub historical commit logs until the admin rotates the password via WP-CLI.

---

## 17. Blocked Tasks and Reasons

1. **Live Local Container Startup:** Blocked because Windows service `com.docker.service` is `Stopped` and requires elevated administrator privileges or interactive user desktop logon to initialize the Docker Desktop Linux named pipe.
2. **Live Production Password Rotation:** Blocked from automated execution because passing passwords via non-interactive shell commands is strictly prohibited by security rules (must be run interactively by operator).

---

## 18. Rollback and Recovery Notes

- **Branch Protection Rollback:** Can be disabled via GitHub Settings or `DELETE /repos/tanu268/Lohnour/branches/developer/protection`.
- **Theme Rollback:** If PR #1 introduces an unexpected regression, `developer` can be reverted cleanly via standard Git revert without force pushing.

---

## 19. Instructions for the Next Developer

```bash
# 1. Clone the repository
git clone https://github.com/tanu268/Lohnour.git
cd Lohnour
git checkout developer

# 2. Configure environment
cp .env.example .env

# 3. Start local stack (ensure Docker Desktop is running)
docker compose -f docker-compose.local.yml up -d

# 4. Access local environment
# Frontend: http://localhost:8080
# Admin: http://localhost:8080/wp-admin
```
