# Ansible setup

We use Ansible to provision the contents on our VMs. See [nrec](../nrec/)
directory for details on setting up the VMs we use.

Some services are automatically deployed using Ansible when they
are built. Other changes must be deployed maually.
See https://github.com/blindern/deployer

## Automatic deployment

Some services are deployed automatically using Ansible when they
are built, by using the [deployer](https://github.com/blindern/deployer) tool.
These must be listed in [services.json](roles/service-deployer/files/services.json).

Most other changes to this directory is also deployed automatically
by triggering the deployer tool from a few GitHub Actions in this repo.
See the GitHub actions for more details.

Deploy for changes to `roles/service-*` directories must be triggered
manually at https://github.com/blindern/drift/actions/workflows/ansible-services.yml
This is to avoid race conditions when changing multiple things at once.

## Getting started

Install Ansible locally using your package manager. E.g. on Arch:
`pacman -S ansible`

The file `hosts` holds our inventory. The file `site.yml` is the main playbook
holding all the infrastructure.

Make sure you always run this on the latest commit in Git, so you do not
rollback changes.

### Regular use-cases

#### Update a service

Use the tag specified in `site.yml` and limit to the host the
service is running on (see the `site.yml` file):

```bash
ansible-playbook site.yml -i hosts -l fcos-3 -t service-simplesamlphp
```

### Other ansible commands

Example runs:

```bash
# Show uptime on all hosts.
ansible all -i hosts -m shell -a uptime

# Run the site.yml playbook for a specific tag on a specific host.
ansible-playbook site.yml -i hosts -t base -l fcos-1

# Run the site.yml playbook for everything.
ansible-playbook site.yml -i hosts

# Run a more complex command on all hosts.
ansible all -i hosts -m shell -a 'cat /proc/cpuinfo | grep "core id" | wc -l'
```

## Steps for adding new hosts

1. Add a DNS record in Domeneshop and add this entry to [hosts](./hosts).
2. Bootstrap the node using Ansible.
3. Approve the host in the ZeroTier Dashboard.
