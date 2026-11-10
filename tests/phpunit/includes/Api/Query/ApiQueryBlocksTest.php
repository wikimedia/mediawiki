<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Block\BlockActionInfo;
use MediaWiki\Block\Restriction\ActionRestriction;
use MediaWiki\Block\Restriction\CreatePageNamespaceRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use Wikimedia\Timestamp\TimestampFormat as TS;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiQueryBlocks
 */
class ApiQueryBlocksTest extends ApiTestCase {
	use MockAuthorityTrait;

	public function testExecute() {
		[ $data ] = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'blocks',
		] );
		$this->assertEquals( [ 'batchcomplete' => true, 'query' => [ 'blocks' => [] ] ], $data );
	}

	public function testExecuteBlock() {
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$time = time();
		$ts = wfTimestamp( TS::MW, $time );
		$expiry = wfTimestamp( TS::MW, $time + 24 * 60 * 60 );
		$block = $this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [
				'targetUser' => $badActor,
				'by' => $sysop,
				'timestamp' => $ts,
				'expiry' => $expiry,
			] );

		[ $data ] = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'blocks',
		] );
		$this->assertArrayHasKey( 'query', $data );
		$this->assertArrayHasKey( 'blocks', $data['query'] );
		$this->assertCount( 1, $data['query']['blocks'] );
		$subset = [
			'id' => $block->getId(),
			'user' => $badActor->getName(),
			'expiry' => wfTimestamp( TS::ISO_8601, $expiry ),
			'duration-l10n' => '1 day',
		];
		$this->assertArrayContains( $subset, $data['query']['blocks'][0] );
	}

	public function testExecuteSitewide() {
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$block = $this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [
				'targetUser' => $badActor,
				'by' => $sysop,
			] );

		[ $data ] = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'blocks',
		] );
		$this->assertArrayHasKey( 'query', $data );
		$this->assertArrayHasKey( 'blocks', $data['query'] );
		$this->assertCount( 1, $data['query']['blocks'] );
		$subset = [
			'id' => $block->getId(),
			'user' => $badActor->getName(),
			'expiry' => $block->getExpiry(),
			'partial' => !$block->isSitewide(),
		];
		$this->assertArrayContains( $subset, $data['query']['blocks'][0] );
	}

	public function testHiddenBlocksVisibility() {
		// Ensure that hidden blocks are not shown by default.
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$parentBlock = $this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [
				'targetUser' => $badActor,
				'by' => $sysop,
				'hideName' => true,
				'enableAutoblock' => true,
			] );

		$this->getServiceContainer()->getDatabaseBlockStore()
			->doAutoblock( $parentBlock, '1.2.3.4' );

		[ $data ] = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'blocks',
		] );
		$this->assertCount( 0, $data['query']['blocks'] );

		[ $data ] = $this->doApiRequestWithToken( [
			'action' => 'query',
			'list' => 'blocks',
		], null, $this->mockRegisteredAuthorityWithPermissions( [ 'hideuser' ] ) );

		$this->assertCount( 2, $data['query']['blocks'] );
	}

	public static function provideHiddenUserOtherBlocksVisibility(): array {
		return [
			'No rights' => [
				'rights' => [],
				'expectedCount' => 0,
			],
			'deferredblock' => [
				'rights' => [ 'deferredblock' ],
				'expectedCount' => 0,
			],
			'hideuser' => [
				'rights' => [ 'hideuser' ],
				'expectedCount' => 2,
			],
		];
	}

	/**
	 * T391343 regression test
	 * @dataProvider provideHiddenUserOtherBlocksVisibility
	 */
	public function testHiddenUserOtherBlocksVisibility( array $rights, int $expectedCount ): void {
		$user = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();
		$store = $this->getServiceContainer()->getDatabaseBlockStore();
		$store->insertBlockWithParams( [
			'targetUser' => $user,
			'by' => $sysop,
		] );
		$store->insertBlockWithParams( [
			'targetUser' => $user,
			'by' => $sysop,
			'hideName' => true,
		] );

		[ $data ] = $this->doApiRequest(
			[ 'action' => 'query', 'list' => 'blocks', 'bkprop' => 'id|user' ],
			null,
			false,
			$this->mockRegisteredAuthorityWithPermissions( $rights )
		);
		$this->assertCount( $expectedCount, $data['query']['blocks'] );
	}

	public function testDeferredBlocksVisibility(): void {
		$this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [
				'targetUser' => $this->getTestUser()->getUser(),
				'by' => $this->getTestSysop()->getUser(),
				'deferred' => true,
			] );
		$params = [
			'action' => 'query',
			'list' => 'blocks',
			'bkprop' => 'id|flags',
		];

		[ $data ] = $this->doApiRequest(
			$params, null, false, $this->mockRegisteredAuthorityWithoutPermissions( [ 'hideuser', 'deferredblock' ] )
		);
		$this->assertCount( 0, $data['query']['blocks'] );

		[ $data ] = $this->doApiRequest(
			$params, null, false, $this->mockRegisteredAuthorityWithPermissions( [ 'hideuser' ] )
		);
		$this->assertCount( 0, $data['query']['blocks'] );

		[ $data ] = $this->doApiRequest(
			$params, null, false, $this->mockRegisteredAuthorityWithPermissions( [ 'deferredblock' ] )
		);
		$this->assertCount( 1, $data['query']['blocks'] );
		$this->assertTrue( $data['query']['blocks'][0]['deferred'] );
		$this->assertFalse( $data['query']['blocks'][0]['block-hidden'] );
	}

	public static function provideHiddenDeferredBlocksVisibility(): array {
		return [
			'Deferred block hiding the user, no rights' => [ [ 'hideName' => true ], [], false ],
			'Deferred block hiding the user, hideuser' => [ [ 'hideName' => true ], [ 'hideuser' ], false ],
			'Deferred block hiding the user, deferredblock' => [
				[ 'hideName' => true ], [ 'deferredblock' ], false
			],
			'Deferred block hiding the user, both rights' => [
				[ 'hideName' => true ], [ 'hideuser', 'deferredblock' ], true
			],
			'Deferred hidden block, hideuser' => [ [ 'hideBlock' => true ], [ 'hideuser' ], false ],
			'Deferred hidden block, deferredblock' => [ [ 'hideBlock' => true ], [ 'deferredblock' ], false ],
			'Deferred hidden block, both rights' => [
				[ 'hideBlock' => true ], [ 'hideuser', 'deferredblock' ], true
			],
		];
	}

	/** @dataProvider provideHiddenDeferredBlocksVisibility */
	public function testHiddenDeferredBlocksVisibility(
		array $blockParams,
		array $rights,
		bool $expectVisible
	): void {
		$this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [
				'targetUser' => $this->getTestUser()->getUser(),
				'by' => $this->getTestSysop()->getUser(),
				'deferred' => true,
			] + $blockParams );

		[ $data ] = $this->doApiRequest(
			[ 'action' => 'query', 'list' => 'blocks', 'bkprop' => 'id|flags' ],
			null,
			false,
			$this->mockRegisteredAuthorityWithPermissions( $rights )
		);
		$this->assertCount( $expectVisible ? 1 : 0, $data['query']['blocks'] );
		if ( $expectVisible ) {
			$this->assertTrue( $data['query']['blocks'][0]['deferred'] );
			$this->assertTrue( $data['query']['blocks'][0]['block-hidden'] );
		}
	}

	public function testExecuteRestrictions() {
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$block = $this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [
				'targetUser' => $badActor,
				'by' => $sysop,
				'expiry' => 'infinity',
				'sitewide' => 0,
			] );

		$subset = [
			'id' => $block->getId(),
			'user' => $badActor->getName(),
			'expiry' => $block->getExpiry(),
			'duration-l10n' => $this->apiContext->msg( 'infiniteblock' )->plain(),
		];

		$title = 'Lady Macbeth';
		$pageData = $this->insertPage( $title );
		$pageId = $pageData['id'];

		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'ipblocks_restrictions' )
			->row( [
				'ir_ipb_id' => $block->getId(),
				'ir_type' => PageRestriction::TYPE_ID,
				'ir_value' => $pageId,
			] )
			// Page that has been deleted.
			->row( [
				'ir_ipb_id' => $block->getId(),
				'ir_type' => PageRestriction::TYPE_ID,
				'ir_value' => 999999,
			] )
			->row( [
				'ir_ipb_id' => $block->getId(),
				'ir_type' => NamespaceRestriction::TYPE_ID,
				'ir_value' => NS_USER_TALK,
			] )
			->row( [
				'ir_ipb_id' => $block->getId(),
				'ir_type' => CreatePageNamespaceRestriction::TYPE_ID,
				'ir_value' => NS_MAIN,
			] )
			// Invalid type
			->row( [
				'ir_ipb_id' => $block->getId(),
				'ir_type' => 127,
				'ir_value' => 4,
			] )
			// Action (upload)
			->row( [
				'ir_ipb_id' => $block->getId(),
				'ir_type' => ActionRestriction::TYPE_ID,
				'ir_value' => BlockActionInfo::ACTION_UPLOAD,
			] )
			->caller( __METHOD__ )
			->execute();

		// Test without requesting restrictions.
		[ $data ] = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'blocks',
		] );
		$this->assertArrayHasKey( 'query', $data );
		$this->assertArrayHasKey( 'blocks', $data['query'] );
		$this->assertCount( 1, $data['query']['blocks'] );
		$flagSubset = array_merge( $subset, [
			'partial' => !$block->isSitewide(),
		] );
		$this->assertArrayContains( $flagSubset, $data['query']['blocks'][0] );
		$this->assertArrayNotHasKey( 'restrictions', $data['query']['blocks'][0] );

		// Test requesting the restrictions.
		[ $data ] = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'blocks',
			'bkprop' => 'id|user|expiry|restrictions'
		] );
		$this->assertArrayHasKey( 'query', $data );
		$this->assertArrayHasKey( 'blocks', $data['query'] );
		$this->assertCount( 1, $data['query']['blocks'] );
		$restrictionsSubset = array_merge( $subset, [
			'restrictions' => [
				'pages' => [
					[
						'id' => $pageId,
						'ns' => NS_MAIN,
						'title' => $title,
					],
				],
				'namespaces' => [
					NS_USER_TALK,
				],
				'createpagenamespaces' => [
					NS_MAIN,
				],
				'actions' => [
					'upload'
				]
			],
		] );
		$this->assertEqualsCanonicalizing( $restrictionsSubset, $data['query']['blocks'][0] );
		$this->assertArrayNotHasKey( 'partial', $data['query']['blocks'][0] );
	}
}
