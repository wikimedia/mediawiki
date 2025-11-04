<?php

use Wikimedia\Rdbms\LBFactory;

/**
 * Since this is a unit test, we don't test the singleton() or destroySingletons() methods. We also
 * can't test get() with a valid argument, because that winds up calling static methods of
 * ObjectCache and LoggerFactory that aren't yet compatible with proper unit tests. Those will be
 * tested in the integration test for now.
 *
 * @covers \LockManagerGroup
 */
class LockManagerGroupTest extends MediaWikiUnitTestCase {
	private function getMockLBFactory() {
		return $this->createNoOpMock( LBFactory::class );
	}

	public function testConstructorNoConfigs() {
		new LockManagerGroup( 'domain', [], $this->getMockLBFactory() );
		$this->assertTrue( true, 'No exception thrown' );
	}

	public function testConstructorConfigWithNoName() {
		$this->expectException( Exception::class );
		$this->expectExceptionMessage( 'Cannot register a lock manager with no name.' );

		new LockManagerGroup( 'domain',
			[ [ 'name' => 'a', 'class' => 'b' ], [ 'class' => 'c' ] ], $this->getMockLBFactory() );
	}

	public function testConstructorConfigWithNoClass() {
		$this->expectException( Exception::class );
		$this->expectExceptionMessage( 'Cannot register lock manager `c` with no class.' );

		new LockManagerGroup( 'domain',
			[ [ 'name' => 'a', 'class' => 'b' ], [ 'name' => 'c' ] ], $this->getMockLBFactory() );
	}

	public function testGetUndefined() {
		$this->expectException( Exception::class );
		$this->expectExceptionMessage( 'No lock manager defined with the name `c`.' );

		$lmg = new LockManagerGroup( 'domain', [ [ 'name' => 'a', 'class' => 'b' ] ],
			$this->getMockLBFactory() );
		$lmg->get( 'c' );
	}

	public function testConfigUndefined() {
		$this->expectException( Exception::class );
		$this->expectExceptionMessage( 'No lock manager defined with the name `c`.' );

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
}
