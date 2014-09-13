<?php

/**
 * @group Database
 * @group Blocking
 */
class BlockTest extends MediaWikiLangTestCase {

	/** @var Block */
	private $block;
	private $madeAt;

	/* variable used to save up the blockID we insert in this test suite */
	private $blockId;

	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( array(
			'wgLanguageCode' => 'en',
			'wgContLang' => Language::factory( 'en' )
		) );
	}

	function addDBData() {

		$user = User::newFromName( 'UTBlockee' );
		if ( $user->getID() == 0 ) {
			$user->addToDatabase();
			$user->setPassword( 'UTBlockeePassword' );

			$user->saveSettings();
		}

		// Delete the last round's block if it's still there
		$oldBlock = Block::newFromTarget( 'UTBlockee' );
		if ( $oldBlock ) {
			// An old block will prevent our new one from saving.
			$oldBlock->delete();
		}

		$this->block = new Block( 'UTBlockee', $user->getID(), 0,
			'Parce que', 0, false, time() + 100500
		);
		$this->madeAt = wfTimestamp( TS_MW );

		$this->block->insert();
		// save up ID for use in assertion. Since ID is an autoincrement,
		// its value might change depending on the order the tests are run.
		// ApiBlockTest insert its own blocks!
		$newBlockId = $this->block->getId();
		if ( $newBlockId ) {
			$this->blockId = $newBlockId;
		} else {
			throw new MWException( "Failed to insert block for BlockTest; old leftover block remaining?" );
		}

		$this->addXffBlocks();
	}

	/**
	 * debug function : dump the ipblocks table
	 */
	function dumpBlocks() {
		$v = $this->db->select( 'ipblocks', '*' );
		print "Got " . $v->numRows() . " rows. Full dump follow:\n";
		foreach ( $v as $row ) {
			print_r( $row );
		}
	}

	/**
	 * @covers Block::newFromTarget
	 */
	public function testINewFromTargetReturnsCorrectBlock() {
		$this->assertTrue( $this->block->equals( Block::newFromTarget( 'UTBlockee' ) ), "newFromTarget() returns the same block as the one that was made" );
	}

	/**
	 * @covers Block::newFromID
	 */
	public function testINewFromIDReturnsCorrectBlock() {
		$this->assertTrue( $this->block->equals( Block::newFromID( $this->blockId ) ), "newFromID() returns the same block as the one that was made" );
	}

	/**
	 * per bug 26425
	 */
	public function testBug26425BlockTimestampDefaultsToTime() {
		// delta to stop one-off errors when things happen to go over a second mark.
		$delta = abs( $this->madeAt - $this->block->mTimestamp );
		$this->assertLessThan( 2, $delta, "If no timestamp is specified, the block is recorded as time()" );
	}

	/**
	 * CheckUser since being changed to use Block::newFromTarget started failing
	 * because the new function didn't accept empty strings like Block::load()
	 * had. Regression bug 29116.
	 *
	 * @dataProvider provideBug29116Data
	 * @covers Block::newFromTarget
	 */
	public function testBug29116NewFromTargetWithEmptyIp( $vagueTarget ) {
		$block = Block::newFromTarget( 'UTBlockee', $vagueTarget );
		$this->assertTrue( $this->block->equals( $block ), "newFromTarget() returns the same block as the one that was made when given empty vagueTarget param " . var_export( $vagueTarget, true ) );
	}

	public static function provideBug29116Data() {
		return array(
			array( null ),
			array( '' ),
			array( false )
		);
	}

	/**
	 * @covers Block::prevents
	 */
	public function testBlockedUserCanNotCreateAccount() {
		$username = 'BlockedUserToCreateAccountWith';
		$u = User::newFromName( $username );
		$u->setPassword( 'NotRandomPass' );
		$u->addToDatabase();
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
		$block = new Block(
			/* $address */ $username,
			/* $user */ 14146,
			/* $by */ 0,
			/* $reason */ 'crosswiki block...',
			/* $timestamp */ wfTimestampNow(),
			/* $auto */ false,
			/* $expiry */ $this->db->getInfinity(),
			/* anonOnly */ false,
			/* $createAccount */ true,
			/* $enableAutoblock */ true,
			/* $hideName (ipb_deleted) */ true,
			/* $blockEmail */ true,
			/* $allowUsertalk */ false,
			/* $byName */ 'MetaWikiUser'
		);
		$block->insert();

		// Reload block from DB
		$userBlock = Block::newFromTarget( $username );
		$this->assertTrue(
			(bool)$block->prevents( 'createaccount' ),
			"Block object in DB should prevents 'createaccount'"
		);

		$this->assertInstanceOf(
			'Block',
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

		// Foreign perspective (blockee not on current wiki)...
		$block = new Block(
			/* $address */ 'UserOnForeignWiki',
			/* $user */ 14146,
			/* $by */ 0,
			/* $reason */ 'crosswiki block...',
			/* $timestamp */ wfTimestampNow(),
			/* $auto */ false,
			/* $expiry */ $this->db->getInfinity(),
			/* anonOnly */ false,
			/* $createAccount */ true,
			/* $enableAutoblock */ true,
			/* $hideName (ipb_deleted) */ true,
			/* $blockEmail */ true,
			/* $allowUsertalk */ false,
			/* $byName */ 'MetaWikiUser'
		);

		$res = $block->insert( $this->db );
		$this->assertTrue( (bool)$res['id'], 'Block succeeded' );

		// Local perspective (blockee on current wiki)...
		$user = User::newFromName( 'UserOnForeignWiki' );
		$user->addToDatabase();
		// Set user ID to match the test value
		$this->db->update( 'user', array( 'user_id' => 14146 ), array( 'user_id' => $user->getId() ) );
		$user = null; // clear

		$block = Block::newFromID( $res['id'] );
		$this->assertEquals( 'UserOnForeignWiki', $block->getTarget()->getName(), 'Correct blockee name' );
		$this->assertEquals( '14146', $block->getTarget()->getId(), 'Correct blockee id' );
		$this->assertEquals( 'MetaWikiUser', $block->getBlocker(), 'Correct blocker name' );
		$this->assertEquals( 'MetaWikiUser', $block->getByName(), 'Correct blocker name' );
		$this->assertEquals( 0, $block->getBy(), 'Correct blocker id' );
	}

	protected function addXffBlocks() {
		static $inited = false;

		if ( $inited ) {
			return;
		}

		$inited = true;

		$blockList = array(
			array( 'target' => '70.2.0.0/16',
				'type' => Block::TYPE_RANGE,
				'desc' => 'Range Hardblock',
				'ACDisable' => false,
				'isHardblock' => true,
				'isAutoBlocking' => false,
			),
			array( 'target' => '2001:4860:4001::/48',
				'type' => Block::TYPE_RANGE,
				'desc' => 'Range6 Hardblock',
				'ACDisable' => false,
				'isHardblock' => true,
				'isAutoBlocking' => false,
			),
			array( 'target' => '60.2.0.0/16',
				'type' => Block::TYPE_RANGE,
				'desc' => 'Range Softblock with AC Disabled',
				'ACDisable' => true,
				'isHardblock' => false,
				'isAutoBlocking' => false,
			),
			array( 'target' => '50.2.0.0/16',
				'type' => Block::TYPE_RANGE,
				'desc' => 'Range Softblock',
				'ACDisable' => false,
				'isHardblock' => false,
				'isAutoBlocking' => false,
			),
			array( 'target' => '50.1.1.1',
				'type' => Block::TYPE_IP,
				'desc' => 'Exact Softblock',
				'ACDisable' => false,
				'isHardblock' => false,
				'isAutoBlocking' => false,
			),
		);

		foreach ( $blockList as $insBlock ) {
			$target = $insBlock['target'];

			if ( $insBlock['type'] === Block::TYPE_IP ) {
				$target = User::newFromName( IP::sanitizeIP( $target ), false )->getName();
			} elseif ( $insBlock['type'] === Block::TYPE_RANGE ) {
				$target = IP::sanitizeRange( $target );
			}

			$block = new Block();
			$block->setTarget( $target );
			$block->setBlocker( 'testblocker@global' );
			$block->mReason = $insBlock['desc'];
			$block->mExpiry = 'infinity';
			$block->prevents( 'createaccount', $insBlock['ACDisable'] );
			$block->isHardblock( $insBlock['isHardblock'] );
			$block->isAutoblocking( $insBlock['isAutoBlocking'] );
			$block->insert();
		}
	}

	public static function providerXff() {
		return array(
			array( 'xff' => '1.2.3.4, 70.2.1.1, 60.2.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Range Hardblock'
			),
			array( 'xff' => '1.2.3.4, 50.2.1.1, 60.2.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Range Softblock with AC Disabled'
			),
			array( 'xff' => '1.2.3.4, 70.2.1.1, 50.1.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Exact Softblock'
			),
			array( 'xff' => '1.2.3.4, 70.2.1.1, 50.2.1.1, 50.1.1.1, 2.3.4.5',
				'count' => 3,
				'result' => 'Exact Softblock'
			),
			array( 'xff' => '1.2.3.4, 70.2.1.1, 50.2.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Range Hardblock'
			),
			array( 'xff' => '1.2.3.4, 70.2.1.1, 60.2.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Range Hardblock'
			),
			array( 'xff' => '50.2.1.1, 60.2.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Range Softblock with AC Disabled'
			),
			array( 'xff' => '1.2.3.4, 50.1.1.1, 60.2.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Exact Softblock'
			),
			array( 'xff' => '1.2.3.4, <$A_BUNCH-OF{INVALID}TEXT\>, 60.2.1.1, 2.3.4.5',
				'count' => 1,
				'result' => 'Range Softblock with AC Disabled'
			),
			array( 'xff' => '1.2.3.4, 50.2.1.1, 2001:4860:4001:802::1003, 2.3.4.5',
				'count' => 2,
				'result' => 'Range6 Hardblock'
			),
		);
	}

	/**
	 * @dataProvider providerXff
	 * @covers Block::getBlocksForIPList
	 * @covers Block::chooseBlock
	 */
	public function testBlocksOnXff( $xff, $exCount, $exResult ) {
		$list = array_map( 'trim', explode( ',', $xff ) );
		$xffblocks = Block::getBlocksForIPList( $list, true );
		$this->assertEquals( $exCount, count( $xffblocks ), 'Number of blocks for ' . $xff );
		$block = Block::chooseBlock( $xffblocks, $list );
		$this->assertEquals( $exResult, $block->mReason, 'Correct block type for XFF header ' . $xff );
	}
}
