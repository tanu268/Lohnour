import asyncio
from playwright.async_api import async_playwright

URL = "https://pro.lohnour.com/coaching/agentic-ai-foundations/?v=phase5a_screen_banner"
ARTIFACT_DIR = r"C:\Users\knamd\.gemini\antigravity-ide\brain\2550e99a-70f2-4de5-817a-9147af38f451"

SHOTS = [
    (1400, 900, "desktop_1400"),
    (768, 1024, "tablet_768"),
    (390, 844, "mobile_390"),
]

async def take_banner_shots():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page()

        for w, h, label in SHOTS:
            await page.set_viewport_size({"width": w, "height": h})
            await page.goto(URL, wait_until="networkidle")

            # Dark Mode
            await page.evaluate('''() => {
                document.documentElement.classList.remove('light-theme');
                document.documentElement.classList.add('dark-theme');
            }''')
            await page.wait_for_timeout(300)
            banner = await page.query_selector('.program-hero-banner')
            if banner:
                await banner.scroll_into_view_if_needed()
                await banner.screenshot(path=f"{ARTIFACT_DIR}/full_banner_dark_{label}.png")

            # Light Mode
            await page.evaluate('''() => {
                document.documentElement.classList.remove('dark-theme');
                document.documentElement.classList.add('light-theme');
            }''')
            await page.wait_for_timeout(300)
            banner = await page.query_selector('.program-hero-banner')
            if banner:
                await banner.scroll_into_view_if_needed()
                await banner.screenshot(path=f"{ARTIFACT_DIR}/full_banner_light_{label}.png")

            print(f"Captured full banner shots for {label}")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(take_banner_shots())
