# E2E Tests

Playwright end-to-end tests for FBS services.

## Local Setup

### 1. Install dependencies

```bash
pnpm install
pnpm exec playwright install chromium
```

### 2. Configure credentials

Create `.env` file:

```
FBS_TEST_USERNAME=halvargimnes
FBS_TEST_PASSWORD=<password>
```

Get password from: https://foreningenbs.no/confluence/spaces/IT/pages/2033134/Kontoer+for+IT-gruppa

## Running Tests

```bash
# Run all tests
pnpm test

# Run with browser visible
pnpm test:headed

# Interactive UI mode
pnpm test:ui

# Run specific test file
pnpm test src/wiki.spec.ts
pnpm test src/intern.spec.ts

# Run with debug mode (step through)
pnpm exec playwright test --debug
```

## CI/CD

Tests run automatically:
- **Hourly**: All tests via scheduled workflow
- **On deploy**: Relevant tests after deploying confluence, webdavcgi, simplesamlphp, openldap

Failed tests send alerts to Slack.

## GitHub Secrets/Variables

```bash
# Test user username
gh variable set FBS_TEST_USERNAME --body "halvargimnes"

# Test user password
gh secret set FBS_TEST_PASSWORD

# Slack bot OAuth token (org-level secret, available to all repos)
gh secret set SLACK_BOT_TOKEN --org blindern --visibility all
```

## Slack App Setup

1. Go to https://api.slack.com/apps → Create New App → From scratch
2. App Name: "GitHub Actions"
3. OAuth & Permissions → Bot Token Scopes → Add `chat:write`
4. Install to Workspace → Copy Bot User OAuth Token (`xoxb-...`)
5. Set as org secret: `gh secret set SLACK_BOT_TOKEN --org blindern --visibility all`
6. Invite bot to alerts channel: `/invite @GitHub Actions`
