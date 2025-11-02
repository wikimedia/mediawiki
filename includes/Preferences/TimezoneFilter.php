<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Preferences;

use MediaWiki\User\UserTimeCorrection;

class TimezoneFilter implements Filter {

	/**
	 * @inheritDoc
	 */
	public function filterForForm( $value ) {
		return $value;
	}

	/**
	 * @inheritDoc
	 */
	public function filterFromForm( $tz ) {
		return ( new UserTimeCorrection( $tz ) )->toString();
	}
}
