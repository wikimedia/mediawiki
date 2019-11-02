<?php

/**
 * @group API
 * @group Database
 * @covers ApiFormatXml
 */
class ApiFormatXmlTest extends ApiFormatTestBase {

	protected $printerName = 'xml';

	public static function setUpBeforeClass() : void {
		parent::setUpBeforeClass();
		$page = WikiPage::factory( Title::newFromText( 'MediaWiki:ApiFormatXmlTest.xsl' ) );
		// phpcs:disable Generic.Files.LineLength
		$page->doEditContent( new WikitextContent(
			'<?xml version="1.0"?><xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" />'
		), 'Summary' );
		// phpcs:enable
		$page = WikiPage::factory( Title::newFromText( 'MediaWiki:ApiFormatXmlTest' ) );
		$page->doEditContent( new WikitextContent( 'Bogus' ), 'Summary' );
		$page = WikiPage::factory( Title::newFromText( 'ApiFormatXmlTest' ) );
		$page->doEditContent( new WikitextContent( 'Bogus' ), 'Summary' );
	}

	public static function provideGeneralEncoding() {
		// phpcs:disable Generic.Files.LineLength
		return [
			// Basic types
			[ [ null, 'a' => null ], '<?xml version="1.0"?><api><_v _idx="0" /></api>' ],
			[ [ true, 'a' => true ], '<?xml version="1.0"?><api a=""><_v _idx="0">true</_v></api>' ],
			[ [ false, 'a' => false ], '<?xml version="1.0"?><api><_v _idx="0">false</_v></api>' ],
			[ [ true, 'a' => true, ApiResult::META_BC_BOOLS => [ 0, 'a' ] ],
				'<?xml version="1.0"?><api a=""><_v _idx="0">1</_v></api>' ],
			[ [ false, 'a' => false, ApiResult::META_BC_BOOLS => [ 0, 'a' ] ],
				'<?xml version="1.0"?><api><_v _idx="0"></_v></api>' ],
			[ [ 42, 'a' => 42 ], '<?xml version="1.0"?><api a="42"><_v _idx="0">42</_v></api>' ],
			[ [ 42.5, 'a' => 42.5 ], '<?xml version="1.0"?><api a="42.5"><_v _idx="0">42.5</_v></api>' ],
			[ [ 1e42, 'a' => 1e42 ], '<?xml version="1.0"?><api a="1.0E+42"><_v _idx="0">1.0E+42</_v></api>' ],
			[ [ 'foo', 'a' => 'foo' ], '<?xml version="1.0"?><api a="foo"><_v _idx="0">foo</_v></api>' ],
			[ [ 'fóo', 'a' => 'fóo' ], '<?xml version="1.0"?><api a="fóo"><_v _idx="0">fóo</_v></api>' ],

			// Arrays and objects
			[ [ [] ], '<?xml version="1.0"?><api><_v /></api>' ],
			[ [ [ 'x' => 1 ] ], '<?xml version="1.0"?><api><_v x="1" /></api>' ],
			[ [ [ 2 => 1 ] ], '<?xml version="1.0"?><api><_v><_v _idx="2">1</_v></_v></api>' ],
			[ [ (object)[] ], '<?xml version="1.0"?><api><_v /></api>' ],
			[ [ [ 1, ApiResult::META_TYPE => 'assoc' ] ], '<?xml version="1.0"?><api><_v><_v _idx="0">1</_v></_v></api>' ],
			[ [ [ 'x' => 1, ApiResult::META_TYPE => 'array' ] ], '<?xml version="1.0"?><api><_v><_v>1</_v></_v></api>' ],
			[ [ [ 'x' => 1, 'y' => [ 'z' => 1 ], ApiResult::META_TYPE => 'kvp' ] ],
				'<?xml version="1.0"?><api><_v><_v _name="x" xml:space="preserve">1</_v><_v _name="y"><z xml:space="preserve">1</z></_v></_v></api>' ],
			[ [ [ 'x' => 1, ApiResult::META_TYPE => 'kvp', ApiResult::META_INDEXED_TAG_NAME => 'i', ApiResult::META_KVP_KEY_NAME => 'key' ] ],
				'<?xml version="1.0"?><api><_v><i key="x" xml:space="preserve">1</i></_v></api>' ],
			[ [ [ 'x' => 1, ApiResult::META_TYPE => 'BCkvp', ApiResult::META_KVP_KEY_NAME => 'key' ] ],
				'<?xml version="1.0"?><api><_v><_v key="x" xml:space="preserve">1</_v></_v></api>' ],
			[ [ [ 'x' => 1, ApiResult::META_TYPE => 'BCarray' ] ], '<?xml version="1.0"?><api><_v x="1" /></api>' ],
			[ [ [ 'a', 'b', ApiResult::META_TYPE => 'BCassoc' ] ], '<?xml version="1.0"?><api><_v><_v _idx="0">a</_v><_v _idx="1">b</_v></_v></api>' ],

			// Content
			[ [ 'content' => 'foo', ApiResult::META_CONTENT => 'content' ],
				'<?xml version="1.0"?><api xml:space="preserve">foo</api>' ],

			// Specified element name
			[ [ 'foo', 'bar', ApiResult::META_INDEXED_TAG_NAME => 'itn' ],
				'<?xml version="1.0"?><api><itn>foo</itn><itn>bar</itn></api>' ],

			// Subelements
			[ [ 'a' => 1, 's' => 1, '_subelements' => [ 's' ] ],
				'<?xml version="1.0"?><api a="1"><s xml:space="preserve">1</s></api>' ],

			// Content and subelement
			[ [ 'a' => 1, 'content' => 'foo', ApiResult::META_CONTENT => 'content' ],
				'<?xml version="1.0"?><api a="1" xml:space="preserve">foo</api>' ],
			[ [ 's' => [], 'content' => 'foo', ApiResult::META_CONTENT => 'content' ],
				'<?xml version="1.0"?><api><s /><content xml:space="preserve">foo</content></api>' ],
			[
				[
					's' => 1,
					'content' => 'foo',
					ApiResult::META_CONTENT => 'content',
					ApiResult::META_SUBELEMENTS => [ 's' ]
				],
				'<?xml version="1.0"?><api><s xml:space="preserve">1</s><content xml:space="preserve">foo</content></api>'
			],

			// BC Subelements
			[ [ 'foo' => 'foo', ApiResult::META_BC_SUBELEMENTS => [ 'foo' ] ],
				'<?xml version="1.0"?><api><foo xml:space="preserve">foo</foo></api>' ],

			// Name mangling
			[ [ 'foo.bar' => 1 ], '<?xml version="1.0"?><api foo.bar="1" />' ],
			[ [ '' => 1 ], '<?xml version="1.0"?><api _="1" />' ],
			[ [ 'foo bar' => 1 ], '<?xml version="1.0"?><api _foo.20.bar="1" />' ],
			[ [ 'foo:bar' => 1 ], '<?xml version="1.0"?><api _foo.3A.bar="1" />' ],
			[ [ 'foo%.bar' => 1 ], '<?xml version="1.0"?><api _foo.25..2E.bar="1" />' ],
			[ [ '4foo' => 1, 'foo4' => 1 ], '<?xml version="1.0"?><api _4foo="1" foo4="1" />' ],
			[ [ "foo\xe3\x80\x80bar" => 1 ], '<?xml version="1.0"?><api _foo.3000.bar="1" />' ],
			[ [ 'foo:bar' => 1, ApiResult::META_PRESERVE_KEYS => [ 'foo:bar' ] ],
				'<?xml version="1.0"?><api foo:bar="1" />' ],
			[ [ 'a', 'b', ApiResult::META_INDEXED_TAG_NAME => 'foo bar' ],
				'<?xml version="1.0"?><api><_foo.20.bar>a</_foo.20.bar><_foo.20.bar>b</_foo.20.bar></api>' ],

			// includenamespace param
			[ [ 'x' => 'foo' ], '<?xml version="1.0"?><api x="foo" xmlns="http://www.mediawiki.org/xml/api/" />',
				[ 'includexmlnamespace' => 1 ] ],

			// xslt param
			[ [], '<?xml version="1.0"?><api><warnings><xml xml:space="preserve">Invalid or non-existent stylesheet specified.</xml></warnings></api>',
				[ 'xslt' => 'DoesNotExist' ] ],
			[ [], '<?xml version="1.0"?><api><warnings><xml xml:space="preserve">Stylesheet should be in the MediaWiki namespace.</xml></warnings></api>',
				[ 'xslt' => 'ApiFormatXmlTest' ] ],
			[ [], '<?xml version="1.0"?><api><warnings><xml xml:space="preserve">Stylesheet should have ".xsl" extension.</xml></warnings></api>',
				[ 'xslt' => 'MediaWiki:ApiFormatXmlTest' ] ],
			[ [],
				'<?xml version="1.0"?><?xml-stylesheet href="' .
					htmlspecialchars( Title::newFromText( 'MediaWiki:ApiFormatXmlTest.xsl' )->getLocalURL( 'action=raw' ) ) .
					'" type="text/xsl" ?><api />',
				[ 'xslt' => 'MediaWiki:ApiFormatXmlTest.xsl' ] ],
		];
		// phpcs:enable
	}

}
