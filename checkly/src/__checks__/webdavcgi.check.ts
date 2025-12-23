import { BrowserCheck } from "checkly/constructs"
import * as path from "path"
import { emailChannel } from "../alert-channels"
import { fbsGroup } from "../groups"

new BrowserCheck("webdavcgi-browser-check", {
  name: "webdavcgi",
  alertChannels: [emailChannel],
  group: fbsGroup,
  testOnly: true,
  playwrightConfig: {
    timeout: 120000,
    use: {
      viewport: { width: 1280, height: 720 },
      locale: "nb-NO",
    },
  },
  code: {
    entrypoint: path.join(__dirname, "webdavcgi.spec.ts"),
  },
  runParallel: true,
  tags: ["webdavcgi"],
})
