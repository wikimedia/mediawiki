<?php

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\User;
use Psr\Log\NullLogger;
use Wikimedia\IPUtils;
use Wikimedia\ScopedCallback;

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
	use TempUserTestTrait;

	private User $sysop;
	private int $expiredBlockId = 11111;
	private int $unexpiredBlockId = 22222;
	private int $autoblockId = 33333;

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
			MainConfigNames::AutoblockExpiry => 86400,
			MainConfigNames::BlockCIDRLimit => [ 'IPv4' => 16, 'IPv6' => 19 ],
			MainConfigNames::BlockDisablesLogin => false,
			MainConfigNames::PutIPinRC => true,
			MainConfigNames::UpdateRowsPerQuery => 10,
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
			'blockTargetFactory' => $services->getBlockTargetFactory(),
			'autoblockExemptionList' => $services->getAutoblockExemptionList(),
			'sessionManager' => $services->getSessionManager()
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
		$targetFactory = $this->getServiceContainer()->getBlockTargetFactory();
		if ( isset( $options['target'] ) ) {
			if ( $options['target'] instanceof User ) {
				$target = $targetFactory->newFromUser( $options['target'] );
			} else {
				$target = $targetFactory->newFromString( $options['target'] );
			}
		} else {
			$target = $targetFactory->newUserBlockTarget( $this->getTestUser()->getUser() );
		}
		$autoblock = $options['autoblock'] ?? false;

		return new DatabaseBlock( [
			'by' => $this->sysop,
			'target' => $target,
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
		$store = $this->getStore();
		$block = $store->insertBlockWithParams( [
			'address' => '1.2.3.4',
			'by' => $this->getTestSysop()->getUser(),
		] );

		$blockId = $block->getId();
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
		$dbRow = $this->getDb()->newSelectQueryBuilder()
			->select( '*' )
			->from( 'block' )
			->where( [ 'bl_id' => $missingBlockId ] )
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
	 * @covers ::newFromRow
	 */
	public function testNewFromRow() {
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$block = $blockStore->insertBlockWithParams( [
			'targetUser' => $badActor,
			'by' => $sysop,
			'expiry' => 'infinity',
		] );

		$blockQuery = $blockStore->getQueryInfo();
		$db = $this->getDb();
		$row = $db->newSelectQueryBuilder()
			->queryInfo( $blockQuery )
			->where( [
				'bl_id' => $block->getId(),
			] )
			->caller( __METHOD__ )
			->fetchRow();

		$block = $blockStore->newFromRow( $db, $row );
		$this->assertInstanceOf( DatabaseBlock::class, $block );
		$this->assertEquals( $block->getBy(), $sysop->getId() );
		$this->assertEquals( $block->getTargetName(), $badActor->getName() );
		$this->assertEquals( $block->getTargetName(), $badActor->getName() );
		$this->assertTrue( $block->isBlocking( $badActor ), 'Is blocking expected user' );
		$this->assertEquals( $block->getTargetUserIdentity()->getId(), $badActor->getId() );
	}

	/**
	 * @covers ::getQueryInfo
	 */
	public function testGetQueryInfo() {
		// We don't list all of the fields that should be included, because that just
		// duplicates the function itself. Instead, check the structure and the field
		// aliases. The fact that this query info is everything needed to create a block
		// is validated by its uses within the service
		$queryInfo = $this->getStore()->getQueryInfo();
		$this->assertArrayHasKey( 'tables', $queryInfo );
		$this->assertArrayHasKey( 'fields', $queryInfo );
		$this->assertArrayHasKey( 'joins', $queryInfo );

		$this->assertIsArray( $queryInfo['fields'] );
		$this->assertArrayHasKey( 'bl_by', $queryInfo['fields'] );
		$this->assertSame( 'block_by_actor.actor_user', $queryInfo['fields']['bl_by'] );
		$this->assertArrayHasKey( 'bl_by_text', $queryInfo['fields'] );
		$this->assertSame( 'block_by_actor.actor_name', $queryInfo['fields']['bl_by_text'] );
	}

	/**
	 * @covers ::newListFromIPs
	 * @covers ::newFromRow
	 */
	public function testNewListFromIPs() {
		$store = $this->getStore();
		$inserted = $store->insertBlockWithParams( [
			'address' => '1.2.3.4',
			'by' => $this->getTestSysop()->getUser(),
		] );

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
			$inserted->getId(),
			$list[0]->getId(),
			'Block returned is the correct one'
		);
	}

	/**
	 * @covers ::newListFromTarget
	 * @covers ::newLoad
	 */
	public function testNewListFromTargetAuto() {
		// Set up once and do multiple tests, faster than a provider
		$store = $this->getStore();
		$ip = '1.2.3.4';
		$inserted = $store->insertBlockWithParams( [
			'address' => $ip,
			'by' => $this->getTestSysop()->getUser(),
			'auto' => true,
		] );

		// ALL
		$list = $store->newListFromTarget( $ip,
			null, false, DatabaseBlockStore::AUTO_ALL );
		$this->assertCount( 1, $list );

		// Specified with IP
		$list = $store->newListFromTarget( $ip,
			null, false, DatabaseBlockStore::AUTO_SPECIFIED );
		$this->assertCount( 0, $list );

		// Specified autoblock ID
		$list = $store->newListFromTarget( "#{$inserted->getId()}",
			null, false, DatabaseBlockStore::AUTO_SPECIFIED );
		$this->assertCount( 1, $list );

		// None with IP
		$list = $store->newListFromTarget( $ip,
			null, false, DatabaseBlockStore::AUTO_NONE );
		$this->assertCount( 0, $list );

		// None with autoblock ID
		$list = $store->newListFromTarget( "#{$inserted->getId()}",
			null, false, DatabaseBlockStore::AUTO_NONE );
		$this->assertCount( 0, $list );
	}

	public static function provideGetConditionForRanges() {
		$hex1 = IPUtils::toHex( '1.2.3.4' );
		$hex2 = IPUtils::toHex( '1.2.3.5' );
		$hex3 = IPUtils::toHex( '1.2.3.6' );
		$hex4 = IPUtils::toHex( '1.2.3.7' );
		yield 'Both same end' => [
			[ [ $hex1, null ], [ $hex2, null ] ],
			[
				"(bt_range_start LIKE '0102%' ESCAPE '`'"
					. " AND bt_range_start <= '$hex1'"
					. " AND bt_range_end >= '$hex1')",
				"(bt_range_start LIKE '0102%' ESCAPE '`'"
					. " AND bt_range_start <= '$hex2'"
					. " AND bt_range_end >= '$hex2')",
				"(bt_ip_hex IN ('01020304','01020305') AND bt_range_start IS NULL)"
			],
		];
		yield 'Both different end' => [
			[ [ $hex1, $hex2 ], [ $hex3, $hex4 ] ],
			[
				"(bt_range_start LIKE '0102%' ESCAPE '`'"
					. " AND bt_range_start <= '$hex1'"
					. " AND bt_range_end >= '$hex2')",
				"(bt_range_start LIKE '0102%' ESCAPE '`'"
					. " AND bt_range_start <= '$hex3'"
					. " AND bt_range_end >= '$hex4')",
			],
		];
		yield 'First same end, second different end' => [
			[ [ $hex1, null ], [ $hex3, $hex4 ] ],
			[
				"(bt_range_start LIKE '0102%' ESCAPE '`'"
					. " AND bt_range_start <= '$hex1'"
					. " AND bt_range_end >= '$hex1')",
				"(bt_range_start LIKE '0102%' ESCAPE '`'"
					. " AND bt_range_start <= '$hex3'"
					. " AND bt_range_end >= '$hex4')",
				"(bt_ip_hex = '$hex1' AND bt_range_start IS NULL)",
			],
		];
		yield 'First different end, second same end' => [
			[ [ $hex1, $hex2 ], [ $hex3, null ] ],
			[
				"(bt_range_start LIKE '0102%' ESCAPE '`'"
					. " AND bt_range_start <= '$hex1'"
					. " AND bt_range_end >= '$hex2')",
				"(bt_range_start LIKE '0102%' ESCAPE '`'"
					. " AND bt_range_start <= '$hex3'"
					. " AND bt_range_end >= '$hex3')",
				"(bt_ip_hex = '$hex3' AND bt_range_start IS NULL)",
			],
		];
	}

	/**
	 * @dataProvider provideGetConditionForRanges
	 * @covers ::getConditionForRanges
	 * @covers ::getIpFragment
	 */
	public function testGetConditionForRanges( array $ranges, array $expect ) {
		$this->assertSame(
			$expect,
			$this->getStore()->getConditionForRanges( $ranges )
		);
	}

	public static function provideGetRangeCond() {
		// $start, $end, $expect
		$hex1 = IPUtils::toHex( '1.2.3.4' );
		$hex2 = IPUtils::toHex( '1.2.3.5' );
		yield 'IPv4 start, same end' => [
			$hex1,
			null,
			"((bt_range_start LIKE '0102%' ESCAPE '`'"
			. " AND bt_range_start <= '$hex1'"
			. " AND bt_range_end >= '$hex1'))"
			. " OR ((bt_ip_hex = '$hex1' AND bt_range_start IS NULL))"
		];
		yield 'IPv4 start, different end' => [
			$hex1,
			$hex2,
			"((bt_range_start LIKE '0102%' ESCAPE '`'"
			. " AND bt_range_start <= '$hex1'"
			. " AND bt_range_end >= '$hex2'))"
		];
		$hex3 = IPUtils::toHex( '2000:DEAD:BEEF:A:0:0:0:0' );
		$hex4 = IPUtils::toHex( '2000:DEAD:BEEF:A:0:0:000A:000F' );
		yield 'IPv6 start, same end' => [
			$hex3,
			null,
			"((bt_range_start LIKE 'v6-2000%' ESCAPE '`'"
			. " AND bt_range_start <= '$hex3'"
			. " AND bt_range_end >= '$hex3'))"
			. " OR ((bt_ip_hex = '$hex3' AND bt_range_start IS NULL))"
		];
		yield 'IPv6 start, different end' => [
			$hex3,
			$hex4,
			"((bt_range_start LIKE 'v6-2000%' ESCAPE '`'"
			. " AND bt_range_start <= '$hex3'"
			. " AND bt_range_end >= '$hex4'))"
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
			$this->getStore()->getRangeCond( $start, $end ) );
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
		$rows = $this->getDb()->newSelectQueryBuilder()
			->queryInfo( $store->getQueryInfo() )
			->where( $store->getRangeCond( $start, $end ) )
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
						'constructorArgs' => [ MainConfigNames::PutIPinRC => false ],
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
					'config' => [ MainConfigNames::BlockDisablesLogin => false ],
				],
				true,
			],
			'Blocked user cannot log in' => [
				[
					'config' => [ MainConfigNames::BlockDisablesLogin => true ],
				],
				false,
			],
		];
	}

	public function testInsertBlockAutoblock() {
		// This is quicker than adding a recent change for an unblocked user.
		// See addDBDataOnce documentation for more details.
		$target = $this->sysop;
		$store = $this->getStore();
		$store->deleteBlocksMatchingConds( [ 'bt_user' => $target->getId() ] );
		$block = $this->getBlock( [
			'autoblock' => true,
			'target' => $target,
		] );

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

	public function testInsertExistingBlock() {
		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$page = $this->getExistingTestPage( 'Foo' );
		$restriction = new PageRestriction( 0, $page->getId() );
		$block = $blockStore->insertBlockWithParams( [
			'targetUser' => $badActor,
			'by' => $sysop,
			'expiry' => 'infinity',
			'restrictions' => [ $restriction ],
		] );

		// Insert the block again, which should result in a failure
		$result = $blockStore->insertBlock( $block );

		$this->assertFalse( $result );

		// Ensure that there are no restrictions where the blockId is 0.
		$count = $this->getDb()->newSelectQueryBuilder()
			->select( '*' )
			->from( 'ipblocks_restrictions' )
			->where( [ 'ir_ipb_id' => 0 ] )
			->caller( __METHOD__ )->fetchRowCount();
		$this->assertSame( 0, $count );
	}

	public function testUpdateBlock() {
		$store = $this->getStore();
		$existingBlock = $store->newFromTarget( $this->sysop );
		$existingBlockTimestamp = $existingBlock->getTimestamp();

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
		// Check that the timestamp was updated
		$this->assertGreaterThan( $existingBlockTimestamp, $updatedBlock->getTimestamp() );
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
		$blockRows = (bool)$this->getDb()->newSelectQueryBuilder()
			->select( 'bl_id' )
			->from( 'block' )
			->where( [ 'bl_id' => $blockId ] )
			->fetchResultSet()->numRows();
		$blockRestrictionsRows = (bool)$this->getDb()->newSelectQueryBuilder()
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
	 * Regression test for T382881
	 */
	public function testPurgeExpiredBlocksMulti() {
		// Make deferred updates be actually deferred
		$scope = DeferredUpdates::preventOpportunisticUpdates();

		// Add a second block
		$store = $this->getStore();
		$store->insertBlockWithParams( [
			'address' => '1.1.1.1',
			'expiry' => '2001-01-01T00:00:00',
			'reason' => 'additional expired block',
			'by' => $this->sysop,
			'expectedTargetCount' => 1,
		] );

		// Check that there are really two blocks on that user now
		$this->newSelectQueryBuilder()
			->select( 'bt_count' )
			->from( 'block_target' )
			->where( [ 'bt_address' => '1.1.1.1' ] )
			->caller( __METHOD__ )
			->assertFieldValue( '2' );

		// Run deferred updates, purging them both
		ScopedCallback::consume( $scope );
		$this->runDeferredUpdates();

		// Now the block_target row should be gone
		$this->newSelectQueryBuilder()
			->select( 'bt_count' )
			->from( 'block_target' )
			->where( [ 'bt_address' => '1.1.1.1' ] )
			->caller( __METHOD__ )
			->assertEmptyResult();
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
		$commentId = $this->getDb()->newSelectQueryBuilder()
			->select( 'comment_id' )
			->from( 'comment' )
			->caller( __METHOD__ )
			->fetchField();

		$commonBlockData = [
			'bl_by_actor' => $this->sysop->getActorId(),
			'bl_reason_id' => $commentId,
			'bl_timestamp' => $this->getDb()->timestamp( '20000101000000' ),
			'bl_anon_only' => 0,
			'bl_create_account' => 0,
			'bl_deleted' => 0,
			'bl_block_email' => 0,
			'bl_allow_usertalk' => 0,
			'bl_sitewide' => 0,
		];

		$targetRows = [
			'1.1.1.1' => [
				'bt_address' => '1.1.1.1',
				'bt_ip_hex' => IPUtils::toHex( '1.1.1.1' ),
				'bt_auto' => 0,
			],
			'sysop' => [
				'bt_user' => $this->sysop->getId(),
				'bt_user_text' => $this->sysop->getName(),
				'bt_auto' => 0,
			],
			'2.2.2.2' => [
				'bt_address' => '2.2.2.2',
				'bt_ip_hex' => IPUtils::toHex( '2.2.2.2' ),
				'bt_auto' => 1,
			]
		];
		$targetIds = [];
		foreach ( $targetRows as $i => $row ) {
			$this->getDb()->newInsertQueryBuilder()
				->insertInto( 'block_target' )
				->row( $row + [ 'bt_count' => 1 ] )
				->execute();
			$targetIds[$i] = $this->getDb()->insertId();
		}

		$blockData = [
			[
				'bl_id' => $this->expiredBlockId,
				'bl_target' => $targetIds['1.1.1.1'],
				'bl_expiry' => $this->getDb()->timestamp( '20010101000000' ),
				'bl_enable_autoblock' => 0,
				'bl_parent_block_id' => 0,
			] + $commonBlockData,
			[
				'bl_id' => $this->unexpiredBlockId,
				'bl_target' => $targetIds['sysop'],
				'bl_expiry' => $this->getDb()->getInfinity(),
				'bl_enable_autoblock' => 1,
				'bl_parent_block_id' => 0,
			] + $commonBlockData,
			[
				'bl_id' => $this->autoblockId,
				'bl_target' => $targetIds['2.2.2.2'],
				'bl_expiry' => $this->getDb()->getInfinity(),
				'bl_enable_autoblock' => 0,
				'bl_parent_block_id' => $this->unexpiredBlockId,
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

		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'block' )
			->rows( $blockData )
			->caller( __METHOD__ )
			->execute();

		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'ipblocks_restrictions' )
			->rows( $restrictionData )
			->caller( __METHOD__ )
			->execute();
	}

	public function testHardBlocks() {
		// Set up temp user config
		$this->enableAutoCreateTempUser();

		$targetFactory = $this->getServiceContainer()->getBlockTargetFactory();
		$store = $this->getStore();
		$blocker = $this->getTestUser()->getUser();

		$block = new DatabaseBlock();
		$block->setTarget( $targetFactory->newFromString( '1.2.3.4' ) );
		$block->setBlocker( $blocker );
		$block->setReason( 'test' );
		$block->setExpiry( 'infinity' );
		$block->isHardblock( false );
		$store->insertBlock( $block );

		$this->assertFalse(
			(bool)$store->newFromTarget( '~1' ),
			'Temporary user is not blocked directly'
		);
		$this->assertTrue(
			(bool)$store->newFromTarget( '~1', '1.2.3.4' ),
			'Temporary user is blocked by soft block'
		);
		$this->assertFalse(
			(bool)$store->newFromTarget( $blocker, '1.2.3.4' ),
			'Logged-in user is not blocked by soft block'
		);
	}

	/**
	 * Regression test for T31116 which relates to CheckUser asking for blocks with an
	 * empty string for a vague target.
	 *
	 * @dataProvider provideT31116Data
	 */
	public function testT31116NewFromTargetWithEmptyIp( $vagueTarget ) {
		$store = $this->getStore();
		$target = $this->getTestUser()->getUser();
		$initialBlock = $this->getBlock( [ 'target' => $target ] );
		$store->insertBlock( $initialBlock );
		$block = $this->getStore()->newFromTarget( $target->getName(), $vagueTarget );

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

	public static function provideNewFromTargetRangeBlocks() {
		return [
			'Blocks to IPv4 ranges' => [
				[ '0.0.0.0/20', '0.0.0.0/30', '0.0.0.0/25' ],
				'0.0.0.0',
				'0.0.0.0/30'
			],
			'Blocks to IPv6 ranges' => [
				[ '0:0:0:0:0:0:0:0/20', '0:0:0:0:0:0:0:0/30', '0:0:0:0:0:0:0:0/25' ],
				'0:0:0:0:0:0:0:0',
				'0:0:0:0:0:0:0:0/30'
			],
			'Blocks to wide IPv4 range and IP' => [
				[ '0.0.0.0/16', '0.0.0.0' ],
				'0.0.0.0',
				'0.0.0.0'
			],
			'Blocks to narrow IPv4 range and IP' => [
				[ '0.0.0.0/31', '0.0.0.0' ],
				'0.0.0.0',
				'0.0.0.0'
			],
			'Blocks to wide IPv6 range and IP' => [
				[ '0:0:0:0:0:0:0:0/19', '0:0:0:0:0:0:0:0' ],
				'0:0:0:0:0:0:0:0',
				'0:0:0:0:0:0:0:0'
			],
			'Blocks to narrow IPv6 range and IP' => [
				[ '0:0:0:0:0:0:0:0/127', '0:0:0:0:0:0:0:0' ],
				'0:0:0:0:0:0:0:0',
				'0:0:0:0:0:0:0:0'
			],
			'Blocks to wide IPv6 range and IP, large numbers' => [
				[ '2000:DEAD:BEEF:A:0:0:0:0/19', '2000:DEAD:BEEF:A:0:0:0:0' ],
				'2000:DEAD:BEEF:A:0:0:0:0',
				'2000:DEAD:BEEF:A:0:0:0:0'
			],
			'Blocks to narrow IPv6 range and IP, large numbers' => [
				[ '2000:DEAD:BEEF:A:0:0:0:0/127', '2000:DEAD:BEEF:A:0:0:0:0' ],
				'2000:DEAD:BEEF:A:0:0:0:0',
				'2000:DEAD:BEEF:A:0:0:0:0'
			],
		];
	}

	/**
	 * @dataProvider provideNewFromTargetRangeBlocks
	 */
	public function testNewFromTargetRangeBlocks( $targets, $ip, $expectedTarget ) {
		$blockStore = $this->getStore();
		$targetFactory = $this->getServiceContainer()->getBlockTargetFactory();
		$blocker = $this->getTestSysop()->getUser();

		foreach ( $targets as $target ) {
			$block = new DatabaseBlock();
			$block->setTarget( $targetFactory->newFromString( $target ) );
			$block->setBlocker( $blocker );
			$blockStore->insertBlock( $block );
		}

		// Should find the block with the narrowest range
		$block = $blockStore->newFromTarget( $this->getTestUser()->getUserIdentity(), $ip );
		$this->assertSame(
			$expectedTarget,
			$block->getTargetName()
		);
	}

}
