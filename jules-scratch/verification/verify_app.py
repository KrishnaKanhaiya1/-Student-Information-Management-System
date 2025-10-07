from playwright.sync_api import sync_playwright, expect

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    try:
        page.goto("http://localhost:8000/index.php")

        # Log in
        page.get_by_placeholder("Username").fill("admin")
        page.get_by_placeholder("Password").fill("admin123")
        page.get_by_role("button", name="Login").click()
        expect(page.get_by_text("Welcome, admin!")).to_be_visible()

        # Navigate to view students
        page.get_by_role("link", name="View Students").click()
        expect(page.get_by_role("heading", name="All Students")).to_be_visible()

        page.screenshot(path="jules-scratch/verification/verification.png")
        print("Screenshot saved to jules-scratch/verification/verification.png")

    except Exception as e:
        print(f"An error occurred: {e}")
        page.screenshot(path="jules-scratch/verification/error.png")

    finally:
        browser.close()

with sync_playwright() as playwright:
    run(playwright)