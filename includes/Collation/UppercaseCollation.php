<?php
/**
 * @license GPL-2.0-or-later
 * @since 1.16.3
 *
 * @file
 */

use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageFactory;

class UppercaseCollation extends Collation {

	/** @var Language Language object for English, so we can use the generic
	 * UTF-8 uppercase function there
	 */
	private $lang;

	public function __construct( LanguageFactory $languageFactory ) {
		$this->lang = $languageFactory->getLanguage( 'en' );
	}

	/** @inheritDoc */
	public function getSortKey( $string ) {
		return $this->lang->uc( $string );
	}

	/** @inheritDoc */
	public function getFirstLetter( $string ) {
		if ( $string[0] == "\0" ) {
			$string = substr( $string, 1 );
		}
		return $this->lang->ucfirst( $this->lang->firstChar( $string ) );
	}

}
