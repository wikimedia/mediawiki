#!/bin/sh

# Get parameters from environment

export DJVU_DUMP="${DJVU_DUMP:-djvudump}"
export DJVU_TXT="${DJVU_TXT:-djvutxt}"

runDump() {
	# djvudump is faster than djvutoxml (now abandoned) as of version 3.5
	# https://sourceforge.net/p/djvu/bugs/71/
	"$DJVU_DUMP" file.djvu > dump
}

runTxt() {
	# Text layer
	"$DJVU_TXT" \
		--detail=page \
		file.djvu > txt
	# Store exit code so we can use it later
	echo $? > txt_exit_code
}

if [ -x "$DJVU_DUMP" ]; then
	runDump
fi

if [ -x "$DJVU_TXT" ]; then
	runTxt
fi
