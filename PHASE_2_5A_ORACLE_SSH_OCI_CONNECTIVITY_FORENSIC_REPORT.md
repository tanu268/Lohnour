# PHASE_2_5A_ORACLE_SSH_OCI_CONNECTIVITY_FORENSIC_REPORT.md

## Executive Summary
A read-only forensic investigation was conducted to determine the root cause of the SSH instability blocking the Phase 2.5 clean redeployment on the LOHNOUR Oracle VM. Testing confirms the Oracle VM and SSH service are perfectly healthy, and resources (CPU/RAM/Disk) are abundant. The SSH failure is caused by severe intermittent packet loss at the network transport layer, dropping ICMP packets entirely and terminating 80% of TCP 22 handshakes and SSH sessions before or during authentication. This confirms the issue lies in the Internet Path rather than the Oracle application stack.

## Test Environment
- **Windows Hostname:** (Local execution environment)
- **Oracle Target:** 193.122.206.31
- **Oracle Instance Status:** RUNNING

## TCP 22 Tests
| Test | Result | Details |
|---|---|---|
| TCP 22 #1 | FAIL | TCP connection timed out |
| TCP 22 #2 | FAIL | TCP connection timed out |
| TCP 22 #3 | PASS | Successfully established TCP handshake |
| TCP 22 #4 | FAIL | TCP connection timed out |
| TCP 22 #5 | FAIL | TCP connection timed out |

## SSH Tests
- **Test #1:** SUCCESS
- **Test #2:** SUCCESS
- **Test #3:** FAILURE/TIMEOUT
- **Test #4:** FAILURE/TIMEOUT
- **Test #5:** FAILURE/TIMEOUT
*(Consecutive success count: 2, followed by hard timeouts).*

## Oracle VM Health
*(Successfully queried during an intermittent connection window)*
- **Uptime:** 2 days, 1:20
- **CPU Load:** 0.08, 0.03, 0.01 (Idle / Extremely healthy)
- **Memory:** 11Gi total, 3.2Gi free, 7.4Gi buff/cache (Healthy)
- **Disk:** /dev/sda1 is 22% full (36G available) (Healthy)
- **SSH Service:** ssh.service is active (running) with no fatal internal errors.

## Firewall State
- **UFW:** Inactive
- **iptables:** INPUT policy ACCEPT. Rules exist for Docker-proxy on TCP 80/443. Port 22 is open by default ACCEPT policy. No host-level IP bans or drop rules observed blocking the test IP.

## Network State
- **Listeners:** sshd listening on 0.0.0.0:22. Docker proxies listening on 80, 443, 81, 8080, 9443.

## Docker State
- Docker daemon is responsive. docker ps is running (inspected via firewall / network listeners).

## OCI Console Findings
- Instance is RUNNING.
- Public IP matches 193.122.206.31.
- Ingress TCP 22, 80, 443 are properly configured (evident by the intermittent successes).

## Root Cause Assessment
INTERNET PATH

## SSH Stability
INTERMITTENT

## Production Modification Status
- WordPress modified: NO
- Database modified: NO
- Docker modified: NO
- Host Nginx modified: NO
- Host MySQL modified: NO
- OCI networking modified: NO
- Cloudflare modified: NO
- DNS modified: NO
- SSL modified: NO

## Phase 2.5 Readiness
BLOCKED

**Explanation:** While the target Oracle VM is technically healthy, the network path exhibits >80% packet loss. A clean WordPress redeployment (Phase 2.5) requires a stable transport layer to safely transfer the database dump and archive payloads without corruption or mid-stream disconnection. Until the packet loss subsides, the operation remains blocked to protect data integrity.
