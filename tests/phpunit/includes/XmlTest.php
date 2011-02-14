<?php

class XmlTest extends MediaWikiTestCase {

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

	public function testDateMenu( ) {
		$curYear   = intval(gmdate('Y'));
		$prevYear  = $curYear - 1;

		$curMonth  = intval(gmdate('n'));
		$prevMonth = $curMonth - 1;
		if( $prevMonth == 0 ) { $prevMonth = 12; }
		$nextMonth = $curMonth + 1;
		if( $nextMonth == 13 ) { $nextMonth = 1; }


		$this->assertEquals(
			'<label for="year">From year (and earlier):</label> <input name="year" size="4" value="2011" id="year" maxlength="4" /> <label for="month">From month (and earlier):</label> <select id="month" name="month" class="mw-month-selector"><option value="-1">all</option>
<option value="1">January</option>
<option value="2" selected="selected">February</option>
<option value="3">March</option>
<option value="4">April</option>
<option value="5">May</option>
<option value="6">June</option>
<option value="7">July</option>
<option value="8">August</option>
<option value="9">September</option>
<option value="10">October</option>
<option value="11">November</option>
<option value="12">December</option></select>',
			Xml::dateMenu( 2011, 02 ),
			"Date menu for february 2011"
		);
		$this->assertEquals(
			'<label for="year">From year (and earlier):</label> <input name="year" size="4" value="2011" id="year" maxlength="4" /> <label for="month">From month (and earlier):</label> <select id="month" name="month" class="mw-month-selector"><option value="-1">all</option>
<option value="1">January</option>
<option value="2">February</option>
<option value="3">March</option>
<option value="4">April</option>
<option value="5">May</option>
<option value="6">June</option>
<option value="7">July</option>
<option value="8">August</option>
<option value="9">September</option>
<option value="10">October</option>
<option value="11">November</option>
<option value="12">December</option></select>',
			Xml::dateMenu( 2011, -1),
			"Date menu with negative month for 'All'"
		);
		$this->assertEquals(
			Xml::dateMenu( $curYear, $curMonth ),
			Xml::dateMenu( ''      , $curMonth ),
			"Date menu year is the current one when not specified"
		);
		$this->assertEquals(
			Xml::dateMenu( $prevYear, $nextMonth ),
			Xml::dateMenu( '', $nextMonth ),
			"Date menu next month is 11 months ago"
		);

		# FIXME: please note there is no year there!
		$this->assertEquals(
			'<label for="year">From year (and earlier):</label> <input name="year" size="4" value="" id="year" maxlength="4" /> <label for="month">From month (and earlier):</label> <select id="month" name="month" class="mw-month-selector"><option value="-1">all</option>
<option value="1">January</option>
<option value="2">February</option>
<option value="3">March</option>
<option value="4">April</option>
<option value="5">May</option>
<option value="6">June</option>
<option value="7">July</option>
<option value="8">August</option>
<option value="9">September</option>
<option value="10">October</option>
<option value="11">November</option>
<option value="12">December</option></select>',
			Xml::dateMenu( '', ''),
			"Date menu with neither year or month"
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
	function testLabelAttributeCanOnlyBeClass() {
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
			'<label for="id" class="nice">name</label>',
			Xml::label( 'name', 'id', array(
				'generated' => true,
				'class' => 'nice',
				'anotherattr' => 'value',
				)
			),
			'label() skip all attributes but "class"'
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
			'["a", 1]',
			Xml::encodeJsVar( array( 'a', 1 ) ),
			'encodeJsVar() with array'
		);
		$this->assertEquals(
			'{"a": "a", "b": 1}',
			Xml::encodeJsVar( array( 'a' => 'a', 'b' => 1 ) ),
			'encodeJsVar() with associative array'
		);
	}

	function testEncodeJsVarObject() {
		$this->assertEquals(
			'{"a": "a", "b": 1}',
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
