import asyncio
from playwright.async_api import async_playwright

URL = "https://pro.lohnour.com/coaching/agentic-ai-foundations/?v=test_breakpoints"
ARTIFACT_DIR = r"C:\Users\knamd\.gemini\antigravity-ide\brain\2550e99a-70f2-4de5-817a-9147af38f451"

REFINED_CSS = """
/* Hero Outer Section Spacing */
.program-page .program-hero {
    position: relative !important;
    padding: var(--spacing-sm, 16px) 0 var(--spacing-md, 32px) 0 !important;
    background: var(--color-bg-alt) !important;
    border-bottom: 1px solid var(--color-border-subtle) !important;
}

.program-page .program-hero-breadcrumb {
    margin-bottom: var(--spacing-sm, 16px) !important;
}

/* Base Unified Hero Card */
.program-page .program-hero-grid {
    position: relative !important;
    border-radius: var(--radius-lg, 12px) !important;
    overflow: hidden !important;
    border: 1px solid var(--color-border-subtle) !important;
    background: var(--color-bg-surface) !important;
    box-shadow: var(--shadow-subtle, 0 4px 20px rgba(0, 0, 0, 0.04)) !important;
}

/* ==========================================================================
   DESKTOP (min-width: 901px): Text on LEFT side ON TOP OF the banner image
   ========================================================================== */
@media (min-width: 901px) {
    .program-page .program-hero-grid {
        display: block !important;
        min-height: 520px !important;
    }

    /* Background image layer */
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

    /* Subtle left-side feather scrim using existing theme token var(--color-bg-warm) */
    .program-page .program-hero-grid::after {
        content: '' !important;
        position: absolute !important;
        inset: 0 !important;
        z-index: 2 !important;
        pointer-events: none !important;
        background: linear-gradient(
            to right,
            var(--color-bg-warm) 0%,
            color-mix(in srgb, var(--color-bg-warm) 85%, transparent) 32%,
            color-mix(in srgb, var(--color-bg-warm) 35%, transparent) 52%,
            transparent 72%
        ) !important;
    }

    /* Text content on LEFT side */
    .program-page .program-hero-content {
        position: relative !important;
        z-index: 3 !important;
        max-width: 540px !important;
        padding: 44px 40px 40px 40px !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        min-height: 520px !important;
    }

    .program-page .program-hero-title {
        font-family: var(--font-heading) !important;
        font-size: clamp(2rem, 3.5vw, 2.85rem) !important;
        font-weight: 700 !important;
        line-height: 1.15 !important;
        letter-spacing: -0.025em !important;
        color: var(--color-text) !important;
        margin: 0 0 12px 0 !important;
        text-wrap: balance !important;
    }

    .program-page .program-hero-desc {
        font-size: clamp(1rem, 1.35vw, 1.15rem) !important;
        line-height: 1.55 !important;
        color: var(--color-text-secondary) !important;
        max-width: 460px !important;
        margin: 0 0 18px 0 !important;
    }

    .program-page .program-hero-meta {
        display: flex !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        gap: 8px !important;
        margin-bottom: 22px !important;
    }

    .program-page .meta-chip {
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        padding: 6px 12px !important;
        font-size: 0.8rem !important;
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
        padding: 12px 20px !important;
        font-size: 0.95rem !important;
    }
}

/* Adjustments at 1024px desktop/tablet */
@media (max-width: 1100px) and (min-width: 901px) {
    .program-page .program-hero-grid {
        min-height: 480px !important;
    }
    .program-page .program-hero-content {
        max-width: 460px !important;
        padding: 36px 32px !important;
        min-height: 480px !important;
    }
    .program-page .program-hero-title {
        font-size: 2.25rem !important;
    }
    .program-page .program-hero-desc {
        font-size: 1rem !important;
        max-width: 400px !important;
    }
}

/* ==========================================================================
   TABLET & MOBILE (max-width: 900px): Unified stacked composition
   Content sits above the banner image without overlapping people or laptop
   ========================================================================== */
@media (max-width: 900px) {
    .program-page .program-hero-grid {
        display: flex !important;
        flex-direction: column !important;
        gap: 0 !important;
        min-height: auto !important;
    }

    .program-page .program-hero-grid::after {
        display: none !important;
    }

    .program-page .program-hero-content {
        position: relative !important;
        max-width: 100% !important;
        min-height: auto !important;
        padding: 32px 28px 20px 28px !important;
        z-index: 2 !important;
    }

    .program-page .program-hero-title {
        font-size: clamp(1.85rem, 4vw, 2.35rem) !important;
        line-height: 1.2 !important;
        margin: 0 0 10px 0 !important;
    }

    .program-page .program-hero-desc {
        font-size: 1.05rem !important;
        line-height: 1.55 !important;
        max-width: 680px !important;
        margin: 0 0 16px 0 !important;
    }

    .program-page .program-hero-meta {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 8px !important;
        margin-bottom: 20px !important;
    }

    .program-page .program-hero-actions {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        flex-wrap: wrap !important;
    }

    .program-page .program-hero-banner {
        position: relative !important;
        inset: auto !important;
        width: 100% !important;
        height: auto !important;
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
        object-position: center !important;
        border-radius: 0 !important;
    }
}

/* Mobile-Specific Refinement (<= 640px) */
@media (max-width: 640px) {
    .program-page .program-hero {
        padding: var(--spacing-xs, 8px) 0 var(--spacing-md, 32px) 0 !important;
        text-align: center !important;
    }

    .program-page .program-hero-breadcrumb {
        margin-bottom: var(--spacing-xs) !important;
    }

    .program-page .program-hero-breadcrumb .breadcrumb-list {
        justify-content: center !important;
        font-size: 0.775rem !important;
        gap: 6px !important;
    }

    .program-page .program-hero-content {
        padding: 24px 18px 18px 18px !important;
        align-items: center !important;
        text-align: center !important;
    }

    .program-page .program-hero-title {
        font-size: clamp(1.75rem, 7vw, 2.25rem) !important;
        text-align: center !important;
        margin-bottom: 8px !important;
    }

    .program-page .program-hero-desc {
        font-size: 0.95rem !important;
        text-align: center !important;
        margin-bottom: 14px !important;
    }

    .program-page .program-hero-meta {
        justify-content: center !important;
        gap: 6px !important;
        margin-bottom: 18px !important;
        width: 100% !important;
    }

    .program-page .program-hero-actions {
        flex-direction: column !important;
        width: 100% !important;
        gap: 10px !important;
    }

    .program-page .hero-btn-primary,
    .program-page .hero-btn-secondary {
        width: 100% !important;
        max-width: 340px !important;
        justify-content: center !important;
    }

    .program-page .program-hero-grid {
        border-radius: var(--radius-md, 8px) !important;
    }
}
"""

async def test():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page()

        viewports = [
            (1440, 900, "1440"),
            (1280, 800, "1280"),
            (1024, 768, "1024"),
            (834, 1194, "834"),
            (768, 1024, "768"),
            (430, 932, "430"),
            (390, 844, "390"),
            (375, 812, "375"),
            (360, 800, "360"),
        ]

        for w, h, lbl in viewports:
            await page.set_viewport_size({"width": w, "height": h})
            await page.goto(URL, wait_until="networkidle")
            await page.add_style_tag(content=REFINED_CSS)

            # Dark
            await page.evaluate("() => { document.documentElement.classList.remove('light-theme'); document.documentElement.classList.add('dark-theme'); }")
            await page.wait_for_timeout(150)
            hero = await page.query_selector('.program-hero')
            if hero:
                await hero.scroll_into_view_if_needed()
                await hero.screenshot(path=f"{ARTIFACT_DIR}/refined_dark_{lbl}.png")

            # Light
            await page.evaluate("() => { document.documentElement.classList.remove('dark-theme'); document.documentElement.classList.add('light-theme'); }")
            await page.wait_for_timeout(150)
            if hero:
                await hero.scroll_into_view_if_needed()
                await hero.screenshot(path=f"{ARTIFACT_DIR}/refined_light_{lbl}.png")

            print(f"Captured refined screenshots for {lbl}")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(test())
