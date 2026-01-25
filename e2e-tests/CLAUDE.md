# CLAUDE.md

## Setup

```bash
pnpm install
pnpm exec playwright install chromium
```

Create `.env`:

```text
FBS_TEST_USERNAME=halvargimnes
FBS_TEST_PASSWORD=<from confluence>
```

## Commands

```bash
pnpm test                    # all tests
pnpm test src/wiki.spec.ts   # single file
pnpm test:headed             # with browser visible
pnpm test:ui                 # interactive UI mode
pnpm exec playwright test --debug  # step through
```

## CI

Tests run on schedule and after relevant deploys. Failures alert to Slack.
