<?php

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\User\User;

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

	protected function setUp(): void {
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
			'by' => $this->blocker,
		] );
		$result = $this->getServiceContainer()->getDatabaseBlockStore()->insertBlock( $block );
		$this->assertNotFalse( $result, 'Could not insert block' );
		$blockFromDB = DatabaseBlock::newFromID( $result['id'] );
		$this->assertTrue( $blockFromDB !== null, 'Could not retrieve block' );
	}

	private function getBlockFromParams( array $params ) {
		if ( array_key_exists( 'user', $params ) ) {
			return DatabaseBlock::newFromTarget( $params['user'] );
		}
		if ( array_key_exists( 'userid', $params ) ) {
			return DatabaseBlock::newFromTarget(
				$this->getServiceContainer()->getUserFactory()->newFromId( $params['userid'] )
			);
		}
		return DatabaseBlock::newFromID( $params['id'] );
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

	public function testWithNoToken() {
		$this->expectException( ApiUsageException::class );
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
		$this->expectApiErrorCode( 'permissiondenied' );

		$this->setGroupPermissions( 'sysop', 'block', false );

		$this->doUnblock();
	}

	public function testUnblockWhenBlocked() {
		$this->expectApiErrorCode( 'ipbblocked' );

		$block = new DatabaseBlock( [
			'address' => $this->blocker->getName(),
			'by' => $this->getTestUser( 'sysop' )->getUser(),
		] );
		$this->getServiceContainer()->getDatabaseBlockStore()->insertBlock( $block );

		$this->doUnblock();
	}

	public function testUnblockSelfWhenBlocked() {
		$block = new DatabaseBlock( [
			'address' => $this->blocker->getName(),
			'by' => $this->getTestUser( 'sysop' )->getUser(),
		] );
		$result = $this->getServiceContainer()->getDatabaseBlockStore()->insertBlock( $block );
		$this->assertNotFalse( $result, 'Could not insert block' );

		$this->doUnblock( [ 'user' => $this->blocker->getName() ] );
	}

	public function testUnblockWithTagNewBackend() {
		$this->getServiceContainer()->getChangeTagsStore()->defineTag( 'custom tag' );

		$this->doUnblock( [ 'tags' => 'custom tag' ] );

		$this->assertSame( 1, (int)$this->getDb()->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'logging' )
			->join( 'change_tag', null, 'ct_log_id = log_id' )
			->join( 'change_tag_def', null, 'ctd_id = ct_tag_id' )
			->where( [ 'log_type' => 'block', 'ctd_name' => 'custom tag' ] )
			->caller( __METHOD__ )->fetchField() );
	}

	public function testUnblockWithProhibitedTag() {
		$this->expectApiErrorCode( 'tags-apply-no-permission' );

		$this->getServiceContainer()->getChangeTagsStore()->defineTag( 'custom tag' );

		$this->setGroupPermissions( 'user', 'applychangetags', false );

		$this->doUnblock( [ 'tags' => 'custom tag' ] );
	}

	public function testUnblockById() {
		$this->doUnblock( [ 'userid' => $this->blockee->getId() ] );
	}

	public function testUnblockByInvalidId() {
		$this->expectApiErrorCode( 'nosuchuserid' );

		$this->doUnblock( [ 'userid' => 1234567890 ] );
	}

	public function testUnblockNonexistentBlock() {
		$this->expectApiErrorCode( 'cantunblock' );

		$this->doUnblock( [ 'user' => $this->blocker ] );
	}

	public function testWatched() {
		$userPage = Title::makeTitle( NS_USER, $this->blockee->getName() );
		$this->doUnblock( [ 'watchuser' => true ] );
		$this->assertTrue( $this->getServiceContainer()->getWatchlistManager()
			->isWatched( $this->blocker, $userPage ) );
	}
}
