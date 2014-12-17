<?php

/**
 * @group API
 * @covers ApiFormatXml
 */
class ApiFormatXmlTest extends ApiFormatTestBase {

	protected $printerName = 'xml';

	protected function setUp() {
		parent::setUp();
		$page = WikiPage::factory( Title::newFromText( 'MediaWiki:ApiFormatXmlTest.xsl' ) );
		$page->doEditContent( new WikitextContent(
			'<?xml version="1.0"?><xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" />'
		), 'Summary' );
		$page = WikiPage::factory( Title::newFromText( 'MediaWiki:ApiFormatXmlTest' ) );
		$page->doEditContent( new WikitextContent( 'Bogus' ), 'Summary' );
		$page = WikiPage::factory( Title::newFromText( 'ApiFormatXmlTest' ) );
		$page->doEditContent( new WikitextContent( 'Bogus' ), 'Summary' );
	}

	public function provideGeneralEncoding() {
		$tests = array(
			// Basic types
			array( array( null ), '<?xml version="1.0"?><api><x /></api>' ),
			array( array( true, 'a' => true ), '<?xml version="1.0"?><api a=""><x>1</x></api>' ),
			array( array( false, 'a' => false ), '<?xml version="1.0"?><api><x></x></api>' ),
			array( array( 42, 'a' => 42 ), '<?xml version="1.0"?><api a="42"><x>42</x></api>' ),
			array( array( 42.5, 'a' => 42.5 ), '<?xml version="1.0"?><api a="42.5"><x>42.5</x></api>' ),
			array( array( 1e42, 'a' => 1e42 ), '<?xml version="1.0"?><api a="1.0E+42"><x>1.0E+42</x></api>' ),
			array( array( 'foo', 'a' => 'foo' ), '<?xml version="1.0"?><api a="foo"><x>foo</x></api>' ),
			array( array( 'fóo', 'a' => 'fóo' ), '<?xml version="1.0"?><api a="fóo"><x>fóo</x></api>' ),

			// Arrays and objects
			array( array( array() ), '<?xml version="1.0"?><api><x /></api>' ),
			array( array( array( 'x' => 1 ) ), '<?xml version="1.0"?><api><x x="1" /></api>' ),
			array( array( array( 2 => 1, '_element' => 'x' ) ), '<?xml version="1.0"?><api><x><x>1</x></x></api>' ),

			// Content
			array( array( '*' => 'foo' ), '<?xml version="1.0"?><api xml:space="preserve">foo</api>' ),

			// Subelements
			array( array( 'a' => 1, 's' => 1, '_subelements' => array( 's' ) ),
				'<?xml version="1.0"?><api a="1"><s xml:space="preserve">1</s></api>' ),

			// includenamespace param
			array( array( 'x' => 'foo' ), '<?xml version="1.0"?><api x="foo" xmlns="http://www.mediawiki.org/xml/api/" />',
				array( 'includexmlnamespace' => 1 ) ),

			// xslt param
			array( array(), '<?xml version="1.0"?><api><warnings><xml xml:space="preserve">Invalid or non-existent stylesheet specified</xml></warnings></api>',
				array( 'xslt' => 'DoesNotExist' ) ),
			array( array(), '<?xml version="1.0"?><api><warnings><xml xml:space="preserve">Stylesheet should be in the MediaWiki namespace.</xml></warnings></api>',
				array( 'xslt' => 'ApiFormatXmlTest' ) ),
			array( array(), '<?xml version="1.0"?><api><warnings><xml xml:space="preserve">Stylesheet should have .xsl extension.</xml></warnings></api>',
				array( 'xslt' => 'MediaWiki:ApiFormatXmlTest' ) ),
			array( array(),
				'<?xml version="1.0"?><?xml-stylesheet href="' .
					htmlspecialchars( Title::newFromText( 'MediaWiki:ApiFormatXmlTest.xsl' )->getLocalURL( 'action=raw' ) ) .
					'" type="text/xsl" ?><api />',
				array( 'xslt' => 'MediaWiki:ApiFormatXmlTest.xsl' ) ),
		);

		// Add in the needed "_element" for all indexed arrays
		$ret = array();
		foreach ( $tests as $v ) {
			$v[0] += array( '_element' => 'x' );
			$ret[] = $v;
		}
		return $ret;
	}

	/**
	 * @dataProvider provideXmlFail
	 */
	public function testXmlFail( array $data, $expect, array $params = array() ) {
		try {
			echo $this->encodeData( $params, $data ) . "\n";
			$this->fail( "Expected exception not thrown" );
		} catch ( MWException $ex ) {
			$this->assertSame( $expect, $ex->getMessage(), 'Expected exception' );
		}
	}

	public function provideXmlFail() {
		return array(
			// Array without _element
			array( array( 1 ), 'Internal error in ApiFormatXml::recXmlPrint: (api, ...) has integer keys without _element value. Use ApiResult::setIndexedTagName().' ),
			// Content and subelement
			array( array( 1, 's' => array(), '*' => 2, '_element' => 'x' ), 'Internal error in ApiFormatXml::recXmlPrint: (api, ...) has content and subelements' ),
			array( array( 1, 's' => 1, '*' => 2, '_element' => 'x', '_subelements' => array( 's' ) ), 'Internal error in ApiFormatXml::recXmlPrint: (api, ...) has content and subelements' ),
			// These should fail but don't because of a long-standing bug (see T57371#639713)
			//array( array( 1, '*' => 2, '_element' => 'x' ), 'Internal error in ApiFormatXml::recXmlPrint: (api, ...) has content and subelements' ),
			//array( array( 's' => array(), '*' => 2 ), 'Internal error in ApiFormatXml::recXmlPrint: (api, ...) has content and subelements' ),
			//array( array( 's' => 1, '*' => 2, '_subelements' => array( 's' ) ), 'Internal error in ApiFormatXml::recXmlPrint: (api, ...) has content and subelements' ),
		);
	}

}
