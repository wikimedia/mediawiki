<?php
/**
 * PHPUnit tests for the Talysh converter.
 * The language can be represented using two scripts:
 *  - Latin (tly)
 *  - Cyrillic (tly-cyrl)
 *
 * @author Amir E. Aharoni
 */

namespace MediaWiki\Tests\Language\Converters;

use MediaWiki\Tests\Language\LanguageConverterTestTrait;
use MediaWikiIntegrationTestCase;

/**
 * @group Language
 * @covers \MediaWiki\Language\LanguageConverter
 * @covers \TlyConverter
 */
class TlyConverterTest extends MediaWikiIntegrationTestCase {
	use LanguageConverterTestTrait;

	public function testConversionToCyrillic() {
		// A conversion of Latin to Cyrillic
		$this->assertEquals(
			'АаБбВвГгҒғДдЕеӘәЖжЗзИиЫыЈјКкЛлМмНнОоПпРрСсТтУуҮүФфХхҺһЧчҸҹШш',
			$this->convertToCyrillic(
				'AaBbVvQqĞğDdEeƏəJjZzİiIıYyKkLlMmNnOoPpRrSsTtUuÜüFfXxHhÇçCcŞş'
			)
		);

		// A simple conversion of Cyrillic to Cyrillic
		$this->assertEquals( 'Лик',
			$this->convertToCyrillic( 'Лик' )
		);

		// Assert that -{}-s are handled correctly
		// NB: Latin word followed by Latin word, and the second one is converted
		$this->assertEquals( 'Lankon Осторо',
			$this->convertToCyrillic( '-{Lankon}- Ostoro' )
		);

		// Assert that -{}-s are handled correctly
		// NB: Latin word followed by Cyrillic word, and nothing is converted
		$this->assertEquals( 'Lankon Осторо',
			$this->convertToCyrillic( '-{Lankon}- Осторо' )
		);
	}

	public function testConversionToLatin() {
		// A conversion of Cyrillic to Latin
		$this->assertEquals(
			'AaBbCcÇçDdEeƏəFfĞğHhXxIıİiJjKkQqLlMmNnOoPpRrSsŞşTtUuÜüVvYyZz',
			$this->convertToLatin(
				'АаБбҸҹЧчДдЕеӘәФфҒғҺһХхЫыИиЖжКкГгЛлМмНнОоПпРрСсШшТтУуҮүВвЈјЗз'
			)
		);

		// A simple conversion of Latin to Latin
		$this->assertEquals( 'Lik',
			$this->convertToLatin( 'Lik' )
		);

		// Assert that -{}-s are handled correctly
		// NB: Cyrillic word followed by Cyrillic word, and the second one is converted
		$this->assertEquals( 'Ланкон Ostoro',
			$this->convertToLatin( '-{Ланкон}- Осторо' )
		);

		// Assert that -{}-s are handled correctly
		// NB: Cyrillic word followed by Latin word, and nothing is converted
		$this->assertEquals( 'Ланкон Ostoro',
			$this->convertToLatin( '-{Ланкон}- Ostoro' )
		);
	}

	# #### HELPERS #####################################################

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
		return $this->convertTo( $text, 'tly-cyrl' );
	}

	protected function convertToLatin( $text ) {
		return $this->convertTo( $text, 'tly' );
	}
}
