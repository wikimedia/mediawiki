<?php

# Stub profiling functions

$haveProctitle=function_exists("setproctitle");
function wfProfileIn( $fn = '' ) {
	global $hackwhere, $haveProctitle;
	if ($haveProctitle) {
		$hackwhere[] = $fn;
		setproctitle($fn . ' ['.wfWikiID().']');
	}
}
function wfProfileOut( $fn = '' ) {
	global $hackwhere, $haveProctitle;
	if (!$haveProctitle)
		return;
	if (count($hackwhere))
		array_pop($hackwhere);
	if (count($hackwhere))
		setproctitle($hackwhere[count($hackwhere)-1] . ' ['.wfWikiID().']');
}
function wfGetProfilingOutput( $s, $e ) {}
function wfProfileClose() {}
$wgProfiling = false;

?>
