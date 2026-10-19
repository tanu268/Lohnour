import asyncio
from playwright.async_api import async_playwright

URL = "https://pro.lohnour.com/coaching/agentic-ai-foundations/?v=test_var_seamless"
ARTIFACT_DIR = r"C:\Users\knamd\.gemini\antigravity-ide\brain\2550e99a-70f2-4de5-817a-9147af38f451"

CSS_SEAMLESS = """
.program-page .program-hero {
    padding: var(--spacing-sm, 16px) 0 var(--spacing-md, 32px) 0 !important;
}
.program-page .program-hero-breadcrumb {
    margin-bottom: var(--spacing-sm, 16px) !important;
}

/* Unified Card Container */
.program-page .program-hero-grid {
    display: flex !important;
    flex-direction: column !important;
    gap: 0 !important;
    border-radius: var(--radius-lg, 12px) !important;
    overflow: hidden !important;
    border: 1px solid var(--color-border-subtle) !important;
    background: var(--color-bg-surface) !important;
    box-shadow: var(--shadow-subtle, 0 4px 20px rgba(0, 0, 0, 0.04)) !important;
}

/* Upper Portion: Hero Content with compact, refined editorial spacing */
.program-page .program-hero-content {
    max-width: 100% !important;
    padding: 36px 44px 24px 44px !important;
    position: relative !important;
    z-index: 2 !important;
}

.program-page .program-hero-title {
    font-size: clamp(2rem, 4vw, 2.75rem) !important;
    line-height: 1.15 !important;
    margin: 0 0 12px 0 !important;
}

.program-page .program-hero-desc {
    font-size: clamp(1.05rem, 1.5vw, 1.2rem) !important;
    line-height: 1.55 !important;
    max-width: 760px !important;
    margin: 0 0 18px 0 !important;
}

.program-page .program-hero-meta {
    gap: 8px !important;
    margin-bottom: 22px !important;
}

.program-page .program-hero-actions {
    gap: 12px !important;
    margin-bottom: 0 !important;
}

/* Lower Portion: Banner Image continues seamlessly underneath */
.program-page .program-hero-banner {
    width: 100% !important;
    margin: 0 !important;
    border: none !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    background: transparent !important;
    overflow: hidden !important;
}

.program-page .program-hero-banner img {
    width: 100% !important;
    height: auto !important;
    aspect-ratio: 2 / 1 !important;
    display: block !important;
    object-fit: cover !important;
    border-radius: 0 !important;
}

@media (max-width: 768px) {
    .program-page .program-hero-content {
        padding: 24px 20px 20px 20px !important;
    }
    .program-page .program-hero-title {
        font-size: 1.85rem !important;
    }
    .program-page .program-hero-actions {
        flex-direction: column !important;
    }
    .program-page .hero-btn-primary,
    .program-page .hero-btn-secondary {
        width: 100% !important;
    }
}
"""

async def run():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page()

        for theme in ["dark", "light"]:
            for w, h, lbl in [(1400, 900, "1400")]:
                await page.set_viewport_size({"width": w, "height": h})
                await page.goto(URL, wait_until="networkidle")
                await page.add_style_tag(content=CSS_SEAMLESS)

                if theme == "dark":
                    await page.evaluate('''() => {
                        document.documentElement.classList.remove('light-theme');
                        document.documentElement.classList.add('dark-theme');
                    }''')
                else:
                    await page.evaluate('''() => {
                        document.documentElement.classList.remove('dark-theme');
                        document.documentElement.classList.add('light-theme');
                    }''')
                await page.wait_for_timeout(200)

                hero = await page.query_selector('.program-hero')
                if hero:
                    await hero.scroll_into_view_if_needed()
                    fname = f"{ARTIFACT_DIR}/seamless_{theme}_{lbl}.png"
                    await hero.screenshot(path=fname)
                    print(f"Captured {fname}")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(run())
