<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Block\BlockActionInfo;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\ActionRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers MediaWiki\Api\ApiQueryBlocks
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

		$block = new DatabaseBlock( [
			'address' => $badActor,
			'by' => $sysop,
			'expiry' => 'infinity',
		] );

		$this->getServiceContainer()->getDatabaseBlockStore()->insertBlock( $block );

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
		];
		$this->assertArraySubmapSame( $subset, $data['query']['blocks'][0] );
	}

	public function testExecuteSitewide() {
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$block = new DatabaseBlock( [
			'address' => $badActor,
			'by' => $sysop,
		] );

		$this->getServiceContainer()->getDatabaseBlockStore()->insertBlock( $block );

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
		$this->assertArraySubmapSame( $subset, $data['query']['blocks'][0] );
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

		$autoblock = $this->getServiceContainer()->getDatabaseBlockStore()
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

	public function testExecuteRestrictions() {
		$this->overrideConfigValue( MainConfigNames::EnablePartialActionBlocks, true );
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$block = new DatabaseBlock( [
			'address' => $badActor,
			'by' => $sysop,
			'expiry' => 'infinity',
			'sitewide' => 0,
		] );

		$this->getServiceContainer()->getDatabaseBlockStore()->insertBlock( $block );

		$subset = [
			'id' => $block->getId(),
			'user' => $badActor->getName(),
			'expiry' => $block->getExpiry(),
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
		$this->assertArraySubmapSame( $flagSubset, $data['query']['blocks'][0] );
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
				'actions' => [
					'upload'
				]
			],
		] );
		$this->assertArraySubmapSame( $restrictionsSubset, $data['query']['blocks'][0] );
		$this->assertArrayNotHasKey( 'partial', $data['query']['blocks'][0] );
	}
}
