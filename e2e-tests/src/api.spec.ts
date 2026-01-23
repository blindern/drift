import { test, expect } from "@playwright/test"

test("matmeny API returns data", async ({ request }) => {
  // Use dates relative to a known working date
  const response = await request.get(
    "https://foreningenbs.no/intern/api/matmeny?from=2024-05-21&to=2024-05-22",
  )
  expect(response.status()).toBe(200)
  const json = await response.json()
  expect(json[0]?.dishes?.[0]).toBeTruthy()
})
