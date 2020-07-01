<?php

use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\MediaWikiServices;

/**
 * @group Language
 */
class LanguageConverterIntegrationTest extends MediaWikiIntegrationTestCase {

	/** @var LanguageConverterFactory */
	private $factory;

	/**
	 * Shorthand for getting a Language Converter for specific language's code
	 * @param string $code code of converter
	 * @return ILanguageConverter
	 */
	private function getLanguageConverter( $code ) : ILanguageConverter {
		$language = MediaWikiServices::getInstance()->getLanguageFactory()
			->getLanguage( $code );
		return $this->factory->getLanguageConverter( $language );
	}

	protected function setUp() : void {
		$this->factory = new LanguageConverterFactory( false, function () {
			$language = MediaWikiServices::getInstance()->getContentLanguage();
		} );
		parent::setUp();
	}

	/**
	 * @covers LanguageConverter::hasVariant
	 */
	public function testHasVariant() {
		// See LanguageSrTest::testHasVariant() for additional tests
		$converterEn = $this->getLanguageConverter( 'en' );
		$this->assertTrue( $converterEn->hasVariant( 'en' ), 'base is always a variant' );
		$this->assertFalse( $converterEn->hasVariant( 'en-bogus' ), 'bogus en variant' );

		$converterBogus = $this->getLanguageConverter( 'bogus' );
		$this->assertTrue( $converterBogus->hasVariant( 'bogus' ), 'base is always a variant' );
	}
}
