import subprocess
import json

routes = [
    ("/", "full 9 sections and testimonial slider", ["hero", "testimonial", "footer"]),
    ("/about/", "founder profile", ["about", "founder", "lohnour"]),
    ("/services/", "pathway cards", ["service", "pathway"]),
    ("/coaching/", "bento grid", ["coaching", "bento"]),
    ("/coaching/agentic-ai-foundations/", "all 12 modules", ["module", "curriculum", "agentic"]),
    ("/guides/", "guides directory", ["guide"]),
    ("/contact/", "inquiry form", ["contact", "form"]),
    ("/blog/", "blog index", ["blog", "article", "post"]),
    ("/wp-login.php", "administrative login", ["loginform", "wp-login", "user_login"])
]

print("=== 9-ROUTE INTERNAL INGRESS ACCEPTANCE AUDIT ===")
results = []

for path, desc, keywords in routes:
    cmd = f"docker run --rm --network proxy_network curlimages/curl:8.5.0 -s -w '\\n---HTTP_STATUS:%{{http_code}}---' --max-time 10 -H 'Host: pro.lohnour.com' -H 'X-Forwarded-Proto: https' http://lohnour-wordpress{path}"
    p = subprocess.run(cmd, shell=True, capture_output=True, text=True)
    raw = p.stdout
    
    status_code = "000"
    body = raw
    if "---HTTP_STATUS:" in raw:
        parts = raw.split("---HTTP_STATUS:")
        body = parts[0]
        status_code = parts[1].replace("---", "").strip()
    
    # Check keywords in body
    body_lower = body.lower()
    kw_found = [kw for kw in keywords if kw in body_lower]
    
    # Content validations
    has_setup = "wp-admin/setup-config.php" in body_lower
    has_fatal = "fatal error" in body_lower or "parse error" in body_lower
    has_db_error = "error establishing a database connection" in body_lower
    
    passed = (status_code == "200" and not has_setup and not has_fatal and not has_db_error and len(kw_found) > 0)
    
    # Specifically for route 5, check modules count
    extra_info = ""
    if path == "/coaching/agentic-ai-foundations/":
        module_count = body_lower.count("module-card") + body_lower.count("curriculum-module") + body_lower.count("module ")
        extra_info = f" (Module markers found: {module_count})"
        
    res = {
        "path": path,
        "description": desc,
        "status_code": status_code,
        "bytes": len(body),
        "keywords_found": kw_found,
        "has_setup_wizard": has_setup,
        "has_fatal_error": has_fatal,
        "has_db_error": has_db_error,
        "passed": passed,
        "extra_info": extra_info
    }
    results.append(res)
    print(f"[{'PASS' if passed else 'FAIL'}] {path:35} -> HTTP {status_code} ({len(body)} bytes) | Keywords: {kw_found}{extra_info}")

print("\n--- Summary ---")
all_passed = all(r["passed"] for r in results)
print(f"All 9 routes passed: {all_passed} ({sum(1 for r in results if r['passed'])}/9)")

