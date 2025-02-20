<?php
/**
 * @author Amir E. Aharoni
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * Tests for Samogitian
 *
 * @group Language
 */
class LanguageSgsTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePluralAllForms
	 * @covers \MediaWiki\Language\Language::convertPlural
	 */
	public function testPluralAllForms( $result, $value ) {
		$forms = [ 'one', 'two', 'few', 'many', 'other' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	/**
	 * @dataProvider providePluralAllForms
	 * @covers \MediaWiki\Language\Language::getPluralRuleType
	 */
	public function testGetPluralRuleType( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->getPluralRuleType( $value ) );
	}

	public static function providePluralAllForms() {
		return [
			[ 'other', 0 ],
			[ 'one', 1 ],
			[ 'two', 2 ],
			[ 'few', 3 ],
			[ 'few', 9 ],
			[ 'other', 10 ],
			[ 'other', 11 ],
			[ 'other', 19 ],
			[ 'other', 20 ],
			[ 'few', 22 ],
			[ 'other', 100 ],
			[ 'one', 101 ],
			[ 'few', 102 ],
			[ 'many', '1.5' ],
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
			[ 'other', 0 ],
			[ 'one', 1 ],
			[ 'other', 2 ],
			[ 'other', 3 ],
			[ 'other', 10 ],
			[ 'other', 11 ],
			[ 'other', 12 ],
			[ 'other', 19 ],
			[ 'other', 20 ],
			[ 'other', 100 ],
			[ 'one', 101 ],
			[ 'other', 111 ],
			[ 'other', 112 ],
		];
	}
}
