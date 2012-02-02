<?php

class XmlTest extends MediaWikiTestCase {
	private static $oldLang;
	private static $oldNamespaces;

	public function setUp() {
		global $wgLang, $wgContLang;

		self::$oldLang = $wgLang;
		$wgLang = Language::factory( 'en' );

		// Hardcode namespaces during test runs,
		// so that html output based on existing namespaces
		// can be properly evaluated.
		self::$oldNamespaces = $wgContLang->getNamespaces();
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
		global $wgLang, $wgContLang;
		$wgLang = self::$oldLang;
		
		$wgContLang->setNamespaces( self::$oldNamespaces );
	}

	public function testExpandAttributes() {
		$this->assertNull( Xml::expandAttributes(null),
			'Converting a null list of attributes'
		);
		$this->assertEquals( '', Xml::expandAttributes( array() ),
			'Converting an empty list of attributes'
		);
	}

	public function testExpandAttributesException() {
		$this->setExpectedException('MWException');
		Xml::expandAttributes('string');
	}

	function testElementOpen() {
		$this->assertEquals(
			'<element>',
			Xml::element( 'element', null, null ),
			'Opening element with no attributes'
		);
	}

	function testElementEmpty() {
		$this->assertEquals(
			'<element />',
			Xml::element( 'element', null, '' ),
			'Terminated empty element'
		);
	}

	function testElementInputCanHaveAValueOfZero() {
		$this->assertEquals(
			'<input name="name" value="0" />',
			Xml::input( 'name', false, 0 ),
			'Input with a value of 0 (bug 23797)'
		);
	}
	function testElementEscaping() {
		$this->assertEquals(
			'<element>hello &lt;there&gt; you &amp; you</element>',
			Xml::element( 'element', null, 'hello <there> you & you' ),
			'Element with no attributes and content that needs escaping'
		);
	}

	public function testEscapeTagsOnly() {
		$this->assertEquals( '&quot;&gt;&lt;', Xml::escapeTagsOnly( '"><' ),
			'replace " > and < with their HTML entitites'
		);
	}

	function testElementAttributes() {
		$this->assertEquals(
			'<element key="value" <>="&lt;&gt;">',
			Xml::element( 'element', array( 'key' => 'value', '<>' => '<>' ), null ),
			'Element attributes, keys are not escaped'
		);
	}

	function testOpenElement() {
		$this->assertEquals(
			'<element k="v">',
			Xml::openElement( 'element', array( 'k' => 'v' ) ),
			'openElement() shortcut'
		);
	}

	function testCloseElement() {
		$this->assertEquals( '</element>', Xml::closeElement( 'element' ), 'closeElement() shortcut' );
	}

	/**
	 * @group Broken
	 */
	public function testDateMenu( ) {
		$curYear   = intval(gmdate('Y'));
		$prevYear  = $curYear - 1;

		$curMonth  = intval(gmdate('n'));
		$prevMonth = $curMonth - 1;
		if( $prevMonth == 0 ) { $prevMonth = 12; }
		$nextMonth = $curMonth + 1;
		if( $nextMonth == 13 ) { $nextMonth = 1; }

		$this->assertEquals(
			'<label for="year">From year (and earlier):</label> <input name="year" size="4" value="2011" id="year" maxlength="4" /> <label for="month">From month (and earlier):</label> <select id="month" name="month" class="mw-month-selector"><option value="-1">all</option>' . "\n" .
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
			'<label for="year">From year (and earlier):</label> <input name="year" size="4" value="2011" id="year" maxlength="4" /> <label for="month">From month (and earlier):</label> <select id="month" name="month" class="mw-month-selector"><option value="-1">all</option>' . "\n" .
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
			Xml::dateMenu( 2011, -1),
			"Date menu with negative month for 'All'"
		);
		$this->assertEquals(
			Xml::dateMenu( $curYear, $curMonth ),
			Xml::dateMenu( ''      , $curMonth ),
			"Date menu year is the current one when not specified"
		);

		// @todo FIXME: next month can be in the next year
		// test failing because it is now december
		$this->assertEquals(
			Xml::dateMenu( $prevYear, $nextMonth ),
			Xml::dateMenu( '', $nextMonth ),
			"Date menu next month is 11 months ago"
		);

		# @todo FIXME: Please note there is no year there!
		$this->assertEquals(
			'<label for="year">From year (and earlier):</label> <input name="year" size="4" value="" id="year" maxlength="4" /> <label for="month">From month (and earlier):</label> <select id="month" name="month" class="mw-month-selector"><option value="-1">all</option>' . "\n" .
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

	function testNamespaceSelector() {
		$this->assertEquals(
			'<select class="namespaceselector" id="namespace" name="namespace">' . "\n" .
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
			Xml::namespaceSelector(),
			'Basic namespace selector without custom options'
		);
		$this->assertEquals(
			'<label for="namespace">Select a namespace:</label>' .
'&#160;<select class="namespaceselector" id="namespace" name="myname">' . "\n" .
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
			Xml::namespaceSelector( $selected = '2', $all = 'all', $element_name = 'myname', $label = 'Select a namespace:' ),
			'Basic namespace selector with custom values'
		);
	}


	#
	# textarea
	#
	function testTextareaNoContent() {
		$this->assertEquals(
			'<textarea name="name" id="name" cols="40" rows="5"></textarea>',
			Xml::textarea( 'name', '' ),
			'textarea() with not content'
		);
	}

	function testTextareaAttribs() {
		$this->assertEquals(
			'<textarea name="name" id="name" cols="20" rows="10">&lt;txt&gt;</textarea>',
			Xml::textarea( 'name', '<txt>', 20, 10 ),
			'textarea() with custom attribs'
		);
	}

	#
	# input and label
	#
	function testLabelCreation() {
		$this->assertEquals(
			'<label for="id">name</label>',
			Xml::label( 'name', 'id' ),
			'label() with no attribs'
		);
	}
	function testLabelAttributeCanOnlyBeClassOrTitle() {
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

	#
	# JS
	#
	function testEscapeJsStringSpecialChars() {
		$this->assertEquals(
			'\\\\\r\n',
			Xml::escapeJsString( "\\\r\n" ),
			'escapeJsString() with special characters'
		);
	}

	function testEncodeJsVarBoolean() {
		$this->assertEquals(
			'true',
			Xml::encodeJsVar( true ),
			'encodeJsVar() with boolean'
		);
	}

	function testEncodeJsVarNull() {
		$this->assertEquals(
			'null',
			Xml::encodeJsVar( null ),
			'encodeJsVar() with null'
		);
	}

	function testEncodeJsVarArray() {
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

	function testEncodeJsVarObject() {
		$this->assertEquals(
			'{"a":"a","b":1}',
			Xml::encodeJsVar( (object)array( 'a' => 'a', 'b' => 1 ) ),
			'encodeJsVar() with object'
		);
	}

	function testEncodeJsVarInt() {
		$this->assertEquals(
			'123456',
			Xml::encodeJsVar( 123456 ),
			'encodeJsVar() with int'
		);
	}

	function testEncodeJsVarFloat() {
		$this->assertEquals(
			'1.23456',
			Xml::encodeJsVar( 1.23456 ),
			'encodeJsVar() with float'
		);
	}

	function testEncodeJsVarIntString() {
		$this->assertEquals(
			'"123456"',
			Xml::encodeJsVar( '123456' ),
			'encodeJsVar() with int-like string'
		);
	}

	function testEncodeJsVarFloatString() {
		$this->assertEquals(
			'"1.23456"',
			Xml::encodeJsVar( '1.23456' ),
			'encodeJsVar() with float-like string'
		);
	}
}
