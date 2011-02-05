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
}
