#!/bin/bash
#
# Resource limiting wrapper for command execution
#
# Why is this in shell script? Because bash has a setrlimit() wrapper
# and is available on most Linux systems. If Perl was distributed with
# BSD::Resource included, we would happily use that instead, but it isn't.

MW_INCLUDE_STDERR=
MW_CPU_LIMIT=0
MW_CGROUP=
MW_MEM_LIMIT=0
MW_FILE_SIZE_LIMIT=0
MW_WALL_CLOCK_LIMIT=0

# Override settings
eval "$2"

if [ -n "$MW_INCLUDE_STDERR" ]; then
	exec 2>&1
fi

if [ "$MW_CPU_LIMIT" -gt 0 ]; then
	ulimit -t "$MW_CPU_LIMIT"
fi
if [ "$MW_MEM_LIMIT" -gt 0 ]; then
	if [ -n "$MW_CGROUP" ]; then
		# Create cgroup
		if ! mkdir -m 0700 "$MW_CGROUP"/$$; then
			echo "limit.sh: failed to create the cgroup." 1>&2
			exit 1
		fi
		echo $$ > "$MW_CGROUP"/$$/tasks
		if [ -n "$MW_CGROUP_NOTIFY" ]; then
			echo "1" > "$MW_CGROUP"/$$/notify_on_release
		fi
		# Memory
		echo $(($MW_MEM_LIMIT*1024)) > "$MW_CGROUP"/$$/memory.limit_in_bytes
		# Memory+swap
		echo $(($MW_MEM_LIMIT*1024)) > "$MW_CGROUP"/$$/memory.memsw.limit_in_bytes
	else
		ulimit -v "$MW_MEM_LIMIT"
	fi
else
	MW_CGROUP=""
fi
if [ "$MW_FILE_SIZE_LIMIT" -gt 0 ]; then
	ulimit -f "$MW_FILE_SIZE_LIMIT"
fi
if [ "$MW_WALL_CLOCK_LIMIT" -gt 0 -a -x "/usr/bin/timeout" ]; then
	/usr/bin/timeout $MW_WALL_CLOCK_LIMIT /bin/bash -c "$1"
	STATUS="$?"
	if [ "$STATUS" == 124 ]; then
		echo "limit.sh: timed out." 1>&2
	fi
else
	eval "$1"
	STATUS="$?"
fi

# Clean up cgroup
cleanup() {
	# First we have to move the current task into a "garbage" group, otherwise
	# the cgroup will not be empty, and attempting to remove it will fail with
	# "Device or resource busy"
	if [ -w "$MW_CGROUP"/tasks ]; then
		GARBAGE="$MW_CGROUP"
	else
		GARBAGE="$MW_CGROUP"/garbage-"$USER"
		if [ ! -e "$GARBAGE" ]; then
			mkdir -m 0700 "$GARBAGE"
		fi
	fi
	echo $BASHPID > "$GARBAGE"/tasks

	# Suppress errors in case the cgroup has disappeared due to a release script
	rmdir "$MW_CGROUP"/$$ 2>/dev/null
}

updateTaskCount() {
	# There are lots of ways to count lines in a file in shell script, but this
	# is one of the few that doesn't create another process, which would
	# increase the returned number of tasks.
	readarray < "$MW_CGROUP"/$$/tasks
	NUM_TASKS=${#MAPFILE[*]}
}

if [ -n "$MW_CGROUP" ]; then
	updateTaskCount

	if [ $NUM_TASKS -gt 1 ]; then
		# Spawn a monitor process which will continue to poll for completion
		# of all processes in the cgroup after termination of the parent shell
		(
			while [ $NUM_TASKS -gt 1 ]; do
				sleep 10
				updateTaskCount
			done
			cleanup
		) >&/dev/null < /dev/null &
		disown -a
	else
		cleanup
	fi
fi
exit "$STATUS"

