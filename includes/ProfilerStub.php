<?php
/**
 * Stub profiling functions
 * @addtogroup Profiler
 */

/** backward compatibility */
$wgProfiling = false;

/** is setproctitle function available ? */
$haveProctitle = function_exists( 'setproctitle' );

/**
 * Begin profiling of a function
 * @param string $fn
 */
function wfProfileIn( $fn = '' ) {
	global $hackwhere, $wgDBname, $haveProctitle;
	if( $haveProctitle ){
		$hackwhere[] = $fn;
		setproctitle( $fn . " [$wgDBname]" );
	}
}

/**
 * Stop profiling of a function
 * @param string $fn
 */
function wfProfileOut( $fn = '' ) {
	global $hackwhere, $wgDBname, $haveProctitle;
	if( !$haveProctitle )
		return;
	if( count( $hackwhere ) )
		array_pop( $hackwhere );
	if( count( $hackwhere ) )
		setproctitle( $hackwhere[count( $hackwhere )-1] . " [$wgDBname]" );
}

/**
 * Does nothing, just for compatibility 
 */
function wfGetProfilingOutput( $s, $e ) {}

/**
 * Does nothing, just for compatibility 
 */
function wfProfileClose() {}
