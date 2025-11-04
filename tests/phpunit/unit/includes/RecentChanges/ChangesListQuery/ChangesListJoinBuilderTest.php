<?php

namespace MediaWiki\Tests\Unit\RecentChanges\ChangesListQuery;

use MediaWiki\RecentChanges\ChangesListQuery\ChangesListJoinBuilder;
use MediaWiki\Tests\Unit\Libs\Rdbms\AddQuoterMock;
use Wikimedia\Rdbms\Expression;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\ChangesListJoinBuilder
 */
class ChangesListJoinBuilderTest extends \MediaWikiUnitTestCase {
	private function getJoin() {
		return new ChangesListJoinBuilder( 'table', null, [ 'foo=bar' ] );
	}

	public function testConstruct() {
		$join = $this->getJoin();
		$this->assertSame(
			'vague JOIN table ON foo=bar',
			$join->toString( new AddQuoterMock )
		);
	}

	public static function provideMutator() {
		return [
			[
				'forFields',
				'vague JOIN table ON foo=bar /* for fields */'
			],
			[
				'forConds',
				'vague JOIN table ON foo=bar /* for conds */'
			],
			[
				'straight',
				'straight JOIN table ON foo=bar'
			],
			[
				'reorderable',
				'reorderable JOIN table ON foo=bar'
			],
			[
				'left',
				'left JOIN table ON foo=bar'

			],
			[
				'weakLeft',
				'weak-left JOIN table ON foo=bar'
			],
		];
	}

	/**
	 * @dataProvider provideMutator
	 * @param string $method
	 * @param string $expected
	 */
	public function testMutator( $method, $expected ) {
		$join = $this->getJoin();
		$this->assertSame( $join, $join->$method() );
		$this->assertSame( $expected, $join->toString( new AddQuoterMock ) );
	}

	public function testOn() {
		$join = $this->getJoin();
		$join->on( new Expression( 'field', '=', 'value' ) );
		$this->assertSame(
			"vague JOIN table ON foo=bar AND field = 'value'",
			$join->toString( new AddQuoterMock )
		);
	}

	private function toMethod( $joinName ) {
		return preg_replace_callback(
			'/-([a-z])/',
			static fn ( $m ) => strtoupper( $m[1] ),
			$joinName
		);
	}

	public static function provideSetType() {
		// phpcs:disable Universal.WhiteSpace.CommaSpacing
		return [
			[ 'straight',    'straight',    'accept' ],
			[ 'straight',    'left',        'accept' ],
			[ 'straight',    'weak-left',   'ignore' ],
			[ 'straight',    'reorderable', 'accept' ],
			[ 'left',        'straight',    'ignore' ],
			[ 'left',        'left',        'accept' ],
			[ 'left',        'weak-left',   'ignore' ],
			[ 'left',        'reorderable', 'throw' ],
			[ 'weak-left',   'straight',    'accept' ],
			[ 'weak-left',   'left',        'accept' ],
			[ 'weak-left',   'weak-left',   'accept' ],
			[ 'weak-left',   'reorderable', 'accept' ],
			[ 'reorderable', 'straight',    'ignore' ],
			[ 'reorderable', 'left',        'throw' ],
			[ 'reorderable', 'weak-left',   'ignore' ],
			[ 'reorderable', 'reorderable', 'accept' ],
		];
		// phpcs:enable
	}

	/**
	 * @dataProvider provideSetType
	 * @param string $initial
	 * @param string $final
	 * @param string $expect "throw", "accept" or "ignore"
	 */
	public function testSetType( $initial, $final, $expect ) {
		$initialDump = "$initial JOIN table ON foo=bar";
		$finalDump = match ( $expect ) {
			'throw' => '',
			'accept' => "$final JOIN table ON foo=bar",
			default => $initialDump,
		};
		$initialMethod = $this->toMethod( $initial );
		$finalMethod = $this->toMethod( $final );
		$quoter = new AddQuoterMock;
		$join = $this->getJoin();
		$join->$initialMethod();
		$this->assertSame( $initialDump, $join->toString( $quoter ) );
		if ( $expect === 'throw' ) {
			$this->expectException( \LogicException::class );
		}
		$join->$finalMethod();
		$this->assertSame( $finalDump, $join->toString( $quoter ) );
	}

	public static function providePrepare() {
		foreach ( [ 'weak-left', 'straight', 'left', 'reorderable' ] as $joinType ) {
			yield [ $joinType ];
		}
	}

	private function toSqbMethod( $joinName ) {
		return match ( $joinName ) {
			'weak-left' => 'leftJoin',
			'left' => 'leftJoin',
			'reorderable' => 'join',
			'straight' => 'straightJoin',
		};
	}

	/**
	 * @dataProvider providePrepare
	 * @param string $joinType
	 */
	public function testPrepare( $joinType ) {
		$join = $this->getJoin();
		$method = $this->toMethod( $joinType );
		$join->$method();
		$sqbMethod = $this->toSqbMethod( $joinType );
		$sqb = $this->createMock( SelectQueryBuilder::class );
		$sqb->expects( $this->once() )
			->method( $sqbMethod )
			->with( 'table', null, [ 'foo=bar' ] );
		$join->prepare( $sqb );
	}
}
