<?php
declare( strict_types = 1 );
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\Parser\Parsoid\Config;

use InvalidArgumentException;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Parser\Parsoid\Config\PageContent;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\Mocks\Content\DummyContentForTesting;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Parser\Parsoid\Config\PageContent
 */
class PageContentTest extends MediaWikiUnitTestCase {

	protected RevisionRecord $rev;

	protected function setUp(): void {
		parent::setUp();
		$this->rev = $this->createMock( RevisionRecord::class );
	}

	public function testConstruct() {
		$pageContent = new PageContent( $this->rev );
		$this->assertInstanceOf( PageContent::class, $pageContent );
	}

	public function testGetRoles() {
		$record = new MutableRevisionRecord(
			PageIdentityValue::localIdentity( 1, NS_MAIN, 'Foo' )
		);
		$mainSlot = new SlotRecord(
			(object)[
				'slot_id' => 1,
				'slot_revision_id' => null,
				'slot_content_id' => 1,
				'content_address' => null,
				'model_name' => 'x',
				'role_name' => SlotRecord::MAIN,
				'slot_origin' => null
			],
			new DummyContentForTesting( SlotRecord::MAIN )
		);
		$record->setSlot( $mainSlot );
		$this->rev->method( 'getSlotRoles' )->willReturn( [ SlotRecord::MAIN ] );
		$roles = [ SlotRecord::MAIN ];
		$pageContent = new PageContent( $this->rev );
		$this->assertEquals( $roles, $pageContent->getRoles() );
	}

	public function testHasRole() {
		$record = new MutableRevisionRecord(
			PageIdentityValue::localIdentity( 1, NS_MAIN, 'Foo' )
		);
		$mainSlot = new SlotRecord(
			(object)[
				'slot_id' => 1,
				'slot_revision_id' => null,
				'slot_content_id' => 1,
				'content_address' => null,
				'model_name' => 'x',
				'role_name' => SlotRecord::MAIN,
				'slot_origin' => null
			],
			new DummyContentForTesting( SlotRecord::MAIN )
		);
		$record->setSlot( $mainSlot );
		$this->rev->method( 'hasSlot' )->willReturn( true );
		$pageContent = new PageContent( $this->rev );
		$this->assertTrue( $pageContent->hasRole( SlotRecord::MAIN ) );
	}

	public function testGetModel() {
		$record = new MutableRevisionRecord(
			PageIdentityValue::localIdentity( 1, NS_MAIN, 'Foo' )
		);
		$mainSlot = new SlotRecord(
			(object)[
				'slot_id' => 1,
				'slot_revision_id' => null,
				'slot_content_id' => 1,
				'content_address' => null,
				'model_name' => 'testing',
				'role_name' => SlotRecord::MAIN,
				'slot_origin' => null
			],
			new DummyContentForTesting( SlotRecord::MAIN )
		);
		$record->setSlot( $mainSlot );
		$this->rev->method( 'hasSlot' )->willReturn( true );
		$this->rev->method( 'getContent' )->willReturn( $mainSlot->getContent() );
		$pageContent = new PageContent( $this->rev );
		$model = $pageContent->getModel( SlotRecord::MAIN );
		$this->assertEquals( 'testing', $model );
	}

	public function testGetModelThrowsExceptionForMissingRole() {
		$nonExistentRole = 'nonexistent';
		$this->rev->method( 'hasSlot' )->with( $nonExistentRole )->willReturn( false );
		$pageContent = new PageContent( $this->rev );
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "PageContent does not have role '$nonExistentRole'" );
		// Attempt to get the model of a non-existent role.
		$pageContent->getModel( $nonExistentRole );
	}

	public function testGetFormat() {
		$pageContent = $this->createMock( PageContent::class );
		$pageContent->method( 'getFormat' )->willReturn( CONTENT_FORMAT_WIKITEXT );
		$this->assertEquals( CONTENT_FORMAT_WIKITEXT, $pageContent->getFormat( SlotRecord::MAIN ) );
	}

	public function testGetContent() {
		$content = new DummyContentForTesting( SlotRecord::MAIN );
		$pageContent = $this->createMock( PageContent::class );
		$pageContent->method( 'getContent' )->willReturn( $content->serialize() );
		$this->assertEquals( $content->serialize(), $pageContent->getContent( SlotRecord::MAIN ) );
	}

	public function testGetContentThrowsExceptionForMissingRole() {
		$nonExistentRole = 'nonexistent';

		// No need to set up any slots since we're testing for a missing role
		$this->rev->method( 'hasSlot' )->with( $nonExistentRole )->willReturn( false );

		$pageContent = new PageContent( $this->rev );

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "PageContent does not have role '$nonExistentRole'" );
		$pageContent->getContent( $nonExistentRole );
	}
}
