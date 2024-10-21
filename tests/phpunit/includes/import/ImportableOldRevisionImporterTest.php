<?php

use MediaWiki\Content\ContentHandler;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use Psr\Log\NullLogger;

/**
 * @group Database
 * @covers \ImportableOldRevisionImporter
 */
class ImportableOldRevisionImporterTest extends MediaWikiIntegrationTestCase {
	use TempUserTestTrait;

	protected function setUp(): void {
		parent::setUp();

		$this->getServiceContainer()->getChangeTagsStore()->defineTag( 'tag1' );
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
			$services->getConnectionProvider(),
			$services->getRevisionStoreFactory()->getRevisionStoreForImport(),
			$services->getSlotRoleRegistry(),
			$services->getWikiPageFactory(),
			$services->getPageUpdaterFactory(),
			$services->getUserFactory()
		);
		$result = $importer->import( $revision );
		$this->assertTrue( $result );

		$tags = $this->getServiceContainer()->getChangeTagsStore()->getTags(
			$this->getDb(),
			null,
			$title->getLatestRevID()
		);
		$this->assertSame( $expectedTags, $tags );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStoreFactory::getRevisionStoreForImport
	 * @covers \MediaWiki\User\ActorMigration::newMigrationForImport
	 * @covers \MediaWiki\User\ActorStoreFactory::getActorStoreForImport
	 * @covers \MediaWiki\User\ActorStoreFactory::getActorNormalizationForImport
	 * @dataProvider provideTestCases
	 */
	public function testImportWithTempAccountsEnabled( $expectedTags ) {
		$this->enableAutoCreateTempUser();
		$this->testImport( $expectedTags );
	}
}
