#!/bin/bash
set -eux

# 172.25.16.1 = web-1 (SAML-gated proxy), 172.25.16.18 = gatus
# bridge family since fbs0 traffic is L2-bridged and bypasses iptables (no br_netfilter)

nft delete table bridge pgadmin 2>/dev/null || true
nft -f - <<'EOF'
table bridge pgadmin {
  chain forward {
    type filter hook forward priority 0;
    ip daddr 172.25.16.57 tcp dport 80 ip saddr { 172.25.16.1, 172.25.16.18 } accept
    ip daddr 172.25.16.57 tcp dport 80 drop
  }
}
EOF
