<?php

# Stub profiling functions

function wfProfileIn( $fn = '' ) {
	global $hackwhere, $wgDBname;
	$hackwhere[] = $fn;
	if (function_exists("setproctitle"))
		setproctitle($fn . " [$wgDBname]");
}
function wfProfileOut( $fn = '' ) {
	global $hackwhere, $wgDBname;
	if (count($hackwhere))
		array_pop($hackwhere);
	if (function_exists("setproctitle") && count($hackwhere))
		setproctitle($hackwhere[count($hackwhere)-1] . " [$wgDBname]");
}
function wfGetProfilingOutput( $s, $e ) {}
function wfProfileClose() {}
function wfLogProfilingData() {}

?>
