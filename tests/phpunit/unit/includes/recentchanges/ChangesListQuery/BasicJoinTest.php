<?php

namespace MediaWiki\Tests\Unit\RecentChanges\ChangesListQuery;

use MediaWiki\RecentChanges\ChangesListQuery\BasicJoin;
use MediaWiki\RecentChanges\ChangesListQuery\ChangesListJoinBuilder;
use MediaWiki\RecentChanges\ChangesListQuery\JoinDependencyProvider;
use MediaWiki\Tests\Unit\Libs\Rdbms\AddQuoterMock;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\BasicJoin
 */
class BasicJoinTest extends \MediaWikiUnitTestCase {
	public function testForFields() {
		$join = new BasicJoin(
			'table',
			'table_alias',
			[ 'foo=bar' ],
			[ 'another_table' ]
		);
		$provider = $this->createMock( JoinDependencyProvider::class );
		$provider->expects( $this->atLeastOnce() )
			->method( 'joinForFields' )
			->with( 'another_table' );
		$quoter = new AddQuoterMock();
		$instance = $join->forFields( $provider );
		$this->assertSame(
			'vague JOIN table AS table_alias ON foo=bar /* for fields */',
			$instance->toString( $quoter )
		);

		// Same instance second time
		$this->assertSame( $instance, $join->forFields( $provider ) );
	}

	public function testForConds() {
		$join = new BasicJoin(
			'table',
			'table_alias',
			'foo=bar',
			'another_table'
		);
		$provider = $this->createMock( JoinDependencyProvider::class );
		$provider->expects( $this->atLeastOnce() )
			->method( 'joinForConds' )
			->with( 'another_table' );
		$quoter = new AddQuoterMock();
		$instance = $join->forConds( $provider );
		$this->assertSame(
			'vague JOIN table AS table_alias ON foo=bar /* for conds */',
			$instance->toString( $quoter )
		);

		// Same instance second time
		$this->assertSame( $instance, $join->forConds( $provider ) );
	}

	public function testAlias() {
		$join = new BasicJoin(
			'table',
			'table_alias',
			'foo=bar',
			'another_table'
		);
		$provider = $this->createMock( JoinDependencyProvider::class );
		$a = $join->alias( $provider, 'a' );
		$this->assertInstanceOf( ChangesListJoinBuilder::class, $a );
		$b = $join->alias( $provider, 'b' );
		$this->assertInstanceOf( ChangesListJoinBuilder::class, $b );
		$this->assertNotSame( $a, $b );
	}

	public function testPrepare() {
		$join = new BasicJoin(
			'table',
			'table_alias',
			'foo=bar',
			'another_table'
		);
		$provider = $this->createMock( JoinDependencyProvider::class );
		$join->alias( $provider, 'a' )->left();
		$join->alias( $provider, 'b' )->straight();
		$sqb = $this->createMock( SelectQueryBuilder::class );
		$sqb->expects( $this->once() )
			->method( 'leftJoin' )
			->with( 'table', 'a', [ 'foo=bar' ] );
		$sqb->expects( $this->once() )
			->method( 'straightJoin' )
			->with( 'table', 'b', [ 'foo=bar' ] );
		$join->prepare( $sqb );
	}
}
