import asyncio
import urllib.request
import os
from playwright.async_api import async_playwright

URL = "https://pro.lohnour.com/coaching/agentic-ai-foundations/?nocache=overlay_final"
ARTIFACT_DIR = r"C:\Users\knamd\.gemini\antigravity-ide\brain\2550e99a-70f2-4de5-817a-9147af38f451"

ROUTES = [
    "https://pro.lohnour.com/",
    "https://pro.lohnour.com/services/",
    "https://pro.lohnour.com/about/",
    "https://pro.lohnour.com/contact/",
    "https://pro.lohnour.com/blog/",
    "https://pro.lohnour.com/guides/",
    "https://pro.lohnour.com/coaching/",
    "https://pro.lohnour.com/coaching/agentic-ai-foundations/",
]

VIEWPORTS = [
    (1440, 900, "1440_desktop"),
    (1280, 800, "1280_desktop"),
    (1024, 768, "1024_landscape"),
    (834, 1194, "834_tablet"),
    (768, 1024, "768_tablet"),
    (430, 932, "430_mobile"),
    (414, 896, "414_mobile"),
    (390, 844, "390_mobile"),
    (375, 667, "375_mobile"),
    (360, 800, "360_mobile"),
]

async def verify():
    print("==================================================")
    print("1. CHECKING CORE ROUTES HTTP STATUS")
    print("==================================================")
    req_headers = {'User-Agent': 'Mozilla/5.0'}
    all_routes_ok = True
    for r in ROUTES:
        try:
            req = urllib.request.Request(r, headers=req_headers)
            with urllib.request.urlopen(req, timeout=10) as resp:
                print(f"[{resp.status}] OK: {r}")
                if resp.status != 200:
                    all_routes_ok = False
        except Exception as e:
            print(f"ERROR on {r}: {e}")
            all_routes_ok = False

    if not all_routes_ok:
        print("WARNING: Some routes failed status check!")

    print("\n==================================================")
    print("2. RUNNING PLAYWRIGHT VERIFICATION ON LIVE PRODUCTION")
    print("==================================================")
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page()

        for w, h, label in VIEWPORTS:
            await page.set_viewport_size({"width": w, "height": h})
            resp = await page.goto(URL, wait_until="networkidle")
            status = resp.status if resp else "N/A"

            # Check horizontal overflow
            overflow = await page.evaluate("() => document.documentElement.scrollWidth > window.innerWidth")
            scroll_width = await page.evaluate("() => document.documentElement.scrollWidth")
            client_width = await page.evaluate("() => window.innerWidth")

            print(f"\nViewport {w}x{h} ({label}): HTTP {status}")
            print(f"  Horizontal Overflow: {'FAIL' if overflow else 'PASS (zero overflow)'} (scrollWidth={scroll_width}, clientWidth={client_width})")

            # Test Dark Theme
            await page.evaluate("() => { document.documentElement.classList.remove('light-theme'); document.documentElement.classList.add('dark-theme'); }")
            await page.wait_for_timeout(100)
            
            dark_display = await page.evaluate("() => window.getComputedStyle(document.querySelector('.banner-theme-dark')).display")
            light_display = await page.evaluate("() => window.getComputedStyle(document.querySelector('.banner-theme-light')).display")
            print(f"  Dark Theme check: dark_img={dark_display} (expected block), light_img={light_display} (expected none)")

            hero = await page.query_selector('.program-hero')
            if hero:
                await hero.scroll_into_view_if_needed()
                shot_path = os.path.join(ARTIFACT_DIR, f"live_hero_dark_{label}.png")
                await hero.screenshot(path=shot_path)
                print(f"  Saved screenshot: live_hero_dark_{label}.png")

            # Test Light Theme
            await page.evaluate("() => { document.documentElement.classList.remove('dark-theme'); document.documentElement.classList.add('light-theme'); }")
            await page.wait_for_timeout(100)

            dark_display_lt = await page.evaluate("() => window.getComputedStyle(document.querySelector('.banner-theme-dark')).display")
            light_display_lt = await page.evaluate("() => window.getComputedStyle(document.querySelector('.banner-theme-light')).display")
            print(f"  Light Theme check: dark_img={dark_display_lt} (expected none), light_img={light_display_lt} (expected block)")

            if hero:
                await hero.scroll_into_view_if_needed()
                shot_path = os.path.join(ARTIFACT_DIR, f"live_hero_light_{label}.png")
                await hero.screenshot(path=shot_path)
                print(f"  Saved screenshot: live_hero_light_{label}.png")

        await browser.close()

    print("\n==================================================")
    print("ALL LIVE PRODUCTION VERIFICATIONS COMPLETED")
    print("==================================================")

if __name__ == "__main__":
    asyncio.run(verify())
