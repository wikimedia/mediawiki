<?php

namespace MediaWiki\Tests\Block;

use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\Restriction\Restriction;
use MediaWiki\MainConfigNames;

/**
 * @group Database
 * @group Blocking
 * @covers \MediaWiki\Block\BlockRestrictionStore
 */
class BlockRestrictionStoreTest extends \MediaWikiLangTestCase {

	protected BlockRestrictionStore $blockRestrictionStore;

	protected function setUp(): void {
		parent::setUp();

		$this->blockRestrictionStore = $this->getServiceContainer()->getBlockRestrictionStore();
	}

	public function testLoadMultipleRestrictions() {
		$this->overrideConfigValue( MainConfigNames::BlockDisablesLogin, false );
		$block = $this->insertBlock();

		$pageFoo = $this->getExistingTestPage( 'Foo' );
		$pageBar = $this->getExistingTestPage( 'Bar' );

		$this->blockRestrictionStore->insert( [
			new PageRestriction( $block->getId(), $pageFoo->getId() ),
			new PageRestriction( $block->getId(), $pageBar->getId() ),
			new NamespaceRestriction( $block->getId(), NS_USER ),
		] );

		$restrictions = $this->blockRestrictionStore->loadByBlockId( $block->getId() );

		$this->assertCount( 3, $restrictions );
	}

	public function testWithNoRestrictions() {
		$block = $this->insertBlock();
		$restrictions = $this->blockRestrictionStore->loadByBlockId( $block->getId() );
		$this->assertSame( [], $restrictions );
	}

	public function testWithEmptyParam() {
		$restrictions = $this->blockRestrictionStore->loadByBlockId( [] );
		$this->assertSame( [], $restrictions );
	}

	public function testIgnoreNotSupportedTypes() {
		$block = $this->insertBlock();

		$pageFoo = $this->getExistingTestPage( 'Foo' );
		$pageBar = $this->getExistingTestPage( 'Bar' );

		// valid type
		$this->insertRestriction( $block->getId(), PageRestriction::TYPE_ID, $pageFoo->getId() );
		$this->insertRestriction( $block->getId(), NamespaceRestriction::TYPE_ID, NS_USER );

		// invalid type
		$this->insertRestriction( $block->getId(), 9, $pageBar->getId() );
		$this->insertRestriction( $block->getId(), 10, NS_FILE );

		$restrictions = $this->blockRestrictionStore->loadByBlockId( $block->getId() );
		$this->assertCount( 2, $restrictions );
	}

	public function testMappingPageRestrictionObject() {
		$block = $this->insertBlock();
		$title = 'Lady Macbeth';
		$page = $this->getExistingTestPage( $title );

		// Test Page Restrictions.
		$this->blockRestrictionStore->insert( [
			new PageRestriction( $block->getId(), $page->getId() ),
		] );

		$restrictions = $this->blockRestrictionStore->loadByBlockId( $block->getId() );

		[ $pageRestriction ] = $restrictions;
		$this->assertInstanceOf( PageRestriction::class, $pageRestriction );
		$this->assertEquals( $block->getId(), $pageRestriction->getBlockId() );
		$this->assertEquals( $page->getId(), $pageRestriction->getValue() );
		$this->assertEquals( PageRestriction::TYPE, $pageRestriction->getType() );
		$this->assertEquals( $pageRestriction->getTitle()->getText(), $title );
	}

	public function testMappingNamespaceRestrictionObject() {
		$block = $this->insertBlock();

		$this->blockRestrictionStore->insert( [
			new NamespaceRestriction( $block->getId(), NS_USER ),
		] );

		$restrictions = $this->blockRestrictionStore->loadByBlockId( $block->getId() );

		[ $namespaceRestriction ] = $restrictions;
		$this->assertInstanceOf( NamespaceRestriction::class, $namespaceRestriction );
		$this->assertEquals( $block->getId(), $namespaceRestriction->getBlockId() );
		$this->assertSame( NS_USER, $namespaceRestriction->getValue() );
		$this->assertEquals( NamespaceRestriction::TYPE, $namespaceRestriction->getType() );
	}

	public function testInsert() {
		$block = $this->insertBlock();

		$pageFoo = $this->getExistingTestPage( 'Foo' );
		$pageBar = $this->getExistingTestPage( 'Bar' );

		$restrictions = [
			new PageRestriction( $block->getId(), $pageFoo->getId() ),
			new PageRestriction( $block->getId(), $pageBar->getId() ),
			new NamespaceRestriction( $block->getId(), NS_USER )
		];

		$result = $this->blockRestrictionStore->insert( $restrictions );
		$this->assertTrue( $result );

		$result = $this->blockRestrictionStore->insert( [] );
		$this->assertFalse( $result );
	}

	public function testInsertTypes() {
		$block = $this->insertBlock();

		$pageFoo = $this->getExistingTestPage( 'Foo' );
		$pageBar = $this->getExistingTestPage( 'Bar' );

		$invalid = $this->createMock( Restriction::class );
		$invalid->method( 'toRow' )
			->willReturn( [
				'ir_ipb_id' => $block->getId(),
				'ir_type' => 9,
				'ir_value' => 42,
			] );

		$restrictions = [
			new PageRestriction( $block->getId(), $pageFoo->getId() ),
			new PageRestriction( $block->getId(), $pageBar->getId() ),
			new NamespaceRestriction( $block->getId(), NS_USER ),
			$invalid,
		];

		$result = $this->blockRestrictionStore->insert( $restrictions );
		$this->assertTrue( $result );

		$restrictions = $this->blockRestrictionStore->loadByBlockId( $block->getId() );
		$this->assertCount( 3, $restrictions );
	}

	public function testUpdateInsert() {
		$block = $this->insertBlock();
		$pageFoo = $this->getExistingTestPage( 'Foo' );
		$pageBar = $this->getExistingTestPage( 'Bar' );
		$this->blockRestrictionStore->insert( [
				new PageRestriction( $block->getId(), $pageFoo->getId() ),
		] );

		$this->blockRestrictionStore->update( [
			new PageRestriction( $block->getId(), $pageBar->getId() ),
			new NamespaceRestriction( $block->getId(), NS_USER ),
		] );

		$result = $this->getDb()->newSelectQueryBuilder()
			->select( [ '*' ] )
			->from( 'ipblocks_restrictions' )
			->where( [ 'ir_ipb_id' => $block->getId() ] )
			->fetchResultSet();

		$this->assertEquals( 2, $result->numRows() );
		$row = $result->fetchObject();
		$this->assertEquals( $block->getId(), $row->ir_ipb_id );
		$this->assertEquals( $pageBar->getId(), $row->ir_value );
	}

	public function testUpdateChange() {
		$block = $this->insertBlock();
		$page = $this->getExistingTestPage( 'Foo' );

		$this->blockRestrictionStore->update( [
			new PageRestriction( $block->getId(), $page->getId() ),
		] );

		$result = $this->getDb()->newSelectQueryBuilder()
			->select( [ '*' ] )
			->from( 'ipblocks_restrictions' )
			->where( [ 'ir_ipb_id' => $block->getId() ] )
			->fetchResultSet();

		$this->assertSame( 1, $result->numRows() );
		$row = $result->fetchObject();
		$this->assertEquals( $block->getId(), $row->ir_ipb_id );
		$this->assertEquals( $page->getId(), $row->ir_value );
	}

	public function testUpdateNoRestrictions() {
		$block = $this->insertBlock();

		$this->blockRestrictionStore->update( [] );

		$result = $this->getDb()->newSelectQueryBuilder()
			->select( [ '*' ] )
			->from( 'ipblocks_restrictions' )
			->where( [ 'ir_ipb_id' => $block->getId() ] )
			->fetchResultSet();

		$this->assertSame( 0, $result->numRows() );
	}

	public function testUpdateSame() {
		$block = $this->insertBlock();
		$page = $this->getExistingTestPage( 'Foo' );
		$this->blockRestrictionStore->insert( [
			new PageRestriction( $block->getId(), $page->getId() ),
		] );

		$this->blockRestrictionStore->update( [
			new PageRestriction( $block->getId(), $page->getId() ),
		] );

		$result = $this->getDb()->newSelectQueryBuilder()
			->select( [ '*' ] )
			->from( 'ipblocks_restrictions' )
			->where( [ 'ir_ipb_id' => $block->getId() ] )
			->fetchResultSet();

		$this->assertSame( 1, $result->numRows() );
		$row = $result->fetchObject();
		$this->assertEquals( $block->getId(), $row->ir_ipb_id );
		$this->assertEquals( $page->getId(), $row->ir_value );
	}

	public function testDeleteAllUpdateByParentBlockId() {
		// Create a block and an autoblock (a child block)
		$block = $this->insertBlock();
		$pageFoo = $this->getExistingTestPage( 'Foo' );
		$pageBar = $this->getExistingTestPage( 'Bar' );
		$this->blockRestrictionStore->insert( [
			new PageRestriction( $block->getId(), $pageFoo->getId() ),
		] );
		$autoblockId = $this->getServiceContainer()->getDatabaseBlockStore()
			->doAutoblock( $block, '127.0.0.1' );

		// Ensure that the restrictions on the block have not changed.
		$restrictions = $this->blockRestrictionStore->loadByBlockId( $block->getId() );
		$this->assertCount( 1, $restrictions );
		$this->assertEquals( $pageFoo->getId(), $restrictions[0]->getValue() );

		// Ensure that the restrictions on the autoblock are the same as the block.
		$restrictions = $this->blockRestrictionStore->loadByBlockId( $autoblockId );
		$this->assertCount( 1, $restrictions );
		$this->assertEquals( $pageFoo->getId(), $restrictions[0]->getValue() );

		// Update the restrictions on the autoblock (but leave the block unchanged)
		$this->blockRestrictionStore->updateByParentBlockId( $block->getId(), [
			new PageRestriction( $block->getId(), $pageBar->getId() ),
		] );

		// Ensure that the restrictions on the block have not changed.
		$restrictions = $this->blockRestrictionStore->loadByBlockId( $block->getId() );
		$this->assertCount( 1, $restrictions );
		$this->assertEquals( $pageFoo->getId(), $restrictions[0]->getValue() );

		// Ensure that the restrictions on the autoblock have been updated.
		$restrictions = $this->blockRestrictionStore->loadByBlockId( $autoblockId );
		$this->assertCount( 1, $restrictions );
		$this->assertEquals( $pageBar->getId(), $restrictions[0]->getValue() );
	}

	public function testUpdateByParentBlockId() {
		// Create a block and an autoblock (a child block)
		$block = $this->insertBlock();
		$page = $this->getExistingTestPage( 'Foo' );
		$this->blockRestrictionStore->insert( [
			new PageRestriction( $block->getId(), $page->getId() ),
		] );
		$autoblockId = $this->getServiceContainer()->getDatabaseBlockStore()
			->doAutoblock( $block, '127.0.0.1' );

		// Ensure that the restrictions on the block have not changed.
		$restrictions = $this->blockRestrictionStore->loadByBlockId( $block->getId() );
		$this->assertCount( 1, $restrictions );

		// Ensure that the restrictions on the autoblock have not changed.
		$restrictions = $this->blockRestrictionStore->loadByBlockId( $autoblockId );
		$this->assertCount( 1, $restrictions );

		// Remove the restrictions on the autoblock (but leave the block unchanged)
		$this->blockRestrictionStore->updateByParentBlockId( $block->getId(), [] );

		// Ensure that the restrictions on the block have not changed.
		$restrictions = $this->blockRestrictionStore->loadByBlockId( $block->getId() );
		$this->assertCount( 1, $restrictions );

		// Ensure that the restrictions on the autoblock have been updated.
		$restrictions = $this->blockRestrictionStore->loadByBlockId( $autoblockId );
		$this->assertSame( [], $restrictions );
	}

	public function testNoAutoblocksUpdateByParentBlockId() {
		// Create a block with no autoblock.
		$block = $this->insertBlock();
		$page = $this->getExistingTestPage( 'Foo' );
		$this->blockRestrictionStore->insert( [
			new PageRestriction( $block->getId(), $page->getId() ),
		] );

		// Ensure that the restrictions on the block have not changed.
		$restrictions = $this->blockRestrictionStore->loadByBlockId( $block->getId() );
		$this->assertCount( 1, $restrictions );

		// Update the restrictions on any autoblocks (there are none).
		$this->blockRestrictionStore->updateByParentBlockId( $block->getId(), $restrictions );

		// Ensure that the restrictions on the block have not changed.
		$restrictions = $this->blockRestrictionStore->loadByBlockId( $block->getId() );
		$this->assertCount( 1, $restrictions );
	}

	public function testDelete() {
		$block = $this->insertBlock();
		$page = $this->getExistingTestPage( 'Foo' );
		$this->blockRestrictionStore->insert( [
			new PageRestriction( $block->getId(), $page->getId() ),
		] );

		$restrictions = $this->blockRestrictionStore->loadByBlockId( $block->getId() );
		$this->assertCount( 1, $restrictions );

		$result = $this->blockRestrictionStore->delete( $restrictions );
		$this->assertTrue( $result );

		$restrictions = $this->blockRestrictionStore->loadByBlockId( $block->getId() );
		$this->assertSame( [], $restrictions );
	}

	public function testDeleteByBlockId() {
		$block = $this->insertBlock();
		$page = $this->getExistingTestPage( 'Foo' );
		$this->blockRestrictionStore->insert( [
			new PageRestriction( $block->getId(), $page->getId() ),
		] );

		$restrictions = $this->blockRestrictionStore->loadByBlockId( $block->getId() );
		$this->assertCount( 1, $restrictions );

		$result = $this->blockRestrictionStore->deleteByBlockId( $block->getId() );
		$this->assertNotFalse( $result );

		$restrictions = $this->blockRestrictionStore->loadByBlockId( $block->getId() );
		$this->assertSame( [], $restrictions );
	}

	/**
	 * @dataProvider equalsDataProvider
	 *
	 * @param array $a
	 * @param array $b
	 * @param bool $expected
	 */
	public function testEquals( array $a, array $b, $expected ) {
		$this->assertSame( $expected, $this->blockRestrictionStore->equals( $a, $b ) );
	}

	public static function equalsDataProvider() {
		return [
			[
				[
					new PageRestriction( 1, 1 ),
				],
				[
					new PageRestriction( 1, 2 )
				],
				false,
			],
			[
				[
					new PageRestriction( 1, 1 ),
				],
				[
					new PageRestriction( 1, 1 ),
					new PageRestriction( 1, 2 )
				],
				false,
			],
			[
				[],
				[],
				true,
			],
			[
				[
					new PageRestriction( 1, 1 ),
					new PageRestriction( 1, 2 ),
					new PageRestriction( 2, 3 ),
				],
				[
					new PageRestriction( 2, 3 ),
					new PageRestriction( 1, 2 ),
					new PageRestriction( 1, 1 ),
				],
				true
			],
			[
				[
					new NamespaceRestriction( 1, NS_USER ),
				],
				[
					new NamespaceRestriction( 1, NS_USER ),
				],
				true
			],
			[
				[
					new NamespaceRestriction( 1, NS_USER ),
				],
				[
					new NamespaceRestriction( 1, NS_TALK ),
				],
				false
			],
		];
	}

	public function testSetBlockId() {
		$restrictions = [
			new PageRestriction( 1, 1 ),
			new PageRestriction( 1, 2 ),
			new NamespaceRestriction( 1, NS_USER ),
		];

		$this->assertSame( 1, $restrictions[0]->getBlockId() );
		$this->assertSame( 1, $restrictions[1]->getBlockId() );
		$this->assertSame( 1, $restrictions[2]->getBlockId() );

		$result = $this->blockRestrictionStore->setBlockId( 2, $restrictions );

		foreach ( $result as $restriction ) {
			$this->assertSame( 2, $restriction->getBlockId() );
		}
	}

	protected function insertBlock() {
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		return $this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [
				'address' => $badActor,
				'by' => $sysop,
				'expiry' => 'infinity',
				'sitewide' => 0,
				'enableAutoblock' => true,
			] );
	}

	protected function insertRestriction( $blockId, $type, $value ) {
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'ipblocks_restrictions' )
			->row( [
				'ir_ipb_id' => $blockId,
				'ir_type' => $type,
				'ir_value' => $value,
			] )
			->caller( __METHOD__ )
			->execute();
	}
}
