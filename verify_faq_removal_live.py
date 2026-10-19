import asyncio
import os
import sys

sys.stdout.reconfigure(encoding='utf-8')
from playwright.async_api import async_playwright

async def verify_faq():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        page = await browser.new_page()
        await page.set_viewport_size({'width': 1280, 'height': 800})
        await page.goto('https://pro.lohnour.com/coaching/agentic-ai-foundations/', wait_until='networkidle')
        
        info = await page.evaluate('''() => {
            const faqFooter = document.querySelector('.faq-preview-footer');
            const faqSection = document.querySelector('#faqs');
            const hasViewAll = faqSection ? faqSection.innerText.includes('View All FAQs') : false;
            return {
                faqFooterExists: faqFooter !== null,
                hasViewAllText: hasViewAll,
                faqItemCount: document.querySelectorAll('.faq-item').length
            };
        }''')
        
        print('Live FAQ Check:', info)
        assert not info['faqFooterExists'], "ERROR: .faq-preview-footer still exists!"
        assert not info['hasViewAllText'], "ERROR: 'View All FAQs' text still present!"
        print(f"PASS: FAQ section has {info['faqItemCount']} items and ZERO 'View All FAQs' link.")
        
        # Scroll to FAQ and capture screenshot
        faq_section = page.locator('#faqs')
        await faq_section.scroll_into_view_if_needed()
        await page.wait_for_timeout(400)
        shot_path = r"C:\Users\knamd\.gemini\antigravity-ide\brain\2550e99a-70f2-4de5-817a-9147af38f451\final_qa_screenshots\faq_section_no_link.png"
        await faq_section.screenshot(path=shot_path)
        print(f"Saved screenshot to {shot_path}")
        
        # Also capture area showing bottom of FAQ + top of Final CTA
        cta_section = page.locator('.cta-section')
        await cta_section.scroll_into_view_if_needed()
        await page.wait_for_timeout(400)
        shot_path_cta = r"C:\Users\knamd\.gemini\antigravity-ide\brain\2550e99a-70f2-4de5-817a-9147af38f451\final_qa_screenshots\faq_and_final_cta.png"
        await page.screenshot(path=shot_path_cta)
        print(f"Saved screenshot to {shot_path_cta}")
        
        await browser.close()

asyncio.run(verify_faq())
