<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Page\Event\PageCreatedEvent;
use MediaWiki\Page\Event\PageRevisionUpdatedEvent;
use MediaWiki\Tests\ExpectCallbackTrait;
use MediaWiki\Tests\recentchanges\ChangeTrackingUpdateSpyTrait;
use MediaWiki\Tests\Search\SearchUpdateSpyTrait;
use PHPUnit\Framework\Assert;

/**
 * Tests for MediaWiki api.php?action=import.
 *
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiImport
 */
class ApiImportTest extends ApiUploadTestCase {
	use ChangeTrackingUpdateSpyTrait;
	use SearchUpdateSpyTrait;
	use ExpectCallbackTrait;

	public function tearDown(): void {
		// phpcs:ignore MediaWiki.Usage.SuperGlobalsUsage.SuperGlobals
		unset( $_FILES['xml'] );

		parent::tearDown();
	}

	public function testImport() {
		// Clear some extension hook handlers that may interfere with mock object expectations.
		$this->clearHooks( [
			'RevisionRecordInserted',
			'PageSaveComplete',
			'LinksUpdateComplete',
		] );

		$title = $this->getNonexistingTestPage()->getTitle();

		// We expect two PageRevisionUpdated events, one triggered by
		// ImportableOldRevisionImporter when importing the latest revision;
		// And one triggered by ApiImportReporter when creating a dummy revision.
		// NOTE: it's not clear whether this is intentional or desirable!

		$calls = 0;

		// Declare expectations
		$this->expectDomainEvent(
			PageRevisionUpdatedEvent::TYPE, 2,
			static function ( PageRevisionUpdatedEvent $event ) use ( &$calls, $title ) {
				$calls++;

				Assert::assertTrue( $event->getPage()->isSamePageAs( $title ) );

				Assert::assertTrue(
					$event->hasCause( PageRevisionUpdatedEvent::CAUSE_IMPORT ),
					PageRevisionUpdatedEvent::CAUSE_IMPORT
				);

				Assert::assertTrue( $event->isSilent(), 'isSilent' );
				Assert::assertTrue( $event->isImplicit(), 'isImplicit' );

				if ( $calls === 1 ) {
					// First call, from ImportableOldRevisionImporter
					Assert::assertTrue( $event->isCreation(), 'isCreation' );
					Assert::assertTrue( $event->changedLatestRevisionId(), 'changedLatestRevisionId' );
					Assert::assertTrue( $event->isEffectiveContentChange(), 'isEffectiveContentChange' );
					Assert::assertTrue( $event->isNominalContentChange(), 'isNominalContentChange' );
					Assert::assertTrue( $event->isSilent(), 'isSilent' );

					Assert::assertFalse(
						$event->getLatestRevisionAfter()->isMinor(),
						'isMinor'
					);
				} else {
					// Second call, from ApiImportReporter
					Assert::assertFalse( $event->isCreation(), 'isCreation' );
					Assert::assertTrue( $event->changedLatestRevisionId(), 'changedLatestRevisionId' );
					Assert::assertFalse( $event->isEffectiveContentChange(), 'isEffectiveContentChange' );
					Assert::assertFalse( $event->isNominalContentChange(), 'isNominalContentChange' );
					Assert::assertTrue( $event->isSilent(), 'isSilent' );

					Assert::assertTrue(
						$event->getLatestRevisionAfter()->isMinor(),
						'isMinor'
					);
				}
			}
		);

		$this->expectDomainEvent( PageCreatedEvent::TYPE, 1 );

		// Hooks fired by PageUpdater
		$this->expectHook( 'RevisionFromEditComplete', 1 );
		$this->expectHook( 'PageSaveComplete', 1 );

		// Expect only non-edit recent changes entry, but no edit count
		// or user talk.
		$this->expectChangeTrackingUpdates( 0, 1, 0, 0, 1 );

		// Expect search updates to be triggered
		$this->expectSearchUpdates( 1 );

		// Prepare the fake upload XML
		$dumpData = file_get_contents( __DIR__ . '/../../data/import/Basic.import-1.xml' );
		$dumpData = str_replace(
			'{{page1_title}}',
			$title->getPrefixedDBkey(),
			$dumpData
		);

		// NOTE: We can't use $this->fakeUploadChunk(), because ImportStreamSource
		//       doesn't use WebRequest but hits $_FILES directly.
		$tmpName = $this->getNewTempFile();
		file_put_contents( $tmpName, $dumpData );

		// phpcs:ignore MediaWiki.Usage.SuperGlobalsUsage.SuperGlobals
		$_FILES['xml'] = [
			'name' => 'xml',
			'type' => 'application/xml',
			'tmp_name' => $tmpName,
			'size' => strlen( $dumpData ),
			'error' => UPLOAD_ERR_OK,
		];

		// Do the import
		$admin = $this->getTestSysop()->getAuthority();
		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'import',
			'interwikiprefix' => 'test'
		], null, $admin )[0];

		// check response
		$this->assertArrayHasKey( 'import', $apiResult );
		$this->assertArrayHasKey( 0, $apiResult['import'] );
		$this->assertArrayHasKey( 'title', $apiResult['import'][0] );
		$this->assertSame( $title->getText(), $apiResult['import'][0]['title'] );
		$this->assertArrayHasKey( 'revisions', $apiResult['import'][0] );
		$this->assertSame( 1, $apiResult['import'][0]['revisions'] );

		// check that the page exists now
		$page = $this->getServiceContainer()->getPageStore()
			->getPageByReference( $title );

		$this->assertTrue( $page->exists() );
	}
}
