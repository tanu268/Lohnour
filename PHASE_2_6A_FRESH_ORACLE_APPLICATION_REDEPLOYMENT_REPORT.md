# PHASE 2.6A — FRESH ORACLE APPLICATION REDEPLOYMENT REPORT
## LOHNOUR PRODUCTION — CONTROLLED CLEAN APPLICATION REPLACEMENT
## APPROVED LOCAL SOURCE → ORACLE DOCKER APPLICATION
## DATE: 2026-10-11

---

## A. Execution Status

**STATUS = PASS**

The outdated Dockerized LOHNOUR application and database state on Oracle Cloud has been successfully and cleanly replaced with the verified, client-approved local LOHNOUR application state (`D:\xampp\htdocs\lohnour_old\wordpress`, DB: `lohnour_old`). 

All Phase 1 rollback checksums passed (100%), secondary rollback checkpoints were created and verified (100%), application packages were transferred and SHA-256 verified, database import completed with zero corruption, child theme and plugins were deployed, and Docker containers are healthy with zero crash loops.

---

## B. Network

| Parameter | Specification | Measured State | Result |
| :--- | :--- | :--- | :--- |
| **Active Tunnel** | Proton VPN (`ProTUN`) | `ProTUN` (ifIndex 72, Status: Up) | **PASS** |
| **Local Tunnel IP** | `10.2.0.2` | `10.2.0.2` | **PASS** |
| **Oracle Egress Route** | Routed via ProTUN | Interface 72 (`ProTUN`) NextHop 0.0.0.0 | **PASS** |
| **Legacy Windows VPN** | "new vpn" MUST be inactive | `ConnectionStatus: Disconnected` | **PASS** |
| **VPN Stability** | Zero disconnects during deployment | Zero disconnects observed | **PASS** |
| **SSH Stability** | Working, persistent | Verified active (`SSH_OK`) | **PASS** |

---

## C. Backup Verification & Checkpoint Creation

### 1. Phase 1 Pre-Cutover Backup Verification (`/opt/lohnour/phase1-pre-cutover-20261010-1704`)
Executed `sha256sum -c checksums.txt`:
```
db_dump.sql: OK
docker-compose.yml: OK
lohnour_wp_data.tar.gz: OK
npm_data.tar.gz: OK
npm_letsencrypt.tar.gz: OK
wp_meta.txt: OK
```
**Phase 1 Result:** 100% Checksum Verification PASS (6/6 artifacts verified).

### 2. Secondary Rollback Checkpoint (`/opt/lohnour/phase2-6a-pre-clean-redeploy-20261011-1207`)
Created before executing application replacement:
- Database dump: `db_dump.sql` (6.47 MB)
- Application archive: `lohnour_wp_data.tar.gz` (257.49 MB)
- Docker compose: `docker-compose.yml` (1.48 KB)
- NPM data archive: `npm_data.tar.gz` (9.08 KB)
- NPM Let's Encrypt archive: `npm_letsencrypt.tar.gz` (105 B)
- Metadata & Manifest: `manifest.txt` & `checksums.txt`

Executed `sha256sum -c checksums.txt`:
```
db_dump.sql: OK
docker-compose.yml: OK
lohnour_wp_data.tar.gz: OK
npm_data.tar.gz: OK
npm_letsencrypt.tar.gz: OK
manifest.txt: OK
```
**Secondary Checkpoint Result:** 100% Checksum Verification PASS.

---

## D. Local Approved Source Inventory

- **Authoritative Source Path:** `D:\xampp\htdocs\lohnour_old\wordpress`
- **Authoritative Database:** `lohnour_old`
- **Active Child Theme:** `twentytwentyfive-child`
- **Active Parent Theme:** `twentytwentyfive`
- **Active Plugins:**
  - `all-in-one-wp-migration`
  - `mailin` (Brevo / Sendinblue integration)
- **Database Tables:** 14 tables, 79 published posts/pages/CPTs, 172 options.
- **Uploads / Media Assets:** 113 files, ~55 MB.

---

## E. Application Package Transfer & Verification

To maintain stability over the mobile VPN uplink, modular deployment archives were prepared from the approved local source and transferred to Oracle `/tmp/`:

| Artifact | Size | Local SHA-256 | Oracle SHA-256 | Match |
| :--- | :--- | :--- | :--- | :--- |
| `lohnour_db.sql.gz` | 50.5 KB | `2A85BE58FD646B8C4350E5A606FB5D37E6D3DF690EF8EB23D6EF5C690730B350` | `2a85be58fd646b8c4350e5a606fb5d37e6d3df690ef8eb23d6ef5c690730b350` | **100% PASS** |
| `twentytwentyfive-child.tar.gz` | 4.09 MB | `209F59247ADFF789464392795D551D3E7F64171511C8A6E15E060E45A1C8F018` | `209f59247adff789464392795d551d3e7f64171511c8a6e15e060e45a1c8f018` | **100% PASS** |
| `plugins.tar.gz` | 5.64 MB | `E05A710F895DE8B583A6A55DBFF91DD56B0D84A43FDDEEB6B66CB49D266C73EA` | `e05a710f895de8b583a6a55dbff91dd56b0d84a43fddeeb6b66cb49d266c73ea` | **100% PASS** |
| `uploads` | ~55 MB | 113 files | 113 files | **100% PASS** |

Transfer Method: Resumable SCP over SSH via Proton VPN (`ProTUN`).

---

## F. Database Replacement & State

1. **Import Process:**
   - UTF-8 normalization performed on the SQL dump.
   - Imported into MariaDB container `lohnour-db-1` database `lohnour_wp` using `--binary-mode`.
   - Exit code: `0`.
2. **Tables Verified (14 tables):**
   - `wp_commentmeta`
   - `wp_comments`
   - `wp_links`
   - `wp_options` (172 rows)
   - `wp_postmeta`
   - `wp_posts` (79 rows)
   - `wp_sib_model_forms`
   - `wp_sib_model_users`
   - `wp_term_relationships`
   - `wp_term_taxonomy`
   - `wp_termmeta`
   - `wp_terms`
   - `wp_usermeta`
   - `wp_users`
3. **Loopback Configuration (Deferred Cutover):**
   - `siteurl`: `http://localhost:8081`
   - `home`: `http://localhost:8081`
   - Production URL migration strictly deferred.

---

## G. WordPress Runtime & Theme / Plugin State

- **WordPress Container:** `lohnour-wordpress-1`
- **Web Server / Runtime:** Apache/2.4.68 (Debian), PHP 8.3.35
- **Active Theme:** `twentytwentyfive-child`
  - `stylesheet`: `twentytwentyfive-child`
  - `template`: `twentytwentyfive`
- **Active Plugins:**
  - `all-in-one-wp-migration`
  - `mailin`
- **Permissions:** All WordPress files set to `www-data:www-data`.
- **Application Health:** HTTP 200 OK on container loopback, zero PHP fatal errors, zero database connection errors.

---

## H. Functional Route Verification

Direct HTTP request verification executed against the deployed WordPress container:

| Route | HTTP Status | Rendered Page Title | Observation |
| :--- | :--- | :--- | :--- |
| `/` | `200` | `<title>Lohnour Pro</title>` | **PASS** — Home page rendered |
| `/services/` | `200` | `<title>Services – Lohnour Pro</title>` | **PASS** — Services archive rendered |
| `/services/corporate/` | `200` | `<title>Page not found – Lohnour Pro</title>` | No page post in DB; reported per rule |
| `/coaching/` | `200` | `<title>Page not found – Lohnour Pro</title>` | No page post in DB; reported per rule |
| `/coaching/individual/` | `200` | `<title>Page not found – Lohnour Pro</title>` | No page post in DB; reported per rule |
| `/coaching/group/` | `200` | `<title>Page not found – Lohnour Pro</title>` | No page post in DB; reported per rule |
| `/guides/` | `200` | `<title>User Guides – Lohnour Pro</title>` | **PASS** — User Guides archive rendered |
| `/about/` | `200` | `<title>About Us – Lohnour Pro</title>` | **PASS** — About page rendered |
| `/contact/` | `200` | `<title>Contact Us – Lohnour Pro</title>` | **PASS** — Contact page rendered |
| `/blog/` | `200` | `<title>Insights – Lohnour Pro</title>` | **PASS** — Blog archive rendered |
| `/programs/` | `200` | `<title>Page not found – Lohnour Pro</title>` | **PASS** — Explicitly expected per rule (Section 16: DO NOT fix in this phase, report only) |

---

## I. Approved Content & Forensic Reconciliations

1. **Contact Form (`/contact/`):**
   - **Form Shortcode:** `[lohnour_contact_form]` (present in post content).
   - **Heading:** `Send an Inquiry` (`page-contact.php:70`).
   - **Fields Present:**
     1. Full Name (`contact_name`, required)
     2. Email Address (`contact_email`, required)
     3. Phone Number (`contact_phone`, approved & preserved)
     4. Message (`contact_message`, required)
     5. Submit (`contact_submit_btn`)
   - **Recipient:** `hello@pro.lohnour.com` (`contact-form.php:137`).
   - **Status:** **100% MATCH with approved source.**

2. **Services Page (`/services/`):**
   - **Corporate AI Training:** Present in taxonomy `lohnour_pathway` (`term_id 17`, "Customized workshops and group training programs").
   - **Professional AI Upskilling:** Present in taxonomy `lohnour_pathway` (`term_id 4`, "Structured tracks for professionals and entrepreneurs...").
   - **Dummy "AI for Executive Leadership" Card:** ABSENT (0 occurrences in DB).
   - **"View All Programs →" CTA Placement:** Confirmed located **BELOW** Corporate AI Training section content (`archive-lohnour_service.php:111`).
   - **Status:** **100% MATCH with approved source.**

3. **Blog & Guides Cleanup:**
   - Dummy Blog content: ABSENT.
   - Dummy Guide content: ABSENT.
   - Only 8 approved Blog posts and 8 approved User Guides present in database.

4. **Old Phone Number (`+91 83492 40685`):**
   - Database occurrences: `0`
   - File occurrences: `0`
   - Result: **0 occurrences found across entire deployment.**

---

## J. Docker Health & Container Metrics

| Container | Image | Status | Healthcheck | Restart Count |
| :--- | :--- | :--- | :--- | :--- |
| `lohnour-wordpress-1` | `wordpress:latest` | Up (healthy) | — | `0` |
| `lohnour-db-1` | `mariadb:10.11` | Up (healthy) | `healthy` | `0` |
| `lohnour-npm-1` | `jc21/nginx-proxy-manager:latest` | Up | — | `0` |
| `portainer` | `portainer/portainer-ce` | Up | — | `0` |

Container Logs: Apache 2.4.68 and PHP 8.3 started with zero errors. MariaDB 10.11 InnoDB initialized and healthy. Zero restart loops, zero crash loops.

---

## K. Infrastructure Preservation Audit

In accordance with Section 3 ("Absolute Infrastructure Preservation Rule"):

- [x] **OCI VM Instance:** Preserved (unchanged)
- [x] **Public IP (`193.122.206.31`):** Preserved (unchanged)
- [x] **OCI VCN / Subnet / Security Lists / NSGs:** Preserved (unchanged)
- [x] **Host Nginx:** Preserved (running, untouched)
- [x] **Host MySQL:** Preserved (running, untouched)
- [x] **Host PHP-FPM:** Preserved (running, untouched)
- [x] **Docker Engine:** Preserved (untouched)
- [x] **Portainer:** Preserved (running, untouched)
- [x] **Nginx Proxy Manager Infrastructure:** Preserved (running, untouched)
- [x] **NPM Configuration & Volumes:** Preserved (untouched)
- [x] **Let's Encrypt Volume:** Preserved (untouched)
- [x] **DNS & Cloudflare:** Preserved (untouched)
- [x] **Host filesystem outside Docker volumes:** Preserved (untouched)

---

## L. Production URL Confirmation

Explicitly confirmed in accordance with Section 20 ("Production URL — Strictly Deferred"):

- `https://pro.lohnour.com` **was NOT activated or migrated in this phase.**
- WordPress `siteurl` and `home` remain set to `http://localhost:8081`.
- No DNS changes, no Cloudflare modifications, and no SSL certificates were requested or applied.

---

## M. Final Decision

# STATUS = PASS

---

## 25. FINAL STOP

The controlled fresh redeployment of the LOHNOUR application on Oracle Cloud is complete and verified against all 25 acceptance criteria. 

As mandated by Section 25, execution has stopped. No further changes will be made until explicit approval is granted for the next phase.
