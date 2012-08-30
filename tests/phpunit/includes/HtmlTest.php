<?php
/** tests for includes/Html.php */

class HtmlTest extends MediaWikiTestCase {
	private static $oldLang;
	private static $oldContLang;
	private static $oldLanguageCode;
	private static $oldNamespaces;
	private static $oldHTML5;

	public function setUp() {
		global $wgLang, $wgContLang, $wgLanguageCode, $wgHTML5;

		// Save globals
		self::$oldLang = $wgLang;
		self::$oldContLang = $wgContLang;
		self::$oldNamespaces = $wgContLang->getNamespaces();
		self::$oldLanguageCode = $wgLanguageCode;
		self::$oldHTML5 = $wgHTML5;

		$wgLanguageCode = 'en';
		$wgContLang = $wgLang = Language::factory( $wgLanguageCode );

		// Hardcode namespaces during test runs,
		// so that html output based on existing namespaces
		// can be properly evaluated.
		$wgContLang->setNamespaces( array(
			-2 => 'Media',
			-1 => 'Special',
			0  => '',
			1  => 'Talk',
			2  => 'User',
			3  => 'User_talk',
			4  => 'MyWiki',
			5  => 'MyWiki_Talk',
			6  => 'File',
			7  => 'File_talk',
			8  => 'MediaWiki',
			9  => 'MediaWiki_talk',
			10  => 'Template',
			11  => 'Template_talk',
			14  => 'Category',
			15  => 'Category_talk',
			100  => 'Custom',
			101  => 'Custom_talk',
		) );
	}

	public function tearDown() {
		global $wgLang, $wgContLang, $wgLanguageCode, $wgHTML5;

		// Restore globals
		$wgContLang->setNamespaces( self::$oldNamespaces );
		$wgLang = self::$oldLang;
		$wgContLang = self::$oldContLang;
		$wgLanguageCode = self::$oldLanguageCode;
		$wgHTML5 = self::$oldHTML5;
	}

	/**
	 * Wrapper to easily set $wgHTML5 = true.
	 * Original value will be restored after test completion.
	 * @todo Move to MediaWikiTestCase
	 */
	public function enableHTML5() {
		global $wgHTML5;
		$wgHTML5 = true;
	}
	/**
	 * Wrapper to easily set $wgHTML5 = false
	 * Original value will be restored after test completion.
	 * @todo Move to MediaWikiTestCase
	 */
	public function disableHTML5() {
		global $wgHTML5;
		$wgHTML5 = false;
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
			Html::expandAttributes( array( 'class' => array(
				'GREEN',
				'GREEN' => false,
				'GREEN',
			)))
		);
	}

	function testNamespaceSelector() {
		$this->assertEquals(
			'<select>' . "\n" .
'<option value="0">(Main)</option>' . "\n" .
'<option value="1">Talk</option>' . "\n" .
'<option value="2">User</option>' . "\n" .
'<option value="3">User talk</option>' . "\n" .
'<option value="4">MyWiki</option>' . "\n" .
'<option value="5">MyWiki Talk</option>' . "\n" .
'<option value="6">File</option>' . "\n" .
'<option value="7">File talk</option>' . "\n" .
'<option value="8">MediaWiki</option>' . "\n" .
'<option value="9">MediaWiki talk</option>' . "\n" .
'<option value="10">Template</option>' . "\n" .
'<option value="11">Template talk</option>' . "\n" .
'<option value="14">Category</option>' . "\n" .
'<option value="15">Category talk</option>' . "\n" .
'<option value="100">Custom</option>' . "\n" .
'<option value="101">Custom talk</option>' . "\n" .
'</select>',
			Html::namespaceSelector(),
			'Basic namespace selector without custom options'
		);

		$this->assertEquals(
			'<label for="mw-test-namespace">Select a namespace:</label>&#160;' .
'<select id="mw-test-namespace" name="wpNamespace">' . "\n" .
'<option value="all">all</option>' . "\n" .
'<option value="0">(Main)</option>' . "\n" .
'<option value="1">Talk</option>' . "\n" .
'<option value="2" selected="">User</option>' . "\n" .
'<option value="3">User talk</option>' . "\n" .
'<option value="4">MyWiki</option>' . "\n" .
'<option value="5">MyWiki Talk</option>' . "\n" .
'<option value="6">File</option>' . "\n" .
'<option value="7">File talk</option>' . "\n" .
'<option value="8">MediaWiki</option>' . "\n" .
'<option value="9">MediaWiki talk</option>' . "\n" .
'<option value="10">Template</option>' . "\n" .
'<option value="11">Template talk</option>' . "\n" .
'<option value="14">Category</option>' . "\n" .
'<option value="15">Category talk</option>' . "\n" .
'<option value="100">Custom</option>' . "\n" .
'<option value="101">Custom talk</option>' . "\n" .
'</select>',
			Html::namespaceSelector(
				array( 'selected' => '2', 'all' => 'all', 'label' => 'Select a namespace:' ),
				array( 'name' => 'wpNamespace', 'id' => 'mw-test-namespace' )
			),
			'Basic namespace selector with custom values'
		);

		$this->assertEquals(
			'<label>Select a namespace:</label>&#160;' .
'<select>' . "\n" .
'<option value="0">(Main)</option>' . "\n" .
'<option value="1">Talk</option>' . "\n" .
'<option value="2">User</option>' . "\n" .
'<option value="3">User talk</option>' . "\n" .
'<option value="4">MyWiki</option>' . "\n" .
'<option value="5">MyWiki Talk</option>' . "\n" .
'<option value="6">File</option>' . "\n" .
'<option value="7">File talk</option>' . "\n" .
'<option value="8">MediaWiki</option>' . "\n" .
'<option value="9">MediaWiki talk</option>' . "\n" .
'<option value="10">Template</option>' . "\n" .
'<option value="11">Template talk</option>' . "\n" .
'<option value="14">Category</option>' . "\n" .
'<option value="15">Category talk</option>' . "\n" .
'<option value="100">Custom</option>' . "\n" .
'<option value="101">Custom talk</option>' . "\n" .
'</select>',
			Html::namespaceSelector(
				array( 'label' => 'Select a namespace:' )
			),
			'Basic namespace selector with a custom label but no id attribtue for the <select>'
		);
	}

	function testCanFilterOutNamespaces() {
		$this->assertEquals(
'<select>' . "\n" .
'<option value="2">User</option>' . "\n" .
'<option value="4">MyWiki</option>' . "\n" .
'<option value="5">MyWiki Talk</option>' . "\n" .
'<option value="6">File</option>' . "\n" .
'<option value="7">File talk</option>' . "\n" .
'<option value="8">MediaWiki</option>' . "\n" .
'<option value="9">MediaWiki talk</option>' . "\n" .
'<option value="10">Template</option>' . "\n" .
'<option value="11">Template talk</option>' . "\n" .
'<option value="14">Category</option>' . "\n" .
'<option value="15">Category talk</option>' . "\n" .
'</select>',
			Html::namespaceSelector(
				array( 'exclude' => array( 0, 1, 3, 100, 101 ) )
			),
			'Namespace selector namespace filtering.'
		);
	}

	function testCanDisableANamespaces() {
		$this->assertEquals(
'<select>' . "\n" .
'<option disabled="" value="0">(Main)</option>' . "\n" .
'<option disabled="" value="1">Talk</option>' . "\n" .
'<option disabled="" value="2">User</option>' . "\n" .
'<option disabled="" value="3">User talk</option>' . "\n" .
'<option disabled="" value="4">MyWiki</option>' . "\n" .
'<option value="5">MyWiki Talk</option>' . "\n" .
'<option value="6">File</option>' . "\n" .
'<option value="7">File talk</option>' . "\n" .
'<option value="8">MediaWiki</option>' . "\n" .
'<option value="9">MediaWiki talk</option>' . "\n" .
'<option value="10">Template</option>' . "\n" .
'<option value="11">Template talk</option>' . "\n" .
'<option value="14">Category</option>' . "\n" .
'<option value="15">Category talk</option>' . "\n" .
'<option value="100">Custom</option>' . "\n" .
'<option value="101">Custom talk</option>' . "\n" .
'</select>',
			Html::namespaceSelector( array(
				'disable' => array( 0, 1, 2, 3, 4 )
			) ),
			'Namespace selector namespace disabling'
		);
	}

	/**
	 * @dataProvider providesHtml5InputTypes
	 */
	function testHtmlElementAcceptsNewHtml5TypesInHtml5Mode( $HTML5InputType ) {
		$this->enableHTML5();
		$this->assertEquals(
			'<input type="' . $HTML5InputType . '" />',
			HTML::element( 'input', array( 'type' => $HTML5InputType ) ),
			'In HTML5, HTML::element() should accept type="' . $HTML5InputType . '"'
		);
	}

	/**
	 * List of input element types values introduced by HTML5
	 * Full list at http://www.w3.org/TR/html-markup/input.html
	 */
	function providesHtml5InputTypes() {
		$types = array(
			'datetime',
			'datetime-local',
			'date',
			'month',
			'time',
			'week',
			'number',
			'range',
			'email',
			'url',
			'search',
			'tel',
			'color',
		);
		$cases = array();
		foreach( $types as $type ) {
			$cases[] = array( $type );
		}
		return $cases;
	}
}
