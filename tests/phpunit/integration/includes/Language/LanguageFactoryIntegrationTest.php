<?php
namespace MediaWiki\Tests\Languages;

use MediaWikiIntegrationTestCase;
use Wikimedia\Bcp47Code\Bcp47CodeValue;

/**
 * @group Language
 * @covers \MediaWiki\Languages\LanguageFactory
 */
class LanguageFactoryIntegrationTest extends MediaWikiIntegrationTestCase {
	private function createFactory() {
		return $this->getServiceContainer()->getLanguageFactory();
	}

	/**
	 * @dataProvider provideCodes
	 */
	public function testGetParentLanguage( $code, $ignore, $parent = null ) {
		$factory = $this->createFactory();
		$lang = $factory->getParentLanguage( $code );
		$this->assertSame( $parent, $lang ? $lang->getCode() : null );
	}

	/**
	 * @dataProvider provideCodes
	 */
	public function testGetParentLanguageBcp47Code( $ignore, $bcp47code, $parent = null ) {
		$factory = $this->createFactory();
		$bcp47obj = new Bcp47CodeValue( $bcp47code );
		$lang = $factory->getParentLanguage( $bcp47obj );
		$this->assertSame( $parent, $lang ? $lang->getCode() : null );
	}

	public static function provideCodes() {
		return [
			# Basic codes
			[ 'de', 'de' ],
			[ 'fr', 'fr' ],
			[ 'ja', 'ja' ],
			# Base languages with variants are their own parents
			[ 'en', 'en', 'en' ],
			[ 'sr', 'sr', 'sr' ],
			[ 'crh', 'crh', 'crh' ],
			[ 'zh', 'zh', 'zh' ],
			# Variant codes
			[ 'zh-hans', 'zh-Hans', 'zh' ],
			# Non standard codes
			# Unlike deprecated codes, this *are* valid internal codes and
			# will be returned from Language::getCode()
			[ 'cbk-zam', 'cbk' ],
			[ 'de-formal', 'de-x-formal' ],
			[ 'eml', 'egl' ],
			[ 'en-rtl', 'en-x-rtl' ],
			[ 'es-formal', 'es-x-formal' ],
			[ 'hu-formal', 'hu-x-formal' ],
			[ 'map-bms', 'jv-x-bms' ],
			[ 'mo', 'ro-Cyrl-MD' ],
			[ 'nrm', 'nrf' ],
			[ 'nl-informal', 'nl-x-informal' ],
			[ 'roa-tara', 'nap-x-tara' ],
			[ 'simple', 'en-simple' ],
			[ 'sr-ec', 'sr-Cyrl', 'sr' ],
			[ 'sr-el', 'sr-Latn', 'sr' ],
			[ 'zh-cn', 'zh-Hans-CN', 'zh' ],
			[ 'zh-sg', 'zh-Hans-SG', 'zh' ],
			[ 'zh-my', 'zh-Hans-MY', 'zh' ],
			[ 'zh-tw', 'zh-Hant-TW', 'zh' ],
			[ 'zh-hk', 'zh-Hant-HK', 'zh' ],
			[ 'zh-mo', 'zh-Hant-MO', 'zh' ],
			[ 'zh-hans', 'zh-Hans', 'zh' ],
			[ 'zh-hant', 'zh-Hant', 'zh' ],
		];
	}

}
