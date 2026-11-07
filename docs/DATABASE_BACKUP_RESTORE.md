# Lohnour Pro — MariaDB Backup & Restoration Runbook

**Target Host:** Sam's Oracle Cloud VM (`141.148.51.166` / `sams-oracle-vm`)  
**Database Service:** `lohnour-db` (MariaDB 10.11 LTS)  
**Database Name:** `lohnour_wp`  
**Database Volume:** `lohnour_db_data`  
**Backup Location:** `/opt/lohnour/backups/`

---

## 1. Production Database Profile

The production database is an optimized MariaDB 10.11 LTS deployment.

- **Verified Table Inventory (14 Tables):**
  1. `wp_commentmeta`
  2. `wp_comments`
  3. `wp_links`
  4. `wp_options`
  5. `wp_postmeta`
  6. `wp_posts` (92 records verified)
  7. `wp_sib_model_forms` (Brevo email integration)
  8. `wp_sib_model_users` (Brevo subscriber contacts)
  9. `wp_term_relationships`
  10. `wp_term_taxonomy`
  11. `wp_termmeta`
  12. `wp_terms`
  13. `wp_usermeta`
  14. `wp_users` (Sole administrator: `samarpit`, ID 1)

- **Database Revision:** `61833`
- **Application URL Options:**
  - `siteurl`: `https://pro.lohnour.com`
  - `home`: `https://pro.lohnour.com`

---

## 2. Live Backup Procedures

### Method A: Automated Checkpoint Backup via Script

Run the automated backup script from the host VM:

```bash
sudo bash /opt/lohnour/phase8_backup.sh
```

This creates a timestamped SQL dump and WordPress archive in `/opt/lohnour/backups/`:
- `phase8-checkpoint-<timestamp>_db.sql`
- `phase8-checkpoint-<timestamp>_wp_html.tar.gz`

### Method B: Manual Consistent Point-in-Time Dump

To create an ad-hoc, crash-consistent database snapshot without locking tables:

```bash
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
DB_PASS=$(sudo grep DB_ROOT_PASSWORD /opt/lohnour/.env | cut -d= -f2)

sudo docker exec lohnour-db mysqldump \
  -u root \
  -p"${DB_PASS}" \
  --single-transaction \
  --quick \
  --routines \
  --triggers \
  --databases lohnour_wp > "/opt/lohnour/backups/manual_backup_${TIMESTAMP}.sql"

sudo chmod 600 "/opt/lohnour/backups/manual_backup_${TIMESTAMP}.sql"
echo "Backup created: /opt/lohnour/backups/manual_backup_${TIMESTAMP}.sql"
```

---

## 3. Database Restoration Runbook

> [!WARNING]
> Restoring a database snapshot will overwrite all current database tables in `lohnour_wp`. Ensure an ad-hoc snapshot is taken immediately prior to restoration.

### Step 1: Pre-Restoration Snapshot

```bash
sudo bash /opt/lohnour/phase8_backup.sh
```

### Step 2: Ingest the SQL Snapshot

Pipe the SQL snapshot directly into the running `lohnour-db` container:

```bash
DB_PASS=$(sudo grep DB_ROOT_PASSWORD /opt/lohnour/.env | cut -d= -f2)
BACKUP_FILE="/opt/lohnour/backups/<TARGET_BACKUP_FILE>.sql"

sudo docker exec -i lohnour-db mysql -u root -p"${DB_PASS}" lohnour_wp < "${BACKUP_FILE}"
```

### Step 3: Flush Privileges & Refresh WordPress Cache

```bash
sudo docker exec lohnour-wordpress wp cache flush --allow-root
```

---

## 4. Post-Restoration Integrity Verification

Verify the database post-restoration using the standalone inspection script:

```bash
sudo python3 /opt/lohnour/scripts/inspect_wp_live.py
```

Expected JSON verification response:
```json
{
  "tables": 14,
  "posts": 92,
  "siteurl": "https://pro.lohnour.com",
  "home": "https://pro.lohnour.com",
  "db_version": 61833
}
```

If the verification script confirms 14 tables, 92 posts, and correct URLs, the restoration is complete and successful.
