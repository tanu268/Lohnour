import sys
import time
import requests
from playwright.sync_api import sync_playwright

sys.stdout.reconfigure(encoding='utf-8')

LIVE_BASE_URL = "https://pro.lohnour.com"
PAGE_URL = f"{LIVE_BASE_URL}/coaching/agentic-ai-foundations/?v=module_acc_v1"

CORE_ROUTES = [
    "/",
    "/services/",
    "/about/",
    "/contact/",
    "/blog/",
    "/guides/",
    "/coaching/",
    "/coaching/agentic-ai-foundations/"
]

VIEWPORTS = [
    {"name": "1440_desktop", "width": 1440, "height": 900},
    {"name": "1280_desktop", "width": 1280, "height": 800},
    {"name": "1024_landscape", "width": 1024, "height": 768},
    {"name": "834_tablet", "width": 834, "height": 1194},
    {"name": "768_tablet", "width": 768, "height": 1024},
    {"name": "430_mobile", "width": 430, "height": 932},
    {"name": "414_mobile", "width": 414, "height": 896},
    {"name": "390_mobile", "width": 390, "height": 844},
    {"name": "375_mobile", "width": 375, "height": 667},
    {"name": "360_mobile", "width": 360, "height": 800},
]

print("=" * 50)
print("1. CHECKING CORE ROUTES HTTP STATUS")
print("=" * 50)
for route in CORE_ROUTES:
    url = f"{LIVE_BASE_URL}{route}"
    try:
        r = requests.get(url, timeout=15)
        status = r.status_code
        if status == 200:
            print(f"[{status}] OK: {url}")
        else:
            print(f"[{status}] FAIL: {url}")
            sys.exit(1)
    except Exception as e:
        print(f"ERROR requesting {url}: {e}")
        sys.exit(1)

print("\n" + "=" * 50)
print("2. VERIFYING LIVE MODULE ACCORDION ON PRODUCTION")
print("=" * 50)

with sync_playwright() as p:
    browser = p.chromium.launch(headless=True)
    
    # 2A. Breakpoint verification
    for vp in VIEWPORTS:
        context = browser.new_context(viewport={"width": vp["width"], "height": vp["height"]})
        page = context.new_page()
        res = page.goto(PAGE_URL, wait_until="networkidle", timeout=30000)
        status = res.status if res else "Unknown"
        
        print(f"\nViewport {vp['width']}x{vp['height']} ({vp['name']}): HTTP {status}")
        
        # Check horizontal overflow
        overflow_info = page.evaluate("""() => {
            return {
                scrollWidth: document.documentElement.scrollWidth,
                clientWidth: document.documentElement.clientWidth,
                bodyScrollWidth: document.body.scrollWidth,
                hasOverflow: document.documentElement.scrollWidth > document.documentElement.clientWidth
            };
        }""")
        
        if overflow_info['hasOverflow']:
            print(f"  HORIZONTAL OVERFLOW DETECTED: scrollWidth={overflow_info['scrollWidth']}, clientWidth={overflow_info['clientWidth']}")
            sys.exit(1)
        else:
            print(f"  Horizontal Overflow: PASS (zero overflow) (scrollWidth={overflow_info['scrollWidth']}, clientWidth={overflow_info['clientWidth']})")
            
        # Verify left sidebar and mobile toggle NOT present
        sidebar_present = page.evaluate("() => document.getElementById('curriculum-guide-sidebar') !== null")
        toggle_present = page.evaluate("() => document.querySelector('.curriculum-mobile-outline-toggle-wrapper') !== null")
        print(f"  Left Sidebar Removed: {'FAIL' if sidebar_present else 'PASS (not in DOM)'}")
        print(f"  Mobile Toggle Removed: {'FAIL' if toggle_present else 'PASS (not in DOM)'}")
        if sidebar_present or toggle_present:
            sys.exit(1)
            
        # Verify 8 modules and default collapsed state
        modules_info = page.evaluate("""() => {
            const blocks = Array.from(document.querySelectorAll('.curriculum-guide-module-block'));
            const headers = Array.from(document.querySelectorAll('.module-accordion-header'));
            const contents = Array.from(document.querySelectorAll('.module-accordion-content'));
            
            const count = blocks.length;
            const allCollapsed = headers.every(h => h.getAttribute('aria-expanded') === 'false');
            const noActiveBlock = blocks.every(b => !b.classList.contains('active'));
            
            return {
                count: count,
                allCollapsed: allCollapsed,
                noActiveBlock: noActiveBlock,
                modules: blocks.map(b => b.id)
            };
        }""")
        
        print(f"  Modules Present: {'PASS' if modules_info['count'] == 8 else 'FAIL'} ({modules_info['count']} modules)")
        print(f"  Default All Collapsed: {'PASS' if modules_info['allCollapsed'] and modules_info['noActiveBlock'] else 'FAIL'}")
        if modules_info['count'] != 8 or not modules_info['allCollapsed'] or not modules_info['noActiveBlock']:
            sys.exit(1)
            
        # Scroll to curriculum
        curriculum_elem = page.locator("#curriculum")
        curriculum_elem.scroll_into_view_if_needed()
        time.sleep(0.3)
        
        # Capture Dark Theme screenshot
        page.evaluate("() => document.documentElement.setAttribute('data-theme', 'dark')")
        time.sleep(0.2)
        dark_shot = f"live_mod_acc_dark_{vp['name']}.png"
        curriculum_elem.screenshot(path=dark_shot)
        print(f"  Saved screenshot: {dark_shot}")
        
        # Capture Light Theme screenshot
        page.evaluate("() => document.documentElement.setAttribute('data-theme', 'light')")
        time.sleep(0.2)
        light_shot = f"live_mod_acc_light_{vp['name']}.png"
        curriculum_elem.screenshot(path=light_shot)
        print(f"  Saved screenshot: {light_shot}")
        
        context.close()
        
    # 2B. Test Full Accordion Interactivity
    print("\n" + "=" * 50)
    print("3. TESTING LIVE ACCORDION INTERACTIVITY ON PRODUCTION")
    print("=" * 50)
    
    context = browser.new_context(viewport={"width": 1280, "height": 800})
    page = context.new_page()
    page.goto(PAGE_URL, wait_until="networkidle", timeout=30000)
    
    curriculum_section = page.locator("#curriculum")
    curriculum_section.scroll_into_view_if_needed()
    time.sleep(0.3)
    
    # Step 1: Click Module 1 -> Module 1 opens
    mod1_btn = page.locator("#curriculum-mod-1-header")
    mod1_btn.click()
    time.sleep(0.5) # allow 350ms transition
    
    m1_state = page.evaluate("""() => {
        const mod1 = document.getElementById('curriculum-mod-1');
        const header = document.getElementById('curriculum-mod-1-header');
        const content = document.getElementById('curriculum-mod-1-content');
        return {
            active: mod1.classList.contains('active'),
            expanded: header.getAttribute('aria-expanded'),
            height: content.offsetHeight,
            maxHeight: content.style.maxHeight
        };
    }""")
    print(f"Click Module 1: active={m1_state['active']}, expanded={m1_state['expanded']}, height={m1_state['height']}px, maxHeight={m1_state['maxHeight']}")
    if not m1_state['active'] or m1_state['expanded'] != 'true' or m1_state['height'] <= 0:
        print("FAIL: Module 1 did not expand properly!")
        sys.exit(1)
    print("  PASS: Module 1 opened successfully.")
    
    # Capture screenshot of expanded Module 1
    page.evaluate("() => document.documentElement.setAttribute('data-theme', 'dark')")
    time.sleep(0.2)
    curriculum_section.screenshot(path="live_mod1_expanded_dark.png")
    page.evaluate("() => document.documentElement.setAttribute('data-theme', 'light')")
    time.sleep(0.2)
    curriculum_section.screenshot(path="live_mod1_expanded_light.png")
    print("  Saved screenshots: live_mod1_expanded_dark.png & live_mod1_expanded_light.png")
    
    # Step 2: Test Inner Accordion inside Module 1 ("What You'll Learn")
    learn_btn = page.locator("#curriculum-mod-1-learn .guides-accordion-header")
    learn_btn.click()
    time.sleep(0.4)
    
    learn_state = page.evaluate("""() => {
        const item = document.getElementById('curriculum-mod-1-learn');
        const btn = item.querySelector('.guides-accordion-header');
        const content = document.getElementById('mod-1-learn-content');
        return {
            active: item.classList.contains('active'),
            expanded: btn.getAttribute('aria-expanded'),
            height: content.offsetHeight
        };
    }""")
    print(f"Click Inner What You'll Learn: active={learn_state['active']}, expanded={learn_state['expanded']}, height={learn_state['height']}px")
    if not learn_state['active'] or learn_state['expanded'] != 'true' or learn_state['height'] <= 0:
        print("FAIL: Inner What You'll Learn did not expand properly!")
        sys.exit(1)
    print("  PASS: Inner What You'll Learn expanded.")
    
    # Step 3: Test Inner Accordion inside Module 1 ("Practical")
    prac_btn = page.locator("#curriculum-mod-1-practical .guides-accordion-header")
    prac_btn.click()
    time.sleep(0.4)
    prac_state = page.evaluate("""() => {
        const item = document.getElementById('curriculum-mod-1-practical');
        const btn = item.querySelector('.guides-accordion-header');
        return {
            active: item.classList.contains('active'),
            expanded: btn.getAttribute('aria-expanded')
        };
    }""")
    print(f"Click Inner Practical: active={prac_state['active']}, expanded={prac_state['expanded']}")
    if not prac_state['active'] or prac_state['expanded'] != 'true':
        print("FAIL: Inner Practical did not expand!")
        sys.exit(1)
    print("  PASS: Inner Practical expanded.")

    # Step 4: Test Inner Accordion inside Module 1 ("Module Outcome")
    out_btn = page.locator("#curriculum-mod-1-outcome .guides-accordion-header")
    out_btn.click()
    time.sleep(0.4)
    out_state = page.evaluate("""() => {
        const item = document.getElementById('curriculum-mod-1-outcome');
        const btn = item.querySelector('.guides-accordion-header');
        return {
            active: item.classList.contains('active'),
            expanded: btn.getAttribute('aria-expanded')
        };
    }""")
    print(f"Click Inner Outcome: active={out_state['active']}, expanded={out_state['expanded']}")
    if not out_state['active'] or out_state['expanded'] != 'true':
        print("FAIL: Inner Outcome did not expand!")
        sys.exit(1)
    print("  PASS: Inner Outcome expanded.")

    # Step 5: Click Module 2 -> Single-Open: Module 1 closes and Module 2 opens
    mod2_btn = page.locator("#curriculum-mod-2-header")
    mod2_btn.click()
    time.sleep(0.5)
    
    single_open_state = page.evaluate("""() => {
        const m1 = document.getElementById('curriculum-mod-1');
        const m1_btn = document.getElementById('curriculum-mod-1-header');
        const m2 = document.getElementById('curriculum-mod-2');
        const m2_btn = document.getElementById('curriculum-mod-2-header');
        return {
            m1_active: m1.classList.contains('active'),
            m1_expanded: m1_btn.getAttribute('aria-expanded'),
            m2_active: m2.classList.contains('active'),
            m2_expanded: m2_btn.getAttribute('aria-expanded')
        };
    }""")
    print(f"Single-Open Check: M1 active={single_open_state['m1_active']}(exp={single_open_state['m1_expanded']}), M2 active={single_open_state['m2_active']}(exp={single_open_state['m2_expanded']})")
    if single_open_state['m1_active'] or single_open_state['m1_expanded'] != 'false' or not single_open_state['m2_active'] or single_open_state['m2_expanded'] != 'true':
        print("FAIL: Single-open logic did not close Module 1 when Module 2 was clicked!")
        sys.exit(1)
    print("  PASS: Single-open logic confirmed: Module 1 closed and Module 2 opened.")

    # Step 6: Click Module 2 again -> closes it
    mod2_btn.click()
    time.sleep(0.5)
    m2_closed_state = page.evaluate("""() => {
        const m2 = document.getElementById('curriculum-mod-2');
        const m2_btn = document.getElementById('curriculum-mod-2-header');
        return {
            active: m2.classList.contains('active'),
            expanded: m2_btn.getAttribute('aria-expanded')
        };
    }""")
    print(f"Click Module 2 again: active={m2_closed_state['active']}, expanded={m2_closed_state['expanded']}")
    if m2_closed_state['active'] or m2_closed_state['expanded'] != 'false':
        print("FAIL: Module 2 did not collapse when clicked again!")
        sys.exit(1)
    print("  PASS: Module 2 collapsed on re-click (all modules collapsed).")

    # Step 7: Keyboard Accessibility (Enter & Space)
    mod3_btn = page.locator("#curriculum-mod-3-header")
    mod3_btn.focus()
    page.keyboard.press("Enter")
    time.sleep(0.5)
    m3_enter_state = page.evaluate("""() => {
        const m3 = document.getElementById('curriculum-mod-3');
        const m3_btn = document.getElementById('curriculum-mod-3-header');
        return {
            active: m3.classList.contains('active'),
            expanded: m3_btn.getAttribute('aria-expanded')
        };
    }""")
    print(f"Keyboard Enter on Module 3: active={m3_enter_state['active']}, expanded={m3_enter_state['expanded']}")
    if not m3_enter_state['active'] or m3_enter_state['expanded'] != 'true':
        print("FAIL: Keyboard Enter failed to expand Module 3!")
        sys.exit(1)
    print("  PASS: Keyboard Enter expanded Module 3.")

    # Press Space on Module 3 to collapse
    page.keyboard.press("Space")
    time.sleep(0.5)
    m3_space_state = page.evaluate("""() => {
        const m3 = document.getElementById('curriculum-mod-3');
        const m3_btn = document.getElementById('curriculum-mod-3-header');
        return {
            active: m3.classList.contains('active'),
            expanded: m3_btn.getAttribute('aria-expanded')
        };
    }""")
    print(f"Keyboard Space on Module 3: active={m3_space_state['active']}, expanded={m3_space_state['expanded']}")
    if m3_space_state['active'] or m3_space_state['expanded'] != 'false':
        print("FAIL: Keyboard Space failed to collapse Module 3!")
        sys.exit(1)
    print("  PASS: Keyboard Space collapsed Module 3.")

    context.close()
    browser.close()

print("\n" + "=" * 50)
print("ALL LIVE PRODUCTION VERIFICATIONS COMPLETED SUCCESSFULLY!")
print("=" * 50)
