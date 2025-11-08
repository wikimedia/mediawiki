<?php

namespace MediaWiki\Tests\Unit\Libs\Rdbms;

use InvalidArgumentException;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use Wikimedia\Rdbms\AndExpressionGroup;
use Wikimedia\Rdbms\Expression;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeMatch;
use Wikimedia\Rdbms\LikeValue;
use Wikimedia\Rdbms\OrExpressionGroup;

/**
 * @covers \Wikimedia\Rdbms\Expression
 * @covers \Wikimedia\Rdbms\ExpressionGroup
 * @covers \Wikimedia\Rdbms\AndExpressionGroup
 * @covers \Wikimedia\Rdbms\OrExpressionGroup
 * @covers \Wikimedia\Rdbms\LikeMatch
 * @covers \Wikimedia\Rdbms\LikeValue
 */
class ExpressionTest extends TestCase {
	use MediaWikiCoversValidator;

	public static function provideSimple() {
		return [
			[
				'rev_timestamp', '>', '20221025145309',
				"rev_timestamp > '20221025145309'"
			],
			[
				'rev_timestamp', '>=', '20221025145309',
				"rev_timestamp >= '20221025145309'"
			],
			[
				'rev_timestamp', '<', '20221025145309',
				"rev_timestamp < '20221025145309'"
			],
			[
				'rev_timestamp', '<=', '20221025145309',
				"rev_timestamp <= '20221025145309'"
			],
			[
				'rev_timestamp', '=', '20221025145309',
				"rev_timestamp = '20221025145309'"
			],
			[
				'rev_timestamp', '!=', '20221025145309',
				"rev_timestamp != '20221025145309'"
			],
			[
				'revision.rev_timestamp', '!=', '20221025145309',
				"revision.rev_timestamp != '20221025145309'"
			],
			[
				'rev_id', '>', 12345,
				"rev_id > 12345"
			],
			[
				'rev_id', '>=', 12345,
				"rev_id >= 12345"
			],
			[
				'rev_id', '<', 12345,
				"rev_id < 12345"
			],
			[
				'rev_id', '<=', 12345,
				"rev_id <= 12345"
			],
			[
				'rev_id', '=', 12345,
				"rev_id = 12345"
			],
			[
				'rev_id', '=', [ 'x' ],
				"rev_id = 'x'"
			],
			[
				'rev_id', '!=', 12345,
				"rev_id != 12345"
			],
			[
				'rev_id', '!=', [ 'x' ],
				"rev_id != 'x'"
			],
			[
				'rev_id', '=', [ 12345, 456 ],
				"rev_id IN (12345,456)"
			],
			[
				'rev_id', '!=', [ 12345, 456 ],
				"rev_id NOT IN (12345,456)"
			],
			[
				'rc_old_len', '=', null,
				"rc_old_len IS NULL"
			],
			[
				'rc_old_len', '!=', null,
				"rc_old_len IS NOT NULL"
			],
			[
				'rev_timestamp', '=', [ '20221025145309', '20230919152602' ],
				"rev_timestamp IN ('20221025145309','20230919152602')"
			],
			[
				'rev_timestamp', '!=', [ '20221025145309', '20230919152602' ],
				"rev_timestamp NOT IN ('20221025145309','20230919152602')"
			],
			[
				'user_name', IExpression::LIKE, new LikeValue( 'foo', new LikeMatch( '%' ) ),
				"user_name LIKE 'foo%' ESCAPE '`'"
			],
			[
				'user_name', IExpression::NOT_LIKE, new LikeValue( new LikeMatch( '%' ), 'foo' ),
				"user_name NOT LIKE '%foo' ESCAPE '`'"
			],
			[
				'user_name', IExpression::LIKE, new LikeValue( 'foo' ),
				"user_name LIKE 'foo' ESCAPE '`'"
			],
			[
				'user_name', IExpression::LIKE, new LikeValue( new LikeMatch( '_' ), '%_', new LikeMatch( '%' ) ),
				"user_name LIKE '_`%`_%' ESCAPE '`'"
			],
		];
	}

	/**
	 * @dataProvider provideSimple
	 */
	public function testSimple( $field, $op, $value, $sql ) {
		$expr = new Expression( $field, $op, $value );
		$this->assertSame( $sql, $expr->toSql( new AddQuoterMock() ) );
	}

	public static function provideSimpleInvalid() {
		return [
			[ 'rev_timestamp', 'bigger', '12345' ],
			[ 'rev_timestamp', 'bigger', 1234 ],
			[ 'rev_timestamp', '=', [] ],
			[ 'rev_timestamp', '=', [ 1234, null, 456 ] ],
			[ 'rev_timestamp', '>', [ 1234, 456 ] ],
			[ 'rev_timestamp', '<', [ 1234, 456 ] ],
			[ 'rev_timestamp', '>', null ],
			[ 'rev_timestamp', '<', null ],
			[ 'rev_timestamp', '=<', '12345' ],
			'associative array' => [ 'rev_timestamp', '=', [ 'a' => 1234, 'b' => 456 ] ],
			'associative array (single)' => [ 'rev_timestamp', '=', [ 'a' => 1234 ] ],
			'array with gaps' => [ 'rev_timestamp', '=', array_unique( [ 1234, 1234, 456, 456 ] ) ],
			[ 'user_name', IExpression::LIKE, 'foo' ],
			[ 'user_name', IExpression::LIKE, [ 'foo' ] ],
			[ 'user_name', '=', new LikeValue( 'foo' ) ],
			[ 'SELECT user_password from user where user_id = 1', '>', 1234 ],
		];
	}

	/** @dataProvider provideSimpleInvalid */
	public function testSimpleInvalid( $field, $op, $value ): void {
		$this->expectException( InvalidArgumentException::class );
		$expr = new Expression( $field, $op, $value );
		$expr->toSql( new AddQuoterMock() );
	}

	public function testSimpleChain() {
		$expr = ( new Expression( 'rev_timestamp', '>', '20221025145309' ) )
			->and( 'rev_timestamp', '<', '20230919152602' );
		$this->assertSame(
			"(rev_timestamp > '20221025145309' AND rev_timestamp < '20230919152602')",
			$expr->toSql( new AddQuoterMock() )
		);

		$expr = ( new Expression( 'rev_timestamp', '>', '20221025145309' ) )
			->or( 'rev_timestamp', '<', '20230919152602' );
		$this->assertSame(
			"(rev_timestamp > '20221025145309' OR rev_timestamp < '20230919152602')",
			$expr->toSql( new AddQuoterMock() )
		);

		$expr = ( new Expression( 'rev_timestamp', '>', '20221025145309' ) )
			->and( 'rev_id', '=', [ 1234, 4567 ] );
		$this->assertSame(
			"(rev_timestamp > '20221025145309' AND rev_id IN (1234,4567))",
			$expr->toSql( new AddQuoterMock() )
		);

		$expr = ( new Expression( 'rev_timestamp', '>', '20221025145309' ) )
			->or( 'rev_id', '=', [ 1234, 4567 ] );
		$this->assertSame(
			"(rev_timestamp > '20221025145309' OR rev_id IN (1234,4567))",
			$expr->toSql( new AddQuoterMock() )
		);

		$expr = ( new Expression( 'rev_timestamp', '>', '20221025145309' ) )
			->and( 'rc_old_len', '!=', null );
		$this->assertSame(
			"(rev_timestamp > '20221025145309' AND rc_old_len IS NOT NULL)",
			$expr->toSql( new AddQuoterMock() )
		);

		$expr = ( new Expression( 'rev_timestamp', '>', '20221025145309' ) )
			->or( 'rc_old_len', '!=', null );
		$this->assertSame(
			"(rev_timestamp > '20221025145309' OR rc_old_len IS NOT NULL)",
			$expr->toSql( new AddQuoterMock() )
		);

		$expr = ( new Expression( 'rev_timestamp', '>', '20221025145309' ) )
			->and( 'rc_old_len', '=', null );
		$this->assertSame(
			"(rev_timestamp > '20221025145309' AND rc_old_len IS NULL)",
			$expr->toSql( new AddQuoterMock() )
		);

		$expr = ( new Expression( 'rev_timestamp', '>', '20221025145309' ) )
			->or( 'rc_old_len', '=', null );
		$this->assertSame(
			"(rev_timestamp > '20221025145309' OR rc_old_len IS NULL)",
			$expr->toSql( new AddQuoterMock() )
		);
	}

	public function testComplexChain() {
		$expr = ( new Expression( 'rev_timestamp', '>', '20221025145309' ) )
			->or( 'rc_old_len', '=', null )
			->or( 'rc_new_len', '=', null );
		$this->assertSame(
			"(rev_timestamp > '20221025145309' OR rc_old_len IS NULL OR rc_new_len IS NULL)",
			$expr->toSql( new AddQuoterMock() )
		);

		$expr2 = ( new Expression( 'rev_id', '=', [ 123, 456 ] ) )
			->andExpr( $expr );
		$this->assertSame(
			"(rev_id IN (123,456) AND " .
			"(rev_timestamp > '20221025145309' OR rc_old_len IS NULL OR rc_new_len IS NULL))",
			$expr2->toSql( new AddQuoterMock() )
		);

		$expr3 = ( new Expression( 'rev_actor', '>', 3 ) )
			->orExpr( $expr2 );
		$this->assertSame(
			"(rev_actor > 3 OR (rev_id IN (123,456) AND " .
			"(rev_timestamp > '20221025145309' OR rc_old_len IS NULL OR rc_new_len IS NULL)))",
			$expr3->toSql( new AddQuoterMock() )
		);

		$expr4 = ( new AndExpressionGroup( new Expression( 'rev_id', '=', [ 123, 456 ] ) ) )
			->andExpr( $expr );
		$this->assertSame(
			"(rev_id IN (123,456) AND " .
			"(rev_timestamp > '20221025145309' OR rc_old_len IS NULL OR rc_new_len IS NULL))",
			$expr4->toSql( new AddQuoterMock() )
		);

		$expr5 = ( new OrExpressionGroup( new Expression( 'rev_id', '=', [ 123, 456 ] ) ) )
			->orExpr( $expr2 );
		$this->assertSame(
			"(rev_id IN (123,456) OR (rev_id IN (123,456) AND " .
			"(rev_timestamp > '20221025145309' OR rc_old_len IS NULL OR rc_new_len IS NULL)))",
			$expr5->toSql( new AddQuoterMock() )
		);
	}
}
