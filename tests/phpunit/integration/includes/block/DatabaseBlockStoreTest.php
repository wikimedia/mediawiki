<?php

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\User\User;
use Psr\Log\NullLogger;
use Wikimedia\IPUtils;

/**
 * Integration tests for DatabaseBlockStore.
 *
 * @author DannyS712
 * @group Blocking
 * @group Database
 * @covers \MediaWiki\Block\DatabaseBlockStore
 * @coversDefaultClass \MediaWiki\Block\DatabaseBlockStore
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
			'AutoblockExpiry' => 86400,
			'BlockCIDRLimit' => [ 'IPv4' => 16, 'IPv6' => 19 ],
			'BlockDisablesLogin' => false,
			'BlockTargetMigrationStage' => SCHEMA_COMPAT_OLD,
			'PutIPinRC' => true,
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
			'dbProvider' => $services->getDBLoadBalancerFactory(),
			'readOnlyMode' => $readOnlyMode,
			'userFactory' => $services->getUserFactory(),
			'tempUserConfig' => $services->getTempUserConfig(),
			'blockUtils' => $services->getBlockUtils(),
			'autoblockExemptionList' => $services->getAutoblockExemptionList(),
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
		$this->assertSame(
			$autoblock->getReasonComment()->text,
			wfMessage( 'autoblocker', $block->getTargetName(), $block->getReasonComment()->text )->text()
		);

		$restrictionStore = $this->getServiceContainer()->getBlockRestrictionStore();
		$this->assertTrue(
			$restrictionStore->equals(
				$autoblock->getRestrictions(),
				$block->getRestrictions()
			)
		);
	}

	/**
	 * @covers ::newFromID
	 * @covers ::newListFromTarget
	 * @covers ::newFromRow
	 */
	public function testNewFromID_exists() {
		$block = new DatabaseBlock( [
			'address' => '1.2.3.4',
			'by' => $this->getTestSysop()->getUser(),
		] );
		$store = $this->getStore();
		$inserted = $store->insertBlock( $block );
		$this->assertTrue(
			(bool)$inserted['id'],
			'Block inserted correctly'
		);

		$blockId = $inserted['id'];
		$newFromIdRes = $store->newFromID( $blockId );
		$this->assertInstanceOf(
			DatabaseBlock::class,
			$newFromIdRes,
			'Looking up an existing block by id'
		);

		$newListRes = $store->newListFromTarget( "#$blockId" );
		$this->assertCount(
			1,
			$newListRes,
			'newListFromTarget with a block id for an existing block'
		);
		$this->assertInstanceOf(
			DatabaseBlock::class,
			$newListRes[0],
			'DatabaseBlock returned'
		);
		$this->assertSame(
			$blockId,
			$newListRes[0]->getId(),
			'Block returned is the correct one'
		);
	}

	/**
	 * @covers ::newFromID
	 * @covers ::newListFromTarget
	 */
	public function testNewFromID_missing() {
		$store = $this->getStore();
		$missingBlockId = 9998;
		$dbRow = $this->db->newSelectQueryBuilder()
			->select( '*' )
			->from( 'ipblocks' )
			->where( [ 'ipb_id' => $missingBlockId ] )
			->caller( __METHOD__ )
			->fetchRow();
		$this->assertFalse(
			$dbRow,
			"Sanity check: make sure there is no block with id $missingBlockId"
		);

		$newFromIdRes = $store->newFromID( $missingBlockId );
		$this->assertNull(
			$newFromIdRes,
			'Looking up a missing block by id'
		);

		$newListRes = $store->newListFromTarget( "#$missingBlockId" );
		$this->assertCount(
			0,
			$newListRes,
			'newListFromTarget with a block id for a missing block'
		);
	}

	/**
	 * @covers ::getQueryInfo
	 */
	public function testGetQueryInfo() {
		// We don't list all of the fields that should be included, because that just
		// duplicates the function itself. Instead, check the structure and the field
		// aliases. The fact that this query info is everything needed to create a block
		// is validated by its uses within the service
		$queryInfo = $this->getStore()->getQueryInfo( DatabaseBlockStore::SCHEMA_IPBLOCKS );
		$this->assertArrayHasKey( 'tables', $queryInfo );
		$this->assertArrayHasKey( 'fields', $queryInfo );
		$this->assertArrayHasKey( 'joins', $queryInfo );

		$this->assertIsArray( $queryInfo['fields'] );
		$this->assertArrayHasKey( 'ipb_by', $queryInfo['fields'] );
		$this->assertSame( 'ipblocks_actor.actor_user', $queryInfo['fields']['ipb_by'] );
		$this->assertArrayHasKey( 'ipb_by_text', $queryInfo['fields'] );
		$this->assertSame( 'ipblocks_actor.actor_name', $queryInfo['fields']['ipb_by_text'] );
	}

	/**
	 * @covers ::newListFromIPs
	 * @covers ::newFromRow
	 */
	public function testNewListFromIPs() {
		$block = new DatabaseBlock( [
			'address' => '1.2.3.4',
			'by' => $this->getTestSysop()->getUser(),
		] );
		$store = $this->getStore();
		$inserted = $store->insertBlock( $block );
		$this->assertTrue(
			(bool)$inserted['id'],
			'Sanity check: block inserted correctly'
		);

		// Early return of empty array if no ips in the list
		$list = $store->newListFromIPs( [], true );
		$this->assertCount(
			0,
			$list,
			'No matching blocks'
		);

		// Empty array for no match
		$list = $store->newListFromIPs(
			[ '10.1.1.1', '192.168.1.1' ],
			true
		);
		$this->assertCount(
			0,
			$list,
			'No blocks retrieved if all ips are invalid or trusted proxies'
		);

		// Actually fetching, block was inserted above
		$list = $store->newListFromIPs( [ '1.2.3.4' ], true );
		$this->assertCount(
			1,
			$list,
			'Block retrieved for the blocked ip'
		);
		$this->assertInstanceOf(
			DatabaseBlock::class,
			$list[0],
			'Sanity check: DatabaseBlock returned'
		);
		$this->assertSame(
			$inserted['id'],
			$list[0]->getId(),
			'Block returned is the correct one'
		);
	}

	public static function provideGetRangeCond() {
		// $start, $end, $expect
		$hex1 = IPUtils::toHex( '1.2.3.4' );
		$hex2 = IPUtils::toHex( '1.2.3.5' );
		yield 'IPv4 start, same end' => [
			$hex1,
			null,
			"(ipb_range_start  LIKE '0102%' ESCAPE '`')"
			. " AND (ipb_range_start <= '$hex1')"
			. " AND (ipb_range_end >= '$hex1')"
		];
		yield 'IPv4 start, different end' => [
			$hex1,
			$hex2,
			"(ipb_range_start  LIKE '0102%' ESCAPE '`')"
			. " AND (ipb_range_start <= '$hex1')"
			. " AND (ipb_range_end >= '$hex2')"
		];
		$hex3 = IPUtils::toHex( '2000:DEAD:BEEF:A:0:0:0:0' );
		$hex4 = IPUtils::toHex( '2000:DEAD:BEEF:A:0:0:000A:000F' );
		yield 'IPv6 start, same end' => [
			$hex3,
			null,
			"(ipb_range_start  LIKE 'v6-2000%' ESCAPE '`')"
			. " AND (ipb_range_start <= '$hex3')"
			. " AND (ipb_range_end >= '$hex3')"
		];
		yield 'IPv6 start, different end' => [
			$hex3,
			$hex4,
			"(ipb_range_start  LIKE 'v6-2000%' ESCAPE '`')"
			. " AND (ipb_range_start <= '$hex3')"
			. " AND (ipb_range_end >= '$hex4')"
		];
	}

	/**
	 * @dataProvider provideGetRangeCond
	 * @covers ::getRangeCond
	 * @covers ::getIpFragment
	 */
	public function testGetRangeCond( $start, $end, $expect ) {
		$this->assertSame(
			$expect,
			$this->getStore()->getRangeCond( $start, $end, DatabaseBlockStore::SCHEMA_IPBLOCKS ) );
	}

	public static function provideGetRangeCondIntegrated() {
		return [
			'single IP block' => [ '3.3.3.3', '3.3.3.3', true ],
			'/32 range blocks single IP' => [ '3.3.3.3/32', '3.3.3.3', true ],
			'single IP block mismatch' => [ '3.3.3.3', '3.3.3.4', false ],
			'/32 range mismatch' => [ '3.3.3.3/32', '3.3.3.4', false ],
			'/24 match' => [ '3.3.3.0/24', '3.3.3.0', true ],
			'/24 mismatch' => [ '3.3.3.0/24', '3.3.4.0', false ],
			'range search exact match' => [ '3.3.3.0/24', '3.3.3.0/24', true ],
			'encompassing range match' => [ '3.3.3.0/24', '3.3.3.1/27', true ],
			'excessive range mismatch' => [ '3.3.0.0/24', '3.3.0.0/22', false ],
		];
	}

	/**
	 * Test getRangeCond() by inserting blocks and checking for matches
	 *
	 * @dataProvider provideGetRangeCondIntegrated
	 * @param string $blockTarget
	 * @param string $searchTarget
	 * @param bool $isBlocked
	 */
	public function testGetRangeCondIntegrated( $blockTarget, $searchTarget, $isBlocked ) {
		$store = $this->getStore();
		$store->insertBlock( $this->getBlock( [ 'target' => $blockTarget ] ) );
		[ $start, $end ] = IPUtils::parseRange( $searchTarget );
		$rows = $this->db->newSelectQueryBuilder()
			->queryInfo( $store->getQueryInfo( DatabaseBlockStore::SCHEMA_IPBLOCKS ) )
			->where( $store->getRangeCond( $start, $end, DatabaseBlockStore::SCHEMA_CURRENT ) )
			->fetchResultSet();
		$this->assertSame( $isBlocked ? 1 : 0, $rows->numRows() );
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

		$retrievedBlock = $store->newFromID( $result['id'] );
		$this->assertTrue( $block->equals( $retrievedBlock ) );
	}

	public static function provideInsertBlockSuccess() {
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
		$store->insertBlock( $block );

		$this->assertSame(
			$expectTokenEqual,
			$targetToken === $userFactory->newFromUserIdentity( $block->getTargetUserIdentity() )->getToken()
		);
	}

	public static function provideInsertBlockLogout() {
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
		$this->db->newDeleteQueryBuilder()
			->deleteFrom( 'ipblocks' )
			->where( [ 'ipb_address' => $target->getName() ] )
			->caller( __METHOD__ )
			->execute();
		$block = $this->getBlock( [
			'autoblock' => true,
			'target' => $target,
		] );

		$store = $this->getStore();
		$result = $store->insertBlock( $block );

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'autoIds', $result );
		$this->assertCount( 1, $result['autoIds'] );

		$retrievedBlock = $store->newFromID( $result['autoIds'][0] );
		$this->assertSame( $block->getId(), $retrievedBlock->getParentBlockId() );
		$this->assertAutoblockEqualsBlock( $block, $retrievedBlock );
	}

	public function testInsertBlockError() {
		$block = $this->createMock( DatabaseBlock::class );

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'insert' );

		$store = $this->getStore();
		$store->insertBlock( $block );
	}

	public function testUpdateBlock() {
		$store = $this->getStore();
		$existingBlock = $store->newFromTarget( $this->sysop );

		// Insert an autoblock for T351173 regression testing
		$autoblockId = $store->doAutoblock( $existingBlock, '127.0.0.1' );

		// Modify a block option
		$existingBlock->isUsertalkEditAllowed( true );
		$newExpiry = wfTimestamp( TS_MW, time() + 1000 );
		$existingBlock->setExpiry( $newExpiry );

		$result = $store->updateBlock( $existingBlock );

		$updatedBlock = $store->newFromID( $result['id'] );
		$autoblock = $store->newFromID( $autoblockId );

		$this->assertTrue( $updatedBlock->equals( $existingBlock ) );
		$this->assertAutoblockEqualsBlock( $existingBlock, $autoblock );
		$this->assertLessThanOrEqual( $newExpiry, $autoblock->getExpiry() );
	}

	public function testUpdateBlockAddOrRemoveAutoblock() {
		$store = $this->getStore();
		// Existing block is autoblocking to begin with
		$existingBlock = $store->newFromTarget( $this->sysop );
		$existingBlock->isAutoblocking( false );

		$result = $store->updateBlock( $existingBlock );

		$updatedBlock = $store->newFromID( $result['id'] );

		$this->assertTrue( $updatedBlock->equals( $existingBlock ) );
		$this->assertCount( 0, $result['autoIds'] );

		// Test adding an autoblock in the same test run, since we need the
		// target to be the sysop (see addDBDataOnce documentation), and the
		// sysop is blocked with an autoblock between test runs.
		$existingBlock->isAutoblocking( true );
		$result = $store->updateBlock( $existingBlock );

		$updatedBlock = $store->newFromID( $result['id'] );
		$autoblock = $store->newFromID( $result['autoIds'][0] );

		$this->assertTrue( $updatedBlock->equals( $existingBlock ) );
		$this->assertAutoblockEqualsBlock( $existingBlock, $autoblock );
	}

	/**
	 * @dataProvider provideUpdateBlockRestrictions
	 */
	public function testUpdateBlockRestrictions( $expectedCount ) {
		$store = $this->getStore();
		$existingBlock = $store->newFromTarget( $this->sysop );
		$restrictions = [];
		for ( $ns = 0; $ns < $expectedCount; $ns++ ) {
			$restrictions[] = new NamespaceRestriction( $existingBlock->getId(), $ns );
		}
		$existingBlock->setRestrictions( $restrictions );

		$result = $store->updateBlock( $existingBlock );

		$retrievedBlock = $store->newFromID( $result['id'] );
		$this->assertCount(
			$expectedCount,
			$retrievedBlock->getRestrictions()
		);
	}

	public static function provideUpdateBlockRestrictions() {
		return [
			'Restrictions deleted if removed' => [ 0 ],
			'Restrictions changed if updated' => [ 2 ],
		];
	}

	public function testDeleteBlockSuccess() {
		$store = $this->getStore();
		$target = $this->sysop;
		$block = $store->newFromTarget( $target );

		$this->assertTrue( $store->deleteBlock( $block ) );
		$this->assertNull( $store->newFromTarget( $target ) );
	}

	public function testDeleteBlockFailureReadOnly() {
		$store = $this->getStore( [
			'constructorArgs' => [
				'readOnlyMode' => $this->getDummyReadOnlyMode( true )
			],
		] );
		$target = $this->sysop;
		$block = $store->newFromTarget( $target );

		$this->assertFalse( $store->deleteBlock( $block ) );
		$this->assertTrue( (bool)$store->newFromTarget( $target ) );
	}

	public function testDeleteBlockFailureNoBlockId() {
		$block = $this->createMock( DatabaseBlock::class );
		$block->method( 'getId' )
			->willReturn( null );
		$block->method( 'getWikiId' )
			->willReturn( DatabaseBlock::LOCAL );

		$this->expectException( InvalidArgumentException::class );
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
		$blockRows = (bool)$this->db->newSelectQueryBuilder()
			->select( 'ipb_id' )
			->from( 'ipblocks' )
			->where( [ 'ipb_id' => $blockId ] )
			->fetchResultSet()->numRows();
		$blockRestrictionsRows = (bool)$this->db->newSelectQueryBuilder()
			->select( 'ir_ipb_id' )
			->from( 'ipblocks_restrictions' )
			->where( [ 'ir_ipb_id' => $blockId ] )
			->fetchResultSet()->numRows();

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
			'DatabaseBlockStoreTest test page',
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

		// Get a comment ID. One was added in addDBDataOnce.
		$commentId = $this->db->newSelectQueryBuilder()
			->select( 'comment_id' )
			->from( 'comment' )
			->caller( __METHOD__ )
			->fetchField();

		$commonBlockData = [
			'ipb_by_actor' => $this->sysop->getActorId(),
			'ipb_reason_id' => $commentId,
			'ipb_timestamp' => $this->db->timestamp( '20000101000000' ),
			'ipb_auto' => 0,
			'ipb_anon_only' => 0,
			'ipb_create_account' => 0,
			'ipb_range_start' => '',
			'ipb_range_end' => '',
			'ipb_deleted' => 0,
			'ipb_block_email' => 0,
			'ipb_allow_usertalk' => 0,
			'ipb_sitewide' => 0,
		];

		$blockData = [
			[
				'ipb_id' => $this->expiredBlockId,
				'ipb_address' => '1.1.1.1',
				'ipb_expiry' => $this->db->timestamp( '20010101000000' ),
				'ipb_user' => 0,
				'ipb_enable_autoblock' => 0,
				'ipb_parent_block_id' => 0,
			] + $commonBlockData,
			[
				'ipb_id' => $this->unexpiredBlockId,
				'ipb_address' => $this->sysop,
				'ipb_expiry' => $this->db->getInfinity(),
				'ipb_user' => $this->sysop->getId(),
				'ipb_enable_autoblock' => 1,
				'ipb_parent_block_id' => 0,
			] + $commonBlockData,
			[
				'ipb_id' => $this->autoblockId,
				'ipb_address' => '2.2.2.2',
				'ipb_expiry' => $this->db->getInfinity(),
				'ipb_user' => 0,
				'ipb_enable_autoblock' => 0,
				'ipb_parent_block_id' => $this->unexpiredBlockId,
			] + $commonBlockData,
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

		$this->db->newInsertQueryBuilder()
			->insertInto( 'ipblocks' )
			->rows( $blockData )
			->caller( __METHOD__ )
			->execute();

		$this->db->newInsertQueryBuilder()
			->insertInto( 'ipblocks_restrictions' )
			->rows( $restrictionData )
			->caller( __METHOD__ )
			->execute();
	}

}
