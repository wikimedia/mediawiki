<?php

/**
 * Holds tests for FakeResultWrapper MediaWiki class.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Tests\Rdbms;

use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use Wikimedia\Rdbms\FakeResultWrapper;

/**
 * @covers \Wikimedia\Rdbms\FakeResultWrapper
 */
class FakeResultWrapperTest extends TestCase {
	use MediaWikiCoversValidator;

	public function testIteration() {
		$res = new FakeResultWrapper( [
			[ 'colA' => 1, 'colB' => 'a' ],
			[ 'colA' => 2, 'colB' => 'b' ],
			(object)[ 'colA' => 3, 'colB' => 'c' ],
			[ 'colA' => 4, 'colB' => 'd' ],
			[ 'colA' => 5, 'colB' => 'e' ],
			(object)[ 'colA' => 6, 'colB' => 'f' ],
			(object)[ 'colA' => 7, 'colB' => 'g' ],
			[ 'colA' => 8, 'colB' => 'h' ]
		] );

		$expectedRows = [
			0 => (object)[ 'colA' => 1, 'colB' => 'a' ],
			1 => (object)[ 'colA' => 2, 'colB' => 'b' ],
			2 => (object)[ 'colA' => 3, 'colB' => 'c' ],
			3 => (object)[ 'colA' => 4, 'colB' => 'd' ],
			4 => (object)[ 'colA' => 5, 'colB' => 'e' ],
			5 => (object)[ 'colA' => 6, 'colB' => 'f' ],
			6 => (object)[ 'colA' => 7, 'colB' => 'g' ],
			7 => (object)[ 'colA' => 8, 'colB' => 'h' ]
		];

		$this->assertEquals( 8, $res->numRows() );

		$res->seek( 7 );
		$this->assertEquals( [ 'colA' => 8, 'colB' => 'h' ], $res->fetchRow() );
		$res->seek( 7 );
		$this->assertEquals( (object)[ 'colA' => 8, 'colB' => 'h' ], $res->fetchObject() );

		$this->assertEquals( $expectedRows, iterator_to_array( $res, true ) );

		$rows = [];
		foreach ( $res as $i => $row ) {
			$rows[$i] = $row;
		}
		$this->assertEquals( $expectedRows, $rows );
	}
}
