#!/bin/sh

runDump() {
	local ret
	# djvudump is faster than djvutoxml (now abandoned) as of version 3.5
	# https://sourceforge.net/p/djvu/bugs/71/
	"$DJVU_DUMP" file.djvu > dump
	ret="$?"
	if [ "$ret" -ne 0 ]; then
		echo "djvudump failed with exit code $ret" 1>&2
		rm -f dump
	fi
}
runTxt() {
	local ret
	# Text layer
	if ! "$DJVU_TXT" --detail=page file.djvu > txt; then
		rm -f txt
	fi
	ret="$?"
	if [ "$ret" -ne 0 ]; then
		echo "djvutxt failed with exit code $ret" 1>&2
		rm -f txt
	fi
}
if [ -n "$DJVU_DUMP" ]; then
	runDump
fi
if [ -n "$DJVU_TXT" ]; then
	runTxt
fi
exit 0
