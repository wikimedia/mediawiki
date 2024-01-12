<?php

use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use Psr\Log\NullLogger;

/**
 * @group Database
 * @covers ImportableOldRevisionImporter
 */
class ImportableOldRevisionImporterTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		ChangeTags::defineTag( 'tag1' );
	}

	public static function provideTestCases() {
		yield [ [] ];
		yield [ [ "tag1" ] ];
	}

	/**
	 * @dataProvider provideTestCases
	 */
	public function testImport( $expectedTags ) {
		$services = $this->getServiceContainer();

		$title = Title::newFromText( __CLASS__ . rand() );
		$revision = new WikiRevision();
		$revision->setTitle( $title );
		$revision->setTags( $expectedTags );
		$content = ContentHandler::makeContent( 'dummy edit', $title );
		$revision->setContent( SlotRecord::MAIN, $content );

		$importer = new ImportableOldRevisionImporter(
			true,
			new NullLogger(),
			$services->getDBLoadBalancerFactory(),
			$services->getRevisionStore(),
			$services->getSlotRoleRegistry(),
			$services->getWikiPageFactory(),
			$services->getPageUpdaterFactory(),
			$services->getUserFactory()
		);
		$result = $importer->import( $revision );
		$this->assertTrue( $result );

		$tags = ChangeTags::getTags(
			$this->getDb(),
			null,
			$title->getLatestRevID()
		);
		$this->assertSame( $expectedTags, $tags );
	}
}
