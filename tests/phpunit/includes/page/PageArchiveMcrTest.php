<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Tests\Revision\McrSchemaOverride;

/**
 * Test class for page archiving, using the new MCR schema.
 *
 * @group ContentHandler
 * @group Database
 * ^--- important, causes temporary tables to be used instead of the real database
 *
 * @group medium
 * ^--- important, causes tests not to fail with timeout
 */
class PageArchiveMcrTest extends PageArchiveTestBase {

	use McrSchemaOverride;

	/**
	 * @covers PageArchive::listRevisions
	 */
	public function testListRevisions_slots() {
		$revisions = $this->archivedPage->listRevisions();

		$revisionStore = MediaWikiServices::getInstance()->getInstance()->getRevisionStore();
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

	protected function getExpectedArchiveRows() {
		return [
			[
				'ar_minor_edit' => '0',
				'ar_user' => null,
				'ar_user_text' => $this->ipEditor,
				'ar_actor' => (string)User::newFromName( $this->ipEditor, false )->getActorId( $this->db ),
				'ar_len' => '11',
				'ar_deleted' => '0',
				'ar_rev_id' => strval( $this->ipRev->getId() ),
				'ar_timestamp' => $this->db->timestamp( $this->ipRev->getTimestamp() ),
				'ar_sha1' => '0qdrpxl537ivfnx4gcpnzz0285yxryy',
				'ar_page_id' => strval( $this->ipRev->getPageId() ),
				'ar_comment_text' => 'just a test',
				'ar_comment_data' => null,
				'ar_comment_cid' => '2',
				'ts_tags' => null,
				'ar_id' => '2',
				'ar_namespace' => '0',
				'ar_title' => 'PageArchiveTest_thePage',
				'ar_parent_id' => strval( $this->ipRev->getParentId() ),
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
				'ar_comment_cid' => '1',
				'ts_tags' => null,
				'ar_id' => '1',
				'ar_namespace' => '0',
				'ar_title' => 'PageArchiveTest_thePage',
				'ar_parent_id' => '0',
			],
		];
	}

}
