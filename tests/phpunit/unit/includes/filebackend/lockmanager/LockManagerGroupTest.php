<?php

use Wikimedia\Rdbms\LBFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * Since this is a unit test, we don't test the singleton() or destroySingletons() methods. We also
 * can't test get() with a valid argument, because that winds up calling static methods of
 * ObjectCache and LoggerFactory that aren't yet compatible with proper unit tests. Those will be
 * tested in the integration test for now.
 *
 * @covers LockManagerGroup
 */
class LockManagerGroupTest extends MediaWikiUnitTestCase {
	private function getMockLBFactory() {
		$mock = $this->createMock( LBFactory::class );
		$mock->expects( $this->never() )->method( $this->anythingBut( '__destruct' ) );
		return $mock;
	}

	public function testConstructorNoConfigs() {
		new LockManagerGroup( 'domain', [], $this->getMockLBFactory() );
		$this->assertTrue( true, 'No exception thrown' );
	}

	public function testConstructorConfigWithNoName() {
		$this->setExpectedException( Exception::class,
			'Cannot register a lock manager with no name.' );

		new LockManagerGroup( 'domain',
			[ [ 'name' => 'a', 'class' => 'b' ], [ 'class' => 'c' ] ], $this->getMockLBFactory() );
	}

	public function testConstructorConfigWithNoClass() {
		$this->setExpectedException( Exception::class,
			'Cannot register lock manager `c` with no class.' );

		new LockManagerGroup( 'domain',
			[ [ 'name' => 'a', 'class' => 'b' ], [ 'name' => 'c' ] ], $this->getMockLBFactory() );
	}

	public function testGetUndefined() {
		$this->setExpectedException( Exception::class,
			'No lock manager defined with the name `c`.' );

		$lmg = new LockManagerGroup( 'domain', [ [ 'name' => 'a', 'class' => 'b' ] ],
			$this->getMockLBFactory() );
		$lmg->get( 'c' );
	}

	public function testConfigUndefined() {
		$this->setExpectedException( Exception::class,
			'No lock manager defined with the name `c`.' );

		$lmg = new LockManagerGroup( 'domain', [ [ 'name' => 'a', 'class' => 'b' ] ],
			$this->getMockLBFactory() );
		$lmg->config( 'c' );
	}

	public function testConfig() {
		$lmg = new LockManagerGroup( 'domain', [ [ 'name' => 'a', 'class' => 'b', 'foo' => 'c' ] ],
			$this->getMockLBFactory() );
		$this->assertSame(
			[ 'class' => 'b', 'name' => 'a', 'foo' => 'c', 'domain' => 'domain' ],
			$lmg->config( 'a' )
		);
	}

	public function testGetDefaultNull() {
		$lmg = new LockManagerGroup( 'domain', [], $this->getMockLBFactory() );
		$expected = new NullLockManager( [] );
		$actual = $lmg->getDefault();
		// Have to get rid of the $sessions for equality check to work
		TestingAccessWrapper::newFromObject( $actual )->session = null;
		TestingAccessWrapper::newFromObject( $expected )->session = null;
		$this->assertEquals( $expected, $actual );
	}

	public function testGetAnyException() {
		// XXX Isn't the name 'getAny' misleading if we don't get whatever's available?
		$this->setExpectedException( Exception::class,
			'No lock manager defined with the name `fsLockManager`.' );

		$lmg = new LockManagerGroup( 'domain', [ [ 'name' => 'a', 'class' => 'b' ] ],
			$this->getMockLBFactory() );
		$lmg->getAny();
	}
}
