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
}
