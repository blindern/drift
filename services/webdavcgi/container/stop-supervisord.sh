#!/bin/bash
set -e

# adhere to the supervisord event protocol
# see http://supervisord.org/events.html#events

echo READY

read -r x
echo "$x" >>log.txt
echo -en "RESULT 2\nOK"

# stop supervisord
kill "$(cat /supervisord.pid)"
