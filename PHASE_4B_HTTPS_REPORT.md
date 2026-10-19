# Phase 4B — HTTPS / Let's Encrypt Acceptance Report

**Execution Date:** 2026-10-11  
**Target Domain:** `pro.lohnour.com`  
**Host Target:** `193.122.206.31` (Oracle Cloud Infrastructure)  
**Status:** **SUCCESSFUL**

---

## 1. Executive Summary

In Phase 4B, HTTPS was successfully provisioned and enabled for `pro.lohnour.com` via Nginx Proxy Manager (NPM) using a genuine Let's Encrypt TLS certificate.

- **DNS Verification:** `pro.lohnour.com` resolved to `193.122.206.31`.
- **Certificate Issuance:** ACME HTTP-01 challenge completed successfully; Let's Encrypt certificate issued for `pro.lohnour.com`.
- **Proxy Host Configuration:** Proxy Host 1 updated with SSL certificate, Force SSL (`ssl_forced: true`), and HTTP/2 (`http2_support: true`).
- **HTTP -> HTTPS Redirection:** Verified `HTTP/1.1 301 Moved Permanently` to `https://pro.lohnour.com/`.
- **HTTPS Status:** Verified `HTTP/2 200 OK` over HTTPS with valid certificate.
- **Backend Routing:** Traffic routed cleanly to `wordpress:80`, rendering `<title>Lohnour Pro</title>`.

---

## 2. Certificate Details

- **Common Name (CN):** `pro.lohnour.com`
- **Issuer:** `C = US, O = Let's Encrypt, CN = YE1`
- **Certificate Path:** `/etc/letsencrypt/live/npm-1/fullchain.pem`
- **Private Key Path:** `/etc/letsencrypt/live/npm-1/privkey.pem`
- **Validity Start (notBefore):** Oct 11 16:49:31 2026 GMT
- **Validity End (notAfter):** Jan  9 16:49:30 2027 GMT
- **Key Type:** RSA 2048-bit

```
subject=CN = pro.lohnour.com
issuer=C = US, O = Let's Encrypt, CN = YE1
notBefore=Oct 11 16:49:31 2026 GMT
notAfter=Jan  9 16:49:30 2027 GMT
```

---

## 3. Nginx Proxy Manager Configuration

Generated Nginx configuration snippet (`/data/nginx/proxy_host/1.conf`):
```nginx
server {
  set $forward_scheme http;
  set $server         "wordpress";
  set $port           80;

  listen 80;
  listen [::]:80;

  listen 443 ssl;
  listen [::]:443 ssl;

  server_name pro.lohnour.com;

  http2 on;

  # Let's Encrypt SSL
  include conf.d/include/letsencrypt-acme-challenge.conf;
  include conf.d/include/ssl-cache.conf;
  include conf.d/include/ssl-ciphers.conf;
  ssl_certificate /etc/letsencrypt/live/npm-1/fullchain.pem;
  ssl_certificate_key /etc/letsencrypt/live/npm-1/privkey.pem;

  # Force SSL
  include conf.d/include/force-ssl.conf;

  # Block Exploits
  include conf.d/include/block-exploits.conf;

  proxy_set_header Upgrade $http_upgrade;
  proxy_set_header Connection $http_connection;
  proxy_http_version 1.1;

  access_log /data/logs/proxy-host-1_access.log proxy;
  error_log /data/logs/proxy-host-1_error.log warn;

  location / {
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection $http_connection;
    proxy_http_version 1.1;

    # Proxy!
    include conf.d/include/proxy.conf;
  }
}
```

---

## 4. Verification Evidence

### A. HTTP Redirection
```bash
curl -s -I -H 'Host: pro.lohnour.com' http://127.0.0.1/
```
```http
HTTP/1.1 301 Moved Permanently
Server: openresty
Date: Sun, 11 Oct 2026 17:48:26 GMT
Content-Type: text/html
Content-Length: 166
Connection: keep-alive
Location: https://pro.lohnour.com/
```

### B. HTTPS Response Headers
```bash
curl -s -I https://pro.lohnour.com/
```
```http
HTTP/2 200 
server: openresty
date: Sun, 11 Oct 2026 17:48:27 GMT
content-type: text/html; charset=UTF-8
x-powered-by: PHP/8.3.35
x-served-by: pro.lohnour.com
```

### C. Content Title
```bash
curl -s -L https://pro.lohnour.com/ | grep -i '<title>'
```
```html
<title>Lohnour Pro</title>
```

*(Note: In accordance with Phase 4A diagnosis, WordPress assets and styling still reference `http://localhost:8081` until Phase 5 database URL migration. This is expected behavior at Phase 4B).*

---

## 5. Container Health Check

All 4 containers remain up and healthy:
```
NAMES                 STATUS                PORTS
lohnour-npm-1         Up About an hour      0.0.0.0:80-81->80-81/tcp, [::]:80-81->80-81/tcp, 0.0.0.0:443->443/tcp, [::]:443->443/tcp
lohnour-wordpress-1   Up 10 hours           80/tcp
lohnour-db-1          Up 2 days (healthy)   3306/tcp
portainer             Up 2 days             8000/tcp, 9000/tcp, 0.0.0.0:9443->9443/tcp, [::]:9443->9443/tcp
```

---

## 6. Safety Compliance Statement

- **WordPress database URLs (`siteurl`, `home`):** Unmodified (pending Phase 5).
- **`wp search-replace`:** Not executed.
- **Database tables:** No post or option data altered.
- **Cloudflare DNS records:** Untouched.
- **Bare-metal `/var/www/html`:** Untouched and preserved.
- **WordPress files/plugins:** Untouched.
