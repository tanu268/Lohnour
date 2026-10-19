import asyncio
from playwright.async_api import async_playwright

async def run():
    async with async_playwright() as p:
        print("Launching browser...")
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page()

        print("Navigating to wp-admin...")
        await page.goto("http://localhost:8081/wp-admin", timeout=60000)

        print("Logging in...")
        await page.fill("#user_login", "lohnouradmin")
        await page.fill("#user_pass", "MigrationTempPassword123!")
        await page.click("#wp-submit")
        await page.wait_for_load_state("networkidle")

        print("Navigating to AI1WM Import...")
        await page.goto("http://localhost:8081/wp-admin/admin.php?page=ai1wm_import", timeout=60000)
        await page.wait_for_load_state("networkidle")

        print("Triggering file upload...")
        file_input = page.locator("input[type='file']")
        await file_input.set_input_files(r"D:\LohnourPro_Migration\Website\Backup\localhost-lohnourpro-03-Oct-2026.wpress")

        print("Waiting for proceed modal...")
        try:
            # The button text is "PROCEED >"
            proceed_btn = page.get_by_role("button", name="PROCEED >")
            await proceed_btn.wait_for(state="visible", timeout=300000) # 5 minutes for upload
            print("Clicking proceed...")
            await proceed_btn.click()
        except:
            print("Proceed button not found, maybe taking a screenshot of the popup...")
            await page.screenshot(path="import_popup_retry.png")
            await browser.close()
            return

        print("Waiting for import to finish (this will take a while)...")
        try:
            finish_btn = page.get_by_role("button", name="FINISH")
            await finish_btn.wait_for(state="visible", timeout=300000) # 5 minutes
            print("Import successful! Clicking finish...")
            await finish_btn.click()
        except:
            print("Import finish button not found. Taking screenshot...")
            await page.screenshot(path="import_result.png")
            
        print("Done!")
        await browser.close()

asyncio.run(run())
