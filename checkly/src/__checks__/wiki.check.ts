import { BrowserCheck } from "checkly/constructs"
import * as path from "path"
import { emailChannel } from "../alert-channels"
import { fbsGroup } from "../groups"

new BrowserCheck("wiki-can-login-browser-check", {
  name: "Kan nå beboersiden på wiki",
  alertChannels: [emailChannel],
  group: fbsGroup,
  playwrightConfig: {
    timeout: 120000,
    use: {
      viewport: { width: 1280, height: 720 },
      locale: "nb-NO",
    },
  },
  code: {
    entrypoint: path.join(__dirname, "wiki.spec.ts"),
  },
  runParallel: true,
})
