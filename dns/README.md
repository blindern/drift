# DNS Management

Scripts for managing DNS records at Domeneshop for foreningenbs.no.

## Setup

Create a `.env` file in this directory with your API credentials:

```bash
DOMENESHOP_TOKEN=your_token
DOMENESHOP_SECRET=your_secret
```

Get credentials from https://foreningenbs.no/confluence/display/FBS/Kundedetaljer+Domeneshop
combined with https://domene.shop/admin?view=api

## Scripts

### manage-record.sh

Manage ZeroTier DNS records (`*.zt.foreningenbs.no`).

```bash
./manage-record.sh list                        # List all *.zt records
./manage-record.sh get <hostname>              # Show specific record
./manage-record.sh set <hostname> <ip>         # Create or update (dry-run)
./manage-record.sh set <hostname> <ip> --apply # Actually make changes
./manage-record.sh delete <hostname> --apply   # Delete (with confirmation)
./manage-record.sh delete <hostname> --apply --force  # Delete without confirmation
```

Safety constraints:
- Only operates on records ending in `.zt`
- Validates IP is in `172.25.x.x` range
- Dry-run by default (use `--apply` to execute)
- Errors if hostname has multiple A records

### update-snapshot.sh

Updates `records-foreningenbs.no-snapshot.json` with current DNS state.
Runs daily via GitHub Actions.

```bash
./update-snapshot.sh
```
