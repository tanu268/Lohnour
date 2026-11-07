# LOHNOUR — PHASE 6: SECURE BACKUP TRANSFER & SHA256 VERIFICATION REPORT

**Document Identifier:** `PHASE_6_BACKUP_TRANSFER_REPORT.md`  
**Execution Timestamp (UTC):** 2026-11-06 14:42:00 UTC – 14:46:00 UTC  
**Execution Timestamp (IST):** 2026-11-06 20:12:00 IST – 20:16:00 IST  
**Role:** Senior DevOps Engineer  
**Scope:** Authorized Backup Transfer & Cryptographic Verification ONLY  
**Target Host:** Sam's Oracle VM (`sams-oracle-vm`, `141.148.51.166`)  
**Status:** **PHASE 6 COMPLETE — 100% CRYPTOGRAPHIC INTEGRITY CONFIRMED**

---

## 1. Connection & Authorization Baseline

- **Target Public IP:** `141.148.51.166`
- **SSH User:** `opc`
- **Authentication:** SSH Private Key (`D:\Downloads\ssh-key-2026-05-11.key`)
- **Key Exposure:** None. The private key was neither read, printed, logged, copied, nor transmitted.
- **SSH Connectivity:** Verified active and responsive (`BatchMode=yes`).

---

## 2. Remote Staging Directory Creation

- **Target Path:** `/opt/lohnour/staging`
- **Pre-Creation Check:** Inspected `/opt/lohnour` and `/opt/lohnour/staging`. Confirmed non-existent prior to execution.
- **Directory Creation Command:** 
  ```bash
  sudo mkdir -p /opt/lohnour/staging && sudo chown -R opc:opc /opt/lohnour && sudo chmod 0755 /opt/lohnour && sudo chmod 0700 /opt/lohnour/staging
  ```
- **Observed Permissions & Ownership:**
  ```text
  drwxr-xr-x. 3 opc opc 21 Nov  6 14:42 /opt/lohnour
  drwx------. 2 opc opc  6 Nov  6 14:42 /opt/lohnour/staging
  ```
  - Mode: `0700` (`drwx------`) — restricted strictly to user `opc`.

---

## 3. Pre-Transfer Local Validation

All four backup artifacts located in `D:\lohnourpro\_audit\production-extract` were verified locally using PowerShell `Get-FileHash -Algorithm SHA256` before opening the transfer session.

| Filename | Local File Path | Local Size (Bytes) | Computed SHA256 Checksum | Match Status |
|---|---|:---:|---|:---:|
| `production-lohnour_wp.sql` | `database\production-lohnour_wp.sql` | 290,627 | `db8628e7743b133e372265a7d09ac29729ab7f7c8ed9dfcd0bdd257d7e88a880` | **MATCH** |
| `uploads.tar.gz` | `uploads.tar.gz` | 59,733,182 | `5001b5d43159e4611540661d8af50a9fd0301e06a4224587cb38f7f68983b0d9` | **MATCH** |
| `lohnour_all_plugins.tar.gz` | `lohnour_all_plugins.tar.gz` | 5,646,919 | `1e2c4bb948ea06621a52b1911b6d6a29868695798d8e37753454643e0be68484` | **MATCH** |
| `lohnour_program_cms.tar.gz` | `lohnour_program_cms.tar.gz` | 18,777 | `989eaf8941a27f37bfc7576954a22fe6f64f121ee44c5a9f66e4e6b4777dd5ec` | **MATCH\*** |

*\*Note: The trusted SHA256 string for `lohnour_program_cms.tar.gz` in Section 2 of the prompt had a 2-character typographical truncation (`...66e4b4777dd5ec`, 62 hex characters instead of 64). The actual 64-character SHA256 hash verified identically to the canonical manifest created during Phase 3.6 / Phase 4 (`...66e4e6b4777dd5ec`).*

---

## 4. Secure Transfer Execution

The four artifacts and the `SHA256SUMS` manifest were transferred to Sam's Oracle VM using OpenSSH SCP over port 22:

```powershell
scp -i D:\Downloads\ssh-key-2026-05-11.key "D:\lohnourpro\_audit\production-extract\SHA256SUMS" "D:\lohnourpro\_audit\production-extract\database\production-lohnour_wp.sql" "D:\lohnourpro\_audit\production-extract\lohnour_program_cms.tar.gz" "D:\lohnourpro\_audit\production-extract\lohnour_all_plugins.tar.gz" opc@141.148.51.166:/opt/lohnour/staging/

scp -i D:\Downloads\ssh-key-2026-05-11.key "D:\lohnourpro\_audit\production-extract\uploads.tar.gz" opc@141.148.51.166:/opt/lohnour/staging/
```

- **Transfer Exit Code:** `0` (Success across all files)
- **Files Excluded:** Zero `.env` files, zero private keys, zero source code files, zero database passwords transferred.

---

## 5. Remote Checksum Verification

Remote verification was performed inside `/opt/lohnour/staging` via OpenSSH:

### 1. Verification Command Output (`sha256sum -c SHA256SUMS`)
```text
production-lohnour_wp.sql: OK
uploads.tar.gz: OK
lohnour_all_plugins.tar.gz: OK
lohnour_program_cms.tar.gz: OK
```

### 2. Remote Raw SHA256 Calculation
```text
db8628e7743b133e372265a7d09ac29729ab7f7c8ed9dfcd0bdd257d7e88a880  production-lohnour_wp.sql
5001b5d43159e4611540661d8af50a9fd0301e06a4224587cb38f7f68983b0d9  uploads.tar.gz
1e2c4bb948ea06621a52b1911b6d6a29868695798d8e37753454643e0be68484  lohnour_all_plugins.tar.gz
989eaf8941a27f37bfc7576954a22fe6f64f121ee44c5a9f66e4e6b4777dd5ec  lohnour_program_cms.tar.gz
```

### 3. Remote Staging Directory Listing (`ls -la /opt/lohnour/staging`)
```text
total 64160
drwx------. 2 opc opc      147 Nov  6 14:43 .
drwxr-xr-x. 3 opc opc       21 Nov  6 14:42 ..
-rw-r--r--. 1 opc opc  5646919 Nov  6 14:43 lohnour_all_plugins.tar.gz
-rw-r--r--. 1 opc opc    18777 Nov  6 14:42 lohnour_program_cms.tar.gz
-rw-r--r--. 1 opc opc   290627 Nov  6 14:42 production-lohnour_wp.sql
-rw-r--r--. 1 opc opc      359 Nov  6 14:42 SHA256SUMS
-rw-r--r--. 1 opc opc 59733182 Nov  6 14:45 uploads.tar.gz
```

- **Byte Parity:** Every single file on the remote host matches the local size down to the exact byte.
- **Unexpected Files:** Zero unexpected files or artifacts present in `/opt/lohnour/staging`.

---

## 6. Strict Prohibition Compliance Verification

| Prohibited Action | Compliance Status | Evidence / Verification |
|---|:---:|---|
| **SQL Database Restoration / Import** | **COMPLIED** | Database not touched. No MariaDB container or client invoked. |
| **Production Docker Volume Modification** | **COMPLIED** | No volumes created, deleted, or modified. Only `nodered_data` and `portainer_data` exist. |
| **Docker Network Modification** | **COMPLIED** | No networks created, deleted, or modified. |
| **Container Lifecycle Actions** | **COMPLIED** | No containers pulled, started, stopped, restarted, or recreated. |
| **Preservation of Portainer & Node-RED** | **COMPLIED** | Both containers confirmed running healthy with 5 months continuous uptime. |
| **Reverse Proxy / WordPress Deployment** | **COMPLIED** | Neither Caddy nor WordPress was pulled or deployed. |
| **DNS Record Modification** | **COMPLIED** | No DNS records altered. |
| **Firewall / OCI Ingress Rule Modification** | **COMPLIED** | Zero iptables or firewalld rules altered. OCI VCN untouched. |
| **Archive Extraction on Host** | **COMPLIED** | Archives remain sealed in `/opt/lohnour/staging`. Zero files extracted. |
| **Git Operations** | **COMPLIED** | Zero changes staged, committed, or pushed to Git repository. |

---

## 7. Conclusion & Next Action

- **Phase 6 Completion Criteria:** **MET (100%)**
- All four authoritative production backup archives are securely staged on Sam's Oracle VM inside `/opt/lohnour/staging` with permissions `0700` (`opc:opc`), and all four remote SHA256 checksums match their trusted values with `OK`.
- **STOPPED AS DIRECTED.** No production deployment or restoration has been executed.
- Awaiting explicit user authorization before proceeding to Phase 7.

