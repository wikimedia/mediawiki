<?php
if ( !defined( 'MEDIAWIKI' ) || !defined( 'SELENIUMTEST' ) ) {
	echo "This script cannot be run standalone";
	exit( 1 );
}

class SeleniumTestConsoleLogger {
	public function __construct() {
		// Prepare testsuite for immediate output
		@ini_set( 'zlib.output_compression', 0 );
		@ini_set( 'implicit_flush', 1 );
		for ( $i = 0; $i < ob_get_level(); $i++ ) {
			ob_end_flush();
		}
		ob_implicit_flush( 1 );
	}

	public function write( $message, $mode = false ) {
		$out = '';
		// if ( $mode == MW_TESTLOGGER_RESULT_OK ) $out .= '<font color="green">';
		$out .= htmlentities( $message );
		// if ( $mode == MW_TESTLOGGER_RESULT_OK ) $out .= '</font>';
		if ( $mode != MW_TESTLOGGER_CONTINUE_LINE ) {
			$out .= "\n";
		}

		echo $out;
	}
}