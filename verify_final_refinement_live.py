import asyncio
import os
import sys
import urllib.request
from playwright.async_api import async_playwright

sys.stdout.reconfigure(encoding='utf-8')

ROUTES = [
    "https://pro.lohnour.com/",
    "https://pro.lohnour.com/services/",
    "https://pro.lohnour.com/coaching/",
    "https://pro.lohnour.com/coaching/agentic-ai-foundations/",
    "https://pro.lohnour.com/about/",
    "https://pro.lohnour.com/contact/",
    "https://pro.lohnour.com/blog/",
    "https://pro.lohnour.com/guides/",
]

VIEWPORTS = [
    {"name": "desktop_1440", "width": 1440, "height": 900},
    {"name": "desktop_1280", "width": 1280, "height": 800},
    {"name": "tablet_1024", "width": 1024, "height": 768},
    {"name": "tablet_834", "width": 834, "height": 1194},
    {"name": "tablet_768", "width": 768, "height": 1024},
    {"name": "mobile_430", "width": 430, "height": 932},
    {"name": "mobile_414", "width": 414, "height": 896},
    {"name": "mobile_390", "width": 390, "height": 844},
    {"name": "mobile_375", "width": 375, "height": 667},
    {"name": "mobile_360", "width": 360, "height": 800},
]

OUTPUT_DIR = r"D:\lohnourpro\verification_screenshots"
os.makedirs(OUTPUT_DIR, exist_ok=True)

def verify_routes():
    print("=== 1. VERIFYING HTTP 200 STATUS ON ALL 8 ROUTES ===", flush=True)
    all_ok = True
    for route in ROUTES:
        try:
            req = urllib.request.Request(route, headers={"User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64)"})
            with urllib.request.urlopen(req, timeout=15) as resp:
                code = resp.getcode()
                print(f"  {code} OK : {route}", flush=True)
                if code != 200:
                    all_ok = False
        except Exception as e:
            print(f"  FAIL : {route} -> {e}", flush=True)
            all_ok = False
    return all_ok

async def verify_browser():
    print("\n=== 2. VERIFYING LIVE RESPONSIVE, THEMES & INTERACTIVITY ===", flush=True)
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        results = []

        for vp in VIEWPORTS:
            context = await browser.new_context(
                viewport={"width": vp["width"], "height": vp["height"]},
                user_agent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"
            )
            page = await context.new_page()
            url = "https://pro.lohnour.com/coaching/agentic-ai-foundations/"
            await page.goto(url, wait_until="domcontentloaded", timeout=20000)
            await asyncio.sleep(0.5)

            # Test in Dark Mode (removing light-theme class)
            await page.evaluate("() => { document.documentElement.classList.remove('light-theme'); localStorage.setItem('theme', 'dark'); }")
            await asyncio.sleep(0.3)

            overflow_dark = await page.evaluate("() => document.documentElement.scrollWidth - window.innerWidth")
            h1_size = await page.evaluate("() => window.getComputedStyle(document.querySelector('.program-hero-title')).fontSize")
            h2_size = await page.evaluate("() => window.getComputedStyle(document.querySelector('.section-title')).fontSize")
            h3_size = await page.evaluate("() => window.getComputedStyle(document.querySelector('.module-summary-card h3')).fontSize")
            cta_size = await page.evaluate("() => window.getComputedStyle(document.querySelector('.cta-title')).fontSize")

            # Check theme banner in dark mode
            dark_img_visible = await page.evaluate("() => { const img = document.querySelector('.banner-theme-dark'); return img ? window.getComputedStyle(img).display !== 'none' : false; }")
            light_img_visible = await page.evaluate("() => { const img = document.querySelector('.banner-theme-light'); return img ? window.getComputedStyle(img).display !== 'none' : false; }")

            # Check Accordion Interactivity
            mod1_header = page.locator("#curriculum-mod-1-header")
            mod1_expanded_initial = await mod1_header.get_attribute("aria-expanded")
            await mod1_header.click()
            await asyncio.sleep(0.3)
            mod1_expanded_after_click = await mod1_header.get_attribute("aria-expanded")
            await mod1_header.click()
            await asyncio.sleep(0.3)
            mod1_expanded_after_reclick = await mod1_header.get_attribute("aria-expanded")

            accordion_works = (mod1_expanded_initial == "false" and mod1_expanded_after_click == "true" and mod1_expanded_after_reclick == "false")

            # Save Dark Screenshot for hero
            hero_shot_path = os.path.join(OUTPUT_DIR, f"hero_{vp['name']}_dark.png")
            hero_elem = page.locator(".program-hero")
            if await hero_elem.count() > 0:
                await hero_elem.screenshot(path=hero_shot_path)

            # Test in Light Mode (adding light-theme class)
            await page.evaluate("() => { document.documentElement.classList.add('light-theme'); localStorage.setItem('theme', 'light'); }")
            await asyncio.sleep(0.3)

            overflow_light = await page.evaluate("() => document.documentElement.scrollWidth - window.innerWidth")
            dark_img_visible_light = await page.evaluate("() => { const img = document.querySelector('.banner-theme-dark'); return img ? window.getComputedStyle(img).display !== 'none' : false; }")
            light_img_visible_light = await page.evaluate("() => { const img = document.querySelector('.banner-theme-light'); return img ? window.getComputedStyle(img).display !== 'none' : false; }")

            hero_shot_path_light = os.path.join(OUTPUT_DIR, f"hero_{vp['name']}_light.png")
            if await hero_elem.count() > 0:
                await hero_elem.screenshot(path=hero_shot_path_light)

            dark_correct = (dark_img_visible and not light_img_visible)
            light_correct = (light_img_visible_light and not dark_img_visible_light)

            res = {
                "name": vp["name"],
                "width": vp["width"],
                "height": vp["height"],
                "overflow_dark": overflow_dark,
                "overflow_light": overflow_light,
                "h1_size": h1_size,
                "h2_size": h2_size,
                "h3_size": h3_size,
                "cta_size": cta_size,
                "dark_theme_correct": dark_correct,
                "light_theme_correct": light_correct,
                "accordion_works": accordion_works,
            }
            results.append(res)
            print(f"Viewport {vp['name']} ({vp['width']}x{vp['height']}): Overflow=[Dark: {overflow_dark}px, Light: {overflow_light}px] | H1={h1_size} > H2={h2_size} (CTA={cta_size}) > H3={h3_size} | Accordion={accordion_works} | Themes=[Dark={dark_correct}, Light={light_correct}]", flush=True)

            await context.close()

        await browser.close()
        return results

async def main():
    routes_ok = verify_routes()
    vp_results = await verify_browser()

    print("\n=== SUMMARY TABLE ===", flush=True)
    print(f"{'Viewport':<16} | {'Width':<5} | {'Overflow Dark':<13} | {'Overflow Light':<14} | {'H1 Size':<8} | {'H2 Size':<8} | {'H3 Size':<8} | {'Accordion':<9} | {'Themes':<6} | {'Result':<6}", flush=True)
    print("-" * 115, flush=True)
    all_vp_ok = True
    for r in vp_results:
        overflow_ok = (r['overflow_dark'] <= 0 and r['overflow_light'] <= 0)
        h_ok = float(r['h1_size'].replace('px','')) > float(r['h2_size'].replace('px','')) >= float(r['h3_size'].replace('px',''))
        themes_ok = r['dark_theme_correct'] and r['light_theme_correct']
        pass_str = "PASS" if (overflow_ok and h_ok and themes_ok and r['accordion_works']) else "FAIL"
        if pass_str == "FAIL":
            all_vp_ok = False
        print(f"{r['name']:<16} | {r['width']:<5} | {r['overflow_dark']:<13} | {r['overflow_light']:<14} | {r['h1_size']:<8} | {r['h2_size']:<8} | {r['h3_size']:<8} | {str(r['accordion_works']):<9} | {str(themes_ok):<6} | {pass_str:<6}", flush=True)

    if routes_ok and all_vp_ok:
        print("\nALL VERIFICATIONS PASSED SUCCESSFULLY WITH ZERO REGRESSIONS!", flush=True)
    else:
        print("\nSOME VERIFICATIONS FAILED!", flush=True)

if __name__ == "__main__":
    asyncio.run(main())
