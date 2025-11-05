<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Language\Language;

/**
 * Azerbaijani (Azərbaycan) specific code.
 *
 * @ingroup Languages
 */
class LanguageAz extends Language {

	/** @inheritDoc */
	public function ucfirst( $str ) {
		if ( str_starts_with( $str, 'i' ) ) {
			return 'İ' . substr( $str, 1 );
		}
		return parent::ucfirst( $str );
	}
}
