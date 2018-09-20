<?php
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\SqlBlobStore;
use MediaWiki\Tests\Revision\PreMcrSchemaOverride;

/**
 * Test class for page archiving, using the pre-MCR schema.
 *
 * @group ContentHandler
 * @group Database
 * ^--- important, causes temporary tables to be used instead of the real database
 *
 * @group medium
 * ^--- important, causes tests not to fail with timeout
 */
class PageArchivePreMcrTest extends PageArchiveTestBase {

	use PreMcrSchemaOverride;

	/**
	 * @covers PageArchive::getTextFromRow
	 */
	public function testGetTextFromRow() {
		$this->hideDeprecated( PageArchive::class . '::getTextFromRow' );

		/** @var SqlBlobStore $blobStore */
		$blobStore = MediaWikiServices::getInstance()->getBlobStore();

		$textId = $blobStore->getTextIdFromAddress(
			$this->firstRev->getSlot( SlotRecord::MAIN )->getAddress()
		);

		$row = (object)[ 'ar_text_id' => $textId ];
		$text = $this->archivedPage->getTextFromRow( $row );
		$this->assertSame( 'testing', $text );
	}

	protected function getExpectedArchiveRows() {
		/** @var SqlBlobStore $blobStore */
		$blobStore = MediaWikiServices::getInstance()->getBlobStore();

		return [
			[
				'ar_minor_edit' => '0',
				'ar_user' => '0',
				'ar_user_text' => $this->ipEditor,
				'ar_actor' => null,
				'ar_len' => '11',
				'ar_deleted' => '0',
				'ar_rev_id' => strval( $this->ipRev->getId() ),
				'ar_timestamp' => $this->db->timestamp( $this->ipRev->getTimestamp() ),
				'ar_sha1' => '0qdrpxl537ivfnx4gcpnzz0285yxryy',
				'ar_page_id' => strval( $this->ipRev->getPageId() ),
				'ar_comment_text' => 'just a test',
				'ar_comment_data' => null,
				'ar_comment_cid' => null,
				'ar_content_format' => null,
				'ar_content_model' => null,
				'ts_tags' => null,
				'ar_id' => '2',
				'ar_namespace' => '0',
				'ar_title' => 'PageArchiveTest_thePage',
				'ar_text_id' => (string)$blobStore->getTextIdFromAddress(
					$this->ipRev->getSlot( SlotRecord::MAIN )->getAddress()
				),
				'ar_parent_id' => strval( $this->ipRev->getParentId() ),
			],
			[
				'ar_minor_edit' => '0',
				'ar_user' => (string)$this->getTestUser()->getUser()->getId(),
				'ar_user_text' => $this->getTestUser()->getUser()->getName(),
				'ar_actor' => null,
				'ar_len' => '7',
				'ar_deleted' => '0',
				'ar_rev_id' => strval( $this->firstRev->getId() ),
				'ar_timestamp' => $this->db->timestamp( $this->firstRev->getTimestamp() ),
				'ar_sha1' => 'pr0s8e18148pxhgjfa0gjrvpy8fiyxc',
				'ar_page_id' => strval( $this->firstRev->getPageId() ),
				'ar_comment_text' => 'testing',
				'ar_comment_data' => null,
				'ar_comment_cid' => null,
				'ar_content_format' => null,
				'ar_content_model' => null,
				'ts_tags' => null,
				'ar_id' => '1',
				'ar_namespace' => '0',
				'ar_title' => 'PageArchiveTest_thePage',
				'ar_text_id' => (string)$blobStore->getTextIdFromAddress(
					$this->firstRev->getSlot( SlotRecord::MAIN )->getAddress()
				),
				'ar_parent_id' => '0',
			],
		];
	}

}
