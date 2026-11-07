# LOHNOUR PRO — PRE-DEPLOYMENT VERIFICATION CHECKLIST

**Document Identifier:** `PRE_DEPLOYMENT_CHECKLIST.md`  
**Date:** 2026-11-06  
**Auditor / Role:** Senior DevOps Engineer & Infrastructure Security Auditor  
**Target Environment:** Sam's Oracle VM (Oracle Linux 9.7)  
**Operating Mode:** STRICT READ-ONLY FORENSICS — ZERO DEPLOYMENT MUTATIONS  
**Canonical Documentation Path:** `D:\lohnourpro\docs\PRE_DEPLOYMENT_CHECKLIST.md`  
*(Note: Duplicate copies existing in `current source/lohnourpro_wp_theme/my-docs/` are historical theme artifacts and remain untouched).*  
**Overall Readiness Status:** **NO-GO — DOCUMENTATION REVIEW ONLY — DEPLOYMENT NOT APPROVED**

---

## 1. Overview & Verification Protocol

This checklist serves as the authoritative operational gate before any deployment command is executed on **Sam's Oracle VM**. Every check is assigned an explicit verification status:
- `[x] VERIFIED — CURRENT`: Confirmed through current read-only evidence in this task (e.g. local backup archive presence and hashes).
- `[?] PREVIOUSLY VERIFIED — RECONFIRM LIVE`: Historical observation from prior server inspection requiring fresh live reconfirmation before cutover.
- `[R] REPORTED — NOT INDEPENDENTLY VERIFIED`: Information reported in prior notes but lacking direct independent evidence.
- `[ ] PENDING`: Action scheduled or check awaiting upcoming deployment stage.
- `[!] BLOCKED`: Critical prerequisite missing or gated; blocks deployment until resolved.
- `[-] NOT APPLICABLE`: Genuinely irrelevant to the specific stage.
- `[P] PROPOSED — NOT APPROVED — NOT DEPLOYED`: Architectural proposal, quota model, or procedure submitted for Founder evaluation; NOT approved, tested, or deployed.

---

## 2. Infrastructure & Host Baseline Verification

| Status | Verification Item | Observation / Standard | Evidence Reference |
| :---: | :--- | :--- | :--- |
| `[?]` | **Host OS Distribution** | Oracle Linux Server 9.7 (Enterprise Linux 9 / RHEL 9 compatible) | `INFRASTRUCTURE_AUDIT.md` (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`) |
| `[?]` | **Docker Engine Version** | Docker Engine 29.5.0 installed and active | `INFRASTRUCTURE_AUDIT.md` (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`) |
| `[?]` | **Docker Compose Version**| Docker Compose 5.1.3 installed and active | `INFRASTRUCTURE_AUDIT.md` (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`) |
| `[?]` | **Compute Capacity** | 4 OCPU provisioned on VM | Section 5.1 (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`) |
| `[?]` | **Memory Capacity** | ~22 GiB usable RAM | Section 5.2 (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`) |
| `[?]` | **Existing Containers** | Exactly 2 active containers (`portainer/portainer-ce:lts`, `nodered/node-red:latest`) | Section 3.1 (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`) |
| `[?]` | **Existing Volumes** | Exactly 2 active named volumes (`portainer_data`, `nodered_data`) | Section 3.1 (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`) |
| `[?]` | **Web Listener Availability**| No host listeners on TCP 80 or 443 | Section 4.1 (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`) |
| `[?]` | **Database Port Cleanliness**| No host listener on TCP 3306 (Clean environment) | Section 4.1 (`PREVIOUSLY VERIFIED — RECONFIRM LIVE`) |
| `[?]` | **Host Storage Baseline** | Root filesystem 30 GB (~16 GB free previously); Docker Root Dir unverified; available space must be remeasured | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` (Stage 0/4) |
| `[R]` | **OCI Security List Ingress** | Inbound TCP 22, 80, 443, 9443 reported in notes; requires OCI Console confirmation | `REPORTED — NOT INDEPENDENTLY VERIFIED` |
| `[R]` | **CPU Architecture Check** | Confirm `uname -m` is `aarch64` (ARM64) vs `x86_64` | `REPORTED — NOT INDEPENDENTLY VERIFIED` |
| `[?]` | **OS Firewalld Status** | Check if `firewalld` is active; changes strictly gated (GATE 2) | `PENDING LIVE VERIFICATION` (Stage 0) |
| `[?]` | **SELinux Enforcing Status** | Check `getenforce`; relabeling (`:z`) applies ONLY to application bind mounts if enforcing | `PENDING LIVE VERIFICATION` (Stage 0) |
| `[?]` | **SSH Host Connectivity** | Direct administrative SSH access to Sam's Oracle VM | `PENDING LIVE VERIFICATION` (Stage 0) |

---

## 3. Security & Perimeter Verification

| Status | Verification Item | Observation / Standard | Evidence Reference |
| :---: | :--- | :--- | :--- |
| `[!]` | **Portainer 9443 Hardening** | Public OCI ingress rule on port 9443 (`REPORTED — NOT INDEPENDENTLY VERIFIED`) must be revoked | `SECURITY_REVIEW.md` (SEC-01) — **GATE 2** |
| `[ ]` | **SSH Tunneling Setup** | Admin SSH local port forwarding tested (`-L 9443:localhost:9443`) | Stage 1 Runbook |
| `[P]` | **Database Isolation Design**| MariaDB bound strictly to private internal bridge (`lohnour_db_network`) | `DEPLOYMENT_ARCHITECTURE.md` (`PROPOSED — NOT TESTED`) |
| `[P]` | **Zero DB Host Binding** | Database `ports:` block omitted entirely from compose specification | `DEPLOYMENT_ARCHITECTURE.md` (`PROPOSED`) |
| `[x]` | **Zero CLI Password Exposure**| Passing `-p<password>` on command line strictly prohibited | `SECURITY_REVIEW.md` (SEC-06) |
| `[x]` | **Secure Option File Standard**| Parameter-expansion escaping in temporary 0600 option file inside 0700 dir verified 100% across 9 password categories in MariaDB 10.11.19 | `PHASE_7_3_SAFETY_CORRECTIONS_REPORT.md` (`VERIFIED — CURRENT`) |
| `[?]` | **Node-RED Perimeter** | Port 1880 blocked at OCI VCN boundary | `SECURITY_REVIEW.md` (SEC-04, `REPORTED — NOT INDEPENDENTLY VERIFIED`) |
| `[P]` | **Workload Non-Interference**| Zero shared networks between WordPress stack and Node-RED/Portainer | `DEPLOYMENT_ARCHITECTURE.md` (`PROPOSED`) |
| `[x]` | **Workload Backup State Safety**| State-preserving Portainer (stop/start) and Node-RED (pause/unpause) backup logic verified across all operational states and failure traps | `PHASE_7_3_SAFETY_CORRECTIONS_REPORT.md` (`VERIFIED — CURRENT`) |

---

## 4. Authoritative Backup & Artifact Integrity

All artifacts verified locally in `D:\lohnourpro\_audit\production-extract\`:

| Status | Verification Item | Verified Checksum (SHA-256) | Classification & Integrity Result |
| :---: | :--- | :--- | :--- |
| `[x]` | `production-lohnour_wp.sql` | `db8628e7743b133e372265a7d09ac29729ab7f7c8ed9dfcd0bdd257d7e88a880` | Local file presence & hash: `VERIFIED — CURRENT`. Dump inspection completed: exactly 14 tables (12 WordPress core + 2 Sendinblue/Brevo plugin tables; db_version: 61833; target: lohnour_wp; prefix: wp_). |
| `[x]` | `uploads.tar.gz` | `5001b5d43159e4611540661d8af50a9fd0301e06a4224587cb38f7f68983b0d9` | `VERIFIED — CURRENT` Media uploads archive |
| `[x]` | `twentytwentyfive-child.tar.gz`| `a14af7f749f0e459b60658514e79325a29a3bb7fdc98c56d9591e1faadca802d` | `VERIFIED — CURRENT` Child theme archive |
| `[x]` | `lohnour_all_plugins.tar.gz` | `1e2c4bb948ea06621a52b1911b6d6a29868695798d8e37753454643e0be68484` | `VERIFIED — CURRENT` Plugins archive |
| `[x]` | `lohnour_program_cms.tar.gz` | `b6807f7c19c5cbbfe028c24949fbbe8fca65dc2f8da8be5b7a14ec84869a842b` | `VERIFIED — CURRENT` Custom plugin archive |
| `[x]` | `config/uploads.ini` | `8f8f90dd0d2ff804e84b72ef35b8cf0f214ceb3664d6dbdb9db5d4a13d7890ec` | `VERIFIED — CURRENT` PHP runtime configuration |
| `[!]` | `config/docker-compose.yml` | `c6ae0df4d16853879d71c4860b0e3dd6860b73cce3892d47da1f2b2b1eb329fb` | **HISTORICAL ARCHIVE — REFERENCE ONLY — NOT APPROVED FOR PRODUCTION** |
| `[ ]` | **Host Checksum Matching (Gate 6 - Stage 2)** | Archives verified via `sha256sum -c` after transfer to `/opt/lohnour/staging` | `PENDING` (Stage 4) |
| `[ ]` | **Restore Validation (Gate 6 - Stage 3)** | Archive structure, DB restore, and WordPress files validated in isolated staging (`lohnour_db_staging_data`) | `PENDING (NOT TESTED)` |

> [!CAUTION]
> The historical archive `_audit/production-extract/config/docker-compose.yml` is classified as:  
> **`HISTORICAL ARCHIVE — REFERENCE ONLY — NOT APPROVED FOR PRODUCTION`**  
> - Its existence does not establish production readiness.
> - It must **NOT** be copied into the active deployment directory.
> - It must **NOT** be used as the production stack configuration.
> - It must **NOT** be modified or repurposed as part of this task.
> - Any future reuse requires a separate technical review, compatibility verification, security review, and explicit Founder approval.
> - The canonical production Compose path remains **`COMPOSE FILE LOCATION — NOT YET DECIDED`** until an approved configuration is committed to GitHub.

---

## 5. Deployment Architecture & Configuration

| Status | Verification Item | Target Specification | Reference / Status |
| :---: | :--- | :--- | :--- |
| `[x]` | **Deployment Branch** | Branch `developer` verified to exist locally and on origin | `VERIFIED — CURRENT` |
| `[!]` | **Compose File in GitHub** | Canonical production Compose file path is `COMPOSE FILE LOCATION — NOT YET DECIDED` | Must be decided & committed to Git (**GATE 5**) |
| `[!]` | **Compose Resource Reuse (`external: true`)** | Production Compose must explicitly declare pre-created volumes (`lohnour_wp_data`, `lohnour_db_data`) and networks with `external: true` and exact names; resource reuse cannot be claimed until verified in Git | **GATE 5** |
| `[x]` | **Portainer Deployment Mode**| Build method: **Repository** from `developer` branch (Web Editor explicitly rejected) | `DEPLOYMENT_PLAN.md` |
| `[!]` | **Reverse Proxy Decision** | `PROPOSED — NOT APPROVED` (NPM vs Caddy vs Traefik vs Standalone Nginx vs Cloudflare Tunnel) | Awaiting Founder Selection (**GATE 4**) |
| `[!]` | **Image Version Pinning** | WordPress & MariaDB image tags must be verified and pinned | **WORDPRESS IMAGE SELECTION: BLOCKED** (**GATE 3**) |
| `[P]` | **Docker Network Setup** | `proxy_network` (Ingress Bridge) & `lohnour_db_network` (Internal Bridge) | `PROPOSED` |
| `[P]` | **CPU Limit Model** | Proposed limits: WP 1.00, DB 1.00, Proxy 0.25 = 2.25 OCPU total; nominal unassigned = 1.75 OCPU | `PROPOSED — NOT APPROVED — NOT DEPLOYED` |
| `[x]` | **PHP Runtime Limits** | `upload_max_filesize=512M`, `post_max_size=512M`, `memory_limit=512M` | `uploads.ini` (`VERIFIED — CURRENT`) |
| `[x]` | **Safe Rollback Standard** | Destructive volume deletion and DROP DATABASE removed; state-preserving container backup and restore verified | `BACKUP_AND_ROLLBACK.md` (`VERIFIED — CURRENT`) |

### Docker Volume Isolation & Lifecycle Register

| Status | Volume Name | Target Mount / Scope | Lifecycle & Isolation Policy | Evidence Reference |
| :---: | :--- | :--- | :--- | :--- |
| `[?]` | `portainer_data` | `/data` in `portainer-ce` | Existing workload persistent volume. Zero modification. | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` |
| `[?]` | `nodered_data` | `/data` in `node-red` | Existing workload persistent volume. Zero modification. | `PREVIOUSLY VERIFIED — RECONFIRM LIVE` |
| `[x]` | `lohnour_wp_data` | `/var/www/html` in `lohnour-wordpress` | Persistent volume for WordPress uploads, child theme, and plugins. Pre-created in Phase 9 as empty skeleton; mountpoint verified accessible (`0755 root:root`); write access restricted strictly to `uploads/` only upon Phase 11 extraction. | `VERIFIED — EMPTY SKELETON (PHASE 9)` |
| `[x]` | `lohnour_db_data` | `/var/lib/mysql` in `lohnour-db` | Production persistent database volume. Mounted exclusively by production stack. Pre-created in Phase 9 as empty skeleton; mountpoint verified accessible (`0755 root:root`). Data population awaits Phase 10 explicit authorization. | `VERIFIED — EMPTY SKELETON (PHASE 9)` |
| `[P]` | `lohnour_db_staging_data` | `/var/lib/mysql` in `lohnour-db-staging` | Dedicated staging database volume used exclusively in Gate 6 Stage 3 restore validation drill. Isolated from production; retained post-drill as a **retained staging reference** (not immutable; Docker named volumes possess no cryptographic write-locking). Routine deletion is strictly prohibited; any future removal requires explicit authorization under GATE 10. | `PROPOSED — PENDING VERIFICATION (RETAINED POST-DRILL)` |

### Proposed CPU Allocation Breakdown (`PROPOSED — NOT APPROVED — NOT DEPLOYED`)

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

> [!NOTE]
> **MANDATORY TECHNICAL CPU RESOURCE CLARIFICATIONS:**
> 1. **CPU Limits:** Define maximum CPU capacity a container can consume under configured Docker CPU controls (Linux CFS scheduler). They are **not** guaranteed CPU allocations, nor do they imply continuous consumption.
> 2. **CPU Reservations:** Distinct from limits. No CPU reservations have been verified or approved; **CPU reservations remain unspecified**.
> 3. **Actual CPU Usage:** Actual runtime CPU consumption has **not** been established by this documentation-only task. Proposed limits must not be presented as measured usage, and unused CPU capacity must not be claimed as dedicated to Portainer or Node-RED.
> 4. **Existing Services & Contention:** Portainer and Node-RED are already active on the VM. Their actual CPU consumption and configured limits have not been measured by this task. The nominal unassigned capacity (1.75 OCPU, or 2.00 OCPU without proxy) is shared dynamically with existing services and the host operating system. This allocation does **not** guarantee headroom or prevent CPU contention.
> 5. **Status:** This allocation is strictly **`PROPOSED — NOT APPROVED — NOT DEPLOYED`**.

### WordPress Image Compatibility Verification Protocol (`WORDPRESS IMAGE SELECTION: BLOCKED`)

Until all required evidence has been obtained and reviewed, status is strictly:  
**`WORDPRESS IMAGE SELECTION: BLOCKED`**

The following verification requirements must be satisfied before image selection:
- **A. WordPress Core Version:** Identify the exact WordPress core version used by the existing website from the existing source, backup, or authoritative artifact. Do not infer the version from database revision alone.
- **B. PHP Compatibility:** Establish the PHP version required by the identified core version. Check compatibility with active child theme (`twentytwentyfive-child`) and installed plugins. Verify required PHP extensions. Do not assume PHP 8.2, 8.3, or any other version without evidence.
- **C. Plugin Compatibility:** Identify all installed plugins and their versions. Verify compatibility with selected core and PHP versions. Identify abandoned, outdated, or incompatible plugins without silent modifications.
- **D. Database Compatibility:** Establish existing database engine and version. Verify compatibility with selected WP image and application requirements. Record limitations of database revision `61833` (insufficient proof of core compatibility).
- **E. Image Reproducibility:** Record exact image tag and immutable digest (`image@sha256:...`); do not use `latest`; record architecture/platform compatibility (`linux/arm64` vs `linux/amd64`); require staging restore and functional validation before production approval.

---

## 6. Domain, DNS & SSL Verification

| Status | Verification Item | Expected Configuration | Notes / Action Required |
| :---: | :--- | :--- | :--- |
| `[!]` | **Authoritative Domain Pointing** | A-record for `pro.lohnour.com` pointed to Sam's Oracle VM baseline public IP (`141.148.51.166`) | `PENDING LIVE OCI VERIFICATION` (**GATE 8**). Current DNS does NOT point to this IP. |
| `[!]` | **Cloudflare SSL Encryption Mode** | Must be set to **Full (Strict)** | Prevents SSL redirection loops |
| `[ ]` | **Let's Encrypt Issuance** | Automated ACME HTTP-01 challenge completion | Stage 7 Runbook |
| `[ ]` | **HTTPS Redirection Enforcement** | HTTP 301 permanent redirect from `http://` to `https://` | Stage 7 Runbook |
| `[!]` | **HSTS Deployment Prerequisite** | HSTS `preload` and `includeSubDomains` must NOT be enabled until all subdomains are verified HTTPS | Security Standard |

---

## 7. Explicit Operational Approval Gates (Canonical 10-Gate Register)

Every destructive database restore, backup replacement, volume operation, firewall change, OCI change, DNS change, and production cutover is separately approval-gated under the unified 10-gate model:

```
[ ] GATE 1: Host Baseline & Capacity Verification
    Read-only verification of host architecture (uname -m), usable memory (22 GiB),
    CPU capacity (4 OCPU), and existing workloads (Portainer, Node-RED).
    Sign-off: ________________________  Date: ____________

[ ] GATE 2: Management-Plane & Perimeter Security Approval
    Founder approval for OCI Security List changes (revoking public 9443),
    SSH tunneling (https://localhost:9443), and host firewalld configuration.
    Sign-off: ________________________  Date: ____________

[ ] GATE 3: WordPress & Database Image Compatibility Verification
    Verification of WordPress core, PHP, plugin, and database compatibility
    across all 8 evidence criteria (A-E). Status remains:
    WORDPRESS IMAGE SELECTION: BLOCKED until satisfied.
    Sign-off: ________________________  Date: ____________

[ ] GATE 4: Ingress Reverse Proxy Selection & Approval
    Founder selection and approval of the ingress reverse proxy solution
    (NPM / Caddy / Traefik / Standalone Nginx / Cloudflare Tunnel).
    Sign-off: ________________________  Date: ____________

[ ] GATE 5: Canonical Compose Path & Git Stack Approval
    Founder approval of the canonical production compose file committed to
    the developer branch. (Prerequisites: Gates 3 & 4 finalized; must declare
    external: true for pre-created volumes and networks; resource reuse cannot
    be claimed until committed & verified).
    Path remains COMPOSE FILE LOCATION — NOT YET DECIDED until committed.
    (Historical compose in _audit is classified as
    HISTORICAL ARCHIVE — REFERENCE ONLY — NOT APPROVED FOR PRODUCTION).
    Sign-off: ________________________  Date: ____________

[ ] GATE 6: Backup Integrity & Restore Readiness Verification
    Stage 1: Local backup archive SHA-256 checksums verified 100% (VERIFIED — CURRENT).
             SQL dump schema compatibility remains PENDING — DUMP INSPECTION REQUIRED (BLOCKER 7).
    Stage 2: Remote transfer integrity verified on host (PENDING).
    Stage 3: Restore validation drill in isolated staging environment using
             dedicated staging volume lohnour_db_staging_data (retained post-drill;
             routine volume deletion prohibited) (PENDING — NOT TESTED).
    Sign-off: ________________________  Date: ____________

[ ] GATE 7: Production Storage Pre-Creation & Secrets Injection Approval
    Founder approval for destructive production volume pre-creation, isolated
    database restore into lohnour_db_data, application file extraction into
    lohnour_wp_data (restricted write permissions strictly to uploads/ only), and
    Portainer stack deployment. Requires independently verified root-restricted
    approval artifact /etc/lohnour/approvals/CR-LOHNOUR-STAGE5.approved.
    (Prerequisites: Gates 5 & 6 complete).
    Sign-off: ________________________  Date: ____________

[ ] GATE 8: DNS Modification & Routing Changes Approval
    Founder approval for DNS A-record pointing of pro.lohnour.com
    to Sam's Oracle VM previously verified baseline public IP (141.148.51.166).
    (Requires live reconfirmation in OCI Console immediately before cutover;
    current DNS does NOT point to this IP).
    Sign-off: ________________________  Date: ____________

[ ] GATE 9: Production Cutover & Maintenance Window Approval
    Founder authorization for the estimated 2h 50m deployment maintenance
    window and final acceptance testing.
    Sign-off: ________________________  Date: ____________

[ ] GATE 10: Volume Deletion & Emergency Reset Operations Approval
    Founder approval for any destructive Docker volume deletion (docker volume rm),
    including any eventual decommissioning of lohnour_db_staging_data.
    Strictly restricted to emergency decommissioning; forbidden during standard rollback.
    Sign-off: ________________________  Date: ____________
```

### 7.1 Independent Execution Gates Register (Phases 8–13)

Deployment execution requires explicit, sequential sign-off across all six independent phase boundaries. Automatic continuation is strictly forbidden.

```
[X] PHASE 8 GATE: Production Host Pre-Deployment Backup [PASSED & VERIFIED]
    Scope: Backup /etc/caddy, /etc/nginx, firewalld (runtime/perm/direct), iptables, ip6tables, portainer_data, nodered_data, and inspect configs.
    Criteria: Storage preflight (>= 1.5 GB); baseline workloads verified present (portainer, nodered, portainer_data, nodered_data); Portainer stopped gracefully with multi-tier corroborated lifecycle validation (exit 0, Go PID-1 SIGTERM exit 2 with full corroboration, shell wrapper exit 143; rejecting 137/OOM); post-restart BoltDB datastore health verified; Node-RED process frozen via race-free pause tracking; strict firewall direct-rule classification (CAPTURED vs None, zero error suppression); state-aware cleanup tracking; two-part integrity scheme (SHA256SUMS payload checksums excluding manifests, followed by BACKUP_MANIFEST.sha256); manifest status BACKUP_VERIFIED; Portainer & Node-RED restored to exact pre-backup states; canonical script SHA-256: a5b4c44efc7e254a8a04c8f53fb8a0847301ce6fbf1d874531309f476cad9056.
    Execution Evidence: Executed on Sam's Oracle VM (141.148.51.166) on 2026-11-07 04:46:30Z. Exit code 0. Backup directory: /opt/lohnour/backups/phase8_20261107_044630. All 11 payload artifacts passed sha256sum -c SHA256SUMS. Manifest BACKUP_VERIFIED passed BACKUP_MANIFEST.sha256. Portainer and Node-RED verified running and responding HTTP 200 on ports 9443 and 1880. Detailed report: docs/PHASE_8_PRODUCTION_HOST_BACKUP_REPORT.md.
    Authorization: MANDATORY STOP — Await explicit Phase 9 instruction.
    Sign-off: Principal DevOps & Security Reviewer  Date: 2026-11-07

[X] PHASE 9 GATE: Production Infrastructure & Volume Preparation [PASSED & VERIFIED]
    Scope: Create lohnour_wp_data, lohnour_db_data, lohnour_db_network, and proxy_network.
    Criteria: Volume inspect JSONs confirmed; mountpoints accessible; no data import yet; empty skeletons verified; non-overlapping IPAM subnets (172.18.0.0/16 and 172.19.0.0/16); internal isolation flag enforced on lohnour_db_network; Portainer and Node-RED verified healthy (HTTP 200).
    Execution Evidence: Executed on Sam's Oracle VM (141.148.51.166) on 2026-11-07 05:14:41Z. Exit code 0 across all 4 resource creations. Volumes lohnour_wp_data and lohnour_db_data verified empty (total 0, 0755 root:root). Networks proxy_network and lohnour_db_network verified with 0 container attachments. Detailed report: docs/PHASE_9_PRODUCTION_INFRASTRUCTURE_REPORT.md.
    Authorization: MANDATORY STOP — Await explicit Phase 10 instruction.
    Sign-off: Principal DevOps & Security Reviewer  Date: 2026-11-07

[ ] PHASE 10 GATE: Production Database Recovery & Table Validation
    Scope: Isolated MariaDB init container; secured 0600 option file; SQL import; table check.
    Criteria: 14 tables restored (12 core + 2 Sendinblue/Brevo tables); wp_posts count verified; client.cnf cleaned up via trap.
    Authorization: MANDATORY STOP — Await explicit Phase 11 instruction.
    Sign-off: ________________________  Date: ____________

[ ] PHASE 11 GATE: WordPress Application File & Uploads Restoration
    Scope: Extract uploads, plugins, child theme; enforce www-data 750/640 & root:root 755/644.
    Criteria: File tree verified; write permissions restricted strictly to uploads/.
    Authorization: MANDATORY STOP — Await explicit Phase 12 instruction.
    Sign-off: ________________________  Date: ____________

[ ] PHASE 12 GATE: Portainer Stack Deployment & Internal Application Acceptance
    Scope: Deploy via Portainer Git Repository (developer branch); verify internal health.
    Criteria: All containers healthy; in-container PHP smoke tests return HTTP 200.
    Authorization: MANDATORY STOP — Await explicit Phase 13 instruction.
    Sign-off: ________________________  Date: ____________

[ ] PHASE 13 GATE: Ingress Reverse Proxy & Production DNS Cutover
    Scope: Reverse proxy (Caddy); SSL certs; DNS A-record to 141.148.51.166; 9-route acceptance.
    Criteria: Public SSL verified; 9 canonical routes HTTP 200; maintenance window closed.
    Authorization: FINAL SIGN-OFF — Deployment Complete.
    Sign-off: ________________________  Date: ____________
```

---

## 8. Post-Deployment Acceptance Verification Criteria

- `[ ]` `lohnour-wordpress` container status is `Up` with zero crash loops.
- `[ ]` `lohnour-db` container status is `Up (healthy)`.
- `[ ]` Reverse proxy container status is `Up` and bound to ports 80 and 443.
- `[ ]` Portainer CE continues running normally on `portainer_data`.
- `[ ]` Node-RED continues running normally on `nodered_data`.
- `[ ]` Route 1: `/` (Home) loads cleanly with HTTP 200, full 9 sections, and testimonial slider.
- `[ ]` Route 2: `/about/` (About Us) loads cleanly with HTTP 200 and founder profile.
- `[ ]` Route 3: `/services/` loads cleanly with HTTP 200 and pathway cards.
- `[ ]` Route 4: `/coaching/` loads cleanly with HTTP 200 and bento grid.
- `[ ]` Route 5: `/coaching/agentic-ai-foundations/` (Program #162) loads all 12 modules.
- `[ ]` Route 6: `/guides/` loads cleanly with HTTP 200.
- `[ ]` Route 7: `/contact/` loads cleanly with HTTP 200 and inquiry form.
- `[ ]` Route 8: `/blog/` loads cleanly with HTTP 200.
- `[ ]` Route 9: `/wp-login.php` renders cleanly with zero headers-already-sent PHP warnings.
- `[ ]` SSL certificate is valid and issued by Let's Encrypt / ZeroSSL.
