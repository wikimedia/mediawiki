<?php
/**
 * Copyright (C) 2017 Kunal Mehta <legoktm@debian.org>
 *
 * @license GPL-2.0-or-later
 */

namespace MediaWiki\Composer;

/**
 * Creates a .htaccess in the vendor/ directory
 * to prevent web access.
 *
 * This class runs *outside* of the normal MediaWiki
 * environment and cannot depend upon any MediaWiki
 * code.
 */
class ComposerVendorHtaccessCreator {

	/**
	 * Handle post-install-cmd and post-update-cmd hooks
	 */
	public static function onEvent() {
		$fname = dirname( dirname( __DIR__ ) ) . "/vendor/.htaccess";
		if ( file_exists( $fname ) ) {
			// Already exists
			return;
		}

		file_put_contents( $fname,
			"Require all denied\n" .
			"Satisfy All\n" );
	}
}
