import asyncio
import os
from playwright.async_api import async_playwright

URL = "https://pro.lohnour.com/coaching/agentic-ai-foundations/?v=test_nav_removal"
ARTIFACT_DIR = r"C:\Users\knamd\.gemini\antigravity-ide\brain\2550e99a-70f2-4de5-817a-9147af38f451"

REFLOW_CSS = """
/* Reflow curriculum layout to single full-width column */
.program-page .curriculum-guide-layout {
    display: block !important;
    max-width: 100% !important;
    margin: 0 auto !important;
}

.program-page .curriculum-guide-content {
    display: flex !important;
    flex-direction: column !important;
    gap: 20px !important;
    width: 100% !important;
    max-width: 100% !important;
}

.program-page .curriculum-guide-module-block {
    width: 100% !important;
    box-sizing: border-box !important;
}

/* Remove sidebar and mobile toggle */
.program-page .curriculum-guide-sidebar,
.program-page .curriculum-mobile-outline-toggle-wrapper {
    display: none !important;
}
"""

VIEWPORTS = [
    (1440, 900, "1440_desktop"),
    (1280, 800, "1280_desktop"),
    (1024, 768, "1024_landscape"),
    (834, 1194, "834_tablet"),
    (768, 1024, "768_tablet"),
    (430, 932, "430_mobile"),
    (390, 844, "390_mobile"),
    (360, 800, "360_mobile"),
]

async def run_test():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page()

        for w, h, label in VIEWPORTS:
            await page.set_viewport_size({"width": w, "height": h})
            await page.goto(URL, wait_until="networkidle")

            # Remove left sidebar and mobile toggle from DOM
            await page.evaluate("""() => {
                const sidebar = document.getElementById('curriculum-guide-sidebar');
                if (sidebar) sidebar.remove();
                const mobileToggle = document.querySelector('.curriculum-mobile-outline-toggle-wrapper');
                if (mobileToggle) mobileToggle.remove();
            }""")

            # Add reflow CSS
            await page.add_style_tag(content=REFLOW_CSS)
            await page.wait_for_timeout(100)

            # Check overflow
            overflow = await page.evaluate("() => document.documentElement.scrollWidth > window.innerWidth")
            scroll_width = await page.evaluate("() => document.documentElement.scrollWidth")
            client_width = await page.evaluate("() => window.innerWidth")
            print(f"Viewport {w}x{h} ({label}): Overflow={'FAIL' if overflow else 'PASS'} (scroll={scroll_width}, client={client_width})")

            # Dark theme screenshot
            await page.evaluate("() => { document.documentElement.classList.remove('light-theme'); document.documentElement.classList.add('dark-theme'); }")
            await page.wait_for_timeout(100)

            # Scroll to top of curriculum
            curriculum_sec = await page.query_selector('#curriculum')
            if curriculum_sec:
                await curriculum_sec.scroll_into_view_if_needed()
                await page.wait_for_timeout(100)
                # Dark screenshot
                await curriculum_sec.screenshot(path=os.path.join(ARTIFACT_DIR, f"preview_curriculum_dark_{label}.png"))

            # Light theme screenshot
            await page.evaluate("() => { document.documentElement.classList.remove('dark-theme'); document.documentElement.classList.add('light-theme'); }")
            await page.wait_for_timeout(100)
            if curriculum_sec:
                await curriculum_sec.screenshot(path=os.path.join(ARTIFACT_DIR, f"preview_curriculum_light_{label}.png"))

        await browser.close()
    print("Preview testing finished successfully!")

if __name__ == "__main__":
    asyncio.run(run_test())
