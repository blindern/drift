import { expect, test } from "@playwright/test"
import { loginAtSsp } from "./login-helpers"

test("can login at foreningenbs.no (intern)", { tag: ["@intern", "@simplesamlphp", "@openldap"] }, async ({ page }) => {
  const username = process.env.FBS_TEST_USERNAME
  if (!username) {
    throw new Error("Missing FBS_TEST_USERNAME")
  }

  const response = (await page.goto("https://foreningenbs.no"))!

  await page.waitForSelector(".index-matmeny", { state: "visible" })
  expect(
    response.status(),
    "should respond with correct status code",
  ).toBeLessThan(400)

  await page.locator('a:text("Logg inn")').click()

  await loginAtSsp(page)

  await expect(
    page.getByText(`Du er innlogget som ${username}`),
  ).toBeVisible()
})
