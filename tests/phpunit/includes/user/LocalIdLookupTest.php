<?php

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Config\HashConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\CentralId\LocalIdLookup;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;

/**
 * @covers \MediaWiki\User\CentralId\LocalIdLookup
 * @group Database
 */
class LocalIdLookupTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	/** @var UserIdentity[] */
	private $localUsers = [];

	public function addDBData() {
		for ( $i = 1; $i <= 4; $i++ ) {
			$this->localUsers[] = $this->getMutableTestUser()->getUserIdentity();
		}

		$sysop = static::getTestSysop()->getUserIdentity();
		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();

		$block = new DatabaseBlock( [
			'address' => $this->localUsers[2]->getName(),
			'by' => $sysop,
			'reason' => __METHOD__,
			'expiry' => '1 day',
			'hideName' => false,
		] );
		$blockStore->insertBlock( $block );

		$block = new DatabaseBlock( [
			'address' => $this->localUsers[3]->getName(),
			'by' => $sysop,
			'reason' => __METHOD__,
			'expiry' => '1 day',
			'hideName' => true,
		] );
		$blockStore->insertBlock( $block );
	}

	private function newLookup( array $configOverride = [] ) {
		$lookup = new LocalIdLookup(
			new HashConfig( [
				MainConfigNames::SharedDB => null,
				MainConfigNames::SharedTables => [],
				MainConfigNames::LocalDatabases => [],
			] + $configOverride ),
			$this->getServiceContainer()->getConnectionProvider(),
			$this->getServiceContainer()->getHideUserUtils()
		);
		$lookup->init(
			'test',
			$this->getServiceContainer()->getUserIdentityLookup(),
			$this->getServiceContainer()->getUserFactory()
		);
		return $lookup;
	}

	public function testLookupCentralIds() {
		$lookup = $this->newLookup();
		$permitted = $this->mockAnonAuthorityWithPermissions( [ 'hideuser' ] );
		$nonPermitted = $this->mockAnonAuthorityWithoutPermissions( [ 'hideuser' ] );

		$this->assertSame( [], $lookup->lookupCentralIds( [] ) );

		$expect = [];
		foreach ( $this->localUsers as $localUser ) {
			$expect[$localUser->getId()] = $localUser->getName();
		}
		$expect[12345] = 'X';
		ksort( $expect );

		$expect2 = $expect;
		$expect2[$this->localUsers[3]->getId()] = '';

		$arg = array_fill_keys( array_keys( $expect ), 'X' );

		$this->assertSame( $expect2, $lookup->lookupCentralIds( $arg ) );
		$this->assertSame( $expect, $lookup->lookupCentralIds( $arg, CentralIdLookup::AUDIENCE_RAW ) );
		$this->assertSame( $expect, $lookup->lookupCentralIds( $arg, $permitted ) );
		$this->assertSame( $expect2, $lookup->lookupCentralIds( $arg, $nonPermitted ) );
	}

	public function testLookupUserNames() {
		$lookup = $this->newLookup();
		$permitted = $this->mockAnonAuthorityWithPermissions( [ 'hideuser' ] );
		$nonPermitted = $this->mockAnonAuthorityWithoutPermissions( [ 'hideuser' ] );

		$this->assertSame( [], $lookup->lookupUserNames( [] ) );

		$expect = [];
		foreach ( $this->localUsers as $localUser ) {
			$expect[$localUser->getName()] = $localUser->getId();
		}
		$expect['UTDoesNotExist'] = 'X';
		ksort( $expect );

		$expect2 = $expect;
		$expect2[$this->localUsers[3]->getName()] = 'X';

		$arg = array_fill_keys( array_keys( $expect ), 'X' );

		$this->assertSame( $expect2, $lookup->lookupUserNames( $arg ) );
		$this->assertSame( $expect, $lookup->lookupUserNames( $arg, CentralIdLookup::AUDIENCE_RAW ) );
		$this->assertSame( $expect, $lookup->lookupUserNames( $arg, $permitted ) );
		$this->assertSame( $expect2, $lookup->lookupUserNames( $arg, $nonPermitted ) );
	}

	public function testIsAttached() {
		$lookup = $this->newLookup();
		$user1 = UserIdentityValue::newRegistered( 42, 'Test' );
		$user2 = UserIdentityValue::newAnonymous( 'DoesNotExist' );

		$this->assertTrue( $lookup->isAttached( $user1 ) );
		$this->assertFalse( $lookup->isAttached( $user2 ) );

		$wiki = UserIdentityValue::LOCAL;
		$this->assertTrue( $lookup->isAttached( $user1, $wiki ) );
		$this->assertFalse( $lookup->isAttached( $user2, $wiki ) );

		$wiki = 'some_other_wiki';
		$this->assertFalse( $lookup->isAttached( $user1, $wiki ) );
		$this->assertFalse( $lookup->isAttached( $user2, $wiki ) );
	}

	/**
	 * @dataProvider provideIsAttachedShared
	 * @param bool $sharedDB $wgSharedDB is set
	 * @param bool $sharedTable $wgSharedTables contains 'user'
	 * @param bool $localDBSet $wgLocalDatabases contains the shared DB
	 */
	public function testIsAttachedShared( $sharedDB, $sharedTable, $localDBSet ) {
		$lookup = $this->newLookup( [
			MainConfigNames::SharedDB => $sharedDB ? "dummy" : null,
			MainConfigNames::SharedTables => $sharedTable ? [ 'user' ] : [],
			MainConfigNames::LocalDatabases => $localDBSet ? [ 'shared' ] : [],
		] );
		$this->assertSame(
			$sharedDB && $sharedTable && $localDBSet,
			$lookup->isAttached( UserIdentityValue::newRegistered( 42, 'Test' ), 'shared' )
		);
	}

	public static function provideIsAttachedShared() {
		$ret = [];
		for ( $i = 0; $i < 7; $i++ ) {
			$ret[] = [
				(bool)( $i & 1 ),
				(bool)( $i & 2 ),
				(bool)( $i & 4 ),
			];
		}
		return $ret;
	}

}
