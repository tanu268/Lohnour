import asyncio
from playwright.async_api import async_playwright
import os
import sys

def load_local_env():
    search_paths = [
        os.path.join(os.path.dirname(os.path.abspath(__file__)), ".env"),
        os.path.join(os.path.dirname(os.path.abspath(__file__)), "..", "..", ".env"),
    ]
    for env_path in search_paths:
        norm_path = os.path.normpath(env_path)
        if os.path.exists(norm_path):
            try:
                with open(norm_path, "r", encoding="utf-8") as f:
                    for line in f:
                        line = line.strip()
                        if line and not line.startswith("#") and "=" in line:
                            k, v = line.split("=", 1)
                            os.environ.setdefault(k.strip(), v.strip().strip('"').strip("'"))
            except Exception:
                pass
            break

load_local_env()

async def run():
    test_user = os.environ.get("LOHNOUR_TEST_USER")
    test_pass = os.environ.get("LOHNOUR_TEST_PASSWORD")
    target_url = os.environ.get("LOHNOUR_TEST_URL", "http://localhost:8081/wp-admin")

    if not test_user or not test_pass:
        print("ERROR: Missing required credentials for verification.", file=sys.stderr)
        print("Please set LOHNOUR_TEST_USER and LOHNOUR_TEST_PASSWORD in your environment or in a local .env file.", file=sys.stderr)
        sys.exit(1)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page()

        print(f"Navigating to {target_url}...")
        await page.goto(target_url, timeout=60000)

        print("Logging in...")
        await page.fill("#user_login", test_user)
        await page.fill("#user_pass", test_pass)
        await page.click("#wp-submit")
        await page.wait_for_load_state("networkidle")

        print("Checking Dashboard...")
        await page.goto("http://localhost:8081/wp-admin/index.php", timeout=60000)
        await page.wait_for_load_state("networkidle")
        if "Dashboard" not in await page.title():
            print("FAILED: Dashboard did not load.")
            await page.screenshot(path="dashboard_error.png")
            await browser.close()
            sys.exit(1)
        print("Dashboard loaded successfully.")
        await page.screenshot(path="dashboard_success.png")

        print("Checking Pages...")
        await page.goto("http://localhost:8081/wp-admin/edit.php?post_type=page")
        await page.wait_for_load_state("networkidle")
        pages_text = await page.inner_text("#the-list")
        for page_name in ["Home", "Contact Us", "Insights", "About Us", "Privacy Policy"]:
            if page_name in pages_text:
                print(f"Found page: {page_name}")
            else:
                print(f"MISSING page: {page_name}")
        
        print("Checking Media...")
        await page.goto("http://localhost:8081/wp-admin/upload.php")
        await page.wait_for_load_state("networkidle")
        media_text = await page.inner_text("body")
        if "No media items found" in media_text and "18" not in media_text:
            print("Media might be missing.")
        else:
            print("Media items are present.")

        print("Checking Themes...")
        await page.goto("http://localhost:8081/wp-admin/themes.php")
        await page.wait_for_load_state("networkidle")
        active_theme = await page.inner_text(".theme.active")
        print(f"Active theme: {active_theme.splitlines()[0]}")

        print("Checking Plugins...")
        await page.goto("http://localhost:8081/wp-admin/plugins.php")
        await page.wait_for_load_state("networkidle")
        plugins_text = await page.inner_text("#the-list")
        if "All-in-One WP Migration" in plugins_text:
            print("Plugins are present.")
        
        print("Checking Users...")
        await page.goto("http://localhost:8081/wp-admin/users.php")
        await page.wait_for_load_state("networkidle")
        print("Users page loaded successfully.")

        print("Checking Settings -> General...")
        await page.goto("http://localhost:8081/wp-admin/options-general.php")
        await page.wait_for_load_state("networkidle")
        siteurl = await page.input_value("#siteurl")
        home = await page.input_value("#home")
        print(f"Settings siteurl: {siteurl}")
        print(f"Settings home: {home}")

        await browser.close()

asyncio.run(run())
