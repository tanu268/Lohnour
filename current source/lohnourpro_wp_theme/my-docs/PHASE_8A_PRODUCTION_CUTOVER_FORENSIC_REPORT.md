# PHASE 8A — PRODUCTION CUTOVER FORENSIC PREPARATION

## 1. Executive Summary
A comprehensive read-only forensic analysis was performed on the Oracle server to prepare for the final production cutover. The containerized architecture is healthy, but a critical port conflict exists between the legacy host Nginx (bare-metal) and the Docker Nginx Proxy Manager (NPM). Furthermore, exactly 122 database records natively point to the `localhost:8081` origin and will require a serialized database replacement. The production domain is currently unknown. 

**Status: READY WITH CONDITIONS**

## 2. Current Architecture
- **Host**: Ubuntu, Public IP `193.122.206.31`
- **Docker Engine**: v29.9.0 / Compose v5.6.0
- **WordPress Container**: `lohnour-wordpress-1` (PHP 8.3.35, WP 7.1.3)
- **Database Container**: `lohnour-db-1` (MariaDB 10.11)
- **Proxy Container**: `lohnour-npm-1` (Nginx Proxy Manager)
- **Legacy Bare-Metal**: Running directly on the host (Nginx + MySQL)

## 3. Host Network Analysis
- **Port 80 (TCP)**: Actively bound by the host `nginx` process. It serves the `default_server` configuration.
- **Port 443 (TCP)**: Host Nginx SSL block is currently commented out in the default config, but NPM is blocked from natively using port 80.
- **Port 3306 (TCP)**: Actively bound by the host `mysqld` process, strictly listening on `127.0.0.1`.
- **Port 81 (TCP)**: Bound by NPM `docker-proxy` (Admin UI).

## 4. Docker Network Analysis
- `lohnour-npm-1` currently maps:
  - `8080->80` (Due to host Nginx conflict)
  - `8443->443`
  - `81->81`
- `lohnour-wordpress-1` exposes port 80 internally on `lohnour_network`.
- `lohnour-db-1` exposes port 3306 internally on `lohnour_network`.

## 5. Nginx Analysis (Host)
The host Nginx is serving a default configuration (`server_name _;`). It acts as a potential blocker for NPM because it owns port 80, which is required by Let's Encrypt ACME challenges and standard HTTP web traffic.

## 6. Nginx Proxy Manager Analysis
The NPM SQLite database/hosts directory (`/opt/lohnour/data/nginx/proxy_host/`) is entirely empty. No proxy hosts or certificates have been configured yet.

## 7. Firewall Analysis
- **UFW**: `inactive`
- **iptables**: Allows `0.0.0.0/0` on `tcp dpt:80` and `tcp dpt:443`.
- **CRITICAL CHECK**: Host MySQL `3306` is bound strictly to `127.0.0.1` and is **NOT** exposed publicly.

## 8. WordPress URL Inventory
- **siteurl**: `http://localhost:8081`
- **home**: `http://localhost:8081`
- **Database Search**: 122 occurrences of `http://localhost:8081` exist in the database. 110 of these are in `wp_posts.guid`, 8 in `post_content`, and 4 in `wp_options`.

## 9. Source-Code URL Inventory
Static code analysis of `twentytwentyfive-child` reveals:
- **0** hard-coded `localhost:8081` strings.
- **0** hard-coded internal IP strings.
- Internal navigation utilizes `home_url('/')`, which safely dynamically inherits the `siteurl` option.
- External social links (`https://twitter.com/`, `https://www.youtube.com/@lohnourpro`) are appropriately hard-coded and unaffected.

## 10. Asset/Media URL Inventory
Media files (images) use absolute URLs defined in the database (e.g., `http://localhost:8081/wp-content/uploads/2026/07/wallpaper-82.jpg`). These will seamlessly update during the `wp search-replace` phase.

## 11. Permalink and Canonical Analysis
- Permalinks: `/%year%/%monthnum%/%day%/%postname%/`
- The nested architecture (`/services/corporate/`, `/coaching/individual/`, `/coaching/group/`) remains fully intact following the Phase 7F repair and dynamically respects `home_url()`.

## 12. SSL Readiness
- Certbot is **not** installed on the host. 
- SSL issuance is intended to be handled natively by Nginx Proxy Manager.
- Issuance is currently **blocked** until:
  1. DNS points to the server.
  2. NPM assumes control of port 80 to resolve ACME HTTP-01 challenges.

## 13. DNS Requirements
When the production domain is known, the operator must configure:
- **A Record**: `[domain]` -> `193.122.206.31`
- *(Optional)* **A Record**: `www.[domain]` -> `193.122.206.31`

## 14. Production Domain Requirement
**Production domain required from operator before Phase 8B.** No domain could be inferred from the current environment configurations.

## 15. Cutover Risks
- **HIGH**: Host Nginx Port 80 Conflict. NPM cannot acquire Let's Encrypt certificates or serve standard traffic on Port 80 while the host Nginx occupies it. We must resolve this without destroying the bare-metal WordPress environment (e.g., re-bind host Nginx to port 8082, or configure NPM to proxy the bare-metal site).
- **HIGH**: Database URL Serialization. A manual SQL `UPDATE` will corrupt PHP serialized strings in the database. `wp search-replace` is strictly required.
- **MEDIUM**: DNS Propagation. Cutover cannot proceed until global DNS caches recognize `193.122.206.31`.

## 16. Rollback Strategy
If Phase 8B fails, the environment can be instantly reverted:
1. **Database**: Drop the container DB and restore `/var/lib/mysql/phase7f_pre_canonical.sql`.
2. **URLs**: Re-run `wp search-replace 'https://[domain]' 'http://localhost:8081'`.
3. **Artifacts**: The pristine `.wpress` artifact at `/opt/lohnour/migration/` remains untouched.
4. **Bare-Metal**: The host bare-metal site remains completely isolated from the containerized database.

## 17. Proposed Phase 8B Execution Plan
1. **Blocker Resolution**: Receive Production Domain from Operator.
2. **DNS Setup**: Operator updates DNS to point to `193.122.206.31`.
3. **Port Conflict Resolution**: Safely re-bind host Nginx away from Port 80/443 (e.g., to 8082) to free up the public edge.
4. **NPM Edge Promotion**: Update `docker-compose.yml` to bind NPM directly to `80:80` and `443:443`. Restart NPM.
5. **Proxy Configuration**: Create NPM proxy host for the production domain pointing to `lohnour-wordpress-1:80` and request Let's Encrypt SSL.
6. **URL Cutover**: Execute `wp search-replace 'http://localhost:8081' 'https://[domain]'` inside the WordPress container.
7. **Cache & Rewrite**: Flush rewrite rules and clear transients.
8. **Final QA Audit**: Run headless tests against the live domain to verify assets, canonicals, and SSL termination.

## 18. Verification Performed
The forensic audit strictly adhered to READ-ONLY policies.
- `siteurl` and `home` remain `http://localhost:8081`.
- `wp search-replace` was run exclusively with `--dry-run`.
- No Nginx configurations were altered.
- No firewall ports were opened.
- No DNS or SSL requests were made.

## 19. Changes Made
NO CHANGES MADE — READ-ONLY FORENSIC PREPARATION.

## 20. Final Readiness Status
**READY WITH CONDITIONS**
(Production domain and DNS provider confirmation are required before Phase 8B.)
