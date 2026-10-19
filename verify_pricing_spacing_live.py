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

SCREENSHOT_DIR = r"C:\Users\knamd\.gemini\antigravity-ide\brain\2550e99a-70f2-4de5-817a-9147af38f451\final_qa_screenshots"

async def test_pricing_spacing():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        page = await browser.new_page()
        
        console_errors = []
        page.on("console", lambda msg: console_errors.append(msg.text) if msg.type == "error" else None)
        page.on("pageerror", lambda err: console_errors.append(str(err)))
        
        print("==================================================", flush=True)
        print("COHORT ENROLLMENT SPACING QA - 10 VIEWPORTS", flush=True)
        print("==================================================", flush=True)
        
        for vp in VIEWPORTS:
            await page.set_viewport_size({"width": vp["width"], "height": vp["height"]})
            await page.goto("https://pro.lohnour.com/coaching/agentic-ai-foundations/#faqs", wait_until="networkidle")
            
            data = await page.evaluate("""() => {
                const card = document.querySelector('.pricing-card');
                const badge = card.querySelector('.card-badge');
                const title = card.querySelector('.section-title');
                const desc = card.querySelector('.pricing-desc');
                const callout = card.querySelector('.pricing-callout');
                const actions = card.querySelector('.pricing-actions');
                const btn = card.querySelector('.btn-primary');
                const subtext = card.querySelector('.pricing-subtext');
                
                const b_card = card.getBoundingClientRect();
                const b_badge = badge.getBoundingClientRect();
                const b_title = title.getBoundingClientRect();
                const b_desc = desc.getBoundingClientRect();
                const b_callout = callout.getBoundingClientRect();
                const b_actions = actions.getBoundingClientRect();
                const b_subtext = subtext.getBoundingClientRect();
                
                const doc = document.documentElement;
                
                return {
                    scrollWidth: doc.scrollWidth,
                    clientWidth: doc.clientWidth,
                    hasOverflow: doc.scrollWidth > doc.clientWidth,
                    cardHeight: b_card.height,
                    dist_badge_title: b_title.top - b_badge.bottom,
                    dist_title_desc: b_desc.top - b_title.bottom,
                    dist_desc_callout: b_callout.top - b_desc.bottom,
                    dist_callout_actions: b_actions.top - b_callout.bottom,
                    dist_actions_subtext: b_subtext.top - b_actions.bottom
                };
            }""")
            
            clean_name = vp['name'].split()[0]
            shot_path = os.path.join(SCREENSHOT_DIR, f"cohort_enrollment_{clean_name}.png")
            await page.locator('.pricing-card').screenshot(path=shot_path)
            
            print(f"Viewport: {vp['name']}")
            print(f"  Overflow PASS: {not data['hasOverflow']} (scroll={data['scrollWidth']}, client={data['clientWidth']})")
            print(f"  Badge -> Title:          {data['dist_badge_title']:.1f}px (was 1.5px)")
            print(f"  Title -> Description:    {data['dist_title_desc']:.1f}px (was 6.0px)")
            print(f"  Description -> Chips:    {data['dist_desc_callout']:.1f}px (was 16.0px)")
            print(f"  Chips -> CTA:            {data['dist_callout_actions']:.1f}px (was 16.0px)")
            print(f"  CTA -> Supporting Text:  {data['dist_actions_subtext']:.1f}px (was 8.0px)")
            print(f"  Total Card Height:       {data['cardHeight']:.1f}px")
            print("---", flush=True)
            
        print(f"\nConsole Errors Count: {len(console_errors)}")
        await browser.close()

asyncio.run(test_pricing_spacing())
