<?php

use MediaWiki\MediaWikiServices;
use Psr\Log\NullLogger;

/**
 * @group Database
 * @coversDefaultClass ImportableOldRevisionImporter
 */
class ImportableOldRevisionImporterTest extends MediaWikiIntegrationTestCase {

	public function setUp() {
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
		$revision->setText( "dummy edit" );

		$importer = new ImportableOldRevisionImporter(
			true,
			new NullLogger(),
			$services->getDBLoadBalancer()
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
