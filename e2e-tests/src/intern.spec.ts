import { expect, test } from "@playwright/test"
import { loginAtSsp } from "./login-helpers"

test("can login at foreningenbs.no (intern)", async ({ page }) => {
  const response = (await page.goto("https://foreningenbs.no"))!

  await page.waitForSelector(".index-matmeny", { state: "visible" })
  expect(
    response.status(),
    "should respond with correct status code",
  ).toBeLessThan(400)

  await page.locator('a:text("Logg inn")').click()

  await loginAtSsp(page)

  await expect(
    page.getByText(`Du er innlogget som ${process.env.FBS_TEST_USERNAME}`),
  ).toBeVisible()
})
