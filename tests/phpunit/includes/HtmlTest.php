<?php

use MediaWiki\MediaWikiServices;
use PHPUnit\Framework\Error\Notice;

class HtmlTest extends MediaWikiIntegrationTestCase {
	private $restoreWarnings;

	protected function setUp() : void {
		parent::setUp();

		$this->setMwGlobals( [
			'wgUseMediaWikiUIEverywhere' => false,
		] );

		$langFactory = MediaWikiServices::getInstance()->getLanguageFactory();
		$contLangObj = $langFactory->getLanguage( 'en' );

		// Hardcode namespaces during test runs,
		// so that html output based on existing namespaces
		// can be properly evaluated.
		$contLangObj->setNamespaces( [
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
		] );
		$this->setContentLang( $contLangObj );

		$userLangObj = $langFactory->getLanguage( 'es' );
		$userLangObj->setNamespaces( [
			-2 => "Medio",
			-1 => "Especial",
			0 => "",
			1 => "Discusión",
			2 => "Usuario",
			3 => "Usuario discusión",
			4 => "Wiki",
			5 => "Wiki discusión",
			6 => "Archivo",
			7 => "Archivo discusión",
			8 => "MediaWiki",
			9 => "MediaWiki discusión",
			10 => "Plantilla",
			11 => "Plantilla discusión",
			12 => "Ayuda",
			13 => "Ayuda discusión",
			14 => "Categoría",
			15 => "Categoría discusión",
			100 => "Personalizado",
			101 => "Personalizado discusión",
		] );
		$this->setUserLang( $userLangObj );

		$this->restoreWarnings = false;
	}

	protected function tearDown() : void {
		if ( $this->restoreWarnings ) {
			$this->restoreWarnings = false;
			Wikimedia\restoreWarnings();
		}

		parent::tearDown();
	}

	/**
	 * @covers Html::openElement
	 */
	public function testOpenElement() {
		$this->expectException( Notice::class );
		$this->expectExceptionMessage( 'given element name with space' );
		Html::openElement( 'span id="x"' );
	}

	/**
	 * @covers Html::element
	 * @covers Html::rawElement
	 * @covers Html::openElement
	 * @covers Html::closeElement
	 */
	public function testElementBasics() {
		$this->assertEquals(
			'<img/>',
			Html::element( 'img', null, '' ),
			'Self-closing tag for short-tag elements'
		);

		$this->assertEquals(
			'<element></element>',
			Html::element( 'element', null, null ),
			'Close tag for empty element (null, null)'
		);

		$this->assertEquals(
			'<element></element>',
			Html::element( 'element', [], '' ),
			'Close tag for empty element (array, string)'
		);
	}

	public function dataXmlMimeType() {
		return [
			// ( $mimetype, $isXmlMimeType )
			# HTML is not an XML MimeType
			[ 'text/html', false ],
			# XML is an XML MimeType
			[ 'text/xml', true ],
			[ 'application/xml', true ],
			# XHTML is an XML MimeType
			[ 'application/xhtml+xml', true ],
			# Make sure other +xml MimeTypes are supported
			# SVG is another random MimeType even though we don't use it
			[ 'image/svg+xml', true ],
			# Complete random other MimeTypes are not XML
			[ 'text/plain', false ],
		];
	}

	/**
	 * @dataProvider dataXmlMimeType
	 * @covers Html::isXmlMimeType
	 */
	public function testXmlMimeType( $mimetype, $isXmlMimeType ) {
		$this->assertEquals( $isXmlMimeType, Html::isXmlMimeType( $mimetype ) );
	}

	/**
	 * @covers Html::expandAttributes
	 */
	public function testExpandAttributesSkipsNullAndFalse() {
		# ## EMPTY ########
		$this->assertEmpty(
			Html::expandAttributes( [ 'foo' => null ] ),
			'skip keys with null value'
		);
		$this->assertEmpty(
			Html::expandAttributes( [ 'foo' => false ] ),
			'skip keys with false value'
		);
		$this->assertEquals(
			' foo=""',
			Html::expandAttributes( [ 'foo' => '' ] ),
			'keep keys with an empty string'
		);
	}

	/**
	 * @covers Html::expandAttributes
	 */
	public function testExpandAttributesForBooleans() {
		$this->assertSame(
			'',
			Html::expandAttributes( [ 'selected' => false ] ),
			'Boolean attributes do not generates output when value is false'
		);
		$this->assertSame(
			'',
			Html::expandAttributes( [ 'selected' => null ] ),
			'Boolean attributes do not generates output when value is null'
		);

		$this->assertEquals(
			' selected=""',
			Html::expandAttributes( [ 'selected' => true ] ),
			'Boolean attributes have no value when value is true'
		);
		$this->assertEquals(
			' selected=""',
			Html::expandAttributes( [ 'selected' ] ),
			'Boolean attributes have no value when value is true (passed as numerical array)'
		);
	}

	/**
	 * @covers Html::expandAttributes
	 */
	public function testExpandAttributesForNumbers() {
		$this->assertEquals(
			' value="1"',
			Html::expandAttributes( [ 'value' => 1 ] ),
			'Integer value is cast to a string'
		);
		$this->assertEquals(
			' value="1.1"',
			Html::expandAttributes( [ 'value' => 1.1 ] ),
			'Float value is cast to a string'
		);
	}

	/**
	 * @covers Html::expandAttributes
	 */
	public function testExpandAttributesForObjects() {
		$this->assertEquals(
			' value="stringValue"',
			Html::expandAttributes( [ 'value' => new HtmlTestValue() ] ),
			'Object value is converted to a string'
		);
	}

	/**
	 * Please note it output a string prefixed with a space!
	 * @covers Html::expandAttributes
	 */
	public function testExpandAttributesVariousExpansions() {
		# ## NOT EMPTY ####
		$this->assertEquals(
			' empty_string=""',
			Html::expandAttributes( [ 'empty_string' => '' ] ),
			'Empty string is always quoted'
		);
		$this->assertEquals(
			' key="value"',
			Html::expandAttributes( [ 'key' => 'value' ] ),
			'Simple string value needs no quotes'
		);
		$this->assertEquals(
			' one="1"',
			Html::expandAttributes( [ 'one' => 1 ] ),
			'Number 1 value needs no quotes'
		);
		$this->assertEquals(
			' zero="0"',
			Html::expandAttributes( [ 'zero' => 0 ] ),
			'Number 0 value needs no quotes'
		);
	}

	/**
	 * Html::expandAttributes has special features for HTML
	 * attributes that use space separated lists and also
	 * allows arrays to be used as values.
	 * @covers Html::expandAttributes
	 */
	public function testExpandAttributesListValueAttributes() {
		# ## STRING VALUES
		$this->assertEquals(
			' class="redundant spaces here"',
			Html::expandAttributes( [ 'class' => ' redundant  spaces  here  ' ] ),
			'Normalization should strip redundant spaces'
		);
		$this->assertEquals(
			' class="foo bar"',
			Html::expandAttributes( [ 'class' => 'foo bar foo bar bar' ] ),
			'Normalization should remove duplicates in string-lists'
		);
		# ## "EMPTY" ARRAY VALUES
		$this->assertEquals(
			' class=""',
			Html::expandAttributes( [ 'class' => [] ] ),
			'Value with an empty array'
		);
		$this->assertEquals(
			' class=""',
			Html::expandAttributes( [ 'class' => [ null, '', ' ', '  ' ] ] ),
			'Array with null, empty string and spaces'
		);
		# ## NON-EMPTY ARRAY VALUES
		$this->assertEquals(
			' class="foo bar"',
			Html::expandAttributes( [ 'class' => [
				'foo',
				'bar',
				'foo',
				'bar',
				'bar',
			] ] ),
			'Normalization should remove duplicates in the array'
		);
		$this->assertEquals(
			' class="foo bar"',
			Html::expandAttributes( [ 'class' => [
				'foo bar',
				'bar foo',
				'foo',
				'bar bar',
			] ] ),
			'Normalization should remove duplicates in string-lists in the array'
		);
	}

	/**
	 * Test feature added by r96188, let pass attributes values as
	 * a PHP array. Restricted to class,rel, accesskey.
	 * @covers Html::expandAttributes
	 */
	public function testExpandAttributesSpaceSeparatedAttributesWithBoolean() {
		$this->assertEquals(
			' class="booltrue one"',
			Html::expandAttributes( [ 'class' => [
				'booltrue' => true,
				'one' => 1,

				# Method use isset() internally, make sure we do discard
				# attributes values which have been assigned well known values
				'emptystring' => '',
				'boolfalse' => false,
				'zero' => 0,
				'null' => null,
			] ] )
		);
	}

	/**
	 * How do we handle duplicate keys in HTML attributes expansion?
	 * We could pass a "class" the values: 'GREEN' and [ 'GREEN' => false ]
	 * The latter will take precedence.
	 *
	 * Feature added by r96188
	 * @covers Html::expandAttributes
	 */
	public function testValueIsAuthoritativeInSpaceSeparatedAttributesArrays() {
		$this->assertEquals(
			' class=""',
			Html::expandAttributes( [ 'class' => [
				'GREEN',
				'GREEN' => false,
				'GREEN',
			] ] )
		);
	}

	/**
	 * @covers Html::expandAttributes
	 */
	public function testExpandAttributes_ArrayOnNonListValueAttribute_ThrowsException() {
		// Real-life test case found in the Popups extension (see Gerrit cf0fd64),
		// when used with an outdated BetaFeatures extension (see Gerrit deda1e7)
		$this->expectException( MWException::class );
		Html::expandAttributes( [
			'src' => [
				'ltr' => 'ltr.svg',
				'rtl' => 'rtl.svg'
			]
		] );
	}

	/**
	 * @covers Html::namespaceSelector
	 * @covers Html::namespaceSelectorOptions
	 */
	public function testNamespaceSelector() {
		$this->assertEquals(
			'<select id="namespace" name="namespace">' . "\n" .
				'<option value="0">(Principal)</option>' . "\n" .
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
			'<label for="mw-test-namespace">Select a namespace:</label>' . "\u{00A0}" .
				'<select id="mw-test-namespace" name="wpNamespace">' . "\n" .
				'<option value="all">todos</option>' . "\n" .
				'<option value="0">(Principal)</option>' . "\n" .
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
				[ 'selected' => '2', 'all' => 'all', 'label' => 'Select a namespace:' ],
				[ 'name' => 'wpNamespace', 'id' => 'mw-test-namespace' ]
			),
			'Basic namespace selector with custom values'
		);

		$this->assertEquals(
			'<label for="namespace">Select a namespace:</label>' . "\u{00A0}" .
				'<select id="namespace" name="namespace">' . "\n" .
				'<option value="0">(Principal)</option>' . "\n" .
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
				[ 'label' => 'Select a namespace:' ]
			),
			'Basic namespace selector with a custom label but no id attribtue for the <select>'
		);

		$this->assertEquals(
			'<select id="namespace" name="namespace">' . "\n" .
				'<option value="0">(Principal)</option>' . "\n" .
				'<option value="1">Discusión</option>' . "\n" .
				'<option value="2">Usuario</option>' . "\n" .
				'<option value="3">Usuario discusión</option>' . "\n" .
				'<option value="4">Wiki</option>' . "\n" .
				'<option value="5">Wiki discusión</option>' . "\n" .
				'<option value="6">Archivo</option>' . "\n" .
				'<option value="7">Archivo discusión</option>' . "\n" .
				'<option value="8">MediaWiki</option>' . "\n" .
				'<option value="9">MediaWiki discusión</option>' . "\n" .
				'<option value="10">Plantilla</option>' . "\n" .
				'<option value="11">Plantilla discusión</option>' . "\n" .
				'<option value="12">Ayuda</option>' . "\n" .
				'<option value="13">Ayuda discusión</option>' . "\n" .
				'<option value="14">Categoría</option>' . "\n" .
				'<option value="15">Categoría discusión</option>' . "\n" .
				'<option value="100">Personalizado</option>' . "\n" .
				'<option value="101">Personalizado discusión</option>' . "\n" .
				'</select>',
			Html::namespaceSelector(
				[ 'in-user-lang' => true ]
			),
			'Basic namespace selector in user language'
		);
	}

	/**
	 * @covers Html::namespaceSelector
	 */
	public function testCanFilterOutNamespaces() {
		$this->assertEquals(
			'<select id="namespace" name="namespace">' . "\n" .
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
				[ 'exclude' => [ 0, 1, 3, 100, 101 ] ]
			),
			'Namespace selector namespace filtering.'
		);
		$this->assertEquals(
			'<select id="namespace" name="namespace">' . "\n" .
				'<option value="" selected="">todos</option>' . "\n" .
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
				[ 'exclude' => [ 0, 1, 3, 100, 101 ], 'all' => '' ]
			),
			'Namespace selector namespace filtering with empty custom "all" option.'
		);
	}

	/**
	 * @covers Html::namespaceSelector
	 */
	public function testCanDisableANamespaces() {
		$this->assertEquals(
			'<select id="namespace" name="namespace">' . "\n" .
				'<option disabled="" value="0">(Principal)</option>' . "\n" .
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
			Html::namespaceSelector( [
				'disable' => [ 0, 1, 2, 3, 4 ]
			] ),
			'Namespace selector namespace disabling'
		);
	}

	/**
	 * @dataProvider provideHtml5InputTypes
	 * @covers Html::element
	 */
	public function testHtmlElementAcceptsNewHtml5TypesInHtml5Mode( $HTML5InputType ) {
		$this->assertEquals(
			'<input type="' . $HTML5InputType . '"/>',
			Html::element( 'input', [ 'type' => $HTML5InputType ] ),
			'In HTML5, Html::element() should accept type="' . $HTML5InputType . '"'
		);
	}

	/**
	 * @covers Html::warningBox
	 * @covers Html::messageBox
	 */
	public function testWarningBox() {
		$this->assertEquals(
			Html::warningBox( 'warn' ),
			'<div class="warningbox">warn</div>'
		);
	}

	/**
	 * @covers Html::errorBox
	 * @covers Html::messageBox
	 */
	public function testErrorBox() {
		$this->assertEquals(
			Html::errorBox( 'err' ),
			'<div class="errorbox">err</div>'
		);
		$this->assertEquals(
			Html::errorBox( 'err', 'heading', 'errorbox-custom-class' ),
			'<div class="errorbox errorbox-custom-class"><h2>heading</h2>err</div>'
		);
		$this->assertEquals(
			Html::errorBox( 'err', '0', '' ),
			'<div class="errorbox"><h2>0</h2>err</div>'
		);
	}

	/**
	 * @covers Html::successBox
	 * @covers Html::messageBox
	 */
	public function testSuccessBox() {
		$this->assertEquals(
			Html::successBox( 'great' ),
			'<div class="successbox">great</div>'
		);
		$this->assertEquals(
			Html::successBox( '<script>beware no escaping!</script>' ),
			'<div class="successbox"><script>beware no escaping!</script></div>'
		);
	}

	/**
	 * List of input element types values introduced by HTML5
	 * Full list at https://www.w3.org/TR/html-markup/input.html
	 */
	public static function provideHtml5InputTypes() {
		$types = [
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
		];

		foreach ( $types as $type ) {
			yield [ $type ];
		}
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
		$cases = [];

		# ## Generic cases, match $attribDefault static array
		$cases[] = [ '<area/>',
			'area', [ 'shape' => 'rect' ]
		];

		$cases[] = [ '<button type="submit"></button>',
			'button', [ 'formaction' => 'GET' ]
		];
		$cases[] = [ '<button type="submit"></button>',
			'button', [ 'formenctype' => 'application/x-www-form-urlencoded' ]
		];

		$cases[] = [ '<canvas></canvas>',
			'canvas', [ 'height' => '150' ]
		];
		$cases[] = [ '<canvas></canvas>',
			'canvas', [ 'width' => '300' ]
		];
		# Also check with numeric values
		$cases[] = [ '<canvas></canvas>',
			'canvas', [ 'height' => 150 ]
		];
		$cases[] = [ '<canvas></canvas>',
			'canvas', [ 'width' => 300 ]
		];

		$cases[] = [ '<form></form>',
			'form', [ 'action' => 'GET' ]
		];
		$cases[] = [ '<form></form>',
			'form', [ 'autocomplete' => 'on' ]
		];
		$cases[] = [ '<form></form>',
			'form', [ 'enctype' => 'application/x-www-form-urlencoded' ]
		];

		$cases[] = [ '<input/>',
			'input', [ 'formaction' => 'GET' ]
		];
		$cases[] = [ '<input/>',
			'input', [ 'type' => 'text' ]
		];

		$cases[] = [ '<keygen/>',
			'keygen', [ 'keytype' => 'rsa' ]
		];

		$cases[] = [ '<link/>',
			'link', [ 'media' => 'all' ]
		];

		$cases[] = [ '<menu></menu>',
			'menu', [ 'type' => 'list' ]
		];

		$cases[] = [ '<script></script>',
			'script', [ 'type' => 'text/javascript' ]
		];

		$cases[] = [ '<style></style>',
			'style', [ 'media' => 'all' ]
		];
		$cases[] = [ '<style></style>',
			'style', [ 'type' => 'text/css' ]
		];

		$cases[] = [ '<textarea></textarea>',
			'textarea', [ 'wrap' => 'soft' ]
		];

		# ## SPECIFIC CASES

		# <link type="text/css">
		$cases[] = [ '<link/>',
			'link', [ 'type' => 'text/css' ]
		];

		# <input> specific handling
		$cases[] = [ '<input type="checkbox"/>',
			'input', [ 'type' => 'checkbox', 'value' => 'on' ],
			'Default value "on" is stripped of checkboxes',
		];
		$cases[] = [ '<input type="radio"/>',
			'input', [ 'type' => 'radio', 'value' => 'on' ],
			'Default value "on" is stripped of radio buttons',
		];
		$cases[] = [ '<input type="submit" value="Submit"/>',
			'input', [ 'type' => 'submit', 'value' => 'Submit' ],
			'Default value "Submit" is kept on submit buttons (for possible l10n issues)',
		];
		$cases[] = [ '<input type="color"/>',
			'input', [ 'type' => 'color', 'value' => '' ],
		];
		$cases[] = [ '<input type="range"/>',
			'input', [ 'type' => 'range', 'value' => '' ],
		];

		# <button> specific handling
		# see remarks on https://msdn.microsoft.com/library/ms535211(v=vs.85).aspx
		$cases[] = [ '<button type="submit"></button>',
			'button', [ 'type' => 'submit' ],
			'According to standard the default type is "submit". '
				. 'Depending on compatibility mode IE might use "button", instead.',
		];

		# <select> specific handling
		$cases[] = [ '<select multiple=""></select>',
			'select', [ 'size' => '4', 'multiple' => true ],
		];
		# .. with numeric value
		$cases[] = [ '<select multiple=""></select>',
			'select', [ 'size' => 4, 'multiple' => true ],
		];
		$cases[] = [ '<select></select>',
			'select', [ 'size' => '1', 'multiple' => false ],
		];
		# .. with numeric value
		$cases[] = [ '<select></select>',
			'select', [ 'size' => 1, 'multiple' => false ],
		];

		# Passing an array as value
		$cases[] = [ '<a class="css-class-one css-class-two"></a>',
			'a', [ 'class' => [ 'css-class-one', 'css-class-two' ] ],
			"dropDefaults accepts values given as an array"
		];

		# FIXME: doDropDefault should remove defaults given in an array
		# Expected should be '<a></a>'
		$cases[] = [ '<a class=""></a>',
			'a', [ 'class' => [ '', '' ] ],
			"dropDefaults accepts values given as an array"
		];

		return $cases;
	}

	/**
	 * @covers Html::input
	 */
	public function testWrapperInput() {
		$this->assertEquals(
			'<input type="radio" value="testval" name="testname"/>',
			Html::input( 'testname', 'testval', 'radio' ),
			'Input wrapper with type and value.'
		);
		$this->assertEquals(
			'<input name="testname"/>',
			Html::input( 'testname' ),
			'Input wrapper with all default values.'
		);
	}

	/**
	 * @covers Html::check
	 */
	public function testWrapperCheck() {
		$this->assertEquals(
			'<input type="checkbox" value="1" name="testname"/>',
			Html::check( 'testname' ),
			'Checkbox wrapper unchecked.'
		);
		$this->assertEquals(
			'<input checked="" type="checkbox" value="1" name="testname"/>',
			Html::check( 'testname', true ),
			'Checkbox wrapper checked.'
		);
		$this->assertEquals(
			'<input type="checkbox" value="testval" name="testname"/>',
			Html::check( 'testname', false, [ 'value' => 'testval' ] ),
			'Checkbox wrapper with a value override.'
		);
	}

	/**
	 * @covers Html::radio
	 */
	public function testWrapperRadio() {
		$this->assertEquals(
			'<input type="radio" value="1" name="testname"/>',
			Html::radio( 'testname' ),
			'Radio wrapper unchecked.'
		);
		$this->assertEquals(
			'<input checked="" type="radio" value="1" name="testname"/>',
			Html::radio( 'testname', true ),
			'Radio wrapper checked.'
		);
		$this->assertEquals(
			'<input type="radio" value="testval" name="testname"/>',
			Html::radio( 'testname', false, [ 'value' => 'testval' ] ),
			'Radio wrapper with a value override.'
		);
	}

	/**
	 * @covers Html::label
	 */
	public function testWrapperLabel() {
		$this->assertEquals(
			'<label for="testid">testlabel</label>',
			Html::label( 'testlabel', 'testid' ),
			'Label wrapper'
		);
	}

	public static function provideSrcSetImages() {
		return [
			[ [], '', 'when there are no images, return empty string' ],
			[
				[ '1x' => '1x.png', '1.5x' => '1_5x.png', '2x' => '2x.png' ],
				'1x.png 1x, 1_5x.png 1.5x, 2x.png 2x',
				'pixel depth keys may include a trailing "x"'
			],
			[
				[ '1'  => '1x.png', '1.5' => '1_5x.png', '2'  => '2x.png' ],
				'1x.png 1x, 1_5x.png 1.5x, 2x.png 2x',
				'pixel depth keys may omit a trailing "x"'
			],
			[
				[ '1'  => 'small.png', '1.5' => 'large.png', '2'  => 'large.png' ],
				'small.png 1x, large.png 1.5x',
				'omit larger duplicates'
			],
			[
				[ '1'  => 'small.png', '2'  => 'large.png', '1.5' => 'large.png' ],
				'small.png 1x, large.png 1.5x',
				'omit larger duplicates in irregular order'
			],
		];
	}

	/**
	 * @dataProvider provideSrcSetImages
	 * @covers Html::srcSet
	 */
	public function testSrcSet( $images, $expected, $message ) {
		$this->assertEquals( Html::srcSet( $images ), $expected, $message );
	}

	public static function provideInlineScript() {
		return [
			'Empty' => [
				'',
				'<script></script>'
			],
			'Simple' => [
				'EXAMPLE.label("foo");',
				'<script>EXAMPLE.label("foo");</script>'
			],
			'Ampersand' => [
				'EXAMPLE.is(a && b);',
				'<script>EXAMPLE.is(a && b);</script>'
			],
			'HTML' => [
				'EXAMPLE.label("<a>");',
				'<script>EXAMPLE.label("<a>");</script>'
			],
			'Script closing string (lower)' => [
				'EXAMPLE.label("</script>");',
				'<script>/* ERROR: Invalid script */</script>',
				true,
			],
			'Script closing with non-standard attributes (mixed)' => [
				'EXAMPLE.label("</SCriPT and STyLE>");',
				'<script>/* ERROR: Invalid script */</script>',
				true,
			],
			'HTML-comment-open and script-open' => [
				// In HTML, <script> contents aren't just plain CDATA until </script>,
				// there are levels of escaping modes, and the below sequence puts an
				// HTML parser in a state where </script> would *not* close the script.
				// https://html.spec.whatwg.org/multipage/parsing.html#script-data-double-escape-end-state
				'var a = "<!--<script>";',
				'<script>/* ERROR: Invalid script */</script>',
				true,
			],
		];
	}

	/**
	 * @dataProvider provideInlineScript
	 * @covers Html::inlineScript
	 */
	public function testInlineScript( $code, $expected, $error = false ) {
		if ( $error ) {
			Wikimedia\suppressWarnings();
			$this->restoreWarnings = true;
		}
		$this->assertSame( Html::inlineScript( $code ), $expected );
	}
}

class HtmlTestValue {
	public function __toString() {
		return 'stringValue';
	}
}
