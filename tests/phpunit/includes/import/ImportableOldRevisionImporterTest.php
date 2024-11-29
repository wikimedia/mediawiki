<?php

use MediaWiki\Content\ContentHandler;
use MediaWiki\Page\Event\PageUpdatedEvent;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\Language\LocalizationUpdateSpyTrait;
use MediaWiki\Tests\recentchanges\ChangeTrackingUpdateSpyTrait;
use MediaWiki\Tests\ResourceLoader\ResourceLoaderUpdateSpyTrait;
use MediaWiki\Tests\Search\SearchUpdateSpyTrait;
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
	use ChangeTrackingUpdateSpyTrait;
	use SearchUpdateSpyTrait;
	use LocalizationUpdateSpyTrait;
	use ResourceLoaderUpdateSpyTrait;

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

	private function makeDomainEventSourceListener(
		int &$counter,
		$new
	) {
		return static function ( PageUpdatedEvent $event ) use (
			&$counter, $new
		) {
			Assert::assertTrue( $event->isRevisionChange(), 'isPurge' );
			Assert::assertSame( $new, $event->isNew(), 'isNew' );
			Assert::assertSame( PageUpdatedEvent::CAUSE_IMPORT, $event->getCause(), 'getCause' );

			Assert::assertTrue( $event->isAutomated(), 'isAutomated' );
			Assert::assertTrue( $event->isSilent(), 'isSilent' );

			$counter++;
		};
	}

	/**
	 * Check that importing revisions for a non-existing page emits a
	 * PageUpdatedEvent indicating page creation.
	 */
	public function testEventEmission_new() {
		$calls = 0;

		$title = Title::newFromText( __CLASS__ . rand() );

		$this->getServiceContainer()->getDomainEventSource()->registerListener(
			'PageUpdated',
			$this->makeDomainEventSourceListener( $calls, true )
		);

		// Perform an import
		$importer = $this->getImporter();

		$revision = $this->getWikiRevision( $title );
		$importer->import( $revision );

		$this->runDeferredUpdates();
		$this->assertSame( 1, $calls );
	}

	/**
	 * Check that importing an old revision for an existing page does not emit
	 * a PageUpdatedEvent.
	 */
	public function testEventEmission_old() {
		$calls = 0;

		$page = $this->getExistingTestPage();
		$title = $page->getTitle();

		$this->getServiceContainer()->getDomainEventSource()->registerListener(
			'PageUpdated',
			$this->makeDomainEventSourceListener( $calls, false )
		);

		// Import an old revision
		$importer = $this->getImporter();

		$revision = $this->getWikiRevision( $title );
		$revision->setTimestamp( '20110101223344' );
		$importer->import( $revision );

		$this->runDeferredUpdates();
		$this->assertSame( 0, $calls );
	}

	/**
	 * Check that importing a new revision for an existing page emits
	 * a PageUpdatedEvent.
	 */
	public function testEventEmission_current() {
		$calls = 0;

		MWTimestamp::setFakeTime( '20110101223344' );
		$page = $this->getExistingTestPage();
		$title = $page->getTitle();

		$this->getServiceContainer()->getDomainEventSource()->registerListener(
			'PageUpdated',
			$this->makeDomainEventSourceListener( $calls, false )
		);

		// Import latest revision
		$importer = $this->getImporter();

		$revision = $this->getWikiRevision( $title );
		$revision->setTimestamp( '20240101223344' );
		$importer->import( $revision );

		$this->runDeferredUpdates();
		$this->assertSame( 1, $calls );
	}

	public static function provideUpdatePropagation() {
		$name = __METHOD__ . rand();

		yield 'article' => [ PageIdentityValue::localIdentity( 0, NS_MAIN, $name ) ];
		yield 'user talk' => [ PageIdentityValue::localIdentity( 0, NS_USER_TALK, $name ) ];
		yield 'message' => [ PageIdentityValue::localIdentity( 0, NS_MEDIAWIKI, $name ) ];
		yield 'script' => [ PageIdentityValue::localIdentity( 0, NS_USER, "$name/common.js" ) ];
	}

	/**
	 * Test update propagation.
	 * Includes regression test for T381225
	 *
	 * @dataProvider provideUpdatePropagation
	 */
	public function testUpdatePropagation( PageIdentity $title ) {
		$revision = $this->getWikiRevision( Title::castFromPageIdentity( $title ) );

		$this->expectChangeTrackingUpdates( 0, 0, 0, 0 );

		$this->expectSearchUpdates( 1 );
		$this->expectLocalizationUpdate( $title->getNamespace() === NS_MEDIAWIKI ? 1 : 0 );

		$this->expectResourceLoaderUpdates(
			$revision->getContent()->getModel() === CONTENT_MODEL_JAVASCRIPT ? 1 : 0
		);

		// Now perform the import
		$importer = $this->getImporter();
		$importer->import( $revision );
	}

}
