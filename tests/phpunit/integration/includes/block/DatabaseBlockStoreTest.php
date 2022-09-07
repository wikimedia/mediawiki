<?php

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use Psr\Log\NullLogger;

/**
 * Integration tests for DatabaseBlockStore.
 *
 * @author DannyS712
 * @group Blocking
 * @group Database
 * @covers \MediaWiki\Block\DatabaseBlockStore
 */
class DatabaseBlockStoreTest extends MediaWikiIntegrationTestCase {
	use DummyServicesTrait;

	/** @var User */
	private $sysop;

	/** @var int */
	private $expiredBlockId = 11111;

	/** @var int */
	private $unexpiredBlockId = 22222;

	/** @var int */
	private $autoblockId = 33333;

	/**
	 * @param array $options
	 * - config: Override the ServiceOptions config
	 * - constructorArgs: Override the constructor arguments
	 * @return DatabaseBlockStore
	 */
	private function getStore( array $options = [] ): DatabaseBlockStore {
		$overrideConfig = $options['config'] ?? [];
		$overrideConstructorArgs = $options['constructorArgs'] ?? [];

		$defaultConfig = [
			'PutIPinRC' => true,
			'BlockDisablesLogin' => false,
			'UpdateRowsPerQuery' => 10,
		];
		$config = array_merge( $defaultConfig, $overrideConfig );

		// This ensures continuation after hooks
		$hookContainer = $this->createMock( HookContainer::class );
		$hookContainer->method( 'run' )
			->willReturn( true );

		// Most tests need read only to be false
		$readOnlyMode = $this->getDummyReadOnlyMode( false );

		$services = $this->getServiceContainer();
		$defaultConstructorArgs = [
			'serviceOptions' => new ServiceOptions(
				DatabaseBlockStore::CONSTRUCTOR_OPTIONS,
				$config
			),
			'logger' => new NullLogger(),
			'actorStoreFactory' => $services->getActorStoreFactory(),
			'blockRestrictionStore' => $services->getBlockRestrictionStore(),
			'commentStore' => $services->getCommentStore(),
			'hookContainer' => $hookContainer,
			'loadBalancer' => $services->getDBLoadBalancer(),
			'readOnlyMode' => $readOnlyMode,
			'userFactory' => $services->getUserFactory(),
		];
		$constructorArgs = array_merge( $defaultConstructorArgs, $overrideConstructorArgs );

		return new DatabaseBlockStore( ...array_values( $constructorArgs ) );
	}

	/**
	 * @param array $options
	 * - target: The intended target, an unblocked user by default
	 * - autoblock: Whether this block is autoblocking
	 * @return DatabaseBlock
	 */
	private function getBlock( array $options = [] ): DatabaseBlock {
		$target = $options['target'] ?? $this->getTestUser()->getUser();
		$autoblock = $options['autoblock'] ?? false;

		return new DatabaseBlock( [
			'by' => $this->sysop,
			'address' => $target,
			'enableAutoblock' => $autoblock,
		] );
	}

	/**
	 * Check that an autoblock corresponds to a parent block. The following are not
	 * required to be equal, so are not tested:
	 * - target
	 * - type
	 * - expiry
	 * - autoblocking
	 *
	 * @param DatabaseBlock $block
	 * @param DatabaseBlock $autoblock
	 */
	private function assertAutoblockEqualsBlock(
		DatabaseBlock $block,
		DatabaseBlock $autoblock
	) {
		$this->assertSame( $autoblock->getParentBlockId(), $block->getId() );
		$this->assertSame( $autoblock->isHardblock(), $block->isHardblock() );
		$this->assertSame( $autoblock->isCreateAccountBlocked(), $block->isCreateAccountBlocked() );
		$this->assertSame( $autoblock->getHideName(), $block->getHideName() );
		$this->assertSame( $autoblock->isEmailBlocked(), $block->isEmailBlocked() );
		$this->assertSame( $autoblock->isUsertalkEditAllowed(), $block->isUsertalkEditAllowed() );
		$this->assertSame( $autoblock->isSitewide(), $block->isSitewide() );

		$restrictionStore = $this->getServiceContainer()->getBlockRestrictionStore();
		$this->assertTrue(
			$restrictionStore->equals(
				$autoblock->getRestrictions(),
				$block->getRestrictions()
			)
		);
	}

	/**
	 * @dataProvider provideInsertBlockSuccess
	 */
	public function testInsertBlockSuccess( $options ) {
		$block = $this->getBlock( $options['block'] ?? [] );
		$block->setRestrictions( [
			new NamespaceRestriction( 0, NS_MAIN ),
		] );

		$store = $this->getStore( $options['store'] ?? [] );
		$result = $store->insertBlock( $block );

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'id', $result );
		$this->assertArrayHasKey( 'autoIds', $result );
		$this->assertCount( 0, $result['autoIds'] );

		$retrievedBlock = DatabaseBlock::newFromID( $result['id'] );
		$this->assertTrue( $block->equals( $retrievedBlock ) );
	}

	public function provideInsertBlockSuccess() {
		return [
			'No conflicting block, not autoblocking' => [
				'block' => [
					'autoblock' => false,
				],
			],
			'No conflicting block, autoblocking but IP not in recent changes' => [
				[
					'block' => [
						'autoblock' => true,
					],
					'store' => [
						'constructorArgs' => [
							'PutIPinRC' => false,
						],
					],
				],
			],
			'No conflicting block, autoblocking but no recent edits' => [
				'block' => [
					'autoblock' => true,
				],
			],
			'Conflicting block, expired' => [
				'block' => [
					// Blocked with expired block in addDBData
					'target' => '1.1.1.1',
				],
			],
		];
	}

	public function testInsertBlockConflict() {
		$block = $this->getBlock( [ 'target' => $this->sysop ] );

		$store = $this->getStore();
		$result = $store->insertBlock( $block );

		$this->assertFalse( $result );
		$this->assertNull( $block->getId() );
	}

	/**
	 * @dataProvider provideInsertBlockLogout
	 */
	public function testInsertBlockLogout( $options, $expectTokenEqual ) {
		$block = $this->getBlock();
		$userFactory = $this->getServiceContainer()->getUserFactory();
		$targetToken = $userFactory->newFromUserIdentity( $block->getTargetUserIdentity() )->getToken();

		$store = $this->getStore( $options );
		$result = $store->insertBlock( $block );

		$this->assertSame(
			$expectTokenEqual,
			$targetToken === $userFactory->newFromUserIdentity( $block->getTargetUserIdentity() )->getToken()
		);
	}

	public function provideInsertBlockLogout() {
		return [
			'Blocked user can log in' => [
				[
					'config' => [
						'BlockDisablesLogin' => false,
					],
				],
				true,
			],
			'Blocked user cannot log in' => [
				[
					'config' => [
						'BlockDisablesLogin' => true,
					],
				],
				false,
			],
		];
	}

	public function testInsertBlockAutoblock() {
		// This is quicker than adding a recent change for an unblocked user.
		// See addDBDataOnce documentation for more details.
		$target = $this->sysop;
		$this->db->delete(
			'ipblocks',
			[ 'ipb_address' => $target->getName() ]
		);
		$block = $this->getBlock( [
			'autoblock' => true,
			'target' => $target,
		] );

		$store = $this->getStore();
		$result = $store->insertBlock( $block );

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'autoIds', $result );
		$this->assertCount( 1, $result['autoIds'] );

		$retrievedBlock = DatabaseBlock::newFromID( $result['autoIds'][0] );
		$this->assertSame( $block->getId(), $retrievedBlock->getParentBlockId() );
		$this->assertAutoblockEqualsBlock( $block, $retrievedBlock );
	}

	public function testInsertBlockError() {
		$block = $this->createMock( DatabaseBlock::class );

		$this->expectException( MWException::class );
		$this->expectExceptionMessage( 'insert' );

		$store = $this->getStore();
		$store->insertBlock( $block );
	}

	public function testUpdateBlock() {
		$existingBlock = DatabaseBlock::newFromTarget( $this->sysop );
		$existingBlock->isUsertalkEditAllowed( true );

		$store = $this->getStore();
		$result = $store->updateBlock( $existingBlock );

		$updatedBlock = DatabaseBlock::newFromID( $result['id'] );
		$autoblock = DatabaseBlock::newFromID( $result['autoIds'][0] );

		$this->assertTrue( $updatedBlock->equals( $existingBlock ) );
		$this->assertAutoblockEqualsBlock( $existingBlock, $autoblock );
	}

	public function testUpdateBlockAddOrRemoveAutoblock() {
		// Existing block is autoblocking to begin with
		$existingBlock = DatabaseBlock::newFromTarget( $this->sysop );
		$existingBlock->isAutoblocking( false );

		$store = $this->getStore();
		$result = $store->updateBlock( $existingBlock );

		$updatedBlock = DatabaseBlock::newFromID( $result['id'] );

		$this->assertTrue( $updatedBlock->equals( $existingBlock ) );
		$this->assertCount( 0, $result['autoIds'] );

		// Test adding an autoblock in the same test run, since we need the
		// target to be the sysop (see addDBDataOnce documentation), and the
		// sysop is blocked with an autoblock between test runs.
		$existingBlock->isAutoblocking( true );
		$result = $store->updateBlock( $existingBlock );

		$updatedBlock = DatabaseBlock::newFromID( $result['id'] );
		$autoblock = DatabaseBlock::newFromID( $result['autoIds'][0] );

		$this->assertTrue( $updatedBlock->equals( $existingBlock ) );
		$this->assertAutoblockEqualsBlock( $existingBlock, $autoblock );
	}

	/**
	 * @dataProvider provideUpdateBlockRestrictions
	 */
	public function testUpdateBlockRestrictions( $expectedCount ) {
		$existingBlock = DatabaseBlock::newFromTarget( $this->sysop );
		$restrictions = [];
		for ( $ns = 0; $ns < $expectedCount; $ns++ ) {
			$restrictions[] = new NamespaceRestriction( $existingBlock->getId(), $ns );
		}
		$existingBlock->setRestrictions( $restrictions );

		$store = $this->getStore();
		$result = $store->updateBlock( $existingBlock );

		$retrievedBlock = DatabaseBlock::newFromID( $result['id'] );
		$this->assertCount(
			$expectedCount,
			$retrievedBlock->getRestrictions()
		);
	}

	public function provideUpdateBlockRestrictions() {
		return [
			'Restrictions deleted if removed' => [ 0 ],
			'Restrictions changed if updated' => [ 2 ],
		];
	}

	public function testDeleteBlockSuccess() {
		$target = $this->sysop;
		$block = DatabaseBlock::newFromTarget( $target );

		$store = $this->getStore();

		$this->assertTrue( $store->deleteBlock( $block ) );
		$this->assertNull( DatabaseBlock::newFromTarget( $target ) );
	}

	public function testDeleteBlockFailureReadOnly() {
		$target = $this->sysop;
		$block = DatabaseBlock::newFromTarget( $target );

		$store = $this->getStore( [
			'constructorArgs' => [
				'readOnlyMode' => $this->getDummyReadOnlyMode( true )
			],
		] );

		$this->assertFalse( $store->deleteBlock( $block ) );
		$this->assertTrue( (bool)DatabaseBlock::newFromTarget( $target ) );
	}

	public function testDeleteBlockFailureNoBlockId() {
		$block = $this->createMock( DatabaseBlock::class );
		$block->method( 'getId' )
			->willReturn( null );
		$block->method( 'getWikiId' )
			->willReturn( DatabaseBlock::LOCAL );

		$this->expectException( MWException::class );
		$this->expectExceptionMessage( 'delete' );

		$store = $this->getStore();
		$store->deleteBlock( $block );
	}

	/**
	 * Check whether expired blocks and restrictions were removed from the database.
	 *
	 * @param int $blockId
	 * @param bool $expected Whether to expect to find any rows
	 */
	private function assertPurgeWorked( int $blockId, bool $expected ): void {
		$blockRows = (bool)$this->db->select(
			'ipblocks',
			'ipb_id',
			[ 'ipb_id' => $blockId ]
		)->numRows();
		$blockRestrictionsRows = (bool)$this->db->select(
			'ipblocks_restrictions',
			'ir_ipb_id',
			[ 'ir_ipb_id' => $blockId ]
		)->numRows();

		$this->assertSame( $expected, $blockRows );
		$this->assertSame( $expected, $blockRestrictionsRows );
	}

	public function testPurgeExpiredBlocksSuccess() {
		$store = $this->getStore();
		$store->purgeExpiredBlocks();

		$this->assertPurgeWorked( $this->expiredBlockId, false );
		$this->assertPurgeWorked( $this->unexpiredBlockId, true );
	}

	public function testPurgeExpiredBlocksFailureReadOnly() {
		$store = $this->getStore( [
			'constructorArgs' => [
				'readOnlyMode' => $this->getDummyReadOnlyMode( true ),
			],
		] );
		$store->purgeExpiredBlocks();

		$this->assertPurgeWorked( $this->expiredBlockId, true );
	}

	/**
	 * In order to autoblock a user, they must have a recent change.
	 *
	 * Make a recent change for the test sysop. This user persists between test runs,
	 * so will always have this recent change.
	 *
	 * Regular test users don't persist between test runs, because the TestUserRegistry
	 * is cleared between runs. If we tested autoblocking on a regular test user, we
	 * would need to make a recent change for each test, which is slow.
	 *
	 * Instead we always test autoblocks on the test sysop.
	 */
	public function addDBDataOnce() {
		$this->editPage(
			'UTPage', // Added in addCoreDBData
			'an edit',
			'a summary',
			NS_MAIN,
			$this->getTestSysop()->getUser()
		);
	}

	/**
	 * Three blocks are added:
	 * - an expired block with restrictions, against an IP
	 * - a current block with restrictions, against a user with recent changes
	 * - a current autoblock from the current block above
	 */
	public function addDBData() {
		$this->sysop = $this->getTestSysop()->getUser();

		// Get a comment ID. One was added in addCoreDBData.
		$commentId = $this->db->select(
			'comment',
			'comment_id'
		)->fetchObject()->comment_id;

		$commonBlockData = [
			'ipb_user' => 0,
			'ipb_by_actor' => $this->sysop->getActorId(),
			'ipb_reason_id' => $commentId,
			'ipb_timestamp' => $this->db->timestamp( '20000101000000' ),
			'ipb_auto' => 0,
			'ipb_anon_only' => 0,
			'ipb_create_account' => 0,
			'ipb_enable_autoblock' => 0,
			'ipb_expiry' => $this->db->getInfinity(),
			'ipb_range_start' => '',
			'ipb_range_end' => '',
			'ipb_deleted' => 0,
			'ipb_block_email' => 0,
			'ipb_allow_usertalk' => 0,
			'ipb_parent_block_id' => 0,
			'ipb_sitewide' => 0,
		];

		$blockData = [
			[
				'ipb_id' => $this->expiredBlockId,
				'ipb_address' => '1.1.1.1',
				'ipb_expiry' => $this->db->timestamp( '20010101000000' ),
			],
			[
				'ipb_id' => $this->unexpiredBlockId,
				'ipb_address' => $this->sysop,
				'ipb_user' => $this->sysop->getId(),
				'ipb_enable_autoblock' => 1,
			],
			[
				'ipb_id' => $this->autoblockId,
				'ipb_address' => '2.2.2.2',
				'ipb_parent_block_id' => $this->unexpiredBlockId,
			],
		];

		$restrictionData = [
			[
				'ir_ipb_id' => $this->expiredBlockId,
				'ir_type' => 1,
				'ir_value' => 1,
			],
			[
				'ir_ipb_id' => $this->unexpiredBlockId,
				'ir_type' => 2,
				'ir_value' => 2,
			],
			[
				'ir_ipb_id' => $this->autoblockId,
				'ir_type' => 2,
				'ir_value' => 2,
			],
		];

		foreach ( $blockData as $row ) {
			$this->db->insert( 'ipblocks', $row + $commonBlockData );
		}

		foreach ( $restrictionData as $row ) {
			$this->db->insert( 'ipblocks_restrictions', $row );
		}

		$this->tablesUsed[] = 'ipblocks';
		$this->tablesUsed[] = 'ipblocks_restrictions';
	}

}
