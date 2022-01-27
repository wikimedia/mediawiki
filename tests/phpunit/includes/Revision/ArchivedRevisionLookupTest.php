<?php

namespace MediaWiki\Tests\Revision;

use CommentStoreComment;
use ContentHandler;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWikiIntegrationTestCase;
use MWTimestamp;
use WikiPage;

/**
 * @group Database
 * @coversDefaultClass \MediaWiki\Revision\ArchivedRevisionLookup
 * @covers ::__construct
 */
class ArchivedRevisionLookupTest extends MediaWikiIntegrationTestCase {

	/**
	 * @var int
	 */
	protected $pageId;

	/**
	 * @var PageIdentityValue
	 */
	protected $archivedPage;

	/**
	 * @var PageIdentityValue
	 */
	protected $neverExistingPage;

	/**
	 * Revision of the first (initial) edit
	 * @var RevisionRecord
	 */
	protected $firstRev;

	/**
	 * Revision of the second edit
	 * @var RevisionRecord
	 */
	protected $secondRev;

	protected function addCoreDBData() {
		// Blanked out to keep auto-increment values stable.
	}

	protected function setUp(): void {
		parent::setUp();

		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[
				'page',
				'revision',
				'revision_comment_temp',
				'ip_changes',
				'text',
				'archive',
				'recentchanges',
				'logging',
				'page_props',
				'comment',
				'slots',
				'content',
				'content_models',
				'slot_roles',
			]
		);

		$timestamp = 1635000000;
		MWTimestamp::setFakeTime( $timestamp );

		$this->neverExistingPage = PageIdentityValue::localIdentity(
			0, 0, 'ArchivedRevisionLookupTest_theNeverexistingPage' );

		// First create our dummy page
		$this->archivedPage = PageIdentityValue::localIdentity( 0, 0, 'ArchivedRevisionLookupTest_thePage' );
		$page = new WikiPage( $this->archivedPage );
		$content = ContentHandler::makeContent(
			'testing',
			$page->getTitle(),
			CONTENT_MODEL_WIKITEXT
		);

		$user = $this->getTestUser()->getUser();
		$page->doUserEditContent( $content, $user, 'testing', EDIT_NEW | EDIT_SUPPRESS_RC );

		$this->pageId = $page->getId();
		$this->firstRev = $page->getRevisionRecord();

		$timestamp += 10;

		$revisionStore = $this->getServiceContainer()->getRevisionStore();

		$newContent = ContentHandler::makeContent(
			'Lorem Ipsum',
			$page->getTitle(),
			CONTENT_MODEL_WIKITEXT
		);

		$rev = new MutableRevisionRecord( $page );
		$rev->setUser( $user );
		$rev->setTimestamp( $timestamp );
		$rev->setContent( SlotRecord::MAIN, $newContent );
		$rev->setComment( CommentStoreComment::newUnsavedComment( 'just a test' ) );

		$dbw = wfGetDB( DB_PRIMARY );
		$this->secondRev = $revisionStore->insertRevisionOn( $rev, $dbw );

		// Delete the page
		$timestamp += 10;
		MWTimestamp::setFakeTime( $timestamp );
		$this->deletePage( $page, '', $user );
	}

	protected function getExpectedArchiveRows() {
		return [
			[
				'ar_minor_edit' => '0',
				'ar_user' => (string)$this->getTestUser()->getUser()->getId(),
				'ar_user_text' => $this->getTestUser()->getUser()->getName(),
				'ar_actor' => (string)$this->getTestUser()->getUser()->getActorId(),
				'ar_len' => '11',
				'ar_deleted' => '0',
				'ar_rev_id' => strval( $this->secondRev->getId() ),
				'ar_timestamp' => $this->db->timestamp( $this->secondRev->getTimestamp() ),
				'ar_sha1' => '0qdrpxl537ivfnx4gcpnzz0285yxryy',
				'ar_page_id' => strval( $this->secondRev->getPageId() ),
				'ar_comment_text' => 'just a test',
				'ar_comment_data' => null,
				'ar_comment_cid' => strval( $this->secondRev->getComment()->id ),
				'ts_tags' => null,
				'ar_id' => '2',
				'ar_namespace' => '0',
				'ar_title' => 'ArchivedRevisionLookupTest_thePage',
				'ar_parent_id' => strval( $this->secondRev->getParentId() ),
			],
			[
				'ar_minor_edit' => '0',
				'ar_user' => (string)$this->getTestUser()->getUser()->getId(),
				'ar_user_text' => $this->getTestUser()->getUser()->getName(),
				'ar_actor' => (string)$this->getTestUser()->getUser()->getActorId(),
				'ar_len' => '7',
				'ar_deleted' => '0',
				'ar_rev_id' => strval( $this->firstRev->getId() ),
				'ar_timestamp' => $this->db->timestamp( $this->firstRev->getTimestamp() ),
				'ar_sha1' => 'pr0s8e18148pxhgjfa0gjrvpy8fiyxc',
				'ar_page_id' => strval( $this->firstRev->getPageId() ),
				'ar_comment_text' => 'testing',
				'ar_comment_data' => null,
				'ar_comment_cid' => strval( $this->firstRev->getComment()->id ),
				'ts_tags' => null,
				'ar_id' => '1',
				'ar_namespace' => '0',
				'ar_title' => 'ArchivedRevisionLookupTest_thePage',
				'ar_parent_id' => '0',
			],
		];
	}

	/**
	 * @covers ::listRevisions
	 */
	public function testListRevisions() {
		$lookup = $this->getServiceContainer()->getArchivedRevisionLookup();
		$revisions = $lookup->listRevisions( $this->archivedPage );
		$this->assertEquals( 2, $revisions->numRows() );
		// Get the rows as arrays
		$row0 = (array)$revisions->current();
		$row1 = (array)$revisions->next();

		$expectedRows = $this->getExpectedArchiveRows();

		$this->assertEquals(
			$expectedRows[0],
			$row0
		);
		$this->assertEquals(
			$expectedRows[1],
			$row1
		);
	}

	/**
	 * @covers ::listRevisions
	 */
	public function testListRevisions_slots() {
		$lookup = $this->getServiceContainer()->getArchivedRevisionLookup();
		$revisions = $lookup->listRevisions( $this->archivedPage );

		$revisionStore = $this->getServiceContainer()->getRevisionStore();
		$slotsQuery = $revisionStore->getSlotsQueryInfo( [ 'content' ] );

		foreach ( $revisions as $row ) {
			$this->assertSelect(
				$slotsQuery['tables'],
				'count(*)',
				[ 'slot_revision_id' => $row->ar_rev_id ],
				[ [ 1 ] ],
				[],
				$slotsQuery['joins']
			);
		}
	}

	/**
	 * @covers ::getLastRevisionId
	 */
	public function testGetLastRevisionId() {
		$lookup = $this->getServiceContainer()->getArchivedRevisionLookup();
		$id = $lookup->getLastRevisionId( $this->archivedPage );
		$this->assertSame( $this->secondRev->getId(), $id );
		$this->assertFalse( $lookup->getLastRevisionId( $this->neverExistingPage ) );
	}

	/**
	 * @covers ::hasArchivedRevisions
	 */
	public function testHasArchivedRevisions() {
		$lookup = $this->getServiceContainer()->getArchivedRevisionLookup();
		$this->assertTrue( $lookup->hasArchivedRevisions( $this->archivedPage ) );
		$this->assertFalse( $lookup->hasArchivedRevisions( $this->neverExistingPage ) );
	}

	/**
	 * @covers ::getRevisionRecordByTimestamp
	 * @covers ::getRevisionByConditions
	 */
	public function testGetRevisionRecordByTimestamp() {
		$lookup = $this->getServiceContainer()->getArchivedRevisionLookup();
		$revRecord = $lookup->getRevisionRecordByTimestamp(
			$this->archivedPage,
			$this->secondRev->getTimestamp()
		);
		$this->assertNotNull( $revRecord );
		$this->assertSame( $this->secondRev->getId(), $revRecord->getId() );

		$revRecord = $lookup->getRevisionRecordByTimestamp(
			$this->archivedPage,
			'22991212115555'
		);
		$this->assertNull( $revRecord );
	}

	/**
	 * @covers ::getArchivedRevisionRecord
	 * @covers ::getRevisionByConditions
	 */
	public function testGetArchivedRevisionRecord() {
		$lookup = $this->getServiceContainer()->getArchivedRevisionLookup();
		$revRecord = $lookup->getArchivedRevisionRecord(
			$this->archivedPage,
			$this->secondRev->getId()
		);
		$this->assertNotNull( $revRecord );
		$this->assertSame( $this->pageId, $revRecord->getPageId() );

		$revRecord = $lookup->getArchivedRevisionRecord(
			$this->archivedPage,
			$this->secondRev->getId() + 42
		);
		$this->assertNull( $revRecord );
	}

	/**
	 * @covers ::getPreviousRevisionRecord
	 * @covers ::getRevisionByConditions
	 */
	public function testGetPreviousRevisionRecord() {
		$lookup = $this->getServiceContainer()->getArchivedRevisionLookup();

		$timestamp = wfTimestamp( TS_UNIX, $this->secondRev->getTimestamp() ) + 1;
		$prevRec = $lookup->getPreviousRevisionRecord(
			$this->archivedPage,
			wfTimestamp( TS_MW, $timestamp )
		);
		$this->assertNotNull( $prevRec );
		$this->assertEquals( $this->secondRev->getId(), $prevRec->getId() );

		$prevRec = $lookup->getPreviousRevisionRecord(
			$this->archivedPage,
			wfTimestamp( TS_MW, $this->secondRev->getTimestamp() )
		);
		$this->assertNotNull( $prevRec );
		$this->assertEquals( $this->firstRev->getId(), $prevRec->getId() );

		$prevRec = $lookup->getPreviousRevisionRecord(
			$this->neverExistingPage,
			wfTimestamp( TS_MW, $this->secondRev->getTimestamp() )
		);
		$this->assertNull( $prevRec );
	}

	/**
	 * @covers ::getPreviousRevisionRecord
	 * @covers ::getRevisionByConditions
	 */
	public function testGetPreviousRevisionRecord_recreatedPage() {
		// recreate the archived page
		$timestamp = wfTimestamp( TS_UNIX, $this->secondRev->getTimestamp() ) + 10;
		MWTimestamp::setFakeTime( $timestamp );

		$page = new WikiPage( $this->archivedPage );

		$content = ContentHandler::makeContent(
			'recreated page',
			$page->getTitle(),
			CONTENT_MODEL_WIKITEXT
		);
		$page->doUserEditContent(
			$content,
			$this->getTestUser()->getUser(),
			'testing',
			EDIT_NEW | EDIT_SUPPRESS_RC
		);
		$newRev = $page->getRevisionRecord();

		$lookup = $this->getServiceContainer()->getArchivedRevisionLookup();
		$prevRec = $lookup->getPreviousRevisionRecord(
			$this->archivedPage,
			wfTimestamp( TS_MW, $timestamp + 1 )
		);
		$this->assertEquals( $newRev->getId(), $prevRec->getId() );

		$prevRec = $lookup->getPreviousRevisionRecord(
			$this->archivedPage,
			wfTimestamp( TS_MW, $timestamp - 1 )
		);
		$this->assertEquals( $this->secondRev->getId(), $prevRec->getId() );
	}

}
