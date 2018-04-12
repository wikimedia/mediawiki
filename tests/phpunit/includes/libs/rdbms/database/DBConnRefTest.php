<?php

use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\ResultWrapper;

/**
 * @covers Wikimedia\Rdbms\DBConnRef
 */
class DBConnRefTest extends PHPUnit\Framework\TestCase {

	use PHPUnit4And6Compat;

	/**
	 * @return ILoadBalancer
	 */
	private function getLoadBalancerMock() {
		$lb = $this->getMock( ILoadBalancer::class );

		$lb->method( 'getConnection' )->willReturnCallback(
			function () {
				return $this->getDatabaseMock();
			}
		);

		$lb->method( 'getConnectionRef' )->willReturnCallback(
			function () use ( $lb ) {
				return $this->getDBConnRef( $lb );
			}
		);

		return $lb;
	}

	/**
	 * @return IDatabase
	 */
	private function getDatabaseMock() {
		$db = $this->getMockBuilder( Database::class )
			->disableOriginalConstructor()
			->getMock();

		$db->method( 'select' )->willReturn( new FakeResultWrapper( [] ) );
		$db->method( '__toString' )->willReturn( 'MOCK_DB' );

		return $db;
	}

	/**
	 * @return IDatabase
	 */
	private function getDBConnRef( ILoadBalancer $lb = null ) {
		$lb = $lb ?: $this->getLoadBalancerMock();
		return new DBConnRef( $lb, $this->getDatabaseMock() );
	}

	public function testConstruct() {
		$lb = $this->getLoadBalancerMock();
		$ref = new DBConnRef( $lb, $this->getDatabaseMock() );

		$this->assertInstanceOf( ResultWrapper::class, $ref->select( 'whatever', '*' ) );
	}

	public function testConstruct_params() {
		$lb = $this->getMock( ILoadBalancer::class );

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_MASTER, [ 'test' ], 'dummy', ILoadBalancer::CONN_TRX_AUTOCOMMIT )
			->willReturnCallback(
				function () {
					return $this->getDatabaseMock();
				}
			);

		$ref = new DBConnRef(
			$lb,
			[ DB_MASTER, [ 'test' ], 'dummy', ILoadBalancer::CONN_TRX_AUTOCOMMIT ]
		);

		$this->assertInstanceOf( ResultWrapper::class, $ref->select( 'whatever', '*' ) );
	}

	public function testDestruct() {
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'reuseConnection' );

		$this->innerMethodForTestDestruct( $lb );
	}

	private function innerMethodForTestDestruct( ILoadBalancer $lb ) {
		$ref = $lb->getConnectionRef( DB_REPLICA );

		$this->assertInstanceOf( ResultWrapper::class, $ref->select( 'whatever', '*' ) );
	}

	public function testConstruct_failure() {
		$this->setExpectedException( InvalidArgumentException::class, '' );

		$lb = $this->getLoadBalancerMock();
		new DBConnRef( $lb, 17 ); // bad constructor argument
	}

	public function testGetWikiID() {
		$lb = $this->getMock( ILoadBalancer::class );

		// getWikiID is optimized to not create a connection
		$lb->expects( $this->never() )
			->method( 'getConnection' );

		$ref = new DBConnRef( $lb, [ DB_REPLICA, [], 'dummy', 0 ] );

		$this->assertSame( 'dummy', $ref->getWikiID() );
	}

	public function testGetDomainID() {
		$lb = $this->getMock( ILoadBalancer::class );

		// getDomainID is optimized to not create a connection
		$lb->expects( $this->never() )
			->method( 'getConnection' );

		$ref = new DBConnRef( $lb, [ DB_REPLICA, [], 'dummy', 0 ] );

		$this->assertSame( 'dummy', $ref->getDomainID() );
	}

	public function testSelect() {
		// select should get passed through normally
		$ref = $this->getDBConnRef();
		$this->assertInstanceOf( ResultWrapper::class, $ref->select( 'whatever', '*' ) );
	}

	public function testToString() {
		$ref = $this->getDBConnRef();
		$this->assertInternalType( 'string', $ref->__toString() );

		$lb = $this->getLoadBalancerMock();
		$ref = new DBConnRef( $lb, [ DB_MASTER, [], 'test', 0 ] );
		$this->assertInternalType( 'string', $ref->__toString() );
	}

}
