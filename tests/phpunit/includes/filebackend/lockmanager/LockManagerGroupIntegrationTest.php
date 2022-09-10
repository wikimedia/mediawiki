<?php

use MediaWiki\MainConfigNames;
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
		$this->overrideConfigValue( MainConfigNames::LockManagers,
			[ [ 'name' => 'a', 'class' => 'b' ], [ 'name' => 'c', 'class' => 'd' ] ] );

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
		$this->overrideConfigValue( MainConfigNames::LockManagers, [ [ 'name' => 'a', 'class' => 'b' ] ] );

		$this->assertSame(
			WikiMap::getCurrentWikiDbDomain()->getId(),
			$this->getServiceContainer()
				->getLockManagerGroupFactory()
				->getLockManagerGroup( false )
				->config( 'a' )['domain']
		);
	}

	public function testSingletonNull() {
		$this->overrideConfigValue( MainConfigNames::LockManagers, [ [ 'name' => 'a', 'class' => 'b' ] ] );

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

		$mockLB = $this->createNoOpMock( ILoadBalancer::class, [ 'getConnectionRef' ] );
		$mockLB->expects( $this->once() )->method( 'getConnectionRef' )
			->with( DB_PRIMARY, [], 'domain', $mockLB::CONN_TRX_AUTOCOMMIT )
			->willReturn( 'bogus value' );

		$mockLBFactory = $this->createNoOpMock( LBFactory::class, [ 'getMainLB' ] );
		$mockLBFactory->expects( $this->once() )->method( 'getMainLB' )->with( 'domain' )
			->willReturn( $mockLB );

		$lmg = new LockManagerGroup( 'domain',
			[ [ 'name' => 'a', 'class' => DBLockManager::class ] ], $mockLBFactory );
		$this->assertSame( [], $lmg->get( 'a' ) );
	}
}
