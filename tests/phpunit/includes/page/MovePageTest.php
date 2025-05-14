<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\Content;
use MediaWiki\Content\JavaScriptContent;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\Event\PageMovedEvent;
use MediaWiki\Page\Event\PageRevisionUpdatedEvent;
use MediaWiki\Page\MovePage;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\ExpectCallbackTrait;
use MediaWiki\Tests\Language\LocalizationUpdateSpyTrait;
use MediaWiki\Tests\recentchanges\ChangeTrackingUpdateSpyTrait;
use MediaWiki\Tests\ResourceLoader\ResourceLoaderUpdateSpyTrait;
use MediaWiki\Tests\Rest\Handler\MediaTestTrait;
use MediaWiki\Tests\Search\SearchUpdateSpyTrait;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\Watchlist\WatchedItemStore;
use PHPUnit\Framework\Assert;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * @covers \MediaWiki\Page\MovePage
 * @group Database
 */
class MovePageTest extends MediaWikiIntegrationTestCase {
	use DummyServicesTrait;
	use MediaTestTrait;
	use ChangeTrackingUpdateSpyTrait;
	use SearchUpdateSpyTrait;
	use LocalizationUpdateSpyTrait;
	use ResourceLoaderUpdateSpyTrait;
	use ExpectCallbackTrait;

	/**
	 * @param Title $old
	 * @param Title $new
	 * @param array $params Valid keys are: db, options,
	 *   options is an indexed array that will overwrite our defaults, not a ServiceOptions, so it
	 *   need not contain all keys.
	 * @return MovePage
	 */
	private function newMovePageWithMocks( $old, $new, array $params = [] ): MovePage {
		$mockProvider = $this->createNoOpMock( IConnectionProvider::class, [ 'getPrimaryDatabase' ] );
		$mockProvider->method( 'getPrimaryDatabase' )
			->willReturn( $params['db'] ?? $this->createNoOpMock( IDatabase::class ) );

		return new MovePage(
			$old,
			$new,
			new ServiceOptions(
				MovePage::CONSTRUCTOR_OPTIONS,
				$params['options'] ?? [],
				[
					MainConfigNames::CategoryCollation => 'uppercase',
					MainConfigNames::MaximumMovedPages => 100,
				]
			),
			$mockProvider,
			$this->getDummyNamespaceInfo(),
			$this->createMock( WatchedItemStore::class ),
			$this->makeMockRepoGroup(
				[ 'Existent.jpg', 'Existent2.jpg', 'Existent-file-no-page.jpg' ]
			),
			$this->getServiceContainer()->getContentHandlerFactory(),
			$this->getServiceContainer()->getRevisionStore(),
			$this->getServiceContainer()->getSpamChecker(),
			$this->getServiceContainer()->getHookContainer(),
			$this->getServiceContainer()->getDomainEventDispatcher(),
			$this->getServiceContainer()->getWikiPageFactory(),
			$this->getServiceContainer()->getUserFactory(),
			$this->getServiceContainer()->getUserEditTracker(),
			$this->getServiceContainer()->getMovePageFactory(),
			$this->getServiceContainer()->getCollationFactory(),
			$this->getServiceContainer()->getPageUpdaterFactory(),
			$this->getServiceContainer()->getRestrictionStore(),
			$this->getServiceContainer()->getDeletePageFactory(),
			$this->getServiceContainer()->getLogFormatterFactory()
		);
	}

	protected function setUp(): void {
		parent::setUp();

		// To avoid problems with namespace localization
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'en' );

		// Ensure we have some pages that are guaranteed to exist or not
		$this->getExistingTestPage( 'Existent' );
		$this->getExistingTestPage( 'Existent2' );
		$this->getExistingTestPage( 'File:Existent.jpg' );
		$this->getExistingTestPage( 'File:Existent2.jpg' );
		$this->getExistingTestPage( 'File:Non-file.jpg' );
		// Special treatment as we can't just add wikitext to a JS page
		$this->insertPage( 'MediaWiki:Existent.js', '// Hello this is JavaScript!' );
		$this->getExistingTestPage( 'Hooked in place' );
		$this->getNonexistingTestPage( 'Nonexistent' );
		$this->getNonexistingTestPage( 'Nonexistent2' );
		$this->getNonexistingTestPage( 'File:Nonexistent.jpg' );
		$this->getNonexistingTestPage( 'File:Nonexistent.png' );
		$this->getNonexistingTestPage( 'File:Existent-file-no-page.jpg' );
		$this->getNonexistingTestPage( 'MediaWiki:Nonexistent' );
		$this->getNonexistingTestPage( 'No content allowed' );

		// Set a couple of hooks for specific pages
		$this->setTemporaryHook( 'ContentModelCanBeUsedOn',
			static function ( $modelId, Title $title, &$ok ) {
				if ( $title->getPrefixedText() === 'No content allowed' ) {
					$ok = false;
				}
			}
		);

		$this->setTemporaryHook( 'TitleIsMovable',
			static function ( Title $title, &$result ) {
				if ( strtolower( $title->getPrefixedText() ) === 'hooked in place' ) {
					$result = false;
				}
			}
		);
	}

	/**
	 * @dataProvider provideIsValidMove
	 * @covers \MediaWiki\Page\MovePage::isValidMove
	 * @covers \MediaWiki\Page\MovePage::isValidMoveTarget
	 * @covers \MediaWiki\Page\MovePage::isValidFileMove
	 * @covers \MediaWiki\Page\MovePage::__construct
	 *
	 * @param string|Title $old
	 * @param string|Title $new
	 * @param StatusValue $expectedStatus
	 * @param array $extraOptions
	 */
	public function testIsValidMove(
		$old, $new, StatusValue $expectedStatus, array $extraOptions = []
	) {
		$iwLookup = $this->createMock( InterwikiLookup::class );
		$iwLookup->method( 'isValidInterwiki' )
			->willReturn( true );

		$this->setService( 'InterwikiLookup', $iwLookup );

		$old = $old instanceof Title ? $old : Title::newFromText( $old );
		$new = $new instanceof Title ? $new : Title::newFromText( $new );
		$mp = $this->newMovePageWithMocks( $old, $new, [ 'options' => $extraOptions ] );
		$this->assertStatusMessagesExactly(
			$expectedStatus,
			$mp->isValidMove()
		);
	}

	public static function provideIsValidMove() {
		$ret = [
			'Valid move with redirect' => [
				'Existent',
				'Nonexistent',
				StatusValue::newGood(),
				[ 'createRedirect' => true ]
			],
			'Valid move without redirect' => [
				'Existent',
				'Nonexistent',
				StatusValue::newGood(),
				[ 'createRedirect' => false ]
			],
			'Self move' => [
				'Existent',
				'Existent',
				StatusValue::newFatal( 'selfmove' ),
			],
			'Move from empty name' => [
				Title::makeTitle( NS_MAIN, '' ),
				'Nonexistent',
				// @todo More specific error message, or make the move valid if the page actually
				// exists somehow in the database
				StatusValue::newFatal( 'badarticleerror' ),
			],
			'Move to empty name' => [
				'Existent',
				Title::makeTitle( NS_MAIN, '' ),
				StatusValue::newFatal( 'movepage-invalid-target-title' ),
			],
			'Move to invalid name' => [
				'Existent',
				Title::makeTitle( NS_MAIN, '<' ),
				StatusValue::newFatal( 'movepage-invalid-target-title' ),
			],
			'Move between invalid names' => [
				Title::makeTitle( NS_MAIN, '<' ),
				Title::makeTitle( NS_MAIN, '>' ),
				// @todo First error message should be more specific, or maybe we should make moving
				// such pages valid if they actually exist somehow in the database
				StatusValue::newFatal( 'movepage-source-doesnt-exist', '<' )
					->fatal( 'movepage-invalid-target-title' ),
			],
			'Move nonexistent' => [
				'Nonexistent',
				'Nonexistent2',
				StatusValue::newFatal( 'movepage-source-doesnt-exist', 'Nonexistent' ),
			],
			'Move over existing' => [
				'Existent',
				'Existent2',
				StatusValue::newFatal( 'articleexists', 'Existent2' ),
			],
			'Move from another wiki' => [
				Title::makeTitle( NS_MAIN, 'Test', '', 'otherwiki' ),
				'Nonexistent',
				StatusValue::newFatal( 'immobile-source-namespace-iw' ),
			],
			'Move special page' => [
				'Special:FooBar',
				'Nonexistent',
				StatusValue::newFatal( 'immobile-source-namespace', 'Special' ),
			],
			'Move to another wiki' => [
				'Existent',
				Title::makeTitle( NS_MAIN, 'Test', '', 'otherwiki' ),
				StatusValue::newFatal( 'immobile-target-namespace-iw' ),
			],
			'Move to special page' => [
				'Existent',
				'Special:FooBar',
				StatusValue::newFatal( 'immobile-target-namespace', 'Special' ),
			],
			'Move to allowed content model' => [
				'MediaWiki:Existent.js',
				'MediaWiki:Nonexistent',
				StatusValue::newGood(),
			],
			'Move to prohibited content model' => [
				'Existent',
				'No content allowed',
				StatusValue::newFatal( 'content-not-allowed-here', 'wikitext', 'No content allowed', 'main' ),
			],
			'Aborted by hook' => [
				'Hooked in place',
				'Nonexistent',
				StatusValue::newFatal( 'immobile-source-namespace', '(Main)' ),
			],
			'Doubly aborted by hook' => [
				'Hooked in place',
				'Hooked In Place',
				StatusValue::newFatal( 'immobile-source-namespace', '(Main)' )
					->fatal( 'immobile-target-namespace', '(Main)' ),
			],
			'Non-file to file' => [
				'Existent',
				'File:Nonexistent.jpg',
				StatusValue::newFatal( 'nonfile-cannot-move-to-file' ),
			],
			'File to non-file' => [
				'File:Existent.jpg',
				'Nonexistent',
				StatusValue::newFatal( 'imagenocrossnamespace' ),
			],
			'Existing file to non-existing file' => [
				'File:Existent.jpg',
				'File:Nonexistent.jpg',
				StatusValue::newGood(),
			],
			'Existing file to existing file' => [
				'File:Existent.jpg',
				'File:Existent2.jpg',
				StatusValue::newFatal( 'articleexists', 'File:Existent2.jpg' ),
			],
			'Existing file to existing file with no page' => [
				'File:Existent.jpg',
				'File:Existent-file-no-page.jpg',
				// @todo Is this correct? Moving over an existing file with no page should succeed?
				StatusValue::newGood(),
			],
			'Existing file to name with slash' => [
				'File:Existent.jpg',
				'File:Existent/slashed.jpg',
				StatusValue::newFatal( 'imageinvalidfilename' ),
			],
			'Mismatched file extension' => [
				'File:Existent.jpg',
				'File:Nonexistent.png',
				StatusValue::newFatal( 'imagetypemismatch' ),
			],
			'Non-file page in the File namespace' => [
				'File:Non-file.jpg',
				'File:Non-file-new.png',
				StatusValue::newGood(),
			],
			'File too long' => [
				'File:Existent.jpg',
				'File:0123456789012345678901234567890123456789012345678901234567890123456789' .
					'0123456789012345678901234567890123456789012345678901234567890123456789' .
					'0123456789012345678901234567890123456789012345678901234567890123456789' .
					'012345678901234567890123456789-long.jpg',
				StatusValue::newFatal( 'filename-toolong' ),
			],
			// The FileRepo mock does not return true for ->backendSupportsUnicodePaths()
			'Non-ascii' => [
				'File:Existent.jpg',
				'File:🏳️‍🌈🏳️‍🌈🏳️‍🌈🏳️‍🌈 🏳️‍🌈🏳️‍🌈🏳️‍🌈🏳️‍🌈 🏳️‍🌈🏳️‍🌈🏳️‍🌈🏳️‍🌈 🏳️‍🌈🏳️‍🌈🏳️‍🌈🏳️‍🌈 🏳️‍🌈.jpg',
				StatusValue::newFatal( 'filename-toolong' )
					->fatal( 'windows-nonascii-filename' ),
			],
			'Non-file move long with unicode' => [
				'File:Non-file.jpg',
				'File:🏳️‍🌈🏳️‍🌈🏳️‍🌈🏳️‍🌈 🏳️‍🌈🏳️‍🌈🏳️‍🌈🏳️‍🌈 🏳️‍🌈🏳️‍🌈🏳️‍🌈🏳️‍🌈 🏳️‍🌈🏳️‍🌈🏳️‍🌈🏳️‍🌈 🏳️‍🌈.jpg',
				StatusValue::newGood()
			],
			'File just extension' => [
				'File:Existent.jpg',
				'File:.jpg',
				StatusValue::newFatal( 'filename-tooshort' )
					->fatal( 'imagetypemismatch' ),
			],
		];
		return $ret;
	}

	/**
	 * @dataProvider provideIsValidMove
	 *
	 * @param string|Title $old Old name
	 * @param string|Title $new New name
	 * @param StatusValue $expectedStatus
	 * @param array $extraOptions
	 */
	public function testMove( $old, $new, StatusValue $expectedStatus, array $extraOptions = [] ) {
		$iwLookup = $this->createMock( InterwikiLookup::class );
		$iwLookup->method( 'isValidInterwiki' )
			->willReturn( true );

		$this->setService( 'InterwikiLookup', $iwLookup );

		$old = $old instanceof Title ? $old : Title::newFromText( $old );
		$new = $new instanceof Title ? $new : Title::newFromText( $new );

		$createRedirect = $extraOptions['createRedirect'] ?? true;
		unset( $extraOptions['createRedirect'] );
		$params = [ 'options' => $extraOptions ];

		if ( !$expectedStatus->isGood() ) {
			$obj = $this->newMovePageWithMocks( $old, $new, $params );
			$status = $obj->move( $this->getTestUser()->getUser() );
			$this->assertStatusMessagesExactly(
				$expectedStatus,
				$status
			);
		} else {
			// Create Title objects from text, so they will end up in the instance
			// cache. In assertMoved() we'll check that we are not
			// getting this stale object from Title::newFromText().
			Title::clearCaches();
			$oldFromText = Title::newFromText( $old->getPrefixedText() );
			$newFromText = Title::newFromText( $new->getPrefixedText() );
			$oldFromText->getId();
			$newFromText->getId();

			$oldPageId = $old->getArticleID();
			$status = $this->getServiceContainer()
				->getMovePageFactory()
				->newMovePage( $old, $new )
				->move( $this->getTestUser()->getUser(), 'move reason', $createRedirect );
			$this->assertStatusOK( $status );
			$this->assertMoved( $old, $new, $oldPageId, $createRedirect );

			[
				'nullRevision' => $nullRevision,
				'redirectRevision' => $redirectRevision
			] = $status->getValue();
			$this->assertInstanceOf( RevisionRecord::class, $nullRevision );
			$this->assertSame( $oldPageId, $nullRevision->getPageId() );
			if ( $createRedirect ) {
				$this->assertInstanceOf( RevisionRecord::class, $redirectRevision );
				$this->assertSame( $old->getArticleID( IDBAccessObject::READ_LATEST ), $redirectRevision->getPageId() );
			} else {
				$this->assertNull( $redirectRevision );
			}
		}
	}

	/**
	 * Test for the move operation being aborted via the TitleMove hook
	 * @covers \MediaWiki\Page\MovePage::move
	 */
	public function testMoveAbortedByTitleMoveHook() {
		$error = 'Preventing move operation with TitleMove hook.';
		$this->setTemporaryHook( 'TitleMove',
			static function ( $old, $new, $user, $reason, $status ) use ( $error ) {
				$status->fatal( $error );
			}
		);

		$oldTitle = Title::makeTitle( NS_MAIN, 'Some old title' );
		$this->editPage(
			$oldTitle,
			new WikitextContent( 'foo' ),
			'bar',
			NS_MAIN,
			$this->getTestSysop()->getAuthority()
		);
		$newTitle = Title::makeTitle( NS_MAIN, 'A brand new title' );
		$mp = $this->newMovePageWithMocks( $oldTitle, $newTitle );
		$user = User::newFromName( 'TitleMove tester' );
		$status = $mp->move( $user, 'Reason', true );
		$this->assertStatusError( $error, $status );
	}

	/**
	 * Test moving subpages from one page to another
	 * @covers \MediaWiki\Page\MovePage::moveSubpages
	 */
	public function testMoveSubpages() {
		$name = ucfirst( __FUNCTION__ );

		$subPages = [ "Talk:$name/1", "Talk:$name/2" ];
		$ids = [];
		$pages = [
			$name,
			"Talk:$name",
			"$name 2",
			"Talk:$name 2",
		];
		foreach ( array_merge( $pages, $subPages ) as $page ) {
			$ids[$page] = $this->createPage( $page );
		}

		$oldTitle = Title::newFromText( "Talk:$name" );
		$newTitle = Title::newFromText( "Talk:$name 2" );
		$status = $this->getServiceContainer()
			->getMovePageFactory()
			->newMovePage( $oldTitle, $newTitle )
			->moveSubpages( $this->getTestUser()->getUser(), 'Reason', true );

		$this->assertStatusGood( $status,
			"Moving subpages from Talk:{$name} to Talk:{$name} 2 was not completely successful." );
		foreach ( $subPages as $page ) {
			$this->assertMoved( $page, str_replace( $name, "$name 2", $page ), $ids[$page] );
		}
	}

	/**
	 * Test moving subpages from one page to another
	 * @covers \MediaWiki\Page\MovePage::moveSubpagesIfAllowed
	 */
	public function testMoveSubpagesIfAllowed() {
		$name = ucfirst( __FUNCTION__ );

		$subPages = [ "Talk:$name/1", "Talk:$name/2" ];
		$ids = [];
		$pages = [
			$name,
			"Talk:$name",
			"$name 2",
			"Talk:$name 2",
		];
		foreach ( array_merge( $pages, $subPages ) as $page ) {
			$ids[$page] = $this->createPage( $page );
		}

		$oldTitle = Title::newFromText( "Talk:$name" );
		$newTitle = Title::newFromText( "Talk:$name 2" );
		$status = $this->getServiceContainer()
			->getMovePageFactory()
			->newMovePage( $oldTitle, $newTitle )
			->moveSubpagesIfAllowed( $this->getTestUser()->getUser(), 'Reason', true );

		$this->assertStatusGood( $status,
			"Moving subpages from Talk:{$name} to Talk:{$name} 2 was not completely successful." );
		foreach ( $subPages as $page ) {
			$this->assertMoved( $page, str_replace( $name, "$name 2", $page ), $ids[$page] );
		}
	}

	/**
	 * Shortcut function to create a page and return its id.
	 *
	 * @param string $name Page to create
	 * @return int ID of created page
	 */
	protected function createPage( $name ) {
		return $this->editPage( $name, 'Content' )->getNewRevision()->getPageId();
	}

	/**
	 * @param string $from Prefixed name of source
	 * @param string|Title $to Prefixed name of destination
	 * @param string|Title $id Page id of the page to move
	 * @param bool $createRedirect
	 */
	protected function assertMoved( $from, $to, $id, bool $createRedirect = true ) {
		// NOTE: Title objects returned by Title::newFromText may come from an
		//       instance cache. Using newFromText() here allows us to check
		//       that we are not getting stale instances.
		$fromTitle = Title::newFromText( "$from" );
		$toTitle = Title::newFromText( "$to" );

		$this->assertTrue( $toTitle->exists(),
			"Destination {$toTitle->getPrefixedText()} does not exist" );

		if ( !$createRedirect ) {
			$this->assertFalse( $fromTitle->exists(),
				"Source {$fromTitle->getPrefixedText()} exists" );
		} else {
			$this->assertTrue( $fromTitle->exists(),
				"Source {$fromTitle->getPrefixedText()} does not exist" );
			$this->assertTrue( $fromTitle->isRedirect(),
				"Source {$fromTitle->getPrefixedText()} is not a redirect" );

			$target = $this->getServiceContainer()
				->getRevisionLookup()
				->getRevisionByTitle( $fromTitle )
				->getContent( SlotRecord::MAIN )
				->getRedirectTarget();
			$this->assertSame( $toTitle->getPrefixedText(), $target->getPrefixedText() );
		}

		$this->assertSame( $id, $toTitle->getArticleID() );
	}

	/**
	 * Test redirect handling
	 *
	 * @covers \MediaWiki\Page\MovePage::isValidMove
	 */
	public function testRedirects() {
		$this->editPage( 'ExistentRedirect', '#REDIRECT [[Existent]]' );
		$mp = $this->newMovePageWithMocks(
			Title::makeTitle( NS_MAIN, 'Existent' ),
			Title::makeTitle( NS_MAIN, 'ExistentRedirect' )
		);
		$this->assertStatusGood(
			$mp->isValidMove(),
			'Can move over normal redirect'
		);

		$this->editPage( 'ExistentRedirect3', '#REDIRECT [[Existent]]' );
		$mp = $this->newMovePageWithMocks(
			Title::makeTitle( NS_MAIN, 'Existent2' ),
			Title::makeTitle( NS_MAIN, 'ExistentRedirect3' )
		);
		$this->assertStatusError(
			'redirectexists',
			$mp->isValidMove(),
			'Cannot move over redirect with a different target'
		);

		$this->editPage( 'ExistentRedirect3', '#REDIRECT [[Existent2]]' );
		$mp = $this->newMovePageWithMocks(
			Title::makeTitle( NS_MAIN, 'Existent' ),
			Title::makeTitle( NS_MAIN, 'ExistentRedirect3' )
		);
		$this->assertStatusError(
			'articleexists',
			$mp->isValidMove(),
			'Multi-revision redirects count as articles'
		);
	}

	/**
	 * Assert that links tables are updated after cross namespace page move (T299275).
	 */
	public function testCrossNamespaceLinksUpdate() {
		$title = Title::makeTitle( NS_TEMPLATE, 'Test' );
		$this->getExistingTestPage( $title );

		$wikitext = "[[Test]], [[Image:Existent.jpg]], {{Test}}";

		$old = Title::makeTitle( NS_USER, __METHOD__ );
		$this->editPage( $old, $wikitext );
		$pageId = $old->getId();

		// do a cross-namespace move
		$new = Title::makeTitle( NS_PROJECT, __METHOD__ );
		$obj = $this->newMovePageWithMocks( $old, $new, [ 'db' => $this->getDb() ] );
		$status = $obj->move( $this->getTestUser()->getUser() );

		// sanity checks
		$this->assertStatusOK( $status );
		$this->assertSame( $pageId, $new->getId() );
		$this->assertNotSame( $pageId, $old->getId() );

		// ensure links tables where updated
		$this->newSelectQueryBuilder()
			->select( [ 'lt_namespace', 'lt_title', 'pl_from_namespace' ] )
			->from( 'pagelinks' )
			->join( 'linktarget', null, 'pl_target_id=lt_id' )
			->where( [ 'pl_from' => $pageId ] )
			->assertResultSet( [
				[ NS_MAIN, 'Test', NS_PROJECT ]
			] );
		$targetId = MediaWikiServices::getInstance()->getLinkTargetLookup()->getLinkTargetId( $title );
		$this->newSelectQueryBuilder()
			->select( [ 'tl_target_id', 'tl_from_namespace' ] )
			->from( 'templatelinks' )
			->where( [ 'tl_from' => $pageId ] )
			->assertResultSet( [
				[ $targetId, NS_PROJECT ]
			] );
		$this->newSelectQueryBuilder()
			->select( [ 'il_to', 'il_from_namespace' ] )
			->from( 'imagelinks' )
			->where( [ 'il_from' => $pageId ] )
			->assertResultSet( [
				[ 'Existent.jpg', NS_PROJECT ]
			] );
	}

	/**
	 * Regression test for T381225
	 */
	public function testEventEmission() {
		$old = Title::makeTitle( NS_MEDIAWIKI, 'Foo' );
		$oldPage = $this->getExistingTestPage( $old );
		$oldRev = $oldPage->getRevisionRecord();
		$oldPageId = $old->getId();

		$new = Title::makeTitle( NS_MEDIAWIKI, 'Bar' );
		$this->getNonexistingTestPage( $new );

		$mover = $this->getTestSysop()->getUser();

		$reason = 'testEventEmission';
		// clear the queue
		$this->runJobs();

		$this->expectDomainEvent(
			PageRevisionUpdatedEvent::TYPE, 2,
			static function ( PageRevisionUpdatedEvent $event ) use ( $old, $oldPageId, $new, $oldRev, $mover ) {
				// for the existing page under the new title
				if ( $event->getPage()->isSamePageAs( $new ) ) {
					Assert::assertFalse( $event->isCreation(), 'isCreation' );
					Assert::assertFalse(
						$event->isReconciliationRequest(),
						'isReconciliationRequest'
					);
					Assert::assertTrue(
						$event->changedLatestRevisionId(),
						'changedLatestRevisionId'
					);
					Assert::assertFalse(
						$event->isEffectiveContentChange(),
						'isEffectiveContentChange'
					);
					Assert::assertFalse(
						$event->isNominalContentChange(),
						'isNominalContentChange'
					);
					Assert::assertSame( $oldPageId, $event->getPage()->getId() );
					Assert::assertSame( $oldRev->getId(), $event->getLatestRevisionBefore()->getId() );
					Assert::assertSame( $mover, $event->getPerformer() );

					Assert::assertTrue(
						$event->getLatestRevisionAfter()->isMinor(),
						'isMinor'
					);

					Assert::assertTrue(
						$event->hasCause( PageRevisionUpdatedEvent::CAUSE_MOVE ),
						PageRevisionUpdatedEvent::CAUSE_MOVE
					);

					Assert::assertTrue( $event->isSilent(), 'isSilent' );
				}

				// for the redirect page
				if ( $event->getPage()->isSamePageAs( $old ) ) {
					Assert::assertTrue( $event->isCreation(), 'isCreation' );
					Assert::assertFalse(
						$event->isReconciliationRequest(),
						'isReconciliationRequest'
					);
					Assert::assertTrue(
						$event->changedLatestRevisionId(),
						'changedLatestRevisionId'
					);
					Assert::assertTrue(
						$event->isEffectiveContentChange(),
						'isEffectiveContentChange'
					);
					Assert::assertTrue(
						$event->isNominalContentChange(),
						'isNominalContentChange'
					);
					Assert::assertSame( $mover, $event->getPerformer() );
					Assert::assertSame( $mover, $event->getAuthor() );

					Assert::assertTrue( $event->isSilent(), 'isSilent' );
					Assert::assertTrue( $event->isImplicit(), 'isImplicit' );
				}

				// TODO: assert more properties
			}
		);

		$this->expectDomainEvent(
			PageMovedEvent::TYPE, 1,
			static function ( PageMovedEvent $event )
				use ( $old, $oldPageId, $new, $mover, $reason )
			{
				Assert::assertTrue( $event->getPageRecordAfter()->isSamePageAs( $new ) );
				Assert::assertTrue( $event->getPageRecordBefore()->isSamePageAs( $old ) );

				Assert::assertSame( $oldPageId, $event->getPageId() );

				Assert::assertSame( $reason, $event->getReason(), 'getReason()' );

				// Default settings: Move is expected to create a redirect.
				Assert::assertTrue( $event->wasRedirectCreated() );
				Assert::assertNotNull( $event->getRedirectPage() );
				Assert::assertSame( $old->getDBkey(), $event->getRedirectPage()->getDBkey(), "getRedirectArticle()" );
				Assert::assertNotSame( $oldPageId, $event->getRedirectPage()->getId() );
			}
		);

		// Now move the page
		$obj = $this->newMovePageWithMocks( $old, $new, [ 'db' => $this->getDb() ] );
		$obj->move( $mover, $reason );
	}

	/**
	 * Test case for T394049
	 */
	public function testEventEmissionWithoutCreatingRedirect() {
		$old = Title::makeTitle( NS_MEDIAWIKI, 'Foo' );

		$this->getExistingTestPage( $old );

		$new = Title::makeTitle( NS_MEDIAWIKI, 'Bar' );
		$this->getNonexistingTestPage( $new );

		$mover = $this->getTestSysop()->getUser();

		// clear the queue
		$this->runJobs();

		$this->expectDomainEvent(
			PageMovedEvent::TYPE, 1,
			static function ( PageMovedEvent $event )
			use ( $old, $new, $mover )
			{
				Assert::assertFalse( $event->wasRedirectCreated() );
				Assert::assertNull( $event->getRedirectPage() );
			}
		);

		// Now move the page without creating a redirect
		$obj = $this->newMovePageWithMocks( $old, $new, [ 'db' => $this->getDb() ] );
		$obj->move( $mover, null, false );
	}

	public static function provideUpdatePropagation() {
		static $counter = 1;
		$name = __METHOD__ . $counter++;

		$script = new JavaScriptContent( 'console.log("testing")' );

		yield 'move article' => [ "$name-OLD", "$name-NEW" ];
		yield 'move user talk' => [ "User_talk:$name-OLD", "User_talk:$name-NEW" ];
		yield 'move message' => [ "MediaWiki:$name-OLD", "MediaWiki:$name-NEW" ];
		yield 'move script' => [ "User:$name/OLD.js", "User:$name/NEW.js", $script ];

		yield 'move from user talk' => [ "User_talk:$name-OLD", "$name-NEW" ];
		yield 'move from message' => [ "MediaWiki:$name-OLD", "$name-NEW" ];
		yield 'move from script' => [ "User:$name/OLD.js", "$name/NEW", $script ];

		yield 'move to user talk' => [ "$name-OLD", "User_talk:$name-NEW" ];
		yield 'move to message' => [ "$name-OLD", "MediaWiki:$name-NEW" ];
		yield 'move to script' => [ "$name/OLD", "User:$name/NEW.js" ];
	}

	/**
	 * Test update propagation.
	 * Includes regression test for T381225
	 *
	 * @dataProvider provideUpdatePropagation
	 */
	public function testUpdatePropagation( $old, $new, ?Content $content = null ) {
		// Clear some extension hook handlers that may interfere with mock object expectations.
		$this->clearHooks( [
			'RevisionRecordInserted',
			'PageSaveComplete',
			'PageMoveComplete',
			'LinksUpdateComplete',
		] );

		$old = Title::newFromText( $old );
		$new = Title::newFromText( $new );

		$content ??= new WikitextContent( 'hi' );
		$this->editPage( $old, $content );
		$this->getNonexistingTestPage( $new );

		// clear the queue
		$this->runJobs();

		// Should be counted as user contributions (T163966)
		// Should generate an RC entry for the move log, but not for
		// the dummy revision or redirect page.
		$this->expectChangeTrackingUpdates( 0, 1, 1, 0, 1 );

		// The moved page and the redirect should both get re-indexed.
		$this->expectSearchUpdates( 2 );

		// The localization cache should be reset of any page in the MediaWiki
		// namespace.
		$this->expectLocalizationUpdate(
			( $old->getNamespace() === NS_MEDIAWIKI ? 1 : 0 )
			+ ( $new->getNamespace() === NS_MEDIAWIKI ? 1 : 0 )
		);

		// If the content model is JS, the module cache should be reset for the
		// old and the new title.
		$this->expectResourceLoaderUpdates(
			$content->getModel() === CONTENT_MODEL_JAVASCRIPT ? 2 : 0
		);

		// Now move the page
		$obj = $this->newMovePageWithMocks( $old, $new, [ 'db' => $this->getDb() ] );
		$obj->move( $this->getTestUser()->getUser() );

		// NOTE: assertions are applied by the spies installed earlier.
		$this->runDeferredUpdates();
	}

}
