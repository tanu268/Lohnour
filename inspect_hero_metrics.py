import asyncio
from playwright.async_api import async_playwright

async def inspect():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page()
        await page.goto("https://pro.lohnour.com/coaching/agentic-ai-foundations/?v=inspect_hero", wait_until="networkidle")

        info = await page.evaluate('''() => {
            const hero = document.querySelector('.program-hero');
            const breadcrumb = document.querySelector('.program-hero-breadcrumb');
            const grid = document.querySelector('.program-hero-grid');
            const content = document.querySelector('.program-hero-content');
            const title = document.querySelector('.program-hero-title');
            const desc = document.querySelector('.program-hero-desc');
            const meta = document.querySelector('.program-hero-meta');
            const actions = document.querySelector('.program-hero-actions');
            const banner = document.querySelector('.program-hero-banner');

            const getBox = el => el ? {
                w: el.offsetWidth,
                h: el.offsetHeight,
                top: el.getBoundingClientRect().top,
                bottom: el.getBoundingClientRect().bottom,
                mt: getComputedStyle(el).marginTop,
                mb: getComputedStyle(el).marginBottom,
                pt: getComputedStyle(el).paddingTop,
                pb: getComputedStyle(el).paddingBottom,
            } : null;

            return {
                hero: getBox(hero),
                grid: {
                    gap: getComputedStyle(grid).gap,
                    display: getComputedStyle(grid).display,
                },
                title: getBox(title),
                desc: getBox(desc),
                meta: getBox(meta),
                actions: getBox(actions),
                banner: getBox(banner),
                contentHeight: content.offsetHeight,
                bannerTopMinusActionsBottom: banner.getBoundingClientRect().top - actions.getBoundingClientRect().bottom
            };
        }''')
        import pprint
        pprint.pprint(info)
        await browser.close()

if __name__ == "__main__":
    asyncio.run(inspect())
