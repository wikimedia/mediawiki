<?php
/** tests for includes/Html.php */

class HtmlTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		$langCode = 'en';
		$langObj = Language::factory( $langCode );

		// Hardcode namespaces during test runs,
		// so that html output based on existing namespaces
		// can be properly evaluated.
		$langObj->setNamespaces( array(
			-2 => 'Media',
			-1 => 'Special',
			0 => '',
			1 => 'Talk',
			2 => 'User',
			3 => 'User_talk',
			4 => 'MyWiki',
			5 => 'MyWiki_Talk',
			6 => 'File',
			7 => 'File_talk',
			8 => 'MediaWiki',
			9 => 'MediaWiki_talk',
			10 => 'Template',
			11 => 'Template_talk',
			14 => 'Category',
			15 => 'Category_talk',
			100 => 'Custom',
			101 => 'Custom_talk',
		) );

		$this->setMwGlobals( array(
			'wgLanguageCode' => $langCode,
			'wgContLang' => $langObj,
			'wgLang' => $langObj,
			'wgWellFormedXml' => false,
		) );
	}

	public function testElementBasics() {
		$this->assertEquals(
			'<img>',
			Html::element( 'img', null, '' ),
			'No close tag for short-tag elements'
		);

		$this->assertEquals(
			'<element></element>',
			Html::element( 'element', null, null ),
			'Close tag for empty element (null, null)'
		);

		$this->assertEquals(
			'<element></element>',
			Html::element( 'element', array(), '' ),
			'Close tag for empty element (array, string)'
		);

		$this->setMwGlobals( 'wgWellFormedXml', true );

		$this->assertEquals(
			'<img />',
			Html::element( 'img', null, '' ),
			'Self-closing tag for short-tag elements (wgWellFormedXml = true)'
		);
	}

	public function dataXmlMimeType() {
		return array(
			// ( $mimetype, $isXmlMimeType )
			# HTML is not an XML MimeType
			array( 'text/html', false ),
			# XML is an XML MimeType
			array( 'text/xml', true ),
			array( 'application/xml', true ),
			# XHTML is an XML MimeType
			array( 'application/xhtml+xml', true ),
			# Make sure other +xml MimeTypes are supported
			# SVG is another random MimeType even though we don't use it
			array( 'image/svg+xml', true ),
			# Complete random other MimeTypes are not XML
			array( 'text/plain', false ),
		);
	}

	/**
	 * @dataProvider dataXmlMimeType
	 */
	public function testXmlMimeType( $mimetype, $isXmlMimeType ) {
		$this->assertEquals( $isXmlMimeType, Html::isXmlMimeType( $mimetype ) );
	}

	public function testExpandAttributesSkipsNullAndFalse() {

		### EMPTY ########
		$this->assertEmpty(
			Html::expandAttributes( array( 'foo' => null ) ),
			'skip keys with null value'
		);
		$this->assertEmpty(
			Html::expandAttributes( array( 'foo' => false ) ),
			'skip keys with false value'
		);
		$this->assertNotEmpty(
			Html::expandAttributes( array( 'foo' => '' ) ),
			'keep keys with an empty string'
		);
	}

	public function testExpandAttributesForBooleans() {
		$this->assertEquals(
			'',
			Html::expandAttributes( array( 'selected' => false ) ),
			'Boolean attributes do not generates output when value is false'
		);
		$this->assertEquals(
			'',
			Html::expandAttributes( array( 'selected' => null ) ),
			'Boolean attributes do not generates output when value is null'
		);

		$this->assertEquals(
			' selected',
			Html::expandAttributes( array( 'selected' => true ) ),
			'Boolean attributes have no value when value is true'
		);
		$this->assertEquals(
			' selected',
			Html::expandAttributes( array( 'selected' ) ),
			'Boolean attributes have no value when value is true (passed as numerical array)'
		);

		$this->setMwGlobals( 'wgWellFormedXml', true );

		$this->assertEquals(
			' selected=""',
			Html::expandAttributes( array( 'selected' => true ) ),
			'Boolean attributes have empty string value when value is true (wgWellFormedXml)'
		);
	}

	/**
	 * Test for Html::expandAttributes()
	 * Please note it output a string prefixed with a space!
	 */
	public function testExpandAttributesVariousExpansions() {
		### NOT EMPTY ####
		$this->assertEquals(
			' empty_string=""',
			Html::expandAttributes( array( 'empty_string' => '' ) ),
			'Empty string is always quoted'
		);
		$this->assertEquals(
			' key=value',
			Html::expandAttributes( array( 'key' => 'value' ) ),
			'Simple string value needs no quotes'
		);
		$this->assertEquals(
			' one=1',
			Html::expandAttributes( array( 'one' => 1 ) ),
			'Number 1 value needs no quotes'
		);
		$this->assertEquals(
			' zero=0',
			Html::expandAttributes( array( 'zero' => 0 ) ),
			'Number 0 value needs no quotes'
		);

		$this->setMwGlobals( 'wgWellFormedXml', true );

		$this->assertEquals(
			' empty_string=""',
			Html::expandAttributes( array( 'empty_string' => '' ) ),
			'Attribute values are always quoted (wgWellFormedXml): Empty string'
		);
		$this->assertEquals(
			' key="value"',
			Html::expandAttributes( array( 'key' => 'value' ) ),
			'Attribute values are always quoted (wgWellFormedXml): Simple string'
		);
		$this->assertEquals(
			' one="1"',
			Html::expandAttributes( array( 'one' => 1 ) ),
			'Attribute values are always quoted (wgWellFormedXml): Number 1'
		);
		$this->assertEquals(
			' zero="0"',
			Html::expandAttributes( array( 'zero' => 0 ) ),
			'Attribute values are always quoted (wgWellFormedXml): Number 0'
		);
	}

	/**
	 * Html::expandAttributes has special features for HTML
	 * attributes that use space separated lists and also
	 * allows arrays to be used as values.
	 */
	public function testExpandAttributesListValueAttributes() {
		### STRING VALUES
		$this->assertEquals(
			' class="redundant spaces here"',
			Html::expandAttributes( array( 'class' => ' redundant  spaces  here  ' ) ),
			'Normalization should strip redundant spaces'
		);
		$this->assertEquals(
			' class="foo bar"',
			Html::expandAttributes( array( 'class' => 'foo bar foo bar bar' ) ),
			'Normalization should remove duplicates in string-lists'
		);
		### "EMPTY" ARRAY VALUES
		$this->assertEquals(
			' class=""',
			Html::expandAttributes( array( 'class' => array() ) ),
			'Value with an empty array'
		);
		$this->assertEquals(
			' class=""',
			Html::expandAttributes( array( 'class' => array( null, '', ' ', '  ' ) ) ),
			'Array with null, empty string and spaces'
		);
		### NON-EMPTY ARRAY VALUES
		$this->assertEquals(
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
		$this->assertEquals(
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
	public function testExpandAttributesSpaceSeparatedAttributesWithBoolean() {
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
			) ) )
		);
	}

	/**
	 * How do we handle duplicate keys in HTML attributes expansion?
	 * We could pass a "class" the values: 'GREEN' and array( 'GREEN' => false )
	 * The later will take precedence.
	 *
	 * Feature added by r96188
	 */
	public function testValueIsAuthoritativeInSpaceSeparatedAttributesArrays() {
		$this->assertEquals(
			' class=""',
			Html::expandAttributes( array( 'class' => array(
				'GREEN',
				'GREEN' => false,
				'GREEN',
			) ) )
		);
	}

	public function testNamespaceSelector() {
		$this->assertEquals(
			'<select id=namespace name=namespace>' . "\n" .
				'<option value=0>(Main)</option>' . "\n" .
				'<option value=1>Talk</option>' . "\n" .
				'<option value=2>User</option>' . "\n" .
				'<option value=3>User talk</option>' . "\n" .
				'<option value=4>MyWiki</option>' . "\n" .
				'<option value=5>MyWiki Talk</option>' . "\n" .
				'<option value=6>File</option>' . "\n" .
				'<option value=7>File talk</option>' . "\n" .
				'<option value=8>MediaWiki</option>' . "\n" .
				'<option value=9>MediaWiki talk</option>' . "\n" .
				'<option value=10>Template</option>' . "\n" .
				'<option value=11>Template talk</option>' . "\n" .
				'<option value=14>Category</option>' . "\n" .
				'<option value=15>Category talk</option>' . "\n" .
				'<option value=100>Custom</option>' . "\n" .
				'<option value=101>Custom talk</option>' . "\n" .
				'</select>',
			Html::namespaceSelector(),
			'Basic namespace selector without custom options'
		);

		$this->assertEquals(
			'<label for=mw-test-namespace>Select a namespace:</label>&#160;' .
				'<select id=mw-test-namespace name=wpNamespace>' . "\n" .
				'<option value=all>all</option>' . "\n" .
				'<option value=0>(Main)</option>' . "\n" .
				'<option value=1>Talk</option>' . "\n" .
				'<option value=2 selected>User</option>' . "\n" .
				'<option value=3>User talk</option>' . "\n" .
				'<option value=4>MyWiki</option>' . "\n" .
				'<option value=5>MyWiki Talk</option>' . "\n" .
				'<option value=6>File</option>' . "\n" .
				'<option value=7>File talk</option>' . "\n" .
				'<option value=8>MediaWiki</option>' . "\n" .
				'<option value=9>MediaWiki talk</option>' . "\n" .
				'<option value=10>Template</option>' . "\n" .
				'<option value=11>Template talk</option>' . "\n" .
				'<option value=14>Category</option>' . "\n" .
				'<option value=15>Category talk</option>' . "\n" .
				'<option value=100>Custom</option>' . "\n" .
				'<option value=101>Custom talk</option>' . "\n" .
				'</select>',
			Html::namespaceSelector(
				array( 'selected' => '2', 'all' => 'all', 'label' => 'Select a namespace:' ),
				array( 'name' => 'wpNamespace', 'id' => 'mw-test-namespace' )
			),
			'Basic namespace selector with custom values'
		);

		$this->assertEquals(
			'<label for=namespace>Select a namespace:</label>&#160;' .
				'<select id=namespace name=namespace>' . "\n" .
				'<option value=0>(Main)</option>' . "\n" .
				'<option value=1>Talk</option>' . "\n" .
				'<option value=2>User</option>' . "\n" .
				'<option value=3>User talk</option>' . "\n" .
				'<option value=4>MyWiki</option>' . "\n" .
				'<option value=5>MyWiki Talk</option>' . "\n" .
				'<option value=6>File</option>' . "\n" .
				'<option value=7>File talk</option>' . "\n" .
				'<option value=8>MediaWiki</option>' . "\n" .
				'<option value=9>MediaWiki talk</option>' . "\n" .
				'<option value=10>Template</option>' . "\n" .
				'<option value=11>Template talk</option>' . "\n" .
				'<option value=14>Category</option>' . "\n" .
				'<option value=15>Category talk</option>' . "\n" .
				'<option value=100>Custom</option>' . "\n" .
				'<option value=101>Custom talk</option>' . "\n" .
				'</select>',
			Html::namespaceSelector(
				array( 'label' => 'Select a namespace:' )
			),
			'Basic namespace selector with a custom label but no id attribtue for the <select>'
		);
	}

	public function testCanFilterOutNamespaces() {
		$this->assertEquals(
			'<select id=namespace name=namespace>' . "\n" .
				'<option value=2>User</option>' . "\n" .
				'<option value=4>MyWiki</option>' . "\n" .
				'<option value=5>MyWiki Talk</option>' . "\n" .
				'<option value=6>File</option>' . "\n" .
				'<option value=7>File talk</option>' . "\n" .
				'<option value=8>MediaWiki</option>' . "\n" .
				'<option value=9>MediaWiki talk</option>' . "\n" .
				'<option value=10>Template</option>' . "\n" .
				'<option value=11>Template talk</option>' . "\n" .
				'<option value=14>Category</option>' . "\n" .
				'<option value=15>Category talk</option>' . "\n" .
				'</select>',
			Html::namespaceSelector(
				array( 'exclude' => array( 0, 1, 3, 100, 101 ) )
			),
			'Namespace selector namespace filtering.'
		);
	}

	public function testCanDisableANamespaces() {
		$this->assertEquals(
			'<select id=namespace name=namespace>' . "\n" .
				'<option disabled value=0>(Main)</option>' . "\n" .
				'<option disabled value=1>Talk</option>' . "\n" .
				'<option disabled value=2>User</option>' . "\n" .
				'<option disabled value=3>User talk</option>' . "\n" .
				'<option disabled value=4>MyWiki</option>' . "\n" .
				'<option value=5>MyWiki Talk</option>' . "\n" .
				'<option value=6>File</option>' . "\n" .
				'<option value=7>File talk</option>' . "\n" .
				'<option value=8>MediaWiki</option>' . "\n" .
				'<option value=9>MediaWiki talk</option>' . "\n" .
				'<option value=10>Template</option>' . "\n" .
				'<option value=11>Template talk</option>' . "\n" .
				'<option value=14>Category</option>' . "\n" .
				'<option value=15>Category talk</option>' . "\n" .
				'<option value=100>Custom</option>' . "\n" .
				'<option value=101>Custom talk</option>' . "\n" .
				'</select>',
			Html::namespaceSelector( array(
				'disable' => array( 0, 1, 2, 3, 4 )
			) ),
			'Namespace selector namespace disabling'
		);
	}

	/**
	 * @dataProvider provideHtml5InputTypes
	 */
	public function testHtmlElementAcceptsNewHtml5TypesInHtml5Mode( $HTML5InputType ) {
		$this->assertEquals(
			'<input type=' . $HTML5InputType . '>',
			Html::element( 'input', array( 'type' => $HTML5InputType ) ),
			'In HTML5, HTML::element() should accept type="' . $HTML5InputType . '"'
		);
	}

	/**
	 * List of input element types values introduced by HTML5
	 * Full list at http://www.w3.org/TR/html-markup/input.html
	 */
	public static function provideHtml5InputTypes() {
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
		foreach ( $types as $type ) {
			$cases[] = array( $type );
		}

		return $cases;
	}

	/**
	 * Test out Html::element drops or enforces default value
	 * @covers Html::dropDefaults
	 * @dataProvider provideElementsWithAttributesHavingDefaultValues
	 */
	public function testDropDefaults( $expected, $element, $attribs, $message = '' ) {
		$this->assertEquals( $expected, Html::element( $element, $attribs ), $message );
	}

	public static function provideElementsWithAttributesHavingDefaultValues() {
		# Use cases in a concise format:
		# <expected>, <element name>, <array of attributes> [, <message>]
		# Will be mapped to Html::element()
		$cases = array();

		### Generic cases, match $attribDefault static array
		$cases[] = array( '<area>',
			'area', array( 'shape' => 'rect' )
		);

		$cases[] = array( '<button type=submit></button>',
			'button', array( 'formaction' => 'GET' )
		);
		$cases[] = array( '<button type=submit></button>',
			'button', array( 'formenctype' => 'application/x-www-form-urlencoded' )
		);

		$cases[] = array( '<canvas></canvas>',
			'canvas', array( 'height' => '150' )
		);
		$cases[] = array( '<canvas></canvas>',
			'canvas', array( 'width' => '300' )
		);
		# Also check with numeric values
		$cases[] = array( '<canvas></canvas>',
			'canvas', array( 'height' => 150 )
		);
		$cases[] = array( '<canvas></canvas>',
			'canvas', array( 'width' => 300 )
		);

		$cases[] = array( '<command>',
			'command', array( 'type' => 'command' )
		);

		$cases[] = array( '<form></form>',
			'form', array( 'action' => 'GET' )
		);
		$cases[] = array( '<form></form>',
			'form', array( 'autocomplete' => 'on' )
		);
		$cases[] = array( '<form></form>',
			'form', array( 'enctype' => 'application/x-www-form-urlencoded' )
		);

		$cases[] = array( '<input>',
			'input', array( 'formaction' => 'GET' )
		);
		$cases[] = array( '<input>',
			'input', array( 'type' => 'text' )
		);

		$cases[] = array( '<keygen>',
			'keygen', array( 'keytype' => 'rsa' )
		);

		$cases[] = array( '<link>',
			'link', array( 'media' => 'all' )
		);

		$cases[] = array( '<menu></menu>',
			'menu', array( 'type' => 'list' )
		);

		$cases[] = array( '<script></script>',
			'script', array( 'type' => 'text/javascript' )
		);

		$cases[] = array( '<style></style>',
			'style', array( 'media' => 'all' )
		);
		$cases[] = array( '<style></style>',
			'style', array( 'type' => 'text/css' )
		);

		$cases[] = array( '<textarea></textarea>',
			'textarea', array( 'wrap' => 'soft' )
		);

		### SPECIFIC CASES

		# <link type="text/css">
		$cases[] = array( '<link>',
			'link', array( 'type' => 'text/css' )
		);

		# <input> specific handling
		$cases[] = array( '<input type=checkbox>',
			'input', array( 'type' => 'checkbox', 'value' => 'on' ),
			'Default value "on" is stripped of checkboxes',
		);
		$cases[] = array( '<input type=radio>',
			'input', array( 'type' => 'radio', 'value' => 'on' ),
			'Default value "on" is stripped of radio buttons',
		);
		$cases[] = array( '<input type=submit value=Submit>',
			'input', array( 'type' => 'submit', 'value' => 'Submit' ),
			'Default value "Submit" is kept on submit buttons (for possible l10n issues)',
		);
		$cases[] = array( '<input type=color>',
			'input', array( 'type' => 'color', 'value' => '' ),
		);
		$cases[] = array( '<input type=range>',
			'input', array( 'type' => 'range', 'value' => '' ),
		);

		# <button> specific handling
		# see remarks on http://msdn.microsoft.com/en-us/library/ie/ms535211%28v=vs.85%29.aspx
		$cases[] = array( '<button type=submit></button>',
			'button', array( 'type' => 'submit' ),
			'According to standard the default type is "submit". Depending on compatibility mode IE might use "button", instead.',
		);

		# <select> specifc handling
		$cases[] = array( '<select multiple></select>',
			'select', array( 'size' => '4', 'multiple' => true ),
		);
		# .. with numeric value
		$cases[] = array( '<select multiple></select>',
			'select', array( 'size' => 4, 'multiple' => true ),
		);
		$cases[] = array( '<select></select>',
			'select', array( 'size' => '1', 'multiple' => false ),
		);
		# .. with numeric value
		$cases[] = array( '<select></select>',
			'select', array( 'size' => 1, 'multiple' => false ),
		);

		# Passing an array as value
		$cases[] = array( '<a class="css-class-one css-class-two"></a>',
			'a', array( 'class' => array( 'css-class-one', 'css-class-two' ) ),
			"dropDefaults accepts values given as an array"
		);

		# FIXME: doDropDefault should remove defaults given in an array
		# Expected should be '<a></a>'
		$cases[] = array( '<a class=""></a>',
			'a', array( 'class' => array( '', '' ) ),
			"dropDefaults accepts values given as an array"
		);

		# Craft the Html elements
		$ret = array();
		foreach ( $cases as $case ) {
			$ret[] = array(
				$case[0],
				$case[1], $case[2],
				isset( $case[3] ) ? $case[3] : ''
			);
		}

		return $ret;
	}

	public function testFormValidationBlacklist() {
		$this->assertEmpty(
			Html::expandAttributes( array( 'min' => 1, 'max' => 100, 'pattern' => 'abc', 'required' => true, 'step' => 2 ) ),
			'Blacklist form validation attributes.'
		);
		$this->assertEquals(
			' step=any',
			Html::expandAttributes( array( 'min' => 1, 'max' => 100, 'pattern' => 'abc', 'required' => true, 'step' => 'any' ) ),
			'Allow special case "step=any".'
		);
	}
}
