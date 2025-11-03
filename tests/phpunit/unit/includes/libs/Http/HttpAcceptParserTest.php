<?php

namespace Tests\Unit\Wikimedia\Http;

use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use Wikimedia\Http\HttpAcceptParser;

/**
 * @covers \Wikimedia\Http\HttpAcceptParser
 *
 * @author Daniel Kinzler
 */
class HttpAcceptParserTest extends TestCase {
	use MediaWikiCoversValidator;

	public static function provideParseWeights() {
		return [
			// #0
			[
				'',
				[]
			],
			// #1
			[
				'Foo/Bar',
				[ 'foo/bar' => 1 ]
			],
			// #2
			[
				'Accept: text/plain',
				[ 'text/plain' => 1 ]
			],
			// #3
			[
				'Accept: application/vnd.php.serialized, application/rdf+xml',
				[ 'application/vnd.php.serialized' => 1, 'application/rdf+xml' => 1 ]
			],
			// #4
			[
				'foo/*; q=0.2, xoo; q=0,text/n3',
				[ 'text/n3' => 1, 'foo/*' => 0.2 ]
			],
			// #5
			[
				'foo/*; q=0.2, */*; q=0.1,text/*',
				[ 'text/*' => 1, 'foo/*' => 0.2, '*/*' => 0.1 ]
			],
			// #6
			[
				'Foo/*; q=0.2, Xoo/*; level=3, Bar/*; charset=xyz; q=0.4',
				[ 'xoo/*' => 1, 'bar/*' => 0.4, 'foo/*' => 0.2 ]
			],
		];
	}

	/**
	 * @dataProvider provideParseWeights
	 */
	public function testParseWeights( $header, $expected ) {
		$parser = new HttpAcceptParser();
		$actual = $parser->parseWeights( $header );

		// shouldn't be sensitive to order
		$this->assertEquals( $expected, $actual );
	}

	public static function provideParseAccept() {
		return [
			[
				// Sort by descending q
				'test/123; q=0.5, test/456; q=0.8',
				[
					[
						'type' => 'test',
						'subtype' => '456',
						'q' => 0.8,
						'i' => 1,
						'params' => []
					],
					[
						'type' => 'test',
						'subtype' => '123',
						'q' => 0.5,
						'i' => 0,
						'params' => []
					],
				]
			],
			[
				// Sort by descending q, ascending order
				'test/123; q=0.5, test/789; q=0.8, test/456; q=0.8',
				[
					[
						'type' => 'test',
						'subtype' => '789',
						'q' => 0.8,
						'i' => 1,
						'params' => []
					],
					[
						'type' => 'test',
						'subtype' => '456',
						'q' => 0.8,
						'i' => 2,
						'params' => []
					],
					[
						'type' => 'test',
						'subtype' => '123',
						'q' => 0.5,
						'i' => 0,
						'params' => []
					]
				]
			],
			[
				// Test types and subtypes that contain non-alphanumeric characters
				'hi-ho/12.3; q=0.5, hi/ho+456; q=0.8',
				[
					[
						'type' => 'hi',
						'subtype' => 'ho+456',
						'q' => 0.8,
						'i' => 1,
						'params' => []
					],
					[
						'type' => 'hi-ho',
						'subtype' => '12.3',
						'q' => 0.5,
						'i' => 0,
						'params' => []
					]
				]
			],
			[
				// Test for params
				'text/html; profile="https://www.mediawiki.org/wiki/Specs/HTML/0.0.0"',
				[
					[
						'type' => 'text',
						'subtype' => 'html',
						'q' => 1,
						'i' => 0,
						'params' => [
							'profile' => 'https://www.mediawiki.org/wiki/Specs/HTML/0.0.0'
						]
					]
				]
			],
			[
				// Incomplete q - T391867
				'test/123; q=0.5, test/789; q',
				[
					[
						'type' => 'test',
						'subtype' => '789',
						'q' => 1,
						'i' => 1,
						'params' => []
					],
					[
						'type' => 'test',
						'subtype' => '123',
						'q' => 0.5,
						'i' => 0,
						'params' => []
					],
				]
			],
		];
	}

	/**
	 * @dataProvider provideParseAccept
	 */
	public function testParseAccept( $header, $expected ) {
		$parser = new HttpAcceptParser();
		$actual = $parser->parseAccept( $header );
		$this->assertEquals( $expected, $actual );
	}

}
