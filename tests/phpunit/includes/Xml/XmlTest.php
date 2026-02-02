<?php

namespace MediaWiki\Tests\Xml;

use MediaWiki\MainConfigNames;
use MediaWiki\Xml\Xml;
use MediaWikiIntegrationTestCase;

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

	public function testLabelCreation() {
		$this->hideDeprecated( Xml::class . '::label' );
		$this->assertEquals(
			'<label for="id">name</label>',
			Xml::label( 'name', 'id' ),
			'label() with no attribs'
		);
	}

	public function testLabelAttributeCanOnlyBeClassOrTitle() {
		$this->hideDeprecated( Xml::class . '::label' );
		$this->assertEquals(
			'<label for="id">name</label>',
			Xml::label( 'name', 'id', [ 'generated' => true ] ),
			'label() cannot be given a generated attribute'
		);
		$this->hideDeprecated( Xml::class . '::label' );
		$this->assertEquals(
			'<label for="id" class="nice">name</label>',
			Xml::label( 'name', 'id', [ 'class' => 'nice' ] ),
			'label() can get a class attribute'
		);
		$this->hideDeprecated( Xml::class . '::label' );
		$this->assertEquals(
			'<label for="id" title="nice tooltip">name</label>',
			Xml::label( 'name', 'id', [ 'title' => 'nice tooltip' ] ),
			'label() can get a title attribute'
		);
		$this->hideDeprecated( Xml::class . '::label' );
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

	public function testListDropdown() {
		$this->hideDeprecated( Xml::class . '::listDropdown' );
		$this->hideDeprecated( Xml::class . '::listDropdownOptions' );
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
		$this->hideDeprecated( Xml::class . '::listDropdownOptions' );
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
		$this->hideDeprecated( Xml::class . '::listDropdownOptions' );
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
		$this->hideDeprecated( Xml::class . '::listDropdownOptionsOoui' );
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
}
