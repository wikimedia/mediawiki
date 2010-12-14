<?php

class SeleniumTestHTMLLogger {
	public function setHeaders() {
		global $wgOut;
		$wgOut->addHeadItem( 'selenium', '<style type="text/css">
		.selenium pre {
			overflow-x: auto; /* Use horizontal scroller if needed; for Firefox 2, not needed in Firefox 3 */
			white-space: pre-wrap; /* css-3 */
			white-space: -moz-pre-wrap !important; /* Mozilla, since 1999 */
			white-space: -pre-wrap; /* Opera 4-6 */
			white-space: -o-pre-wrap; /* Opera 7 */
			/* width: 99%; */
			word-wrap: break-word; /* Internet Explorer 5.5+ */
		}
		.selenium-success { color: green }
		</style>' );
	}

	public function write( $message, $mode = false ) {
		global $wgOut;
		$out = '';
		if ( $mode == SeleniumTestSuite::RESULT_OK ) {
			$out .= '<span class="selenium-success">';
		}
		$out .= htmlspecialchars( $message );
		if ( $mode == SeleniumTestSuite::RESULT_OK ) {
			$out .= '</span>';
		}
		if ( $mode != SeleniumTestSuite::CONTINUE_LINE ) {
			$out .= '<br />';
		}

		$wgOut->addHTML( $out );
	}
}
