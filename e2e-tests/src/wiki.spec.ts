import { expect, test } from "./fixtures"
import { loginAtSsp } from "./login-helpers"

test("can login to wiki and see main page", { tag: "@confluence" }, async ({ page }) => {
  await page.goto("https://foreningenbs.no/confluence/")
  await page.locator("#login-link").click()

  await loginAtSsp(page)

  await page.waitForURL("https://foreningenbs.no/confluence/")

  await expect(
    page.getByRole("heading", { name: "Siste endringer på wiki" }),
  ).toBeVisible()
})
