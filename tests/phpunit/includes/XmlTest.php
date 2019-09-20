<?php

/**
 * @group Xml
 */
class XmlTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		$langObj = Language::factory( 'en' );
		$langObj->setNamespaces( [
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
		] );

		$this->setMwGlobals( [
			'wgLang' => $langObj,
			'wgUseMediaWikiUIEverywhere' => false,
		] );
	}

	protected function tearDown() {
		Language::factory( 'en' )->resetNamespaces();
		parent::tearDown();
	}

	/**
	 * @covers Xml::expandAttributes
	 */
	public function testExpandAttributes() {
		$this->assertNull( Xml::expandAttributes( null ),
			'Converting a null list of attributes'
		);
		$this->assertSame( '', Xml::expandAttributes( [] ),
			'Converting an empty list of attributes'
		);
	}

	/**
	 * @covers Xml::expandAttributes
	 */
	public function testExpandAttributesException() {
		$this->setExpectedException( MWException::class );
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
			'Input with a value of 0 (T25797)'
		);
	}

	/**
	 * @covers Xml::element
	 */
	public function testElementEscaping() {
		$this->assertEquals(
			'<element>"hello &lt;there&gt; your\'s &amp; you"</element>',
			Xml::element( 'element', null, '"hello <there> your\'s & you"' ),
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
			Xml::element( 'element', [ 'key' => 'value', '<>' => '<>' ], null ),
			'Element attributes, keys are not escaped'
		);
	}

	/**
	 * @covers Xml::openElement
	 */
	public function testOpenElement() {
		$this->assertEquals(
			'<element k="v">',
			Xml::openElement( 'element', [ 'k' => 'v' ] ),
			'openElement() shortcut'
		);
	}

	/**
	 * @covers Xml::closeElement
	 */
	public function testCloseElement() {
		$this->assertEquals( '</element>', Xml::closeElement( 'element' ), 'closeElement() shortcut' );
	}

	public function provideMonthSelector() {
		global $wgLang;

		$header = '<select name="month" id="month" class="mw-month-selector">';
		$header2 = '<select name="month" id="monthSelector" class="mw-month-selector">';
		$monthsString = '';
		for ( $i = 1; $i < 13; $i++ ) {
			$monthName = $wgLang->getMonthName( $i );
			$monthsString .= "<option value=\"{$i}\">{$monthName}</option>";
			if ( $i !== 12 ) {
				$monthsString .= "\n";
			}
		}
		$monthsString2 = str_replace(
			'<option value="12">December</option>',
			'<option value="12" selected="">December</option>',
			$monthsString
		);
		$end = '</select>';

		$allMonths = "<option value=\"AllMonths\">all</option>\n";
		return [
			[ $header . $monthsString . $end, '', null, 'month' ],
			[ $header . $monthsString2 . $end, 12, null, 'month' ],
			[ $header2 . $monthsString . $end, '', null, 'monthSelector' ],
			[ $header . $allMonths . $monthsString . $end, '', 'AllMonths', 'month' ],

		];
	}

	/**
	 * @covers Xml::monthSelector
	 * @dataProvider provideMonthSelector
	 */
	public function testMonthSelector( $expected, $selected, $allmonths, $id ) {
		$this->assertEquals(
			$expected,
			Xml::monthSelector( $selected, $allmonths, $id )
		);
	}

	/**
	 * @covers Xml::span
	 */
	public function testSpan() {
		$this->assertEquals(
			'<span class="foo" id="testSpan">element</span>',
			Xml::span( 'element', 'foo', [ 'id' => 'testSpan' ] )
		);
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
			'<label for="year">From year (and earlier):</label> ' .
				'<input id="year" maxlength="4" size="7" type="number" value="2011" name="year"/> ' .
				'<label for="month">From month (and earlier):</label> ' .
				'<select name="month" id="month" class="mw-month-selector">' .
				'<option value="-1">all</option>' . "\n" .
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
			'<label for="year">From year (and earlier):</label> ' .
				'<input id="year" maxlength="4" size="7" type="number" value="2011" name="year"/> ' .
				'<label for="month">From month (and earlier):</label> ' .
				'<select name="month" id="month" class="mw-month-selector">' .
				'<option value="-1">all</option>' . "\n" .
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
			'<label for="year">From year (and earlier):</label> ' .
				'<input id="year" maxlength="4" size="7" type="number" name="year"/> ' .
				'<label for="month">From month (and earlier):</label> ' .
				'<select name="month" id="month" class="mw-month-selector">' .
				'<option value="-1">all</option>' . "\n" .
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
			Xml::label( 'name', 'id', [ 'generated' => true ] ),
			'label() can not be given a generated attribute'
		);
		$this->assertEquals(
			'<label for="id" class="nice">name</label>',
			Xml::label( 'name', 'id', [ 'class' => 'nice' ] ),
			'label() can get a class attribute'
		);
		$this->assertEquals(
			'<label for="id" title="nice tooltip">name</label>',
			Xml::label( 'name', 'id', [ 'title' => 'nice tooltip' ] ),
			'label() can get a title attribute'
		);
		$this->assertEquals(
			'<label for="id" class="nice" title="nice tooltip">name</label>',
			Xml::label( 'name', 'id', [
					'generated' => true,
					'class' => 'nice',
					'title' => 'nice tooltip',
					'anotherattr' => 'value',
				]
			),
			'label() skip all attributes but "class" and "title"'
		);
	}

	/**
	 * @covers Xml::languageSelector
	 */
	public function testLanguageSelector() {
		$select = Xml::languageSelector( 'en', true, null,
			[ 'id' => 'testlang' ], wfMessage( 'yourlanguage' ) );
		$this->assertEquals(
			'<label for="testlang">Language:</label>',
			$select[0]
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
			Xml::encodeJsVar( [ 'a', 1 ] ),
			'encodeJsVar() with array'
		);
		$this->assertEquals(
			'{"a":"a","b":1}',
			Xml::encodeJsVar( [ 'a' => 'a', 'b' => 1 ] ),
			'encodeJsVar() with associative array'
		);
	}

	/**
	 * @covers Xml::encodeJsVar
	 */
	public function testEncodeJsVarObject() {
		$this->assertEquals(
			'{"a":"a","b":1}',
			Xml::encodeJsVar( (object)[ 'a' => 'a', 'b' => 1 ] ),
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

	/**
	 * @covers Xml::encodeJsVar
	 */
	public function testXmlJsCode() {
		$code = 'function () { foo( 42 ); }';
		$this->assertEquals(
			$code,
			Xml::encodeJsVar( new XmlJsCode( $code ) )
		);
	}

	/**
	 * @covers Xml::encodeJsVar
	 * @covers XmlJsCode::encodeObject
	 */
	public function testEncodeObject() {
		$codeA = 'function () { foo( 42 ); }';
		$codeB = 'function ( jQuery ) { bar( 142857 ); }';
		$obj = XmlJsCode::encodeObject( [
			'a' => new XmlJsCode( $codeA ),
			'b' => new XmlJsCode( $codeB )
		] );
		$this->assertEquals(
			"{\"a\":$codeA,\"b\":$codeB}",
			Xml::encodeJsVar( $obj )
		);
	}

	/**
	 * @covers Xml::listDropDown
	 */
	public function testListDropDown() {
		$this->assertEquals(
			'<select name="test-name" id="test-name" class="test-css" tabindex="2">' .
				'<option value="other">other reasons</option>' . "\n" .
				'<optgroup label="Foo">' .
				'<option value="Foo 1">Foo 1</option>' . "\n" .
				'<option value="Example" selected="">Example</option>' . "\n" .
				'</optgroup>' . "\n" .
				'<optgroup label="Bar">' .
				'<option value="Bar 1">Bar 1</option>' . "\n" .
				'</optgroup>' .
				'</select>',
			Xml::listDropDown(
				// name
				'test-name',
				// source list
				"* Foo\n** Foo 1\n** Example\n* Bar\n** Bar 1",
				// other
				'other reasons',
				// selected
				'Example',
				// class
				'test-css',
				// tabindex
				2
			)
		);
	}

	/**
	 * @covers Xml::listDropDownOptions
	 */
	public function testListDropDownOptions() {
		$this->assertEquals(
			[
				'other reasons' => 'other',
				'Foo' => [
					'Foo 1' => 'Foo 1',
					'Example' => 'Example',
				],
				'Bar' => [
					'Bar 1' => 'Bar 1',
				],
			],
			Xml::listDropDownOptions(
				"* Foo\n** Foo 1\n** Example\n* Bar\n** Bar 1",
				[ 'other' => 'other reasons' ]
			)
		);
	}

	/**
	 * @covers Xml::listDropDownOptionsOoui
	 */
	public function testListDropDownOptionsOoui() {
		$this->assertEquals(
			[
				[ 'data' => 'other', 'label' => 'other reasons' ],
				[ 'optgroup' => 'Foo' ],
				[ 'data' => 'Foo 1', 'label' => 'Foo 1' ],
				[ 'data' => 'Example', 'label' => 'Example' ],
				[ 'optgroup' => 'Bar' ],
				[ 'data' => 'Bar 1', 'label' => 'Bar 1' ],
			],
			Xml::listDropDownOptionsOoui( [
				'other reasons' => 'other',
				'Foo' => [
					'Foo 1' => 'Foo 1',
					'Example' => 'Example',
				],
				'Bar' => [
					'Bar 1' => 'Bar 1',
				],
			] )
		);
	}

	/**
	 * @covers Xml::fieldset
	 */
	public function testFieldset() {
		$this->assertEquals(
			"<fieldset>\n",
			Xml::fieldset(),
			'Opening tag'
		);
		$this->assertEquals(
			"<fieldset>\n",
			Xml::fieldset( false ),
			'Opening tag (false means no legend)'
		);
		$this->assertEquals(
			"<fieldset>\n",
			Xml::fieldset( '' ),
			'Opening tag (empty string also means no legend)'
		);
		$this->assertEquals(
			"<fieldset>\n<legend>Foo</legend>\n",
			Xml::fieldset( 'Foo' ),
			'Opening tag with legend'
		);
		$this->assertEquals(
			"<fieldset>\n<legend>Foo</legend>\nBar\n</fieldset>\n",
			Xml::fieldset( 'Foo', 'Bar' ),
			'Entire element with legend'
		);
		$this->assertEquals(
			"<fieldset>\n<legend>Foo</legend>\n",
			Xml::fieldset( 'Foo', false ),
			'Opening tag with legend (false means no content and no closing tag)'
		);
		$this->assertEquals(
			"<fieldset>\n<legend>Foo</legend>\n\n</fieldset>\n",
			Xml::fieldset( 'Foo', '' ),
			'Entire element with legend but no content (empty string generates a closing tag)'
		);
		$this->assertEquals(
			"<fieldset class=\"bar\">\n<legend>Foo</legend>\nBar\n</fieldset>\n",
			Xml::fieldset( 'Foo', 'Bar', [ 'class' => 'bar' ] ),
			'Opening tag with legend and attributes'
		);
		$this->assertEquals(
			"<fieldset class=\"bar\">\n<legend>Foo</legend>\n",
			Xml::fieldset( 'Foo', false, [ 'class' => 'bar' ] ),
			'Entire element with legend and attributes'
		);
	}

	/**
	 * @covers Xml::buildTable
	 */
	public function testBuildTable() {
		$firstRow = [ 'foo', 'bar' ];
		$secondRow = [ 'Berlin', 'Tehran' ];
		$headers = [ 'header1', 'header2' ];
		$expected = '<table id="testTable"><thead id="testTable"><th>header1</th>' .
			'<th>header2</th></thead><tr><td>foo</td><td>bar</td></tr><tr><td>Berlin</td>' .
			'<td>Tehran</td></tr></table>';
		$this->assertEquals(
			$expected,
			Xml::buildTable(
				[ $firstRow, $secondRow ],
				[ 'id' => 'testTable' ],
				$headers
			)
		);
	}

	/**
	 * @covers Xml::buildTableRow
	 */
	public function testBuildTableRow() {
		$this->assertEquals(
			'<tr id="testRow"><td>foo</td><td>bar</td></tr>',
			Xml::buildTableRow( [ 'id' => 'testRow' ], [ 'foo', 'bar' ] )
		);
	}
}
