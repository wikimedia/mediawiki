#!/bin/bash

if [ "$1" -gt 0 ]; then
	ulimit -t "$1"
fi
if [ "$2" -gt 0 ]; then
	ulimit -v "$2"
fi
if [ "$3" -gt 0 ]; then
	ulimit -f "$3"
fi
if [ "$4" -gt 0 -a -x "/usr/bin/timeout" ]; then
	/usr/bin/timeout $4 /bin/bash -c "$5"
	STATUS="$?"
	if [ "$STATUS" == 124 ]; then
		echo "ulimit5.sh: timed out." 1>&2
	fi
	exit "$STATUS"
else
	eval "$5"
fi
