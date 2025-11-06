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
class LanguageBeTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers \MediaWiki\Language\Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'one', 'few', 'many', 'other' ];
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
			[ 'many', 11 ],
			[ 'one', 91 ],
			[ 'one', 121 ],
			[ 'few', 2 ],
			[ 'few', 3 ],
			[ 'few', 4 ],
			[ 'few', 334 ],
			[ 'many', 5 ],
			[ 'many', 15 ],
			[ 'many', 120 ],
		];
	}
}
