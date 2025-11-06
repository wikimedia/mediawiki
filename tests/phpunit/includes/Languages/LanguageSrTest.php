<?php
/**
 * @author Antoine Musso <hashar at free dot fr>
 * @copyright Copyright © 2011, Antoine Musso <hashar at free dot fr>
 * @file
 */
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * Tests for Serbian
 *
 * The language can be represented using two scripts:
 *
 *  - Latin (SR_el)
 *  - Cyrillic (SR_ec)
 *
 * Both representations seems to be bijective, hence MediaWiki can convert
 * from one script to the other.
 *
 * @group Language
 */
class LanguageSrTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers \MediaWiki\Language\Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'one', 'few', 'other' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	/**
	 * @dataProvider providePlural
	 * @covers \MediaWiki\Language\Language::getPluralRuleType
	 */
	public function testGetPluralRuleType( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->getPluralRuleType( $value ) );
	}

	public static function providePlural() {
		return [
			[ 'one', 1 ],
			[ 'other', 11 ],
			[ 'one', 91 ],
			[ 'one', 121 ],
			[ 'few', 2 ],
			[ 'few', 3 ],
			[ 'few', 4 ],
			[ 'few', 334 ],
			[ 'other', 5 ],
			[ 'other', 15 ],
			[ 'other', 120 ],
		];
	}

	/**
	 * @dataProvider providePluralTwoForms
	 * @covers \MediaWiki\Language\Language::convertPlural
	 */
	public function testPluralTwoForms( $result, $value ) {
		$forms = [ 'one', 'other' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	public static function providePluralTwoForms() {
		return [
			[ 'one', 1 ],
			[ 'other', 11 ],
			[ 'other', 4 ],
			[ 'one', 91 ],
			[ 'one', 121 ],
		];
	}

}
