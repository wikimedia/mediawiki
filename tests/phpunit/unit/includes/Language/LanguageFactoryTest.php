<?php

namespace MediaWiki\Tests\Unit\Languages;

use LocalisationCache;
use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Languages\LanguageFallback;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Title\NamespaceInfo;
use MediaWikiUnitTestCase;
use Wikimedia\Bcp47Code\Bcp47CodeValue;

/**
 * @group Language
 * @covers \MediaWiki\Languages\LanguageFactory
 */
class LanguageFactoryTest extends MediaWikiUnitTestCase {
	private function createFactory() {
		$options = new ServiceOptions(
			LanguageFactory::CONSTRUCTOR_OPTIONS,
			array_fill_keys( LanguageFactory::CONSTRUCTOR_OPTIONS, null )
		);
		$languageNameUtils = $this->createMock( LanguageNameUtils::class );
		$languageNameUtils
			->method( 'isValidCode' )
			->willReturn( true );
		$factory = new LanguageFactory(
			$options,
			$this->createNoOpMock( NamespaceInfo::class ),
			$this->createNoOpMock( LocalisationCache::class ),
			$languageNameUtils,
			$this->createNoOpMock( LanguageFallback::class ),
			$this->createNoOpMock( LanguageConverterFactory::class ),
			$this->createNoOpMock( HookContainer::class ),
			new HashConfig()
		);
		return $factory;
	}

	/**
	 * @dataProvider provideCodes
	 * @dataProvider provideDeprecatedCodes
	 */
	public function testGetLanguage( $code, $bcp47code ) {
		$factory = $this->createFactory();
		$lang = $factory->getLanguage( $code );
		$this->assertSame( $bcp47code, $lang->toBcp47Code() );
	}

	/**
	 * @dataProvider provideCodes
	 */
	public function testGetLanguageBcp47Code( $code, $bcp47code ) {
		$factory = $this->createFactory();
		$bcp47obj = new Bcp47CodeValue( $bcp47code );
		$lang = $factory->getLanguage( $bcp47obj );
		$this->assertSame( $code, $lang->getCode() );
	}

	public function testGetLanguageAmbig() {
		// At this moment, `egl` is a valid internal code, and should *not*
		// be remapped.  Its BCP-47 equivalent is also `egl`.
		$factory = $this->createFactory();
		$lang = $factory->getLanguage( 'egl' );
		$this->assertSame( 'egl', $lang->getCode() );
		$this->assertSame( 'egl', $lang->toBcp47Code() );
		// `eml` is *also* a valid internal code, and in fact is used for
		// (eg) `eml.wikipedia.org`. But it is not the recommended BCP-47
		// code, and should be mapped to `egl` in that context.
		$lang = $factory->getLanguage( 'eml' );
		$this->assertSame( 'eml', $lang->getCode() );
		$this->assertSame( 'egl', $lang->toBcp47Code() );
		// When converting *from* BCP47, `egl` should get mapped to `eml`,
		// since that's what is currently used internally in production.
		$lang = $factory->getLanguage( new Bcp47CodeValue( 'egl' ) );
		$this->assertSame( 'eml', $lang->getCode() );
		$this->assertSame( 'egl', $lang->toBcp47Code() );
		// See T36217; eventually `eml` should be deprecated and added to
		// DEPRECATED_LANGUAGE_CODE_MAPPING; alternatively `egl` could
		// be removed as a valid internal code.  Either way would remove
		// the ambiguity and this test case would need to be updated
		// appropriately.
	}

	// These are codes which should *not* be used internally: they can
	// be given as inputs to LanguageFactory::getLanguage() (for backward
	// compatibility) but should never be returned from Language::getCode()
	public static function provideDeprecatedCodes() {
		return [
			[ 'als', 'gsw' ],
			[ 'bat-smg', 'sgs' ],
			[ 'be-x-old', 'be-tarask' ],
			[ 'fiu-vro', 'vro' ],
			[ 'roa-rup', 'rup' ],
			[ 'zh-classical', 'lzh' ],
			[ 'zh-min-nan', 'nan' ],
			[ 'zh-yue', 'yue' ],
		];
	}

	public static function provideCodes() {
		return [
			# Basic codes
			[ 'en', 'en' ],
			[ 'de', 'de' ],
			[ 'fr', 'fr' ],
			[ 'ja', 'ja' ],
			# Variant codes
			[ 'zh-hans', 'zh-Hans' ],
			[ 'zh-yue-hk', 'zh-yue-HK' ],
			# Non standard codes
			# Unlike deprecated codes, this *are* valid internal codes and
			# will be returned from Language::getCode()
			[ 'cbk-zam', 'cbk' ],
			[ 'de-formal', 'de-x-formal' ],
			[ 'eml', 'egl' ],
			[ 'en-rtl', 'en-x-rtl' ],
			[ 'es-formal', 'es-x-formal' ],
			[ 'hu-formal', 'hu-x-formal' ],
			[ 'kk-arab', 'kk-Arab' ],
			[ 'kk-cyrl', 'kk-Cyrl' ],
			[ 'kk-latn', 'kk-Latn' ],
			[ 'map-bms', 'jv-x-bms' ],
			[ 'mo', 'ro-Cyrl-MD' ],
			[ 'nrm', 'nrf' ],
			[ 'nl-informal', 'nl-x-informal' ],
			[ 'roa-tara', 'nap-x-tara' ],
			[ 'simple', 'en-simple' ],
			[ 'sr-ec', 'sr-Cyrl' ],
			[ 'sr-el', 'sr-Latn' ],
			[ 'zh-cn', 'zh-Hans-CN' ],
			[ 'zh-sg', 'zh-Hans-SG' ],
			[ 'zh-my', 'zh-Hans-MY' ],
			[ 'zh-tw', 'zh-Hant-TW' ],
			[ 'zh-hk', 'zh-Hant-HK' ],
			[ 'zh-mo', 'zh-Hant-MO' ],
			[ 'zh-hans', 'zh-Hans' ],
			[ 'zh-hant', 'zh-Hant' ],
		];
	}
}
