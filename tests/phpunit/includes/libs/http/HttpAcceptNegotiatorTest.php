<?php

use Wikimedia\Http\HttpAcceptNegotiator;

/**
 * @covers Wikimedia\Http\HttpAcceptNegotiator
 *
 * @author Daniel Kinzler
 */
class HttpAcceptNegotiatorTest extends \PHPUnit\Framework\TestCase {

	public function provideGetFirstSupportedValue() {
		return [
			[ // #0: empty
				[], // supported
				[], // accepted
				null, // default
				null,  // expected
			],
			[ // #1: simple
				[ 'text/foo', 'text/BAR', 'application/zuul' ], // supported
				[ 'text/xzy', 'text/bar' ], // accepted
				null, // default
				'text/BAR',  // expected
			],
			[ // #2: default
				[ 'text/foo', 'text/BAR', 'application/zuul' ], // supported
				[ 'text/xzy', 'text/xoo' ], // accepted
				'X', // default
				'X',  // expected
			],
			[ // #3: preference
				[ 'text/foo', 'text/bar', 'application/zuul' ], // supported
				[ 'text/xoo', 'text/BAR', 'text/foo' ], // accepted
				null, // default
				'text/bar',  // expected
			],
			[ // #4: * wildcard
				[ 'text/foo', 'text/BAR', 'application/zuul' ], // supported
				[ 'text/xoo', '*' ], // accepted
				null, // default
				'text/foo',  // expected
			],
			[ // #5: */* wildcard
				[ 'text/foo', 'text/BAR', 'application/zuul' ], // supported
				[ 'text/xoo', '*/*' ], // accepted
				null, // default
				'text/foo',  // expected
			],
			[ // #6: text/* wildcard
				[ 'text/foo', 'text/BAR', 'application/zuul' ], // supported
				[ 'application/*', 'text/foo' ], // accepted
				null, // default
				'application/zuul',  // expected
			],
		];
	}

	/**
	 * @dataProvider provideGetFirstSupportedValue
	 */
	public function testGetFirstSupportedValue( $supported, $accepted, $default, $expected ) {
		$negotiator = new HttpAcceptNegotiator( $supported );
		$actual = $negotiator->getFirstSupportedValue( $accepted, $default );

		$this->assertEquals( $expected, $actual );
	}

	public function provideGetBestSupportedKey() {
		return [
			[ // #0: empty
				[], // supported
				[], // accepted
				null, // default
				null,  // expected
			],
			[ // #1: simple
				[ 'text/foo', 'text/BAR', 'application/zuul' ], // supported
				[ 'text/xzy' => 1, 'text/bar' => 0.5 ], // accepted
				null, // default
				'text/BAR',  // expected
			],
			[ // #2: default
				[ 'text/foo', 'text/BAR', 'application/zuul' ], // supported
				[ 'text/xzy' => 1, 'text/xoo' => 0.5 ], // accepted
				'X', // default
				'X',  // expected
			],
			[ // #3: weighted
				[ 'text/foo', 'text/BAR', 'application/zuul' ], // supported
				[ 'text/foo' => 0.3, 'text/BAR' => 0.8, 'application/zuul' => 0.5 ], // accepted
				null, // default
				'text/BAR',  // expected
			],
			[ // #4: zero weight
				[ 'text/foo', 'text/BAR', 'application/zuul' ], // supported
				[ 'text/foo' => 0, 'text/xoo' => 1 ], // accepted
				null, // default
				null,  // expected
			],
			[ // #5: * wildcard
				[ 'text/foo', 'text/BAR', 'application/zuul' ], // supported
				[ 'text/xoo' => 0.5, '*' => 0.1 ], // accepted
				null, // default
				'text/foo',  // expected
			],
			[ // #6: */* wildcard
				[ 'text/foo', 'text/BAR', 'application/zuul' ], // supported
				[ 'text/xoo' => 0.5, '*/*' => 0.1 ], // accepted
				null, // default
				'text/foo',  // expected
			],
			[ // #7: text/* wildcard
				[ 'text/foo', 'text/BAR', 'application/zuul' ], // supported
				[ 'text/foo' => 0.3, 'application/*' => 0.8 ], // accepted
				null, // default
				'application/zuul',  // expected
			],
			[ // #8: Test specific format preferred over wildcard (T133314)
				[ 'application/rdf+xml', 'text/json', 'text/html' ], // supported
				[ '*/*' => 1, 'text/html' => 1 ], // accepted
				null, // default
				'text/html',  // expected
			],
			[ // #9: Test specific format preferred over range (T133314)
				[ 'application/rdf+xml', 'text/json', 'text/html' ], // supported
				[ 'text/*' => 1, 'text/html' => 1 ], // accepted
				null, // default
				'text/html',  // expected
			],
			[ // #10: Test range preferred over wildcard (T133314)
				[ 'application/rdf+xml', 'text/html' ], // supported
				[ '*/*' => 1, 'text/*' => 1 ], // accepted
				null, // default
				'text/html',  // expected
			],
		];
	}

	/**
	 * @dataProvider provideGetBestSupportedKey
	 */
	public function testGetBestSupportedKey( $supported, $accepted, $default, $expected ) {
		$negotiator = new HttpAcceptNegotiator( $supported );
		$actual = $negotiator->getBestSupportedKey( $accepted, $default );

		$this->assertEquals( $expected, $actual );
	}

}
