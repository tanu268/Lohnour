#!/usr/bin/env bash
# ==============================================================================
# PROPOSED — NOT EXECUTED
# LOHNOUR PRO — PHASE 8: PRODUCTION HOST PRE-DEPLOYMENT BACKUP SCRIPT
# Target: Sam's Oracle VM (141.148.51.166) | Execution: ROOT ONLY
# Canonical Version: Phase 7 Final Closure
# ==============================================================================
set -euo pipefail

# 1. Root Privilege Gate & Secure Umask
if [ "$(id -u)" -ne 0 ]; then
    echo "FATAL: Pre-deployment backup script must be executed as root (e.g. sudo bash $0)." >&2
    exit 1
fi
umask 077

TARGET_PORTAINER="${TARGET_PORTAINER:-portainer}"
TARGET_PORTAINER_VOL="${TARGET_PORTAINER_VOL:-portainer_data}"
TARGET_NODERED="${TARGET_NODERED:-nodered}"
TARGET_NODERED_VOL="${TARGET_NODERED_VOL:-nodered_data}"

BACKUP_TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="${BACKUP_DIR:-/opt/lohnour/backups/phase8_${BACKUP_TIMESTAMP}}"
mkdir -p "${BACKUP_DIR}"
chmod 700 "${BACKUP_DIR}"

# 2. Storage Preflight Check (1,500 MB minimum required)
MIN_REQUIRED_MB=1500
AVAIL_MB=$(df -m -P "${BACKUP_DIR}" | awk 'NR==2 {print $4}')
if [ "${AVAIL_MB}" -lt "${MIN_REQUIRED_MB}" ]; then
    echo "FATAL: Insufficient disk space on ${BACKUP_DIR}. Available: ${AVAIL_MB} MB, Required: ${MIN_REQUIRED_MB} MB. Aborting." >&2
    exit 1
fi

# 3. State Tracking Flags & Exit Trap Handler
PORTAINER_STOPPED_BY_SCRIPT=0
NODERED_PAUSED_BY_SCRIPT=0
BACKUP_SUCCESSFUL=0
CLEANUP_FAILED=0
FINAL_STATUS="BACKUP_INCOMPLETE"

MANIFEST="${BACKUP_DIR}/BACKUP_MANIFEST.txt"
echo "==============================================================================" > "${MANIFEST}"
echo "LOHNOUR PRO — PHASE 8 PRODUCTION PRE-DEPLOYMENT BACKUP MANIFEST" >> "${MANIFEST}"
echo "Timestamp:           $(date -Iseconds)" >> "${MANIFEST}"
echo "Host:                $(hostname) ($(uname -m))" >> "${MANIFEST}"
echo "Docker Version:      $(docker version --format '{{.Server.Version}}' 2>/dev/null || echo 'unknown')" >> "${MANIFEST}"
echo "Storage Available:   ${AVAIL_MB} MB (Minimum Required: ${MIN_REQUIRED_MB} MB)" >> "${MANIFEST}"
echo "==============================================================================" >> "${MANIFEST}"

cleanup() {
    local exit_code=$?
    if [ "${BACKUP_SUCCESSFUL}" -ne 1 ] || [ "${exit_code}" -ne 0 ]; then
        echo "==> [CLEANUP TRAP TRIGGERED] exit_code=${exit_code}. Restoring original workload states..." >&2
        
        if [ "${PORTAINER_STOPPED_BY_SCRIPT}" -eq 1 ]; then
            echo "==> [CLEANUP] Attempting to restart ${TARGET_PORTAINER}..." >&2
            START_RC=0
            docker start "${TARGET_PORTAINER}" >/dev/null 2>&1 || START_RC=$?
            PORT_CLEANUP_STATE=$(docker inspect -f '{{.State.Status}}' "${TARGET_PORTAINER}" 2>/dev/null || echo "unknown")
            if [ "${START_RC}" -eq 0 ] && [ "${PORT_CLEANUP_STATE}" = "running" ]; then
                echo "==> [CLEANUP] Portainer successfully restarted and verified running." >&2
                PORTAINER_STOPPED_BY_SCRIPT=0
            else
                echo "CRITICAL: FAILED to restore and verify ${TARGET_PORTAINER} running state (start_rc=${START_RC}, state=${PORT_CLEANUP_STATE})!" >&2
                echo "MANUAL RECOVERY COMMAND: sudo docker start ${TARGET_PORTAINER}" >&2
                CLEANUP_FAILED=1
            fi
        fi
        
        if [ "${NODERED_PAUSED_BY_SCRIPT}" -eq 1 ]; then
            echo "==> [CLEANUP] Attempting to unpause ${TARGET_NODERED}..." >&2
            UNPAUSE_RC=0
            docker unpause "${TARGET_NODERED}" >/dev/null 2>&1 || UNPAUSE_RC=$?
            NODE_CLEANUP_STATE=$(docker inspect -f '{{.State.Status}}' "${TARGET_NODERED}" 2>/dev/null || echo "unknown")
            if [ "${UNPAUSE_RC}" -eq 0 ] && [ "${NODE_CLEANUP_STATE}" = "running" ]; then
                echo "==> [CLEANUP] Node-RED successfully unpaused and verified running." >&2
                NODERED_PAUSED_BY_SCRIPT=0
            else
                echo "CRITICAL: FAILED to restore and verify ${TARGET_NODERED} running state (unpause_rc=${UNPAUSE_RC}, state=${NODE_CLEANUP_STATE})!" >&2
                echo "MANUAL RECOVERY COMMAND: sudo docker unpause ${TARGET_NODERED}" >&2
                CLEANUP_FAILED=1
            fi
        fi
        
        if [ "${CLEANUP_FAILED}" -eq 1 ]; then
            FINAL_STATUS="RECOVERY_REQUIRED"
        else
            FINAL_STATUS="BACKUP_FAILED"
        fi
        echo "final_status: ${FINAL_STATUS}" >> "${MANIFEST}"
        echo "FATAL: Phase 8 backup aborted with status ${FINAL_STATUS}." >&2
        exit "${exit_code}"
    fi
}

trap cleanup EXIT
trap 'echo "==> [INT] Received SIGINT. Aborting..."; exit 130' INT
trap 'echo "==> [TERM] Received SIGTERM. Aborting..."; exit 143' TERM

# 4. Host Configuration Directories Backup
echo "==> [1/5] Backing up host reverse proxy configurations..."
if [ -d "/etc/caddy" ]; then
    cp -a /etc/caddy "${BACKUP_DIR}/caddy_config"
    echo "caddy_config: PRESENT (copied via cp -a)" >> "${MANIFEST}"
else
    echo "caddy_config: NOT_PRESENT (skipped)" >> "${MANIFEST}"
fi

if [ -d "/etc/nginx" ]; then
    cp -a /etc/nginx "${BACKUP_DIR}/nginx_config"
    echo "nginx_config: PRESENT (copied via cp -a)" >> "${MANIFEST}"
else
    echo "nginx_config: NOT_PRESENT (skipped)" >> "${MANIFEST}"
fi

# 5. Host Firewall Configuration Capture (Strict Classification)
echo "==> [2/5] Capturing host firewall rules..."
if command -v firewall-cmd >/dev/null 2>&1; then
    if firewall-cmd --state >/dev/null 2>&1; then
        firewall-cmd --get-default-zone > "${BACKUP_DIR}/firewalld_default_zone.txt"
        firewall-cmd --list-all-zones > "${BACKUP_DIR}/firewalld_runtime_zones.txt"
        firewall-cmd --permanent --list-all-zones > "${BACKUP_DIR}/firewalld_permanent_zones.txt"
        firewall-cmd --list-all > "${BACKUP_DIR}/firewalld_active_summary.txt"
        
        # Direct rules capture with strict classification (no swallowed failures)
        DIRECT_RULES_OUT=$(firewall-cmd --direct --get-all-rules 2>"${BACKUP_DIR}/firewalld_direct_rules.err" || echo "__FIREWALL_CMD_DIRECT_FAILED__")
        if [ "${DIRECT_RULES_OUT}" = "__FIREWALL_CMD_DIRECT_FAILED__" ]; then
            echo "FATAL: firewall-cmd --direct --get-all-rules failed. Aborting." >&2
            cat "${BACKUP_DIR}/firewalld_direct_rules.err" >&2
            echo "firewalld_direct_rules: FAILED" >> "${MANIFEST}"
            exit 1
        fi
        rm -f "${BACKUP_DIR}/firewalld_direct_rules.err"
        
        TRIMMED_RULES=$(echo "${DIRECT_RULES_OUT}" | tr -d '[:space:]')
        if [ -z "${TRIMMED_RULES}" ]; then
            echo "None" > "${BACKUP_DIR}/firewalld_direct_rules.txt"
            echo "firewalld_direct_rules: None" >> "${MANIFEST}"
        else
            echo "${DIRECT_RULES_OUT}" > "${BACKUP_DIR}/firewalld_direct_rules.txt"
            echo "firewalld_direct_rules: CAPTURED" >> "${MANIFEST}"
        fi
        echo "firewalld: RUNNING (runtime, permanent, direct, default-zone captured)" >> "${MANIFEST}"
    else
        echo "firewalld: INACTIVE (skipped)" >> "${MANIFEST}"
    fi
else
    echo "firewalld: NOT_INSTALLED" >> "${MANIFEST}"
fi

if command -v iptables-save >/dev/null 2>&1; then
    if iptables-save > "${BACKUP_DIR}/iptables.rules" 2>/dev/null; then
        echo "iptables: CAPTURED (saved to iptables.rules)" >> "${MANIFEST}"
    else
        echo "iptables: FAILED" >> "${MANIFEST}"
        exit 1
    fi
else
    echo "iptables: NOT_INSTALLED" >> "${MANIFEST}"
fi

if command -v ip6tables-save >/dev/null 2>&1; then
    if ip6tables-save > "${BACKUP_DIR}/ip6tables.rules" 2>/dev/null; then
        echo "ip6tables: CAPTURED (saved to ip6tables.rules)" >> "${MANIFEST}"
    else
        echo "ip6tables: FAILED" >> "${MANIFEST}"
        exit 1
    fi
else
    echo "ip6tables: NOT_INSTALLED" >> "${MANIFEST}"
fi

# 6. Baseline Container & Volume Inspection
echo "==> [3/5] Verifying baseline containers and recording inspect metadata..."
if ! docker inspect "${TARGET_PORTAINER}" > "${BACKUP_DIR}/portainer_inspect.json" 2>/dev/null; then
    echo "FATAL: Mandatory baseline container '${TARGET_PORTAINER}' not found on host. Aborting." >&2
    echo "portainer_inspect: NOT_FOUND" >> "${MANIFEST}"
    exit 1
fi
echo "portainer_inspect: RECORDED" >> "${MANIFEST}"

if ! docker inspect "${TARGET_NODERED}" > "${BACKUP_DIR}/nodered_inspect.json" 2>/dev/null; then
    echo "FATAL: Mandatory baseline container '${TARGET_NODERED}' not found on host. Aborting." >&2
    echo "nodered_inspect: NOT_FOUND" >> "${MANIFEST}"
    exit 1
fi
echo "nodered_inspect: RECORDED" >> "${MANIFEST}"

if ! docker volume inspect "${TARGET_PORTAINER_VOL}" >/dev/null 2>&1; then
    echo "FATAL: Mandatory Docker volume '${TARGET_PORTAINER_VOL}' not found. Aborting." >&2
    echo "portainer_volume: NOT_FOUND" >> "${MANIFEST}"
    exit 1
fi
PORT_VOL_DIR=$(docker volume inspect -f '{{ .Mountpoint }}' "${TARGET_PORTAINER_VOL}" 2>/dev/null || echo "")
if [ -z "${PORT_VOL_DIR}" ] || [ ! -d "${PORT_VOL_DIR}" ]; then
    echo "FATAL: Volume mountpoint for '${TARGET_PORTAINER_VOL}' (${PORT_VOL_DIR}) is missing or inaccessible. Aborting." >&2
    echo "portainer_volume: MOUNTPOINT_MISSING" >> "${MANIFEST}"
    exit 1
fi

if ! docker volume inspect "${TARGET_NODERED_VOL}" >/dev/null 2>&1; then
    echo "FATAL: Mandatory Docker volume '${TARGET_NODERED_VOL}' not found. Aborting." >&2
    echo "nodered_volume: NOT_FOUND" >> "${MANIFEST}"
    exit 1
fi
NODE_VOL_DIR=$(docker volume inspect -f '{{ .Mountpoint }}' "${TARGET_NODERED_VOL}" 2>/dev/null || echo "")
if [ -z "${NODE_VOL_DIR}" ] || [ ! -d "${NODE_VOL_DIR}" ]; then
    echo "FATAL: Volume mountpoint for '${TARGET_NODERED_VOL}' (${NODE_VOL_DIR}) is missing or inaccessible. Aborting." >&2
    echo "nodered_volume: MOUNTPOINT_MISSING" >> "${MANIFEST}"
    exit 1
fi

# 7. Portainer Persistent Volume Backup (Safe Stop & Corroborated Lifecycle Validation)
echo "==> [4/5] Evaluating Portainer lifecycle state..."
PORT_INIT_STATE=$(docker inspect -f '{{.State.Status}}' "${TARGET_PORTAINER}" 2>/dev/null || echo "unknown")
echo "Portainer initial state: ${PORT_INIT_STATE}"
echo "portainer_initial_state: ${PORT_INIT_STATE}" >> "${MANIFEST}"

if [ "${PORT_INIT_STATE}" = "running" ]; then
    PORTAINER_STOPPED_BY_SCRIPT=1
    
    # 7.1 Pre-stop baseline capture for identity and signal corroboration
    PRE_PORT_ID=$(docker inspect -f '{{.Id}}' "${TARGET_PORTAINER}" 2>/dev/null || echo "")
    PRE_PORT_IMAGE=$(docker inspect -f '{{.Image}}' "${TARGET_PORTAINER}" 2>/dev/null || echo "")
    PRE_PORT_ENTRYPOINT=$(docker inspect -f '{{json .Config.Entrypoint}}' "${TARGET_PORTAINER}" 2>/dev/null || echo "")
    PRE_PORT_CMD=$(docker inspect -f '{{json .Config.Cmd}}' "${TARGET_PORTAINER}" 2>/dev/null || echo "")
    PRE_PORT_STOPSIGNAL=$(docker inspect -f '{{.Config.StopSignal}}' "${TARGET_PORTAINER}" 2>/dev/null || echo "")
    if [ -z "${PRE_PORT_STOPSIGNAL}" ]; then
        PRE_PORT_STOPSIGNAL="SIGTERM"
    fi
    
    PORT_STOP_TIMEOUT=15
    echo "Stopping ${TARGET_PORTAINER} (${PORT_STOP_TIMEOUT}s timeout) for BoltDB flush..."
    PORT_STOP_START=$(date +%s)
    STOP_RC=0
    docker stop -t "${PORT_STOP_TIMEOUT}" "${TARGET_PORTAINER}" >/dev/null 2>&1 || STOP_RC=$?
    PORT_STOP_END=$(date +%s)
    PORT_STOP_DURATION=$((PORT_STOP_END - PORT_STOP_START))
    
    # 7.2 Inspect post-stop state regardless of command return code
    POST_PORT_ID=$(docker inspect -f '{{.Id}}' "${TARGET_PORTAINER}" 2>/dev/null || echo "")
    POST_PORT_IMAGE=$(docker inspect -f '{{.Image}}' "${TARGET_PORTAINER}" 2>/dev/null || echo "")
    PORT_CURR_STATE=$(docker inspect -f '{{.State.Status}}' "${TARGET_PORTAINER}" 2>/dev/null || echo "unknown")
    PORT_RESTARTING=$(docker inspect -f '{{.State.Restarting}}' "${TARGET_PORTAINER}" 2>/dev/null || echo "unknown")
    PORT_EXIT_CODE=$(docker inspect -f '{{.State.ExitCode}}' "${TARGET_PORTAINER}" 2>/dev/null || echo "-1")
    PORT_OOM=$(docker inspect -f '{{.State.OOMKilled}}' "${TARGET_PORTAINER}" 2>/dev/null || echo "false")
    
    # If inspect definitively confirms container remains running despite stop attempt:
    if [ "${STOP_RC}" -ne 0 ] && [ "${PORT_CURR_STATE}" = "running" ]; then
        PORTAINER_STOPPED_BY_SCRIPT=0
        echo "FATAL: Portainer stop command failed and container remains running (stop_rc=${STOP_RC}). Aborting." >&2
        exit 1
    fi
    
    # Abort if stop command failed or container did not reach stopped state
    if [ "${STOP_RC}" -ne 0 ] || { [ "${PORT_CURR_STATE}" != "exited" ] && [ "${PORT_CURR_STATE}" != "stopped" ]; }; then
        echo "FATAL: Portainer stop failed or state unverified (stop_rc=${STOP_RC}, current_state=${PORT_CURR_STATE}). Aborting." >&2
        exit 1
    fi
    
    # Reject unexpectedly restarting containers
    if [ "${PORT_RESTARTING}" = "true" ]; then
        echo "FATAL: Portainer is in restarting state (restarting=true). Aborting." >&2
        exit 1
    fi
    
    # Reject container identity mutations
    if [ -z "${POST_PORT_ID}" ] || [ "${POST_PORT_ID}" != "${PRE_PORT_ID}" ] || [ "${POST_PORT_IMAGE}" != "${PRE_PORT_IMAGE}" ]; then
        echo "FATAL: Container identity mutated during stop operation (ID: ${PRE_PORT_ID} -> ${POST_PORT_ID}). Aborting." >&2
        exit 1
    fi
    
    # Reject OOM termination
    if [ "${PORT_OOM}" = "true" ]; then
        echo "FATAL: Portainer terminated by OOM killer (OOMKilled=true). Consistency lost. Aborting." >&2
        exit 1
    fi
    
    # Reject timeout / force kill (SIGKILL - 137)
    if [ "${PORT_EXIT_CODE}" -eq 137 ] || [ "${PORT_STOP_DURATION}" -ge "${PORT_STOP_TIMEOUT}" ]; then
        echo "FATAL: Portainer timed out or was force-killed via SIGKILL (exit_code=${PORT_EXIT_CODE}, duration=${PORT_STOP_DURATION}s >= ${PORT_STOP_TIMEOUT}s). Consistency lost. Aborting." >&2
        exit 1
    fi
    
    # 7.3 Multi-tier exit code evaluation with corroborating evidence
    PORT_STOP_CLASSIFICATION=""
    if [ "${PORT_EXIT_CODE}" -eq 0 ]; then
        PORT_STOP_CLASSIFICATION="CLEAN_POSIX_EXIT_0"
        echo "Portainer gracefully stopped with clean exit code 0."
    elif [ "${PORT_EXIT_CODE}" -eq 2 ]; then
        # Require full corroborating evidence for Go PID-1 SIGTERM fallback (dieFromSignal):
        # 1. Stopped within configured timeout
        # 2. Configured stop signal is confirmed as SIGTERM
        # 3. PID-1 process is the compiled Go Portainer binary
        # 4. Container confirmed stopped and not restarting
        # 5. OOM is false
        if [ "${PORT_STOP_DURATION}" -lt "${PORT_STOP_TIMEOUT}" ] && \
           { [ "${PRE_PORT_STOPSIGNAL}" = "SIGTERM" ] || [ "${PRE_PORT_STOPSIGNAL}" = "15" ]; } && \
           echo "${PRE_PORT_ENTRYPOINT}" | grep -q "portainer"; then
            PORT_STOP_CLASSIFICATION="GO_PID1_SIGTERM_EXIT_2 (corroborated: duration=${PORT_STOP_DURATION}s, signal=${PRE_PORT_STOPSIGNAL}, entrypoint=${PRE_PORT_ENTRYPOINT})"
            echo "Portainer gracefully stopped (exit_code=2: Go PID-1 SIGTERM runtime exit with full corroborating evidence)."
        else
            echo "FATAL: Portainer exit code 2 lacked required corroborating SIGTERM evidence (duration=${PORT_STOP_DURATION}s, signal=${PRE_PORT_STOPSIGNAL}, entrypoint=${PRE_PORT_ENTRYPOINT}). Aborting." >&2
            exit 1
        fi
    elif [ "${PORT_EXIT_CODE}" -eq 143 ]; then
        # Require explicit justification for shell SIGTERM (128 + 15):
        # Only accepted if entrypoint or cmd demonstrates a shell wrapper that propagates 143
        if [ "${PORT_STOP_DURATION}" -lt "${PORT_STOP_TIMEOUT}" ] && \
           { [ "${PRE_PORT_STOPSIGNAL}" = "SIGTERM" ] || [ "${PRE_PORT_STOPSIGNAL}" = "15" ]; } && \
           { echo "${PRE_PORT_ENTRYPOINT}" | grep -E "sh|bash" >/dev/null || echo "${PRE_PORT_CMD}" | grep -E "sh|bash" >/dev/null; }; then
            PORT_STOP_CLASSIFICATION="SHELL_SIGTERM_EXIT_143 (shell wrapper justified: duration=${PORT_STOP_DURATION}s, signal=${PRE_PORT_STOPSIGNAL})"
            echo "Portainer gracefully stopped (exit_code=143: shell wrapper SIGTERM exit justified)."
        else
            echo "FATAL: Portainer exit code 143 lacked shell-wrapper justification (entrypoint=${PRE_PORT_ENTRYPOINT}, cmd=${PRE_PORT_CMD}). Aborting." >&2
            exit 1
        fi
    else
        echo "FATAL: Portainer terminated with unexpected exit code (${PORT_EXIT_CODE}, OOM=${PORT_OOM}). Consistency lost. Aborting." >&2
        exit 1
    fi
    echo "portainer_stop_classification: ${PORT_STOP_CLASSIFICATION}" >> "${MANIFEST}"
elif [ "${PORT_INIT_STATE}" = "exited" ] || [ "${PORT_INIT_STATE}" = "stopped" ]; then
    echo "Portainer is already stopped; proceeding directly with volume archive."
    echo "portainer_stop_classification: PREVIOUSLY_STOPPED" >> "${MANIFEST}"
else
    echo "FATAL: Portainer in unexpected state: ${PORT_INIT_STATE}. Aborting." >&2
    exit 1
fi

PORT_ARCHIVE="${BACKUP_DIR}/portainer_data_${BACKUP_TIMESTAMP}.tar.gz"
echo "Archiving Portainer volume from ${PORT_VOL_DIR}..."
tar -czf "${PORT_ARCHIVE}" -C "${PORT_VOL_DIR}" .
tar -tzf "${PORT_ARCHIVE}" >/dev/null
PORT_SIZE=$(stat -c "%s" "${PORT_ARCHIVE}")

if [ "${PORTAINER_STOPPED_BY_SCRIPT}" -eq 1 ]; then
    echo "Restarting ${TARGET_PORTAINER}..."
    RESTART_START_EPOCH=$(date +%s)
    START_RC=0
    docker start "${TARGET_PORTAINER}" >/dev/null 2>&1 || START_RC=$?
    PORT_RESTORED_STATE=$(docker inspect -f '{{.State.Status}}' "${TARGET_PORTAINER}" 2>/dev/null || echo "unknown")
    if [ "${START_RC}" -ne 0 ] || [ "${PORT_RESTORED_STATE}" != "running" ]; then
        echo "FATAL: Portainer failed to return to running state after archive (start_rc=${START_RC}, current_state=${PORT_RESTORED_STATE})." >&2
        exit 1
    fi
    PORTAINER_STOPPED_BY_SCRIPT=0
    
    # 7.4 Post-restart BoltDB datastore health verification
    sleep 2
    PORT_POST_LOGS=$(docker logs --since "${RESTART_START_EPOCH}" "${TARGET_PORTAINER}" 2>&1 || docker logs --tail 30 "${TARGET_PORTAINER}" 2>&1)
    if echo "${PORT_POST_LOGS}" | grep -iE "fatal|panic|corruption|corrupt|recovery failed|error.*database" >/dev/null; then
        echo "FATAL: Portainer post-restart logs indicate database initialization failure or corruption!" >&2
        echo "${PORT_POST_LOGS}" >&2
        exit 1
    fi
    if echo "${PORT_POST_LOGS}" | grep -E "loading PortainerDB|opened database|database.*initialized|starting Portainer" >/dev/null; then
        echo "==> [PORTAINER HEALTH] BoltDB datastore successfully verified healthy post-restart."
        echo "portainer_boltdb_status: VERIFIED_HEALTHY" >> "${MANIFEST}"
    else
        echo "portainer_boltdb_status: RUNNING (no database errors logged)" >> "${MANIFEST}"
    fi
fi

PORT_FINAL_STATE=$(docker inspect -f '{{.State.Status}}' "${TARGET_PORTAINER}" 2>/dev/null || echo "unknown")
if [ "${PORT_FINAL_STATE}" != "${PORT_INIT_STATE}" ]; then
    echo "FATAL: Portainer final state (${PORT_FINAL_STATE}) does not match initial state (${PORT_INIT_STATE})!" >&2
    exit 2
fi
echo "portainer_final_state: ${PORT_FINAL_STATE}" >> "${MANIFEST}"
echo "portainer_volume: BACKED_UP (${PORT_SIZE} bytes, integrity verified)" >> "${MANIFEST}"

# 8. Node-RED Persistent Volume Backup (Safe Pause & Immediate Recovery Tracking)
echo "==> [5/5] Evaluating Node-RED lifecycle state..."
NODE_INIT_STATE=$(docker inspect -f '{{.State.Status}}' "${TARGET_NODERED}" 2>/dev/null || echo "unknown")
echo "Node-RED initial state: ${NODE_INIT_STATE}"
echo "nodered_initial_state: ${NODE_INIT_STATE}" >> "${MANIFEST}"

if [ "${NODE_INIT_STATE}" = "running" ]; then
    NODERED_PAUSED_BY_SCRIPT=1
    echo "Freezing Node-RED process via docker pause..."
    PAUSE_RC=0
    docker pause "${TARGET_NODERED}" >/dev/null 2>&1 || PAUSE_RC=$?
    
    NODE_CURR_STATE=$(docker inspect -f '{{.State.Status}}' "${TARGET_NODERED}" 2>/dev/null || echo "unknown")
    
    # If inspect definitively confirms container remains running despite pause attempt:
    if [ "${PAUSE_RC}" -ne 0 ] && [ "${NODE_CURR_STATE}" = "running" ]; then
        NODERED_PAUSED_BY_SCRIPT=0
        echo "FATAL: Node-RED pause command failed and container remains running (pause_rc=${PAUSE_RC}). Aborting." >&2
        exit 1
    fi
    
    if [ "${PAUSE_RC}" -ne 0 ] || [ "${NODE_CURR_STATE}" != "paused" ]; then
        echo "FATAL: Node-RED pause failed or state unverified (pause_rc=${PAUSE_RC}, current_state=${NODE_CURR_STATE}). Aborting." >&2
        exit 1
    fi
elif [ "${NODE_INIT_STATE}" = "paused" ]; then
    echo "Node-RED is already paused; proceeding directly with volume archive."
else
    echo "FATAL: Node-RED in unexpected state: ${NODE_INIT_STATE}. Aborting." >&2
    exit 1
fi

NODE_ARCHIVE="${BACKUP_DIR}/nodered_data_${BACKUP_TIMESTAMP}.tar.gz"
echo "Archiving Node-RED volume from ${NODE_VOL_DIR}..."
tar -czf "${NODE_ARCHIVE}" -C "${NODE_VOL_DIR}" .
tar -tzf "${NODE_ARCHIVE}" >/dev/null
NODE_SIZE=$(stat -c "%s" "${NODE_ARCHIVE}")

if [ "${NODERED_PAUSED_BY_SCRIPT}" -eq 1 ]; then
    echo "Unpausing ${TARGET_NODERED}..."
    UNPAUSE_RC=0
    docker unpause "${TARGET_NODERED}" >/dev/null 2>&1 || UNPAUSE_RC=$?
    NODE_RESTORED_STATE=$(docker inspect -f '{{.State.Status}}' "${TARGET_NODERED}" 2>/dev/null || echo "unknown")
    if [ "${UNPAUSE_RC}" -eq 0 ] && [ "${NODE_RESTORED_STATE}" = "running" ]; then
        NODERED_PAUSED_BY_SCRIPT=0
    else
        echo "FATAL: Node-RED failed to return to running state after archive (unpause_rc=${UNPAUSE_RC}, current_state=${NODE_RESTORED_STATE})." >&2
        exit 1
    fi
fi

NODE_FINAL_STATE=$(docker inspect -f '{{.State.Status}}' "${TARGET_NODERED}" 2>/dev/null || echo "unknown")
if [ "${NODE_FINAL_STATE}" != "${NODE_INIT_STATE}" ]; then
    echo "FATAL: Node-RED final state (${NODE_FINAL_STATE}) does not match initial state (${NODE_INIT_STATE})!" >&2
    exit 2
fi
echo "nodered_final_state: ${NODE_FINAL_STATE}" >> "${MANIFEST}"
echo "nodered_volume: BACKED_UP (${NODE_SIZE} bytes, integrity verified)" >> "${MANIFEST}"
echo "nodered_consistency_note: Process-frozen snapshot via cgroups pause (crash-consistent flat JSONs; not ACID database transaction)." >> "${MANIFEST}"

# 9. Two-Part Cryptographic Integrity Scheme
echo "==> Generating SHA256 checksums across all backup payload artifacts..."
cd "${BACKUP_DIR}"
find . -type f ! -name "SHA256SUMS" ! -name "BACKUP_MANIFEST.txt" ! -name "BACKUP_MANIFEST.sha256" -print0 | sort -z | xargs -0 sha256sum > SHA256SUMS
chmod 600 SHA256SUMS

echo "==> Verifying payload SHA256 checksums..."
sha256sum -c SHA256SUMS > /dev/null

echo "payload_sha256_verification: ALL_CHECKSUMS_VERIFIED" >> "${MANIFEST}"
echo "final_status: BACKUP_VERIFIED" >> "${MANIFEST}"
echo "==============================================================================" >> "${MANIFEST}"

echo "==> Generating and verifying manifest SHA256 checksum..."
sha256sum BACKUP_MANIFEST.txt > BACKUP_MANIFEST.sha256
chmod 600 BACKUP_MANIFEST.sha256
sha256sum -c BACKUP_MANIFEST.sha256 > /dev/null

BACKUP_SUCCESSFUL=1
FINAL_STATUS="BACKUP_VERIFIED"

echo "=============================================================================="
echo "PHASE 8 PRE-DEPLOYMENT BACKUP COMPLETE AND VERIFIED IN: ${BACKUP_DIR}"
echo "STATUS: ${FINAL_STATUS}"
echo "MANDATORY STOP: Do NOT execute Phase 9 without explicit user authorization."
echo "=============================================================================="
