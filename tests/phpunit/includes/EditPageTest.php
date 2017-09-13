<?php

/**
 * @group Editing
 *
 * @group Database
 *        ^--- tell jenkins this test needs the database
 *
 * @group medium
 *        ^--- tell phpunit that these test cases may take longer than 2 seconds.
 */
class EditPageTest extends MediaWikiLangTestCase {

	protected function setUp() {
		global $wgExtraNamespaces, $wgNamespaceContentModels, $wgContentHandlers, $wgContLang;

		parent::setUp();

		$this->setMwGlobals( [
			'wgExtraNamespaces' => $wgExtraNamespaces,
			'wgNamespaceContentModels' => $wgNamespaceContentModels,
			'wgContentHandlers' => $wgContentHandlers,
			'wgContLang' => $wgContLang,
		] );

		$wgExtraNamespaces[12312] = 'Dummy';
		$wgExtraNamespaces[12313] = 'Dummy_talk';

		$wgNamespaceContentModels[12312] = "testing";
		$wgContentHandlers["testing"] = 'DummyContentHandlerForTesting';

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache
	}

	/**
	 * @dataProvider provideExtractSectionTitle
	 * @covers EditPage::extractSectionTitle
	 */
	public function testExtractSectionTitle( $section, $title ) {
		$extracted = EditPage::extractSectionTitle( $section );
		$this->assertEquals( $title, $extracted );
	}

	public static function provideExtractSectionTitle() {
		return [
			[
				"== Test ==\n\nJust a test section.",
				"Test"
			],
			[
				"An initial section, no header.",
				false
			],
			[
				"An initial section with a fake heder (T34617)\n\n== Test == ??\nwtf",
				false
			],
			[
				"== Section ==\nfollowed by a fake == Non-section == ??\nnoooo",
				"Section"
			],
			[
				"== Section== \t\r\n followed by whitespace (T37051)",
				'Section',
			],
		];
	}

	protected function forceRevisionDate( WikiPage $page, $timestamp ) {
		$dbw = wfGetDB( DB_MASTER );

		$dbw->update( 'revision',
			[ 'rev_timestamp' => $dbw->timestamp( $timestamp ) ],
			[ 'rev_id' => $page->getLatest() ] );

		$page->clear();
	}

	/**
	 * User input text is passed to rtrim() by edit page. This is a simple
	 * wrapper around assertEquals() which calls rrtrim() to normalize the
	 * expected and actual texts.
	 * @param string $expected
	 * @param string $actual
	 * @param string $msg
	 */
	protected function assertEditedTextEquals( $expected, $actual, $msg = '' ) {
		$this->assertEquals( rtrim( $expected ), rtrim( $actual ), $msg );
	}

	/**
	 * Performs an edit and checks the result.
	 *
	 * @param string|Title $title The title of the page to edit
	 * @param string|null $baseText Some text to create the page with before attempting the edit.
	 * @param User|string|null $user The user to perform the edit as.
	 * @param array $edit An array of request parameters used to define the edit to perform.
	 *              Some well known fields are:
	 *              * wpTextbox1: the text to submit
	 *              * wpSummary: the edit summary
	 *              * wpEditToken: the edit token (will be inserted if not provided)
	 *              * wpEdittime: timestamp of the edit's base revision (will be inserted
	 *                if not provided)
	 *              * wpStarttime: timestamp when the edit started (will be inserted if not provided)
	 *              * wpSectionTitle: the section to edit
	 *              * wpMinorEdit: mark as minor edit
	 *              * wpWatchthis: whether to watch the page
	 * @param int|null $expectedCode The expected result code (EditPage::AS_XXX constants).
	 *                  Set to null to skip the check.
	 * @param string|null $expectedText The text expected to be on the page after the edit.
	 *                  Set to null to skip the check.
	 * @param string|null $message An optional message to show along with any error message.
	 *
	 * @return WikiPage The page that was just edited, useful for getting the edit's rev_id, etc.
	 */
	protected function assertEdit( $title, $baseText, $user = null, array $edit,
		$expectedCode = null, $expectedText = null, $message = null
	) {
		if ( is_string( $title ) ) {
			$ns = $this->getDefaultWikitextNS();
			$title = Title::newFromText( $title, $ns );
		}
		$this->assertNotNull( $title );

		if ( is_string( $user ) ) {
			$user = User::newFromName( $user );

			if ( $user->getId() === 0 ) {
				$user->addToDatabase();
			}
		}

		$page = WikiPage::factory( $title );

		if ( $baseText !== null ) {
			$content = ContentHandler::makeContent( $baseText, $title );
			$page->doEditContent( $content, "base text for test" );
			$this->forceRevisionDate( $page, '20120101000000' );

			// sanity check
			$page->clear();
			$currentText = ContentHandler::getContentText( $page->getContent() );

			# EditPage rtrim() the user input, so we alter our expected text
			# to reflect that.
			$this->assertEditedTextEquals( $baseText, $currentText );
		}

		if ( $user == null ) {
			$user = $GLOBALS['wgUser'];
		} else {
			$this->setMwGlobals( 'wgUser', $user );
		}

		if ( !isset( $edit['wpEditToken'] ) ) {
			$edit['wpEditToken'] = $user->getEditToken();
		}

		if ( !isset( $edit['wpEdittime'] ) ) {
			$edit['wpEdittime'] = $page->exists() ? $page->getTimestamp() : '';
		}

		if ( !isset( $edit['wpStarttime'] ) ) {
			$edit['wpStarttime'] = wfTimestampNow();
		}

		if ( !isset( $edit['wpUnicodeCheck'] ) ) {
			$edit['wpUnicodeCheck'] = EditPage::UNICODE_CHECK;
		}

		$req = new FauxRequest( $edit, true ); // session ??

		$article = new Article( $title );
		$article->getContext()->setTitle( $title );
		$ep = new EditPage( $article );
		$ep->setContextTitle( $title );
		$ep->importFormData( $req );

		$bot = isset( $edit['bot'] ) ? (bool)$edit['bot'] : false;

		// this is where the edit happens!
		// Note: don't want to use EditPage::AttemptSave, because it messes with $wgOut
		// and throws exceptions like PermissionsError
		$status = $ep->internalAttemptSave( $result, $bot );

		if ( $expectedCode !== null ) {
			// check edit code
			$this->assertEquals( $expectedCode, $status->value,
				"Expected result code mismatch. $message" );
		}

		$page = WikiPage::factory( $title );

		if ( $expectedText !== null ) {
			// check resulting page text
			$content = $page->getContent();
			$text = ContentHandler::getContentText( $content );

			# EditPage rtrim() the user input, so we alter our expected text
			# to reflect that.
			$this->assertEditedTextEquals( $expectedText, $text,
				"Expected article text mismatch. $message" );
		}

		return $page;
	}

	public static function provideCreatePages() {
		return [
			[ 'expected article being created',
				'EditPageTest_testCreatePage',
				null,
				'Hello World!',
				EditPage::AS_SUCCESS_NEW_ARTICLE,
				'Hello World!'
			],
			[ 'expected article not being created if empty',
				'EditPageTest_testCreatePage',
				null,
				'',
				EditPage::AS_BLANK_ARTICLE,
				null
			],
			[ 'expected MediaWiki: page being created',
				'MediaWiki:January',
				'UTSysop',
				'Not January',
				EditPage::AS_SUCCESS_NEW_ARTICLE,
				'Not January'
			],
			[ 'expected not-registered MediaWiki: page not being created if empty',
				'MediaWiki:EditPageTest_testCreatePage',
				'UTSysop',
				'',
				EditPage::AS_BLANK_ARTICLE,
				null
			],
			[ 'expected registered MediaWiki: page being created even if empty',
				'MediaWiki:January',
				'UTSysop',
				'',
				EditPage::AS_SUCCESS_NEW_ARTICLE,
				''
			],
			[ 'expected registered MediaWiki: page whose default content is empty'
					. ' not being created if empty',
				'MediaWiki:Ipb-default-expiry',
				'UTSysop',
				'',
				EditPage::AS_BLANK_ARTICLE,
				''
			],
			[ 'expected MediaWiki: page not being created if text equals default message',
				'MediaWiki:January',
				'UTSysop',
				'January',
				EditPage::AS_BLANK_ARTICLE,
				null
			],
			[ 'expected empty article being created',
				'EditPageTest_testCreatePage',
				null,
				'',
				EditPage::AS_SUCCESS_NEW_ARTICLE,
				'',
				true
			],
		];
	}

	/**
	 * @dataProvider provideCreatePages
	 * @covers EditPage
	 */
	public function testCreatePage(
		$desc, $pageTitle, $user, $editText, $expectedCode, $expectedText, $ignoreBlank = false
	) {
		$checkId = null;

		$this->setMwGlobals( 'wgHooks', [
			'PageContentInsertComplete' => [ function (
				WikiPage &$page, User &$user, Content $content,
				$summary, $minor, $u1, $u2, &$flags, Revision $revision
			) {
				// types/refs checked
			} ],
			'PageContentSaveComplete' => [ function (
				WikiPage &$page, User &$user, Content $content,
				$summary, $minor, $u1, $u2, &$flags, Revision $revision,
				Status &$status, $baseRevId
			) use ( &$checkId ) {
				$checkId = $status->value['revision']->getId();
				// types/refs checked
			} ],
		] );

		$edit = [ 'wpTextbox1' => $editText ];
		if ( $ignoreBlank ) {
			$edit['wpIgnoreBlankArticle'] = 1;
		}

		$page = $this->assertEdit( $pageTitle, null, $user, $edit, $expectedCode, $expectedText, $desc );

		if ( $expectedCode != EditPage::AS_BLANK_ARTICLE ) {
			$latest = $page->getLatest();
			$page->doDeleteArticleReal( $pageTitle );

			$this->assertGreaterThan( 0, $latest, "Page revision ID updated in object" );
			$this->assertEquals( $latest, $checkId, "Revision in Status for hook" );
		}
	}

	/**
	 * @dataProvider provideCreatePages
	 * @covers EditPage
	 */
	public function testCreatePageTrx(
		$desc, $pageTitle, $user, $editText, $expectedCode, $expectedText, $ignoreBlank = false
	) {
		$checkIds = [];
		$this->setMwGlobals( 'wgHooks', [
			'PageContentInsertComplete' => [ function (
				WikiPage &$page, User &$user, Content $content,
				$summary, $minor, $u1, $u2, &$flags, Revision $revision
			) {
				// types/refs checked
			} ],
			'PageContentSaveComplete' => [ function (
				WikiPage &$page, User &$user, Content $content,
				$summary, $minor, $u1, $u2, &$flags, Revision $revision,
				Status &$status, $baseRevId
			) use ( &$checkIds ) {
				$checkIds[] = $status->value['revision']->getId();
				// types/refs checked
			} ],
		] );

		wfGetDB( DB_MASTER )->begin( __METHOD__ );

		$edit = [ 'wpTextbox1' => $editText ];
		if ( $ignoreBlank ) {
			$edit['wpIgnoreBlankArticle'] = 1;
		}

		$page = $this->assertEdit(
			$pageTitle, null, $user, $edit, $expectedCode, $expectedText, $desc );

		$pageTitle2 = (string)$pageTitle . '/x';
		$page2 = $this->assertEdit(
			$pageTitle2, null, $user, $edit, $expectedCode, $expectedText, $desc );

		wfGetDB( DB_MASTER )->commit( __METHOD__ );

		$this->assertEquals( 0, DeferredUpdates::pendingUpdatesCount(), 'No deferred updates' );

		if ( $expectedCode != EditPage::AS_BLANK_ARTICLE ) {
			$latest = $page->getLatest();
			$page->doDeleteArticleReal( $pageTitle );

			$this->assertGreaterThan( 0, $latest, "Page #1 revision ID updated in object" );
			$this->assertEquals( $latest, $checkIds[0], "Revision #1 in Status for hook" );

			$latest2 = $page2->getLatest();
			$page2->doDeleteArticleReal( $pageTitle2 );

			$this->assertGreaterThan( 0, $latest2, "Page #2 revision ID updated in object" );
			$this->assertEquals( $latest2, $checkIds[1], "Revision #2 in Status for hook" );
		}
	}

	public function testUpdatePage() {
		$checkIds = [];

		$this->setMwGlobals( 'wgHooks', [
			'PageContentInsertComplete' => [ function (
				WikiPage &$page, User &$user, Content $content,
				$summary, $minor, $u1, $u2, &$flags, Revision $revision
			) {
				// types/refs checked
			} ],
			'PageContentSaveComplete' => [ function (
				WikiPage &$page, User &$user, Content $content,
				$summary, $minor, $u1, $u2, &$flags, Revision $revision,
				Status &$status, $baseRevId
			) use ( &$checkIds ) {
				$checkIds[] = $status->value['revision']->getId();
				// types/refs checked
			} ],
		] );

		$text = "one";
		$edit = [
			'wpTextbox1' => $text,
			'wpSummary' => 'first update',
		];

		$page = $this->assertEdit( 'EditPageTest_testUpdatePage', "zero", null, $edit,
			EditPage::AS_SUCCESS_UPDATE, $text,
			"expected successfull update with given text" );
		$this->assertGreaterThan( 0, $checkIds[0], "First event rev ID set" );

		$this->forceRevisionDate( $page, '20120101000000' );

		$text = "two";
		$edit = [
			'wpTextbox1' => $text,
			'wpSummary' => 'second update',
		];

		$this->assertEdit( 'EditPageTest_testUpdatePage', null, null, $edit,
			EditPage::AS_SUCCESS_UPDATE, $text,
			"expected successfull update with given text" );
		$this->assertGreaterThan( 0, $checkIds[1], "Second edit hook rev ID set" );
		$this->assertGreaterThan( $checkIds[0], $checkIds[1], "Second event rev ID is higher" );
	}

	public function testUpdatePageTrx() {
		$text = "one";
		$edit = [
			'wpTextbox1' => $text,
			'wpSummary' => 'first update',
		];

		$page = $this->assertEdit( 'EditPageTest_testTrxUpdatePage', "zero", null, $edit,
			EditPage::AS_SUCCESS_UPDATE, $text,
			"expected successfull update with given text" );

		$this->forceRevisionDate( $page, '20120101000000' );

		$checkIds = [];
		$this->setMwGlobals( 'wgHooks', [
			'PageContentSaveComplete' => [ function (
				WikiPage &$page, User &$user, Content $content,
				$summary, $minor, $u1, $u2, &$flags, Revision $revision,
				Status &$status, $baseRevId
			) use ( &$checkIds ) {
				$checkIds[] = $status->value['revision']->getId();
				// types/refs checked
			} ],
		] );

		wfGetDB( DB_MASTER )->begin( __METHOD__ );

		$text = "two";
		$edit = [
			'wpTextbox1' => $text,
			'wpSummary' => 'second update',
		];

		$this->assertEdit( 'EditPageTest_testTrxUpdatePage', null, null, $edit,
			EditPage::AS_SUCCESS_UPDATE, $text,
			"expected successfull update with given text" );

		$text = "three";
		$edit = [
			'wpTextbox1' => $text,
			'wpSummary' => 'third update',
		];

		$this->assertEdit( 'EditPageTest_testTrxUpdatePage', null, null, $edit,
			EditPage::AS_SUCCESS_UPDATE, $text,
			"expected successfull update with given text" );

		wfGetDB( DB_MASTER )->commit( __METHOD__ );

		$this->assertGreaterThan( 0, $checkIds[0], "First event rev ID set" );
		$this->assertGreaterThan( 0, $checkIds[1], "Second edit hook rev ID set" );
		$this->assertGreaterThan( $checkIds[0], $checkIds[1], "Second event rev ID is higher" );
	}

	public static function provideSectionEdit() {
		$text = 'Intro

== one ==
first section.

== two ==
second section.
';

		$sectionOne = '== one ==
hello
';

		$newSection = '== new section ==

hello
';

		$textWithNewSectionOne = preg_replace(
			'/== one ==.*== two ==/ms',
			"$sectionOne\n== two ==", $text
		);

		$textWithNewSectionAdded = "$text\n$newSection";

		return [
			[ # 0
				$text,
				'',
				'hello',
				'replace all',
				'hello'
			],

			[ # 1
				$text,
				'1',
				$sectionOne,
				'replace first section',
				$textWithNewSectionOne,
			],

			[ # 2
				$text,
				'new',
				'hello',
				'new section',
				$textWithNewSectionAdded,
			],
		];
	}

	/**
	 * @dataProvider provideSectionEdit
	 * @covers EditPage
	 */
	public function testSectionEdit( $base, $section, $text, $summary, $expected ) {
		$edit = [
			'wpTextbox1' => $text,
			'wpSummary' => $summary,
			'wpSection' => $section,
		];

		$this->assertEdit( 'EditPageTest_testSectionEdit', $base, null, $edit,
			EditPage::AS_SUCCESS_UPDATE, $expected,
			"expected successfull update of section" );
	}

	public static function provideAutoMerge() {
		$tests = [];

		$tests[] = [ # 0: plain conflict
			"Elmo", # base edit user
			"one\n\ntwo\n\nthree\n",
			[ # adam's edit
				'wpStarttime' => 1,
				'wpTextbox1' => "ONE\n\ntwo\n\nthree\n",
			],
			[ # berta's edit
				'wpStarttime' => 2,
				'wpTextbox1' => "(one)\n\ntwo\n\nthree\n",
			],
			EditPage::AS_CONFLICT_DETECTED, # expected code
			"ONE\n\ntwo\n\nthree\n", # expected text
			'expected edit conflict', # message
		];

		$tests[] = [ # 1: successful merge
			"Elmo", # base edit user
			"one\n\ntwo\n\nthree\n",
			[ # adam's edit
				'wpStarttime' => 1,
				'wpTextbox1' => "ONE\n\ntwo\n\nthree\n",
			],
			[ # berta's edit
				'wpStarttime' => 2,
				'wpTextbox1' => "one\n\ntwo\n\nTHREE\n",
			],
			EditPage::AS_SUCCESS_UPDATE, # expected code
			"ONE\n\ntwo\n\nTHREE\n", # expected text
			'expected automatic merge', # message
		];

		$text = "Intro\n\n";
		$text .= "== first section ==\n\n";
		$text .= "one\n\ntwo\n\nthree\n\n";
		$text .= "== second section ==\n\n";
		$text .= "four\n\nfive\n\nsix\n\n";

		// extract the first section.
		$section = preg_replace( '/.*(== first section ==.*)== second section ==.*/sm', '$1', $text );

		// generate expected text after merge
		$expected = str_replace( 'one', 'ONE', str_replace( 'three', 'THREE', $text ) );

		$tests[] = [ # 2: merge in section
			"Elmo", # base edit user
			$text,
			[ # adam's edit
				'wpStarttime' => 1,
				'wpTextbox1' => str_replace( 'one', 'ONE', $section ),
				'wpSection' => '1'
			],
			[ # berta's edit
				'wpStarttime' => 2,
				'wpTextbox1' => str_replace( 'three', 'THREE', $section ),
				'wpSection' => '1'
			],
			EditPage::AS_SUCCESS_UPDATE, # expected code
			$expected, # expected text
			'expected automatic section merge', # message
		];

		// see whether it makes a difference who did the base edit
		$testsWithAdam = array_map( function ( $test ) {
			$test[0] = 'Adam'; // change base edit user
			return $test;
		}, $tests );

		$testsWithBerta = array_map( function ( $test ) {
			$test[0] = 'Berta'; // change base edit user
			return $test;
		}, $tests );

		return array_merge( $tests, $testsWithAdam, $testsWithBerta );
	}

	/**
	 * @dataProvider provideAutoMerge
	 * @covers EditPage
	 */
	public function testAutoMerge( $baseUser, $text, $adamsEdit, $bertasEdit,
		$expectedCode, $expectedText, $message = null
	) {
		$this->markTestSkippedIfNoDiff3();

		// create page
		$ns = $this->getDefaultWikitextNS();
		$title = Title::newFromText( 'EditPageTest_testAutoMerge', $ns );
		$page = WikiPage::factory( $title );

		if ( $page->exists() ) {
			$page->doDeleteArticle( "clean slate for testing" );
		}

		$baseEdit = [
			'wpTextbox1' => $text,
		];

		$page = $this->assertEdit( 'EditPageTest_testAutoMerge', null,
			$baseUser, $baseEdit, null, null, __METHOD__ );

		$this->forceRevisionDate( $page, '20120101000000' );

		$edittime = $page->getTimestamp();

		// start timestamps for conflict detection
		if ( !isset( $adamsEdit['wpStarttime'] ) ) {
			$adamsEdit['wpStarttime'] = 1;
		}

		if ( !isset( $bertasEdit['wpStarttime'] ) ) {
			$bertasEdit['wpStarttime'] = 2;
		}

		$starttime = wfTimestampNow();
		$adamsTime = wfTimestamp(
			TS_MW,
			(int)wfTimestamp( TS_UNIX, $starttime ) + (int)$adamsEdit['wpStarttime']
		);
		$bertasTime = wfTimestamp(
			TS_MW,
			(int)wfTimestamp( TS_UNIX, $starttime ) + (int)$bertasEdit['wpStarttime']
		);

		$adamsEdit['wpStarttime'] = $adamsTime;
		$bertasEdit['wpStarttime'] = $bertasTime;

		$adamsEdit['wpSummary'] = 'Adam\'s edit';
		$bertasEdit['wpSummary'] = 'Bertas\'s edit';

		$adamsEdit['wpEdittime'] = $edittime;
		$bertasEdit['wpEdittime'] = $edittime;

		// first edit
		$this->assertEdit( 'EditPageTest_testAutoMerge', null, 'Adam', $adamsEdit,
			EditPage::AS_SUCCESS_UPDATE, null, "expected successfull update" );

		// second edit
		$this->assertEdit( 'EditPageTest_testAutoMerge', null, 'Berta', $bertasEdit,
			$expectedCode, $expectedText, $message );
	}

	/**
	 * @depends testAutoMerge
	 */
	public function testCheckDirectEditingDisallowed_forNonTextContent() {
		$title = Title::newFromText( 'Dummy:NonTextPageForEditPage' );
		$page = WikiPage::factory( $title );

		$article = new Article( $title );
		$article->getContext()->setTitle( $title );
		$ep = new EditPage( $article );
		$ep->setContextTitle( $title );

		$user = $GLOBALS['wgUser'];

		$edit = [
			'wpTextbox1' => serialize( 'non-text content' ),
			'wpEditToken' => $user->getEditToken(),
			'wpEdittime' => '',
			'wpStarttime' => wfTimestampNow(),
			'wpUnicodeCheck' => EditPage::UNICODE_CHECK,
		];

		$req = new FauxRequest( $edit, true );
		$ep->importFormData( $req );

		$this->setExpectedException(
			'MWException',
			'This content model is not supported: testing'
		);

		$ep->internalAttemptSave( $result, false );
	}

}
