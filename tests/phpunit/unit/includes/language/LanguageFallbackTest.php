<?php

use MediaWiki\Languages\LanguageFallback;
use MediaWiki\Languages\LanguageNameUtils;

/**
 * @coversDefaultClass MediaWiki\Languages\LanguageFallback
 * @covers ::__construct
 */
class LanguageFallbackTest extends MediaWikiUnitTestCase {
	use LanguageFallbackTestTrait;

	private const DATA = [
		'en' => [],
		'fr' => [],
		'sco' => [ 'en' ],
		'yi' => [ 'he' ],
		'ruq' => [ 'ruq-latn', 'ro' ],
		'sh' => [ 'bs', 'sr-el', 'hr' ],
	];

	private function getLanguageNameUtils() {
		$mockLangNameUtils = $this->createMock( LanguageNameUtils::class );
		$mockLangNameUtils->method( 'isValidBuiltInCode' )
			->will( $this->returnCallback( function ( $code ) {
				// One-line copy-paste
				return (bool)preg_match( '/^[a-z0-9-]{2,}$/', $code );
			} ) );
		$mockLangNameUtils->expects( $this->never() )
			->method( $this->anythingBut( 'isValidBuiltInCode' ) );
		return $mockLangNameUtils;
	}

	private function getCallee( array $options = [] ) : LanguageFallback {
		return new LanguageFallback(
			$options['siteLangCode'] ?? 'en',
			$this->getMockLocalisationCache(
				$options['expectedGets'] ?? 1,
				$options['fallbackMap'] ?? self::DATA
			),
			$this->getLanguageNameUtils()
		);
	}

	private function getMessagesKey() {
		return LanguageFallback::MESSAGES;
	}

	private function getStrictKey() {
		return LanguageFallback::STRICT;
	}
}
