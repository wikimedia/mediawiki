<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * @group Language
 */
class LanguageFrTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers \MediaWiki\Language\Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'one', 'other' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	/**
	 * @dataProvider providePluralAllForms
	 * @covers \MediaWiki\Language\Language::convertPlural
	 */
	public function testPluralAllForms( $result, $value ) {
		$forms = [ 'one', 'many', 'other' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	/**
	 * @dataProvider providePluralAllForms
	 * @covers \MediaWiki\Language\Language::getPluralRuleType
	 */
	public function testGetPluralRuleType( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->getPluralRuleType( $value ) );
	}

	public static function providePlural() {
		return [
			[ 'one', 0 ],
			[ 'one', 1 ],
			[ 'other', 2 ],
			[ 'other', 200 ],
		];
	}

	public static function providePluralAllForms() {
		return [
			[ 'one', 0 ],
			[ 'one', 1 ],
			[ 'other', 2 ],
			[ 'other', 200 ],
			[ 'one', '0.0' ],
			[ 'one', '0.5' ],
			[ 'one', '1.5' ],
			[ 'one', '1.99' ],
			[ 'other', '2.0' ],
			[ 'many', 1000000 ],
		];
	}
}
