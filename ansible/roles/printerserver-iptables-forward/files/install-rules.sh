#!/bin/bash
set -eux

# 172.25.10.2 = p.zt.foreningenbs.no
# 172.25.12.1 = fcos-1.zt.foreningenbs.no

iptables -t nat -I PREROUTING 1 -i ens3 -p tcp --dport 631 -j DNAT --to-destination 172.25.10.2:631
iptables -I FORWARD 1 -i ens3 -o fbs0 -p tcp --dport 631 -j ACCEPT
iptables -t nat -I POSTROUTING 1 -o fbs0 -p tcp --dport 631 -d 172.25.10.2 -j SNAT --to-source 172.25.12.1

iptables -t nat -I PREROUTING 1 -i ens3 -p tcp --dport 80 -j DNAT --to-destination 172.25.10.2:80
iptables -I FORWARD 1 -i ens3 -o fbs0 -p tcp --dport 80 -j ACCEPT
iptables -t nat -I POSTROUTING 1 -o fbs0 -p tcp --dport 80 -d 172.25.10.2 -j SNAT --to-source 172.25.12.1

iptables -t nat -I PREROUTING 1 -i ens3 -p tcp --dport 443 -j DNAT --to-destination 172.25.10.2:443
iptables -I FORWARD 1 -i ens3 -o fbs0 -p tcp --dport 443 -j ACCEPT
iptables -t nat -I POSTROUTING 1 -o fbs0 -p tcp --dport 443 -d 172.25.10.2 -j SNAT --to-source 172.25.12.1
