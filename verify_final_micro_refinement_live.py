import asyncio
import os
import sys

sys.stdout.reconfigure(encoding='utf-8')
from playwright.async_api import async_playwright

VIEWPORTS = [
    {"name": "1440x900 (Desktop)", "width": 1440, "height": 900},
    {"name": "1280x800 (Desktop)", "width": 1280, "height": 800},
    {"name": "1024x768 (Desktop/Landscape)", "width": 1024, "height": 768},
    {"name": "834x1194 (Tablet Portrait)", "width": 834, "height": 1194},
    {"name": "768x1024 (Tablet Portrait)", "width": 768, "height": 1024},
    {"name": "430x932 (Mobile Pro Max)", "width": 430, "height": 932},
    {"name": "414x896 (Mobile Plus)", "width": 414, "height": 896},
    {"name": "390x844 (Mobile)", "width": 390, "height": 844},
    {"name": "375x667 (Mobile SE)", "width": 375, "height": 667},
    {"name": "360x800 (Android Small)", "width": 360, "height": 800},
]

TARGET_URL = "https://pro.lohnour.com/coaching/agentic-ai-foundations/"
SCREENSHOT_DIR = r"C:\Users\knamd\.gemini\antigravity-ide\brain\2550e99a-70f2-4de5-817a-9147af38f451\final_qa_screenshots"

os.makedirs(SCREENSHOT_DIR, exist_ok=True)

async def test_page():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(ignore_https_errors=True)
        page = await context.new_page()

        console_errors = []
        page.on("console", lambda msg: console_errors.append(msg.text) if msg.type == "error" else None)
        page.on("pageerror", lambda err: console_errors.append(str(err)))

        print("==================================================", flush=True)
        print("1. RUNNING 10 VIEWPORT QA IN DARK AND LIGHT THEME", flush=True)
        print("==================================================", flush=True)

        for vp in VIEWPORTS:
            await page.set_viewport_size({"width": vp["width"], "height": vp["height"]})
            await page.goto(TARGET_URL, wait_until="networkidle")

            # Check overflow
            overflow = await page.evaluate("""() => {
                const doc = document.documentElement;
                return {
                    scrollWidth: doc.scrollWidth,
                    clientWidth: doc.clientWidth,
                    hasOverflow: doc.scrollWidth > doc.clientWidth
                };
            }""")

            # Get Hero & Button computed metrics
            metrics = await page.evaluate("""() => {
                const grid = document.querySelector('.program-hero-grid');
                const actions = document.querySelector('.program-hero-actions');
                const btnPrimary = document.querySelector('.hero-btn-primary');
                const btnSecondary = document.querySelector('.hero-btn-secondary');
                const banner = document.querySelector('.program-hero-banner');
                const darkImg = document.querySelector('.banner-theme-dark');
                const lightImg = document.querySelector('.banner-theme-light');

                const gridStyle = grid ? window.getComputedStyle(grid) : {};
                const actionsStyle = actions ? window.getComputedStyle(actions) : {};
                const btnPrimaryRect = btnPrimary ? btnPrimary.getBoundingClientRect() : {};
                const btnSecondaryRect = btnSecondary ? btnSecondary.getBoundingClientRect() : {};
                const darkImgStyle = darkImg ? window.getComputedStyle(darkImg) : {};
                const lightImgStyle = lightImg ? window.getComputedStyle(lightImg) : {};

                return {
                    gridDisplay: gridStyle.display,
                    gridColumns: gridStyle.gridTemplateColumns,
                    actionsGap: actionsStyle.gap,
                    actionsFlexDir: actionsStyle.flexDirection,
                    btnPrimaryText: btnPrimary ? btnPrimary.innerText.trim() : null,
                    btnSecondaryText: btnSecondary ? btnSecondary.innerText.trim() : null,
                    btnPrimaryBox: { w: btnPrimaryRect.width, h: btnPrimaryRect.height, top: btnPrimaryRect.top, left: btnPrimaryRect.left },
                    btnSecondaryBox: { w: btnSecondaryRect.width, h: btnSecondaryRect.height, top: btnSecondaryRect.top, left: btnSecondaryRect.left },
                    darkImgDisplay: darkImgStyle.display,
                    darkImgOpacity: darkImgStyle.opacity,
                    lightImgDisplay: lightImgStyle.display,
                    lightImgOpacity: lightImgStyle.opacity
                };
            }""")

            # Take Screenshot (Dark theme)
            clean_name = vp['name'].split()[0]
            dark_shot = os.path.join(SCREENSHOT_DIR, f"hero_{clean_name}_dark.png")
            await page.locator('.program-hero').screenshot(path=dark_shot)

            # Switch to Light Theme
            await page.evaluate("""() => {
                document.documentElement.classList.remove('dark-theme');
                document.documentElement.classList.add('light-theme');
                document.documentElement.setAttribute('data-theme', 'light');
            }""")
            await asyncio.sleep(0.3)

            light_metrics = await page.evaluate("""() => {
                const darkImg = document.querySelector('.banner-theme-dark');
                const lightImg = document.querySelector('.banner-theme-light');
                const darkImgStyle = darkImg ? window.getComputedStyle(darkImg) : {};
                const lightImgStyle = lightImg ? window.getComputedStyle(lightImg) : {};
                return {
                    darkImgDisplay: darkImgStyle.display,
                    lightImgDisplay: lightImgStyle.display,
                    lightImgOpacity: lightImgStyle.opacity
                };
            }""")

            light_shot = os.path.join(SCREENSHOT_DIR, f"hero_{clean_name}_light.png")
            await page.locator('.program-hero').screenshot(path=light_shot)

            # Reset back to Dark Theme
            await page.evaluate("""() => {
                document.documentElement.classList.remove('light-theme');
                document.documentElement.classList.add('dark-theme');
                document.documentElement.setAttribute('data-theme', 'dark');
            }""")

            print(f"Viewport: {vp['name']}")
            print(f"  Overflow: scrollWidth={overflow['scrollWidth']}, clientWidth={overflow['clientWidth']} -> PASS={not overflow['hasOverflow']}")
            print(f"  Hero Grid: display={metrics['gridDisplay']}, columns={metrics['gridColumns']}")
            print(f"  CTA Actions: flex-dir={metrics['actionsFlexDir']}, gap={metrics['actionsGap']}")
            print(f"  Dark Image: display={metrics['darkImgDisplay']}, opacity={metrics['darkImgOpacity']}")
            print(f"  Light Image (in light theme): display={light_metrics['lightImgDisplay']}, opacity={light_metrics['lightImgOpacity']}")
            print(f"  Primary Button: '{metrics['btnPrimaryText']}' ({metrics['btnPrimaryBox']['w']:.1f}x{metrics['btnPrimaryBox']['h']:.1f})")
            print(f"  Secondary Button: '{metrics['btnSecondaryText']}' ({metrics['btnSecondaryBox']['w']:.1f}x{metrics['btnSecondaryBox']['h']:.1f})")
            print("---", flush=True)

        print("\n==================================================", flush=True)
        print("2. TESTING FAQ 'VIEW ALL FAQS' LINK DESTINATION", flush=True)
        print("==================================================", flush=True)
        faq_link_info = await page.evaluate("""() => {
            const link = document.querySelector('.faq-preview-footer a');
            return {
                text: link ? link.innerText.trim() : null,
                href: link ? link.getAttribute('href') : null
            };
        }""")
        print(f"FAQ Link Text: {faq_link_info['text']}")
        print(f"FAQ Link Href: {faq_link_info['href']}")

        # Click the link or navigate to href and check response status
        resp = await page.goto(faq_link_info['href'])
        print(f"Navigating to FAQ Link -> HTTP Status: {resp.status}")

        print("\n==================================================", flush=True)
        print("3. REGRESSION ROUTE CHECK", flush=True)
        print("==================================================", flush=True)
        routes = [
            "/",
            "/services/",
            "/coaching/",
            "/coaching/agentic-ai-foundations/",
            "/about/",
            "/contact/",
            "/blog/",
            "/faqs/"
        ]
        for r in routes:
            url = f"https://pro.lohnour.com{r}"
            r_resp = await page.goto(url)
            print(f"Route {r:35} -> HTTP {r_resp.status}")

        print(f"\nConsole Errors Count: {len(console_errors)}")
        if console_errors:
            print("Errors detected:")
            for err in console_errors:
                print("  ", err)

        await browser.close()

asyncio.run(test_page())
