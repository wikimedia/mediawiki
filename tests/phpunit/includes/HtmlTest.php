<?php
/** tests for includes/Html.php */

class HtmlTest extends MediaWikiTestCase {
	private static $oldLang;
	private static $oldContLang;
	private static $oldLanguageCode;
	private static $oldNamespaces;

	public function setUp() {
		global $wgLang, $wgContLang, $wgLanguageCode;
		
		self::$oldLang = $wgLang;
		self::$oldContLang = $wgContLang;
		self::$oldNamespaces = $wgContLang->getNamespaces();
		self::$oldLanguageCode = $wgLanguageCode;
		
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
			100  => 'Custom',
			101  => 'Custom_talk',
		) );
	}
	
	public function tearDown() {
		global $wgLang, $wgContLang, $wgLanguageCode;

		$wgContLang->setNamespaces( self::$oldNamespaces );
		$wgLang = self::$oldLang;
		$wgContLang = self::$oldContLang;
		$wgLanguageCode = self::$oldLanguageCode;
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
			'<select id="namespace" name="namespace">' . "\n" .
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
'<option value="100">Custom</option>' . "\n" .
'<option value="101">Custom talk</option>' . "\n" .
'</select>',
			Html::namespaceSelector(
				array( 'selected' => '2', 'all' => 'all', 'label' => 'Select a namespace:' ),
				array( 'name' => 'wpNamespace', 'id' => 'mw-test-namespace' )
			),
			'Basic namespace selector with custom values'
		);
	}

	function testNamespaceSelectorIdAndNameDefaultsAttributes() {

		$this->assertNsSelectorIdAndName(
			'namespace', 'namespace',
			Html::namespaceSelector( array(), array(
				# neither 'id' nor 'name' key given
			)),
			"Neither 'id' nor 'name' key given"
		);

		$this->assertNsSelectorIdAndName(
			'namespace', 'select_name',
			Html::namespaceSelector( array(), array(
				'name' => 'select_name',
				# no 'id' key given
			)),
			"No 'id' key given, 'name' given"
		);

		$this->assertNsSelectorIdAndName(
			'select_id', 'namespace',
			Html::namespaceSelector( array(), array(
				'id' => 'select_id',
				# no 'name' key given
			)),
			"'id' given, no 'name' key given"
		);

		$this->assertNsSelectorIdAndName(
			'select_id', 'select_name',
			Html::namespaceSelector( array(), array(
				'id'   => 'select_id',
				'name' => 'select_name',
			)),
			"Both 'id' and 'name' given"
		);
	}

	/**
	 * Helper to verify <select> attributes generated by Html::namespaceSelector()
	 * This helper expect the Html method to use 'namespace' as a default value for
	 * both 'id' and 'name' attributes.
	 *
	 * @param String $expectedId <select> id attribute value
	 * @param String $expectedName <select> name attribute value
	 * @param String $html Output of a call to Html::namespaceSelector()
	 * @param String $msg Optional message (default: '')
	*/
	function assertNsSelectorIdAndName( $expectedId, $expectedName, $html, $msg = '' ) {
		$actualId = 'namespace';
		if( 1 === preg_match( '/id="(.+?)"/', $html, $m ) ) {
			$actualId = $m[1];
		}

		$actualName = 'namespace';
		if( 1 === preg_match( '/name="(.+?)"/', $html, $m ) ) {
			$actualName = $m[1];
		}
		$this->assertEquals(
			array( #expected
				'id'   => $expectedId,
				'name' => $expectedName,
			),
			array( #actual
				'id'   => $actualId,
				'name' => $actualName,
			),
			'Html::namespaceSelector() got wrong id and/or name attribute(s). ' . $msg
		);
	}

}
