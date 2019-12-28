# Ansible setup

We use Ansible to provision the contents on our VMs. See [uh-iaas](../uh-iaas/)
directory for details on setting up the VMs we use.

## Getting started

Install Ansible locally using your package manager. E.g. on Arch:
`pacman -S ansible`

The file `hosts` holds our inventory. The file `site.yml` is the main playbook
holding all the infrastructure.

Make sure you always run this on the latest commit in Git, so you do not
rollback changes.

Example runs:

```bash
# Show uptime on all hosts.
ansible all -i hosts -m shell -a uptime

# Run the site.yml playbook for a specific tag on a specific host.
ansible-playbook site.yml -i hosts -t coreos-python -l coreos-1

# Run the site.yml playbook for everything.
ansible-playbook site.yml -i hosts

# Run a more complex command on all hosts.
ansible all -i hosts -m shell -a 'cat /proc/cpuinfo | grep "core id" | wc -l'
```

## Steps for adding new hosts

1. Add a DNS record in Domeneshop and add this entry to [hosts](./hosts).
2. Bootstrap the node using Ansible.
3. Approve the host in the ZeroTier Dashboard.
