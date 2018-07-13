<?php

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiBlock
 */
class ApiBlockTest extends ApiTestCase {
	protected function setUp() {
		parent::setUp();
		$this->doLogin();
	}

<<<<<<< HEAD
	protected function tearDown() {
		$block = Block::newFromTarget( 'UTApiBlockee' );
		if ( !is_null( $block ) ) {
			$block->delete();
		}
		parent::tearDown();
=======
		$this->mUser = $this->getMutableTestUser()->getUser();
		$this->setMwGlobals( 'wgBlockCIDRLimit', [
			'IPv4' => 16,
			'IPv6' => 19,
		] );
>>>>>>> 87993240db... SECURITY: API: Respect $wgBlockCIDRLimit in action=block
	}

	protected function getTokens() {
		return $this->getTokenList( self::$users['sysop'] );
	}

	function addDBDataOnce() {
		$user = User::newFromName( 'UTApiBlockee' );

		if ( $user->getId() == 0 ) {
			$user->addToDatabase();
			TestUser::setPasswordForUser( $user, 'UTApiBlockeePassword' );

			$user->saveSettings();
		}
	}

	/**
	 * This test has probably always been broken and use an invalid token
	 * Bug tracking brokenness is https://phabricator.wikimedia.org/T37646
	 *
	 * Root cause is https://gerrit.wikimedia.org/r/3434
	 * Which made the Block/Unblock API to actually verify the token
	 * previously always considered valid (T36212).
	 */
	public function testMakeNormalBlock() {
		$tokens = $this->getTokens();

<<<<<<< HEAD
		$user = User::newFromName( 'UTApiBlockee' );

		if ( !$user->getId() ) {
			$this->markTestIncomplete( "The user UTApiBlockee does not exist" );
		}
=======
		$this->assertNotNull( $this->mUser, 'Sanity check' );
>>>>>>> 87993240db... SECURITY: API: Respect $wgBlockCIDRLimit in action=block

		if ( !array_key_exists( 'blocktoken', $tokens ) ) {
			$this->markTestIncomplete( "No block token found" );
		}

		$this->doApiRequest( [
			'action' => 'block',
			'user' => 'UTApiBlockee',
			'reason' => 'Some reason',
			'token' => $tokens['blocktoken'] ], null, false, self::$users['sysop']->getUser() );

		$block = Block::newFromTarget( 'UTApiBlockee' );

		$this->assertTrue( !is_null( $block ), 'Block is valid' );

		$this->assertEquals( 'UTApiBlockee', (string)$block->getTarget() );
		$this->assertEquals( 'Some reason', $block->mReason );
		$this->assertEquals( 'infinity', $block->mExpiry );
	}

	/**
	 * Block by user ID
	 */
	public function testMakeNormalBlockId() {
		$tokens = $this->getTokens();
		$user = User::newFromName( 'UTApiBlockee' );

		if ( !$user->getId() ) {
			$this->markTestIncomplete( "The user UTApiBlockee does not exist." );
		}

		if ( !array_key_exists( 'blocktoken', $tokens ) ) {
			$this->markTestIncomplete( "No block token found" );
		}

		$data = $this->doApiRequest( [
			'action' => 'block',
			'userid' => $user->getId(),
			'reason' => 'Some reason',
			'token' => $tokens['blocktoken'] ], null, false, self::$users['sysop']->getUser() );

		$block = Block::newFromTarget( 'UTApiBlockee' );

		$this->assertTrue( !is_null( $block ), 'Block is valid.' );
		$this->assertEquals( 'UTApiBlockee', (string)$block->getTarget() );
		$this->assertEquals( 'Some reason', $block->mReason );
		$this->assertEquals( 'infinity', $block->mExpiry );
	}

	/**
	 * @expectedException ApiUsageException
	 * @expectedExceptionMessage The "token" parameter must be set
	 */
	public function testBlockingActionWithNoToken() {
		$this->doApiRequest(
			[
				'action' => 'block',
				'user' => 'UTApiBlockee',
				'reason' => 'Some reason',
			],
			null,
			false,
			self::$users['sysop']->getUser()
		);
	}

	public function testRangeBlock() {
		$this->mUser = User::newFromName( '128.0.0.0/16', false );
		$this->doBlock();
	}

	/**
	 * @expectedException ApiUsageException
	 * @expectedExceptionMessage Range blocks larger than /16 are not allowed.
	 */
	public function testVeryLargeRangeBlock() {
		$this->mUser = User::newFromName( '128.0.0.0/1', false );
		$this->doBlock();
	}
}
