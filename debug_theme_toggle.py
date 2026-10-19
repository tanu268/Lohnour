import asyncio
import sys
from playwright.async_api import async_playwright

sys.stdout.reconfigure(encoding='utf-8')

async def check_theme():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page()
        await page.goto('https://pro.lohnour.com/coaching/agentic-ai-foundations/', wait_until='domcontentloaded')

        html_class = await page.evaluate('() => document.documentElement.className')
        html_data_theme = await page.evaluate('() => document.documentElement.getAttribute("data-theme")')
        body_class = await page.evaluate('() => document.body.className')
        print(f'Initial: html.class="{html_class}", html[data-theme]="{html_data_theme}", body.class="{body_class}"')

        dark_display = await page.evaluate('() => window.getComputedStyle(document.querySelector(".banner-theme-dark")).display')
        light_display = await page.evaluate('() => window.getComputedStyle(document.querySelector(".banner-theme-light")).display')
        print(f'Initial banner images: dark.display={dark_display}, light.display={light_display}')

        # Find theme toggle
        toggles = page.locator('button[class*="theme"], button[id*="theme"], a[class*="theme"]')
        count = await toggles.count()
        print(f'Found {count} candidate theme toggles')
        for i in range(count):
            t = toggles.nth(i)
            print(f'  Candidate {i}: id={await t.get_attribute("id")}, class={await t.get_attribute("class")}, aria-label={await t.get_attribute("aria-label")}')

        if count > 0:
            btn = toggles.first
            await btn.click()
            await asyncio.sleep(0.5)
            html_class_after = await page.evaluate('() => document.documentElement.className')
            html_data_theme_after = await page.evaluate('() => document.documentElement.getAttribute("data-theme")')
            dark_display_after = await page.evaluate('() => window.getComputedStyle(document.querySelector(".banner-theme-dark")).display')
            light_display_after = await page.evaluate('() => window.getComputedStyle(document.querySelector(".banner-theme-light")).display')
            print(f'After toggle click: html.class="{html_class_after}", html[data-theme]="{html_data_theme_after}"')
            print(f'After toggle click banner images: dark.display={dark_display_after}, light.display={light_display_after}')

        await browser.close()

asyncio.run(check_theme())
