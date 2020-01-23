<?php

use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\MediaWikiServices;

trait LanguageConverterTestTrait {

	private $codeRegex = '/^(.+)ConverterTest$/';

	protected function code():string {
		if ( preg_match( $this->codeRegex, get_class( $this ), $m ) ) {
			return mb_strtolower( $m[1] );
		}
		return 'en';
	}

	/** Create and return LanguageConveter to be tested.
	 *
	 * @return ILanguageConverter
	 */
	protected function getLanguageConverter() : ILanguageConverter {
		$code = $this->code();

		$language = MediaWikiServices::getInstance()->getLanguageFactory()
			->getLanguage( $code );

		$factory = new LanguageConverterFactory( false, function () use ( $language ) {
			return $language;
		} );

		return $factory->getLanguageConverter( $language );
	}
}
