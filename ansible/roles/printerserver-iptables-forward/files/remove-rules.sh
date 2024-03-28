#!/bin/bash
set -eux

iptables-save | grep -v printerserver-iptables-forward | iptables-restore
