<?php

/**
 * @group Xml
 */
class XmlTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		$langObj = Language::factory( 'en' );
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
			100 => 'Custom',
			101 => 'Custom_talk',
		) );

		$this->setMwGlobals( array(
			'wgLang' => $langObj,
			'wgWellFormedXml' => true,
		) );
	}

	/**
	 * @covers Xml::expandAttributes
	 */
	public function testExpandAttributes() {
		$this->assertNull( Xml::expandAttributes( null ),
			'Converting a null list of attributes'
		);
		$this->assertEquals( '', Xml::expandAttributes( array() ),
			'Converting an empty list of attributes'
		);
	}

	/**
	 * @covers Xml::expandAttributes
	 */
	public function testExpandAttributesException() {
		$this->setExpectedException( 'MWException' );
		Xml::expandAttributes( 'string' );
	}

	/**
	 * @covers Xml::element
	 */
	public function testElementOpen() {
		$this->assertEquals(
			'<element>',
			Xml::element( 'element', null, null ),
			'Opening element with no attributes'
		);
	}

	/**
	 * @covers Xml::element
	 */
	public function testElementEmpty() {
		$this->assertEquals(
			'<element />',
			Xml::element( 'element', null, '' ),
			'Terminated empty element'
		);
	}

	/**
	 * @covers Xml::input
	 */
	public function testElementInputCanHaveAValueOfZero() {
		$this->assertEquals(
			'<input name="name" value="0" />',
			Xml::input( 'name', false, 0 ),
			'Input with a value of 0 (bug 23797)'
		);
	}

	/**
	 * @covers Xml::element
	 */
	public function testElementEscaping() {
		$this->assertEquals(
			'<element>hello &lt;there&gt; you &amp; you</element>',
			Xml::element( 'element', null, 'hello <there> you & you' ),
			'Element with no attributes and content that needs escaping'
		);
	}

	/**
	 * @covers Xml::escapeTagsOnly
	 */
	public function testEscapeTagsOnly() {
		$this->assertEquals( '&quot;&gt;&lt;', Xml::escapeTagsOnly( '"><' ),
			'replace " > and < with their HTML entitites'
		);
	}

	/**
	 * @covers Xml::element
	 */
	public function testElementAttributes() {
		$this->assertEquals(
			'<element key="value" <>="&lt;&gt;">',
			Xml::element( 'element', array( 'key' => 'value', '<>' => '<>' ), null ),
			'Element attributes, keys are not escaped'
		);
	}

	/**
	 * @covers Xml::openElement
	 */
	public function testOpenElement() {
		$this->assertEquals(
			'<element k="v">',
			Xml::openElement( 'element', array( 'k' => 'v' ) ),
			'openElement() shortcut'
		);
	}

	/**
	 * @covers Xml::closeElement
	 */
	public function testCloseElement() {
		$this->assertEquals( '</element>', Xml::closeElement( 'element' ), 'closeElement() shortcut' );
	}

	/**
	 * @covers Xml::dateMenu
	 */
	public function testDateMenu() {
		$curYear = intval( gmdate( 'Y' ) );
		$prevYear = $curYear - 1;

		$curMonth = intval( gmdate( 'n' ) );

		$nextMonth = $curMonth + 1;
		if ( $nextMonth == 13 ) {
			$nextMonth = 1;
		}

		$this->assertEquals(
			'<label for="year">From year (and earlier):</label> <input id="year" maxlength="4" size="7" type="number" value="2011" name="year" /> <label for="month">From month (and earlier):</label> <select id="month" name="month" class="mw-month-selector"><option value="-1">all</option>' . "\n" .
				'<option value="1">January</option>' . "\n" .
				'<option value="2" selected="">February</option>' . "\n" .
				'<option value="3">March</option>' . "\n" .
				'<option value="4">April</option>' . "\n" .
				'<option value="5">May</option>' . "\n" .
				'<option value="6">June</option>' . "\n" .
				'<option value="7">July</option>' . "\n" .
				'<option value="8">August</option>' . "\n" .
				'<option value="9">September</option>' . "\n" .
				'<option value="10">October</option>' . "\n" .
				'<option value="11">November</option>' . "\n" .
				'<option value="12">December</option></select>',
			Xml::dateMenu( 2011, 02 ),
			"Date menu for february 2011"
		);
		$this->assertEquals(
			'<label for="year">From year (and earlier):</label> <input id="year" maxlength="4" size="7" type="number" value="2011" name="year" /> <label for="month">From month (and earlier):</label> <select id="month" name="month" class="mw-month-selector"><option value="-1">all</option>' . "\n" .
				'<option value="1">January</option>' . "\n" .
				'<option value="2">February</option>' . "\n" .
				'<option value="3">March</option>' . "\n" .
				'<option value="4">April</option>' . "\n" .
				'<option value="5">May</option>' . "\n" .
				'<option value="6">June</option>' . "\n" .
				'<option value="7">July</option>' . "\n" .
				'<option value="8">August</option>' . "\n" .
				'<option value="9">September</option>' . "\n" .
				'<option value="10">October</option>' . "\n" .
				'<option value="11">November</option>' . "\n" .
				'<option value="12">December</option></select>',
			Xml::dateMenu( 2011, -1 ),
			"Date menu with negative month for 'All'"
		);
		$this->assertEquals(
			Xml::dateMenu( $curYear, $curMonth ),
			Xml::dateMenu( '', $curMonth ),
			"Date menu year is the current one when not specified"
		);

		$wantedYear = $nextMonth == 1 ? $curYear : $prevYear;
		$this->assertEquals(
			Xml::dateMenu( $wantedYear, $nextMonth ),
			Xml::dateMenu( '', $nextMonth ),
			"Date menu next month is 11 months ago"
		);

		$this->assertEquals(
			'<label for="year">From year (and earlier):</label> <input id="year" maxlength="4" size="7" type="number" name="year" /> <label for="month">From month (and earlier):</label> <select id="month" name="month" class="mw-month-selector"><option value="-1">all</option>' . "\n" .
				'<option value="1">January</option>' . "\n" .
				'<option value="2">February</option>' . "\n" .
				'<option value="3">March</option>' . "\n" .
				'<option value="4">April</option>' . "\n" .
				'<option value="5">May</option>' . "\n" .
				'<option value="6">June</option>' . "\n" .
				'<option value="7">July</option>' . "\n" .
				'<option value="8">August</option>' . "\n" .
				'<option value="9">September</option>' . "\n" .
				'<option value="10">October</option>' . "\n" .
				'<option value="11">November</option>' . "\n" .
				'<option value="12">December</option></select>',
			Xml::dateMenu( '', '' ),
			"Date menu with neither year or month"
		);
	}

	/**
	 * @covers Xml::textarea
	 */
	public function testTextareaNoContent() {
		$this->assertEquals(
			'<textarea name="name" id="name" cols="40" rows="5"></textarea>',
			Xml::textarea( 'name', '' ),
			'textarea() with not content'
		);
	}

	/**
	 * @covers Xml::textarea
	 */
	public function testTextareaAttribs() {
		$this->assertEquals(
			'<textarea name="name" id="name" cols="20" rows="10">&lt;txt&gt;</textarea>',
			Xml::textarea( 'name', '<txt>', 20, 10 ),
			'textarea() with custom attribs'
		);
	}

	/**
	 * @covers Xml::label
	 */
	public function testLabelCreation() {
		$this->assertEquals(
			'<label for="id">name</label>',
			Xml::label( 'name', 'id' ),
			'label() with no attribs'
		);
	}

	/**
	 * @covers Xml::label
	 */
	public function testLabelAttributeCanOnlyBeClassOrTitle() {
		$this->assertEquals(
			'<label for="id">name</label>',
			Xml::label( 'name', 'id', array( 'generated' => true ) ),
			'label() can not be given a generated attribute'
		);
		$this->assertEquals(
			'<label for="id" class="nice">name</label>',
			Xml::label( 'name', 'id', array( 'class' => 'nice' ) ),
			'label() can get a class attribute'
		);
		$this->assertEquals(
			'<label for="id" title="nice tooltip">name</label>',
			Xml::label( 'name', 'id', array( 'title' => 'nice tooltip' ) ),
			'label() can get a title attribute'
		);
		$this->assertEquals(
			'<label for="id" class="nice" title="nice tooltip">name</label>',
			Xml::label( 'name', 'id', array(
					'generated' => true,
					'class' => 'nice',
					'title' => 'nice tooltip',
					'anotherattr' => 'value',
				)
			),
			'label() skip all attributes but "class" and "title"'
		);
	}

	/**
	 * @covers Xml::languageSelector
	 */
	public function testLanguageSelector() {
		$select = Xml::languageSelector( 'en', true, null,
			array( 'id' => 'testlang' ), wfMessage( 'yourlanguage' ) );
		$this->assertEquals(
			'<label for="testlang">Language:</label>',
			$select[0]
		);
	}

	/**
	 * @covers Xml::escapeJsString
	 */
	public function testEscapeJsStringSpecialChars() {
		$this->assertEquals(
			'\\\\\r\n',
			Xml::escapeJsString( "\\\r\n" ),
			'escapeJsString() with special characters'
		);
	}

	/**
	 * @covers Xml::encodeJsVar
	 */
	public function testEncodeJsVarBoolean() {
		$this->assertEquals(
			'true',
			Xml::encodeJsVar( true ),
			'encodeJsVar() with boolean'
		);
	}

	/**
	 * @covers Xml::encodeJsVar
	 */
	public function testEncodeJsVarNull() {
		$this->assertEquals(
			'null',
			Xml::encodeJsVar( null ),
			'encodeJsVar() with null'
		);
	}

	/**
	 * @covers Xml::encodeJsVar
	 */
	public function testEncodeJsVarArray() {
		$this->assertEquals(
			'["a",1]',
			Xml::encodeJsVar( array( 'a', 1 ) ),
			'encodeJsVar() with array'
		);
		$this->assertEquals(
			'{"a":"a","b":1}',
			Xml::encodeJsVar( array( 'a' => 'a', 'b' => 1 ) ),
			'encodeJsVar() with associative array'
		);
	}

	/**
	 * @covers Xml::encodeJsVar
	 */
	public function testEncodeJsVarObject() {
		$this->assertEquals(
			'{"a":"a","b":1}',
			Xml::encodeJsVar( (object)array( 'a' => 'a', 'b' => 1 ) ),
			'encodeJsVar() with object'
		);
	}

	/**
	 * @covers Xml::encodeJsVar
	 */
	public function testEncodeJsVarInt() {
		$this->assertEquals(
			'123456',
			Xml::encodeJsVar( 123456 ),
			'encodeJsVar() with int'
		);
	}

	/**
	 * @covers Xml::encodeJsVar
	 */
	public function testEncodeJsVarFloat() {
		$this->assertEquals(
			'1.23456',
			Xml::encodeJsVar( 1.23456 ),
			'encodeJsVar() with float'
		);
	}

	/**
	 * @covers Xml::encodeJsVar
	 */
	public function testEncodeJsVarIntString() {
		$this->assertEquals(
			'"123456"',
			Xml::encodeJsVar( '123456' ),
			'encodeJsVar() with int-like string'
		);
	}

	/**
	 * @covers Xml::encodeJsVar
	 */
	public function testEncodeJsVarFloatString() {
		$this->assertEquals(
			'"1.23456"',
			Xml::encodeJsVar( '1.23456' ),
			'encodeJsVar() with float-like string'
		);
	}
}
