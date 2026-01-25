# CLAUDE.md

Terraform deploys here are manual from local machines.

## Commands

```bash
# Set up credentials (create .keystone-private.sh first)
. .keystone.sh

# Convert Fedora CoreOS config
make convert-fcos-config

# Apply Terraform
terraform init
terraform apply

# Commit terraform.tfstate after changes
```

## Credentials

Create `.keystone-private.sh`:

```bash
export OS_USERNAME=your-email
export OS_PASSWORD=your-api-password
```

Get API password from https://access.nrec.no/
