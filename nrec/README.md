# VMs on NREC

For details about NREC see https://docs.nrec.no/

We use Terraform to manage the resources on NREC.

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
generated API password provided on your first login to NREC.
You should also be able to reset your API password on https://access.nrec.no/

## Testing openstack commands

```bash
. .keystone.sh
openstack server list
```

## Running terraform

See the template `blindern.tf` for the infrastructure setup.

```bash
. .keystone.sh
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

The `/var/mnt/data` directory is a separate volume that survives
across instance recreations. All state that must be persisted
should be stored within this volume.

## Updating image for new provisions

```bash
./publish-fedora-coreos.sh
```

Edit the `fcos` variable in `blindern.tf`.
