import asyncio
import sys
from playwright.async_api import async_playwright

sys.stdout.reconfigure(encoding='utf-8')

async def audit_computed():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page(viewport={"width": 1440, "height": 900})
        await page.goto("https://pro.lohnour.com/coaching/agentic-ai-foundations/", wait_until="domcontentloaded", timeout=20000)
        await asyncio.sleep(0.5)

        print("=== 1. COMPUTED STYLES OF ALL SECTION H2s ===")
        h2_data = await page.evaluate("""() => {
            const h2s = Array.from(document.querySelectorAll('.program-page h2'));
            return h2s.map(h => ({
                text: h.textContent.trim().slice(0, 30),
                cls: h.className,
                fontFamily: window.getComputedStyle(h).fontFamily,
                fontSize: window.getComputedStyle(h).fontSize,
                fontWeight: window.getComputedStyle(h).fontWeight,
                lineHeight: window.getComputedStyle(h).lineHeight,
                letterSpacing: window.getComputedStyle(h).letterSpacing
            }));
        }""")
        for item in h2_data:
            print(f"  H2: '{item['text']:<30}' | size={item['fontSize']:<8} | weight={item['fontWeight']:<5} | lh={item['lineHeight']:<8} | ls={item['letterSpacing']:<8} | font={item['fontFamily'][:25]}")

        print("\n=== 2. COMPUTED STYLES OF ALL H3 CARD HEADINGS ===")
        h3_data = await page.evaluate("""() => {
            const h3s = Array.from(document.querySelectorAll('.program-page h3'));
            return h3s.map(h => ({
                text: h.textContent.trim().slice(0, 30),
                cls: h.className,
                fontSize: window.getComputedStyle(h).fontSize,
                fontWeight: window.getComputedStyle(h).fontWeight,
                lineHeight: window.getComputedStyle(h).lineHeight
            }));
        }""")
        print(f"Total H3 elements: {len(h3_data)}")
        for item in h3_data[:10]:
            print(f"  H3: '{item['text']:<30}' | size={item['fontSize']:<8} | weight={item['fontWeight']:<5} | lh={item['lineHeight']:<8} | cls='{item['cls']}'")
        if len(h3_data) > 10:
            print(f"  ... and {len(h3_data) - 10} more H3s")

        print("\n=== 3. COMPUTED STYLES OF BUTTONS ===")
        btn_data = await page.evaluate("""() => {
            const btns = Array.from(document.querySelectorAll('.program-page .btn'));
            return btns.map(b => ({
                text: b.textContent.trim().slice(0, 30),
                fontSize: window.getComputedStyle(b).fontSize,
                fontWeight: window.getComputedStyle(b).fontWeight,
                padding: window.getComputedStyle(b).padding,
                borderRadius: window.getComputedStyle(b).borderRadius
            }));
        }""")
        for b in btn_data:
            print(f"  BTN: '{b['text']:<30}' | size={b['fontSize']:<8} | weight={b['fontWeight']:<5} | pad={b['padding']:<16} | radius={b['borderRadius']}")

        print("\n=== 4. COMPUTED STYLES OF BADGES / CHIPS ===")
        badge_data = await page.evaluate("""() => {
            const badges = Array.from(document.querySelectorAll('.program-page .card-badge'));
            return badges.map(b => ({
                text: b.textContent.trim(),
                fontSize: window.getComputedStyle(b).fontSize,
                fontWeight: window.getComputedStyle(b).fontWeight,
                textTransform: window.getComputedStyle(b).textTransform,
                letterSpacing: window.getComputedStyle(b).letterSpacing
            }));
        }""")
        print(f"Total .card-badge elements: {len(badge_data)}")
        for b in badge_data[:5]:
            print(f"  BADGE: '{b['text']:<25}' | size={b['fontSize']:<8} | weight={b['fontWeight']:<5} | tt={b['textTransform']:<10} | ls={b['letterSpacing']}")

        print("\n=== 5. COMPUTED STYLES OF MODULE ACCORDION HEADERS ===")
        mod_headers = await page.evaluate("""() => {
            const headers = Array.from(document.querySelectorAll('.module-title-text'));
            return headers.map(h => ({
                text: h.textContent.trim().slice(0, 35),
                fontSize: window.getComputedStyle(h).fontSize,
                fontWeight: window.getComputedStyle(h).fontWeight,
                lineHeight: window.getComputedStyle(h).lineHeight
            }));
        }""")
        print(f"Total module title headers: {len(mod_headers)}")
        for mh in mod_headers:
            print(f"  MODULE HEADER: '{mh['text']:<35}' | size={mh['fontSize']:<8} | weight={mh['fontWeight']:<5} | lh={mh['lineHeight']}")

        await browser.close()

asyncio.run(audit_computed())
