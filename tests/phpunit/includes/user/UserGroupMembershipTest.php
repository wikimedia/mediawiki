<?php

use Wikimedia\Assert\ParameterTypeException;

class UserGroupMembershipTest extends MediaWikiIntegrationTestCase {

	protected function setUp() : void {
		parent::setUp();

		$this->setMwGlobals( [
			'wgGroupPermissions' => [
				'unittesters' => [
					'runtest' => true,
				],
				'testwriters' => [
					'writetest' => true,
				]
			]
		] );
	}

	public function provideInstantiationValidationErrors() {
		return [
			[ 'A', null, null, 'Bad value for parameter $userId: must be a integer' ],
			[ 1, 1, null, 'Bad value for parameter $group: must be a string' ],
			[ 1, null, 1, 'Bad value for parameter $expiry: must be a string' ],
		];
	}

	/**
	 * @param $userId
	 * @param $group
	 * @param $expiry
	 * @param $exception
	 *
	 * @dataProvider provideInstantiationValidationErrors
	 * @covers UserGroupMembership
	 */
	public function testInstantiationValidationErrors( $userId, $group, $expiry, $exception ) {
		$this->expectExceptionMessage( $exception );
		$this->expectException( ParameterTypeException::class );
		$ugm = new UserGroupMembership( $userId, $group, $expiry );
	}

	public function provideInstantiationValidation() {
		return [
			[ 1, null, null, 1, null, null ],
			[ 1, 'test', null, 1, 'test', null ],
			[ 1, 'test', '12345', 1, 'test', '12345' ]
		];
	}

	/**
	 * @param $userId
	 * @param $group
	 * @param $expiry
	 * @param $userId_
	 * @param $group_
	 * @param $expiry_
	 *
	 * @dataProvider provideInstantiationValidation
	 * @covers UserGroupMembership
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
	 * @covers UserGroupMembership::equals
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
	 * @covers UserGroupMembership::isExpired
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
