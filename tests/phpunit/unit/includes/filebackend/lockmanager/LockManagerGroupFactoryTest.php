<?php

use MediaWiki\FileBackend\LockManager\LockManagerGroupFactory;
use Wikimedia\Rdbms\LBFactory;

/**
 * @covers \MediaWiki\FileBackend\LockManager\LockManagerGroupFactory
 * @todo Should we somehow test that the LockManagerGroup objects are as we expect? How do we do
 *   that without getting into testing LockManagerGroup itself?
 */
class LockManagerGroupFactoryTest extends MediaWikiUnitTestCase {
	public function testGetLockManagerGroup() {
		$mockLbFactory = $this->createNoOpMock( LBFactory::class );

		$factory = new LockManagerGroupFactory( 'defaultDomain', [], $mockLbFactory );
		$lbmUnspecified = $factory->getLockManagerGroup();
		$lbmFalse = $factory->getLockManagerGroup( false );
		$lbmDefault = $factory->getLockManagerGroup( 'defaultDomain' );
		$lbmOther = $factory->getLockManagerGroup( 'otherDomain' );

		$this->assertSame( $lbmUnspecified, $lbmFalse );
		$this->assertSame( $lbmFalse, $lbmDefault );
		$this->assertSame( $lbmDefault, $lbmUnspecified );
		$this->assertNotEquals( $lbmUnspecified, $lbmOther );
		$this->assertNotEquals( $lbmFalse, $lbmOther );
		$this->assertNotEquals( $lbmDefault, $lbmOther );

		$this->assertSame( $lbmUnspecified, $factory->getLockManagerGroup() );
		$this->assertSame( $lbmFalse, $factory->getLockManagerGroup( false ) );
		$this->assertSame( $lbmDefault, $factory->getLockManagerGroup( 'defaultDomain' ) );
		$this->assertSame( $lbmOther, $factory->getLockManagerGroup( 'otherDomain' ) );
	}
}
