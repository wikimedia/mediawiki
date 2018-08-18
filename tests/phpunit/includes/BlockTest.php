<?php

/**
 * @group Database
 * @group Blocking
 */
class BlockTest extends MediaWikiLangTestCase {

	/**
	 * @return User
	 */
	private function getUserForBlocking() {
		$testUser = $this->getMutableTestUser();
		$user = $testUser->getUser();
		$user->addToDatabase();
		TestUser::setPasswordForUser( $user, 'UTBlockeePassword' );
		$user->saveSettings();
		return $user;
	}

	/**
	 * @param User $user
	 *
	 * @return Block
	 * @throws MWException
	 */
	private function addBlockForUser( User $user ) {
		// Delete the last round's block if it's still there
		$oldBlock = Block::newFromTarget( $user->getName() );
		if ( $oldBlock ) {
			// An old block will prevent our new one from saving.
			$oldBlock->delete();
		}

		$blockOptions = [
			'address' => $user->getName(),
			'user' => $user->getId(),
			'by' => $this->getTestSysop()->getUser()->getId(),
			'reason' => 'Parce que',
			'expiry' => time() + 100500,
		];
		$block = new Block( $blockOptions );

		$block->insert();
		// save up ID for use in assertion. Since ID is an autoincrement,
		// its value might change depending on the order the tests are run.
		// ApiBlockTest insert its own blocks!
		if ( !$block->getId() ) {
			throw new MWException( "Failed to insert block for BlockTest; old leftover block remaining?" );
		}

		$this->addXffBlocks();

		return $block;
	}

	/**
	 * @covers Block::newFromTarget
	 */
	public function testINewFromTargetReturnsCorrectBlock() {
		$user = $this->getUserForBlocking();
		$block = $this->addBlockForUser( $user );

		$this->assertTrue(
			$block->equals( Block::newFromTarget( $user->getName() ) ),
			"newFromTarget() returns the same block as the one that was made"
		);
	}

	/**
	 * @covers Block::newFromID
	 */
	public function testINewFromIDReturnsCorrectBlock() {
		$user = $this->getUserForBlocking();
		$block = $this->addBlockForUser( $user );

		$this->assertTrue(
			$block->equals( Block::newFromID( $block->getId() ) ),
			"newFromID() returns the same block as the one that was made"
		);
	}

	/**
	 * per T28425
	 * @covers Block::__construct
	 */
	public function testT28425BlockTimestampDefaultsToTime() {
		$user = $this->getUserForBlocking();
		$block = $this->addBlockForUser( $user );
		$madeAt = wfTimestamp( TS_MW );

		// delta to stop one-off errors when things happen to go over a second mark.
		$delta = abs( $madeAt - $block->mTimestamp );
		$this->assertLessThan(
			2,
			$delta,
			"If no timestamp is specified, the block is recorded as time()"
		);
	}

	/**
	 * CheckUser since being changed to use Block::newFromTarget started failing
	 * because the new function didn't accept empty strings like Block::load()
	 * had. Regression T31116.
	 *
	 * @dataProvider provideT31116Data
	 * @covers Block::newFromTarget
	 */
	public function testT31116NewFromTargetWithEmptyIp( $vagueTarget ) {
		$user = $this->getUserForBlocking();
		$initialBlock = $this->addBlockForUser( $user );
		$block = Block::newFromTarget( $user->getName(), $vagueTarget );

		$this->assertTrue(
			$initialBlock->equals( $block ),
			"newFromTarget() returns the same block as the one that was made when "
				. "given empty vagueTarget param " . var_export( $vagueTarget, true )
		);
	}

	public static function provideT31116Data() {
		return [
			[ null ],
			[ '' ],
			[ false ]
		];
	}

	/**
	 * @covers Block::prevents
	 */
	public function testBlockedUserCanNotCreateAccount() {
		$username = 'BlockedUserToCreateAccountWith';
		$u = User::newFromName( $username );
		$u->addToDatabase();
		$userId = $u->getId();
		$this->assertNotEquals( 0, $userId, 'sanity' );
		TestUser::setPasswordForUser( $u, 'NotRandomPass' );
		unset( $u );

		// Sanity check
		$this->assertNull(
			Block::newFromTarget( $username ),
			"$username should not be blocked"
		);

		// Reload user
		$u = User::newFromName( $username );
		$this->assertFalse(
			$u->isBlockedFromCreateAccount(),
			"Our sandbox user should be able to create account before being blocked"
		);

		// Foreign perspective (blockee not on current wiki)...
		$blockOptions = [
			'address' => $username,
			'user' => $userId,
			'reason' => 'crosswiki block...',
			'timestamp' => wfTimestampNow(),
			'expiry' => $this->db->getInfinity(),
			'createAccount' => true,
			'enableAutoblock' => true,
			'hideName' => true,
			'blockEmail' => true,
			'byText' => 'm>MetaWikiUser',
		];
		$block = new Block( $blockOptions );
		$block->insert();

		// Reload block from DB
		$userBlock = Block::newFromTarget( $username );
		$this->assertTrue(
			(bool)$block->prevents( 'createaccount' ),
			"Block object in DB should prevents 'createaccount'"
		);

		$this->assertInstanceOf(
			Block::class,
			$userBlock,
			"'$username' block block object should be existent"
		);

		// Reload user
		$u = User::newFromName( $username );
		$this->assertTrue(
			(bool)$u->isBlockedFromCreateAccount(),
			"Our sandbox user '$username' should NOT be able to create account"
		);
	}

	/**
	 * @covers Block::insert
	 */
	public function testCrappyCrossWikiBlocks() {
		// Delete the last round's block if it's still there
		$oldBlock = Block::newFromTarget( 'UserOnForeignWiki' );
		if ( $oldBlock ) {
			// An old block will prevent our new one from saving.
			$oldBlock->delete();
		}

		// Local perspective (blockee on current wiki)...
		$user = User::newFromName( 'UserOnForeignWiki' );
		$user->addToDatabase();
		$userId = $user->getId();
		$this->assertNotEquals( 0, $userId, 'sanity' );

		// Foreign perspective (blockee not on current wiki)...
		$blockOptions = [
			'address' => 'UserOnForeignWiki',
			'user' => $user->getId(),
			'reason' => 'crosswiki block...',
			'timestamp' => wfTimestampNow(),
			'expiry' => $this->db->getInfinity(),
			'createAccount' => true,
			'enableAutoblock' => true,
			'hideName' => true,
			'blockEmail' => true,
			'byText' => 'Meta>MetaWikiUser',
		];
		$block = new Block( $blockOptions );

		$res = $block->insert( $this->db );
		$this->assertTrue( (bool)$res['id'], 'Block succeeded' );

		$user = null; // clear

		$block = Block::newFromID( $res['id'] );
		$this->assertEquals(
			'UserOnForeignWiki',
			$block->getTarget()->getName(),
			'Correct blockee name'
		);
		$this->assertEquals( $userId, $block->getTarget()->getId(), 'Correct blockee id' );
		$this->assertEquals( 'Meta>MetaWikiUser', $block->getBlocker()->getName(),
			'Correct blocker name' );
		$this->assertEquals( 'Meta>MetaWikiUser', $block->getByName(), 'Correct blocker name' );
		$this->assertEquals( 0, $block->getBy(), 'Correct blocker id' );
	}

	protected function addXffBlocks() {
		static $inited = false;

		if ( $inited ) {
			return;
		}

		$inited = true;

		$blockList = [
			[ 'target' => '70.2.0.0/16',
				'type' => Block::TYPE_RANGE,
				'desc' => 'Range Hardblock',
				'ACDisable' => false,
				'isHardblock' => true,
				'isAutoBlocking' => false,
			],
			[ 'target' => '2001:4860:4001::/48',
				'type' => Block::TYPE_RANGE,
				'desc' => 'Range6 Hardblock',
				'ACDisable' => false,
				'isHardblock' => true,
				'isAutoBlocking' => false,
			],
			[ 'target' => '60.2.0.0/16',
				'type' => Block::TYPE_RANGE,
				'desc' => 'Range Softblock with AC Disabled',
				'ACDisable' => true,
				'isHardblock' => false,
				'isAutoBlocking' => false,
			],
			[ 'target' => '50.2.0.0/16',
				'type' => Block::TYPE_RANGE,
				'desc' => 'Range Softblock',
				'ACDisable' => false,
				'isHardblock' => false,
				'isAutoBlocking' => false,
			],
			[ 'target' => '50.1.1.1',
				'type' => Block::TYPE_IP,
				'desc' => 'Exact Softblock',
				'ACDisable' => false,
				'isHardblock' => false,
				'isAutoBlocking' => false,
			],
		];

		$blocker = $this->getTestUser()->getUser();
		foreach ( $blockList as $insBlock ) {
			$target = $insBlock['target'];

			if ( $insBlock['type'] === Block::TYPE_IP ) {
				$target = User::newFromName( IP::sanitizeIP( $target ), false )->getName();
			} elseif ( $insBlock['type'] === Block::TYPE_RANGE ) {
				$target = IP::sanitizeRange( $target );
			}

			$block = new Block();
			$block->setTarget( $target );
			$block->setBlocker( $blocker );
			$block->mReason = $insBlock['desc'];
			$block->mExpiry = 'infinity';
			$block->prevents( 'createaccount', $insBlock['ACDisable'] );
			$block->isHardblock( $insBlock['isHardblock'] );
			$block->isAutoblocking( $insBlock['isAutoBlocking'] );
			$block->insert();
		}
	}

	public static function providerXff() {
		return [
			[ 'xff' => '1.2.3.4, 70.2.1.1, 60.2.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Range Hardblock'
			],
			[ 'xff' => '1.2.3.4, 50.2.1.1, 60.2.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Range Softblock with AC Disabled'
			],
			[ 'xff' => '1.2.3.4, 70.2.1.1, 50.1.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Exact Softblock'
			],
			[ 'xff' => '1.2.3.4, 70.2.1.1, 50.2.1.1, 50.1.1.1, 2.3.4.5',
				'count' => 3,
				'result' => 'Exact Softblock'
			],
			[ 'xff' => '1.2.3.4, 70.2.1.1, 50.2.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Range Hardblock'
			],
			[ 'xff' => '1.2.3.4, 70.2.1.1, 60.2.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Range Hardblock'
			],
			[ 'xff' => '50.2.1.1, 60.2.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Range Softblock with AC Disabled'
			],
			[ 'xff' => '1.2.3.4, 50.1.1.1, 60.2.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Exact Softblock'
			],
			[ 'xff' => '1.2.3.4, <$A_BUNCH-OF{INVALID}TEXT\>, 60.2.1.1, 2.3.4.5',
				'count' => 1,
				'result' => 'Range Softblock with AC Disabled'
			],
			[ 'xff' => '1.2.3.4, 50.2.1.1, 2001:4860:4001:802::1003, 2.3.4.5',
				'count' => 2,
				'result' => 'Range6 Hardblock'
			],
		];
	}

	/**
	 * @dataProvider providerXff
	 * @covers Block::getBlocksForIPList
	 * @covers Block::chooseBlock
	 */
	public function testBlocksOnXff( $xff, $exCount, $exResult ) {
		$user = $this->getUserForBlocking();
		$this->addBlockForUser( $user );

		$list = array_map( 'trim', explode( ',', $xff ) );
		$xffblocks = Block::getBlocksForIPList( $list, true );
		$this->assertEquals( $exCount, count( $xffblocks ), 'Number of blocks for ' . $xff );
		$block = Block::chooseBlock( $xffblocks, $list );
		$this->assertEquals( $exResult, $block->mReason, 'Correct block type for XFF header ' . $xff );
	}

	/**
	 * @covers Block::__construct
	 */
	public function testDeprecatedConstructor() {
		$this->hideDeprecated( 'Block::__construct with multiple arguments' );
		$username = 'UnthinkablySecretRandomUsername';
		$reason = 'being irrational';

		# Set up the target
		$u = User::newFromName( $username );
		if ( $u->getId() == 0 ) {
			$u->addToDatabase();
			TestUser::setPasswordForUser( $u, 'TotallyObvious' );
		}
		unset( $u );

		# Make sure the user isn't blocked
		$this->assertNull(
			Block::newFromTarget( $username ),
			"$username should not be blocked"
		);

		# Perform the block
		$block = new Block(
			/* address */ $username,
			/* user */ 0,
			/* by */ $this->getTestSysop()->getUser()->getId(),
			/* reason */ $reason,
			/* timestamp */ 0,
			/* auto */ false,
			/* expiry */ 0
		);
		$block->insert();

		# Check target
		$this->assertEquals(
			$block->getTarget()->getName(),
			$username,
			"Target should be set properly"
		);

		# Check supplied parameter
		$this->assertEquals(
			$block->mReason,
			$reason,
			"Reason should be non-default"
		);

		# Check default parameter
		$this->assertFalse(
			(bool)$block->prevents( 'createaccount' ),
			"Account creation should not be blocked by default"
		);
	}

	/**
	 * @covers Block::getSystemBlockType
	 * @covers Block::insert
	 * @covers Block::doAutoblock
	 */
	public function testSystemBlocks() {
		$user = $this->getUserForBlocking();
		$this->addBlockForUser( $user );

		$blockOptions = [
			'address' => $user->getName(),
			'reason' => 'test system block',
			'timestamp' => wfTimestampNow(),
			'expiry' => $this->db->getInfinity(),
			'byText' => 'MediaWiki default',
			'systemBlock' => 'test',
			'enableAutoblock' => true,
		];
		$block = new Block( $blockOptions );

		$this->assertSame( 'test', $block->getSystemBlockType() );

		try {
			$block->insert();
			$this->fail( 'Expected exception not thrown' );
		} catch ( MWException $ex ) {
			$this->assertSame( 'Cannot insert a system block into the database', $ex->getMessage() );
		}

		try {
			$block->doAutoblock( '192.0.2.2' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( MWException $ex ) {
			$this->assertSame( 'Cannot autoblock from a system block', $ex->getMessage() );
		}
	}

}
