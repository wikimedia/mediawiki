<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Languages\LanguageFactory;

/**
 * Workaround for the lack of support of Sorani Kurdish / Central Kurdish language ('ckb') in ICU.
 *
 * Uses the same collation rules as Persian / Farsi ('fa'), but different characters for digits.
 *
 * @deprecated since 1.44 use CentralKurdishCollation instead
 * @since 1.23
 */
class CollationCkb extends IcuCollation {

	public function __construct( LanguageFactory $languageFactory ) {
		wfDeprecated( __METHOD__, '1.44' );
		// This will set $locale and collators, which affect the actual sorting order
		parent::__construct( $languageFactory, 'fa' );
		// Override the 'fa' language set by parent constructor, which affects #getFirstLetterData()
		$this->digitTransformLanguage = $languageFactory->getLanguage( 'ckb' );
	}
}
