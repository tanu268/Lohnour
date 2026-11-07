# Lohnour Pro — Authoritative Documentation Index

This directory serves as the centralized, authoritative documentation library for **Lohnour Pro**. All architectural specifications, operational runbooks, developer guides, and forensic phase audits are cataloged below.

---

## 1. Core Architecture & Infrastructure

| Document | Description | Scope |
|---|---|---|
| [`PRODUCTION_ARCHITECTURE.md`](PRODUCTION_ARCHITECTURE.md) | Technical architecture of Sam's Oracle Cloud VM (`141.148.51.166`): containers, network isolation, volume boundaries, and Caddy TLS termination | Production |
| [`DEPLOYMENT_ARCHITECTURE.md`](DEPLOYMENT_ARCHITECTURE.md) | Ingress routing, reverse proxy specifications, and container topology | Infrastructure |
| [`INFRASTRUCTURE_AUDIT.md`](INFRASTRUCTURE_AUDIT.md) | Baseline infrastructure audit of Oracle Linux Server 9.7 ARM64 host | Infrastructure |

---

## 2. Operations & Deployment Runbooks

| Document | Description | Target |
|---|---|---|
| [`DEPLOYMENT_GUIDE.md`](DEPLOYMENT_GUIDE.md) | 5-stage fail-closed production deployment lifecycle and acceptance gates | Production VM |
| [`ROLLBACK_GUIDE.md`](ROLLBACK_GUIDE.md) | Fast-path emergency rollback procedures for theme, Compose, and database failures | Recovery |
| [`DATABASE_BACKUP_RESTORE.md`](DATABASE_BACKUP_RESTORE.md) | MariaDB crash-consistent backup procedures (14 tables), point-in-time snapshots, and restoration | Database |
| [`BACKUP_AND_ROLLBACK.md`](BACKUP_AND_ROLLBACK.md) | Comprehensive system-wide checkpointing and container volume snapshot guide | System |
| [`PRE_DEPLOYMENT_CHECKLIST.md`](PRE_DEPLOYMENT_CHECKLIST.md) | Mandatory pre-flight verification gates before staging production changes | Deployment |

---

## 3. Developer Onboarding & Governance

| Document | Description | Audience |
|---|---|---|
| [`DEVELOPMENT_SETUP.md`](DEVELOPMENT_SETUP.md) | Reproducible local development onboarding using `docker-compose.local.yml` | Developers |
| [`ENVIRONMENT_VARIABLES.md`](ENVIRONMENT_VARIABLES.md) | Full reference of all environment variables across local, CI, and production environments | All |
| [`GITHUB_WORKFLOW.md`](GITHUB_WORKFLOW.md) | Multi-developer Git flow, branch topology (`main`, `developer`, `feature/*`), and PR review rules | Contributors |

---

## 4. Security & Post-Deployment Audits

| Document | Description | Classification |
|---|---|---|
| [`SECURITY_REVIEW.md`](SECURITY_REVIEW.md) | Workload isolation, port exposure review (MariaDB, WordPress, Node-RED, Portainer) | Security |
| [`POST_GITHUB_SYNC_VERIFICATION.md`](POST_GITHUB_SYNC_VERIFICATION.md) | Verification of GitHub remote, actual CI execution, and theme reconciliation | Verification |
| [`GITHUB_PRODUCTION_RECONCILIATION.md`](GITHUB_PRODUCTION_RECONCILIATION.md) | Three-way forensic comparison between local code, GitHub, and Oracle VM | Audit |

---

## 5. Historical Phase Execution Reports (`docs/reports/`)

Historical forensic reports generated during migration and cutover phases are maintained for regulatory and audit compliance:

- [`reports/LOHNOUR_REPOSITORY_AND_SECURITY_REMEDIATION_REPORT.md`](reports/LOHNOUR_REPOSITORY_AND_SECURITY_REMEDIATION_REPORT.md) — **Master Governance, Security & Reconciliation Report**
- `PHASE_1_PRODUCTION_BACKUP_REPORT.md` — Initial production baseline backup
- `PHASE_2_5_ORACLE_CLEAN_REDEPLOYMENT_REPORT.md` — Fresh container stack redeployment
- `PHASE_6_FINAL_PRODUCTION_BROWSER_QA_REPORT.md` — End-to-end browser acceptance QA
- `PHASE_7_PRODUCTION_BACKUP_AND_ROLLBACK_READINESS.md` — Backup script validation
- `PHASE_8_PRODUCTION_HOST_BACKUP_REPORT.md` — Automated host-level backup
- `PHASE_9_PRODUCTION_INFRASTRUCTURE_REPORT.md` — Ingress reverse proxy evaluation
- `PHASE_10_FINAL_EXECUTION_REPORT.md` — MariaDB database restoration & table verification
- `PHASE_11_FILE_RESTORATION_REPORT.md` — WordPress core, plugin, and media uploads restoration
- `PHASE_12_PORTAINER_DEPLOYMENT_REPORT.md` — WordPress application stack launch and health verification
- `PHASE_13_PRODUCTION_DEPLOYMENT_REPORT.md` — Caddy reverse proxy deployment and Let's Encrypt TLS cutover
