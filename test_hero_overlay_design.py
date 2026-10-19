import asyncio
from playwright.async_api import async_playwright

URL = "https://pro.lohnour.com/coaching/agentic-ai-foundations/?v=test_hero_overlay"
ARTIFACT_DIR = r"C:\Users\knamd\.gemini\antigravity-ide\brain\2550e99a-70f2-4de5-817a-9147af38f451"

# Candidate CSS for Hero with Text on Left Over Image
CSS_CANDIDATE_1 = """
/* Outer Hero Section Spacing */
.program-page .program-hero {
    position: relative !important;
    padding: var(--spacing-sm, 16px) 0 var(--spacing-md, 32px) 0 !important;
    background: var(--color-bg-alt) !important;
    border-bottom: 1px solid var(--color-border-subtle) !important;
}

.program-page .program-hero-breadcrumb {
    margin-bottom: var(--spacing-sm, 16px) !important;
}

/* Hero Frame: Houses background image and overlay text */
.program-page .program-hero-grid {
    position: relative !important;
    display: block !important;
    border-radius: var(--radius-lg, 12px) !important;
    overflow: hidden !important;
    border: 1px solid var(--color-border-subtle) !important;
    background: var(--color-bg-surface) !important;
    box-shadow: var(--shadow-subtle, 0 4px 20px rgba(0, 0, 0, 0.04)) !important;
    min-height: 520px !important;
}

/* Background Banner Image: Absolute positioned behind content */
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

/* Subtle Left-to-Right feather scrim using existing theme tokens for text contrast */
.program-page .program-hero-grid::after {
    content: '' !important;
    position: absolute !important;
    inset: 0 !important;
    z-index: 2 !important;
    pointer-events: none !important;
    background: linear-gradient(
        to right,
        var(--color-bg-warm) 0%,
        color-mix(in srgb, var(--color-bg-warm) 85%, transparent) 35%,
        color-mix(in srgb, var(--color-bg-warm) 40%, transparent) 55%,
        transparent 75%
    ) !important;
}

/* Hero Content on LEFT side */
.program-page .program-hero-content {
    position: relative !important;
    z-index: 3 !important;
    max-width: 560px !important;
    padding: 48px 44px 44px 44px !important;
    display: flex !important;
    flex-direction: column !important;
    justify-content: center !important;
    min-height: 520px !important;
}

.program-page .program-hero-title {
    font-family: var(--font-heading) !important;
    font-size: clamp(2rem, 3.8vw, 3rem) !important;
    font-weight: 700 !important;
    line-height: 1.15 !important;
    letter-spacing: -0.025em !important;
    color: var(--color-text) !important;
    margin: 0 0 14px 0 !important;
    text-wrap: balance !important;
}

.program-page .program-hero-desc {
    font-size: clamp(1rem, 1.4vw, 1.15rem) !important;
    line-height: 1.55 !important;
    color: var(--color-text-secondary) !important;
    max-width: 480px !important;
    margin: 0 0 20px 0 !important;
}

.program-page .program-hero-meta {
    display: flex !important;
    flex-wrap: wrap !important;
    align-items: center !important;
    gap: 8px !important;
    margin-bottom: 24px !important;
}

.program-page .meta-chip {
    display: inline-flex !important;
    align-items: center !important;
    gap: 7px !important;
    padding: 7px 12px !important;
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

/* Tablet (1024px to 769px) */
@media (max-width: 1024px) and (min-width: 769px) {
    .program-page .program-hero-grid {
        min-height: 480px !important;
    }
    .program-page .program-hero-content {
        max-width: 500px !important;
        padding: 36px 32px 36px 32px !important;
        min-height: 480px !important;
    }
    .program-page .program-hero-title {
        font-size: 2.25rem !important;
    }
    .program-page .program-hero-desc {
        font-size: 1.05rem !important;
    }
}

/* Tablet Portrait (768px to 641px) */
@media (max-width: 768px) and (min-width: 641px) {
    .program-page .program-hero-grid {
        min-height: 460px !important;
    }
    .program-page .program-hero-content {
        max-width: 460px !important;
        padding: 32px 28px !important;
        min-height: 460px !important;
    }
    .program-page .program-hero-grid::after {
        background: linear-gradient(
            to right,
            var(--color-bg-warm) 0%,
            color-mix(in srgb, var(--color-bg-warm) 90%, transparent) 45%,
            color-mix(in srgb, var(--color-bg-warm) 50%, transparent) 70%,
            transparent 90%
        ) !important;
    }
}

/* Mobile (<= 640px): Stacked composition with banner visible underneath content */
@media (max-width: 640px) {
    .program-page .program-hero {
        padding: var(--spacing-xs, 8px) 0 var(--spacing-md, 32px) 0 !important;
    }
    .program-page .program-hero-grid {
        min-height: auto !important;
        display: flex !important;
        flex-direction: column !important;
        border-radius: var(--radius-md, 8px) !important;
    }
    .program-page .program-hero-grid::after {
        display: none !important;
    }
    .program-page .program-hero-content {
        position: relative !important;
        max-width: 100% !important;
        min-height: auto !important;
        padding: 24px 18px 20px 18px !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        text-align: center !important;
    }
    .program-page .program-hero-title {
        font-size: 1.75rem !important;
        text-align: center !important;
        margin-bottom: 8px !important;
    }
    .program-page .program-hero-desc {
        font-size: 0.95rem !important;
        text-align: center !important;
        margin-bottom: 14px !important;
        max-width: 100% !important;
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
    .program-page .program-hero-banner {
        position: relative !important;
        inset: auto !important;
        width: 100% !important;
        height: auto !important;
    }
    .program-page .program-hero-banner img {
        width: 100% !important;
        height: auto !important;
        aspect-ratio: 2 / 1 !important;
        object-fit: cover !important;
        object-position: center !important;
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
            (390, 844, "390"),
        ]

        for w, h, lbl in viewports:
            await page.set_viewport_size({"width": w, "height": h})
            await page.goto(URL, wait_until="networkidle")
            await page.add_style_tag(content=CSS_CANDIDATE_1)

            # Dark
            await page.evaluate('''() => {
                document.documentElement.classList.remove('light-theme');
                document.documentElement.classList.add('dark-theme');
            }''')
            await page.wait_for_timeout(200)
            hero = await page.query_selector('.program-hero')
            if hero:
                await hero.scroll_into_view_if_needed()
                await hero.screenshot(path=f"{ARTIFACT_DIR}/overlay_dark_{lbl}.png")
                print(f"Captured overlay_dark_{lbl}.png")

            # Light
            await page.evaluate('''() => {
                document.documentElement.classList.remove('dark-theme');
                document.documentElement.classList.add('light-theme');
            }''')
            await page.wait_for_timeout(200)
            if hero:
                await hero.scroll_into_view_if_needed()
                await hero.screenshot(path=f"{ARTIFACT_DIR}/overlay_light_{lbl}.png")
                print(f"Captured overlay_light_{lbl}.png")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(test())
