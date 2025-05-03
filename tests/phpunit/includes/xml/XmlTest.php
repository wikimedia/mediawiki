<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Xml\Xml;

/**
 * See also \MediaWiki\Tests\Unit\XmlTest for the pure unit tests
 *
 * @group Xml
 * @covers \MediaWiki\Xml\Xml
 */
class XmlTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::LanguageCode => 'en',
		] );

		$langObj = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
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

		$this->setUserLang( $langObj );
	}

	public static function provideElement() {
		// $expect, $element, $attribs, $contents
		yield 'Opening element with no attributes' => [ '<element>', 'element', null, null ];
		yield 'Terminated empty element' => [ '<element />', 'element', null, '' ];
		yield 'Element with no attributes and content that needs escaping' => [
			'<element>"hello &lt;there&gt; your\'s &amp; you"</element>',
			'element',
			null,
			'"hello <there> your\'s & you"'
		];
		yield 'Element attributes, keys are not escaped' => [
			'<element key="value" <>="&lt;&gt;">',
			'element',
			[ 'key' => 'value', '<>' => '<>' ],
			null
		];
	}

	/**
	 * @dataProvider provideElement
	 */
	public function testElement( string $expect, string $element, $attribs, $content ) {
		$this->assertEquals(
			$expect,
			Xml::element( $element, $attribs, $content )
		);
	}

	public function testElementInputCanHaveAValueOfZero() {
		$this->assertEquals(
			'<input name="name" value="0" />',
			Xml::input( 'name', false, 0 ),
			'Input with a value of 0 (T25797)'
		);
	}

	public function testOpenElement() {
		$this->assertEquals(
			'<element k="v">',
			Xml::openElement( 'element', [ 'k' => 'v' ] ),
			'openElement() shortcut'
		);
	}

	public function testCloseElement() {
		$this->assertEquals( '</element>', Xml::closeElement( 'element' ), 'closeElement() shortcut' );
	}

	public static function provideMonthSelector() {
		# providers are run before services are set up
		$lang = new class() {
			public function getMonthName( $i ) {
				$months = [
					'January', 'February', 'March', 'April', 'May', 'June',
					'July', 'August', 'September', 'October', 'November',
					'December',
				];
				return $months[$i - 1] ?? 'unknown';
			}
		};

		$header = '<select name="month" id="month" class="mw-month-selector">';
		$header2 = '<select name="month" id="monthSelector" class="mw-month-selector">';
		$monthsString = '';
		for ( $i = 1; $i < 13; $i++ ) {
			$monthName = $lang->getMonthName( $i );
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
	 * @dataProvider provideMonthSelector
	 */
	public function testMonthSelector( $expected, $selected, $allmonths, $id ) {
		$this->hideDeprecated( Xml::class . '::monthSelector' );
		$this->assertEquals(
			$expected,
			Xml::monthSelector( $selected, $allmonths, $id )
		);
	}

	public function testSpan() {
		$this->hideDeprecated( Xml::class . '::span' );
		$this->assertEquals(
			'<span class="foo" id="testSpan">element</span>',
			Xml::span( 'element', 'foo', [ 'id' => 'testSpan' ] )
		);
	}

	public function testDateMenu() {
		$curYear = intval( gmdate( 'Y' ) );
		$prevYear = $curYear - 1;

		$curMonth = intval( gmdate( 'n' ) );

		$nextMonth = $curMonth + 1;
		if ( $nextMonth == 13 ) {
			$nextMonth = 1;
		}

		$this->hideDeprecated( Xml::class . '::dateMenu' );
		$this->hideDeprecated( Xml::class . '::monthSelector' );

		$this->assertEquals(
			'<label for="year">From year (and earlier):</label> ' .
				'<input id="year" maxlength="4" size="7" type="number" value="2011" name="year"> ' .
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
				'<input id="year" maxlength="4" size="7" type="number" value="2011" name="year"> ' .
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
				'<input id="year" maxlength="4" size="7" type="number" name="year"> ' .
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

	public function testTextareaNoContent() {
		$this->hideDeprecated( Xml::class . '::textarea' );
		$this->assertEquals(
			'<textarea name="name" id="name" cols="40" rows="5"></textarea>',
			Xml::textarea( 'name', '' ),
			'textarea() with not content'
		);
	}

	public function testTextareaAttribs() {
		$this->hideDeprecated( Xml::class . '::textarea' );
		$this->assertEquals(
			'<textarea name="name" id="name" cols="20" rows="10">&lt;txt&gt;</textarea>',
			Xml::textarea( 'name', '<txt>', 20, 10 ),
			'textarea() with custom attribs'
		);
	}

	public function testLabelCreation() {
		$this->assertEquals(
			'<label for="id">name</label>',
			Xml::label( 'name', 'id' ),
			'label() with no attribs'
		);
	}

	public function testLabelAttributeCanOnlyBeClassOrTitle() {
		$this->assertEquals(
			'<label for="id">name</label>',
			Xml::label( 'name', 'id', [ 'generated' => true ] ),
			'label() cannot be given a generated attribute'
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

	public function testLanguageSelector() {
		$this->hideDeprecated( Xml::class . '::languageSelector' );

		$select = Xml::languageSelector( 'en', true, null,
			[ 'id' => 'testlang' ], wfMessage( 'yourlanguage' ) );
		$this->assertEquals(
			'<label for="testlang">Language:</label>',
			$select[0]
		);
	}

	public function testListDropdown() {
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
			Xml::listDropdown(
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

	public function testListDropdownOptions() {
		$this->assertEquals(
			[
				'other reasons' => 'other',
				'Empty group item' => 'Empty group item',
				'Foo' => [
					'Foo 1' => 'Foo 1',
					'Example' => 'Example',
				],
				'Bar' => [
					'Bar 1' => 'Bar 1',
				],
			],
			Xml::listDropdownOptions(
				"*\n** Empty group item\n* Foo\n** Foo 1\n** Example\n* Bar\n** Bar 1",
				[ 'other' => 'other reasons' ]
			)
		);
	}

	public function testListDropdownOptionsOthers() {
		// Do not use the value for 'other' as option group - T251351
		$this->assertEquals(
			[
				'other reasons' => 'other',
				'Foo 1' => 'Foo 1',
				'Example' => 'Example',
				'Bar' => [
					'Bar 1' => 'Bar 1',
				],
			],
			Xml::listDropdownOptions(
				"* other reasons\n** Foo 1\n** Example\n* Bar\n** Bar 1",
				[ 'other' => 'other reasons' ]
			)
		);
	}

	public function testListDropdownOptionsOoui() {
		$this->assertEquals(
			[
				[ 'data' => 'other', 'label' => 'other reasons' ],
				[ 'optgroup' => 'Foo' ],
				[ 'data' => 'Foo 1', 'label' => 'Foo 1' ],
				[ 'data' => 'Example', 'label' => 'Example' ],
				[ 'optgroup' => 'Bar' ],
				[ 'data' => 'Bar 1', 'label' => 'Bar 1' ],
			],
			Xml::listDropdownOptionsOoui( [
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

	public static function provideFieldset() {
		// $expect, [ $arg1, $arg2, ... ]
		yield 'Opening tag' => [ "<fieldset>\n", [] ];
		yield 'Opening tag (false means no legend)' => [ "<fieldset>\n", [ false ] ];
		yield 'Opening tag (empty string means no legend)' => [ "<fieldset>\n", [ '' ] ];
		yield 'Opening tag with legend' => [
			"<fieldset>\n<legend>Foo</legend>\n",
			[ 'Foo' ]
		];
		yield 'Entire element with legend' => [
			"<fieldset>\n<legend>Foo</legend>\nBar\n</fieldset>\n",
			[ 'Foo', 'Bar' ]
		];
		yield 'Opening tag with legend (false means no content and no closing tag)' => [
			"<fieldset>\n<legend>Foo</legend>\n",
			[ 'Foo', false ]
		];
		yield 'Entire element with legend but no content (empty string generates a closing tag)' => [
			"<fieldset>\n<legend>Foo</legend>\n\n</fieldset>\n",
			[ 'Foo', '' ]
		];
		yield 'Opening tag with legend and attributes' => [
			"<fieldset class=\"bar\">\n<legend>Foo</legend>\nBar\n</fieldset>\n",
			[ 'Foo', 'Bar', [ 'class' => 'bar' ] ]
		];
		yield 'Entire element with legend and attributes' => [
			"<fieldset class=\"bar\">\n<legend>Foo</legend>\n",
			[ 'Foo', false, [ 'class' => 'bar' ] ]
		];
	}

	/**
	 * @dataProvider provideFieldset
	 */
	public function testFieldset( string $expect, array $args ) {
		$this->assertEquals(
			$expect,
			Xml::fieldset( ...$args )
		);
	}

	public function testBuildTable() {
		$this->hideDeprecated( Xml::class . '::buildTable' );
		$this->hideDeprecated( Xml::class . '::buildTableRow' );

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

	public function testBuildTableRow() {
		$this->hideDeprecated( Xml::class . '::buildTableRow' );

		$this->assertEquals(
			'<tr id="testRow"><td>foo</td><td>bar</td></tr>',
			Xml::buildTableRow( [ 'id' => 'testRow' ], [ 'foo', 'bar' ] )
		);
	}
}
