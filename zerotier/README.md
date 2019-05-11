# ZeroTier for FBS

ZeroTier is set up as VPN to connect our services and machines without
being dependant on global IP adresses. https://www.zerotier.com/

ZeroTier Network: 

Routes: 172.25.0.0/16

Auto-assigned IP range: 172.25.0.1-172.25.10.254

## Registered managed hosts

* athene
  * b31bfcca47
  * 172.25.30.63 (athene.zt.foreningenbs.no)
* p
  * 2a0b7d98cb
  * 172.25.187.67 (p.zt.foreningenbs.no)
* coreos-1
  * aa87dee842
  * 172.25.4.159 (coreos-1.zt.foreningenbs.no)

## Registered services

* ldap.zt.foreningenbs.no
* ldapmaster.zt.foreningenbs.no
* users-api.zt.foreningenbs.no

## Connecting to the ZeroTier network

* Install ZeroTier from https://www.zerotier.com/
* Request to join network `a84ac5c10a9c7522` (leave only "allow managed" checked)
* Log in to https://my.zerotier.com/network/a84ac5c10a9c7522 using the
  credentials given at https://foreningenbs.no/confluence/display/FBS/Kundeforhold+ZeroTier
* Check the "Auth?" box to grant access to the new member. Also give it a name
  and/or description so we know what/whos host it is.
* You should now be able to ping athene.zt.foreningenbs.no
