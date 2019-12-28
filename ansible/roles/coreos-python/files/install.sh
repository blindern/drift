#!/bin/bash
set -eu -o pipefail

# See http://pypy.org/download.html
# (Linux x86-64 binary (64bit, built on CentOS6))
# The PYPY_VERSION is injected by the Ansible task.
PYPY_URL=https://bitbucket.org/pypy/pypy/downloads/${PYPY_VERSION}-linux64.tar.bz2

# Cleanup any previous install.
rm -rf /opt/pypy
mkdir -p /opt/pypy

wget -O- "$PYPY_URL" | tar --strip-components=1 -C /opt/pypy -xjf -

mkdir -p /opt/pypy/lib
[ -f /lib64/libncurses.so.6.1 ] && ln -snf /lib64/libncurses.so.6.1 /opt/pypy/lib/libtinfo.so.5

mkdir -p /opt/bin
cat >/opt/bin/python <<'EOF'
#!/bin/bash
LD_LIBRARY_PATH=/opt/pypy/lib:$LD_LIBRARY_PATH exec /opt/pypy/bin/pypy3 "$@"
EOF

chmod +x /opt/bin/python
python --version

# Setup pip.
python -m ensurepip

ln -s /opt/pypy/bin/pip3 /opt/bin/pip3

pip3 --version

# Use a special file to hold current version to compare
# for updates. Only write this after we are sure we have
# a working installation (command above success).
echo "$PYPY_VERSION" >/opt/pypy/.version
