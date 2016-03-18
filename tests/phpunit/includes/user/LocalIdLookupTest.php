<?php

/**
 * @covers LocalIdLookup
 * @group Database
 */
class LocalIdLookupTest extends MediaWikiTestCase {
	private $localUsers = [];

	protected function setUp() {
		global $wgGroupPermissions;

		parent::setUp();

		$this->stashMwGlobals( [ 'wgGroupPermissions' ] );
		$wgGroupPermissions['local-id-lookup-test']['hideuser'] = true;
	}

	public function addDBData() {
		for ( $i = 1; $i <= 4; $i++ ) {
			$user = User::newFromName( "UTLocalIdLookup$i" );
			if ( $user->getId() == 0 ) {
				$user->addToDatabase();
			}
			$this->localUsers["UTLocalIdLookup$i"] = $user->getId();
		}

		User::newFromName( 'UTLocalIdLookup1' )->addGroup( 'local-id-lookup-test' );

		$block = new Block( [
			'address' => 'UTLocalIdLookup3',
			'by' => User::idFromName( 'UTSysop' ),
			'reason' => __METHOD__,
			'expiry' => '1 day',
			'hideName' => false,
		] );
		$block->insert();

		$block = new Block( [
			'address' => 'UTLocalIdLookup4',
			'by' => User::idFromName( 'UTSysop' ),
			'reason' => __METHOD__,
			'expiry' => '1 day',
			'hideName' => true,
		] );
		$block->insert();
	}

	public function testLookupCentralIds() {
		$lookup = new LocalIdLookup();
		$user1 = User::newFromName( 'UTLocalIdLookup1' );
		$user2 = User::newFromName( 'UTLocalIdLookup2' );

		$this->assertTrue( $user1->isAllowed( 'hideuser' ), 'sanity check' );
		$this->assertFalse( $user2->isAllowed( 'hideuser' ), 'sanity check' );

		$this->assertSame( [], $lookup->lookupCentralIds( [] ) );

		$expect = array_flip( $this->localUsers );
		$expect[123] = 'X';
		ksort( $expect );

		$expect2 = $expect;
		$expect2[$this->localUsers['UTLocalIdLookup4']] = '';

		$arg = array_fill_keys( array_keys( $expect ), 'X' );

		$this->assertSame( $expect2, $lookup->lookupCentralIds( $arg ) );
		$this->assertSame( $expect, $lookup->lookupCentralIds( $arg, CentralIdLookup::AUDIENCE_RAW ) );
		$this->assertSame( $expect, $lookup->lookupCentralIds( $arg, $user1 ) );
		$this->assertSame( $expect2, $lookup->lookupCentralIds( $arg, $user2 ) );
	}

	public function testLookupUserNames() {
		$lookup = new LocalIdLookup();
		$user1 = User::newFromName( 'UTLocalIdLookup1' );
		$user2 = User::newFromName( 'UTLocalIdLookup2' );

		$this->assertTrue( $user1->isAllowed( 'hideuser' ), 'sanity check' );
		$this->assertFalse( $user2->isAllowed( 'hideuser' ), 'sanity check' );

		$this->assertSame( [], $lookup->lookupUserNames( [] ) );

		$expect = $this->localUsers;
		$expect['UTDoesNotExist'] = 'X';
		ksort( $expect );

		$expect2 = $expect;
		$expect2['UTLocalIdLookup4'] = 'X';

		$arg = array_fill_keys( array_keys( $expect ), 'X' );

		$this->assertSame( $expect2, $lookup->lookupUserNames( $arg ) );
		$this->assertSame( $expect, $lookup->lookupUserNames( $arg, CentralIdLookup::AUDIENCE_RAW ) );
		$this->assertSame( $expect, $lookup->lookupUserNames( $arg, $user1 ) );
		$this->assertSame( $expect2, $lookup->lookupUserNames( $arg, $user2 ) );
	}

	public function testIsAttached() {
		$lookup = new LocalIdLookup();
		$user1 = User::newFromName( 'UTLocalIdLookup1' );
		$user2 = User::newFromName( 'DoesNotExist' );

		$this->assertTrue( $lookup->isAttached( $user1 ) );
		$this->assertFalse( $lookup->isAttached( $user2 ) );

		$wiki = wfWikiID();
		$this->assertTrue( $lookup->isAttached( $user1, $wiki ) );
		$this->assertFalse( $lookup->isAttached( $user2, $wiki ) );

		$wiki = 'not-' . wfWikiID();
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
		global $wgDBName;
		$this->setMwGlobals( [
			'wgSharedDB' => $sharedDB ? $wgDBName : null,
			'wgSharedTables' => $sharedTable ? [ 'user' ] : [],
			'wgLocalDatabases' => $localDBSet ? [ 'shared' ] : [],
		] );

		$lookup = new LocalIdLookup();
		$this->assertSame(
			$sharedDB && $sharedTable && $localDBSet,
			$lookup->isAttached( User::newFromName( 'UTLocalIdLookup1' ), 'shared' )
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
