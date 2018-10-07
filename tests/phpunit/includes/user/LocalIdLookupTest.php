<?php

/**
 * @covers LocalIdLookup
 * @group Database
 */
class LocalIdLookupTest extends MediaWikiTestCase {
	private $localUsers = [];

	protected function setUp() {
		parent::setUp();

		$this->setGroupPermissions( 'local-id-lookup-test', 'hideuser', true );
	}

	public function addDBData() {
		for ( $i = 1; $i <= 4; $i++ ) {
			$this->localUsers[] = $this->getMutableTestUser()->getUser();
		}

		$sysop = static::getTestSysop()->getUser();

		$block = new Block( [
			'address' => $this->localUsers[2]->getName(),
			'by' => $sysop->getId(),
			'reason' => __METHOD__,
			'expiry' => '1 day',
			'hideName' => false,
		] );
		$block->insert();

		$block = new Block( [
			'address' => $this->localUsers[3]->getName(),
			'by' => $sysop->getId(),
			'reason' => __METHOD__,
			'expiry' => '1 day',
			'hideName' => true,
		] );
		$block->insert();
	}

	public function getLookupUser() {
		return static::getTestUser( [ 'local-id-lookup-test' ] )->getUser();
	}

	public function testLookupCentralIds() {
		$lookup = new LocalIdLookup();

		$user1 = $this->getLookupUser();
		$user2 = User::newFromName( 'UTLocalIdLookup2' );

		$this->assertTrue( $user1->isAllowed( 'hideuser' ), 'sanity check' );
		$this->assertFalse( $user2->isAllowed( 'hideuser' ), 'sanity check' );

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
		$this->assertSame( $expect, $lookup->lookupCentralIds( $arg, $user1 ) );
		$this->assertSame( $expect2, $lookup->lookupCentralIds( $arg, $user2 ) );
	}

	public function testLookupUserNames() {
		$lookup = new LocalIdLookup();
		$user1 = $this->getLookupUser();
		$user2 = User::newFromName( 'UTLocalIdLookup2' );

		$this->assertTrue( $user1->isAllowed( 'hideuser' ), 'sanity check' );
		$this->assertFalse( $user2->isAllowed( 'hideuser' ), 'sanity check' );

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
		$this->assertSame( $expect, $lookup->lookupUserNames( $arg, $user1 ) );
		$this->assertSame( $expect2, $lookup->lookupUserNames( $arg, $user2 ) );
	}

	public function testIsAttached() {
		$lookup = new LocalIdLookup();
		$user1 = $this->getLookupUser();
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
			$lookup->isAttached( $this->getLookupUser(), 'shared' )
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
