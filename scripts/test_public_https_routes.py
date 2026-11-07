import urllib.request
import ssl
import json
import re

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

base_url = "https://pro.lohnour.com"
ctx = ssl.create_default_context()

print("==============================================================================")
print("PHASE 13: 9-ROUTE PUBLIC HTTPS ACCEPTANCE VERIFICATION")
print(f"Target: {base_url}")
print("==============================================================================")

results = []

for path, desc, keywords in routes:
    full_url = base_url + path
    req = urllib.request.Request(
        full_url,
        headers={"User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) Antigravity-Acceptance/1.0"}
    )
    
    try:
        with urllib.request.urlopen(req, context=ctx, timeout=15) as resp:
            status_code = resp.getcode()
            headers = dict(resp.getheaders())
            body_bytes = resp.read()
            body = body_bytes.decode("utf-8", errors="replace")
            
            body_lower = body.lower()
            kw_found = [kw for kw in keywords if kw in body_lower]
            
            has_setup = "wp-admin/setup-config.php" in body_lower
            has_fatal = "fatal error" in body_lower or "parse error" in body_lower
            has_db_error = "error establishing a database connection" in body_lower
            
            passed = (status_code == 200 and not has_setup and not has_fatal and not has_db_error and len(kw_found) > 0)
            
            extra_info = ""
            if path == "/coaching/agentic-ai-foundations/":
                # Verify modules
                module_count = body_lower.count("module-card") + body_lower.count("curriculum-module") + body_lower.count("module ")
                extra_info = f" (Module markers found: {module_count})"
            
            res = {
                "route": path,
                "url": full_url,
                "status_code": status_code,
                "bytes": len(body_bytes),
                "passed": passed,
                "keywords": kw_found,
                "hsts": headers.get("strict-transport-security", "missing"),
                "caddy_via": headers.get("via", "missing"),
                "php_powered": headers.get("x-powered-by", "missing"),
                "extra_info": extra_info
            }
            results.append(res)
            print(f"[{'PASS' if passed else 'FAIL'}] {path:35} -> HTTP {status_code} ({len(body_bytes):6} B) | Keywords: {kw_found}{extra_info}")
    except Exception as e:
        print(f"[FAIL] {path:35} -> ERROR: {e}")
        results.append({
            "route": path,
            "url": full_url,
            "status_code": 0,
            "bytes": 0,
            "passed": False,
            "error": str(e)
        })

print("\n--- Summary ---")
pass_count = sum(1 for r in results if r.get("passed"))
print(f"Total Routes Verified: {len(results)}")
print(f"Passed: {pass_count}/{len(results)}")
print(f"All Routes Passed: {pass_count == len(results)}")

with open("d:/lohnourpro/scripts/public_https_acceptance_results.json", "w", encoding="utf-8") as f:
    json.dump(results, f, indent=2)
print("Saved d:/lohnourpro/scripts/public_https_acceptance_results.json")

