#!/bin/sh
for SCRIPT in _bluespice/pre-autoload-dump.d/*
do
	if [ -f $SCRIPT -a -x $SCRIPT ]
	then
		echo "Running $SCRIPT"
		$SCRIPT
	fi
done