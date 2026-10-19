import asyncio
import urllib.request
import os
from playwright.async_api import async_playwright

URL = "https://pro.lohnour.com/coaching/agentic-ai-foundations/?v=live_no_nav_check"
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
    for r in ROUTES:
        try:
            req = urllib.request.Request(r, headers=req_headers)
            with urllib.request.urlopen(req, timeout=10) as resp:
                print(f"[{resp.status}] OK: {r}")
        except Exception as e:
            print(f"ERROR on {r}: {e}")

    print("\n==================================================")
    print("2. VERIFYING LIVE CURRICULUM ON PRODUCTION")
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

            # Check sidebar and mobile toggle removal
            sidebar_exists = await page.evaluate("() => document.getElementById('curriculum-guide-sidebar') !== null")
            toggle_exists = await page.evaluate("() => document.querySelector('.curriculum-mobile-outline-toggle-wrapper') !== null")

            # Check 8 modules presence
            mod_count = await page.evaluate("() => document.querySelectorAll('.curriculum-guide-module-block').length")

            print(f"\nViewport {w}x{h} ({label}): HTTP {status}")
            print(f"  Horizontal Overflow: {'FAIL' if overflow else 'PASS (zero overflow)'} (scrollWidth={scroll_width}, clientWidth={client_width})")
            print(f"  Left Sidebar Removed: {'PASS (not in DOM)' if not sidebar_exists else 'FAIL (sidebar still in DOM)'}")
            print(f"  Mobile Toggle Removed: {'PASS (not in DOM)' if not toggle_exists else 'FAIL (toggle still in DOM)'}")
            print(f"  Modules Present: {'PASS (8 modules)' if mod_count == 8 else f'FAIL ({mod_count} modules)'}")

            # Test Dark Theme
            await page.evaluate("() => { document.documentElement.classList.remove('light-theme'); document.documentElement.classList.add('dark-theme'); }")
            await page.wait_for_timeout(100)

            curriculum_sec = await page.query_selector('#curriculum')
            if curriculum_sec:
                await curriculum_sec.scroll_into_view_if_needed()
                await page.wait_for_timeout(100)
                shot_path = os.path.join(ARTIFACT_DIR, f"live_curriculum_dark_{label}.png")
                await curriculum_sec.screenshot(path=shot_path)
                print(f"  Saved screenshot: live_curriculum_dark_{label}.png")

            # Test Light Theme
            await page.evaluate("() => { document.documentElement.classList.remove('dark-theme'); document.documentElement.classList.add('light-theme'); }")
            await page.wait_for_timeout(100)
            if curriculum_sec:
                await curriculum_sec.scroll_into_view_if_needed()
                await page.wait_for_timeout(100)
                shot_path = os.path.join(ARTIFACT_DIR, f"live_curriculum_light_{label}.png")
                await curriculum_sec.screenshot(path=shot_path)
                print(f"  Saved screenshot: live_curriculum_light_{label}.png")

        # Test Accordion interactive click on Live Production
        print("\n==================================================")
        print("3. TESTING LIVE ACCORDION INTERACTIVITY")
        print("==================================================")
        await page.set_viewport_size({"width": 1280, "height": 800})
        await page.goto(URL, wait_until="networkidle")
        
        # Test clicking Practical accordion in Module 1
        practical_btn = await page.query_selector('#curriculum-mod-1-practical .guides-accordion-header')
        if practical_btn:
            aria_before = await practical_btn.get_attribute('aria-expanded')
            await practical_btn.click()
            await page.wait_for_timeout(150)
            aria_after = await practical_btn.get_attribute('aria-expanded')
            is_active = await page.evaluate("() => document.getElementById('curriculum-mod-1-practical').classList.contains('active')")
            print(f"  Practical Accordion click: aria-expanded before={aria_before}, after={aria_after}, activeClass={is_active} -> {'PASS' if is_active and aria_after == 'true' else 'FAIL'}")

        # Test clicking Outcome accordion in Module 1
        outcome_btn = await page.query_selector('#curriculum-mod-1-outcome .guides-accordion-header')
        if outcome_btn:
            aria_before_out = await outcome_btn.get_attribute('aria-expanded')
            await outcome_btn.click()
            await page.wait_for_timeout(150)
            aria_after_out = await outcome_btn.get_attribute('aria-expanded')
            is_active_out = await page.evaluate("() => document.getElementById('curriculum-mod-1-outcome').classList.contains('active')")
            print(f"  Outcome Accordion click: aria-expanded before={aria_before_out}, after={aria_after_out}, activeClass={is_active_out} -> {'PASS' if is_active_out and aria_after_out == 'true' else 'FAIL'}")

        await browser.close()
    print("\n==================================================")
    print("ALL LIVE PRODUCTION VERIFICATIONS COMPLETED")
    print("==================================================")

if __name__ == "__main__":
    asyncio.run(verify())
