<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Page\MovePageFactory;
use MediaWiki\Permissions\PermissionManager;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * @group Database
 */
class MovePageTest extends MediaWikiTestCase {
	/**
	 * @param string $class
	 * @return object A mock that throws on any method call
	 */
	private function getNoOpMock( $class ) {
		$mock = $this->createMock( $class );
		$mock->expects( $this->never() )->method( $this->anythingBut( '__destruct' ) );
		return $mock;
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
			->willReturn( $params['db'] ?? $this->getNoOpMock( IDatabase::class ) );
		$mockLB->expects( $this->never() )
			->method( $this->anythingBut( 'getConnection', '__destruct' ) );

		$mockLocalFile = $this->createMock( LocalFile::class );
		$mockLocalFile->method( 'exists' )->willReturn( false );
		$mockLocalFile->expects( $this->never() )
			->method( $this->anythingBut( 'exists', 'load', '__destruct' ) );

		$mockLocalRepo = $this->createMock( LocalRepo::class );
		$mockLocalRepo->method( 'newFile' )->willReturn( $mockLocalFile );
		$mockLocalRepo->expects( $this->never() )
			->method( $this->anythingBut( 'newFile', '__destruct' ) );

		$mockRepoGroup = $this->createMock( RepoGroup::class );
		$mockRepoGroup->method( 'getLocalRepo' )->willReturn( $mockLocalRepo );
		$mockRepoGroup->expects( $this->never() )
			->method( $this->anythingBut( 'getLocalRepo', '__destruct' ) );

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
			$params['nsInfo'] ?? $this->getNoOpMock( NamespaceInfo::class ),
			$params['wiStore'] ?? $this->getNoOpMock( WatchedItemStore::class ),
			$params['permMgr'] ?? $this->getNoOpMock( PermissionManager::class ),
			$params['repoGroup'] ?? $mockRepoGroup
		);
	}

	public function setUp() {
		parent::setUp();
		$this->tablesUsed[] = 'page';
		$this->tablesUsed[] = 'revision';
		$this->tablesUsed[] = 'comment';
	}

	/**
	 * @dataProvider provideIsValidMove
	 * @covers MovePage::isValidMove
	 * @covers MovePage::isValidFileMove
	 */
	public function testIsValidMove( $old, $new, $error ) {
		global $wgMultiContentRevisionSchemaMigrationStage;
		if ( $wgMultiContentRevisionSchemaMigrationStage === SCHEMA_COMPAT_OLD ) {
			// We can only set this to false with the old schema
			$this->setMwGlobals( 'wgContentHandlerUseDB', false );
		}
		$mp = $this->newMovePage(
			Title::newFromText( $old ),
			Title::newFromText( $new ),
			[ 'options' => [ 'ContentHandlerUseDB' => false ] ]
		);
		$status = $mp->isValidMove();
		if ( $error === true ) {
			$this->assertTrue( $status->isGood() );
		} else {
			$this->assertTrue( $status->hasMessage( $error ) );
		}
	}

	/**
	 * This should be kept in sync with TitleTest::provideTestIsValidMoveOperation
	 */
	public static function provideIsValidMove() {
		global $wgMultiContentRevisionSchemaMigrationStage;
		$ret = [
			// for MovePage::isValidMove
			[ 'Test', 'Test', 'selfmove' ],
			[ 'Special:FooBar', 'Test', 'immobile-source-namespace' ],
			[ 'Test', 'Special:FooBar', 'immobile-target-namespace' ],
			[ 'Page', 'File:Test.jpg', 'nonfile-cannot-move-to-file' ],
			// for MovePage::isValidFileMove
			[ 'File:Test.jpg', 'Page', 'imagenocrossnamespace' ],
		];
		if ( $wgMultiContentRevisionSchemaMigrationStage === SCHEMA_COMPAT_OLD ) {
			// The error can only occur if $wgContentHandlerUseDB is false, which doesn't work with
			// the new schema, so omit the test in that case
			array_push( $ret,
				[ 'MediaWiki:Common.js', 'Help:Some wikitext page', 'bad-target-model' ] );
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
