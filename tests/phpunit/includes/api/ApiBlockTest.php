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

	protected function getTokens( &$request = null ) {
		return $this->getTokenList( self::$users['sysop'], null, $request );
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
		$lastReq = null;
		$tokens = $this->getTokens( $lastReq );

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
			'token' => $tokens['blocktoken'] ), null, false, self::$users['sysop']->user, $lastReq );

		$block = Block::newFromTarget( 'UTApiBlockee' );

		$this->assertTrue( !is_null( $block ), 'Block is valid' );

		$this->assertEquals( 'UTApiBlockee', (string)$block->getTarget() );
		$this->assertEquals( 'Some reason', $block->mReason );
		$this->assertEquals( 'infinity', $block->mExpiry );
	}

	/**
	 * @expectedException UsageException
	 * @expectedExceptionMessage The token parameter must be set
	 */
	public function testBlockingActionWithNoToken( ) {
		$this->doApiRequest(
			array(
				'action' => 'block',
				'user' => 'UTApiBlockee',
				'reason' => 'Some reason',
			),
			null,
			false,
			self::$users['sysop']->user
		);
	}
}
