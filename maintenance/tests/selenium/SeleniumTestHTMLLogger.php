<?php
if ( !defined( 'MEDIAWIKI' ) || !defined( 'SELENIUMTEST' ) ) {
	echo "This script cannot be run standalone";
	exit( 1 );
}

class SeleniumTestHTMLLogger {
	public function __construct() {
		// Prepare testsuite for immediate output
		@ini_set( 'zlib.output_compression', 0 );
		@ini_set( 'implicit_flush', 1 );
		for ( $i = 0; $i < ob_get_level(); $i++ ) {
			ob_end_flush();
		}
		ob_implicit_flush( 1 );

		// Output some style information
		echo '<style>
		pre {
			overflow-x: auto; /* Use horizontal scroller if needed; for Firefox 2, not needed in Firefox 3 */
			white-space: pre-wrap; /* css-3 */
			white-space: -moz-pre-wrap !important; /* Mozilla, since 1999 */
			white-space: -pre-wrap; /* Opera 4-6 */
			white-space: -o-pre-wrap; /* Opera 7 */
			/* width: 99%; */
			word-wrap: break-word; /* Internet Explorer 5.5+ */
		}
		</style>';
	}

	public function write( $message, $mode = false ) {
		$out = '';
		if ( $mode == MW_TESTLOGGER_RESULT_OK ) {
			$out .= '<font color="green">';
		}
		$out .= htmlentities( $message );
		if ( $mode == MW_TESTLOGGER_RESULT_OK ) {
			$out .= '</font>';
		}
		if ( $mode != MW_TESTLOGGER_CONTINUE_LINE ) {
			$out .= '<br />';
		}

		echo $out;
	}
}