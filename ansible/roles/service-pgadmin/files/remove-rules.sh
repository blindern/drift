#!/bin/bash
set -eux

nft delete table bridge pgadmin 2>/dev/null || true
