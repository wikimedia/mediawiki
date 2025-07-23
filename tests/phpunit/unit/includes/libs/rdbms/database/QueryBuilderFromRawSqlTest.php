<?php

namespace Wikimedia\Tests\Rdbms;

use MediaWikiUnitTestCase;
use Wikimedia\Rdbms\Platform\SQLPlatform;
use Wikimedia\Rdbms\QueryBuilderFromRawSql;

/**
 * @covers \Wikimedia\Rdbms\QueryBuilderFromRawSql
 */
class QueryBuilderFromRawSqlTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider provideBuildQuery
	 */
	public function testBuildQuery( $sql, $expectedVerb, $expectedFlags ) {
		$query = QueryBuilderFromRawSql::buildQuery( $sql, 0 );
		$this->assertSame( $expectedVerb, $query->getVerb() );
		$this->assertSame( $expectedFlags, $query->getFlags() );
	}

	public static function provideBuildQuery() {
		return [
			[
				'SELECT * FROM foo',
				'SELECT', SQLPlatform::QUERY_CHANGE_NONE,
			],
			[
				'SELECT * FROM foo LOCK IN SHARE MODE',
				// should probably be QUERY_CHANGE_LOCKS but this is how we handle it today
				'SELECT', SQLPlatform::QUERY_CHANGE_NONE,
			],
			[
				'SELECT * FROM foo FOR UPDATE',
				// should probably be QUERY_CHANGE_LOCKS but this is how we handle it today
				'SELECT', SQLPlatform::QUERY_CHANGE_ROWS,
			],
			[
				'INSERT INTO foo VALUES (1)',
				'INSERT', SQLPlatform::QUERY_CHANGE_ROWS,
			],
			[
				'INSERT INTO foo (SELECT * FROM bar)',
				'INSERT', SQLPlatform::QUERY_CHANGE_ROWS,
			],
			[
				'UPDATE foo SET bar = 1',
				'UPDATE', SQLPlatform::QUERY_CHANGE_ROWS,
			],
			[
				'DELETE FROM foo',
				'DELETE', SQLPlatform::QUERY_CHANGE_ROWS,
			],
			[
				'EXPLAIN INSERT INTO foo VALUES (1)',
				'EXPLAIN', SQLPlatform::QUERY_CHANGE_NONE,
			],
			[
				'BEGIN',
				'BEGIN', SQLPlatform::QUERY_CHANGE_TRX,
			],
			[
				'COMMIT',
				'COMMIT', SQLPlatform::QUERY_CHANGE_TRX,
			],
			[
				'ROLLBACK',
				'ROLLBACK', SQLPlatform::QUERY_CHANGE_TRX,
			],
			[
				'SAVEPOINT foo',
				'SAVEPOINT', SQLPlatform::QUERY_CHANGE_TRX,
			],
			[
				'RELEASE SAVEPOINT foo',
				'RELEASE SAVEPOINT', SQLPlatform::QUERY_CHANGE_TRX,
			],
			[
				'ROLLBACK TO SAVEPOINT foo',
				'ROLLBACK TO SAVEPOINT', SQLPlatform::QUERY_CHANGE_TRX,
			],
			// Most DDL test cases don't match the documentation of Query::__construct() but
			// they are accurate representation of the current logic.
			[
				'CREATE TABLE foo (id INT)',
				'CREATE', SQLPlatform::QUERY_CHANGE_SCHEMA,
			],
			[
				'CREATE TEMPORARY TABLE foo (id INT)',
				'CREATE TEMPORARY', SQLPlatform::QUERY_CHANGE_SCHEMA,
			],
			[
				'CREATE INDEX foo ON bar (baz)',
				'CREATE INDEX', SQLPlatform::QUERY_CHANGE_SCHEMA,
			],
			[
				'DROP TABLE foo',
				'DROP', SQLPlatform::QUERY_CHANGE_SCHEMA,
			],
			[
				'DROP INDEX foo ON bar',
				'DROP INDEX', SQLPlatform::QUERY_CHANGE_SCHEMA,
			],
			[
				'ALTER TABLE foo ADD COLUMN bar INT',
				'ALTER', SQLPlatform::QUERY_CHANGE_SCHEMA,
			],
			[
				'CREATE DATABASE foo',
				'CREATE DATABASE', SQLPlatform::QUERY_CHANGE_SCHEMA,
			],
			[
				'ALTER DATABASE foo',
				'ALTER DATABASE', SQLPlatform::QUERY_CHANGE_SCHEMA,
			],
			[
				'DROP DATABASE foo',
				'DROP DATABASE', SQLPlatform::QUERY_CHANGE_SCHEMA,
			],
		];
	}

	/**
	 * @param string $sql
	 * @param bool $res
	 * @dataProvider provideIsWriteQuery
	 */
	public function testIsWriteQuery( string $sql, bool $res ) {
		$query = QueryBuilderFromRawSql::buildQuery( $sql, 0 );
		$this->assertSame( $res, $query->isWriteQuery() );
	}

	public static function provideIsWriteQuery(): array {
		return [
			[ 'SELECT foo', false ],
			[ '  SELECT foo FROM bar', false ],
			[ 'BEGIN', false ],
			[ 'SHOW EXPLAIN FOR 12;', false ],
			[ 'USE foobar', false ],
			[ '(SELECT 1)', false ],
			[ 'INSERT INTO foo', true ],
			[ 'TRUNCATE bar', true ],
			[ 'DELETE FROM baz', true ],
			[ 'CREATE TABLE foobar', true ]
		];
	}

	/**
	 * @dataProvider provideGeneralizeSQL
	 */
	public function testGeneralizeSQL( string $sql, string $generalizedSql ) {
		// Note that the default pcre.backtrack_limit is 1000000
		$this->assertSame( $generalizedSql, QueryBuilderFromRawSql::generalizeSQL( $sql ) );
	}

	public static function provideGeneralizeSQL() {
		return [
			[
				'SELECT * FROM t1 WHERE c1 = 5',
				'SELECT * FROM t1 WHERE c1 = N'
			],
			[
				'SELECT * FROM t1 WHERE c1 = "A"',
				'SELECT * FROM t1 WHERE c1 = \'X\''
			],
			[
				'SELECT * FROM t1 WHERE c1 = \'A\'',
				'SELECT * FROM t1 WHERE c1 = \'X\''
			],
			[
				'SELECT * FROM t1 WHERE c1 IN (5,-6)',
				'SELECT * FROM t1 WHERE c1 IN (N,...,N)'
			],
			[
				'INSERT INTO t1 (1,2,\'3\',\'four\')',
				'INSERT INTO t1 (N,...,N,\'X\')'
			],
			[
				'SELECT * FROM t1 WHERE c1 IN ("A","B")',
				'SELECT * FROM t1 WHERE c1 IN (\'X\')'
			],
			[
				'SELECT * FROM t2 WHERE c1 IN ("1","2","3","4")',
				'SELECT * FROM t2 WHERE c1 IN (\'X\')'
			],
			[
				'SELECT * FROM t3 WHERE c1 IN (' . implode( ',', range( -1, -5000 ) ) . ')',
				'SELECT * FROM t3 WHERE c1 IN (N,...,N)'
			],
			[
				// High range to test against backtrack limits - T391874
				'SELECT * FROM t3 WHERE c1 IN (' . implode( ',', range( 1, 10000 ) ) . ')',
				'SELECT * FROM t3 WHERE c1 IN (N,...,N)'
			]
		];
	}
}
