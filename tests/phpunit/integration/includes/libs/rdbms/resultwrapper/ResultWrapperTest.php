<?php

/**
 * Holds tests for ResultWrapper MediaWiki class.
 *
 * @license GPL-2.0-or-later
 * @file
 */

use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * @group Database
 * @covers \Wikimedia\Rdbms\ResultWrapper
 * @covers \Wikimedia\Rdbms\MysqliResultWrapper
 * @covers \Wikimedia\Rdbms\PostgresResultWrapper
 * @covers \Wikimedia\Rdbms\SqliteResultWrapper
 */
class ResultWrapperTest extends MediaWikiIntegrationTestCase {
	public function getSchemaOverrides( IMaintainableDatabase $db ) {
		return [
			'create' => [ 'ResultWrapperTest' ],
			'scripts' => [ __DIR__ . '/ResultWrapperTest.sql' ]
		];
	}

	public function testIteration() {
		$this->getDb()->insert(
			'ResultWrapperTest', [
				[ 'col_a' => '1', 'col_b' => 'a' ],
				[ 'col_a' => '2', 'col_b' => 'b' ],
				[ 'col_a' => '3', 'col_b' => 'c' ],
				[ 'col_a' => '4', 'col_b' => 'd' ],
				[ 'col_a' => '5', 'col_b' => 'e' ],
				[ 'col_a' => '6', 'col_b' => 'f' ],
				[ 'col_a' => '7', 'col_b' => 'g' ],
				[ 'col_a' => '8', 'col_b' => 'h' ]
			],
			__METHOD__
		);

		$expectedRows = [
			0 => (object)[ 'col_a' => '1', 'col_b' => 'a' ],
			1 => (object)[ 'col_a' => '2', 'col_b' => 'b' ],
			2 => (object)[ 'col_a' => '3', 'col_b' => 'c' ],
			3 => (object)[ 'col_a' => '4', 'col_b' => 'd' ],
			4 => (object)[ 'col_a' => '5', 'col_b' => 'e' ],
			5 => (object)[ 'col_a' => '6', 'col_b' => 'f' ],
			6 => (object)[ 'col_a' => '7', 'col_b' => 'g' ],
			7 => (object)[ 'col_a' => '8', 'col_b' => 'h' ]
		];

		$res = $this->getDb()->newSelectQueryBuilder()
			->select( [ 'col_a', 'col_b' ] )
			->from( 'ResultWrapperTest' )
			->where( '1 = 1' )
			->caller( __METHOD__ )->fetchResultSet();
		$this->assertSame( 8, $res->numRows() );
		$this->assertTrue( $res->valid() );

		$res->seek( 7 );
		$this->assertSame( 7, $res->key() );
		$this->assertArrayEquals( [ 'col_a' => '8', 0 => '8', 'col_b' => 'h', 1 => 'h', ],
			$res->fetchRow(), false, true );
		$this->assertSame( 7, $res->key() );

		$res->seek( 7 );
		$this->assertSame( 7, $res->key() );
		$this->assertEquals( (object)[ 'col_a' => '8', 'col_b' => 'h' ], $res->fetchObject() );
		$this->assertEquals( (object)[ 'col_a' => '8', 'col_b' => 'h' ], $res->current() );
		$this->assertSame( 7, $res->key() );

		$res->seek( 6 );
		$this->assertTrue( $res->valid() );
		$this->assertSame( 6, $res->key() );
		$this->assertEquals( (object)[ 'col_a' => '7', 'col_b' => 'g' ], $res->fetchObject() );
		$this->assertTrue( $res->valid() );
		$this->assertSame( 6, $res->key() );
		$this->assertEquals( (object)[ 'col_a' => '8', 'col_b' => 'h' ], $res->fetchObject() );
		$this->assertSame( 7, $res->key() );
		$this->assertFalse( $res->fetchObject() );
		$this->assertFalse( $res->current() );
		$this->assertFalse( $res->valid() );

		$this->assertArrayEquals( $expectedRows, iterator_to_array( $res, true ),
			false, true );

		$rows = [];
		foreach ( $res as $i => $row ) {
			$rows[$i] = $row;
		}
		$this->assertEquals( $expectedRows, $rows );
	}

	public function testCurrentNoResults() {
		$res = $this->getDb()->newSelectQueryBuilder()
			->select( [ 'col_a', 'col_b' ] )
			->from( 'ResultWrapperTest' )
			->where( '1 = 0' )
			->caller( __METHOD__ )->fetchResultSet();
		$this->assertFalse( $res->current() );
	}

	public function testValidNoResults() {
		$res = $this->getDb()->newSelectQueryBuilder()
			->select( [ 'col_a', 'col_b' ] )
			->from( 'ResultWrapperTest' )
			->where( '1 = 0' )
			->caller( __METHOD__ )->fetchResultSet();
		$this->assertFalse( $res->valid() );
	}

	public function testSeekNoResults() {
		$res = $this->getDb()->newSelectQueryBuilder()
			->select( [ 'col_a', 'col_b' ] )
			->from( 'ResultWrapperTest' )
			->where( '1 = 0' )
			->caller( __METHOD__ )->fetchResultSet();
		$res->seek( 0 );
		$this->assertTrue( true ); // no error
	}

	public static function provideSeekOutOfBounds() {
		return [ [ 0, 1 ], [ 1, 1 ], [ 1, 2 ], [ 1, -1 ] ];
	}

	/** @dataProvider provideSeekOutOfBounds */
	public function testSeekOutOfBounds( $numRows, $seekPos ) {
		for ( $i = 0; $i < $numRows; $i++ ) {
			$this->getDb()->insert( 'ResultWrapperTest',
				[ [ 'col_a' => $i, 'col_b' => $i ] ],
				__METHOD__ );
		}
		$res = $this->getDb()->newSelectQueryBuilder()
			->select( [ 'col_a', 'col_b' ] )
			->from( 'ResultWrapperTest' )
			->where( '1 = 0' )
			->caller( __METHOD__ )->fetchResultSet();
		$this->expectException( OutOfBoundsException::class );
		$res->seek( $seekPos );
	}
}
