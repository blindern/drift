import {
  AlertEscalationBuilder,
  CheckGroup,
  RetryStrategyBuilder,
} from "checkly/constructs"
import { emailChannel } from "./alert-channels"

export const fbsGroup = new CheckGroup("fbs-check-group", {
  name: "foreningenbs.no",
  activated: true,
  muted: false,
  locations: ["eu-north-1"],
  environmentVariables: [],
  apiCheckDefaults: {},
  concurrency: 100,
  alertChannels: [emailChannel],
  alertEscalationPolicy: AlertEscalationBuilder.runBasedEscalation(1),
  retryStrategy: RetryStrategyBuilder.linearStrategy({
    baseBackoffSeconds: 30,
    maxRetries: 10,
    sameRegion: false,
  }),
  runParallel: true,
})
