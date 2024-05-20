# Checkly

Useful resources:

- https://www.checklyhq.com/docs/cli/
- https://checklyhq.com/docs
- https://www.checklyhq.com/docs/cli/npm-packages/

## Testing locally

```bash
pnpm install

# See https://foreningenbs.no/confluence/display/FBS/Kundedetaljer+Checkly
pnpm checkly login

# Load secrets.
pnpm checkly env pull .env

# Run tests.
pnpm checkly test --env-file=./.env
```

## Deploying changes

Automatically done when pushed to `main`.

Manual deploy:

```bash
pnpm checkly deploy
```

## Provisioned environment variables

These are already provisioned and remains as documentation.

```bash
pnpm checkly env add FBS_TEST_USERNAME halvargimnes
pnpm checkly env add FBS_TEST_PASSWORD --locked
```
