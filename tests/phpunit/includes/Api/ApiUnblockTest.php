<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Api\ApiUsageException;
use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiUnblock
 */
class ApiUnblockTest extends ApiTestCase {
	/** @var User */
	private $blocker;

	/** @var User */
	private $blockee;

	private DatabaseBlockStore $blockStore;

	protected function setUp(): void {
		parent::setUp();

		$this->blocker = $this->getTestSysop()->getUser();
		$this->blockee = $this->getMutableTestUser()->getUser();
		$this->blockStore = $this->getServiceContainer()->getDatabaseBlockStore();

		$this->overrideConfigValue( MainConfigNames::EnableMultiBlocks, true );

		// Initialize a blocked user (used by most tests, although not all)
		$this->insertBlock();
	}

	private function insertBlock( $options = [] ) {
		$options = array_merge( [
			'targetUser' => $this->blockee,
			'by' => $this->blocker,
		], $options );

		return $this->blockStore->insertBlockWithParams( $options );
	}

	private function getBlocksFromParams( array $params ): array {
		if ( array_key_exists( 'user', $params ) ) {
			return $this->blockStore->newListFromTarget( $params['user'] );
		}
		if ( array_key_exists( 'userid', $params ) ) {
			return $this->blockStore->newListFromTarget(
				$this->getServiceContainer()->getUserFactory()->newFromId( $params['userid'] )
			);
		}

		$block = $this->blockStore->newFromID( $params['id'] );
		return $block ? [ $block ] : [];
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

		$originalBlocks = $this->getBlocksFromParams( $params );

		$this->doApiRequestWithToken( $params );

		// We only check later on whether the blocks existed to begin with, because maybe the caller
		// expects doApiRequestWithToken to throw, in which case the block(s) might not be expected to
		// exist to begin with.
		$this->assertTrue( count( $originalBlocks ) > 0, 'Block(s) should initially exist' );
		$this->assertTrue( !$this->getBlocksFromParams( $params ), 'Block(s)h should have been removed' );
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

		$this->insertBlock( [
			'address' => $this->blocker->getName(),
			'by' => $this->getTestUser( 'sysop' )->getUser(),
		] );

		$this->doUnblock();
	}

	public function testUnblockSelfWhenBlocked() {
		$this->insertBlock( [
			'address' => $this->blocker->getName(),
			'by' => $this->getTestUser( 'sysop' )->getUser(),
		] );

		$this->doUnblock( [ 'user' => $this->blocker->getName() ] );
	}

	public function testUnblockSelfByIdWhenBlocked() {
		$block = $this->insertBlock( [
			'address' => $this->blocker->getName(),
			'by' => $this->getTestUser( 'sysop' )->getUser(),
		] );

		$this->doUnblock( [ 'id' => $block->getId() ] );
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

	public function testUnblockByUserId() {
		$this->doUnblock( [ 'userid' => $this->blockee->getId() ] );
	}

	public function testUnblockByInvalidUserId() {
		$this->expectApiErrorCode( 'nosuchuserid' );
		$this->doUnblock( [ 'userid' => 1234567890 ] );
	}

	public function testUnblockNonexistentBlock() {
		$this->expectApiErrorCode( 'cantunblock' );
		$this->doUnblock( [ 'user' => $this->blocker ] );
	}

	public function testNoSuchBlockId() {
		$this->expectApiErrorCode( 'nosuchblockid' );
		$this->doUnblock( [ 'id' => 12345 ] );
	}

	public function testUnblockByBlockId() {
		$block = $this->insertBlock();
		$this->doUnblock( [ 'id' => $block->getId() ] );
	}

	public function testWatched() {
		$userPage = Title::makeTitle( NS_USER, $this->blockee->getName() );
		$this->doUnblock( [ 'watchuser' => true ] );
		$this->assertTrue( $this->getServiceContainer()->getWatchlistManager()
			->isWatched( $this->blocker, $userPage ) );
	}
}
