<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Preferences;

class IntvalFilter implements Filter {

	/**
	 * @inheritDoc
	 */
	public function filterForForm( $value ) {
		return $value;
	}

	/**
	 * @inheritDoc
	 */
	public function filterFromForm( $value ) {
		return intval( $value );
	}
}
