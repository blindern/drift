import { BrowserCheck } from "checkly/constructs"
import * as path from "path"
import { emailChannel } from "../alert-channels"
import { fbsGroup } from "../groups"

new BrowserCheck("intern-login-browser-check", {
  name: "Kan logge inn på foreningenbs.no",
  alertChannels: [emailChannel],
  group: fbsGroup,
  code: {
    entrypoint: path.join(__dirname, "intern.spec.ts"),
  },
  runParallel: true,
})
