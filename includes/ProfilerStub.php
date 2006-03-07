<?php

# Stub profiling functions

$haveProctitle=function_exists("setproctitle");
function wfProfileIn( $fn = '' ) {
	global $hackwhere, $wgDBname, $haveProctitle;
	if ($haveProctitle) {
		$hackwhere[] = $fn;
		setproctitle($fn . " [$wgDBname]");
	}
}
function wfProfileOut( $fn = '' ) {
	global $hackwhere, $wgDBname, $haveProctitle;
	if (!$haveProctitle)
		return;
	if (count($hackwhere))
		array_pop($hackwhere);
	if (count($hackwhere))
		setproctitle($hackwhere[count($hackwhere)-1] . " [$wgDBname]");
}
function wfGetProfilingOutput( $s, $e ) {}
function wfProfileClose() {}
function wfLogProfilingData() {}

?>
