<?php
/** tests for includes/Html.php */

class HtmlTest extends MediaWikiTestCase {
	private static $oldLang;
	private static $oldContLang;

	public function setUp() {
		global $wgLang, $wgContLang, $wgLanguageCode;
		
		self::$oldLang = $wgLang;
		self::$oldContLang = $wgContLang;
		
		$wgLanguageCode = 'en';
		$wgContLang = $wgLang = Language::factory( $wgLanguageCode );
	}
	
	public function tearDown() {
		global $wgLang, $wgContLang, $wgLanguageCode;
		$wgLang = self::$oldLang;
		$wgContLang = self::$oldContLang;
		$wgLanguageCode = $wgContLang->getCode();
	}

	public function testExpandAttributesSkipsNullAndFalse() {
		
		### EMPTY ########
		$this->AssertEmpty(
			Html::expandAttributes( array( 'foo' => null ) ),
			'skip keys with null value'
		);
		$this->AssertEmpty(
			Html::expandAttributes( array( 'foo' => false ) ),
			'skip keys with false value'
		);
		$this->AssertNotEmpty(
			Html::expandAttributes( array( 'foo' => '' ) ),
			'keep keys with an empty string'
		);
	}

	public function testExpandAttributesForBooleans() {
		global $wgHtml5;
		$this->AssertEquals(
			'',
			Html::expandAttributes( array( 'selected' => false ) ),
			'Boolean attributes do not generates output when value is false'
		);
		$this->AssertEquals(
			'',
			Html::expandAttributes( array( 'selected' => null ) ),
			'Boolean attributes do not generates output when value is null'
		);

		$this->AssertEquals(
			$wgHtml5 ? ' selected=""' : ' selected="selected"',
			Html::expandAttributes( array( 'selected' => true ) ),
			'Boolean attributes skip value output'
		);
		$this->AssertEquals(
			$wgHtml5 ? ' selected=""' : ' selected="selected"',
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
			Html::expandAttributes( array( 'empty_string' => '' ) ),
			'Value with an empty string'
		);
		$this->AssertEquals(
			' key="value"',
			Html::expandAttributes( array( 'key' => 'value' ) ),
			'Value is a string'
		);
		$this->AssertEquals(
			' one="1"',
			Html::expandAttributes( array( 'one' => 1 ) ),
			'Value is a numeric one'
		);
		$this->AssertEquals(
			' zero="0"',
			Html::expandAttributes( array( 'zero' => 0 ) ),
			'Value is a numeric zero'
		);
	}

	/**
	 * Html::expandAttributes has special features for HTML
	 * attributes that use space separated lists and also
	 * allows arrays to be used as values.
	 */
	public function testExpandAttributesListValueAttributes() {
		### STRING VALUES
		$this->AssertEquals(
			' class="redundant spaces here"',
			Html::expandAttributes( array( 'class' => ' redundant  spaces  here  ' ) ),
			'Normalization should strip redundant spaces'
		);
		$this->AssertEquals(
			' class="foo bar"',
			Html::expandAttributes( array( 'class' => 'foo bar foo bar bar' ) ),
			'Normalization should remove duplicates in string-lists'
		);
		### "EMPTY" ARRAY VALUES
		$this->AssertEquals(
			' class=""',
			Html::expandAttributes( array( 'class' => array() ) ),
			'Value with an empty array'
		);
		$this->AssertEquals(
			' class=""',
			Html::expandAttributes( array( 'class' => array( null, '', ' ', '  ' ) ) ),
			'Array with null, empty string and spaces'
		);
		### NON-EMPTY ARRAY VALUES
		$this->AssertEquals(
			' class="foo bar"',
			Html::expandAttributes( array( 'class' => array(
				'foo',
				'bar',
				'foo',
				'bar',
				'bar',
			) ) ),
			'Normalization should remove duplicates in the array'
		);
		$this->AssertEquals(
			' class="foo bar"',
			Html::expandAttributes( array( 'class' => array(
				'foo bar',
				'bar foo',
				'foo',
				'bar bar',
			) ) ),
			'Normalization should remove duplicates in string-lists in the array'
		);
	}

	/**
	 * Test feature added by r96188, let pass attributes values as
	 * a PHP array. Restricted to class,rel, accesskey.
	 */
	function testExpandAttributesSpaceSeparatedAttributesWithBoolean() {
		$this->assertEquals(
			' class="booltrue one"',
			Html::expandAttributes( array( 'class' => array(
				'booltrue' => true,
				'one' => 1,

				# Method use isset() internally, make sure we do discard
			    # attributes values which have been assigned well known values
				'emptystring' => '',
				'boolfalse' => false,
				'zero' => 0,
				'null' => null,
			)))
		);
	}

	/**
	 * How do we handle duplicate keys in HTML attributes expansion?
	 * We could pass a "class" the values: 'GREEN' and array( 'GREEN' => false )
	 * The later will take precedence.
	 *
	 * Feature added by r96188
	 */
	function testValueIsAuthoritativeInSpaceSeparatedAttributesArrays() {
		$this->assertEquals(
			' class=""',
			HTML::expandAttributes( array( 'class' => array(
				'GREEN',
				'GREEN' => false,
				'GREEN',
			)))
		);
	}
}
