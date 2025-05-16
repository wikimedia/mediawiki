<?php

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Message\Message;
use MediaWiki\Page\Event\PageDeletedEvent;
use MediaWiki\Page\Event\PageRevisionUpdatedEvent;
use MediaWiki\Page\MergeHistory;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Tests\ExpectCallbackTrait;
use MediaWiki\Tests\Language\LocalizationUpdateSpyTrait;
use MediaWiki\Tests\recentchanges\ChangeTrackingUpdateSpyTrait;
use MediaWiki\Tests\ResourceLoader\ResourceLoaderUpdateSpyTrait;
use MediaWiki\Tests\Search\SearchUpdateSpyTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\Title;
use MediaWiki\Utils\MWTimestamp;

/**
 * @group Database
 */
class MergeHistoryTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;
	use ChangeTrackingUpdateSpyTrait;
	use SearchUpdateSpyTrait;
	use LocalizationUpdateSpyTrait;
	use ResourceLoaderUpdateSpyTrait;
	use ExpectCallbackTrait;

	private const NS_WITHOUT_REDIRECTS = 2030;
	private const CM_TESTING = 'testing';

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::ExtraNamespaces => [
				self::NS_WITHOUT_REDIRECTS => 'NoRedirect',
				self::NS_WITHOUT_REDIRECTS + 1 => 'NoRedirect_talk'
			] + MainConfigSchema::getDefaultValue( MainConfigNames::ExtraNamespaces ),

			MainConfigNames::NamespaceContentModels => [
				self::NS_WITHOUT_REDIRECTS => self::CM_TESTING
			] + MainConfigSchema::getDefaultValue( MainConfigNames::NamespaceContentModels ),

			MainConfigNames::ContentHandlers => [
				// Relies on the DummyContentHandlerForTesting not
				// supporting redirects by default. If this ever gets
				// changed this test has to be fixed.
				self::CM_TESTING => DummyContentHandlerForTesting::class
			] + MainConfigSchema::getDefaultValue( MainConfigNames::ContentHandlers ),

			MainConfigNames::RCWatchCategoryMembership => true
		] );
	}

	/**
	 * Make some pages to work with
	 */
	public function addDBDataOnce() {
		// Pages that won't actually be merged
		$this->insertPage( 'Test' );
		$this->insertPage( 'Test2' );

		// Pages that will be merged
		$this->insertPage( 'Merge1' );
		$this->insertPage( 'Merge2' );
	}

	/**
	 * @dataProvider provideIsValidMerge
	 * @covers \MediaWiki\Page\MergeHistory::isValidMerge
	 * @param string $source Source page
	 * @param string $dest Destination page
	 * @param string|bool $timestamp Timestamp up to which revisions are merged (or false for all)
	 * @param string|bool $error Expected error for test (or true for no error)
	 */
	public function testIsValidMerge( $source, $dest, $timestamp, $error ) {
		if ( $timestamp === true ) {
			// Although this timestamp is after the latest timestamp of both pages,
			// MergeHistory should select the latest source timestamp up to this which should
			// still work for the merge.
			$timestamp = time() + ( 24 * 3600 );
		}
		$factory = $this->getServiceContainer()->getMergeHistoryFactory();
		$mh = $factory->newMergeHistory(
			Title::newFromText( $source ),
			Title::newFromText( $dest ),
			$timestamp
		);
		$status = $mh->isValidMerge();
		if ( $error === true ) {
			$this->assertStatusGood( $status );
		} else {
			$this->assertStatusError( $error, $status );
		}
	}

	public static function provideIsValidMerge() {
		return [
			// for MergeHistory::isValidMerge
			[ 'Test', 'Test2', false, true ],
			// Timestamp of `true` is a placeholder for "in the future""
			[ 'Test', 'Test2', true, true ],
			[ 'Test', 'Test', false, 'mergehistory-fail-self-merge' ],
			[ 'Nonexistant', 'Test2', false, 'mergehistory-fail-invalid-source' ],
			[ 'Test', 'Nonexistant', false, 'mergehistory-fail-invalid-dest' ],
			[
				'Test',
				'Test2',
				'This is obviously an invalid timestamp',
				'mergehistory-fail-bad-timestamp'
			],
		];
	}

	/**
	 * Test merge revision limit checking
	 * @covers \MediaWiki\Page\MergeHistory::isValidMerge
	 */
	public function testIsValidMergeRevisionLimit() {
		$limit = MergeHistory::REVISION_LIMIT;
		$mh = $this->getMockBuilder( MergeHistory::class )
			->onlyMethods( [ 'getRevisionCount' ] )
			->setConstructorArgs( [
				Title::makeTitle( NS_MAIN, 'Test' ),
				Title::makeTitle( NS_MAIN, 'Test2' ),
				null,
				$this->getServiceContainer()->getConnectionProvider(),
				$this->getServiceContainer()->getContentHandlerFactory(),
				$this->getServiceContainer()->getWatchedItemStore(),
				$this->getServiceContainer()->getSpamChecker(),
				$this->getServiceContainer()->getHookContainer(),
				$this->getServiceContainer()->getPageUpdaterFactory(),
				$this->getServiceContainer()->getTitleFormatter(),
				$this->getServiceContainer()->getTitleFactory(),
				$this->getServiceContainer()->getDeletePageFactory(),
			] )
			->getMock();
		$mh->expects( $this->once() )
			->method( 'getRevisionCount' )
			->willReturn( $limit + 1 );

		$status = $mh->isValidMerge();

		$this->assertStatusNotOK( $status );
		$this->assertStatusMessagesExactly(
			StatusValue::newFatal( 'mergehistory-fail-toobig', Message::numParam( $limit ) ),
			$status
		);
	}

	/**
	 * Test user permission checking
	 * @covers \MediaWiki\Page\MergeHistory::authorizeMerge
	 * @covers \MediaWiki\Page\MergeHistory::probablyCanMerge
	 */
	public function testCheckPermissions() {
		$factory = $this->getServiceContainer()->getMergeHistoryFactory();
		$mh = $factory->newMergeHistory(
			Title::makeTitle( NS_MAIN, 'Test' ),
			Title::makeTitle( NS_MAIN, 'Test2' )
		);

		foreach ( [ 'authorizeMerge', 'probablyCanMerge' ] as $method ) {
			// Sysop with mergehistory permission
			$status = $mh->$method(
				$this->mockRegisteredUltimateAuthority(),
				''
			);
			$this->assertStatusOK( $status );

			$status = $mh->$method(
				$this->mockRegisteredAuthorityWithoutPermissions( [ 'mergehistory' ] ),
				''
			);
			$this->assertStatusError( 'mergehistory-fail-permission', $status );
		}
	}

	/**
	 * Test merged revision count
	 * @covers \MediaWiki\Page\MergeHistory::getMergedRevisionCount
	 */
	public function testGetMergedRevisionCount() {
		$factory = $this->getServiceContainer()->getMergeHistoryFactory();
		$mh = $factory->newMergeHistory(
			Title::makeTitle( NS_MAIN, 'Merge1' ),
			Title::makeTitle( NS_MAIN, 'Merge2' )
		);

		$sysop = static::getTestSysop()->getUser();
		$mh->merge( $sysop );
		$this->assertSame( 1, $mh->getMergedRevisionCount() );
	}

	/**
	 * Test update to source page for pages with
	 * content model that supports redirects
	 *
	 * @covers \MediaWiki\Page\MergeHistory::merge
	 */
	public function testSourceUpdateWithRedirectSupport() {
		$title = Title::makeTitle( NS_MAIN, 'Merge5' );
		$title2 = Title::makeTitle( NS_MAIN, 'Merge6' );

		$this->insertPage( $title );
		$this->insertPage( $title2 );

		// Latest revision turned into redirect, no page deleted.
		$this->expectDomainEvent( PageRevisionUpdatedEvent::TYPE, 1 );
		$this->expectDomainEvent( PageDeletedEvent::TYPE, 0 );

		$factory = $this->getServiceContainer()->getMergeHistoryFactory();
		$mh = $factory->newMergeHistory( $title, $title2 );

		$this->assertTrue( $title->exists() );

		$status = $mh->merge( static::getTestSysop()->getUser() );
		$this->assertStatusOK( $status );

		$this->assertTrue( $title->exists() );

		$srcLog = $this->getLatestLogEntry( $title );
		$dstLog = $this->getLatestLogEntry( $title2 );

		$this->assertNotNull( $srcLog );
		$this->assertSame( 'merge/merge', $srcLog->getFullType() );

		$this->assertNotNull( $dstLog );
		$this->assertSame( 'merge/merge-into', $dstLog->getFullType() );
	}

	/**
	 * Test update to source page for pages with
	 * content model that does not support redirects
	 *
	 * @covers \MediaWiki\Page\MergeHistory::merge
	 */
	public function testSourceUpdateForNoRedirectSupport() {
		$title = Title::makeTitle( self::NS_WITHOUT_REDIRECTS, 'Merge3' );
		$title2 = Title::makeTitle( self::NS_WITHOUT_REDIRECTS, 'Merge4' );

		$this->insertPage( $title );
		$this->insertPage( $title2 );

		// Latest revision didn't change, page deleted.
		$this->expectDomainEvent( PageRevisionUpdatedEvent::TYPE, 0 );
		$this->expectDomainEvent( PageDeletedEvent::TYPE, 1 );

		$factory = $this->getServiceContainer()->getMergeHistoryFactory();
		$mh = $factory->newMergeHistory( $title, $title2 );

		$this->assertTrue( $title->exists() );

		$status = $mh->merge( static::getTestSysop()->getUser() );
		$this->assertStatusOK( $status );

		// XXX: Using the $title object triggers failures in some versions of PHP,
		//      see discussions on Id7549ebfdffaf90db87a359b4e44c0fbca9bef0b.
		$this->runDeferredUpdates();
		$pageStore = $this->getServiceContainer()->getPageStore();
		$pageAfter = $pageStore->getPageByName(
			$title->getNamespace(),
			$title->getDBkey(),
			IDBAccessObject::READ_LATEST
		);
		$this->assertNull( $pageAfter );

		$srcLog = $this->getLatestLogEntry( $title );
		$dstLog = $this->getLatestLogEntry( $title2 );

		$this->assertNotNull( $srcLog );
		$this->assertSame( 'merge/merge', $srcLog->getFullType() );

		$this->assertNotNull( $dstLog );
		$this->assertSame( 'merge/merge-into', $dstLog->getFullType() );
	}

	private function getLatestLogEntry( PageIdentity $page ): ?LogEntry {
		$row = DatabaseLogEntry::newSelectQueryBuilder( $this->getDb() )
			->where( [ 'log_page' => $page->getId() ] )
			->orderBy( 'log_id', 'DESC' )
			->limit( 1 )
			->caller( __METHOD__ )
			->fetchRow();

		if ( !$row ) {
			return null;
		}

		return DatabaseLogEntry::newFromRow( $row );
	}

	/**
	 * Test that links tables are updated to reflect the fact that the
	 * source page has become a redirect.
	 *
	 * @covers \MediaWiki\Page\MergeHistory::merge
	 */
	public function testLinkTableUpdates() {
		$textWithLinks = "Hello [[world]]\n\n[[Category:Test]]";
		$worldLink = new TitleValue( NS_MAIN, 'World' );
		$testCategory = 'Test';

		$title = Title::makeTitle( NS_MAIN, __METHOD__ . '_src' );
		$title2 = Title::makeTitle( NS_MAIN, __METHOD__ . '_des' );

		$this->insertPage( $title, $textWithLinks );
		$this->insertPage( $title2 );

		$this->runDeferredUpdates();

		// sanity check before testing the effect of the merge
		$this->assertLinks( $title, [ $worldLink ], [ $testCategory ] );

		$factory = $this->getServiceContainer()->getMergeHistoryFactory();
		$mh = $factory->newMergeHistory( $title, $title2 );

		$status = $mh->merge( static::getTestSysop()->getUser() );
		$this->assertStatusOK( $status );

		// make sure the links are gone, but the redirect is there
		$this->runDeferredUpdates();
		$this->assertLinks( $title, [ $title2 ], [], $title2 );
	}

	private function assertLinks(
		PageIdentity $page,
		array $links,
		array $categories,
		?LinkTarget $redirect = null
	) {
		$actualRedirect =
			$this->getServiceContainer()->getRedirectLookup()->getRedirectTarget( $page );

		if ( $redirect ) {
			$this->assertTrue( $actualRedirect->isSameLinkAs( $redirect ), 'Redirect target' );
		} else {
			$this->assertNull( $actualRedirect, 'Redirect target' );
		}

		$linksMigration = $this->getServiceContainer()->getLinksMigration();
		$linkRows = $this->getDb()->newSelectQueryBuilder()
			->queryInfo( $linksMigration->getQueryInfo( 'pagelinks' ) )
			->where( [ 'pl_from' => $page->getId() ] )
			->caller( __METHOD__ )
			->fetchResultSet();

		$actualLinks = [];

		[ $plNamespace, $plTitle ] = $linksMigration->getTitleFields( 'pagelinks' );
		foreach ( $linkRows as $row ) {
			$key = $row->$plNamespace . ':' . $row->$plTitle;
			$actualLinks[$key] = true;
		}

		foreach ( $links as $lnk ) {
			$key = $lnk->getNamespace() . ':' . $lnk->getDBkey();
			$this->assertArrayHasKey( $key, $actualLinks, 'Page Links' );
			unset( $actualLinks[ $key ] );
		}

		$this->assertSame( [], $actualLinks, 'Leftover Page Links' );

		$actualCategories = $this->getDb()->newSelectQueryBuilder()
			->select( 'cl_to' )
			->from( 'categorylinks' )
			->where( [ 'cl_from' => $page->getId() ] )
			->caller( __METHOD__ )
			->fetchFieldValues();

		$actualCategories = array_flip( $actualCategories );

		foreach ( $categories as $cat ) {
			$this->assertArrayHasKey( $cat, $actualCategories, 'Categories' );
			unset( $actualCategories[ $cat ] );
		}
		$this->assertSame( [], $actualCategories, 'Leftover Categories' );
	}

	/**
	 * @covers \MediaWiki\Page\MergeHistory::initTimestampLimits
	 */
	public function testSplitTimestamp() {
		// Create the source page with two revisions with the same timestamp
		$user = static::getTestSysop()->getUser();
		$title1 = $this->insertPage( "Merge7" )["title"];
		$timestamp = MWTimestamp::now( TS_MW );
		$store = $this->getServiceContainer()->getRevisionStore();
		$revision = MutableRevisionRecord::newFromParentRevision( $store->getFirstRevision( $title1 ) );
		$revision->setTimestamp( $timestamp );
		$revision->setComment( CommentStoreComment::newUnsavedComment( "testing" ) );
		$revision->setUser( $user );
		$dbw = $this->getDB();
		$revid1 = $store->insertRevisionOn( $revision, $dbw )->getID();

		$revision2 = MutableRevisionRecord::newFromParentRevision( $store->getFirstRevision( $title1 ) );
		$revision2->setTimestamp( $timestamp );
		$revision2->setComment( CommentStoreComment::newUnsavedComment( "testing" ) );
		$revision2->setUser( $user );
		$revid2 = $store->insertRevisionOn( $revision2, $dbw )->getID();
		// Create the destination page (here to ensure its timestamp is the same or later than the above)
		$title2 = $this->insertPage( "Merge8" )["title"];

		// Now do the merge
		$factory = $this->getServiceContainer()->getMergeHistoryFactory();
		$mh = $factory->newMergeHistory( $title1, $title2, $timestamp . '|' . $revid1 );
		$status = $mh->merge( $user );
		$this->assertStatusOK( $status );

		$this->assertNull( $store->getRevisionByPageId( $title1->getId(), $revid1 ) );
		$this->assertNotNull( $store->getRevisionByPageId( $title1->getId(), $revid2 ) );
		$this->assertNotNull( $store->getRevisionByPageId( $title2->getId(), $revid1 ) );
		$this->assertNull( $store->getRevisionByPageId( $title2->getId(), $revid2 ) );
	}

	public static function provideUpdatePropagation() {
		static $counter = 1;
		$name = __METHOD__ . $counter++;

		$script = new JavaScriptContent( 'console.log("testing")' );
		$noRedirect = new DummyContentForTesting( 'just a test' );

		yield 'merge articles' => [ "$name-OLD", "$name-NEW" ];
		yield 'merge no-redirect' => [ "NoRedirect:$name-OLD", "NoRedirect:$name-NEW",
			$noRedirect, $noRedirect ];
		yield 'merge user talk' => [ "User_talk:$name-OLD", "User_talk:$name-NEW" ];
		yield 'merge messages' => [ "MediaWiki:$name-OLD", "MediaWiki:$name-NEW" ];
		yield 'merge scripts' => [ "User:$name/OLD.js", "User:$name/NEW.js", $script ];

		// TODO: also test partial merges!
	}

	/**
	 * Test update propagation.
	 *
	 * @covers \MediaWiki\Page\MergeHistory::merge
	 *
	 * @dataProvider provideUpdatePropagation
	 */
	public function testUpdatePropagation(
		$old,
		$new,
		?Content $oldContent = null,
		?Content $newContent = null
	) {
		// Clear some extension hook handlers that may interfere with mock object expectations.
		$this->clearHooks( [
			'RevisionRecordInserted',
			'PageSaveComplete',
			'PageMoveComplete',
			'LinksUpdateComplete',
		] );

		$old = Title::newFromText( $old );
		$new = Title::newFromText( $new );

		$oldContent ??= new WikitextContent( 'hey' );
		$newContent ??= new WikitextContent( 'ho' );

		MWTimestamp::setFakeTime( '20220101223344' );
		$this->editPage( $old, $oldContent );

		MWTimestamp::setFakeTime( '20240101334455' );
		$this->editPage( $new, $newContent );

		// clear the queue
		$this->runJobs();

		$deleteSource = false;

		$contentHandler = $this->getServiceContainer()->getContentHandlerFactory()
			->getContentHandler( $oldContent->getModel() );

		if ( !$contentHandler->supportsRedirects() || (
				// Do not create redirects for wikitext message overrides (T376399).
				// Maybe one day they will have a custom content model and this special case won't be needed.
				$old->getNamespace() === NS_MEDIAWIKI &&
				$old->getContentModel() === CONTENT_MODEL_WIKITEXT
			) ) {
			$deleteSource = true;
		}

		// Merges don't count as user contributions and should not trigger talk
		// page notifications. They should show up in RecentChanges as merges,
		// not edits.
		if ( $deleteSource ) {
			// If the source page gets deleted, there's an additional RC entry.
			$this->expectChangeTrackingUpdates( 0, 3, 0, 0, 0 );
		} else {
			$this->expectChangeTrackingUpdates( 0, 2, 0, 0, 1 );
		}

		// The source page should get re-indexed.
		$this->expectSearchUpdates( 1 );

		// The localization cache should be reset for the MediaWiki
		// namespace.
		$this->expectLocalizationUpdate(
			$old->getNamespace() === NS_MEDIAWIKI ? 1 : 0
		);

		// If the content model is JS, the module cache should be reset for the
		// source page.
		$this->expectResourceLoaderUpdates(
			$oldContent->getModel() === CONTENT_MODEL_JAVASCRIPT ? 1 : 0
		);

		// Now merge the pages
		$admin = static::getTestUser( [ 'sysop', 'interface-admin' ] )->getUser();

		$factory = $this->getServiceContainer()->getMergeHistoryFactory();
		$mh = $factory->newMergeHistory( $old, $new );
		$status = $mh->merge( $admin );

		$this->assertStatusOK( $status ); // sanity

		$this->runDeferredUpdates();
	}
}
