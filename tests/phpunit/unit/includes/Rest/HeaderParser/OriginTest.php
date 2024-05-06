<?php

namespace MediaWiki\Tests\Rest\HeaderParser;

use MediaWiki\Rest\HeaderParser\HeaderParserError;
use MediaWiki\Rest\HeaderParser\Origin;
use MediaWikiUnitTestCase;
use Wikimedia\Assert\PreconditionException;

/**
 * @covers \MediaWiki\Rest\HeaderParser\Origin
 */
class OriginTest extends MediaWikiUnitTestCase {

	public function testNullHeader() {
		$header = Origin::parseHeaderList( [ 'null' ] );
		$this->assertTrue( $header->isNullOrigin() );
		$this->assertFalse( $header->isMultiOrigin() );
		$this->assertArrayEquals( [], $header->getOriginList() );
	}

	public function testMultipleHeaders() {
		$this->expectException( HeaderParserError::class );
		Origin::parseHeaderList( [ 'null', 'null' ] );
	}

	public function testGetSingleOrigin() {
		$this->assertSame( 'https://en.wikipedia.org',
			Origin::parseHeaderList( [ 'https://en.wikipedia.org' ] )->getSingleOrigin() );
	}

	public function testGetSingleOriginThrows() {
		$this->expectException( PreconditionException::class );
		Origin::parseHeaderList( [ 'https://en.wikipedia.org https://ru.wikipedia.org' ] )
			->getSingleOrigin();
	}

	public static function provideOriginParse() {
		yield 'Single origin' =>
			[ 'https://en.wikipedia.org', [ 'https://en.wikipedia.org' ], false ];
		yield 'Multiple origins' =>
			[ 'https://en.wikipedia.org https://foo.bar', [ 'https://en.wikipedia.org', 'https://foo.bar' ], true ];
	}

	/**
	 * @dataProvider provideOriginParse
	 */
	public function testOriginParse( string $header, array $originList, bool $isMulti ) {
		$header = Origin::parseHeaderList( [ $header ] );
		$this->assertArrayEquals( $originList, $header->getOriginList() );
		$this->assertSame( $isMulti, $header->isMultiOrigin() );
	}

	public static function provideMatch() {
		yield 'null origin' => [ 'null', [ 'null' ], [], false ];
		yield 'empty allow list' => [ 'https://en.wikipedia.org', [], [], false ];
		yield 'empty exclude list' => [ 'https://en.wikipedia.org', [ 'en.wikipedia.org' ], [], true ];
		yield 'single origin, match' => [ 'https://en.wikipedia.org', [ 'en.wikipedia.org' ], [ 'example.com' ], true ];
		yield 'single origin, exclude no match' =>
			[
				'https://en.wikipedia.org',
				[ 'en.wikipedia.org' ],
				[ 'en.wikipedia.org' ],
				false,
			];
		yield 'single origin, * match' =>
			[
				'https://en.wikipedia.org',
				[ '*.wikipedia.org' ],
				[ '*.example.com' ],
				true,
			];
		yield 'single origin, exclude * no match' =>
			[
				'https://en.wikipedia.org',
				[ '*.wikipedia.org' ],
				[ '*.wikipedia.org' ],
				false,
			];
		yield 'single origin, ? match' =>
			[
				'https://en.wikipedia.org',
				[ '??.wikipedia.org' ],
				[ '??.example.com' ],
				true,
			];
		yield 'single origin, exclude ? no match' =>
			[
				'https://en.wikipedia.org',
				[ '??.wikipedia.org' ],
				[ '??.wikipedia.org' ],
				false,
			];
		yield 'single origin, no match' =>
			[
					'https://en.wiktionary.org',
					[ 'en.wikipedia.org' ],
					[ 'example.com' ],
					false,
			];
		yield 'single origin, * no match' =>
			[
				'https://en.wiktionary.org',
				[ '*.wikipedia.org' ],
				[ '*.example.com' ],
				false,
			];
		yield 'single origin, ? no match' =>
			[
				'https://en.wiktionary.org',
				[ '??.wikipedia.org' ],
				[ '??.example.com' ],
				false,
			];
		yield 'single origin, multi-list, match' =>
			[
				'https://en.wiktionary.org',
				[ 'en.wikipedia.org', 'en.wiktionary.org' ],
				[ 'example.com', 'www.mediawiki.org' ],
				true
			];
		yield 'single origin, exclude multi-list, no match' =>
			[
				'https://en.wiktionary.org',
				[ 'en.wiktionary.org' ],
				[ 'en.wikipedia.org', 'en.wiktionary.org' ],
				false
			];
		yield 'single origin, multi-list, * match' =>
			[
				'https://en.wiktionary.org',
				[ '*.wikipedia.org', '*.wiktionary.org' ],
				[ '*.example.com', '*.mediawiki.org' ],
				true,
			];
		yield 'single origin, exclude multi-list, * no match' =>
			[
				'https://en.wiktionary.org',
				[ '*.wikipedia.org', '*.wiktionary.org' ],
				[ '*.wikipedia.org', '*.wiktionary.org' ],
				false,
			];
		yield 'single origin, multi-list, ? match' =>
			[
				'https://en.wiktionary.org',
				[ '??.wikipedia.org', '??.wiktionary.org' ],
				[ '??.example.com', '??.mediawiki.org' ],
				true,
			];
		yield 'single origin, exclude multi-list, ? no match' =>
			[
				'https://en.wiktionary.org',
				[ '??.wikipedia.org', '??.wiktionary.org' ],
				[ '??.wikipedia.org', '??.wiktionary.org' ],
				false,
			];
		yield 'single origin, multi-list, no match' =>
			[ 'https://en.wikibooks.org', [ 'en.wikipedia.org', 'en.wiktionary.org' ], [], false ];
		yield 'single origin, multi-list, * no match' =>
			[ 'https://en.wikibooks.org', [ '*.wikipedia.org', '*.wiktionary.org' ], [], false ];
		yield 'single origin, multi-list, ? no match' =>
			[ 'https://en.wikibooks.org', [ '??.wikipedia.org', '??.wiktionary.org' ], [], false ];
		yield 'multi origin, multi-list, match' =>
			[
				'https://en.wikipedia.org https://en.wiktionary.org',
				[ 'en.wikipedia.org', 'en.wiktionary.org' ],
				[ 'example.com', 'www.mediawiki.org' ],
				true,
			];
		yield 'multi origin, exclude multi-list, no match' =>
			[
				'https://en.wikipedia.org https://en.wiktionary.org',
				[ 'en.wikipedia.org', 'en.wiktionary.org' ],
				[ 'en.wikipedia.org', 'en.wiktionary.org' ],
				false,
			];
		yield 'multi origin, multi-list, * match' =>
			[
				'https://en.wikipedia.org https://en.wiktionary.org',
				[ '*.wikipedia.org', '*.wiktionary.org' ],
				[ '*.example.com', '*.mediawiki.org' ],
				true
			];
		yield 'multi origin, exclude multi-list, * no match' =>
			[
				'https://en.wikipedia.org https://en.wiktionary.org',
				[ '*.wikipedia.org', '*.wiktionary.org' ],
				[ '*.wikipedia.org', '*.wiktionary.org' ],
				false
			];
		yield 'multi origin, multi-list, ? match' =>
			[
				'https://en.wikipedia.org https://en.wiktionary.org',
				[ '??.wikipedia.org', '??.wiktionary.org' ],
				[ '??.example.com', '??.mediawiki.org' ],
				true,
			];
		yield 'multi origin, exclude multi-list, ? no match' =>
			[
				'https://en.wikipedia.org https://en.wiktionary.org',
				[ '??.wikipedia.org', '??.wiktionary.org' ],
				[ '??.wikipedia.org', '??.wiktionary.org' ],
				false,
			];
		yield 'multi origin, multi-list, no match' =>
			[
				'https://en.wikipedia.org https://en.wikibooks.org',
				[ 'en.wikipedia.org', 'en.wiktionary.org' ],
				[ 'example.com', 'www.mediawiki.org' ],
				false,
			];
		yield 'multi origin, multi-list, * no match' =>
			[
				'https://en.wikipedia.org https://en.wikibooks.org',
				[ '*.wikipedia.org', '*.wiktionary.org' ],
				[ '*.example.com', '*.mediawiki.org' ],
				false,
			];
		yield 'multi origin, multi-list, ? no match' =>
			[
				'https://en.wikipedia.org https://en.wikibooks.org',
				[ '??.wikipedia.org', '??.wiktionary.org' ],
				[ '??.example.com', '??.mediawiki.org' ],
				false,
			];
	}

	/**
	 * @dataProvider provideMatch
	 */
	public function testMatch( string $header, array $allowList, array $excludeList, bool $expect ) {
		$header = Origin::parseHeaderList( [ $header ] );
		$this->assertSame( $expect, $header->match( $allowList, $excludeList ) );
	}
}
