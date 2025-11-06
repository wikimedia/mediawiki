<?php

namespace MediaWiki\Tests\Language\Converters;

use MediaWiki\Tests\Language\LanguageConverterTestTrait;
use MediaWikiIntegrationTestCase;

/**
 * @group Language
 * @covers \ShConverter
 */
class ShConverterTest extends MediaWikiIntegrationTestCase {
	use LanguageConverterTestTrait;

	/**
	 * @dataProvider provideAutoConvertToAllVariants
	 */
	public function testAutoConvertToAllVariants( $result, $value ) {
		$this->assertEquals( $result, $this->getLanguageConverter()->autoConvertToAllVariants( $value ) );
	}

	public static function provideAutoConvertToAllVariants() {
		return [
			[
				[
					'sh-latn' => 'g',
					'sh-cyrl' => 'г',
				],
				'g'
			],
			[
				[
					'sh-latn' => 'г',
					'sh-cyrl' => 'г',
				],
				'г'
			],
		];
	}

	public function testConvertTo() {
		$this->testConversionToLatin();
		$this->testConversionToCyrillic();
	}

	/**
	 * Wrapper for testConvertTo() for Cyrillic
	 */
	public function testConversionToCyrillic() {
		// A simple conversion of Latin to Cyrillic
		$this->assertEquals( 'абвг',
			$this->convertToCyrillic( 'abvg' )
		);
		// Same as above, but assert that -{}-s must be removed and not converted
		$this->assertEquals( 'ljабnjвгdž',
			$this->convertToCyrillic( '-{lj}-ab-{nj}-vg-{dž}-' )
		);
		// A simple conversion of Cyrillic to Cyrillic
		$this->assertEquals( 'абвг',
			$this->convertToCyrillic( 'абвг' )
		);
		// Same as above, but assert that -{}-s must be removed and not converted
		$this->assertEquals( 'ljабnjвгdž',
			$this->convertToCyrillic( '-{lj}-аб-{nj}-вг-{dž}-' )
		);
		// Roman numerals are not converted
		$this->assertEquals( 'а I б II в III г IV шђжчћ',
			$this->convertToCyrillic( 'a I b II v III g IV šđžčć' )
		);
		// Exemption for the words containing "konjug" or "konjun"
		$this->assertEquals( 'конјугација',
			$this->convertToCyrillic( 'konjugacija' )
		);
		// Exemption for the words containing "wiki"
		$this->assertEquals( 'Википедија',
			$this->convertToCyrillic( 'Wikipedija' )
		);
		// Manual conversion rules work
		$this->assertEquals( 'Cyrillic',
			$this->convertToCyrillic( '-{sh-latn:Latin; sh-cyrl:Cyrillic;}-' )
		);
	}

	/**
	 * Wrapper for testConvertTo() for Latin
	 */
	public function testConversionToLatin() {
		// A simple conversion of Latin to Latin
		$this->assertEquals( 'abvg',
			$this->convertToLatin( 'abvg' )
		);
		// Same as above, but assert that -{}-s must be removed and not converted
		$this->assertEquals( 'ljabnjvgdž',
			$this->convertToLatin( '-{lj}-ab-{nj}-vg-{dž}-' )
		);
		// Manual conversion rules work
		$this->assertEquals( 'Latin',
			$this->convertToLatin( '-{sh-latn:Latin; sh-cyrl:Cyrillic;}-' )
		);
	}

	/**
	 * Wrapper for converter::convertTo() method
	 * @param string $text
	 * @param string $variant
	 * @return string
	 */
	protected function convertTo( $text, $variant ) {
		return $this->getLanguageConverter()->convertTo( $text, $variant );
	}

	protected function convertToCyrillic( $text ) {
		return $this->convertTo( $text, 'sh-cyrl' );
	}

	protected function convertToLatin( $text ) {
		return $this->convertTo( $text, 'sh-latn' );
	}
}
