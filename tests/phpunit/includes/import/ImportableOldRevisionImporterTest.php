<?php

use MediaWiki\Content\ContentHandler;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\PageUpdatedEvent;
use MediaWiki\Tests\Language\LanguageEventIngressSpyTrait;
use MediaWiki\Tests\recentchanges\ChangeTrackingEventIngressSpyTrait;
use MediaWiki\Tests\Search\SearchEventIngressSpyTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use PHPUnit\Framework\Assert;
use Psr\Log\NullLogger;

/**
 * @group Database
 * @covers \ImportableOldRevisionImporter
 */
class ImportableOldRevisionImporterTest extends MediaWikiIntegrationTestCase {
	use TempUserTestTrait;
	use ChangeTrackingEventIngressSpyTrait;
	use SearchEventIngressSpyTrait;
	use LanguageEventIngressSpyTrait;

	protected function setUp(): void {
		parent::setUp();

		$this->getServiceContainer()->getChangeTagsStore()->defineTag( 'tag1' );
	}

	public static function provideTestCases() {
		yield [ [] ];
		yield [ [ "tag1" ] ];
	}

	/**
	 * @param Title $title
	 *
	 * @return WikiRevision
	 * @throws MWContentSerializationException
	 * @throws MWUnknownContentModelException
	 */
	private function getWikiRevision( Title $title ): WikiRevision {
		$revision = new WikiRevision();
		$revision->setTitle( $title );
		$content = ContentHandler::makeContent( 'dummy edit', $title );
		$revision->setContent( SlotRecord::MAIN, $content );

		return $revision;
	}

	private function getImporter(): ImportableOldRevisionImporter {
		$services = $this->getServiceContainer();

		return new ImportableOldRevisionImporter(
			true,
			new NullLogger(),
			$services->getConnectionProvider(),
			$services->getRevisionStoreFactory()->getRevisionStoreForImport(),
			$services->getSlotRoleRegistry(),
			$services->getWikiPageFactory(),
			$services->getPageUpdaterFactory(),
			$services->getUserFactory(),
			$services->getDomainEventDispatcher()
		);
	}

	/**
	 * @dataProvider provideTestCases
	 */
	public function testImport( $expectedTags ) {
		$title = Title::newFromText( __CLASS__ . rand() );
		$revision = $this->getWikiRevision( $title );
		$revision->setTags( $expectedTags );

		$importer = $this->getImporter();
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

	/**
	 * Regression test for T381225
	 */
	public function testEventEmission() {
		$calls = 0;

		$title = Title::newFromText( __CLASS__ . rand() );
		$revision = $this->getWikiRevision( $title );

		$this->getServiceContainer()->getDomainEventSource()->registerListener(
			'PageUpdated',
			static function ( PageUpdatedEvent $event ) use ( &$calls ) {
				Assert::assertTrue(
					$event->hasFlag( PageUpdatedEvent::FLAG_IMPORTED ),
					PageUpdatedEvent::FLAG_IMPORTED
				);
				Assert::assertTrue(
					$event->hasFlag( PageUpdatedEvent::FLAG_SILENT ),
					PageUpdatedEvent::FLAG_SILENT
				);
				Assert::assertTrue(
					$event->hasFlag( PageUpdatedEvent::FLAG_AUTOMATED ),
					PageUpdatedEvent::FLAG_AUTOMATED
				);

				// TODO: assert more properties

				$calls++;
			}
		);

		// Perform an import
		$importer = $this->getImporter();
		$importer->import( $revision );

		$this->runDeferredUpdates();
		$this->assertSame( 1, $calls );
	}

	/**
	 * Regression test for T381225
	 */
	public function testEventPropagation() {
		// Make sure SearchEventIngress is triggered and tries to re-index the page
		$this->installChangeTrackingEventIngressSpyForImport();
		$this->installSearchEventIngressSpyForImport();
		$this->installLanguageEventIngressSpyForImport();

		// Now perform the import
		$title = Title::makeTitle( NS_MEDIAWIKI, __CLASS__ . rand() );
		$revision = $this->getWikiRevision( $title );
		$importer = $this->getImporter();
		$importer->import( $revision );
	}

}
