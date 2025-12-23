import { expect, test } from "@playwright/test"
import { loginAtSsp } from "../login-helpers"

test("can login to webdavcgi and see main page", async ({ page }) => {
  await page.goto("https://foreningenbs.no/filer/")
  await loginAtSsp(page)

  await page.waitForURL("https://foreningenbs.no/filer/")

  await expect(page.getByText("Foreningens dokumentarkiv")).toBeVisible()
})
