import asyncio
import sys

sys.stdout.reconfigure(encoding='utf-8')
from playwright.async_api import async_playwright

async def check_faq():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        page = await browser.new_page()
        await page.set_viewport_size({'width': 1280, 'height': 800})
        await page.goto('https://pro.lohnour.com/coaching/agentic-ai-foundations/#faqs', wait_until='networkidle')
        
        faq_footer = page.locator('.faq-preview-footer')
        await faq_footer.screenshot(path=r'C:\Users\knamd\.gemini\antigravity-ide\brain\2550e99a-70f2-4de5-817a-9147af38f451\final_qa_screenshots\faq_preview_footer.png')
        
        link = page.locator('.faq-preview-footer a')
        text = await link.inner_text()
        href = await link.get_attribute('href')
        print(f'FAQ Footer Link: text="{text}", href="{href}"')
        
        # Click it
        await link.click()
        await page.wait_for_load_state('networkidle')
        
        print('Navigated URL:', page.url)
        print('Page title:', await page.title())
        await browser.close()

asyncio.run(check_faq())
