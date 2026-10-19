import asyncio
import os
import sys

sys.stdout.reconfigure(encoding='utf-8')
from playwright.async_api import async_playwright

SCREENSHOT_DIR = r"C:\Users\knamd\.gemini\antigravity-ide\brain\2550e99a-70f2-4de5-817a-9147af38f451\final_qa_screenshots"

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

async def capture_dark_theme():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page()
        
        for vp in VIEWPORTS:
            await page.set_viewport_size({"width": vp["width"], "height": vp["height"]})
            await page.goto("https://pro.lohnour.com/coaching/agentic-ai-foundations/", wait_until="networkidle")
            
            # Ensure it is in dark theme by clicking toggle if it has 'light-theme'
            is_light = await page.evaluate("() => document.documentElement.classList.contains('light-theme')")
            if is_light:
                await page.click(".theme-toggle")
                await asyncio.sleep(0.4)
            
            dark_info = await page.evaluate("""() => {
                const darkImg = document.querySelector('.banner-theme-dark');
                const lightImg = document.querySelector('.banner-theme-light');
                return {
                    htmlClass: document.documentElement.className,
                    darkDisplay: window.getComputedStyle(darkImg).display,
                    darkOpacity: window.getComputedStyle(darkImg).opacity,
                    lightDisplay: window.getComputedStyle(lightImg).display
                };
            }""")
            
            clean_name = vp['name'].split()[0]
            shot_path = os.path.join(SCREENSHOT_DIR, f"hero_{clean_name}_dark_actual.png")
            await page.locator('.program-hero').screenshot(path=shot_path)
            print(f"Captured {vp['name']} in Dark Theme: html.class='{dark_info['htmlClass']}', darkDisplay={dark_info['darkDisplay']}, lightDisplay={dark_info['lightDisplay']}")
            
        await browser.close()

asyncio.run(capture_dark_theme())
