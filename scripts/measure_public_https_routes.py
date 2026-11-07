import urllib.request
import ssl
import time
import json

routes = [
    ("/", "Homepage: 9 sections & testimonial slider", ["hero", "testimonial", "footer"]),
    ("/about/", "About: Founder profile & story", ["about", "founder", "lohnour"]),
    ("/services/", "Services: Pathway cards & taxonomy", ["service", "pathway"]),
    ("/coaching/", "Coaching: Bento grid pathways", ["coaching", "bento"]),
    ("/coaching/agentic-ai-foundations/", "Curriculum: All 12 modules", ["module", "curriculum", "agentic"]),
    ("/guides/", "Guides: Knowledge base archive", ["guide"]),
    ("/contact/", "Contact: Inquiry form & Brevo fields", ["contact", "form"]),
    ("/blog/", "Blog: Insights & article index", ["blog", "article", "post"]),
    ("/wp-login.php", "Admin: Administrative login portal", ["loginform", "wp-login", "user_login"])
]

base = "https://pro.lohnour.com"
ctx = ssl.create_default_context()
results = []

print("=== AUDIT E: PUBLIC HTTPS ROUTE MEASUREMENT ===")

for path, desc, kw in routes:
    url = base + path
    req = urllib.request.Request(url, headers={"User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) LohnourAuditor/1.0"})
    
    start = time.perf_counter()
    try:
        with urllib.request.urlopen(req, context=ctx, timeout=15) as resp:
            elapsed_ms = round((time.perf_counter() - start) * 1000, 2)
            code = resp.getcode()
            headers = dict(resp.getheaders())
            body = resp.read().decode("utf-8", errors="replace")
            
            body_lower = body.lower()
            matched_kw = [k for k in kw if k in body_lower]
            has_setup = "wp-admin/setup-config.php" in body_lower
            has_fatal = "fatal error" in body_lower or "parse error" in body_lower
            has_db_err = "error establishing a database connection" in body_lower
            
            passed = (code == 200 and not has_setup and not has_fatal and not has_db_err and len(matched_kw) > 0)
            
            item = {
                "route": path,
                "description": desc,
                "status": code,
                "response_time_ms": elapsed_ms,
                "bytes": len(body),
                "passed": passed,
                "matched_keywords": matched_kw,
                "hsts": headers.get("strict-transport-security"),
                "caddy_via": headers.get("via"),
                "php_powered": headers.get("x-powered-by"),
                "alt_svc": headers.get("alt-svc")
            }
            results.append(item)
            print(f"[{'PASS' if passed else 'FAIL'}] {path:35} -> HTTP {code} ({elapsed_ms:6.1f} ms, {len(body):6} B) | {desc}")
    except Exception as e:
        elapsed_ms = round((time.perf_counter() - start) * 1000, 2)
        print(f"[FAIL] {path:35} -> ERROR ({elapsed_ms} ms): {e}")
        results.append({
            "route": path,
            "description": desc,
            "status": 0,
            "response_time_ms": elapsed_ms,
            "passed": False,
            "error": str(e)
        })

print("\n--- Summary ---")
pass_count = sum(1 for r in results if r["passed"])
print(f"Total Routes Verified: {len(results)}")
print(f"Passed: {pass_count}/{len(results)}")

with open("d:/lohnourpro/scripts/audit_e_route_measurements.json", "w", encoding="utf-8") as f:
    json.dump(results, f, indent=2)
print("Saved d:/lohnourpro/scripts/audit_e_route_measurements.json")

