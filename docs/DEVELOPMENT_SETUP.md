# Lohnour Pro — Developer Environment Setup Guide

This guide provides reproducible, step-by-step instructions for running the complete **Lohnour Pro** WordPress development environment locally using Docker Compose.

---

## 1. Prerequisites & System Requirements

Before starting, ensure your local development workstation meets the following requirements:

| Tool | Minimum Version | Recommended | Notes |
|---|---|---|---|
| **Operating System** | Windows 10/11 (WSL2), macOS 13+, or Ubuntu 22.04+ | Windows 11 with WSL2 Ubuntu | Native Docker support |
| **Docker Desktop / Engine** | Docker 24.0+ | Docker 26.0+ | Must support Compose v2 (`docker compose`) |
| **Docker Compose** | Compose v2.20+ | Latest Compose CLI | Integrated with Docker Desktop |
| **Git** | 2.34+ | Latest Git | Required for branch management |
| **PHP (Optional)** | PHP 8.2+ | PHP 8.2 CLI | Useful for local syntax checking (`php -l`) |
| **Python (Optional)** | Python 3.10+ | Python 3.11/3.12 | Used for local test and audit scripts |

---

## 2. Repository Cloning & Branch Setup

Clone the repository and checkout the `developer` integration branch:

```bash
git clone https://github.com/tanu268/Lohnour.git
cd Lohnour
git checkout developer
git pull origin developer
```

Verify your initial Git status:

```bash
git status
```

---

## 3. Environment Configuration

1. Copy the provided `.env.example` template to `.env` in the repository root:

```bash
cp .env.example .env
```

2. Open `.env` and verify the local database settings:

```ini
# ==============================================================================
# Local Development Configuration
# ==============================================================================
LOCAL_DB_NAME=lohnour_local_wp
LOCAL_DB_USER=lohnour_local_user
LOCAL_DB_PASSWORD=LocalDevPassword2026!
LOCAL_DB_ROOT_PASSWORD=LocalRootPassword2026!
```

> [!CAUTION]
> NEVER enter production database passwords or production SSH keys into your local `.env` file unless explicitly needed for a maintenance script. `.env` is protected by `.gitignore` and must never be committed.

---

## 4. Local Container Architecture (`docker-compose.local.yml`)

The local development stack is specifically tuned for reproducibility and developer velocity:

```mermaid
graph LR
    Dev[Developer Browser<br/>http://localhost:8080] --> WP[lohnour-local-wordpress<br/>Port 8080 -> 80]
    WP --> DB[(lohnour-local-db<br/>MariaDB 10.11)]

    subgraph "Local Bind Mounts"
        ThemeFiles[./current source/lohnourpro_wp_theme] -.->|Bind Mount ro/rw| WPTheme[/var/www/html/wp-content/themes/twentytwentyfive-child]
        UploadsIni[./config/uploads.ini] -.->|Bind Mount ro| PHPConf[/usr/local/etc/php/conf.d/uploads.ini]
    end
```

### Key Differences from Production:
1. **Frontend Port:** WordPress is published directly to `localhost:8080` (production uses Caddy reverse proxy on 80/443).
2. **Theme Bind Mount:** Local changes in `current source/lohnourpro_wp_theme` reflect immediately inside the container without requiring container rebuilds.
3. **URL Overrides:** The container entrypoint automatically overrides `WP_HOME` and `WP_SITEURL` to `http://localhost:8080` to prevent canonical redirects to `https://pro.lohnour.com`.
4. **Outbound Mail Stubbed:** Outbound email (`wp_mail`) is stubbed in the container config to prevent accidental emails to real users during local testing.

---

## 5. Starting the Local Environment

Run the following command to spin up the local stack:

```bash
docker compose -f docker-compose.local.yml up -d
```

### Checking Container Health

Verify both containers are running and healthy:

```bash
docker compose -f docker-compose.local.yml ps
```

Expected output:
```text
NAME                     IMAGE                           COMMAND                  SERVICE      CREATED         STATUS                   PORTS
lohnour-local-db         mariadb:10.11.19                "docker-entrypoint.s…"   db           5 seconds ago   Up 5 seconds (healthy)   
lohnour-local-wordpress  wordpress:7.1.4-php8.2-apache   "docker-entrypoint.s…"   wordpress    5 seconds ago   Up 4 seconds             0.0.0.0:8080->80/tcp
```

---

## 6. WordPress First-Time Initialization

When starting with a fresh local database volume, access WordPress in your browser:

1. Open [http://localhost:8080](http://localhost:8080).
2. Follow the 1-minute WordPress setup wizard:
   - **Site Title:** Lohnour Pro Local
   - **Username:** `admin`
   - **Password:** `AdminPassword123!` (or your chosen local password)
   - **Your Email:** `dev@lohnour.local`
3. Click **Install WordPress** and log in.

### Activating the Child Theme & Custom CMS Plugin

Once logged into `http://localhost:8080/wp-admin`:

1. Navigate to **Appearance > Themes**.
2. Activate **Twenty Twenty-Five Child**.
3. Navigate to **Plugins > Installed Plugins**.
4. Activate **Lohnour Program CMS** (if present in `wp-content/plugins/`).

---

## 7. Working with Theme & Plugin Code

### Theme Location
The active child theme is located at:
`current source/lohnourpro_wp_theme/`

- **Style Overrides:** `style.css` and `assets/css/`
- **Template Files:** `front-page.php`, `page-about.php`, `archive-lohnour_service.php`, etc.
- **PHP Helpers & Post Types:** `inc/`
- **Navigation & Scripts:** `assets/js/`

### Validating PHP Syntax Locally

Before committing changes, validate PHP files for syntax errors:

```bash
php -l "current source/lohnourpro_wp_theme/functions.php"
php -l "current source/lohnourpro_wp_theme/front-page.php"
```

---

## 8. Stopping and Resetting the Environment

### Stop Containers (Preserve Data)

```bash
docker compose -f docker-compose.local.yml stop
```

### Restart Containers

```bash
docker compose -f docker-compose.local.yml start
```

### Complete Teardown (Deletes Local Database & WP Data)

> [!CAUTION]
> This command deletes local database volumes. Use only when you want a completely fresh start.

```bash
docker compose -f docker-compose.local.yml down -v
```

---

## 9. Common Troubleshooting

### 1. Port 8080 is already in use
If another service is using port 8080, open `docker-compose.local.yml` and change the port mapping:
```yaml
ports:
  - "8085:80"
```
And adjust `WP_HOME` / `WP_SITEURL` accordingly.

### 2. Database Connection Error (`Error establishing a database connection`)
Wait 15-20 seconds for MariaDB to initialize on first launch. You can check the database logs:
```bash
docker compose -f docker-compose.local.yml logs db
```

### 3. Redirection to `pro.lohnour.com`
If the browser redirects to the production domain, clear your browser cache or run in an incognito window. The container sets `define('WP_HOME', 'http://localhost:8080');` to lock the URL locally.

