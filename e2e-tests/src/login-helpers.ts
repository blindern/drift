import { Page } from "@playwright/test"

export async function loginAtSsp(page: Page) {
  await page
    .locator('button:text("Brukernavn og passord for foreningsbrukeren din")')
    .click()

  const username = process.env["FBS_TEST_USERNAME"]
  if (!username) {
    throw new Error("Missing FBS_TEST_USERNAME")
  }

  const password = process.env["FBS_TEST_PASSWORD"]
  if (!password) {
    throw new Error("Missing FBS_TEST_PASSWORD")
  }

  await page.locator("#username").fill(username)
  await page.locator("#password").fill(password)
  await page.locator("button[type=submit]").click()
}
