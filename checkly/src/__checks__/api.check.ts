import { ApiCheck, AssertionBuilder } from "checkly/constructs"
import { fbsGroup } from "../groups"

new ApiCheck("fbs-matmeny-api-check", {
  name: "Matmeny API",
  group: fbsGroup,
  degradedResponseTime: 10000,
  maxResponseTime: 20000,
  frequency: 30,
  request: {
    url: "https://foreningenbs.no/intern/api/matmeny?from=2024-05-21&to=2024-05-22",
    method: "GET",
    assertions: [
      AssertionBuilder.statusCode().equals(200),
      AssertionBuilder.jsonBody("$[0].dishes[0]").isNotNull(),
    ],
  },
  runParallel: true,
})
