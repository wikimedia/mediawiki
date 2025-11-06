<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * Tests for Latvian
 *
 * @group Language
 */
class LanguageLvTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers \MediaWiki\Language\Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'zero', 'one', 'other' ];
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
			[ 'zero', 11 ],
			[ 'one', 21 ],
			[ 'zero', 411 ],
			[ 'other', 2 ],
			[ 'other', 9 ],
			[ 'zero', 12 ],
			[ 'other', 12.345 ],
			[ 'zero', 20 ],
			[ 'other', 22 ],
			[ 'one', 31 ],
			[ 'zero', 200 ],
		];
	}
}
