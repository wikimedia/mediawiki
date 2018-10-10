<?php
use MediaWiki\Tests\Revision\McrReadNewSchemaOverride;

/**
 * Tests WikiPage against the intermediate MCR DB schema for use during schema migration.
 *
 * @covers WikiPage
 *
 * @group WikiPage
 * @group Storage
 * @group ContentHandler
 * @group Database
 * @group medium
 */
class WikiPageMcrReadNewDbTest extends WikiPageDbTestBase {

	use McrReadNewSchemaOverride;

	protected function getContentHandlerUseDB() {
		return true;
	}

	public function testGetDeletionUpdates() {
		$m1 = $this->defineMockContentModelForUpdateTesting( 'M1' );
		$a1 = $this->defineMockContentModelForUpdateTesting( 'A1' );

		$mainContent1 = $this->createMockContent( $m1, 'main 1' );
		$auxContent1 = $this->createMockContent( $a1, 'aux 1' );

		$page = new WikiPage( Title::newFromText( __METHOD__ ) );
		$page = $this->createPage(
			$page,
			[ 'main' => $mainContent1, 'aux' => $auxContent1 ]
		);

		$dataUpdates = $page->getDeletionUpdates( $page->getRevisionRecord() );
		$this->assertNotEmpty( $dataUpdates );

		$updateNames = array_map( function ( $du ) {
			return isset( $du->_name ) ? $du->_name : get_class( $du );
		}, $dataUpdates );

		$this->assertContains( LinksDeletionUpdate::class, $updateNames );
		$this->assertContains( 'M1 deletion update', $updateNames );
		$this->assertContains( 'A1 deletion update', $updateNames );
	}

}
