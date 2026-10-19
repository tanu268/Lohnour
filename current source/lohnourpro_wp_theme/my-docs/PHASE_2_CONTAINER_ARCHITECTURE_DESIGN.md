# LOHNOUR PRO
# PHASE 2 — CONTAINER ARCHITECTURE + MIGRATION DESIGN

## 1. Executive Summary
This document outlines the target containerized architecture for LOHNOUR PRO, designed to securely transition from the existing bare-metal bootstrap environment to a Docker + Portainer managed stack. The architecture provides isolation, scalability, and accommodates future Agentic AI workloads while preserving a safe rollback path.

## 2. Phase 1 Inputs
- **Docker/Portainer:** Not installed.
- **Host Nginx:** Active on port 80.
- **Host MySQL:** Active on 127.0.0.1:3306.
- **Host PHP-FPM:** Active.
- **WordPress:** Bare-metal bootstrap environment active in `/var/www/html`.
- **Resources:** 2 CPU cores, 11 GiB RAM (~10 GiB available), ~41 GB free disk.

## 3. Current Infrastructure
The VPS is currently running a traditional bare-metal LEMP stack on Ubuntu 22.04.5 LTS. While functional, it does not meet the desired target state of a Portainer-managed containerized deployment.

## 4. Target Architecture
The proposed architecture introduces Docker and a reverse proxy to route traffic securely.
- **Docker Engine:** Core container runtime.
- **Docker Compose:** Stack definition format for Portainer.
- **Portainer:** Stack and container management UI.
- **Reverse Proxy:** Nginx Proxy Manager (NPM) is recommended for its intuitive UI, seamless Let's Encrypt integration, and native Portainer compatibility.
- **WordPress:** Official `wordpress:latest` (Apache-based) container. We recommend this over FPM to simplify configuration and guarantee maximum compatibility with the `.wpress` migration plugin out of the box.
- **Database:** `mariadb:10.11` (or `mysql:8.0`) containerized, fully isolated from the host.

## 5. Architecture Diagram
```text
Internet
   │
   ▼
Port 80/443
   │
   ▼
Reverse Proxy (Nginx Proxy Manager - Docker)
   │
   ├──▶ Portainer (Port 9000/9443 - Internal/Proxy only)
   │
   └──▶ LOHNOUR Stack (Docker Compose managed by Portainer)
          │
          ▼
    WordPress Container
          │
    (lohnour_network - Private Bridge)
          │
          ▼
    Database Container (No public ports exposed)

Future AI services
   │
   └──▶ Separate Docker Stacks (Proxied via NPM)
```

## 6. Container Design
- **Nginx Proxy Manager:** 1 container (`jc21/nginx-proxy-manager:latest`) managing proxy hosts.
- **Portainer:** 1 container (`portainer/portainer-ce:latest`) managing stacks.
- **LOHNOUR Stack:** 2 containers (1 WordPress, 1 Database).

## 7. Network Design
- **lohnour_network:** A user-defined private bridge network for the LOHNOUR stack. The WordPress container and Database container will communicate exclusively over this network.
- **proxy_network:** An external bridge network connecting Nginx Proxy Manager to Portainer and the WordPress container.
- The database is **never** attached to the `proxy_network` and exposes no ports to the host.

## 8. Volume Design
- **lohnour_wp_data:** Persistent named volume mapped to `/var/www/html` in the WP container.
- **lohnour_db_data:** Persistent named volume mapped to `/var/lib/mysql`.
- **portainer_data:** Persistent named volume for Portainer configs.
- **npm_data** / **npm_letsencrypt:** Persistent named volumes for Nginx Proxy Manager.
*The `.wpress` archive will be uploaded via the WordPress UI post-installation; no direct host volume mapping is required for it.*

## 9. Port Design
- **Port 80/443:** Nginx Proxy Manager (Requires Host Nginx to be disabled or remapped).
- **Port 81:** Nginx Proxy Manager Admin UI (Can be proxied later).
- **Port 9000/9443:** Portainer (Restricted by firewall or proxied).
- **Port 3306:** Host MySQL (Will remain untouched initially; Docker DB will NOT bind to 3306 on the host).

## 10. Portainer Design
- **Image:** `portainer/portainer-ce:latest`
- **Volume:** `portainer_data`
- **Socket:** Requires `/var/run/docker.sock` bind mount.
- **Access:** Exposed locally on 9443, to be proxied securely via Nginx Proxy Manager.

## 11. Host Nginx Strategy
**Recommendation: OPTION B (Host Nginx replaced by Dockerized Reverse Proxy)**
- **Why:** To achieve a fully containerized environment, the reverse proxy must also be Dockerized. This allows Portainer to manage routing and SSL certificates cleanly.
- **Strategy:** Stop and disable Host Nginx immediately prior to starting Nginx Proxy Manager. If rollback is needed, stop NPM and restart Host Nginx.

## 12. Database Strategy
**Recommendation: OPTION B (Move to a dedicated Docker database container)**
- **Why:** Maximum isolation, portability, and native Portainer stack management. Using the host MySQL defeats the purpose of the containerized stack.
- **Strategy:** A new empty DB container will be spun up. The `.wpress` migration handles the database restoration implicitly. The host MySQL remains untouched as part of the rollback safety net.

## 13. .wpress Migration Strategy
- The All-in-One WP Migration plugin will be installed inside the new Dockerized WordPress container.
- PHP/Nginx limits inside the container (and NPM) must be adjusted to allow >100MB uploads.
- The `.wpress` file will be uploaded through the WP Admin interface and restored natively.
- No temporary host storage mapping is required for the migration archive.

## 14. Resource Strategy
- **WordPress + DB:** ~500MB - 1GB RAM.
- **NPM + Portainer:** ~300MB RAM.
- **Total Estimated Usage:** ~1.3GB RAM.
- **Remaining RAM:** ~8.7GB available for future AI services.
- **Feasibility:** Excellent. The VPS can comfortably host this stack and future lightweight AI agents. Heavy GPU/LLM inference is not supported.

## 15. Security Strategy
- **Database:** No host ports bound (`3306` isolated).
- **Docker Socket:** Bound only to Portainer; no external exposure.
- **Secrets:** DB passwords passed via `.env` files in Portainer or Docker Secrets.
- **HTTPS:** Managed exclusively by Nginx Proxy Manager via Let's Encrypt (Phase 7+).
- **Firewall:** UFW to restrict all ports except 80, 443, and 22.

## 16. Rollback Strategy
The existing bare-metal environment serves as the ultimate rollback.
- **Trigger:** Migration failure, unrecoverable container crash, or performance degradation.
- **Procedure:**
  1. `docker compose down` (or stop stacks in Portainer).
  2. `sudo systemctl start nginx` (Restores port 80 to bare-metal WP).
  3. Bare-metal WP reconnects to the untouched Host MySQL.
- **Preservation:** `/var/www/html` and Host MySQL must **NOT** be deleted until final production sign-off.

## 17. Future AI Compatibility
- Future AI agent stacks can be deployed via Portainer.
- They will be placed on their own private networks.
- They will be exposed via Nginx Proxy Manager using subdomains (e.g., `ai.lohnourpro.com`).
- Strict memory limits (`deploy.resources.limits.memory`) will be applied in Compose to prevent AI workloads from starving the WordPress containers.

## 18. Risks

| Risk | Severity | Impact | Mitigation |
|------|----------|--------|------------|
| Port 80 Conflict | High | Docker Proxy fails to start | Stop Host Nginx before deploying Proxy. |
| Upload Limits | Medium | `.wpress` import fails | Configure NPM and WP container limits to 256M. |
| Rollback Failure | High | Total site outage | Do not delete Host DB or `/var/www/html` until sign-off. |

## 19. Recommended Final Architecture
**RECOMMENDED ARCHITECTURE:** A 3-Tier Dockerized setup managed by Portainer.
- **Tier 1:** Nginx Proxy Manager (handles 80/443).
- **Tier 2:** WordPress container (`wordpress:latest` Apache) on `lohnour_network`.
- **Tier 3:** MariaDB container on `lohnour_network` (no exposed ports).
- **Management:** Portainer CE with Docker socket access.
*This approach guarantees security, separation of concerns, easy SSL management, and perfect portability for the LOHNOUR project.*

## 20. Phase 3 Readiness
### Ready
- VPS resources verified.
- Rollback environment verified.
- Target architecture defined.

### Blocked
- None.

### Decisions requiring approval
- Approval to stop Host Nginx and install Docker in Phase 3.

## 21. Explicit Statement of Changes
NO INFRASTRUCTURE CHANGES WERE MADE IN PHASE 2.
