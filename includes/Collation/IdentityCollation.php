<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Language\Language;

/**
 * Collation class that's essentially a no-op.
 *
 * Does sorting based on binary value of the string.
 * Like how things were pre 1.17.
 *
 * @since 1.18
 */
class IdentityCollation extends Collation {

	/** @var Language */
	private $contentLanguage;

	public function __construct( Language $contentLanguage ) {
		$this->contentLanguage = $contentLanguage;
	}

	/** @inheritDoc */
	public function getSortKey( $string ) {
		return $string;
	}

	/** @inheritDoc */
	public function getFirstLetter( $string ) {
		// Copied from UppercaseCollation.
		// I'm kind of unclear on when this could happen...
		if ( $string[0] == "\0" ) {
			$string = substr( $string, 1 );
		}
		return $this->contentLanguage->firstChar( $string );
	}
}
