# CLAUDE.md

## Commands

All ansible commands must be run from within the `ansible/` directory.

```bash
# Deploy specific service to host
ansible-playbook site.yml -i hosts -l fcos-3 -t service-simplesamlphp

# Deploy all common config to host
ansible-playbook site.yml -i hosts -t base -l fcos-1

# Deploy everything
ansible-playbook site.yml -i hosts

# Run command on all hosts
ansible all -i hosts -m shell -a uptime
```

## Structure

- `site.yml` - Main playbook, defines which services run on which host
- `hosts` - Inventory (fcos-1, fcos-2, fcos-3)
- `roles/service-*` - Individual service definitions
- `host_vars/` - Per-host variables including deployer image refs

## Deployment

- Changes to `roles/service-*` require manual trigger via GitHub Actions
- Other changes deploy automatically via GitHub Actions
- Service images auto-deploy via deployer when built
