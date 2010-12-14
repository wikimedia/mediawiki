<?php

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
		// if ( $mode == SeleniumTestSuite::RESULT_OK ) $out .= '<font color="green">';
		$out .= htmlentities( $message );
		// if ( $mode == SeleniumTestSuite::RESULT_OK ) $out .= '</font>';
		if ( $mode != SeleniumTestSuite::CONTINUE_LINE ) {
			$out .= "\n";
		}

		echo $out;
	}
}
