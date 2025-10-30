<?php

/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Testing
 */

/**
 * Initialize and detect the DjVu files support
 */
class DjVuSupport {

	/**
	 * Initialises DjVu tools global with default values
	 */
	public function __construct() {
		global $wgDjvuRenderer, $wgDjvuDump, $wgFileExtensions, $wgDjvuTxt;

		$wgDjvuRenderer = $wgDjvuRenderer ?: '/usr/bin/ddjvu';
		$wgDjvuDump = $wgDjvuDump ?: '/usr/bin/djvudump';
		$wgDjvuTxt = $wgDjvuTxt ?: '/usr/bin/djvutxt';

		if ( !in_array( 'djvu', $wgFileExtensions ) ) {
			$wgFileExtensions[] = 'djvu';
		}
	}

	/**
	 * Returns true if the DjVu tools are usable
	 *
	 * @return bool
	 */
	public function isEnabled() {
		global $wgDjvuRenderer, $wgDjvuDump, $wgDjvuTxt;

		return is_executable( $wgDjvuRenderer )
			&& is_executable( $wgDjvuDump )
			&& is_executable( $wgDjvuTxt );
	}
}
