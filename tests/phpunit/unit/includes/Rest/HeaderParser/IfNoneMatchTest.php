<?php

namespace MediaWiki\Tests\Rest\HeaderParser;

use MediaWiki\Rest\HeaderParser\IfNoneMatch;

/**
 * @covers \MediaWiki\Rest\HeaderParser\IfNoneMatch
 */
class IfNoneMatchTest extends \MediaWikiUnitTestCase {
	public static function provideParseHeaderList() {
		return [
			'empty array' => [
				[],
				[]
			],
			'star' => [
				[ '*' ],
				[ [ 'weak' => true, 'contents' => null, 'whole' => '*' ] ]
			],
			'etag' => [
				[ '"x"' ],
				[ [ 'weak' => false, 'contents' => 'x', 'whole' => '"x"' ] ]
			],
			'star etag' => [
				[ '*, "x"' ],
				[]
			],
			'etag star' => [
				[ '"x", *' ],
				[]
			],
			'two in a string' => [
				[ '"x","y"' ],
				[
					[ 'weak' => false, 'contents' => 'x', 'whole' => '"x"' ],
					[ 'weak' => false, 'contents' => 'y', 'whole' => '"y"' ]
				]
			],
			'two with whitespace' => [
				[ "\"x\"\t,  \"y\"" ],
				[
					[ 'weak' => false, 'contents' => 'x', 'whole' => '"x"' ],
					[ 'weak' => false, 'contents' => 'y', 'whole' => '"y"' ]
				]
			],
			'two separate headers' => [
				[ '"x"', '"y"' ],
				[
					[ 'weak' => false, 'contents' => 'x', 'whole' => '"x"' ],
					[ 'weak' => false, 'contents' => 'y', 'whole' => '"y"' ]
				]
			],
			'trailing comma' => [
				[ '"x",' ],
				[]
			],
			'trailing garbage' => [
				[ '"x",asdfg' ],
				[]
			]
		];
	}

	/**
	 * @dataProvider provideParseHeaderList
	 * @param array $input
	 * @param array $expected
	 */
	public function testParseHeaderList( $input, $expected ) {
		$parser = new IfNoneMatch;
		$actual = $parser->parseHeaderList( $input );
		$this->assertSame( $expected, $actual );
	}

	public static function provideParseETag() {
		return [
			'empty' => [
				'',
				null
			],
			'strong' => [
				'"x"',
				[ 'weak' => false, 'contents' => 'x', 'whole' => '"x"' ]
			],
			'weak' => [
				'W/"x"',
				[ 'weak' => true, 'contents' => 'x', 'whole' => 'W/"x"' ]
			],
			'weak with lowercase w' => [
				'w/"x"',
				null
			],
			'quoted character outside valid range' => [
				'" "',
				null
			],
			'trailing garbage' => [
				'"x"a',
				null
			]
		];
	}

	/**
	 * @dataProvider provideParseETag
	 */
	public function testParseETag( $input, $expected ) {
		$parser = new IfNoneMatch;
		$actual = $parser->parseETag( $input );
		$this->assertSame( $expected, $actual );
	}
}
