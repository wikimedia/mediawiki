<?php

namespace MediaWiki\Tests\Block;

use MediaWiki\Block\BlockRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\Restriction\Restriction;

/**
 * @group Database
 * @group Blocking
 * @coversDefaultClass \MediaWiki\Block\BlockRestriction
 */
class BlockRestrictionTest extends \MediaWikiLangTestCase {

	public function tearDown() {
		parent::tearDown();
		$this->resetTables();
	}

	/**
	 * @covers ::loadByBlockId
	 * @covers ::resultToRestrictions
	 * @covers ::rowToRestriction
	 */
	public function testLoadMultipleRestrictions() {
		$block = $this->insertBlock();

		$pageFoo = $this->getExistingTestPage( 'Foo' );
		$pageBar = $this->getExistingTestPage( 'Bar' );

		BlockRestriction::insert( [
			new PageRestriction( $block->getId(), $pageFoo->getId() ),
			new PageRestriction( $block->getId(), $pageBar->getId() ),
			new NamespaceRestriction( $block->getId(), NS_USER ),
		] );

		$restrictions = BlockRestriction::loadByBlockId( $block->getId() );

		$this->assertCount( 3, $restrictions );
	}

	/**
	 * @covers ::loadByBlockId
	 * @covers ::resultToRestrictions
	 * @covers ::rowToRestriction
	 */
	public function testWithNoRestrictions() {
		$block = $this->insertBlock();
		$restrictions = BlockRestriction::loadByBlockId( $block->getId() );
		$this->assertEmpty( $restrictions );
	}

	/**
	 * @covers ::loadByBlockId
	 * @covers ::resultToRestrictions
	 * @covers ::rowToRestriction
	 */
	public function testWithEmptyParam() {
		$restrictions = BlockRestriction::loadByBlockId( [] );

		$this->assertEmpty( $restrictions );
	}

	/**
	 * @covers ::loadByBlockId
	 * @covers ::resultToRestrictions
	 * @covers ::rowToRestriction
	 */
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

		$restrictions = BlockRestriction::loadByBlockId( $block->getId() );
		$this->assertCount( 2, $restrictions );
	}

	/**
	 * @covers ::loadByBlockId
	 * @covers ::resultToRestrictions
	 * @covers ::rowToRestriction
	 */
	public function testMappingPageRestrictionObject() {
		$block = $this->insertBlock();
		$title = 'Lady Macbeth';
		$page = $this->getExistingTestPage( $title );

		// Test Page Restrictions.
		BlockRestriction::insert( [
			new PageRestriction( $block->getId(), $page->getId() ),
		] );

		$restrictions = BlockRestriction::loadByBlockId( $block->getId() );

		list( $pageRestriction ) = $restrictions;
		$this->assertInstanceOf( PageRestriction::class, $pageRestriction );
		$this->assertEquals( $block->getId(), $pageRestriction->getBlockId() );
		$this->assertEquals( $page->getId(), $pageRestriction->getValue() );
		$this->assertEquals( $pageRestriction->getType(), PageRestriction::TYPE );
		$this->assertEquals( $pageRestriction->getTitle()->getText(), $title );
	}

	/**
	 * @covers ::loadByBlockId
	 * @covers ::resultToRestrictions
	 * @covers ::rowToRestriction
	 */
	public function testMappingNamespaceRestrictionObject() {
		$block = $this->insertBlock();

		BlockRestriction::insert( [
			new NamespaceRestriction( $block->getId(), NS_USER ),
		] );

		$restrictions = BlockRestriction::loadByBlockId( $block->getId() );

		list( $namespaceRestriction ) = $restrictions;
		$this->assertInstanceOf( NamespaceRestriction::class, $namespaceRestriction );
		$this->assertEquals( $block->getId(), $namespaceRestriction->getBlockId() );
		$this->assertSame( NS_USER, $namespaceRestriction->getValue() );
		$this->assertEquals( $namespaceRestriction->getType(), NamespaceRestriction::TYPE );
	}

	/**
	 * @covers ::insert
	 */
	public function testInsert() {
		$block = $this->insertBlock();

		$pageFoo = $this->getExistingTestPage( 'Foo' );
		$pageBar = $this->getExistingTestPage( 'Bar' );

		$restrictions = [
			new \stdClass(),
			new PageRestriction( $block->getId(), $pageFoo->getId() ),
			new PageRestriction( $block->getId(), $pageBar->getId() ),
			new NamespaceRestriction( $block->getId(), NS_USER )
		];

		$result = BlockRestriction::insert( $restrictions );
		$this->assertTrue( $result );

		$restrictions = [
			new \stdClass(),
		];

		$result = BlockRestriction::insert( $restrictions );
		$this->assertFalse( $result );

		$result = BlockRestriction::insert( [] );
		$this->assertFalse( $result );
	}

	/**
	 * @covers ::insert
	 */
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
			new \stdClass(),
			new PageRestriction( $block->getId(), $pageFoo->getId() ),
			new PageRestriction( $block->getId(), $pageBar->getId() ),
			new NamespaceRestriction( $block->getId(), NS_USER ),
			$invalid,
		];

		$result = BlockRestriction::insert( $restrictions );
		$this->assertTrue( $result );

		$restrictions = BlockRestriction::loadByBlockId( $block->getId() );
		$this->assertCount( 3, $restrictions );
	}

	/**
	 * @covers ::update
	 * @covers ::restrictionsByBlockId
	 * @covers ::restrictionsToRemove
	 */
	public function testUpdateInsert() {
		$block = $this->insertBlock();
		$pageFoo = $this->getExistingTestPage( 'Foo' );
		$pageBar = $this->getExistingTestPage( 'Bar' );
		BlockRestriction::insert( [
				new PageRestriction( $block->getId(), $pageFoo->getId() ),
		] );

		BlockRestriction::update( [
			new \stdClass(),
			new PageRestriction( $block->getId(), $pageBar->getId() ),
			new NamespaceRestriction( $block->getId(), NS_USER ),
		] );

		$db = wfGetDb( DB_REPLICA );
		$result = $db->select(
			[ 'ipblocks_restrictions' ],
			[ '*' ],
			[ 'ir_ipb_id' => $block->getId() ]
		);

		$this->assertEquals( 2, $result->numRows() );
		$row = $result->fetchObject();
		$this->assertEquals( $block->getId(), $row->ir_ipb_id );
		$this->assertEquals( $pageBar->getId(), $row->ir_value );
	}

	/**
	 * @covers ::update
	 * @covers ::restrictionsByBlockId
	 * @covers ::restrictionsToRemove
	 */
	public function testUpdateChange() {
		$block = $this->insertBlock();
		$page = $this->getExistingTestPage( 'Foo' );

		BlockRestriction::update( [
			new PageRestriction( $block->getId(), $page->getId() ),
		] );

		$db = wfGetDb( DB_REPLICA );
		$result = $db->select(
			[ 'ipblocks_restrictions' ],
			[ '*' ],
			[ 'ir_ipb_id' => $block->getId() ]
		);

		$this->assertEquals( 1, $result->numRows() );
		$row = $result->fetchObject();
		$this->assertEquals( $block->getId(), $row->ir_ipb_id );
		$this->assertEquals( $page->getId(), $row->ir_value );
	}

	/**
	 * @covers ::update
	 * @covers ::restrictionsByBlockId
	 * @covers ::restrictionsToRemove
	 */
	public function testUpdateNoRestrictions() {
		$block = $this->insertBlock();

		BlockRestriction::update( [] );

		$db = wfGetDb( DB_REPLICA );
		$result = $db->select(
			[ 'ipblocks_restrictions' ],
			[ '*' ],
			[ 'ir_ipb_id' => $block->getId() ]
		);

		$this->assertEquals( 0, $result->numRows() );
	}

	/**
	 * @covers ::update
	 * @covers ::restrictionsByBlockId
	 * @covers ::restrictionsToRemove
	 */
	public function testUpdateSame() {
		$block = $this->insertBlock();
		$page = $this->getExistingTestPage( 'Foo' );
		BlockRestriction::insert( [
			new PageRestriction( $block->getId(), $page->getId() ),
		] );

		BlockRestriction::update( [
			new PageRestriction( $block->getId(), $page->getId() ),
		] );

		$db = wfGetDb( DB_REPLICA );
		$result = $db->select(
			[ 'ipblocks_restrictions' ],
			[ '*' ],
			[ 'ir_ipb_id' => $block->getId() ]
		);

		$this->assertEquals( 1, $result->numRows() );
		$row = $result->fetchObject();
		$this->assertEquals( $block->getId(), $row->ir_ipb_id );
		$this->assertEquals( $page->getId(), $row->ir_value );
	}

	/**
	 * @covers ::updateByParentBlockId
	 */
	public function testDeleteAllUpdateByParentBlockId() {
		// Create a block and an autoblock (a child block)
		$block = $this->insertBlock();
		$pageFoo = $this->getExistingTestPage( 'Foo' );
		$pageBar = $this->getExistingTestPage( 'Bar' );
		BlockRestriction::insert( [
			new PageRestriction( $block->getId(), $pageFoo->getId() ),
		] );
		$autoblockId = $block->doAutoblock( '127.0.0.1' );

		// Ensure that the restrictions on the block have not changed.
		$restrictions = BlockRestriction::loadByBlockId( $block->getId() );
		$this->assertCount( 1, $restrictions );
		$this->assertEquals( $pageFoo->getId(), $restrictions[0]->getValue() );

		// Ensure that the restrictions on the autoblock are the same as the block.
		$restrictions = BlockRestriction::loadByBlockId( $autoblockId );
		$this->assertCount( 1, $restrictions );
		$this->assertEquals( $pageFoo->getId(), $restrictions[0]->getValue() );

		// Update the restrictions on the autoblock (but leave the block unchanged)
		BlockRestriction::updateByParentBlockId( $block->getId(), [
			new PageRestriction( $block->getId(), $pageBar->getId() ),
		] );

		// Ensure that the restrictions on the block have not changed.
		$restrictions = BlockRestriction::loadByBlockId( $block->getId() );
		$this->assertCount( 1, $restrictions );
		$this->assertEquals( $pageFoo->getId(), $restrictions[0]->getValue() );

		// Ensure that the restrictions on the autoblock have been updated.
		$restrictions = BlockRestriction::loadByBlockId( $autoblockId );
		$this->assertCount( 1, $restrictions );
		$this->assertEquals( $pageBar->getId(), $restrictions[0]->getValue() );
	}

	/**
	 * @covers ::updateByParentBlockId
	 */
	public function testUpdateByParentBlockId() {
		// Create a block and an autoblock (a child block)
		$block = $this->insertBlock();
		$page = $this->getExistingTestPage( 'Foo' );
		BlockRestriction::insert( [
			new PageRestriction( $block->getId(), $page->getId() ),
		] );
		$autoblockId = $block->doAutoblock( '127.0.0.1' );

		// Ensure that the restrictions on the block have not changed.
		$restrictions = BlockRestriction::loadByBlockId( $block->getId() );
		$this->assertCount( 1, $restrictions );

		// Ensure that the restrictions on the autoblock have not changed.
		$restrictions = BlockRestriction::loadByBlockId( $autoblockId );
		$this->assertCount( 1, $restrictions );

		// Remove the restrictions on the autoblock (but leave the block unchanged)
		BlockRestriction::updateByParentBlockId( $block->getId(), [] );

		// Ensure that the restrictions on the block have not changed.
		$restrictions = BlockRestriction::loadByBlockId( $block->getId() );
		$this->assertCount( 1, $restrictions );

		// Ensure that the restrictions on the autoblock have been updated.
		$restrictions = BlockRestriction::loadByBlockId( $autoblockId );
		$this->assertCount( 0, $restrictions );
	}

	/**
	 * @covers ::updateByParentBlockId
	 */
	public function testNoAutoblocksUpdateByParentBlockId() {
		// Create a block with no autoblock.
		$block = $this->insertBlock();
		$page = $this->getExistingTestPage( 'Foo' );
		BlockRestriction::insert( [
			new PageRestriction( $block->getId(), $page->getId() ),
		] );

		// Ensure that the restrictions on the block have not changed.
		$restrictions = BlockRestriction::loadByBlockId( $block->getId() );
		$this->assertCount( 1, $restrictions );

		// Update the restrictions on any autoblocks (there are none).
		BlockRestriction::updateByParentBlockId( $block->getId(), $restrictions );

		// Ensure that the restrictions on the block have not changed.
		$restrictions = BlockRestriction::loadByBlockId( $block->getId() );
		$this->assertCount( 1, $restrictions );
	}

	/**
	 * @covers ::delete
	 */
	public function testDelete() {
		$block = $this->insertBlock();
		$page = $this->getExistingTestPage( 'Foo' );
		BlockRestriction::insert( [
			new PageRestriction( $block->getId(), $page->getId() ),
		] );

		$restrictions = BlockRestriction::loadByBlockId( $block->getId() );
		$this->assertCount( 1, $restrictions );

		$result = BlockRestriction::delete( array_merge( $restrictions, [ new \stdClass() ] ) );
		$this->assertTrue( $result );

		$restrictions = BlockRestriction::loadByBlockId( $block->getId() );
		$this->assertCount( 0, $restrictions );
	}

	/**
	 * @covers ::deleteByBlockId
	 */
	public function testDeleteByBlockId() {
		$block = $this->insertBlock();
		$page = $this->getExistingTestPage( 'Foo' );
		BlockRestriction::insert( [
			new PageRestriction( $block->getId(), $page->getId() ),
		] );

		$restrictions = BlockRestriction::loadByBlockId( $block->getId() );
		$this->assertCount( 1, $restrictions );

		$result = BlockRestriction::deleteByBlockId( $block->getId() );
		$this->assertNotFalse( $result );

		$restrictions = BlockRestriction::loadByBlockId( $block->getId() );
		$this->assertCount( 0, $restrictions );
	}

	/**
	 * @covers ::deleteByParentBlockId
	 */
	public function testDeleteByParentBlockId() {
		// Create a block with no autoblock.
		$block = $this->insertBlock();
		$page = $this->getExistingTestPage( 'Foo' );
		BlockRestriction::insert( [
			new PageRestriction( $block->getId(), $page->getId() ),
		] );
		$autoblockId = $block->doAutoblock( '127.0.0.1' );

		// Ensure that the restrictions on the block have not changed.
		$restrictions = BlockRestriction::loadByBlockId( $block->getId() );
		$this->assertCount( 1, $restrictions );

		// Ensure that the restrictions on the autoblock are the same as the block.
		$restrictions = BlockRestriction::loadByBlockId( $autoblockId );
		$this->assertCount( 1, $restrictions );

		// Remove all of the restrictions on the autoblock (but leave the block unchanged).
		$result = BlockRestriction::deleteByParentBlockId( $block->getId() );
		// NOTE: commented out until https://gerrit.wikimedia.org/r/c/mediawiki/core/+/469324 is merged
		//$this->assertTrue( $result );

		// Ensure that the restrictions on the block have not changed.
		$restrictions = BlockRestriction::loadByBlockId( $block->getId() );
		$this->assertCount( 1, $restrictions );

		// Ensure that the restrictions on the autoblock have been removed.
		$restrictions = BlockRestriction::loadByBlockId( $autoblockId );
		$this->assertCount( 0, $restrictions );
	}

	/**
	 * @covers ::equals
	 * @dataProvider equalsDataProvider
	 *
	 * @param array $a
	 * @param array $b
	 * @param bool $expected
	 */
	public function testEquals( array $a, array $b, $expected ) {
		$this->assertSame( $expected, BlockRestriction::equals( $a, $b ) );
	}

	public function equalsDataProvider() {
		return [
			[
				[
					new \stdClass(),
					new PageRestriction( 1, 1 ),
				],
				[
					new \stdClass(),
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

	/**
	 * @covers ::setBlockId
	 */
	public function testSetBlockId() {
		$restrictions = [
			new \stdClass(),
			new PageRestriction( 1, 1 ),
			new PageRestriction( 1, 2 ),
			new NamespaceRestriction( 1, NS_USER ),
		];

		$this->assertSame( 1, $restrictions[1]->getBlockId() );
		$this->assertSame( 1, $restrictions[2]->getBlockId() );
		$this->assertSame( 1, $restrictions[3]->getBlockId() );

		$result = BlockRestriction::setBlockId( 2, $restrictions );

		foreach ( $result as $restriction ) {
			$this->assertSame( 2, $restriction->getBlockId() );
		}
	}

	protected function insertBlock() {
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$block = new \Block( [
			'address' => $badActor->getName(),
			'user' => $badActor->getId(),
			'by' => $sysop->getId(),
			'expiry' => 'infinity',
			'sitewide' => 0,
			'enableAutoblock' => true,
		] );

		$block->insert();

		return $block;
	}

	protected function insertRestriction( $blockId, $type, $value ) {
		$this->db->insert( 'ipblocks_restrictions', [
			'ir_ipb_id' => $blockId,
			'ir_type' => $type,
			'ir_value' => $value,
		] );
	}

	protected function resetTables() {
		$this->db->delete( 'ipblocks', '*', __METHOD__ );
		$this->db->delete( 'ipblocks_restrictions', '*', __METHOD__ );
	}
}
