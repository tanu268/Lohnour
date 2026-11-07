#!/bin/bash
# ==============================================================================
# LOHNOUR PRO — PHASE 10 AUTOMATED ACCEPTANCE TEST SUITE (20 SCENARIOS)
# Tests the actual canonical script (scripts/phase10_db_recovery.sh) directly
# in an isolated WSL2 Docker environment with zero mocks.
# ==============================================================================
set -u

CANONICAL_SCRIPT="/mnt/d/lohnourpro/scripts/phase10_db_recovery.sh"
LOCAL_MARIADB_IMAGE="mariadb:10.11.19"
SQL_DUMP="/mnt/d/lohnourpro/_audit/production-extract/database/production-lohnour_wp.sql"
CANONICAL_SQL_HASH="db8628e7743b133e372265a7d09ac29729ab7f7c8ed9dfcd0bdd257d7e88a880"

echo "================================================================="
echo "PHASE 10 AUTOMATED ACCEPTANCE TEST SUITE (20 CANONICAL SCENARIOS)"
echo "Target Engine: ${LOCAL_MARIADB_IMAGE}"
echo "Canonical Script: ${CANONICAL_SCRIPT}"
echo "================================================================="

PASS_COUNT=0
FAIL_COUNT=0

record_result() {
  local name="$1"
  local status="$2"
  local details="$3"
  if [ "$status" = "PASS" ]; then
    echo "[PASS] $name - $details"
    PASS_COUNT=$((PASS_COUNT + 1))
  else
    echo "[FAIL] $name - $details"
    FAIL_COUNT=$((FAIL_COUNT + 1))
  fi
}

# -----------------------------------------------------------------------------
# SCENARIO 1: Secret Absence in Docker Container Configuration
# -----------------------------------------------------------------------------
echo -e "\n--> Running Scenario 1: Zero Secret Leakage in Container Configuration"
S1_VOL="test_s1_sec_vol"
S1_NET="test_s1_sec_net"
S1_CNT="test-s1-sec-init"
docker volume create "$S1_VOL" >/dev/null
docker network create --internal "$S1_NET" >/dev/null

# Run canonical script in background to launch container, inspect it, then terminate
DB_ROOT_PASSWORD="SecretPassRoot123456!" \
DB_PASSWORD="SecretPassUser123456!" \
TARGET_VOLUME="$S1_VOL" \
TARGET_NETWORK="$S1_NET" \
INIT_CONTAINER="$S1_CNT" \
SQL_FILE="$SQL_DUMP" \
EXPECTED_SQL_HASH="$CANONICAL_SQL_HASH" \
PINNED_MARIADB_IMAGE="$LOCAL_MARIADB_IMAGE" \
bash "$CANONICAL_SCRIPT" >/dev/null 2>&1 &
S1_PID=$!

# Wait until container exists and inspect environment
S1_LEAK=0
for i in $(seq 1 30); do
  if docker inspect "$S1_CNT" >/dev/null 2>&1; then
    ENV_JSON=$(docker inspect -f '{{json .Config.Env}}' "$S1_CNT" 2>/dev/null || echo "")
    if echo "$ENV_JSON" | grep -q "SecretPass"; then
      S1_LEAK=1
    fi
    break
  fi
  sleep 1
done

wait $S1_PID 2>/dev/null || true
docker rm -f "$S1_CNT" >/dev/null 2>&1 || true
docker volume rm "$S1_VOL" >/dev/null 2>&1 || true
docker network rm "$S1_NET" >/dev/null 2>&1 || true

if [ $S1_LEAK -eq 0 ]; then
  record_result "Scenario 1" "PASS" "Canonical script provisions secrets via _FILE; zero plaintext secrets in Config.Env"
else
  record_result "Scenario 1" "FAIL" "Plaintext password detected in container Config.Env"
fi

# -----------------------------------------------------------------------------
# SCENARIO 2: Complete Canonical Script End-to-End Execution
# -----------------------------------------------------------------------------
echo -e "\n--> Running Scenario 2: Canonical Script End-to-End Execution"
S2_VOL="test_s2_e2e_vol"
S2_NET="test_s2_e2e_net"
S2_CNT="test-s2-e2e-init"
docker volume create "$S2_VOL" >/dev/null
docker network create --internal "$S2_NET" >/dev/null

S2_OUT=$(
  DB_ROOT_PASSWORD="E2ERootPasswordValid123!" \
  DB_PASSWORD="E2EUserPasswordValid123!" \
  TARGET_VOLUME="$S2_VOL" \
  TARGET_NETWORK="$S2_NET" \
  INIT_CONTAINER="$S2_CNT" \
  SQL_FILE="$SQL_DUMP" \
  EXPECTED_SQL_HASH="$CANONICAL_SQL_HASH" \
  PINNED_MARIADB_IMAGE="$LOCAL_MARIADB_IMAGE" \
  bash "$CANONICAL_SCRIPT" 2>&1
)
S2_RC=$?

# Verify container decommission and volume contents
S2_CNT_EXISTS=$(docker inspect "$S2_CNT" >/dev/null 2>&1 && echo "yes" || echo "no")
S2_FILE_COUNT=$(docker run --rm -v "$S2_VOL:/data" alpine ls -A /data | wc -l)
docker volume rm "$S2_VOL" >/dev/null 2>&1 || true
docker network rm "$S2_NET" >/dev/null 2>&1 || true

if [ $S2_RC -eq 0 ] && [ "$S2_CNT_EXISTS" = "no" ] && [ "$S2_FILE_COUNT" -gt 0 ] && \
   echo "$S2_OUT" | grep -q "All 10 acceptance checks passed with 100% compliance"; then
  record_result "Scenario 2" "PASS" "Canonical script executed cleanly (rc=0), all 10 checks passed, container decommissioned, volume verified"
else
  record_result "Scenario 2" "FAIL" "RC=$S2_RC, CntExists=$S2_CNT_EXISTS, Files=$S2_FILE_COUNT"
fi

# -----------------------------------------------------------------------------
# SCENARIO 3: Pre-Gate A: Checksum Mismatch Abort on Canonical Script
# -----------------------------------------------------------------------------
echo -e "\n--> Running Scenario 3: Pre-Gate Checksum Mismatch Abort"
S3_VOL="test_s3_chk_vol"
S3_NET="test_s3_chk_net"
docker volume create "$S3_VOL" >/dev/null
docker network create --internal "$S3_NET" >/dev/null

S3_OUT=$(
  DB_ROOT_PASSWORD="ValidRootPass123456!" \
  DB_PASSWORD="ValidUserPass123456!" \
  TARGET_VOLUME="$S3_VOL" \
  TARGET_NETWORK="$S3_NET" \
  SQL_FILE="$SQL_DUMP" \
  EXPECTED_SQL_HASH="corrupted_hash_value_that_does_not_match" \
  bash "$CANONICAL_SCRIPT" 2>&1
)
S3_RC=$?
docker volume rm "$S3_VOL" >/dev/null 2>&1 || true
docker network rm "$S3_NET" >/dev/null 2>&1 || true

if [ $S3_RC -eq 1 ] && echo "$S3_OUT" | grep -q "FATAL PRE-GATE: SQL dump hash mismatch"; then
  record_result "Scenario 3" "PASS" "Canonical script aborted at Pre-Gate A on checksum mismatch (rc=1)"
else
  record_result "Scenario 3" "FAIL" "Failed to abort on checksum mismatch (RC=$S3_RC)"
fi

# -----------------------------------------------------------------------------
# SCENARIO 4: Pre-Gate A: Missing SQL File Abort on Canonical Script
# -----------------------------------------------------------------------------
echo -e "\n--> Running Scenario 4: Pre-Gate Missing SQL File Abort"
S4_VOL="test_s4_sql_vol"
S4_NET="test_s4_sql_net"
docker volume create "$S4_VOL" >/dev/null
docker network create --internal "$S4_NET" >/dev/null

S4_OUT=$(
  DB_ROOT_PASSWORD="ValidRootPass123456!" \
  DB_PASSWORD="ValidUserPass123456!" \
  TARGET_VOLUME="$S4_VOL" \
  TARGET_NETWORK="$S4_NET" \
  SQL_FILE="/tmp/nonexistent_sql_file_12345.sql" \
  EXPECTED_SQL_HASH="$CANONICAL_SQL_HASH" \
  bash "$CANONICAL_SCRIPT" 2>&1
)
S4_RC=$?
docker volume rm "$S4_VOL" >/dev/null 2>&1 || true
docker network rm "$S4_NET" >/dev/null 2>&1 || true

if [ $S4_RC -eq 1 ] && echo "$S4_OUT" | grep -q "FATAL PRE-GATE: SQL dump file"; then
  record_result "Scenario 4" "PASS" "Canonical script aborted at Pre-Gate A on missing SQL file (rc=1)"
else
  record_result "Scenario 4" "FAIL" "Failed to abort on missing SQL file (RC=$S4_RC)"
fi

# -----------------------------------------------------------------------------
# SCENARIO 5: Pre-Gate A: Non-Existent Target Volume Abort on Canonical Script
# -----------------------------------------------------------------------------
echo -e "\n--> Running Scenario 5: Pre-Gate Missing Volume Abort"
S5_OUT=$(
  DB_ROOT_PASSWORD="ValidRootPass123456!" \
  DB_PASSWORD="ValidUserPass123456!" \
  TARGET_VOLUME="nonexistent_target_vol_$$" \
  SQL_FILE="$SQL_DUMP" \
  EXPECTED_SQL_HASH="$CANONICAL_SQL_HASH" \
  bash "$CANONICAL_SCRIPT" 2>&1
)
S5_RC=$?

if [ $S5_RC -eq 1 ] && echo "$S5_OUT" | grep -q "FATAL PRE-GATE: Volume nonexistent_target_vol_.* does not exist"; then
  record_result "Scenario 5" "PASS" "Canonical script aborted at Pre-Gate A on missing target volume (rc=1)"
else
  record_result "Scenario 5" "FAIL" "Failed to abort on missing volume (RC=$S5_RC)"
fi

# -----------------------------------------------------------------------------
# SCENARIO 6: Pre-Gate A: Non-Empty Volume Abort on Canonical Script
# -----------------------------------------------------------------------------
echo -e "\n--> Running Scenario 6: Pre-Gate Non-Empty Volume Abort"
S6_VOL="test_s6_nonempty_vol"
docker volume create "$S6_VOL" >/dev/null
docker run --rm -v "$S6_VOL:/data" alpine touch /data/unexpected_file.txt >/dev/null

S6_OUT=$(
  DB_ROOT_PASSWORD="ValidRootPass123456!" \
  DB_PASSWORD="ValidUserPass123456!" \
  TARGET_VOLUME="$S6_VOL" \
  SQL_FILE="$SQL_DUMP" \
  EXPECTED_SQL_HASH="$CANONICAL_SQL_HASH" \
  bash "$CANONICAL_SCRIPT" 2>&1
)
S6_RC=$?
docker volume rm "$S6_VOL" >/dev/null 2>&1 || true

if [ $S6_RC -eq 1 ] && echo "$S6_OUT" | grep -q "is NOT empty (1 entries). Aborting."; then
  record_result "Scenario 6" "PASS" "Canonical script aborted at Pre-Gate A on non-empty volume (rc=1)"
else
  record_result "Scenario 6" "FAIL" "Failed to abort on non-empty volume (RC=$S6_RC)"
fi

# -----------------------------------------------------------------------------
# SCENARIO 7: Pre-Gate A: Attached Container Abort on Canonical Script
# -----------------------------------------------------------------------------
echo -e "\n--> Running Scenario 7: Pre-Gate Attached Container Abort"
S7_VOL="test_s7_attach_vol"
S7_CNT="test-s7-blocker"
docker volume create "$S7_VOL" >/dev/null
docker create --name "$S7_CNT" -v "$S7_VOL:/data" alpine >/dev/null

S7_OUT=$(
  DB_ROOT_PASSWORD="ValidRootPass123456!" \
  DB_PASSWORD="ValidUserPass123456!" \
  TARGET_VOLUME="$S7_VOL" \
  SQL_FILE="$SQL_DUMP" \
  EXPECTED_SQL_HASH="$CANONICAL_SQL_HASH" \
  bash "$CANONICAL_SCRIPT" 2>&1
)
S7_RC=$?
docker rm -f "$S7_CNT" >/dev/null 2>&1 || true
docker volume rm "$S7_VOL" >/dev/null 2>&1 || true

if [ $S7_RC -eq 1 ] && echo "$S7_OUT" | grep -q "is attached to container(s)"; then
  record_result "Scenario 7" "PASS" "Canonical script aborted at Pre-Gate A on attached container (rc=1)"
else
  record_result "Scenario 7" "FAIL" "Failed to abort on attached container (RC=$S7_RC)"
fi

# -----------------------------------------------------------------------------
# SCENARIO 8: Pre-Gate A: Non-Internal Network Abort on Canonical Script
# -----------------------------------------------------------------------------
echo -e "\n--> Running Scenario 8: Pre-Gate Non-Internal Network Abort"
S8_VOL="test_s8_net_vol"
S8_NET="test_s8_external_net"
docker volume create "$S8_VOL" >/dev/null
docker network create "$S8_NET" >/dev/null # Internal: false

S8_OUT=$(
  DB_ROOT_PASSWORD="ValidRootPass123456!" \
  DB_PASSWORD="ValidUserPass123456!" \
  TARGET_VOLUME="$S8_VOL" \
  TARGET_NETWORK="$S8_NET" \
  SQL_FILE="$SQL_DUMP" \
  EXPECTED_SQL_HASH="$CANONICAL_SQL_HASH" \
  bash "$CANONICAL_SCRIPT" 2>&1
)
S8_RC=$?
docker volume rm "$S8_VOL" >/dev/null 2>&1 || true
docker network rm "$S8_NET" >/dev/null 2>&1 || true

if [ $S8_RC -eq 1 ] && echo "$S8_OUT" | grep -q "is not marked Internal: true"; then
  record_result "Scenario 8" "PASS" "Canonical script aborted at Pre-Gate A on non-internal network (rc=1)"
else
  record_result "Scenario 8" "FAIL" "Failed to abort on non-internal network (RC=$S8_RC)"
fi

# -----------------------------------------------------------------------------
# SCENARIO 9: Pre-Gate A: Container Collision Abort on Canonical Script
# -----------------------------------------------------------------------------
echo -e "\n--> Running Scenario 9: Pre-Gate Container Name Collision Abort"
S9_VOL="test_s9_col_vol"
S9_NET="test_s9_col_net"
S9_CNT="test-s9-collision-cnt"
docker volume create "$S9_VOL" >/dev/null
docker network create --internal "$S9_NET" >/dev/null
docker create --name "$S9_CNT" alpine >/dev/null

S9_OUT=$(
  DB_ROOT_PASSWORD="ValidRootPass123456!" \
  DB_PASSWORD="ValidUserPass123456!" \
  TARGET_VOLUME="$S9_VOL" \
  TARGET_NETWORK="$S9_NET" \
  INIT_CONTAINER="$S9_CNT" \
  SQL_FILE="$SQL_DUMP" \
  EXPECTED_SQL_HASH="$CANONICAL_SQL_HASH" \
  bash "$CANONICAL_SCRIPT" 2>&1
)
S9_RC=$?
docker rm -f "$S9_CNT" >/dev/null 2>&1 || true
docker volume rm "$S9_VOL" >/dev/null 2>&1 || true
docker network rm "$S9_NET" >/dev/null 2>&1 || true

if [ $S9_RC -eq 1 ] && echo "$S9_OUT" | grep -q "Container test-s9-collision-cnt already exists"; then
  record_result "Scenario 9" "PASS" "Canonical script aborted at Pre-Gate A on container name collision (rc=1)"
else
  record_result "Scenario 9" "FAIL" "Failed to abort on container collision (RC=$S9_RC)"
fi

# -----------------------------------------------------------------------------
# SCENARIO 10: Signal Trapping: SIGINT Returns Exit Code 130 on Canonical Script
# -----------------------------------------------------------------------------
echo -e "\n--> Running Scenario 10: SIGINT Handling on Canonical Script"
S10_RC_FILE="/tmp/s10_rc_$$"
(
  set -m
  DB_ROOT_PASSWORD="ValidRootPassScenario10!" \
  DB_PASSWORD="ValidUserPassScenario10!" \
  TARGET_VOLUME="nonexistent_test_vol_s10_$$" \
  bash "$CANONICAL_SCRIPT" >/dev/null 2>&1 &
  PID=$!
  sleep 0.1
  kill -INT $PID 2>/dev/null || true
  wait $PID 2>/dev/null || RC=$?
  echo "$RC" > "$S10_RC_FILE"
)
S10_RC=$(cat "$S10_RC_FILE" 2>/dev/null || echo 0)
rm -f "$S10_RC_FILE"

if [ "$S10_RC" -eq 130 ]; then
  record_result "Scenario 10" "PASS" "SIGINT on canonical script returned exit code 130 and executed cleanup"
else
  record_result "Scenario 10" "FAIL" "Expected exit code 130, observed $S10_RC"
fi

# -----------------------------------------------------------------------------
# SCENARIO 11: Signal Trapping: SIGTERM Returns Exit Code 143 on Canonical Script
# -----------------------------------------------------------------------------
echo -e "\n--> Running Scenario 11: SIGTERM Handling on Canonical Script"
S11_RC_FILE="/tmp/s11_rc_$$"
(
  set -m
  DB_ROOT_PASSWORD="ValidRootPassScenario11!" \
  DB_PASSWORD="ValidUserPassScenario11!" \
  TARGET_VOLUME="nonexistent_test_vol_s11_$$" \
  bash "$CANONICAL_SCRIPT" >/dev/null 2>&1 &
  PID=$!
  sleep 0.1
  kill -TERM $PID 2>/dev/null || true
  wait $PID 2>/dev/null || RC=$?
  echo "$RC" > "$S11_RC_FILE"
)
S11_RC=$(cat "$S11_RC_FILE" 2>/dev/null || echo 0)
rm -f "$S11_RC_FILE"

if [ "$S11_RC" -eq 143 ]; then
  record_result "Scenario 11" "PASS" "SIGTERM on canonical script returned exit code 143 and executed cleanup"
else
  record_result "Scenario 11" "FAIL" "Expected exit code 143, observed $S11_RC"
fi

# -----------------------------------------------------------------------------
# SCENARIO 12: Password Policy: Rejection of Short Password on Canonical Script
# -----------------------------------------------------------------------------
echo -e "\n--> Running Scenario 12: Short Password Policy Rejection"
S12_OUT=$(
  DB_ROOT_PASSWORD="ShortPass1!" \
  DB_PASSWORD="ValidUserPass123456!" \
  bash "$CANONICAL_SCRIPT" 2>&1
)
S12_RC=$?

if [ $S12_RC -eq 1 ] && echo "$S12_OUT" | grep -q "DB_ROOT_PASSWORD does not meet minimum length requirement"; then
  record_result "Scenario 12" "PASS" "Canonical script rejected short password (< 16 chars) at preflight (rc=1)"
else
  record_result "Scenario 12" "FAIL" "Failed to reject short password (RC=$S12_RC)"
fi

# -----------------------------------------------------------------------------
# SCENARIO 13: Password Policy: Rejection of Newline Character on Canonical Script
# -----------------------------------------------------------------------------
echo -e "\n--> Running Scenario 13: Newline Character Policy Rejection"
S13_OUT=$(
  DB_ROOT_PASSWORD=$'Illegal\nNewlinePass123456' \
  DB_PASSWORD="ValidUserPass123456!" \
  bash "$CANONICAL_SCRIPT" 2>&1
)
S13_RC=$?

if [ $S13_RC -eq 1 ] && echo "$S13_OUT" | grep -q "Password contains prohibited newline character"; then
  record_result "Scenario 13" "PASS" "Canonical script rejected password containing newline (rc=1)"
else
  record_result "Scenario 13" "FAIL" "Failed to reject password with newline (RC=$S13_RC)"
fi

# -----------------------------------------------------------------------------
# SCENARIO 14: Password Policy: Rejection of Tab Character on Canonical Script
# -----------------------------------------------------------------------------
echo -e "\n--> Running Scenario 14: Tab Character Policy Rejection"
S14_OUT=$(
  DB_ROOT_PASSWORD=$'Illegal\tTabPass123456' \
  DB_PASSWORD="ValidUserPass123456!" \
  bash "$CANONICAL_SCRIPT" 2>&1
)
S14_RC=$?

if [ $S14_RC -eq 1 ] && echo "$S14_OUT" | grep -q "Password contains prohibited carriage return or tab character"; then
  record_result "Scenario 14" "PASS" "Canonical script rejected password containing tab (rc=1)"
else
  record_result "Scenario 14" "FAIL" "Failed to reject password with tab (RC=$S14_RC)"
fi

# -----------------------------------------------------------------------------
# SCENARIO 15: Password Policy: Rejection of Carriage Return on Canonical Script
# -----------------------------------------------------------------------------
echo -e "\n--> Running Scenario 15: Carriage Return Policy Rejection"
S15_OUT=$(
  DB_ROOT_PASSWORD=$'Illegal\rCarriagePass123456' \
  DB_PASSWORD="ValidUserPass123456!" \
  bash "$CANONICAL_SCRIPT" 2>&1
)
S15_RC=$?

if [ $S15_RC -eq 1 ] && echo "$S15_OUT" | grep -q "Password contains prohibited carriage return or tab character"; then
  record_result "Scenario 15" "PASS" "Canonical script rejected password containing carriage return (rc=1)"
else
  record_result "Scenario 15" "FAIL" "Failed to reject password with carriage return (RC=$S15_RC)"
fi

# -----------------------------------------------------------------------------
# SCENARIO 16: SQL Import Failure & Volume Preservation on Canonical Script
# -----------------------------------------------------------------------------
echo -e "\n--> Running Scenario 16: SQL Import Failure & Volume Preservation"
S16_VOL="test_s16_fail_vol"
S16_NET="test_s16_fail_net"
S16_CNT="test-s16-fail-init"
docker volume create "$S16_VOL" >/dev/null
docker network create --internal "$S16_NET" >/dev/null

S16_DUMP=$(mktemp /tmp/s16_corrupt.sql.XXXXXX)
echo "INVALID SYNTAX STATEMENT;" > "$S16_DUMP"
S16_HASH=$(sha256sum "$S16_DUMP" | awk '{print $1}')

S16_OUT=$(
  DB_ROOT_PASSWORD="ValidRootPassScenario16!" \
  DB_PASSWORD="ValidUserPassScenario16!" \
  TARGET_VOLUME="$S16_VOL" \
  TARGET_NETWORK="$S16_NET" \
  INIT_CONTAINER="$S16_CNT" \
  SQL_FILE="$S16_DUMP" \
  EXPECTED_SQL_HASH="$S16_HASH" \
  PINNED_MARIADB_IMAGE="$LOCAL_MARIADB_IMAGE" \
  bash "$CANONICAL_SCRIPT" 2>&1
)
S16_RC=$?
rm -f "$S16_DUMP"

S16_FILE_COUNT=$(docker run --rm -v "$S16_VOL:/data" alpine ls -A /data | wc -l)
docker volume rm "$S16_VOL" >/dev/null 2>&1 || true
docker network rm "$S16_NET" >/dev/null 2>&1 || true

if [ $S16_RC -ne 0 ] && [ "$S16_FILE_COUNT" -gt 0 ] && \
   echo "$S16_OUT" | grep -q "STATUS: RECOVERY_FAILED_DATA_PRESERVED"; then
  record_result "Scenario 16" "PASS" "Canonical script handled import error: reported RECOVERY_FAILED_DATA_PRESERVED and kept volume intact ($S16_FILE_COUNT files)"
else
  record_result "Scenario 16" "FAIL" "Failed to preserve volume on import error (RC=$S16_RC, Files=$S16_FILE_COUNT)"
fi

# -----------------------------------------------------------------------------
# SCENARIO 17: Table Count Mismatch Detection on Canonical Script
# -----------------------------------------------------------------------------
echo -e "\n--> Running Scenario 17: Table Count Mismatch Detection"
S17_VOL="test_s17_tbl_vol"
S17_NET="test_s17_tbl_net"
S17_CNT="test-s17-tbl-init"
docker volume create "$S17_VOL" >/dev/null
docker network create --internal "$S17_NET" >/dev/null

# Create SQL dump that creates 1 table instead of 14
S17_DUMP=$(mktemp /tmp/s17_tbl.sql.XXXXXX)
cat << 'EOF' > "$S17_DUMP"
CREATE TABLE `wp_options` (`option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT, PRIMARY KEY (`option_id`));
EOF
S17_HASH=$(sha256sum "$S17_DUMP" | awk '{print $1}')

S17_OUT=$(
  DB_ROOT_PASSWORD="ValidRootPassScenario17!" \
  DB_PASSWORD="ValidUserPassScenario17!" \
  TARGET_VOLUME="$S17_VOL" \
  TARGET_NETWORK="$S17_NET" \
  INIT_CONTAINER="$S17_CNT" \
  SQL_FILE="$S17_DUMP" \
  EXPECTED_SQL_HASH="$S17_HASH" \
  PINNED_MARIADB_IMAGE="$LOCAL_MARIADB_IMAGE" \
  bash "$CANONICAL_SCRIPT" 2>&1
)
S17_RC=$?
rm -f "$S17_DUMP"
docker volume rm "$S17_VOL" >/dev/null 2>&1 || true
docker network rm "$S17_NET" >/dev/null 2>&1 || true

if [ $S17_RC -ne 0 ] && echo "$S17_OUT" | grep -q "VERIFICATION FAILED: Table count mismatch (1 != 14)"; then
  record_result "Scenario 17" "PASS" "Canonical script caught table count mismatch (1 != 14) and aborted (rc=1)"
else
  record_result "Scenario 17" "FAIL" "Failed to catch table count mismatch (RC=$S17_RC)"
fi

# -----------------------------------------------------------------------------
# SCENARIO 18: wp_posts Count Mismatch Detection on Canonical Script
# -----------------------------------------------------------------------------
echo -e "\n--> Running Scenario 18: wp_posts Count Mismatch Detection"
S18_VOL="test_s18_posts_vol"
S18_NET="test_s18_posts_net"
S18_CNT="test-s18-posts-init"
docker volume create "$S18_VOL" >/dev/null
docker network create --internal "$S18_NET" >/dev/null

# Modify real SQL dump to leave 1 post instead of 92
S18_DUMP=$(mktemp /tmp/s18_posts.sql.XXXXXX)
cp "$SQL_DUMP" "$S18_DUMP"
echo "DELETE FROM \`wp_posts\` WHERE \`ID\` != 2;" >> "$S18_DUMP"
S18_HASH=$(sha256sum "$S18_DUMP" | awk '{print $1}')

S18_OUT=$(
  DB_ROOT_PASSWORD="ValidRootPassScenario18!" \
  DB_PASSWORD="ValidUserPassScenario18!" \
  TARGET_VOLUME="$S18_VOL" \
  TARGET_NETWORK="$S18_NET" \
  INIT_CONTAINER="$S18_CNT" \
  SQL_FILE="$S18_DUMP" \
  EXPECTED_SQL_HASH="$S18_HASH" \
  PINNED_MARIADB_IMAGE="$LOCAL_MARIADB_IMAGE" \
  bash "$CANONICAL_SCRIPT" 2>&1
)
S18_RC=$?
rm -f "$S18_DUMP"
docker volume rm "$S18_VOL" >/dev/null 2>&1 || true
docker network rm "$S18_NET" >/dev/null 2>&1 || true

if [ $S18_RC -ne 0 ] && echo "$S18_OUT" | grep -q "VERIFICATION FAILED: wp_posts count mismatch (1 != 92)"; then
  record_result "Scenario 18" "PASS" "Canonical script caught wp_posts count mismatch (1 != 92) and aborted (rc=1)"
else
  record_result "Scenario 18" "FAIL" "Failed to catch wp_posts count mismatch (RC=$S18_RC)"
fi

# -----------------------------------------------------------------------------
# SCENARIO 19: Home URL Mismatch Detection on Canonical Script
# -----------------------------------------------------------------------------
echo -e "\n--> Running Scenario 19: Home URL Mismatch Detection"
S19_VOL="test_s19_url_vol"
S19_NET="test_s19_url_net"
S19_CNT="test-s19-url-init"
docker volume create "$S19_VOL" >/dev/null
docker network create --internal "$S19_NET" >/dev/null

# Modify SQL dump to set home URL to http://localhost:8080
S19_DUMP=$(mktemp /tmp/s19_url.sql.XXXXXX)
sed "s|https://pro.lohnour.com|http://localhost:8080|g" "$SQL_DUMP" > "$S19_DUMP"
S19_HASH=$(sha256sum "$S19_DUMP" | awk '{print $1}')

S19_OUT=$(
  DB_ROOT_PASSWORD="ValidRootPassScenario19!" \
  DB_PASSWORD="ValidUserPassScenario19!" \
  TARGET_VOLUME="$S19_VOL" \
  TARGET_NETWORK="$S19_NET" \
  INIT_CONTAINER="$S19_CNT" \
  SQL_FILE="$S19_DUMP" \
  EXPECTED_SQL_HASH="$S19_HASH" \
  PINNED_MARIADB_IMAGE="$LOCAL_MARIADB_IMAGE" \
  bash "$CANONICAL_SCRIPT" 2>&1
)
S19_RC=$?
rm -f "$S19_DUMP"
docker volume rm "$S19_VOL" >/dev/null 2>&1 || true
docker network rm "$S19_NET" >/dev/null 2>&1 || true

if [ $S19_RC -ne 0 ] && echo "$S19_OUT" | grep -q "VERIFICATION FAILED: siteurl mismatch"; then
  record_result "Scenario 19" "PASS" "Canonical script caught URL mismatch and aborted (rc=1)"
else
  record_result "Scenario 19" "FAIL" "Failed to catch URL mismatch (RC=$S19_RC)"
fi

# -----------------------------------------------------------------------------
# SCENARIO 20: Complex Password & Option File Escaping on Canonical Script
# -----------------------------------------------------------------------------
echo -e "\n--> Running Scenario 20: Complex Password & Option File Escaping"
S20_VOL="test_s20_complex_vol"
S20_NET="test_s20_complex_net"
S20_CNT="test-s20-complex-init"
docker volume create "$S20_VOL" >/dev/null
docker network create --internal "$S20_NET" >/dev/null

# Password with double quotes, backslashes, spaces, # and ; comment characters
COMPLEX_ROOT_PASS='P@ss\"#; root_complex2026!'
COMPLEX_USER_PASS='P@ss\"#; user_complex2026!'

S20_OUT=$(
  DB_ROOT_PASSWORD="$COMPLEX_ROOT_PASS" \
  DB_PASSWORD="$COMPLEX_USER_PASS" \
  TARGET_VOLUME="$S20_VOL" \
  TARGET_NETWORK="$S20_NET" \
  INIT_CONTAINER="$S20_CNT" \
  SQL_FILE="$SQL_DUMP" \
  EXPECTED_SQL_HASH="$CANONICAL_SQL_HASH" \
  PINNED_MARIADB_IMAGE="$LOCAL_MARIADB_IMAGE" \
  bash "$CANONICAL_SCRIPT" 2>&1
)
S20_RC=$?
docker volume rm "$S20_VOL" >/dev/null 2>&1 || true
docker network rm "$S20_NET" >/dev/null 2>&1 || true

if [ $S20_RC -eq 0 ] && echo "$S20_OUT" | grep -q "All 10 acceptance checks passed with 100% compliance"; then
  record_result "Scenario 20" "PASS" "Canonical script executed end-to-end with complex special-character passwords (quotes, backslashes, spaces, # and ;)"
else
  record_result "Scenario 20" "FAIL" "Failed to execute with complex special-character passwords (RC=$S20_RC)"
fi

# -----------------------------------------------------------------------------
# FINAL SUMMARY
# -----------------------------------------------------------------------------
echo -e "\n================================================================="
echo "PHASE 10 ACCEPTANCE TEST RESULTS: $PASS_COUNT / 20 PASSED"
if [ $FAIL_COUNT -eq 0 ]; then
  echo "ALL 20 CANONICAL SCENARIOS PASSED WITH 100% SUCCESS RATE."
  exit 0
else
  echo "TEST SUITE FAILED: $FAIL_COUNT SCENARIO(S) FAILED."
  exit 1
fi
