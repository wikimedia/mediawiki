<?php

use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LBFactory;

/**
 * Most of the file is covered by the unit test and/or FileBackendTest. Here we fill in the missing
 * bits that don't work with unit tests yet.
 *
 * @covers LockManagerGroup
 */
class LockManagerGroupIntegrationTest extends MediaWikiIntegrationTestCase {
	public function testWgLockManagers() {
		$this->setMwGlobals( 'wgLockManagers',
			[ [ 'name' => 'a', 'class' => 'b' ], [ 'name' => 'c', 'class' => 'd' ] ] );
		$this->getServiceContainer()->resetServiceForTesting( 'LockManagerGroupFactory' );

		$lmg = $this->getServiceContainer()->getLockManagerGroupFactory()->getLockManagerGroup();
		$domain = WikiMap::getCurrentWikiDbDomain()->getId();

		$this->assertSame(
			[ 'class' => 'b', 'name' => 'a', 'domain' => $domain ],
			$lmg->config( 'a' ) );
		$this->assertSame(
			[ 'class' => 'd', 'name' => 'c', 'domain' => $domain ],
			$lmg->config( 'c' ) );
	}

	public function testSingletonFalse() {
		$this->setMwGlobals( 'wgLockManagers', [ [ 'name' => 'a', 'class' => 'b' ] ] );
		$this->getServiceContainer()->resetServiceForTesting( 'LockManagerGroupFactory' );

		$this->assertSame(
			WikiMap::getCurrentWikiDbDomain()->getId(),
			$this->getServiceContainer()
				->getLockManagerGroupFactory()
				->getLockManagerGroup( false )
				->config( 'a' )['domain']
		);
	}

	public function testSingletonNull() {
		$this->setMwGlobals( 'wgLockManagers', [ [ 'name' => 'a', 'class' => 'b' ] ] );
		$this->getServiceContainer()->resetServiceForTesting( 'LockManagerGroupFactory' );

		$this->assertSame(
			WikiMap::getCurrentWikiDbDomain()->getId(),
			$this->getServiceContainer()
				->getLockManagerGroupFactory()
				->getLockManagerGroup( null )
				->config( 'a' )['domain']
		);
	}

	public function testGetDBLockManager() {
		$this->markTestSkipped( 'DBLockManager case in LockManagerGroup::get appears to be ' .
			'broken, tries to instantiate an abstract class' );

		$mockLB = $this->createMock( ILoadBalancer::class );
		$mockLB->expects( $this->never() )
			->method( $this->anythingBut( '__destruct', 'getConnectionRef' ) );
		$mockLB->expects( $this->once() )->method( 'getConnectionRef' )
			->with( DB_PRIMARY, [], 'domain', $mockLB::CONN_TRX_AUTOCOMMIT )
			->willReturn( 'bogus value' );

		$mockLBFactory = $this->createMock( LBFactory::class );
		$mockLBFactory->expects( $this->never() )
			->method( $this->anythingBut( '__destruct', 'getMainLB' ) );
		$mockLBFactory->expects( $this->once() )->method( 'getMainLB' )->with( 'domain' )
			->willReturn( $mockLB );

		$lmg = new LockManagerGroup( 'domain',
			[ [ 'name' => 'a', 'class' => DBLockManager::class ] ], $mockLBFactory );
		$this->assertSame( [], $lmg->get( 'a' ) );
	}
}
