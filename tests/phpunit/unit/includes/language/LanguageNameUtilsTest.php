<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MediaWikiServices;

class LanguageNameUtilsTest extends MediaWikiUnitTestCase {
	/**
	 * @param array $optionsArray
	 */
	private static function newObj( array $optionsArray = [] ) : LanguageNameUtils {
		return new LanguageNameUtils(
			new ServiceOptions(
				LanguageNameUtils::CONSTRUCTOR_OPTIONS,
				$optionsArray,
				[
					'ExtraLanguageNames' => [],
					'LanguageCode' => 'en',
					'UsePigLatinVariant' => false,
				]
			),
			MediaWikiServices::getInstance()->getHookContainer()
		);
	}

	use LanguageNameUtilsTestTrait;

	private function isSupportedLanguage( $code ) {
		return $this->newObj()->isSupportedLanguage( $code );
	}

	private function isValidCode( $code ) {
		return $this->newObj()->isValidCode( $code );
	}

	private function isValidBuiltInCode( $code ) {
		return $this->newObj()->isValidBuiltInCode( $code );
	}

	private function isKnownLanguageTag( $code ) {
		return $this->newObj()->isKnownLanguageTag( $code );
	}

	private function assertGetLanguageNames( array $options, $expected, $code, ...$otherArgs ) {
		$this->assertSame( $expected, $this->newObj( $options )
			->getLanguageNames( ...$otherArgs )[strtolower( $code )] ?? '' );
		$this->assertSame( $expected,
			$this->newObj( $options )->getLanguageName( $code, ...$otherArgs ) );
	}

	private function getLanguageNames( ...$args ) {
		return $this->newObj()->getLanguageNames( ...$args );
	}

	private function getLanguageName( ...$args ) {
		return $this->newObj()->getLanguageName( ...$args );
	}

	private function getFileName( ...$args ) {
		return self::newObj()->getFileName( ...$args );
	}

	private function getMessagesFileName( $code ) {
		return self::newObj()->getMessagesFileName( $code );
	}

	private function getJsonMessagesFileName( $code ) {
		return self::newObj()->getJsonMessagesFileName( $code );
	}
}
