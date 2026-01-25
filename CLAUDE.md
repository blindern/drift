# CLAUDE.md

## Overview

Infrastructure-as-code repo for Foreningenbs (Blindern Studenterhjem). VMs on NREC (Norwegian Research and Education Cloud), configured with Ansible, running Docker containers connected via ZeroTier L2 network.

## Architecture

```
nrec/           - Terraform for NREC VMs (Fedora CoreOS hosts), manual deploy
ansible/        - Host configuration and service deployment
  site.yml      - Main playbook, shows which services run where
  roles/        - service-* roles define individual services
services/       - Dockerfiles for services without own repo
e2e-tests/      - Playwright tests for production services
gcp/            - Google Cloud Terraform (manual deploy)
```

**Hosts:** fcos-1, fcos-2, fcos-3 (see `ansible/hosts`)
**Data:** `/var/mnt/data` on each host (survives VM recreation)
**Network:** ZeroTier 172.25.0.0/16, services get static IPs and are reached by hostname

## Adding a new service

When adding a new service with a ZeroTier IP:
1. Allocate IP in `README.md` (next available in 172.25.16.x range)
2. Create DNS record: `./dns/manage-record.sh set <name>.zt <ip> --apply`
3. Add Ansible role in `ansible/roles/service-<name>/`
4. Add to `ansible/site.yml`

## Important: README.md tracking

README.md tracks items that may need updates:
- GitHub workflow badges (update when adding/removing workflows)
- Host IP allocations
- Service IP allocations (when adding new services)

## Deployment

Most services auto-deploy via [deployer](https://github.com/blindern/deployer) when images are built.

## Encryption

- **git-crypt**: Sensitive files encrypted in repo
- **Ansible Vault**: Additional encryption for secrets (key stored in repo)
