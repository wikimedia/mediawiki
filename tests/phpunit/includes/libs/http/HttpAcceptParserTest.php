<?php

use Wikimedia\Http\HttpAcceptParser;

/**
 * @covers Wikimedia\Http\HttpAcceptParser
 *
 * @author Daniel Kinzler
 */
class HttpAcceptParserTest extends \PHPUnit\Framework\TestCase {

	public function provideParseWeights() {
		return [
			[ // #0
				'',
				[]
			],
			[ // #1
				'Foo/Bar',
				[ 'foo/bar' => 1 ]
			],
			[ // #2
				'Accept: text/plain',
				[ 'text/plain' => 1 ]
			],
			[ // #3
				'Accept: application/vnd.php.serialized, application/rdf+xml',
				[ 'application/vnd.php.serialized' => 1, 'application/rdf+xml' => 1 ]
			],
			[ // #4
				'foo; q=0.2, xoo; q=0,text/n3',
				[ 'text/n3' => 1, 'foo' => 0.2 ]
			],
			[ // #5
				'*; q=0.2, */*; q=0.1,text/*',
				[ 'text/*' => 1, '*' => 0.2, '*/*' => 0.1 ]
			],
			// TODO: nicely ignore additional type paramerters
			//[ // #6
			//	'Foo; q=0.2, Xoo; level=3, Bar; charset=xyz; q=0.4',
			//	[ 'xoo' => 1, 'bar' => 0.4, 'foo' => 0.1 ]
			//],
		];
	}

	/**
	 * @dataProvider provideParseWeights
	 */
	public function testParseWeights( $header, $expected ) {
		$parser = new HttpAcceptParser();
		$actual = $parser->parseWeights( $header );

		$this->assertEquals( $expected, $actual ); // shouldn't be sensitive to order
	}

}
