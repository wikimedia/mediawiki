<?php

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiQueryBlocks
 */
class ApiQueryBlocksTest extends ApiTestCase {

	protected $tablesUsed = [
		'ipblocks',
		'ipblocks_restrictions',
	];

	public function testExecute() {
		list( $data ) = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'blocks',
		] );
		$this->assertEquals( [ 'batchcomplete' => true, 'query' => [ 'blocks' => [] ] ], $data );
	}

	public function testExecuteBlock() {
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$block = new DatabaseBlock( [
			'address' => $badActor->getName(),
			'user' => $badActor->getId(),
			'by' => $sysop->getId(),
			'expiry' => 'infinity',
		] );

		$block->insert();

		list( $data ) = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'blocks',
		] );
		$this->arrayHasKey( 'query', $data );
		$this->arrayHasKey( 'blocks', $data['query'] );
		$this->assertCount( 1, $data['query']['blocks'] );
		$subset = [
			'id' => $block->getId(),
			'user' => $badActor->getName(),
			'expiry' => $block->getExpiry(),
		];
		$this->assertArraySubset( $subset, $data['query']['blocks'][0] );
	}

	public function testExecuteSitewide() {
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$block = new DatabaseBlock( [
			'address' => $badActor->getName(),
			'user' => $badActor->getId(),
			'by' => $sysop->getId(),
			'ipb_expiry' => 'infinity',
			'ipb_sitewide' => 1,
		] );

		$block->insert();

		list( $data ) = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'blocks',
		] );
		$this->arrayHasKey( 'query', $data );
		$this->arrayHasKey( 'blocks', $data['query'] );
		$this->assertCount( 1, $data['query']['blocks'] );
		$subset = [
			'id' => $block->getId(),
			'user' => $badActor->getName(),
			'expiry' => $block->getExpiry(),
			'partial' => !$block->isSitewide(),
		];
		$this->assertArraySubset( $subset, $data['query']['blocks'][0] );
	}

	public function testExecuteRestrictions() {
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$block = new DatabaseBlock( [
			'address' => $badActor->getName(),
			'user' => $badActor->getId(),
			'by' => $sysop->getId(),
			'expiry' => 'infinity',
			'sitewide' => 0,
		] );

		$block->insert();

		$subset = [
			'id' => $block->getId(),
			'user' => $badActor->getName(),
			'expiry' => $block->getExpiry(),
		];

		$title = 'Lady Macbeth';
		$pageData = $this->insertPage( $title );
		$pageId = $pageData['id'];

		$this->db->insert( 'ipblocks_restrictions', [
			'ir_ipb_id' => $block->getId(),
			'ir_type' => PageRestriction::TYPE_ID,
			'ir_value' => $pageId,
		] );
		// Page that has been deleted.
		$this->db->insert( 'ipblocks_restrictions', [
			'ir_ipb_id' => $block->getId(),
			'ir_type' => PageRestriction::TYPE_ID,
			'ir_value' => 999999,
		] );
		$this->db->insert( 'ipblocks_restrictions', [
			'ir_ipb_id' => $block->getId(),
			'ir_type' => NamespaceRestriction::TYPE_ID,
			'ir_value' => NS_USER_TALK,
		] );
		$this->db->insert( 'ipblocks_restrictions', [
			'ir_ipb_id' => $block->getId(),
			'ir_type' => 3,
			'ir_value' => 4,
		] );

		// Test without requesting restrictions.
		list( $data ) = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'blocks',
		] );
		$this->arrayHasKey( 'query', $data );
		$this->arrayHasKey( 'blocks', $data['query'] );
		$this->assertCount( 1, $data['query']['blocks'] );
		$flagSubset = array_merge( $subset, [
			'partial' => !$block->isSitewide(),
		] );
		$this->assertArraySubset( $flagSubset, $data['query']['blocks'][0] );
		$this->assertArrayNotHasKey( 'restrictions', $data['query']['blocks'][0] );

		// Test requesting the restrictions.
		list( $data ) = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'blocks',
			'bkprop' => 'id|user|expiry|restrictions'
		] );
		$this->arrayHasKey( 'query', $data );
		$this->arrayHasKey( 'blocks', $data['query'] );
		$this->assertCount( 1, $data['query']['blocks'] );
		$restrictionsSubset = array_merge( $subset, [
			'restrictions' => [
				'pages' => [
					[
						'id' => $pageId,
						'ns' => 0,
						'title' => $title,
					],
				],
				'namespaces' => [
					NS_USER_TALK,
				],
			],
		] );
		$this->assertArraySubset( $restrictionsSubset, $data['query']['blocks'][0] );
		$this->assertArrayNotHasKey( 'partial', $data['query']['blocks'][0] );
	}
}
