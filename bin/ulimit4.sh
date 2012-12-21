#!/bin/bash

if [ -e /sys/fs/cgroup/memory/mediawiki/job/ ]; then
	mkdir -m 0700 /sys/fs/cgroup/memory/mediawiki/job/$$
	echo $$ > /sys/fs/cgroup/memory/mediawiki/job/$$/tasks
	echo "1" > /sys/fs/cgroup/memory/mediawiki/job/$$/notify_on_release
	#memory
	echo $(($2*1024)) > /sys/fs/cgroup/memory/mediawiki/job/$$/memory.limit_in_bytes
	#memory+swap
	echo $(($2*1024)) > /sys/fs/cgroup/memory/mediawiki/job/$$/memory.memsw.limit_in_bytes
fi

ulimit -t $1 -v $2 -f $3
eval "$4"
