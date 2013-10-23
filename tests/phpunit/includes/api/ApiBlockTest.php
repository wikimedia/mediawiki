<?php

/**
 * @group API
 * @group Database
 * @group medium
 */
class ApiBlockTest extends ApiTestCase {
	protected function setUp() {
		parent::setUp();
		$this->doLogin();
	}

	function getTokens() {
		return $this->getTokenList( self::$users['sysop'] );
	}

	function addDBData() {
		$user = User::newFromName( 'UTApiBlockee' );

		if ( $user->getId() == 0 ) {
			$user->addToDatabase();
			$user->setPassword( 'UTApiBlockeePassword' );

			$user->saveSettings();
		}
	}

	/**
	 * This test has probably always been broken and use an invalid token
	 * Bug tracking brokenness is https://bugzilla.wikimedia.org/35646
	 *
	 * Root cause is https://gerrit.wikimedia.org/r/3434
	 * Which made the Block/Unblock API to actually verify the token
	 * previously always considered valid (bug 34212).
	 */
	public function testMakeNormalBlock() {
		$tokens = $this->getTokens();

		$user = User::newFromName( 'UTApiBlockee' );

		if ( !$user->getId() ) {
			$this->markTestIncomplete( "The user UTApiBlockee does not exist" );
		}

		if ( !array_key_exists( 'blocktoken', $tokens ) ) {
			$this->markTestIncomplete( "No block token found" );
		}

		$this->doApiRequest( array(
			'action' => 'block',
			'user' => 'UTApiBlockee',
			'reason' => 'Some reason',
			'token' => $tokens['blocktoken'] ), null, false, self::$users['sysop']->user );

		$block = Block::newFromTarget( 'UTApiBlockee' );

		$this->assertTrue( !is_null( $block ), 'Block is valid' );

		$this->assertEquals( 'UTApiBlockee', (string)$block->getTarget() );
		$this->assertEquals( 'Some reason', $block->mReason );
		$this->assertEquals( 'infinity', $block->mExpiry );
	}

	/**
	 * Attempting to block without a token should give a UsageException with
	 * error message:
	 *   "The token parameter must be set"
	 *
	 * @dataProvider provideBlockUnblockAction
	 * @expectedException UsageException
	 */
	public function testBlockingActionWithNoToken( $action ) {
		$this->doApiRequest(
			array(
				'action' => $action,
				'user' => 'UTApiBlockee',
				'reason' => 'Some reason',
			),
			null,
			false,
			self::$users['sysop']->user
		);
	}

	/**
	 * Just provide the 'block' and 'unblock' action to test both API calls
	 */
	public static function provideBlockUnblockAction() {
		return array(
			array( 'block' ),
			array( 'unblock' ),
		);
	}
}
