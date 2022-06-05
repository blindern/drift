# VMs on UH-IaaS

See https://github.com/cybernetisk/drift/tree/master/uh-iaas
for related work.

For details about UH-IaaS see
http://docs.uh-iaas.no/en/latest/api.html
and the related pages.

We use Terraform to manage the resources on UH-IaaS.

## Setting up dependencies

Installing requirements on Ubuntu:

```bash
sudo apt install python-pip
pip install python-openstackclient
```

Terraform need manual setup. See
https://learn.hashicorp.com/terraform/getting-started/install.html
and https://askubuntu.com/a/983352

## Configuring credentials

Create a file name `.keystone-private.sh` using this template:

```bash
export OS_USERNAME=REPLACE-ME
export OS_PASSWORD=REPLACE-ME
```

The username should be your email address, and the password should be a
generated API password provided on your first login to UH-IaaS.
You should also be able to reset your API password on https://access.uh-iaas.no/

## Testing openstack commands

```bash
. .keystone.sh
openstack server list
```

## Running terraform

See the template `blindern.tf` for the infrastructure setup.

```bash
. .keystone.sh
make convert-config
make convert-fcos-config
terraform init
terraform apply
```

The `terraform.tfstate` file must be commited and pushed when changed.

## Configuring hosts from initial state

The Terraform setup in this directory is only for creating VMs and
their core setup, not for setting up the contents of the VMs.

Provision the host using [ansible](../ansible/) if it has been recreated
or a new host is added.

## Data folder

The `/data` directory is a volume that contains the `drift` repo checked out.
It also holds the encryption key.

All state that must be persisted should be stored within this volume, as only
this will be persisted across instance recreations.

## Updating image for new provisions

```bash
./publish-fedora-coreos.sh
```
