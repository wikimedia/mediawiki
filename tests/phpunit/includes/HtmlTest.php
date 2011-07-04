<?php
/** tests for includes/Html.php */

class HtmlTest extends MediaWikiTestCase {
	private static $oldLang;

	public function setUp() {
		global $wgLang, $wgLanguageCode;
		
		self::$oldLang = $wgLang;
		$wgLanguageCode = 'en';
		$wgLang = Language::factory( $wgLanguageCode );
	}
	
	public function tearDown() {
		global $wgLang, $wgLanguageCode;
		$wgLang = self::$oldLang;
		$wgLanguageCode = $wgLang->getCode();
	}

	public function testExpandAttributesSkipsNullAndFalse() {
		
		### EMPTY ########
		$this->AssertEmpty(
			Html::expandAttributes( array( 'foo'=>null) ),
			'skip keys with null value'
		);
		$this->AssertEmpty(
			Html::expandAttributes( array( 'foo'=>false) ),
			'skip keys with false value'
		);
		$this->AssertNotEmpty(
			Html::expandAttributes( array( 'foo'=>'') ),
			'keep keys with an empty string'
		);
	}

	public function testExpandAttributesForBooleans() {
		$this->AssertEquals(
			'',
			Html::expandAttributes( array( 'selected'=>false) ),
			'Boolean attributes do not generates output when value is false'
		);
		$this->AssertEquals(
			'',
			Html::expandAttributes( array( 'selected'=>null) ),
			'Boolean attributes do not generates output when value is null'
		);

		### FIXME: maybe they should just output 'selected'
		$this->AssertEquals(
			' selected=""',
			Html::expandAttributes( array( 'selected'=>true ) ),
			'Boolean attributes skip value output'
		);
		$this->AssertEquals(
			' selected=""',
			Html::expandAttributes( array( 'selected' ) ),
			'Boolean attributes (ex: selected) do not need a value'
		);
	}

	/**
	 * Test for Html::expandAttributes()
	 * Please note it output a string prefixed with a space!
	 */
	public function testExpandAttributesVariousExpansions() {
		### NOT EMPTY ####
		$this->AssertEquals(
			' empty_string=""',
			Html::expandAttributes( array( 'empty_string'=>'') ),
			'Value with an empty string'
		);
		$this->AssertEquals(
			' key="value"',
			Html::expandAttributes( array( 'key'=>'value') ),
			'Value is a string'
		);
		$this->AssertEquals(
			' one="1"',
			Html::expandAttributes( array( 'one'=>1) ),
			'Value is a numeric one'
		);
		$this->AssertEquals(
			' zero="0"',
			Html::expandAttributes( array( 'zero'=>0) ),
			'Value is a numeric zero'
		);
	}
}
