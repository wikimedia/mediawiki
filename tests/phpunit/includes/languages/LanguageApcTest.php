<?php

/**
 * @group Language
 */
class LanguageApcTest extends LanguageClassesTestCase {
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
			[ 'few', 3 ],
			[ 'few', 9 ],
			[ 'few', 10 ],
			[ 'other', 11 ],
			[ 'other', 12 ],
			[ 'other', 99 ],
			[ 'other', 100 ],
			[ 'other', 101 ],
			[ 'other', 102 ],
			[ 'few', 103 ],
			[ 'few', 104 ],
			[ 'few', 109 ],
			[ 'few', 110 ],
			[ 'other', 111 ],
			[ 'other', 112 ],
			[ 'other', 9999 ],
			[ 'other', 1000 ],
			[ 'few', 1003 ],
			[ 'other', 1.7 ],
		];
	}
}
