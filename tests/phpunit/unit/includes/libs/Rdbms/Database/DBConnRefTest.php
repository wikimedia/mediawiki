<?php

namespace Wikimedia\Tests\Rdbms;

use InvalidArgumentException;
use LogicException;
use MediaWikiCoversValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Wikimedia\Rdbms\AndExpressionGroup;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\DBReadOnlyRoleError;
use Wikimedia\Rdbms\DBUnexpectedError;
use Wikimedia\Rdbms\Expression;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\OrExpressionGroup;

/**
 * @covers \Wikimedia\Rdbms\DBConnRef
 */
class DBConnRefTest extends TestCase {

	use MediaWikiCoversValidator;

	/**
	 * @return ILoadBalancer|MockObject
	 */
	private function getLoadBalancerMock() {
		// getConnection() and getConnectionInternal() should keep returning the same connection
		// on every call, unless that connection was closed. Then they should return a new
		// connection.
		$conn = $this->getDatabaseMock();
		$getDatabaseMock = function () use ( &$conn ) {
			if ( !$conn->isOpen() ) {
				$conn = $this->getDatabaseMock();
			}
			return $conn;
		};

		$lb = $this->createMock( ILoadBalancer::class );
		$lb->method( 'getConnection' )->willReturnCallback( $getDatabaseMock );
		$lb->method( 'getConnectionInternal' )->willReturnCallback( $getDatabaseMock );

		return $lb;
	}

	/**
	 * @return IDatabase
	 */
	private function getDatabaseMock() {
		$db = $this->createMock( IDatabase::class );

		$open = true;
		$db->method( 'select' )->willReturnCallback( static function () use ( &$open ) {
			if ( !$open ) {
				throw new LogicException( "Not open" );
			}

			return new FakeResultWrapper( [] );
		} );
		$db->method( 'close' )->willReturnCallback( static function () use ( &$open ) {
			$open = false;

			return true;
		} );
		$db->method( 'isOpen' )->willReturnCallback( static function () use ( &$open ) {
			return $open;
		} );

		return $db;
	}

	/**
	 * @param ILoadBalancer|null $lb
	 * @return IDatabase
	 */
	private function getDBConnRef( ?ILoadBalancer $lb = null ) {
		$lb ??= $this->getLoadBalancerMock();
		return new DBConnRef( $lb, [ DB_PRIMARY, [], 'mywiki', 0 ], DB_PRIMARY );
	}

	/**
	 * Test that bumping the modification counter causes the wrapped connection
	 * to be discarded and re-aquired.
	 */
	public function testModCount() {
		$lb = $this->getLoadBalancerMock();
		$lb->expects( $this->exactly( 3 ) )->method( 'getConnectionInternal' );

		$params = [ DB_PRIMARY, [], 'mywiki', 0 ];
		$modcount = 0;
		$ref = new DBConnRef( $lb, $params, DB_PRIMARY, $modcount );

		$ref->select( 'test', '*' );
		$ref->select( 'test', '*' );

		$modcount++; // cause second call to getConnectionInternal
		$ref->select( 'test', '*' );
		$ref->select( 'test', '*' );

		$modcount++; // cause third call to getConnectionInternal
		$ref->select( 'test', '*' );
		$ref->select( 'test', '*' );
	}

	public function testConstruct() {
		$lb = $this->createMock( ILoadBalancer::class );

		$lb->expects( $this->once() )
			->method( 'getConnectionInternal' )
			->with( DB_PRIMARY, [ 'test' ], 'dummy', ILoadBalancer::CONN_TRX_AUTOCOMMIT )
			->willReturn( $this->getDatabaseMock() );

		$ref = new DBConnRef(
			$lb,
			[ DB_PRIMARY, [ 'test' ], 'dummy', $lb::CONN_TRX_AUTOCOMMIT ],
			DB_PRIMARY
		);

		$this->assertInstanceOf( IResultWrapper::class, $ref->select( 'whatever', '*' ) );
		$this->assertEquals( DB_PRIMARY, $ref->getReferenceRole() );

		$ref2 = new DBConnRef(
			$lb,
			[ DB_PRIMARY, [ 'test' ], 'dummy', $lb::CONN_TRX_AUTOCOMMIT ],
			DB_REPLICA
		);
		$this->assertEquals( DB_REPLICA, $ref2->getReferenceRole() );
	}

	public function testDestruct() {
		$lb = $this->getLoadBalancerMock();

		$this->innerMethodForTestDestruct( $lb );
	}

	private function innerMethodForTestDestruct( ILoadBalancer $lb ) {
		$ref = $lb->getConnection( DB_REPLICA );

		$this->assertInstanceOf( IResultWrapper::class, $ref->select( 'whatever', '*' ) );
	}

	public function testConstruct_failure() {
		$this->expectException( InvalidArgumentException::class );

		$lb = $this->getLoadBalancerMock();
		new DBConnRef( $lb, 17, DB_REPLICA ); // bad constructor argument
	}

	public function testGetDomainID() {
		$lb = $this->createMock( ILoadBalancer::class );

		// getDomainID is optimized to not create a connection
		$lb->expects( $this->never() )
			->method( 'getConnection' );

		$ref = new DBConnRef( $lb, [ DB_REPLICA, [], 'dummy', 0 ], DB_REPLICA );

		$this->assertSame( 'dummy', $ref->getDomainID() );
	}

	public function testSelect() {
		// select should get passed through normally
		$ref = $this->getDBConnRef();
		$this->assertInstanceOf( IResultWrapper::class, $ref->select( 'whatever', '*' ) );
	}

	public function testExpr() {
		$ref = $this->getDBConnRef();
		$this->assertInstanceOf( Expression::class, $ref->expr( 'key', '=', null ) );
		$this->assertInstanceOf( AndExpressionGroup::class, $ref->andExpr( [ 'key' => null, $ref->expr( 'key', '=', null ) ] ) );
		$this->assertInstanceOf( OrExpressionGroup::class, $ref->orExpr( [ 'key' => null, $ref->expr( 'key', '=', null ) ] ) );
	}

	public function testToString() {
		$ref = $this->getDBConnRef();
		$this->assertIsString( $ref->__toString() );

		$lb = $this->getLoadBalancerMock();
		$ref = new DBConnRef( $lb, [ DB_PRIMARY, [], 'test', 0 ], DB_PRIMARY );
		$this->assertIsString( $ref->__toString() );
	}

	public function testClose() {
		$lb = $this->getLoadBalancerMock();
		$ref = new DBConnRef( $lb, [ DB_REPLICA, [], 'dummy', 0 ], DB_PRIMARY );
		$this->expectException( DBUnexpectedError::class );
		$ref->close();
	}

	public function testGetReferenceRole() {
		$lb = $this->getLoadBalancerMock();
		$ref = new DBConnRef( $lb, [ DB_REPLICA, [], 'dummy', 0 ], DB_REPLICA );
		$this->assertSame( DB_REPLICA, $ref->getReferenceRole() );

		$ref = new DBConnRef( $lb, [ DB_PRIMARY, [], 'dummy', 0 ], DB_PRIMARY );
		$this->assertSame( DB_PRIMARY, $ref->getReferenceRole() );

		$ref = new DBConnRef( $lb, [ 1, [], 'dummy', 0 ], DB_REPLICA );
		$this->assertSame( DB_REPLICA, $ref->getReferenceRole() );

		$ref = new DBConnRef( $lb, [ 0, [], 'dummy', 0 ], DB_PRIMARY );
		$this->assertSame( DB_PRIMARY, $ref->getReferenceRole() );
	}

	/**
	 * @dataProvider provideRoleExceptions
	 */
	public function testRoleExceptions( $method, $args ) {
		$lb = $this->getLoadBalancerMock();
		$ref = new DBConnRef( $lb, [ DB_REPLICA, [], 'dummy', 0 ], DB_REPLICA );
		$this->expectException( DBReadOnlyRoleError::class );
		$ref->$method( ...$args );
	}

	public static function provideRoleExceptions() {
		return [
			[ 'insert', [ 'table', [ 'a' => 1 ] ] ],
			[ 'update', [ 'table', [ 'a' => 1 ], [ 'a' => 2 ] ] ],
			[ 'delete', [ 'table', [ 'a' => 1 ] ] ],
			[ 'replace', [ 'table', [ 'a' ], [ 'a' => 1 ] ] ],
			[ 'upsert', [ 'table', [ 'a' => 1 ], [ 'a' ], [ 'a = a + 1' ] ] ],
			[ 'lock', [ 'k', 'method' ] ],
			[ 'unlock', [ 'k', 'method' ] ],
			[ 'getScopedLockAndFlush', [ 'k', 'method', 1 ] ]
		];
	}
}
