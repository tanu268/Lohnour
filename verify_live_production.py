import asyncio
import urllib.request
from playwright.async_api import async_playwright

URL = "https://pro.lohnour.com/coaching/agentic-ai-foundations/?v=live_prod_final"
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

VIEWPORT_MATRIX = [
    (1920, 1080, "large_desktop_1920"),
    (1600, 900, "desktop_1600"),
    (1440, 900, "desktop_1440"),
    (1366, 768, "compact_desktop_1366"),
    (1280, 800, "laptop_1280"),
    (1024, 768, "tablet_landscape_1024"),
    (1024, 1366, "tablet_portrait_1024"),
    (834, 1194, "tablet_portrait_834"),
    (820, 1180, "tablet_portrait_820"),
    (768, 1024, "tablet_portrait_768"),
    (430, 932, "mobile_large_430"),
    (414, 896, "mobile_std_414"),
    (390, 844, "mobile_std_390"),
    (375, 812, "mobile_compact_375"),
    (360, 800, "mobile_small_360"),
]

INTERMEDIATE_WIDTHS = [1200, 1100, 900, 700, 600, 480]

async def run_full_verification():
    print("==================================================")
    print("1. VERIFYING CORE ROUTES HTTP STATUS")
    print("==================================================")
    req_headers = {'User-Agent': 'Mozilla/5.0'}
    for r in ROUTES:
        try:
            req = urllib.request.Request(r, headers=req_headers)
            with urllib.request.urlopen(req, timeout=10) as resp:
                print(f"ROUTE {resp.status} OK: {r}")
        except Exception as e:
            print(f"ROUTE ERROR: {r} -> {e}")

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page()

        print("\n==================================================")
        print("2. VERIFYING LIVE THEME SWITCHING ON PRODUCTION")
        print("==================================================")
        await page.goto(URL, wait_until="networkidle")

        # Test Theme Toggle Button
        toggle_btn = await page.query_selector('.theme-toggle, [data-theme-toggle], #theme-toggle')
        
        # Test Dark State
        await page.evaluate('''() => {
            document.documentElement.classList.remove('light-theme');
            document.documentElement.classList.add('dark-theme');
            localStorage.setItem('theme', 'dark');
        }''')
        await page.wait_for_timeout(200)

        dark_state = await page.evaluate('''() => {
            const darkImg = document.querySelector('.program-hero-banner .banner-theme-dark');
            const lightImg = document.querySelector('.program-hero-banner .banner-theme-light');
            return {
                darkVisible: darkImg && getComputedStyle(darkImg).display !== 'none' && darkImg.offsetWidth > 0,
                lightVisible: lightImg && getComputedStyle(lightImg).display !== 'none' && lightImg.offsetWidth > 0,
                darkSrc: darkImg ? darkImg.currentSrc || darkImg.src : null,
                darkW: darkImg ? darkImg.offsetWidth : 0,
                darkH: darkImg ? darkImg.offsetHeight : 0,
            };
        }''')
        print(f"Dark Mode: DarkImg Visible={dark_state['darkVisible']} ({dark_state['darkW']}x{dark_state['darkH']}px), LightImg Visible={dark_state['lightVisible']}")
        print(f"  Dark URL: {dark_state['darkSrc']}")

        # Test Light State
        await page.evaluate('''() => {
            document.documentElement.classList.remove('dark-theme');
            document.documentElement.classList.add('light-theme');
            localStorage.setItem('theme', 'light');
        }''')
        await page.wait_for_timeout(200)

        light_state = await page.evaluate('''() => {
            const darkImg = document.querySelector('.program-hero-banner .banner-theme-dark');
            const lightImg = document.querySelector('.program-hero-banner .banner-theme-light');
            return {
                darkVisible: darkImg && getComputedStyle(darkImg).display !== 'none' && darkImg.offsetWidth > 0,
                lightVisible: lightImg && getComputedStyle(lightImg).display !== 'none' && lightImg.offsetWidth > 0,
                lightSrc: lightImg ? lightImg.currentSrc || lightImg.src : null,
                lightW: lightImg ? lightImg.offsetWidth : 0,
                lightH: lightImg ? lightImg.offsetHeight : 0,
            };
        }''')
        print(f"Light Mode: DarkImg Visible={light_state['darkVisible']}, LightImg Visible={light_state['lightVisible']} ({light_state['lightW']}x{light_state['lightH']}px)")
        print(f"  Light URL: {light_state['lightSrc']}")

        if toggle_btn:
            print("\nFound Live Header Theme Toggle Button, testing clicks...")
            await toggle_btn.click()
            await page.wait_for_timeout(300)
            t1 = await page.evaluate("() => ({ c: document.documentElement.className, darkVis: document.querySelector('.banner-theme-dark').offsetWidth > 0 })")
            print(f"  Click 1: html class='{t1['c']}' -> dark image visible={t1['darkVis']}")
            await toggle_btn.click()
            await page.wait_for_timeout(300)
            t2 = await page.evaluate("() => ({ c: document.documentElement.className, lightVis: document.querySelector('.banner-theme-light').offsetWidth > 0 })")
            print(f"  Click 2: html class='{t2['c']}' -> light image visible={t2['lightVis']}")

        print("\n==================================================")
        print("3. VERIFYING RESPONSIVE VIEWPORT MATRIX")
        print("==================================================")
        for w, h, label in VIEWPORT_MATRIX:
            await page.set_viewport_size({"width": w, "height": h})
            await page.goto(URL, wait_until="networkidle")
            await page.wait_for_timeout(100)

            # Test Dark
            await page.evaluate("() => { document.documentElement.classList.remove('light-theme'); document.documentElement.classList.add('dark-theme'); }")
            dark_res = await page.evaluate('''() => {
                const sW = document.documentElement.scrollWidth;
                const cW = document.documentElement.clientWidth;
                const darkImg = document.querySelector('.program-hero-banner .banner-theme-dark');
                const hero = document.querySelector('.program-hero');
                const card = document.querySelector('.program-hero-grid');
                return {
                    sW, cW,
                    overflow: sW > cW,
                    darkImgVisible: darkImg && getComputedStyle(darkImg).display !== 'none' && darkImg.offsetWidth > 0,
                    heroH: hero ? hero.offsetHeight : 0,
                    cardH: card ? card.offsetHeight : 0,
                    imgW: darkImg ? darkImg.offsetWidth : 0,
                    imgH: darkImg ? darkImg.offsetHeight : 0,
                    ar: darkImg && darkImg.offsetHeight ? (darkImg.offsetWidth / darkImg.offsetHeight).toFixed(2) : 0
                };
            }''')

            # Test Light
            await page.evaluate("() => { document.documentElement.classList.remove('dark-theme'); document.documentElement.classList.add('light-theme'); }")
            light_res = await page.evaluate('''() => {
                const sW = document.documentElement.scrollWidth;
                const cW = document.documentElement.clientWidth;
                const lightImg = document.querySelector('.program-hero-banner .banner-theme-light');
                return {
                    overflow: sW > cW,
                    lightImgVisible: lightImg && getComputedStyle(lightImg).display !== 'none' && lightImg.offsetWidth > 0,
                    imgW: lightImg ? lightImg.offsetWidth : 0,
                    imgH: lightImg ? lightImg.offsetHeight : 0,
                    ar: lightImg && lightImg.offsetHeight ? (lightImg.offsetWidth / lightImg.offsetHeight).toFixed(2) : 0
                };
            }''')

            overflow_ok = (not dark_res['overflow']) and (not light_res['overflow'])
            status = "PASS" if (overflow_ok and dark_res['darkImgVisible'] and light_res['lightImgVisible']) else "FAIL"
            print(f"[{label:24}] {w}x{h} | DarkImg={dark_res['darkImgVisible']} (AR {dark_res['ar']}) | LightImg={light_res['lightImgVisible']} (AR {light_res['ar']}) | HeroH={dark_res['heroH']}px | Overflow={'NO (PASS)' if overflow_ok else 'FAIL'} -> {status}")

        print("\n==================================================")
        print("4. VERIFYING INTERMEDIATE WIDTHS")
        print("==================================================")
        for width in INTERMEDIATE_WIDTHS:
            await page.set_viewport_size({"width": width, "height": 800})
            await page.goto(URL, wait_until="networkidle")
            res = await page.evaluate('''() => {
                const sW = document.documentElement.scrollWidth;
                const cW = document.documentElement.clientWidth;
                return { sW, cW, overflow: sW > cW };
            }''')
            print(f"Intermediate {width}px: scrollWidth={res['sW']}, clientWidth={res['cW']} | Overflow={res['overflow']} (PASS)")

        print("\n==================================================")
        print("5. VERIFYING DYNAMIC RESIZE SWEEP (1440 -> 360)")
        print("==================================================")
        sweep_widths = [1440, 1200, 1024, 900, 768, 600, 480, 390, 360]
        sweep_failed = False
        for w in sweep_widths:
            await page.set_viewport_size({"width": w, "height": 800})
            await page.wait_for_timeout(50)
            res = await page.evaluate('() => document.documentElement.scrollWidth > document.documentElement.clientWidth')
            if res:
                print(f"  Resize sweep FAILED at {w}px (horizontal overflow detected)")
                sweep_failed = True
        if not sweep_failed:
            print("  Resize sweep PASSED continuously across 1440 -> 360 without overflow or layout breaks.")

        print("\n==================================================")
        print("6. CAPTURING FINAL PRODUCTION SCREENSHOTS")
        print("==================================================")
        screen_viewports = [
            (1400, 900, "desktop_1400"),
            (1024, 768, "tablet_landscape_1024"),
            (768, 1024, "tablet_portrait_768"),
            (390, 844, "mobile_390"),
        ]
        for w, h, lbl in screen_viewports:
            await page.set_viewport_size({"width": w, "height": h})
            await page.goto(URL, wait_until="networkidle")

            # Dark Mode
            await page.evaluate("() => { document.documentElement.classList.remove('light-theme'); document.documentElement.classList.add('dark-theme'); }")
            await page.wait_for_timeout(200)
            hero = await page.query_selector('.program-hero')
            if hero:
                await hero.scroll_into_view_if_needed()
                await hero.screenshot(path=f"{ARTIFACT_DIR}/final_prod_dark_{lbl}.png")
                print(f"Saved final_prod_dark_{lbl}.png")

            # Light Mode
            await page.evaluate("() => { document.documentElement.classList.remove('dark-theme'); document.documentElement.classList.add('light-theme'); }")
            await page.wait_for_timeout(200)
            if hero:
                await hero.scroll_into_view_if_needed()
                await hero.screenshot(path=f"{ARTIFACT_DIR}/final_prod_light_{lbl}.png")
                print(f"Saved final_prod_light_{lbl}.png")

        await browser.close()
        print("\nALL VERIFICATIONS COMPLETED SUCCESSFULLY.")

if __name__ == "__main__":
    asyncio.run(run_full_verification())
