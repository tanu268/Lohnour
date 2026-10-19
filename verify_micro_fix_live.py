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
    {"name": "tablet_768", "width": 768, "height": 1024},
    {"name": "mobile_430", "width": 430, "height": 932},
    {"name": "mobile_414", "width": 414, "height": 896},
    {"name": "mobile_390", "width": 390, "height": 844},
    {"name": "mobile_375", "width": 375, "height": 667},
    {"name": "mobile_360", "width": 360, "height": 800},
]

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
    print("\n=== 2. VERIFYING LIVE MICRO-FIX PROPERTIES & RESPONSIVENESS ===", flush=True)
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        results = []

        # Detailed Check on Desktop 1440
        page = await browser.new_page(viewport={"width": 1440, "height": 900})
        url = "https://pro.lohnour.com/coaching/agentic-ai-foundations/"
        await page.goto(url, wait_until="domcontentloaded", timeout=20000)
        await asyncio.sleep(0.5)

        # CHANGE 1: Outcome text check
        outcome_font_style = await page.evaluate("() => window.getComputedStyle(document.querySelector('.north-star-quote p')).fontStyle")
        outcome_font_weight = await page.evaluate("() => window.getComputedStyle(document.querySelector('.north-star-quote p')).fontWeight")
        outcome_font_size = await page.evaluate("() => window.getComputedStyle(document.querySelector('.north-star-quote p')).fontSize")
        outcome_text_start = await page.evaluate("() => document.querySelector('.north-star-quote p').textContent.trim().slice(0, 45)")

        print(f"\n--- CHANGE #1: PROGRAM OUTCOME TEXT ---", flush=True)
        print(f"  Text begins: '{outcome_text_start}'", flush=True)
        print(f"  font-style: '{outcome_font_style}' (expected: 'normal')", flush=True)
        print(f"  font-weight: '{outcome_font_weight}' (expected: '500')", flush=True)
        print(f"  font-size: '{outcome_font_size}'", flush=True)
        change1_ok = (outcome_font_style == "normal")

        # CHANGE 2: Key Cohort Highlights hierarchy check
        card_title_size = await page.evaluate("() => window.getComputedStyle(document.querySelector('.overview-highlights h3')).fontSize")
        card_title_weight = await page.evaluate("() => window.getComputedStyle(document.querySelector('.overview-highlights h3')).fontWeight")
        
        bullet_strongs = await page.evaluate("""() => {
            const strongs = Array.from(document.querySelectorAll('.highlights-list strong'));
            return strongs.map(s => ({
                text: s.textContent.trim(),
                fontSize: window.getComputedStyle(s).fontSize,
                fontWeight: window.getComputedStyle(s).fontWeight,
                lineHeight: window.getComputedStyle(s).lineHeight
            }));
        }""")

        desc_size = await page.evaluate("() => window.getComputedStyle(document.querySelector('.highlights-list p')).fontSize")

        print(f"\n--- CHANGE #2: KEY COHORT HIGHLIGHTS HIERARCHY ---", flush=True)
        print(f"  Card Title ('Key Cohort Highlights'): size={card_title_size}, weight={card_title_weight}", flush=True)
        for bs in bullet_strongs:
            print(f"  Bullet Heading: '{bs['text']}' | size={bs['fontSize']} | weight={bs['fontWeight']} | lh={bs['lineHeight']}", flush=True)
        print(f"  Supporting Description (p): size={desc_size}", flush=True)

        card_title_px = float(card_title_size.replace("px", ""))
        bullet_px = float(bullet_strongs[0]["fontSize"].replace("px", ""))
        desc_px = float(desc_size.replace("px", ""))
        change2_ok = (card_title_px > bullet_px > desc_px)
        print(f"  Hierarchy Check: Card Title ({card_title_px}px) > Bullet Heading ({bullet_px}px) > Description ({desc_px}px) -> {change2_ok}", flush=True)

        await page.close()

        # Viewport check across all viewports
        print(f"\n--- RESPONSIVE & THEME CHECKS ACROSS VIEWPORTS ---", flush=True)
        for vp in VIEWPORTS:
            ctx = await browser.new_context(viewport={"width": vp["width"], "height": vp["height"]})
            p_vp = await ctx.new_page()
            await p_vp.goto(url, wait_until="domcontentloaded", timeout=20000)

            # Dark theme
            await p_vp.evaluate("() => { document.documentElement.classList.remove('light-theme'); }")
            await asyncio.sleep(0.2)
            overflow_dark = await p_vp.evaluate("() => document.documentElement.scrollWidth - window.innerWidth")

            # Light theme
            await p_vp.evaluate("() => { document.documentElement.classList.add('light-theme'); }")
            await asyncio.sleep(0.2)
            overflow_light = await p_vp.evaluate("() => document.documentElement.scrollWidth - window.innerWidth")

            # Check accordion functionality
            mod1_header = p_vp.locator("#curriculum-mod-1-header")
            exp_initial = await mod1_header.get_attribute("aria-expanded")
            await mod1_header.click()
            await asyncio.sleep(0.2)
            exp_open = await mod1_header.get_attribute("aria-expanded")
            await mod1_header.click()
            await asyncio.sleep(0.2)
            exp_closed = await mod1_header.get_attribute("aria-expanded")

            acc_ok = (exp_initial == "false" and exp_open == "true" and exp_closed == "false")

            res = {
                "name": vp["name"],
                "width": vp["width"],
                "height": vp["height"],
                "overflow_dark": overflow_dark,
                "overflow_light": overflow_light,
                "acc_ok": acc_ok
            }
            results.append(res)
            print(f"  {vp['name']:<14} ({vp['width']}x{vp['height']}): Overflow=[Dark: {overflow_dark}px, Light: {overflow_light}px] | Accordion={acc_ok}", flush=True)
            await ctx.close()

        await browser.close()
        return change1_ok and change2_ok and all(r["overflow_dark"] <= 0 and r["overflow_light"] <= 0 and r["acc_ok"] for r in results)

async def main():
    routes_ok = verify_routes()
    browser_ok = await verify_browser()

    if routes_ok and browser_ok:
        print("\nALL VERIFICATIONS PASSED PERFECTLY WITH ZERO REGRESSIONS!", flush=True)
    else:
        print("\nSOME VERIFICATIONS FAILED!", flush=True)

if __name__ == "__main__":
    asyncio.run(main())
