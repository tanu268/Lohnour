import asyncio
from playwright.async_api import async_playwright

URL = "https://pro.lohnour.com/coaching/agentic-ai-foundations/?v=verify_unified"

UNIFIED_CSS = """
/* Refined Hero Outer Spacing */
.program-page .program-hero {
	position: relative;
	padding: var(--spacing-sm, 16px) 0 var(--spacing-md, 32px) 0 !important;
	background: var(--color-bg-alt);
	border-bottom: 1px solid var(--color-border-subtle);
}

.program-page .program-hero-breadcrumb {
	margin-bottom: var(--spacing-sm, 16px) !important;
}

/* Unified Hero Banner Card Composition */
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

/* Program Title */
.program-page .program-hero-title {
	font-family: var(--font-heading);
	font-size: clamp(2.25rem, 4.5vw, 3.25rem) !important;
	font-weight: 700;
	line-height: 1.15;
	letter-spacing: -0.025em;
	color: var(--color-text);
	margin: 0 0 12px 0 !important;
	text-wrap: balance;
}

/* Program Description */
.program-page .program-hero-desc {
	font-size: clamp(1.05rem, 1.75vw, 1.25rem) !important;
	line-height: 1.55;
	color: var(--color-text-secondary);
	max-width: 780px !important;
	margin: 0 0 18px 0 !important;
}

/* Program Meta Chips Row */
.program-page .program-hero-meta {
	display: flex !important;
	flex-wrap: wrap !important;
	align-items: center !important;
	gap: 8px !important;
	margin-bottom: 22px !important;
}

/* Hero Actions */
.program-page .program-hero-actions {
	display: flex !important;
	align-items: center !important;
	gap: 12px !important;
	flex-wrap: wrap !important;
	margin-bottom: 0 !important;
}

/* Lower Portion: Theme-Aware Responsive Program Banner */
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
	object-position: center !important;
	border-radius: 0 !important;
}

/* Theme Switching Mechanism */
.program-page .program-hero-banner .banner-theme-dark {
	display: block;
}
.program-page .program-hero-banner .banner-theme-light {
	display: none;
}

html.light-theme .program-page .program-hero-banner .banner-theme-dark {
	display: none !important;
}
html.light-theme .program-page .program-hero-banner .banner-theme-light {
	display: block !important;
}

html.dark-theme .program-page .program-hero-banner .banner-theme-dark {
	display: block !important;
}
html.dark-theme .program-page .program-hero-banner .banner-theme-light {
	display: none !important;
}

/* Tablet (1024px to 641px) */
@media (max-width: 1024px) {
	.program-page .program-hero {
		padding: var(--spacing-sm, 16px) 0 var(--spacing-md, 32px) 0 !important;
	}

	.program-page .program-hero-content {
		max-width: 100% !important;
		padding: 32px 32px 20px 32px !important;
	}

	.program-page .program-hero-title {
		font-size: clamp(2rem, 4vw, 2.5rem) !important;
		line-height: 1.2 !important;
		margin-bottom: 10px !important;
	}

	.program-page .program-hero-desc {
		font-size: 1.05rem !important;
		max-width: 680px !important;
		margin-bottom: 16px !important;
	}

	.program-page .program-hero-meta {
		gap: 8px !important;
		margin-bottom: 20px !important;
	}
}

/* Mobile (<= 640px) */
@media (max-width: 640px) {
	.program-page .program-hero {
		padding: var(--spacing-xs, 8px) 0 var(--spacing-md, 32px) 0 !important;
	}

	.program-page .program-hero-content {
		padding: 24px 18px 18px 18px !important;
	}

	.program-page .program-hero-title {
		font-size: 1.75rem !important;
		margin-bottom: 8px !important;
	}

	.program-page .program-hero-desc {
		font-size: 0.95rem !important;
		line-height: 1.5 !important;
		margin-bottom: 14px !important;
	}

	.program-page .program-hero-meta {
		gap: 6px !important;
		margin-bottom: 18px !important;
	}

	.program-page .program-hero-actions {
		flex-direction: column !important;
		gap: 10px !important;
	}

	.program-page .hero-btn-primary,
	.program-page .hero-btn-secondary {
		width: 100% !important;
		justify-content: center !important;
	}

	.program-page .program-hero-grid {
		border-radius: var(--radius-md, 8px) !important;
	}
}
"""

VIEWPORT_MATRIX = [
    (1920, 1080, "large_desktop_1920"),
    (1600, 900, "desktop_1600"),
    (1440, 900, "desktop_1440"),
    (1366, 768, "compact_desktop_1366"),
    (1280, 800, "laptop_1280"),
    (1024, 768, "tablet_landscape_1024"),
    (1024, 1366, "tablet_portrait_1024"),
    (834, 1194, "tablet_portrait_834"),
    (820, 1180, "tablet_portrait_820"),
    (768, 1024, "tablet_portrait_768"),
    (430, 932, "mobile_large_430"),
    (414, 896, "mobile_std_414"),
    (390, 844, "mobile_std_390"),
    (375, 812, "mobile_compact_375"),
    (360, 800, "mobile_small_360"),
]

INTERMEDIATE_WIDTHS = [1200, 1100, 900, 700, 600, 480]

async def verify():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page()

        print("--- Testing Viewports Matrix with Unified Hero CSS ---")
        for w, h, label in VIEWPORT_MATRIX:
            await page.set_viewport_size({"width": w, "height": h})
            await page.goto(URL, wait_until="networkidle")
            await page.add_style_tag(content=UNIFIED_CSS)
            await page.wait_for_timeout(100)

            # Test Dark Mode
            await page.evaluate("() => { document.documentElement.classList.remove('light-theme'); document.documentElement.classList.add('dark-theme'); }")
            dark_res = await page.evaluate('''() => {
                const sW = document.documentElement.scrollWidth;
                const cW = document.documentElement.clientWidth;
                const darkImg = document.querySelector('.program-hero-banner .banner-theme-dark');
                const hero = document.querySelector('.program-hero');
                const card = document.querySelector('.program-hero-grid');
                return {
                    scrollWidth: sW,
                    clientWidth: cW,
                    overflow: sW > cW,
                    darkImgVisible: darkImg && getComputedStyle(darkImg).display !== 'none' && darkImg.offsetWidth > 0,
                    heroH: hero ? hero.offsetHeight : 0,
                    cardH: card ? card.offsetHeight : 0,
                };
            }''')

            # Test Light Mode
            await page.evaluate("() => { document.documentElement.classList.remove('dark-theme'); document.documentElement.classList.add('light-theme'); }")
            light_res = await page.evaluate('''() => {
                const sW = document.documentElement.scrollWidth;
                const cW = document.documentElement.clientWidth;
                const lightImg = document.querySelector('.program-hero-banner .banner-theme-light');
                return {
                    overflow: sW > cW,
                    lightImgVisible: lightImg && getComputedStyle(lightImg).display !== 'none' && lightImg.offsetWidth > 0
                };
            }''')

            overflow_ok = (not dark_res['overflow']) and (not light_res['overflow'])
            status = "PASS" if (overflow_ok and dark_res['darkImgVisible'] and light_res['lightImgVisible']) else "FAIL"
            print(f"[{label:24}] {w}x{h} | DarkImg={dark_res['darkImgVisible']} | LightImg={light_res['lightImgVisible']} | HeroH={dark_res['heroH']}px | Overflow={'NO (PASS)' if overflow_ok else 'FAIL'} -> {status}")

        print("\n--- Testing Intermediate Widths ---")
        for width in INTERMEDIATE_WIDTHS:
            await page.set_viewport_size({"width": width, "height": 800})
            await page.goto(URL, wait_until="networkidle")
            await page.add_style_tag(content=UNIFIED_CSS)
            res = await page.evaluate('''() => {
                const sW = document.documentElement.scrollWidth;
                const cW = document.documentElement.clientWidth;
                return { sW, cW, overflow: sW > cW };
            }''')
            print(f"Intermediate {width}px: scrollWidth={res['sW']}, clientWidth={res['cW']} | Overflow={res['overflow']}")

        print("\n--- Testing Dynamic Resize Sweep (1440 -> 360) ---")
        sweep_widths = [1440, 1200, 1024, 900, 768, 600, 480, 390, 360]
        sweep_failed = False
        for w in sweep_widths:
            await page.set_viewport_size({"width": w, "height": 800})
            await page.wait_for_timeout(50)
            res = await page.evaluate('() => document.documentElement.scrollWidth > document.documentElement.clientWidth')
            if res:
                print(f"  Resize sweep FAILED at {w}px (horizontal overflow detected)")
                sweep_failed = True
        if not sweep_failed:
            print("  Resize sweep PASSED continuously across 1440 -> 360 without overflow or layout breaks.")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(verify())
