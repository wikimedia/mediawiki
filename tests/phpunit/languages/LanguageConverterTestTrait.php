<?php

use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\MediaWikiServices;

trait LanguageConverterTestTrait {

	private $codeRegex = '/^(.+)ConverterTest$/';

	protected function code(): string {
		if ( preg_match( $this->codeRegex, get_class( $this ), $m ) ) {
			# Normalize language code since classes uses underscores
			return mb_strtolower( str_replace( '_', '-', $m[1] ) );
		}
		return 'en';
	}

	/** Create and return LanguageConveter to be tested.
	 *
	 * @return ILanguageConverter
	 */
	protected function getLanguageConverter(): ILanguageConverter {
		$code = $this->code();

		$language = MediaWikiServices::getInstance()->getLanguageFactory()
			->getLanguage( $code );

		$factory = new LanguageConverterFactory(
			MediaWikiServices::getInstance()->getObjectFactory(),
			false,
			false,
			false,
			static function () use ( $language ) {
				return $language;
			}
		);

		return $factory->getLanguageConverter( $language );
	}
}
