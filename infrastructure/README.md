# Lohnour Pro — Canonical Infrastructure Configuration

This directory contains the canonical, version-controlled infrastructure definitions for both production deployment on Oracle Cloud Infrastructure (OCI) and local reproducible development.

## Directory Structure

```text
infrastructure/
├── docker-compose.yml        # Authoritative production Compose specification (ARM64)
├── Caddyfile                 # Authoritative Caddy v2 reverse proxy and TLS configuration
├── config/
│   └── uploads.ini           # PHP custom runtime configuration (512M upload limits)
├── docker-compose.local.yml  # Local development Compose specification
└── README.md                 # Infrastructure architecture documentation
```

> [!IMPORTANT]
> The root of this repository contains identical copies of `docker-compose.yml`, `Caddyfile`, `config/uploads.ini`, and `docker-compose.local.yml` for direct CLI execution compatibility (`docker compose up -d`).

---

## 1. Production Architecture Overview

The production stack runs on a dedicated Oracle Cloud VM (`141.148.51.166` / `sams-oracle-vm`) running Oracle Linux Server 9.7 ARM64 (`aarch64`).

### Container Topology & Workload Isolation

| Service | Container Name | Image Digest / Tag | Published Host Ports | Internal Networks | Persistent Storage |
|---|---|---|---|---|---|
| **Reverse Proxy** | `lohnour-caddy` | `caddy@sha256:78bd35025c...` | `80:80`, `443:443` | `proxy_network` | `caddy_data`, `caddy_config`, `/opt/lohnour/Caddyfile` (ro) |
| **Application** | `lohnour-wordpress` | `wordpress:7.1.4-php8.2-apache` | *None* (Private) | `proxy_network`, `lohnour_db_network` | `lohnour_wp_data`, `/opt/lohnour/config/uploads.ini` (ro) |
| **Database** | `lohnour-db` | `mariadb@sha256:0de9840f23...` | *None* (Private) | `lohnour_db_network` | `lohnour_db_data` |

### Security Invariants

1. **Zero Database Host Port Exposure:** Port `3306` is attached strictly to `lohnour_db_network`. It is unreachable from the host interface and public internet.
2. **Zero WordPress Apache Port Exposure:** Port `80` is attached strictly to `proxy_network` and `lohnour_db_network`.
3. **Dedicated Ingress Isolation:** Caddy terminates TLS (Let's Encrypt automated ACME) and reverse-proxies requests over HTTP to `lohnour-wordpress:80`.
4. **Environment Isolation:** Secrets are injected at runtime via `/opt/lohnour/.env` (`0600 root:root`) and are **never** committed to version control.

---

## 2. Local Reproducible Development Stack

For local development and testing, use `docker-compose.local.yml`:

```bash
# 1. Prepare environment
cp .env.example .env

# 2. Launch local stack
docker compose -f docker-compose.local.yml up -d
```

This starts:
- WordPress on `http://localhost:8080`
- Isolated local MariaDB instance
- Bind-mounted local child theme (`./current source/lohnourpro_wp_theme`)
