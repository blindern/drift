# drift

This Git-repo describes the setup of some our services.
For more details, see
https://foreningenbs.no/confluence/display/FBS/IT-gruppa

## Design principles of our setup

The intention of these principles is to keep a simple and easily
maintained setup with few components. We might consider moving
to something more complex later (such as Kubernetes) if we need
more HA capabilities or a platform to control services deployment.

- [Terraform](./uh-iaas/) is used to provision VMs in
  [UH-IaaS](http://docs.uh-iaas.no/en/latest/index.html). Only the minimal
  instance setup is done in Terraform / cloud-init, as any change to this
  will recreate the instances.
  - All data is stored on a separate volume mounted at `/data`, allowing us
    to more easily recreate instances if needed, and to have a single
    location to backup.
  - As host OS we use CoreOS Container Linux which is self-updating.
    This will be replaced later when CoreOS Container Linux gets
    deprecated and replaced by Fedore CoreOS.
- [Ansible](./ansible/) is used to configure the hosts, including the
  mapping of which services run on the various hosts. When recreating a
  VM instance, Ansible should perform all required tasks for the host
  to be running properly in short time. Some manual tasks for moving data
  might be needed.
- All hosts use ZeroTier to be in the same L2 network. A bridge named `fbs0`
  and a docker network is set up to allow Docker containers to use IPs
  in this network.
  Each host has its own internal IP-range so ad-hoc containers can get
  IPs in this network.
- All services run as Docker containers. The containers gets a static
  internal IPs and all containers can reach any other service on any host.
- Services should be reached only by using its hostname, such as
  `users-api.zt.foreningenbs.no`. Published services are only published
  on the host they are running.

## Network details

- ZeroTier is set up to use:
  - 172.25.0.0/16
- Auto-assign range for normal ZeroTier clients:
  - 172.25.0.0/23 (172.25.0.1 - 172.25.1.254)
- Hosts:
  - 172.25.10.1 athene.zt.foreningenbs.no
  - 172.25.10.2 p.zt.foreningenbs.no
  - 172.25.11.1 coreos-1.zt.foreningenbs.no
  - 172.25.11.2 coreos-2.zt.foreningenbs.no
  - 172.25.11.3 coreos-3.zt.foreningenbs.no
  - 172.25.11.4 coreos-4.zt.foreningenbs.no
- IP-ranges for ad-hoc containers with Docker:
  - 172.25.21.0/24 coreos-1
  - 172.25.22.0/24 coreos-2
  - 172.25.22.0/24 coreos-3
  - 172.25.22.0/24 coreos-4
- IP-range used for allocation of services:
  - 172.25.16.0/22 (172.25.16.0-172.25.19.255)
- Allocated services:
  - 172.25.16.1 web-1.zt.foreningenbs.no (not set up yet)
  - 172.25.16.2 users-api.zt.foreningenbs.no
  - 172.25.16.3 intern-backend.zt.foreningenbs.no (not set up yet)
  - 172.25.16.4 intern-frontend.zt.foreningenbs.no (not set up yet)
  - 172.25.16.5 okoreports-backend.zt.foreningenbs.no
  - 172.25.16.6 okoreports-frontend.zt.foreningenbs.no
  - 172.25.16.7 smaabruket-availability-api.zt.foreningenbs.no
  - 172.25.16.8 confluence.zt.foreningenbs.no
  - 172.25.16.9 slack-invite-automation.zt.foreningenbs.no
  - 172.25.16.10 dugnaden.zt.foreningenbs.no (not set up yet)
  - 172.25.16.11 phpldapadmin.zt.foreningenbs.no
  - 172.25.16.12 phpmyadmin.zt.foreningenbs.no
  - 172.25.16.13 simplesamlphp.zt.foreningenbs.no
  - 172.25.16.14 nginx-front-1.zt.foreningenbs.no
  - 172.25.16.30 ldap-master.zt.foreningenbs.no
  - 172.25.16.31 ldap-slave.zt.foreningenbs.no
  - 172.25.16.40 mysql-1.zt.foreningenbs.no
  - 172.25.16.41 mongodb-1.zt.foreningenbs.no
  - 172.25.16.42 postgresql-1.zt.foreningenbs.no
  - 172.25.16.43 mysql-2.zt.foreningenbs.no

### Public web

The service `nginx-front-1` is published at port 80 and 443 and acts
as the reverse proxy for public traffic. DNS-entries must be
set up for this for the physical host this is running at:

- foreningenbs.no: 158.39.48.92 (coreos-2)
- www.foreningenbs.no: 158.39.48.92 (coreos-2)

This service also keeps our Let's Encrypt certificates up-to-date.

### ZeroTier details

To manage the network, log in to https://my.zerotier.com/network/a84ac5c10a9c7522
using the credentials stored at
https://foreningenbs.no/confluence/display/FBS/Kundeforhold+ZeroTier

To add a new ad-hoc client to the network:

- Install ZeroTier from https://www.zerotier.com/ or use
  https://github.com/henrist/zerotier-one-docker.
- Request to join network `a84ac5c10a9c7522` (leave only "allow managed" checked).
- Authorize the client in the ZeroTier dashboard linked above.
- Give the client a description so we keep track of what is connected.
- You should now be able to ping e.g. coreos-1.zt.foreningenbs.no.

## Encryption in this repo

This repo uses [git-crypt](https://github.com/AGWA/git-crypt)
to encrypt sensitive files.

In addition, some files are encrypted using
[Ansible Vault](https://docs.ansible.com/ansible/latest/user_guide/vault.html).
The encryption key used for this is stored in this repo. The intention
of using Ansible Vault is to avoid having plain text files in local
working directories, which is the default behaviour with git-crypt.
