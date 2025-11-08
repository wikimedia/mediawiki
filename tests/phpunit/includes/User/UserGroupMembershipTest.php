<?php

use MediaWiki\User\UserGroupMembership;

class UserGroupMembershipTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->setGroupPermissions(
			[
				'unittesters' => [
					'runtest' => true,
				],
				'testwriters' => [
					'writetest' => true,
				]
			]
		);
	}

	public static function provideInstantiationValidation() {
		return [
			[ 1, null, null, 1, null, null ],
			[ 1, 'test', null, 1, 'test', null ],
			[ 1, 'test', '12345', 1, 'test', '12345' ]
		];
	}

	/**
	 * @dataProvider provideInstantiationValidation
	 * @covers \MediaWiki\User\UserGroupMembership
	 */
	public function testInstantiation( $userId, $group, $expiry, $userId_, $group_, $expiry_ ) {
		$ugm = new UserGroupMembership( $userId, $group, $expiry );
		$this->assertSame(
			$userId_,
			$ugm->getUserId()
		);
		$this->assertSame(
			$group_,
			$ugm->getGroup()
		);
		$this->assertSame(
			$expiry_,
			$ugm->getExpiry()
		);
	}

	/**
	 * @covers \MediaWiki\User\UserGroupMembership::equals
	 */
	public function testComparison() {
		$ugm1 = new UserGroupMembership( 1, 'test', '67890' );
		$ugm2 = new UserGroupMembership( 1, 'test', '67890' );
		$ugm3 = new UserGroupMembership( 1, 'fail', '67890' );
		$ugm4 = new UserGroupMembership( 1, 'fail', '12345' );
		$this->assertTrue( $ugm1->equals( $ugm2 ) );
		$this->assertTrue( $ugm2->equals( $ugm1 ) );
		$this->assertFalse( $ugm1->equals( $ugm3 ) );
		$this->assertFalse( $ugm2->equals( $ugm3 ) );
		$this->assertFalse( $ugm3->equals( $ugm1 ) );
		// Ensure expiry is ignored
		$this->assertTrue( $ugm3->equals( $ugm4 ) );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupMembership::isExpired
	 */
	public function testIsExpired() {
		$ts = wfTimestamp( TS_MW, time() - 100 );
		$ugm = new UserGroupMembership( 1, null, $ts );
		$this->assertTrue(
			$ugm->isExpired()
		);
		$ts = wfTimestamp( TS_MW, time() + 100 );
		$ugm = new UserGroupMembership( 1, null, $ts );
		$this->assertFalse(
			$ugm->isExpired()
		);
		$ugm = new UserGroupMembership( 1, null, null );
		$this->assertFalse(
			$ugm->isExpired()
		);
	}

}
