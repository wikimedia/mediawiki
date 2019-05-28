<?php

use MediaWiki\Block\DatabaseBlock;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiUnblock
 */
class ApiUnblockTest extends ApiTestCase {
	/** @var User */
	private $blocker;

	/** @var User */
	private $blockee;

	public function setUp() {
		parent::setUp();

		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[ 'ipblocks', 'change_tag', 'change_tag_def', 'logging' ]
		);

		$this->blocker = $this->getTestSysop()->getUser();
		$this->blockee = $this->getMutableTestUser()->getUser();

		// Initialize a blocked user (used by most tests, although not all)
		$block = new DatabaseBlock( [
			'address' => $this->blockee->getName(),
			'by' => $this->blocker->getId(),
		] );
		$result = $block->insert();
		$this->assertNotFalse( $result, 'Could not insert block' );
		$blockFromDB = DatabaseBlock::newFromID( $result['id'] );
		$this->assertTrue( !is_null( $blockFromDB ), 'Could not retrieve block' );
	}

	private function getBlockFromParams( array $params ) {
		if ( array_key_exists( 'user', $params ) ) {
			return DatabaseBlock::newFromTarget( $params['user'] );
		}
		if ( array_key_exists( 'userid', $params ) ) {
			return DatabaseBlock::newFromTarget( User::newFromId( $params['userid'] ) );
		}
		return DatabaseBlock::newFromId( $params['id'] );
	}

	/**
	 * Try to submit the unblock API request and check that the block no longer exists.
	 *
	 * @param array $params API request query parameters
	 */
	private function doUnblock( array $params = [] ) {
		$params += [ 'action' => 'unblock' ];
		if ( !array_key_exists( 'userid', $params ) && !array_key_exists( 'id', $params ) ) {
			$params += [ 'user' => $this->blockee->getName() ];
		}

		$originalBlock = $this->getBlockFromParams( $params );

		$this->doApiRequestWithToken( $params );

		// We only check later on whether the block existed to begin with, because maybe the caller
		// expects doApiRequestWithToken to throw, in which case the block might not be expected to
		// exist to begin with.
		$this->assertInstanceOf( DatabaseBlock::class, $originalBlock, 'Block should initially exist' );
		$this->assertNull( $this->getBlockFromParams( $params ), 'Block should have been removed' );
	}

	/**
	 * @expectedException ApiUsageException
	 */
	public function testWithNoToken() {
		$this->doApiRequest( [
			'action' => 'unblock',
			'user' => $this->blockee->getName(),
			'reason' => 'Some reason',
		] );
	}

	public function testNormalUnblock() {
		$this->doUnblock();
	}

	public function testUnblockNoPermission() {
		$this->setExpectedApiException( 'apierror-permissiondenied-unblock' );

		$this->setGroupPermissions( 'sysop', 'block', false );

		$this->doUnblock();
	}

	public function testUnblockWhenBlocked() {
		$this->setExpectedApiException( 'ipbblocked' );

		$block = new DatabaseBlock( [
			'address' => $this->blocker->getName(),
			'by' => $this->getTestUser( 'sysop' )->getUser()->getId(),
		] );
		$block->insert();

		$this->doUnblock();
	}

	public function testUnblockSelfWhenBlocked() {
		$block = new DatabaseBlock( [
			'address' => $this->blocker->getName(),
			'by' => $this->getTestUser( 'sysop' )->getUser()->getId(),
		] );
		$result = $block->insert();
		$this->assertNotFalse( $result, 'Could not insert block' );

		$this->doUnblock( [ 'user' => $this->blocker->getName() ] );
	}

	public function testUnblockWithTagNewBackend() {
		ChangeTags::defineTag( 'custom tag' );

		$this->doUnblock( [ 'tags' => 'custom tag' ] );

		$dbw = wfGetDB( DB_MASTER );
		$this->assertSame( 1, (int)$dbw->selectField(
			[ 'change_tag', 'logging', 'change_tag_def' ],
			'COUNT(*)',
			[ 'log_type' => 'block', 'ctd_name' => 'custom tag' ],
			__METHOD__,
			[],
			[
				'change_tag' => [ 'JOIN', 'ct_log_id = log_id' ],
				'change_tag_def' => [ 'JOIN', 'ctd_id = ct_tag_id' ],
			]
		) );
	}

	public function testUnblockWithProhibitedTag() {
		$this->setExpectedApiException( 'tags-apply-no-permission' );

		ChangeTags::defineTag( 'custom tag' );

		$this->setGroupPermissions( 'user', 'applychangetags', false );

		$this->doUnblock( [ 'tags' => 'custom tag' ] );
	}

	public function testUnblockById() {
		$this->doUnblock( [ 'userid' => $this->blockee->getId() ] );
	}

	public function testUnblockByInvalidId() {
		$this->setExpectedApiException( [ 'apierror-nosuchuserid', 1234567890 ] );

		$this->doUnblock( [ 'userid' => 1234567890 ] );
	}

	public function testUnblockNonexistentBlock() {
		$this->setExpectedAPIException( [ 'ipb_cant_unblock', $this->blocker->getName() ] );

		$this->doUnblock( [ 'user' => $this->blocker ] );
	}
}
