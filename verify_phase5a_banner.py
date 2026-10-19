import asyncio
import urllib.request
import json
from playwright.async_api import async_playwright

URL = "https://pro.lohnour.com/coaching/agentic-ai-foundations/?v=phase5a_val"

VIEWPORT_MATRIX = [
    (1920, 1080, "desktop_1920x1080"),
    (1600, 900, "desktop_1600x900"),
    (1440, 900, "desktop_1440x900"),
    (1366, 768, "desktop_1366x768"),
    (1280, 800, "laptop_1280x800"),
    (1024, 768, "tablet_landscape_1024x768"),
    (1024, 1366, "tablet_portrait_1024x1366"),
    (834, 1194, "tablet_portrait_834x1194"),
    (820, 1180, "tablet_portrait_820x1180"),
    (768, 1024, "tablet_portrait_768x1024"),
    (430, 932, "mobile_430x932"),
    (414, 896, "mobile_414x896"),
    (390, 844, "mobile_390x844"),
    (375, 812, "mobile_375x812"),
    (360, 800, "mobile_360x800"),
]

INTERMEDIATE_WIDTHS = [1200, 1100, 900, 700, 600, 480]

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

async def run_tests():
    print("--- 1. Testing Core Routes HTTP Status ---")
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
        context = await browser.new_context()
        page = await context.new_page()

        print("\n--- 2. Testing Theme Switch & Image Visibility ---")
        await page.goto(URL, wait_until="networkidle")

        # Initial state check
        async def check_images_state(theme_name):
            return await page.evaluate('''() => {
                const darkImg = document.querySelector('.program-hero-banner .banner-theme-dark');
                const lightImg = document.querySelector('.program-hero-banner .banner-theme-light');
                const banner = document.querySelector('.program-hero-banner');
                
                const isDarkVis = darkImg && getComputedStyle(darkImg).display !== 'none' && darkImg.offsetWidth > 0;
                const isLightVis = lightImg && getComputedStyle(lightImg).display !== 'none' && lightImg.offsetWidth > 0;
                
                return {
                    htmlClasses: document.documentElement.className,
                    darkSrc: darkImg ? darkImg.currentSrc || darkImg.src : null,
                    darkDisplay: darkImg ? getComputedStyle(darkImg).display : null,
                    darkVisible: isDarkVis,
                    lightSrc: lightImg ? lightImg.currentSrc || lightImg.src : null,
                    lightDisplay: lightImg ? getComputedStyle(lightImg).display : null,
                    lightVisible: isLightVis,
                    bannerRect: banner ? {
                        w: banner.offsetWidth,
                        h: banner.offsetHeight,
                        aspectRatio: (banner.offsetWidth / banner.offsetHeight).toFixed(2)
                    } : null
                };
            }''')

        # Check default (likely dark unless localStorage or user preference)
        state_init = await check_images_state("initial")
        print(f"Initial State: HTML class: '{state_init['htmlClasses']}'")
        print(f"  Dark image visible: {state_init['darkVisible']} (display: {state_init['darkDisplay']})")
        print(f"  Light image visible: {state_init['lightVisible']} (display: {state_init['lightDisplay']})")
        print(f"  Banner rect: {state_init['bannerRect']}")

        # Ensure Dark Theme explicitly
        await page.evaluate('''() => {
            document.documentElement.classList.remove('light-theme');
            document.documentElement.classList.add('dark-theme');
            localStorage.setItem('theme', 'dark');
        }''')
        state_dark = await check_images_state("dark")
        print("\nDark Mode State:")
        print(f"  Dark visible: {state_dark['darkVisible']}, Light visible: {state_dark['lightVisible']}")
        print(f"  Dark src: {state_dark['darkSrc']}")

        # Ensure Light Theme explicitly
        await page.evaluate('''() => {
            document.documentElement.classList.remove('dark-theme');
            document.documentElement.classList.add('light-theme');
            localStorage.setItem('theme', 'light');
        }''')
        state_light = await check_images_state("light")
        print("\nLight Mode State:")
        print(f"  Dark visible: {state_light['darkVisible']}, Light visible: {state_light['lightVisible']}")
        print(f"  Light src: {state_light['lightSrc']}")

        # Test Theme Toggle Button
        toggle_btn = await page.query_selector('.theme-toggle, [data-theme-toggle], #theme-toggle')
        if toggle_btn:
            print("\nFound Theme Toggle Button, clicking to test user interaction toggle...")
            # Toggle from light to dark
            await toggle_btn.click()
            await page.wait_for_timeout(300)
            state_toggled_1 = await check_images_state("toggled_1")
            print(f"After Click 1: HTML class '{state_toggled_1['htmlClasses']}' -> Dark Vis: {state_toggled_1['darkVisible']}, Light Vis: {state_toggled_1['lightVisible']}")
            # Toggle back to light
            await toggle_btn.click()
            await page.wait_for_timeout(300)
            state_toggled_2 = await check_images_state("toggled_2")
            print(f"After Click 2: HTML class '{state_toggled_2['htmlClasses']}' -> Dark Vis: {state_toggled_2['darkVisible']}, Light Vis: {state_toggled_2['lightVisible']}")
        else:
            print("Theme toggle button selector not directly found, tested via classList toggle.")

        print("\n--- 3. Testing Viewport Matrix & Overflow ---")
        # Test across matrix in both Dark and Light modes
        for width, height, label in VIEWPORT_MATRIX:
            await page.set_viewport_size({"width": width, "height": height})
            await page.wait_for_timeout(200)

            # Test Dark
            await page.evaluate("() => { document.documentElement.classList.remove('light-theme'); document.documentElement.classList.add('dark-theme'); }")
            dark_check = await page.evaluate('''() => {
                const sW = document.documentElement.scrollWidth;
                const cW = document.documentElement.clientWidth;
                const banner = document.querySelector('.program-hero-banner');
                const darkImg = document.querySelector('.program-hero-banner .banner-theme-dark');
                return {
                    scrollWidth: sW,
                    clientWidth: cW,
                    overflow: sW > cW,
                    imgW: darkImg ? darkImg.offsetWidth : 0,
                    imgH: darkImg ? darkImg.offsetHeight : 0,
                    aspectRatio: darkImg && darkImg.offsetHeight ? (darkImg.offsetWidth / darkImg.offsetHeight).toFixed(2) : 0,
                    visible: darkImg && getComputedStyle(darkImg).display !== 'none'
                };
            }''')

            # Test Light
            await page.evaluate("() => { document.documentElement.classList.remove('dark-theme'); document.documentElement.classList.add('light-theme'); }")
            light_check = await page.evaluate('''() => {
                const sW = document.documentElement.scrollWidth;
                const cW = document.documentElement.clientWidth;
                const lightImg = document.querySelector('.program-hero-banner .banner-theme-light');
                return {
                    scrollWidth: sW,
                    clientWidth: cW,
                    overflow: sW > cW,
                    imgW: lightImg ? lightImg.offsetWidth : 0,
                    imgH: lightImg ? lightImg.offsetHeight : 0,
                    aspectRatio: lightImg && lightImg.offsetHeight ? (lightImg.offsetWidth / lightImg.offsetHeight).toFixed(2) : 0,
                    visible: lightImg && getComputedStyle(lightImg).display !== 'none'
                };
            }''')

            overflow_ok = (not dark_check['overflow']) and (not light_check['overflow'])
            print(f"[{label:28}] {width}x{height} | Dark: Vis={dark_check['visible']}, Size={dark_check['imgW']}x{dark_check['imgH']} (AR {dark_check['aspectRatio']}) | Light: Vis={light_check['visible']}, Size={light_check['imgW']}x{light_check['imgH']} (AR {light_check['aspectRatio']}) | Overflow: {'NO OVERFLOW (PASS)' if overflow_ok else 'OVERFLOW FAIL'}")

        print("\n--- 4. Testing Intermediate Widths ---")
        for width in INTERMEDIATE_WIDTHS:
            await page.set_viewport_size({"width": width, "height": 800})
            await page.wait_for_timeout(150)
            res = await page.evaluate('''() => {
                const sW = document.documentElement.scrollWidth;
                const cW = document.documentElement.clientWidth;
                const banner = document.querySelector('.program-hero-banner');
                return {
                    scrollWidth: sW,
                    clientWidth: cW,
                    overflow: sW > cW,
                    bannerW: banner ? banner.offsetWidth : 0,
                    bannerH: banner ? banner.offsetHeight : 0
                };
            }''')
            print(f"Intermediate {width}px: scrollWidth={res['scrollWidth']}, clientWidth={res['clientWidth']}, Banner={res['bannerW']}x{res['bannerH']} | Overflow={res['overflow']}")

        print("\n--- 5. Dynamic Resize Sweep (1440 -> 360) ---")
        sweep_widths = [1440, 1200, 1024, 900, 768, 600, 480, 390, 360]
        sweep_failed = False
        for w in sweep_widths:
            await page.set_viewport_size({"width": w, "height": 800})
            await page.wait_for_timeout(100)
            res = await page.evaluate('''() => {
                const sW = document.documentElement.scrollWidth;
                const cW = document.documentElement.clientWidth;
                return sW > cW;
            }''')
            if res:
                print(f"  Resize sweep FAILED at {w}px (horizontal overflow detected)")
                sweep_failed = True
        if not sweep_failed:
            print("  Resize sweep PASSED continuously across 1440 -> 360 without overflow or layout breaks.")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(run_tests())
