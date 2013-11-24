<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageGv.php */
class LanguageGvTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		// This is not compatible with CLDR plural rules http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html#gv
		// What does this mean? Is there a hard-coded override for gv somewhere? -Ryan Kaldari 2013-01-28
		$forms = array( 'Form 1', 'Form 2', 'Form 3', 'Form 4' );
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	/**
	 * @dataProvider providePlural
	 * @covers Language::getPluralRuleType
	 */
	public function testGetPluralRuleType( $result, $value ) {
		$this->markTestSkipped( "This test won't work since convertPlural for gv doesn't seem to actually follow our plural rules." );
		$this->assertEquals( $result, $this->getLang()->getPluralRuleType( $value ) );
	}

	public static function providePlural() {
		return array(
			array( 'Form 4', 0 ),
			array( 'Form 2', 1 ),
			array( 'Form 3', 2 ),
			array( 'Form 4', 3 ),
			array( 'Form 1', 20 ),
			array( 'Form 2', 21 ),
			array( 'Form 3', 22 ),
			array( 'Form 4', 23 ),
			array( 'Form 4', 50 ),
		);
	}
}
