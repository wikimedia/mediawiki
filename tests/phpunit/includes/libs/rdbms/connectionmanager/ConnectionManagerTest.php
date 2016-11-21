<?php

/**
 * @covers ConnectionManager
 *
 * @license GPL-2.0+
 * @author Daniel Kinzler
 */
class ConnectionManagerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @return IDatabase|PHPUnit_Framework_MockObject_MockObject
	 */
	private function getIDatabaseMock() {
		return $this->getMock( IDatabase::class );
	}

	/**
	 * @return LoadBalancer|PHPUnit_Framework_MockObject_MockObject
	 */
	private function getLoadBalancerMock() {
		$lb = $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()
			->getMock();

		return $lb;
	}

	public function testGetReadConnection() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_SLAVE )
			->will( $this->returnValue( $database ) );

		$manager = new ConsistentReadConnectionManager( $lb );
		$actual = $manager->getReadConnection();

		$this->assertSame( $database, $actual );
	}

	public function testGetWriteConnection() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_MASTER )
			->will( $this->returnValue( $database ) );

		$manager = new ConsistentReadConnectionManager( $lb );
		$actual = $manager->getWriteConnection();

		$this->assertSame( $database, $actual );
	}

	public function testReleaseConnection() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'reuseConnection' )
			->with( $database )
			->will( $this->returnValue( null ) );

		$manager = new ConsistentReadConnectionManager( $lb );
		$manager->releaseConnection( $database );
	}

}
