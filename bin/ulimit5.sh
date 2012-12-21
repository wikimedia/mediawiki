#!/bin/bash

if [ "$1" -gt 0 ]; then
	ulimit -t "$1"
fi
if [ "$2" -gt 0 ]; then
	if [ -e /sys/fs/cgroup/memory/mediawiki/job/ ]; then
		mkdir -m 0700 /sys/fs/cgroup/memory/mediawiki/job/$$
		echo $$ > /sys/fs/cgroup/memory/mediawiki/job/$$/tasks
		echo "1" > /sys/fs/cgroup/memory/mediawiki/job/$$/notify_on_release
		#memory
		echo $(($2*1024)) > /sys/fs/cgroup/memory/mediawiki/job/$$/memory.limit_in_bytes
		#memory+swap
		echo $(($2*1024)) > /sys/fs/cgroup/memory/mediawiki/job/$$/memory.memsw.limit_in_bytes
	fi
	ulimit -v "$2"
fi
if [ "$3" -gt 0 ]; then
	ulimit -f "$3"
fi
if [ "$4" -gt 0 ]; then
	timeout $4 /bin/bash -c "$5"
	STATUS="$?"
	if [ "$STATUS" == 124 ]; then
		echo "ulimit5.sh: timed out." 1>&2
	fi
	exit "$STATUS"
else
	eval "$5"
fi
