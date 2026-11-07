import subprocess
import json

containers = ["lohnour-caddy", "lohnour-wordpress", "lohnour-db", "portainer", "nodered"]
report = {}

for c in containers:
    inspect_cmd = f"docker inspect {c}"
    p = subprocess.run(inspect_cmd, shell=True, capture_output=True, text=True)
    if p.returncode != 0:
        report[c] = {"error": f"Failed to inspect: {p.stderr.strip()}"}
        continue
    data = json.loads(p.stdout)[0]
    
    # Get last 20 log lines
    log_cmd = f"docker logs --tail 20 {c}"
    plog = subprocess.run(log_cmd, shell=True, capture_output=True, text=True)
    logs = (plog.stdout + "\n" + plog.stderr).strip().splitlines()[-10:]
    
    report[c] = {
        "status": data["State"]["Status"],
        "running": data["State"]["Running"],
        "paused": data["State"]["Paused"],
        "restarting": data["State"]["Restarting"],
        "ooms": data["State"].get("OOMKilled", False),
        "exit_code": data["State"]["ExitCode"],
        "health": data["State"].get("Health", {}).get("Status", "N/A"),
        "restart_count": data["RestartCount"],
        "started_at": data["State"]["StartedAt"],
        "finished_at": data["State"]["FinishedAt"],
        "image": data["Config"]["Image"],
        "ports": data["HostConfig"]["PortBindings"],
        "networks": list(data["NetworkSettings"]["Networks"].keys()),
        "mounts": [{
            "type": m["Type"],
            "name": m.get("Name"),
            "source": m.get("Source"),
            "destination": m.get("Destination"),
            "mode": m.get("Mode"),
            "rw": m.get("RW")
        } for m in data["Mounts"]],
        "recent_logs_tail": logs
    }

print(json.dumps(report, indent=2))

