<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\ChangeTags;

use InvalidArgumentException;
use MediaWiki\ChangeTags\ChangeTagsList;
use MediaWiki\ChangeTags\ChangeTagsLogList;
use MediaWiki\ChangeTags\ChangeTagsRevisionList;
use MediaWiki\Context\IContextSource;
use MediaWiki\Page\PageIdentity;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\ChangeTags\ChangeTagsList
 * @group ChangeTag
 * @group Database
 */
class ChangeTagsListTest extends MediaWikiIntegrationTestCase {

	public function testFactoryWithRevision() {
		$context = $this->createMock( IContextSource::class );
		$page = $this->createMock( PageIdentity::class );
		$ids = [ 1, 2, 3 ];

		// Instantiate a ChangeTagsList with revision type.
		$revisionList = ChangeTagsList::factory( 'revision', $context, $page, $ids );

		// Assert that the returned list is an instance of ChangeTagsRevisionList.
		$this->assertInstanceOf( ChangeTagsRevisionList::class, $revisionList );
	}

	public function testFactoryWithLogentry() {
		$context = $this->createMock( IContextSource::class );
		$page = $this->createMock( PageIdentity::class );
		$ids = [ 1, 2, 3 ];

		// Instantiate a ChangeTagsList with logentry type.
		$logList = ChangeTagsList::factory( 'logentry', $context, $page, $ids );

		// Assert that the returned list is an instance of ChangeTagsLogList.
		$this->assertInstanceOf( ChangeTagsLogList::class, $logList );
	}

	public function testFactoryWithUnknownType() {
		$this->expectException( InvalidArgumentException::class );
		$context = $this->createMock( IContextSource::class );
		$page = $this->createMock( PageIdentity::class );
		$ids = [];

		// Instantiate a ChangeTagsList with an unknown type.
		ChangeTagsList::factory( 'unknownType', $context, $page, $ids );
	}

	public function testUpdateChangeTagsOnAll() {
		$this->expectException( InvalidArgumentException::class );
		$context = $this->createMock( IContextSource::class );
		$page = $this->createMock( PageIdentity::class );
		$ids = [];

		// Instantiate a ChangeTagsList.
		$changeTagsList = ChangeTagsList::factory( 'revision', $context, $page, $ids );

		// Mock an Authority (e.g., a User with appropriate permissions).
		$user = $this->getTestUser()->getUser();
		$authority = $user;

		// Mock the tags to add and remove, as well as the reason for the change.
		$tagsToAdd = [ 'mockTagToAddOne', 'mockTagToAddTwo' ];
		$tagsToRemove = [ 'mockTagToRemoveOne', 'mockTagToRemoveTwo' ];
		$params = null;
		$reason = "Test reason for changing tags";

		// Attempt to update the change tags on all items in the list.
		$status = $changeTagsList->updateChangeTagsOnAll( $tagsToAdd, $tagsToRemove, $params, $reason, $authority );

		// Assert that the operation was successful.
		$this->assertStatusOK( $status, 'Updating change tags failed' );
	}

}
