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

	protected function tearDown() {
		$block = Block::newFromTarget( 'UTApiBlockee' );
		if ( !is_null( $block ) ) {
			$block->delete();
		}
		parent::tearDown();
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

	/**
	 * @param array $extraParams Extra API parameters to pass to doApiRequest
	 * @param User  $blocker     User to do the blocking, null to pick
	 *                           arbitrarily
	 */
	private function doBlock(array $extraParams = [], User $blocker = null) {
		if ( $blocker === null ) {
			$blocker = self::$users['sysop']->getUser();
		}

		$tokens = $this->getTokens();

		$user = User::newFromName( 'UTApiBlockee' );

		$this->assertNotEquals( $user->getId(), 0, 'The user UTApiBlockee does not exist' );

		$this->assertArrayHasKey( 'blocktoken', $tokens, 'No block token found' );

		$params = [
			'action' => 'block',
			'user' => 'UTApiBlockee',
			'reason' => 'Some reason',
			'token' => $tokens['blocktoken'],
		];
		if ( array_key_exists( 'userid', $extraParams ) ) {
			// Make sure we don't have both user and userid
			unset( $params['user'] );
		}
		$this->doApiRequest( array_merge( $params, $extraParams ), null, false, $blocker );

		$block = Block::newFromTarget( 'UTApiBlockee' );

		$this->assertTrue( !is_null( $block ), 'Block is valid' );

		$this->assertEquals( 'UTApiBlockee', (string)$block->getTarget() );
		$this->assertEquals( 'Some reason', $block->mReason );
		$this->assertEquals( 'infinity', $block->mExpiry );
	}

	/**
	 * Block by username
	 */
	public function testNormalBlock() {
		$this->doBlock();
	}

	/**
	 * Block by user ID
	 */
	public function testNormalBlockId() {
		$user = User::newFromName( 'UTApiBlockee' );

		$this->doBlock( [ 'userid' => $user->getId() ] );
	}

	/**
	 * A blocked user can't block
	 */
	public function testBlockByBlockedUser() {
		$this->setExpectedException( ApiUsageException::class,
			'You cannot block or unblock other users because you are yourself blocked.' );

		$blocked = User::newFromName( 'UTApiBlocked' );
		$blocked->addToDatabase();
		TestUser::setPasswordForUser( $blocked, 'UTApiBlockedPassword' );
		$blocked->saveSettings();
		$blocked->addGroup( 'sysop' );

		// Now block the blocked user!
		$block = new Block([
			'address' => 'UTApiBlocked',
			'by' => User::newFromName( 'UTApiBlockee' )->getId(),
			'reason' => 'Capriciousness',
			'timestamp' => '19370101000000',
			'expiry' => 'infinity',
		]);
		$block->insert();

		$this->doBlock( [], $blocked );
	}

	public function testBlockOfNonexistentUser() {
		$this->setExpectedException( ApiUsageException::class,
			'There is no user by the name "Nonexistent". Check your spelling.' );

		$this->doBlock( [ 'user' => 'Nonexistent' ] );
	}

	public function testBlockOfNonexistentUserId() {
		$id = 948206325;
		$this->setExpectedException( ApiUsageException::class,
			"There is no user with ID $id." );

		$this->assertFalse( User::whoIs( $id ) );

		$this->doBlock( [ 'userid' => $id ] );
	}

	public function testBlockWithProhibitedTag() {
		$this->setExpectedException( ApiUsageException::class,
			'You do not have permission to apply change tags along with your changes.' );

		ChangeTags::defineTag( 'custom tag' );

		$this->setMwGlobals( 'wgRevokePermissions',
			[ 'user' => [ 'applychangetags' => true ] ] );

		$this->doBlock( [ 'tags' => 'custom tag' ] );
	}

	public function testBlockWithProhibitedHide() {
		$this->setExpectedException( ApiUsageException::class,
			"You don't have permission to hide user names from the block log." );

		$this->doBlock( [ 'hidename' => '' ] );
	}

	public function testBlockWithProhibitedEmailBlock() {
		$this->setExpectedException( ApiUsageException::class,
			"You don't have permission to block users from sending email through the wiki." );

		$this->setMwGlobals( 'wgRevokePermissions',
			[ 'sysop' => [ 'blockemail' => true ] ] );

		$this->doBlock( [ 'noemail' => '' ] );
	}

	public function testBlockWithInvalidExpiry() {
		$this->setExpectedException( ApiUsageException::class, "Expiry time invalid." );

		$this->doBlock( [ 'expiry' => '' ] );
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
}
