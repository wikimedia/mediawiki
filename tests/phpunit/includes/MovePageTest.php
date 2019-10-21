<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\MovePageFactory;
use MediaWiki\Permissions\PermissionManager;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * @group Database
 */
class MovePageTest extends MediaWikiTestCase {
	/**
	 * The only files that exist are 'File:Existent.jpg', 'File:Existent2.jpg', and
	 * 'File:Existent-file-no-page.jpg'. Calling unexpected methods causes a test failure.
	 *
	 * @return RepoGroup
	 */
	private function getMockRepoGroup() : RepoGroup {
		$mockExistentFile = $this->createMock( LocalFile::class );
		$mockExistentFile->method( 'exists' )->willReturn( true );
		$mockExistentFile->method( 'getMimeType' )->willReturn( 'image/jpeg' );
		$mockExistentFile->expects( $this->never() )
			->method( $this->anythingBut( 'exists', 'load', 'getMimeType', '__destruct' ) );

		$mockNonexistentFile = $this->createMock( LocalFile::class );
		$mockNonexistentFile->method( 'exists' )->willReturn( false );
		$mockNonexistentFile->expects( $this->never() )
			->method( $this->anythingBut( 'exists', 'load', '__destruct' ) );

		$mockLocalRepo = $this->createMock( LocalRepo::class );
		$mockLocalRepo->method( 'newFile' )->will( $this->returnCallback(
			function ( Title $title ) use ( $mockExistentFile, $mockNonexistentFile ) {
				if ( in_array( $title->getPrefixedText(),
					[ 'File:Existent.jpg', 'File:Existent2.jpg', 'File:Existent-file-no-page.jpg' ]
				) ) {
					return $mockExistentFile;
				}
				return $mockNonexistentFile;
			}
		) );
		$mockLocalRepo->expects( $this->never() )
			->method( $this->anythingBut( 'newFile', '__destruct' ) );

		$mockRepoGroup = $this->createMock( RepoGroup::class );
		$mockRepoGroup->method( 'getLocalRepo' )->willReturn( $mockLocalRepo );
		$mockRepoGroup->expects( $this->never() )
			->method( $this->anythingBut( 'getLocalRepo', '__destruct' ) );

		return $mockRepoGroup;
	}

	/**
	 * @param LinkTarget $old
	 * @param LinkTarget $new
	 * @param array $params Valid keys are: db, options, nsInfo, wiStore, repoGroup.
	 *   options is an indexed array that will overwrite our defaults, not a ServiceOptions, so it
	 *   need not contain all keys.
	 * @return MovePage
	 */
	private function newMovePage( $old, $new, array $params = [] ) : MovePage {
		$mockLB = $this->createMock( LoadBalancer::class );
		$mockLB->method( 'getConnection' )
			->willReturn( $params['db'] ?? $this->createNoOpMock( IDatabase::class ) );
		$mockLB->expects( $this->never() )
			->method( $this->anythingBut( 'getConnection', '__destruct' ) );

		$mockNsInfo = $this->createMock( NamespaceInfo::class );
		$mockNsInfo->method( 'isMovable' )->will( $this->returnCallback(
			function ( $ns ) {
				return $ns >= 0;
			}
		) );
		$mockNsInfo->expects( $this->never() )
			->method( $this->anythingBut( 'isMovable', '__destruct' ) );

		return new MovePage(
			$old,
			$new,
			new ServiceOptions(
				MovePageFactory::$constructorOptions,
				$params['options'] ?? [],
				[
					'CategoryCollation' => 'uppercase',
					'ContentHandlerUseDB' => true,
				]
			),
			$mockLB,
			$params['nsInfo'] ?? $mockNsInfo,
			$params['wiStore'] ?? $this->createNoOpMock( WatchedItemStore::class ),
			$params['permMgr'] ?? $this->createNoOpMock( PermissionManager::class ),
			$params['repoGroup'] ?? $this->getMockRepoGroup()
		);
	}

	public function setUp() {
		parent::setUp();

		// Ensure we have some pages that are guaranteed to exist or not
		$this->getExistingTestPage( 'Existent' );
		$this->getExistingTestPage( 'Existent2' );
		$this->getExistingTestPage( 'File:Existent.jpg' );
		$this->getExistingTestPage( 'File:Existent2.jpg' );
		$this->getExistingTestPage( 'MediaWiki:Existent.js' );
		$this->getExistingTestPage( 'Hooked in place' );
		$this->getNonExistingTestPage( 'Nonexistent' );
		$this->getNonExistingTestPage( 'Nonexistent2' );
		$this->getNonExistingTestPage( 'File:Nonexistent.jpg' );
		$this->getNonExistingTestPage( 'File:Nonexistent.png' );
		$this->getNonExistingTestPage( 'File:Existent-file-no-page.jpg' );
		$this->getNonExistingTestPage( 'MediaWiki:Nonexistent' );
		$this->getNonExistingTestPage( 'No content allowed' );

		// Set a couple of hooks for specific pages
		$this->setTemporaryHook( 'ContentModelCanBeUsedOn',
			function ( $modelId, Title $title, &$ok ) {
				if ( $title->getPrefixedText() === 'No content allowed' ) {
					$ok = false;
				}
			}
		);

		$this->setTemporaryHook( 'TitleIsMovable',
			function ( Title $title, &$result ) {
				if ( strtolower( $title->getPrefixedText() ) === 'hooked in place' ) {
					$result = false;
				}
			}
		);

		$this->tablesUsed[] = 'page';
		$this->tablesUsed[] = 'revision';
		$this->tablesUsed[] = 'comment';
	}

	/**
	 * @covers MovePage::__construct
	 */
	public function testConstructorDefaults() {
		$services = MediaWikiServices::getInstance();

		$obj1 = new MovePage( Title::newFromText( 'A' ), Title::newFromText( 'B' ) );
		$obj2 = new MovePage(
			Title::newFromText( 'A' ),
			Title::newFromText( 'B' ),
			new ServiceOptions( MovePageFactory::$constructorOptions, $services->getMainConfig() ),
			$services->getDBLoadBalancer(),
			$services->getNamespaceInfo(),
			$services->getWatchedItemStore(),
			$services->getPermissionManager(),
			$services->getRepoGroup(),
			$services->getTitleFormatter()
		);

		$this->assertEquals( $obj2, $obj1 );
	}

	/**
	 * @dataProvider provideIsValidMove
	 * @covers MovePage::isValidMove
	 * @covers MovePage::isValidMoveTarget
	 * @covers MovePage::isValidFileMove
	 * @covers MovePage::__construct
	 * @covers Title::isValidMoveOperation
	 *
	 * @param string|Title $old
	 * @param string|Title $new
	 * @param array $expectedErrors
	 * @param array $extraOptions
	 */
	public function testIsValidMove(
		$old, $new, array $expectedErrors, array $extraOptions = []
	) {
		if ( is_string( $old ) ) {
			$old = Title::newFromText( $old );
		}
		if ( is_string( $new ) ) {
			$new = Title::newFromText( $new );
		}
		// Can't test MovePage with a null target, only isValidMoveOperation
		if ( $new ) {
			$mp = $this->newMovePage( $old, $new, [ 'options' => $extraOptions ] );
			$this->assertSame( $expectedErrors, $mp->isValidMove()->getErrorsArray() );
		}

		foreach ( $extraOptions as $key => $val ) {
			$this->setMwGlobals( "wg$key", $val );
		}
		$this->setService( 'RepoGroup', $this->getMockRepoGroup() );
		// This returns true instead of an array if there are no errors
		$this->hideDeprecated( 'Title::isValidMoveOperation' );
		$this->assertSame( $expectedErrors ?: true, $old->isValidMoveOperation( $new, false ) );
	}

	public static function provideIsValidMove() {
		global $wgMultiContentRevisionSchemaMigrationStage;
		$ret = [
			'Self move' => [
				'Existent',
				'Existent',
				[ [ 'selfmove' ] ],
			],
			'Move to null' => [
				'Existent',
				null,
				[ [ 'badtitletext' ] ],
			],
			'Move from empty name' => [
				Title::makeTitle( NS_MAIN, '' ),
				'Nonexistent',
				// @todo More specific error message, or make the move valid if the page actually
				// exists somehow in the database
				[ [ 'badarticleerror' ] ],
			],
			'Move to empty name' => [
				'Existent',
				Title::makeTitle( NS_MAIN, '' ),
				[ [ 'movepage-invalid-target-title' ] ],
			],
			'Move to invalid name' => [
				'Existent',
				Title::makeTitle( NS_MAIN, '<' ),
				[ [ 'movepage-invalid-target-title' ] ],
			],
			'Move between invalid names' => [
				Title::makeTitle( NS_MAIN, '<' ),
				Title::makeTitle( NS_MAIN, '>' ),
				// @todo First error message should be more specific, or maybe we should make moving
				// such pages valid if they actually exist somehow in the database
				[ [ 'movepage-source-doesnt-exist' ], [ 'movepage-invalid-target-title' ] ],
			],
			'Move nonexistent' => [
				'Nonexistent',
				'Nonexistent2',
				[ [ 'movepage-source-doesnt-exist' ] ],
			],
			'Move over existing' => [
				'Existent',
				'Existent2',
				[ [ 'articleexists' ] ],
			],
			'Move from another wiki' => [
				Title::makeTitle( NS_MAIN, 'Test', '', 'otherwiki' ),
				'Nonexistent',
				[ [ 'immobile-source-namespace-iw' ] ],
			],
			'Move special page' => [
				'Special:FooBar',
				'Nonexistent',
				[ [ 'immobile-source-namespace', 'Special' ] ],
			],
			'Move to another wiki' => [
				'Existent',
				Title::makeTitle( NS_MAIN, 'Test', '', 'otherwiki' ),
				[ [ 'immobile-target-namespace-iw' ] ],
			],
			'Move to special page' =>
				[ 'Existent', 'Special:FooBar', [ [ 'immobile-target-namespace', 'Special' ] ] ],
			'Move to allowed content model' => [
				'MediaWiki:Existent.js',
				'MediaWiki:Nonexistent',
				[],
			],
			'Move to prohibited content model' => [
				'Existent',
				'No content allowed',
				[ [ 'content-not-allowed-here', 'wikitext', 'No content allowed', 'main' ] ],
			],
			'Aborted by hook' => [
				'Hooked in place',
				'Nonexistent',
				// @todo Error is wrong
				[ [ 'immobile-source-namespace', '' ] ],
			],
			'Doubly aborted by hook' => [
				'Hooked in place',
				'Hooked In Place',
				// @todo Both errors are wrong
				[ [ 'immobile-source-namespace', '' ], [ 'immobile-target-namespace', '' ] ],
			],
			'Non-file to file' =>
				[ 'Existent', 'File:Nonexistent.jpg', [ [ 'nonfile-cannot-move-to-file' ] ] ],
			'File to non-file' => [
				'File:Existent.jpg',
				'Nonexistent',
				[ [ 'imagenocrossnamespace' ] ],
			],
			'Existing file to non-existing file' => [
				'File:Existent.jpg',
				'File:Nonexistent.jpg',
				[],
			],
			'Existing file to existing file' => [
				'File:Existent.jpg',
				'File:Existent2.jpg',
				[ [ 'articleexists' ] ],
			],
			'Existing file to existing file with no page' => [
				'File:Existent.jpg',
				'File:Existent-file-no-page.jpg',
				// @todo Is this correct? Moving over an existing file with no page should succeed?
				[],
			],
			'Existing file to name with slash' => [
				'File:Existent.jpg',
				'File:Existent/slashed.jpg',
				[ [ 'imageinvalidfilename' ] ],
			],
			'Mismatched file extension' => [
				'File:Existent.jpg',
				'File:Nonexistent.png',
				[ [ 'imagetypemismatch' ] ],
			],
		];
		if ( $wgMultiContentRevisionSchemaMigrationStage === SCHEMA_COMPAT_OLD ) {
			// ContentHandlerUseDB = false only works with the old schema
			$ret['Move to different content model (ContentHandlerUseDB false)'] = [
				'MediaWiki:Existent.js',
				'MediaWiki:Nonexistent',
				[ [ 'bad-target-model', 'JavaScript', 'wikitext' ] ],
				[ 'ContentHandlerUseDB' => false ],
			];
		}
		return $ret;
	}

	/**
	 * @dataProvider provideMove
	 * @covers MovePage::move
	 *
	 * @param string $old Old name
	 * @param string $new New name
	 * @param array $expectedErrors
	 * @param array $extraOptions
	 */
	public function testMove( $old, $new, array $expectedErrors, array $extraOptions = [] ) {
		if ( is_string( $old ) ) {
			$old = Title::newFromText( $old );
		}
		if ( is_string( $new ) ) {
			$new = Title::newFromText( $new );
		}

		$params = [ 'options' => $extraOptions ];
		if ( $expectedErrors === [] ) {
			$this->markTestIncomplete( 'Checking actual moves has not yet been implemented' );
		}

		$obj = $this->newMovePage( $old, $new, $params );
		$status = $obj->move( $this->getTestUser()->getUser() );
		$this->assertSame( $expectedErrors, $status->getErrorsArray() );
	}

	public static function provideMove() {
		$ret = [];
		foreach ( self::provideIsValidMove() as $name => $arr ) {
			list( $old, $new, $expectedErrors, $extraOptions ) = array_pad( $arr, 4, [] );
			if ( !$new ) {
				// Not supported by testMove
				continue;
			}
			$ret[$name] = $arr;
		}
		return $ret;
	}

	/**
	 * Integration test to catch regressions like T74870. Taken and modified
	 * from SemanticMediaWiki
	 *
	 * @covers Title::moveTo
	 * @covers MovePage::move
	 */
	public function testTitleMoveCompleteIntegrationTest() {
		$this->hideDeprecated( 'Title::moveTo' );

		$oldTitle = Title::newFromText( 'Help:Some title' );
		WikiPage::factory( $oldTitle )->doEditContent( new WikitextContent( 'foo' ), 'bar' );
		$newTitle = Title::newFromText( 'Help:Some other title' );
		$this->assertNull(
			WikiPage::factory( $newTitle )->getRevision()
		);

		$this->assertTrue( $oldTitle->moveTo( $newTitle, false, 'test1', true ) );
		$this->assertNotNull(
			WikiPage::factory( $oldTitle )->getRevision()
		);
		$this->assertNotNull(
			WikiPage::factory( $newTitle )->getRevision()
		);
	}

	/**
	 * Test for the move operation being aborted via the TitleMove hook
	 * @covers MovePage::move
	 */
	public function testMoveAbortedByTitleMoveHook() {
		$error = 'Preventing move operation with TitleMove hook.';
		$this->setTemporaryHook( 'TitleMove',
			function ( $old, $new, $user, $reason, $status ) use ( $error ) {
				$status->fatal( $error );
			}
		);

		$oldTitle = Title::newFromText( 'Some old title' );
		WikiPage::factory( $oldTitle )->doEditContent( new WikitextContent( 'foo' ), 'bar' );
		$newTitle = Title::newFromText( 'A brand new title' );
		$mp = $this->newMovePage( $oldTitle, $newTitle );
		$user = User::newFromName( 'TitleMove tester' );
		$status = $mp->move( $user, 'Reason', true );
		$this->assertTrue( $status->hasMessage( $error ) );
	}

	/**
	 * Test moving subpages from one page to another
	 * @covers MovePage::moveSubpages
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
		$mp = new MovePage( $oldTitle, $newTitle );
		$status = $mp->moveSubpages( $this->getTestUser()->getUser(), 'Reason', true );

		$this->assertTrue( $status->isGood(),
			"Moving subpages from Talk:{$name} to Talk:{$name} 2 was not completely successful." );
		foreach ( $subPages as $page ) {
			$this->assertMoved( $page, str_replace( $name, "$name 2", $page ), $ids[$page] );
		}
	}

	/**
	 * Test moving subpages from one page to another
	 * @covers MovePage::moveSubpagesIfAllowed
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
		$mp = new MovePage( $oldTitle, $newTitle );
		$status = $mp->moveSubpagesIfAllowed( $this->getTestUser()->getUser(), 'Reason', true );

		$this->assertTrue( $status->isGood(),
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
		return $this->editPage( $name, 'Content' )->value['revision']->getPage();
	}

	/**
	 * @param string $from Prefixed name of source
	 * @param string $to Prefixed name of destination
	 * @param string $id Page id of the page to move
	 * @param array|string|null $opts Options: 'noredirect' to expect no redirect
	 */
	protected function assertMoved( $from, $to, $id, $opts = null ) {
		$opts = (array)$opts;

		Title::clearCaches();
		$fromTitle = Title::newFromText( $from );
		$toTitle = Title::newFromText( $to );

		$this->assertTrue( $toTitle->exists(),
			"Destination {$toTitle->getPrefixedText()} does not exist" );

		if ( in_array( 'noredirect', $opts ) ) {
			$this->assertFalse( $fromTitle->exists(),
				"Source {$fromTitle->getPrefixedText()} exists" );
		} else {
			$this->assertTrue( $fromTitle->exists(),
				"Source {$fromTitle->getPrefixedText()} does not exist" );
			$this->assertTrue( $fromTitle->isRedirect(),
				"Source {$fromTitle->getPrefixedText()} is not a redirect" );

			$target = Revision::newFromTitle( $fromTitle )->getContent()->getRedirectTarget();
			$this->assertSame( $toTitle->getPrefixedText(), $target->getPrefixedText() );
		}

		$this->assertSame( $id, $toTitle->getArticleID() );
	}
}
