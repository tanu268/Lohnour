import asyncio
from playwright.async_api import async_playwright

URL = "https://pro.lohnour.com/coaching/agentic-ai-foundations/?v=test_var"
ARTIFACT_DIR = r"C:\Users\knamd\.gemini\antigravity-ide\brain\2550e99a-70f2-4de5-817a-9147af38f451"

# Variation 1: True Overlay on Desktop with responsive stack on tablet/mobile
CSS_VAR1 = """
/* Hero section tighter padding */
.program-page .program-hero {
    padding: var(--spacing-sm, 16px) 0 var(--spacing-md, 32px) 0 !important;
}

.program-page .program-hero-breadcrumb {
    margin-bottom: var(--spacing-sm, 16px) !important;
}

/* Unified Hero Banner Frame */
.program-page .program-hero-grid {
    position: relative !important;
    display: block !important;
    border-radius: var(--radius-lg, 12px) !important;
    overflow: hidden !important;
    border: 1px solid var(--color-border-subtle) !important;
    background: var(--color-bg-surface) !important;
    box-shadow: var(--shadow-subtle) !important;
    margin: 0 !important;
}

/* Banner sits as visual base */
.program-page .program-hero-banner {
    position: relative !important;
    width: 100% !important;
    margin: 0 !important;
    border: none !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    display: block !important;
    overflow: hidden !important;
}

.program-page .program-hero-banner img {
    width: 100% !important;
    height: auto !important;
    aspect-ratio: 2 / 1 !important;
    display: block !important;
    object-fit: cover !important;
    object-position: center bottom !important;
}

/* Hero Content positioned over upper portion on desktop */
@media (min-width: 1025px) {
    .program-page .program-hero-grid {
        min-height: 600px !important;
    }

    .program-page .program-hero-content {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        max-width: 720px !important;
        z-index: 2 !important;
        padding: 40px 48px 32px 48px !important;
    }

    /* Subtle readable overlay mask on top-left area only, leaving right side & people completely clear */
    .program-page .program-hero-grid::before {
        content: '' !important;
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        z-index: 1 !important;
        pointer-events: none !important;
        background: linear-gradient(
            135deg,
            var(--color-bg-warm) 0%,
            color-mix(in srgb, var(--color-bg-warm) 85%, transparent) 40%,
            color-mix(in srgb, var(--color-bg-warm) 20%, transparent) 65%,
            transparent 85%
        ) !important;
    }

    .program-page .program-hero-title {
        font-size: 2.75rem !important;
        line-height: 1.15 !important;
        margin: 0 0 14px 0 !important;
    }

    .program-page .program-hero-desc {
        font-size: 1.15rem !important;
        line-height: 1.55 !important;
        max-width: 620px !important;
        margin: 0 0 20px 0 !important;
    }

    .program-page .program-hero-meta {
        gap: 8px !important;
        margin-bottom: 24px !important;
    }

    .program-page .program-hero-actions {
        gap: 14px !important;
    }
}

/* Tablet (1024px to 769px): graceful integrated composition */
@media (max-width: 1024px) and (min-width: 769px) {
    .program-page .program-hero-content {
        position: relative !important;
        z-index: 2 !important;
        padding: 32px 32px 24px 32px !important;
        max-width: 100% !important;
    }

    .program-page .program-hero-title {
        font-size: 2.25rem !important;
        margin: 0 0 12px 0 !important;
    }

    .program-page .program-hero-desc {
        font-size: 1.05rem !important;
        margin: 0 0 16px 0 !important;
    }

    .program-page .program-hero-meta {
        gap: 8px !important;
        margin-bottom: 20px !important;
    }

    .program-page .program-hero-banner {
        margin-top: 8px !important;
    }
}

/* Mobile (<= 768px): unified card with stacked content and banner seamlessly integrated */
@media (max-width: 768px) {
    .program-page .program-hero-content {
        position: relative !important;
        z-index: 2 !important;
        padding: 24px 20px 20px 20px !important;
        max-width: 100% !important;
    }

    .program-page .program-hero-title {
        font-size: 1.85rem !important;
        line-height: 1.2 !important;
        margin: 0 0 10px 0 !important;
    }

    .program-page .program-hero-desc {
        font-size: 0.95rem !important;
        line-height: 1.5 !important;
        margin: 0 0 14px 0 !important;
    }

    .program-page .program-hero-meta {
        gap: 6px !important;
        margin-bottom: 18px !important;
    }

    .program-page .program-hero-actions {
        gap: 10px !important;
        margin-bottom: 4px !important;
    }

    .program-page .hero-btn-primary,
    .program-page .hero-btn-secondary {
        width: 100% !important;
    }

    .program-page .program-hero-banner {
        margin-top: 16px !important;
    }
}
"""

async def test_variations():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page()

        viewports = [
            (1400, 900, "desktop_1400"),
            (1024, 768, "tablet_1024"),
            (768, 1024, "tablet_768"),
            (390, 844, "mobile_390"),
        ]

        for w, h, label in viewports:
            await page.set_viewport_size({"width": w, "height": h})
            await page.goto(URL, wait_until="networkidle")

            # Apply Variation 1 CSS
            await page.add_style_tag(content=CSS_VAR1)
            await page.wait_for_timeout(200)

            # 1. Dark Mode
            await page.evaluate('''() => {
                document.documentElement.classList.remove('light-theme');
                document.documentElement.classList.add('dark-theme');
            }''')
            await page.wait_for_timeout(200)
            hero = await page.query_selector('.program-hero')
            if hero:
                await hero.scroll_into_view_if_needed()
                await hero.screenshot(path=f"{ARTIFACT_DIR}/v1_dark_{label}.png")

            # 2. Light Mode
            await page.evaluate('''() => {
                document.documentElement.classList.remove('dark-theme');
                document.documentElement.classList.add('light-theme');
            }''')
            await page.wait_for_timeout(200)
            if hero:
                await hero.scroll_into_view_if_needed()
                await hero.screenshot(path=f"{ARTIFACT_DIR}/v1_light_{label}.png")

            print(f"Captured v1 for {label}")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(test_variations())
