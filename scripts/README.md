# Lohnour Pro — Operational & Verification Scripts

This directory houses the authoritative operational, backup, recovery, and verification automation scripts used for managing the **Lohnour Pro** infrastructure.

---

## 1. Production Recovery & Deployment Scripts

| Script | Purpose | Execution Target | Scope |
|---|---|---|---|
| `phase8_backup.sh` | Automated host-level crash-consistent backup of MariaDB (`lohnour_wp`) and WordPress HTML volume | Sam's Oracle VM | Host (`sudo bash`) |
| `phase10_db_recovery.sh` | Point-in-time MariaDB restoration and integrity verification | Sam's Oracle VM | Host (`sudo bash`) |
| `phase11_file_recovery.sh` | WordPress core, custom plugin, child theme, and uploads volume restoration | Sam's Oracle VM | Host (`sudo bash`) |
| `phase12_portainer_deploy.sh` | Controlled container stack deployment with health checks | Sam's Oracle VM | Host (`sudo bash`) |
| `execute_phase13_caddy_deploy.py`| Caddy reverse proxy deployment and Let's Encrypt automated TLS validation | Workstation / VM | Remote SSH / Local |

---

## 2. Integrity & Health Audit Scripts

| Script | Purpose | Runtime |
|---|---|---|
| `inspect_wp_live.py` | Standalone WordPress integrity check (verifies table count = 14, post count = 92, siteurl) | Python 3 |
| `audit_container_health.py` | Container runtime inspect (checks state, restarts = 0, OOM = 0) | Python 3 |
| `audit_database_integrity.py` | Deep table and post query audit | Python 3 |
| `test_9_canonical_routes.py` | Automated HTTP smoke test verifying 9/9 canonical public routes return HTTP 200 OK | Python 3 |
| `measure_public_https_routes.py` | High-precision latency and response size measurement across public HTTPS routes | Python 3 |

---

## 3. Content & Database Utilities

| Script | Purpose | Runtime | Scope |
|---|---|---|---|
| `migrate_user_guide_lessons.php` | Non-destructive, idempotent user guide lesson hierarchy migration utility | PHP CLI | WordPress Core |

---

## 4. Legacy & Diagnostic Scripts Notice

Historical ad-hoc diagnostic scripts (`audit_*.py`, `check_*.py`, `inspect_*.py`, `test_*.py`) remaining at the repository root were committed in initial historical baselines for forensic migration debugging. They are preserved for audit completeness, but all future operational tooling must be added directly into `scripts/`.
