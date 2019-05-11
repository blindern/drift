# UH-IaaS

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

`ct` is used to convert between the CoreOS config formats:

```bash
./download-ct.sh
```

## Configuring credentials

Create a file name `.keystone-private.sh` using this template:

```bash
export OS_USERNAME=REPLACE-ME
export OS_PASSWORD=REPLACE-ME
```

## Testing openstack commands

```bash
. .keystone.sh
openstack server list
```

## Running terraform

See the template `blindern.tf` for the infrastructure setup.

```bash
. .keystone.sh
./convert-config.sh
terraform init
terraform apply
```

The `terraform.tfstate` file must be commited and pushed when changed.

## Custom setup

This is a one-shot setup you most probably never need to care about.
But if you do manual steps that are not automated, update this part!

### Joining zerotier network

```bash
docker exec zerotier-one zerotier-cli join a84ac5c10a9c7522
```

The instance was manually approved and named in ZeroTier dashboard,
and given a DNS record for coreos-1.zt.foreningenbs.no.

## Updating CoreOS instance template

```bash
./publish-coreos.sh
```

## Data folder

The `/data` directory is a volume that contains the `drift` repo checked out.
It also holds the encryption key.
