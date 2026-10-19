import asyncio
from playwright.async_api import async_playwright

URL = "https://pro.lohnour.com/coaching/agentic-ai-foundations/?v=test_1024"
ARTIFACT_DIR = r"C:\Users\knamd\.gemini\antigravity-ide\brain\2550e99a-70f2-4de5-817a-9147af38f451"

CSS_1024_TUNED = """
@media (min-width: 901px) {
    .program-page .program-hero-grid {
        position: relative !important;
        display: block !important;
        border-radius: var(--radius-lg, 12px) !important;
        overflow: hidden !important;
        border: 1px solid var(--color-border-subtle) !important;
        background: var(--color-bg-surface) !important;
        box-shadow: var(--shadow-subtle, 0 4px 20px rgba(0, 0, 0, 0.04)) !important;
        min-height: 500px !important;
    }

    .program-page .program-hero-banner {
        position: absolute !important;
        inset: 0 !important;
        width: 100% !important;
        height: 100% !important;
        margin: 0 !important;
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        background: transparent !important;
        z-index: 1 !important;
    }

    .program-page .program-hero-banner img {
        width: 100% !important;
        height: 100% !important;
        display: block !important;
        object-fit: cover !important;
        object-position: right center !important;
        border-radius: 0 !important;
    }

    .program-page .program-hero-grid::after {
        content: '' !important;
        position: absolute !important;
        inset: 0 !important;
        z-index: 2 !important;
        pointer-events: none !important;
        background: linear-gradient(
            to right,
            var(--color-bg-warm) 0%,
            color-mix(in srgb, var(--color-bg-warm) 90%, transparent) 30%,
            color-mix(in srgb, var(--color-bg-warm) 40%, transparent) 48%,
            transparent 68%
        ) !important;
    }

    .program-page .program-hero-content {
        position: relative !important;
        z-index: 3 !important;
        max-width: 520px !important;
        padding: 40px 36px !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        min-height: 500px !important;
    }

    .program-page .program-hero-title {
        font-family: var(--font-heading) !important;
        font-size: clamp(2rem, 3.2vw, 2.75rem) !important;
        font-weight: 700 !important;
        line-height: 1.15 !important;
        letter-spacing: -0.025em !important;
        color: var(--color-text) !important;
        margin: 0 0 12px 0 !important;
    }

    .program-page .program-hero-desc {
        font-size: clamp(0.95rem, 1.25vw, 1.1rem) !important;
        line-height: 1.5 !important;
        color: var(--color-text-secondary) !important;
        max-width: 420px !important;
        margin: 0 0 18px 0 !important;
    }

    .program-page .program-hero-meta {
        display: flex !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        gap: 7px !important;
        margin-bottom: 22px !important;
        max-width: 440px !important;
    }

    .program-page .meta-chip {
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        padding: 6px 11px !important;
        font-size: 0.775rem !important;
        color: var(--color-text) !important;
        background: var(--color-bg-surface) !important;
        border: 1px solid var(--color-border) !important;
        border-radius: var(--radius-md) !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
    }

    .program-page .program-hero-actions {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        flex-wrap: wrap !important;
        margin-bottom: 0 !important;
    }

    .program-page .hero-btn-primary,
    .program-page .hero-btn-secondary {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-decoration: none !important;
        padding: 11px 18px !important;
        font-size: 0.9rem !important;
    }
}

@media (max-width: 1050px) and (min-width: 901px) {
    .program-page .program-hero-grid {
        min-height: 480px !important;
    }
    .program-page .program-hero-content {
        max-width: 400px !important;
        padding: 32px 28px !important;
        min-height: 480px !important;
    }
    .program-page .program-hero-title {
        font-size: 2rem !important;
    }
    .program-page .program-hero-desc {
        font-size: 0.95rem !important;
        max-width: 360px !important;
    }
    .program-page .program-hero-meta {
        max-width: 360px !important;
        gap: 6px !important;
        margin-bottom: 18px !important;
    }
    .program-page .program-hero-actions {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 8px !important;
    }
    .program-page .hero-btn-primary,
    .program-page .hero-btn-secondary {
        width: 100% !important;
        max-width: 280px !important;
    }
}
"""

async def test():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page()

        for w, h, lbl in [(1440, 900, "1440"), (1024, 768, "1024")]:
            await page.set_viewport_size({"width": w, "height": h})
            await page.goto(URL, wait_until="networkidle")
            await page.add_style_tag(content=CSS_1024_TUNED)

            # Dark
            await page.evaluate("() => { document.documentElement.classList.remove('light-theme'); document.documentElement.classList.add('dark-theme'); }")
            await page.wait_for_timeout(150)
            hero = await page.query_selector('.program-hero')
            if hero:
                await hero.scroll_into_view_if_needed()
                await hero.screenshot(path=f"{ARTIFACT_DIR}/tuned_dark_{lbl}.png")

            # Light
            await page.evaluate("() => { document.documentElement.classList.remove('dark-theme'); document.documentElement.classList.add('light-theme'); }")
            await page.wait_for_timeout(150)
            if hero:
                await hero.scroll_into_view_if_needed()
                await hero.screenshot(path=f"{ARTIFACT_DIR}/tuned_light_{lbl}.png")

            print(f"Captured tuned shots for {lbl}")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(test())
