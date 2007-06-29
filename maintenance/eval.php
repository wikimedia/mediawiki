<?php
/**
 * PHP lacks an interactive mode, but this can be very helpful when debugging.
 * This script lets a command-line user start up the wiki engine and then poke
 * about by issuing PHP commands directly.
 *
 * Unlike eg Python, you need to use a 'return' statement explicitly for the
 * interactive shell to print out the value of the expression. Multiple lines
 * are evaluated separately, so blocks need to be input without a line break.
 * Fatal errors such as use of undeclared functions can kill the shell.
 *
 * To get decent line editing behavior, you should compile PHP with support
 * for GNU readline (pass --with-readline to configure).
 *
 * @addtogroup Maintenance
 */

$wgForceLoadBalancing = (getenv('MW_BALANCE') ? true : false);
$wgUseNormalUser = (getenv('MW_WIKIUSER') ? true : false);
if (getenv('MW_PROFILING')) {
	define('MW_CMDLINE_CALLBACK', 'wfSetProfiling');
}
function wfSetProfiling() { $GLOBALS['wgProfiling'] = true; }

$optionsWithArgs = array( 'd' );

/** */
require_once( "commandLine.inc" );

if ( isset( $options['d'] ) ) {
	$d = $options['d'];
	if ( $d > 0 ) {
		$wgDebugLogFile = '/dev/stdout';
	}
	if ( $d > 1 ) {
		foreach ( $wgLoadBalancer->mServers as $i => $server ) {
			$wgLoadBalancer->mServers[$i]['flags'] |= DBO_DEBUG;
		}
	}
	if ( $d > 2 ) {
		$wgDebugFunctionEntry = true;
	}
}


while ( ( $line = readconsole( '> ' ) ) !== false ) {
	$val = eval( $line . ";" );
	if( is_null( $val ) ) {
		echo "\n";
	} elseif( is_string( $val ) || is_numeric( $val ) ) {
		echo "$val\n";
	} else {
		var_dump( $val );
	}
	if ( function_exists( "readline_add_history" ) ) {
		readline_add_history( $line );
	}
}

print "\n";


