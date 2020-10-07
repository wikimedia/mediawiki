<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\SlotRecord;
use Psr\Log\NullLogger;

/**
 * @group Database
 * @coversDefaultClass ImportableOldRevisionImporter
 */
class ImportableOldRevisionImporterTest extends MediaWikiIntegrationTestCase {

	protected function setUp() : void {
		parent::setUp();

		$this->tablesUsed[] = 'change_tag';
		$this->tablesUsed[] = 'change_tag_def';

		ChangeTags::defineTag( 'tag1' );
	}

	public function provideTestCases() {
		yield [ [] ];
		yield [ [ "tag1" ] ];
	}

	/**
	 * @covers ::import
	 * @param $expectedTags
	 * @dataProvider provideTestCases
	 */
	public function testImport( $expectedTags ) {
		$services = MediaWikiServices::getInstance();

		$title = Title::newFromText( __CLASS__ . rand() );
		$revision = new WikiRevision( $services->getMainConfig() );
		$revision->setTitle( $title );
		$revision->setTags( $expectedTags );
		$content = ContentHandler::makeContent( 'dummy edit', $title );
		$revision->setContent( SlotRecord::MAIN, $content );

		$importer = new ImportableOldRevisionImporter(
			true,
			new NullLogger(),
			$services->getDBLoadBalancer(),
			$services->getRevisionStore(),
			$services->getSlotRoleRegistry()
		);
		$result = $importer->import( $revision );
		$this->assertTrue( $result );

		$page = WikiPage::factory( $title );
		$tags = ChangeTags::getTags(
			$services->getDBLoadBalancer()->getConnection( DB_MASTER ),
			null,
			$page->getLatest()
		);
		$this->assertSame( $expectedTags, $tags );
	}
}
