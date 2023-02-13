<?php

use MediaWiki\Languages\LanguageConverterFactory;

trait LanguageConverterTestTrait {

	private $codeRegex = '/^(.+)ConverterTest$/';

	/** @var LanguageConverterFactory */
	private $factory;

	protected function getCode(): string {
		if ( preg_match( $this->codeRegex, get_class( $this ), $m ) ) {
			# Normalize language code since classes uses underscores
			return mb_strtolower( str_replace( '_', '-', $m[1] ) );
		}
		return '';
	}

	protected function getConverterFactory() {
		if ( $this->factory ) {
			return $this->factory;
		}

		$code = $this->getCode();
		$this->factory = new LanguageConverterFactory(
			$this->getServiceContainer()->getObjectFactory(),
			false,
			false,
			false,
			function () use ( $code ) {
				$services = $this->getServiceContainer();
				if ( $code ) {
					return $services->getLanguageFactory()->getLanguage( $code );
				} else {
					return $services->getContentLanguage();
				}
			}
		);

		return $this->factory;
	}

	/**
	 * @param string|null $language Language code or null to use language
	 * returned by ::getCode(), or the content language if not set either.
	 * @return ILanguageConverter
	 */
	protected function getLanguageConverter( $language = null ): ILanguageConverter {
		if ( $language ) {
			$language = $this->getServiceContainer()->getLanguageFactory()
				->getLanguage( $language );
		}

		return $this->getConverterFactory()->getLanguageConverter( $language );
	}
}
