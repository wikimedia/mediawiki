<?php
use MediaWiki\Tests\Revision\PreMcrSchemaOverride;

/**
 * Tests WikiPage against the pre-MCR, pre ContentHandler DB schema.
 *
 * @covers WikiPage
 *
 * @group WikiPage
 * @group Storage
 * @group ContentHandler
 * @group Database
 * @group medium
 */
class WikiPageNoContentModelDbTest extends WikiPageDbTestBase {

	use PreMcrSchemaOverride;

	protected function getContentHandlerUseDB() {
		return false;
	}

	public function testGetDeletionUpdates() {
		$mainContent1 = new WikitextContent( '' );

		$title = Title::makeTitle( $this->getDefaultWikitextNS(), __METHOD__ );
		$page = new WikiPage( $title );
		$page = $this->createPage(
			$page,
			[ 'main' => $mainContent1 ]
		);

		$dataUpdates = $page->getDeletionUpdates( $page->getRevisionRecord() );
		$this->assertNotEmpty( $dataUpdates );

		$updateNames = array_map( function ( $du ) {
			return isset( $du->_name ) ? $du->_name : get_class( $du );
		}, $dataUpdates );

		$this->assertContains( LinksDeletionUpdate::class, $updateNames );
	}

}
