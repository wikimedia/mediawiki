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
class LanguageCyTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers \MediaWiki\Language\Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'zero', 'one', 'two', 'few', 'many', 'other' ];
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
			[ 'zero', 0 ],
			[ 'one', 1 ],
			[ 'two', 2 ],
			[ 'few', 3 ],
			[ 'many', 6 ],
			[ 'other', 4 ],
			[ 'other', 5 ],
			[ 'other', 11 ],
			[ 'other', 20 ],
			[ 'other', 22 ],
			[ 'other', 223 ],
			[ 'other', 200.00 ],
		];
	}
}
