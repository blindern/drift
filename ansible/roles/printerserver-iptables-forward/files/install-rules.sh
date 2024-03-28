#!/bin/bash
set -eux

# 172.25.10.2 = p.zt.foreningenbs.no
# 172.25.12.1 = fcos-1.zt.foreningenbs.no

for port in 80 443 631; do
  iptables -t nat -I PREROUTING 1 -i ens3 -p tcp --dport $port -j DNAT --to-destination 172.25.10.2:$port -m comment --comment printerserver-iptables-forward
  iptables -I FORWARD 1 -i ens3 -o fbs0 -p tcp --dport $port -j ACCEPT -m comment --comment printerserver-iptables-forward
  iptables -t nat -I POSTROUTING 1 -o fbs0 -p tcp --dport $port -d 172.25.10.2 -j SNAT --to-source 172.25.12.1 -m comment --comment printerserver-iptables-forward
done
