import sys
import time
import requests
from playwright.sync_api import sync_playwright

sys.stdout.reconfigure(encoding='utf-8')

LIVE_BASE_URL = "https://pro.lohnour.com"
PAGE_URL = f"{LIVE_BASE_URL}/coaching/agentic-ai-foundations/?v=hero_opacity_v1"

CORE_ROUTES = [
    "/",
    "/services/",
    "/about/",
    "/contact/",
    "/blog/",
    "/guides/",
    "/coaching/",
    "/coaching/agentic-ai-foundations/"
]

VIEWPORTS = [
    {"name": "1440_desktop", "width": 1440, "height": 900},
    {"name": "1280_desktop", "width": 1280, "height": 800},
    {"name": "1024_landscape", "width": 1024, "height": 768},
    {"name": "834_tablet", "width": 834, "height": 1194},
    {"name": "768_tablet", "width": 768, "height": 1024},
    {"name": "430_mobile", "width": 430, "height": 932},
    {"name": "414_mobile", "width": 414, "height": 896},
    {"name": "390_mobile", "width": 390, "height": 844},
    {"name": "375_mobile", "width": 375, "height": 667},
    {"name": "360_mobile", "width": 360, "height": 800},
]

print("=" * 50, flush=True)
print("1. CHECKING CORE ROUTES HTTP STATUS", flush=True)
print("=" * 50, flush=True)
for route in CORE_ROUTES:
    url = f"{LIVE_BASE_URL}{route}"
    try:
        r = requests.get(url, timeout=15)
        status = r.status_code
        if status == 200:
            print(f"[{status}] OK: {url}", flush=True)
        else:
            print(f"[{status}] FAIL: {url}", flush=True)
            sys.exit(1)
    except Exception as e:
        print(f"ERROR requesting {url}: {e}", flush=True)
        sys.exit(1)

print("\n" + "=" * 50, flush=True)
print("2. VERIFYING LIVE HERO OPACITY ON PRODUCTION", flush=True)
print("=" * 50, flush=True)

with sync_playwright() as p:
    browser = p.chromium.launch(headless=True)
    
    for vp in VIEWPORTS:
        context = browser.new_context(viewport={"width": vp["width"], "height": vp["height"]})
        page = context.new_page()
        res = page.goto(PAGE_URL, wait_until="networkidle", timeout=30000)
        status = res.status if res else "Unknown"
        
        print(f"\nViewport {vp['width']}x{vp['height']} ({vp['name']}): HTTP {status}", flush=True)
        
        # Check horizontal overflow
        overflow_info = page.evaluate("""() => {
            return {
                scrollWidth: document.documentElement.scrollWidth,
                clientWidth: document.documentElement.clientWidth,
                hasOverflow: document.documentElement.scrollWidth > document.documentElement.clientWidth
            };
        }""")
        
        if overflow_info['hasOverflow']:
            print(f"  HORIZONTAL OVERFLOW DETECTED: scrollWidth={overflow_info['scrollWidth']}, clientWidth={overflow_info['clientWidth']}", flush=True)
            sys.exit(1)
        else:
            print(f"  Horizontal Overflow: PASS (zero overflow) (scrollWidth={overflow_info['scrollWidth']}, clientWidth={overflow_info['clientWidth']})", flush=True)
            
        hero_elem = page.locator(".program-hero")
        
        # 1. LIGHT THEME VERIFICATION
        page.evaluate("""() => {
            document.documentElement.className = 'light-theme';
            document.documentElement.setAttribute('data-theme', 'light');
        }""")
        time.sleep(0.2)
        
        light_metrics = page.evaluate("""() => {
            const lightImg = document.querySelector('.banner-theme-light');
            const darkImg = document.querySelector('.banner-theme-dark');
            const title = document.querySelector('.program-hero-title');
            const desc = document.querySelector('.program-hero-desc');
            const cta = document.querySelector('.hero-btn-primary');
            const breadcrumb = document.querySelector('.program-hero-breadcrumb');
            const meta = document.querySelector('.meta-chip');
            
            return {
                lightDisp: lightImg ? getComputedStyle(lightImg).display : 'none',
                lightOpacity: lightImg ? parseFloat(getComputedStyle(lightImg).opacity) : null,
                darkDisp: darkImg ? getComputedStyle(darkImg).display : 'none',
                titleOpacity: title ? parseFloat(getComputedStyle(title).opacity) : null,
                descOpacity: desc ? parseFloat(getComputedStyle(desc).opacity) : null,
                ctaOpacity: cta ? parseFloat(getComputedStyle(cta).opacity) : null,
                breadcrumbOpacity: breadcrumb ? parseFloat(getComputedStyle(breadcrumb).opacity) : null,
                metaOpacity: meta ? parseFloat(getComputedStyle(meta).opacity) : null
            };
        }""")
        
        print(f"  [Light Theme] Image Opacity: {light_metrics['lightOpacity']} (Display: {light_metrics['lightDisp']})", flush=True)
        print(f"  [Light Theme] Text/Foreground Opacity: title={light_metrics['titleOpacity']}, desc={light_metrics['descOpacity']}, cta={light_metrics['ctaOpacity']}, meta={light_metrics['metaOpacity']}", flush=True)
        
        if abs(light_metrics['lightOpacity'] - 0.85) > 0.01:
            print(f"  FAIL: Expected Light Image opacity 0.85, got {light_metrics['lightOpacity']}", flush=True)
            sys.exit(1)
        if light_metrics['titleOpacity'] != 1.0 or light_metrics['descOpacity'] != 1.0 or light_metrics['ctaOpacity'] != 1.0:
            print("  FAIL: Foreground text/CTA opacity was modified!", flush=True)
            sys.exit(1)
            
        light_shot = f"live_hero_light_{vp['name']}.png"
        hero_elem.screenshot(path=light_shot)
        print(f"  Saved screenshot: {light_shot}", flush=True)
        
        # 2. DARK THEME VERIFICATION
        page.evaluate("""() => {
            document.documentElement.className = 'dark-theme';
            document.documentElement.setAttribute('data-theme', 'dark');
        }""")
        time.sleep(0.2)
        
        dark_metrics = page.evaluate("""() => {
            const lightImg = document.querySelector('.banner-theme-light');
            const darkImg = document.querySelector('.banner-theme-dark');
            const title = document.querySelector('.program-hero-title');
            const desc = document.querySelector('.program-hero-desc');
            const cta = document.querySelector('.hero-btn-primary');
            const meta = document.querySelector('.meta-chip');
            
            return {
                darkDisp: darkImg ? getComputedStyle(darkImg).display : 'none',
                darkOpacity: darkImg ? parseFloat(getComputedStyle(darkImg).opacity) : null,
                lightDisp: lightImg ? getComputedStyle(lightImg).display : 'none',
                titleOpacity: title ? parseFloat(getComputedStyle(title).opacity) : null,
                descOpacity: desc ? parseFloat(getComputedStyle(desc).opacity) : null,
                ctaOpacity: cta ? parseFloat(getComputedStyle(cta).opacity) : null,
                metaOpacity: meta ? parseFloat(getComputedStyle(meta).opacity) : null
            };
        }""")
        
        print(f"  [Dark Theme] Image Opacity: {dark_metrics['darkOpacity']} (Display: {dark_metrics['darkDisp']})", flush=True)
        print(f"  [Dark Theme] Text/Foreground Opacity: title={dark_metrics['titleOpacity']}, desc={dark_metrics['descOpacity']}, cta={dark_metrics['ctaOpacity']}, meta={dark_metrics['metaOpacity']}", flush=True)
        
        if abs(dark_metrics['darkOpacity'] - 0.80) > 0.01:
            print(f"  FAIL: Expected Dark Image opacity 0.80, got {dark_metrics['darkOpacity']}", flush=True)
            sys.exit(1)
        if dark_metrics['titleOpacity'] != 1.0 or dark_metrics['descOpacity'] != 1.0 or dark_metrics['ctaOpacity'] != 1.0:
            print("  FAIL: Foreground text/CTA opacity was modified!", flush=True)
            sys.exit(1)
            
        dark_shot = f"live_hero_dark_{vp['name']}.png"
        hero_elem.screenshot(path=dark_shot)
        print(f"  Saved screenshot: {dark_shot}", flush=True)
        
        context.close()
        
    browser.close()

print("\n" + "=" * 50, flush=True)
print("ALL LIVE HERO OPACITY VERIFICATIONS COMPLETED SUCCESSFULLY!", flush=True)
print("=" * 50, flush=True)
