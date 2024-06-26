import { defineConfig } from "checkly"
import {
  AlertEscalationBuilder,
  RetryStrategyBuilder,
} from "checkly/constructs"

// See https://www.checklyhq.com/docs/cli/project-structure/

const config = defineConfig({
  projectName: "FBS",
  logicalId: "fbs",
  repoUrl: "https://github.com/blindern/drift",
  checks: {
    frequency: 120,
    locations: ["eu-north-1"],
    runtimeId: "2024.02",
    checkMatch: "**/__checks__/**/*.check.ts",
    playwrightConfig: {
      timeout: 30000,
      use: {
        viewport: { width: 1280, height: 720 },
        locale: "nb-NO",
      },
    },
    browserChecks: {
      testMatch: "**/__checks__/**/*.spec.ts",
    },
  },
  cli: {
    runLocation: "eu-north-1",
    reporters: ["list"],
  },
})

export default config
