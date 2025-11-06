<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2013, Santhosh Thottingal
 * @file
 */
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * Tests for Manx (Gaelg)
 *
 * @group Language
 */
class LanguageGvTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers \MediaWiki\Language\Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'one', 'two', 'few', 'other' ];
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
			[ 'few', 0 ],
			[ 'one', 1 ],
			[ 'two', 2 ],
			[ 'other', 3 ],
			[ 'few', 20 ],
			[ 'one', 21 ],
			[ 'two', 22 ],
			[ 'other', 23 ],
			[ 'other', 50 ],
			[ 'few', 60 ],
			[ 'few', 80 ],
			[ 'few', 100 ]
		];
	}
}
