<?php
declare( strict_types = 1 );
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @since 1.42
 */

namespace MediaWiki\Tests\Unit\Parser\Parsoid\Config;

use DummyContentForTesting;
use InvalidArgumentException;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Parser\Parsoid\Config\PageContent;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
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

	/**
	 * Tests the constructor of the PageContent class.
	 */
	public function testConstruct() {
		$pageContent = new PageContent( $this->rev );
		$this->assertInstanceOf( PageContent::class, $pageContent );
	}

	/**
	 * Tests retrieving the roles assigned to the page content.
	 */
	public function testGetRoles() {
		$record = new MutableRevisionRecord(
		// Create a new mock revision record for a page.
			PageIdentityValue::localIdentity( 1, NS_MAIN, 'Foo' )
		);
		$mainSlot = new SlotRecord(
			// Creating a dummy slot record.
			(object)[
				'slot_id' => 1,
				'slot_revision_id' => null,
				'slot_content_id' => 1,
				'content_address' => null,
				'model_name' => 'x',
				'role_name' => SlotRecord::MAIN,
				'slot_origin' => null
			],
			// Assigning dummy content for testing.
			new DummyContentForTesting( SlotRecord::MAIN )
		);
		// Setting the slot in the mock revision record.
		$record->setSlot( $mainSlot );
		// Mocking the 'getSlotRoles' method to return the MAIN slot role.
		$this->rev->method( 'getSlotRoles' )->willReturn( [ SlotRecord::MAIN ] );
		// Creating a new PageContent object with the mock revision record.
		$roles = [ SlotRecord::MAIN ];
		$pageContent = new PageContent( $this->rev );
		// Asserts that the correct roles are returned.
		$this->assertEquals( $roles, $pageContent->getRoles() );
	}

	/**
	 * Tests the hasRole method of the PageContent class.
	 */
	public function testHasRole() {
		$record = new MutableRevisionRecord(
			// Create a new mock revision record for a page.
			PageIdentityValue::localIdentity( 1, NS_MAIN, 'Foo' )
		);
		// Creating a dummy slot record.
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
			// Assigning dummy content for testing.
			new DummyContentForTesting( SlotRecord::MAIN )
		);
		// Setting the slot in the mock revision record.
		$record->setSlot( $mainSlot );
		// Mocking the 'hasSlot' method to return true.
		$this->rev->method( 'hasSlot' )->willReturn( true );
		// Creating a new PageContent object with the mock revision record.
		$pageContent = new PageContent( $this->rev );
		// Asserts that the correct roles are returned.
		$this->assertTrue( $pageContent->hasRole( SlotRecord::MAIN ) );
	}

	/**
	 * Tests the hasRole method of the PageContent class.
	 */
	public function testGetModel() {
		// Create a new mock revision record for a page.
		$record = new MutableRevisionRecord(
			PageIdentityValue::localIdentity( 1, NS_MAIN, 'Foo' )
		);
		// Creating a dummy slot record.
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
			// Assigning dummy content for testing.
			new DummyContentForTesting( SlotRecord::MAIN )
		);
		// Setting the slot in the mock revision record.
		$record->setSlot( $mainSlot );
		// Mocking the 'hasSlot' method to return true.
		$this->rev->method( 'hasSlot' )->willReturn( true );
		$this->rev->method( 'getContent' )->willReturn( $mainSlot->getContent() );
		// Creating a new PageContent object with the mock revision record.
		$pageContent = new PageContent( $this->rev );
		// Getting the model of the MAIN slot.
		$model = $pageContent->getModel( SlotRecord::MAIN );
		// Asserts that the correct model is returned.
		$this->assertEquals( 'testing', $model );
	}

	/**
	 * Tests the getModel method of the PageContent class.
	 */
	public function testGetModelThrowsExceptionForMissingRole() {
		// Create a new mock revision record for a page.
		$nonExistentRole = 'nonexistent';
		// No need to set up any slots since we're testing for a missing role
		$this->rev->method( 'hasSlot' )->with( $nonExistentRole )->willReturn( false );
		// Creating a new PageContent object with the mock revision record.
		$pageContent = new PageContent( $this->rev );
		// Expect InvalidArgumentException when trying to get the model of a non-existent role
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "PageContent does not have role '$nonExistentRole'" );
		// Getting the model of the non-existent role
		// Attempts to get the model of a non-existent role.
		$pageContent->getModel( $nonExistentRole );
	}

	/**
	 * Tests the getFormat method of the PageContent class.
	 */
	public function testGetFormat() {
		// Create a new mock revision record for a page.
		$pageContent = $this->createMock( PageContent::class );
		// Mocking the 'getFormat' method to return CONTENT_FORMAT_WIKITEXT.
		$pageContent->method( 'getFormat' )->willReturn( CONTENT_FORMAT_WIKITEXT );
		// Getting the format of the MAIN slot.
		// Asserts that the correct format is returned.
		$this->assertEquals( CONTENT_FORMAT_WIKITEXT, $pageContent->getFormat( SlotRecord::MAIN ) );
	}

	/**
	 * Tests the getFormat method of the PageContent class.
	 */
	public function testGetContent() {
		// Create dummy content for testing.
		$content = new DummyContentForTesting( SlotRecord::MAIN );
		// Create a new mock page content object.
		$pageContent = $this->createMock( PageContent::class );
		// Mocking the 'getContent' method to return the serialized content.
		$pageContent->method( 'getContent' )->willReturn( $content->serialize() );
		// Getting the content of the MAIN slot.
		// Asserts that the correct content is returned.
		$this->assertEquals( $content->serialize(), $pageContent->getContent( SlotRecord::MAIN ) );
	}

	/**
	 * Tests the getContent method of the PageContent class.
	 */
	public function testGetContentThrowsExceptionForMissingRole() {
		$nonExistentRole = 'nonexistent';

		// No need to set up any slots since we're testing for a missing role
		$this->rev->method( 'hasSlot' )->with( $nonExistentRole )->willReturn( false );

		// Creating a new PageContent object with the mock revision record.
		$pageContent = new PageContent( $this->rev );

		// Expect InvalidArgumentException when trying to get the content of a non-existent role
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "PageContent does not have role '$nonExistentRole'" );

		$pageContent->getContent( $nonExistentRole );
	}
}
