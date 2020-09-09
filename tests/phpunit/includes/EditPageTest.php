<?php

use MediaWiki\MediaWikiServices;

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

	protected function setUp() : void {
		parent::setUp();

		$contLang = MediaWikiServices::getInstance()->getContentLanguage();
		$this->setContentLang( $contLang );

		$this->setMwGlobals( [
			'wgExtraNamespaces' => [
				12312 => 'Dummy',
				12313 => 'Dummy_talk',
			],
			'wgNamespaceContentModels' => [ 12312 => 'testing' ],
		] );
		$this->mergeMwGlobalArrayValue(
			'wgContentHandlers',
			[ 'testing' => 'DummyContentHandlerForTesting' ]
		);
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
	 *              * editRevId: revision ID of the edit's base revision (optional)
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
	protected function assertEdit( $title, $baseText, $user, array $edit,
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

		if ( !isset( $edit['wpEdittime'] ) && !isset( $edit['editRevId'] ) ) {
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
		$article->getContext()->setUser( $user );
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
		$this->hideDeprecated( 'Revision::__construct' );
		$this->hideDeprecated( 'PageContentInsertComplete hook' );
		$this->hideDeprecated( 'PageContentSaveComplete hook' );

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
				$checkId = $status->value['revision-record']->getId();
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
			$page->doDeleteArticleReal( $pageTitle, $this->getTestSysop()->getUser() );

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
		$this->hideDeprecated( 'Revision::__construct' );
		$this->hideDeprecated( 'PageContentInsertComplete hook' );
		$this->hideDeprecated( 'PageContentSaveComplete hook' );

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
				$checkIds[] = $status->value['revision-record']->getId();
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

		$this->assertSame( 0, DeferredUpdates::pendingUpdatesCount(), 'No deferred updates' );

		if ( $expectedCode != EditPage::AS_BLANK_ARTICLE ) {
			$latest = $page->getLatest();
			$page->doDeleteArticleReal( $pageTitle, $this->getTestSysop()->getUser() );

			$this->assertGreaterThan( 0, $latest, "Page #1 revision ID updated in object" );
			$this->assertEquals( $latest, $checkIds[0], "Revision #1 in Status for hook" );

			$latest2 = $page2->getLatest();
			$page2->doDeleteArticleReal( $pageTitle2, $this->getTestSysop()->getUser() );

			$this->assertGreaterThan( 0, $latest2, "Page #2 revision ID updated in object" );
			$this->assertEquals( $latest2, $checkIds[1], "Revision #2 in Status for hook" );
		}
	}

	/**
	 * @covers EditPage
	 */
	public function testUpdatePage() {
		$this->hideDeprecated( 'Revision::__construct' );
		$this->hideDeprecated( 'PageContentInsertComplete hook' );
		$this->hideDeprecated( 'PageContentSaveComplete hook' );

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
				$checkIds[] = $status->value['revision-record']->getId();
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
			"expected successful update with given text" );
		$this->assertGreaterThan( 0, $checkIds[0], "First event rev ID set" );

		$this->forceRevisionDate( $page, '20120101000000' );

		$text = "two";
		$edit = [
			'wpTextbox1' => $text,
			'wpSummary' => 'second update',
		];

		$this->assertEdit( 'EditPageTest_testUpdatePage', null, null, $edit,
			EditPage::AS_SUCCESS_UPDATE, $text,
			"expected successful update with given text" );
		$this->assertGreaterThan( 0, $checkIds[1], "Second edit hook rev ID set" );
		$this->assertGreaterThan( $checkIds[0], $checkIds[1], "Second event rev ID is higher" );
	}

	/**
	 * @covers EditPage
	 */
	public function testUpdatePageTrx() {
		$this->hideDeprecated( 'Revision::__construct' );
		$this->hideDeprecated( 'PageContentSaveComplete hook' );

		$text = "one";
		$edit = [
			'wpTextbox1' => $text,
			'wpSummary' => 'first update',
		];

		$page = $this->assertEdit( 'EditPageTest_testTrxUpdatePage', "zero", null, $edit,
			EditPage::AS_SUCCESS_UPDATE, $text,
			"expected successful update with given text" );

		$this->forceRevisionDate( $page, '20120101000000' );

		$checkIds = [];
		$this->setMwGlobals( 'wgHooks', [
			'PageContentSaveComplete' => [ function (
				WikiPage &$page, User &$user, Content $content,
				$summary, $minor, $u1, $u2, &$flags, Revision $revision,
				Status &$status, $baseRevId
			) use ( &$checkIds ) {
				$checkIds[] = $status->value['revision-record']->getId();
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
			"expected successful update with given text" );

		$text = "three";
		$edit = [
			'wpTextbox1' => $text,
			'wpSummary' => 'third update',
		];

		$this->assertEdit( 'EditPageTest_testTrxUpdatePage', null, null, $edit,
			EditPage::AS_SUCCESS_UPDATE, $text,
			"expected successful update with given text" );

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
			"expected successful update of section" );
	}

	public static function provideConflictDetection() {
		yield 'no conflict detected' => [
			'Adam',
			[
				'wpEdittime' => 2, // use the second edit's time
				'editRevId' => 2, // use the second edit's revision ID
			],
			EditPage::AS_SUCCESS_UPDATE,
			'successful update expected'
		];

		yield 'conflict detected based on wpEdittime' => [
			'Adam',
			[
				'wpEdittime' => 1, // use the first edit's time
			],
			EditPage::AS_CONFLICT_DETECTED,
			'conflict expected'
		];

		yield 'conflict detected based on editRevId' => [
			'Adam',
			[
				'editRevId' => 1, // use the first edit's revision ID
			],
			EditPage::AS_CONFLICT_DETECTED,
			'conflict expected'
		];

		yield 'conflict based on wpEdittime ignored for same user' => [
			'Berta',
			[
				'wpEdittime' => 1, // use the first edit's time
			],
			EditPage::AS_SUCCESS_UPDATE,
			'successful update expected'
		];

		yield 'conflict detected based on editRevId even for same user' => [
			'Berta',
			[
				'editRevId' => 1, // use the first edit's revision ID
			],
			EditPage::AS_CONFLICT_DETECTED,
			'conflict expected'
		];
	}

	/**
	 * @dataProvider provideConflictDetection
	 * @covers EditPage
	 */
	public function testConflictDetection( $editUser, $newEdit, $expectedCode, $message ) {
		// create page
		$ns = $this->getDefaultWikitextNS();
		$title = Title::newFromText( __METHOD__, $ns );
		$page = WikiPage::factory( $title );

		if ( $page->exists() ) {
			$page->doDeleteArticleReal(
				"clean slate for testing",
				$this->getTestSysop()->getUser()
			);
		}

		$elmosEdit['wpTextbox1'] = 'Elmo\'s text';
		$bertasEdit['wpTextbox1'] = 'Berta\'s text';
		$newEdit['wpTextbox1'] = 'new text';

		$elmosEdit['wpSummary'] = 'Elmo\'s edit';
		$bertasEdit['wpSummary'] = 'Bertas\'s edit';
		$newEdit['wpSummary'] = $newEdit['wpSummary'] ?? 'new edit';

		// first edit: Elmo
		$page = $this->assertEdit( __METHOD__, null, 'Elmo', $elmosEdit,
			EditPage::AS_SUCCESS_NEW_ARTICLE, null, 'expected successful creation' );

		$this->forceRevisionDate( $page, '20120101000000' );
		$rev1 = $page->getRevisionRecord();

		// second edit: Berta
		$page = $this->assertEdit( __METHOD__, null, 'Berta', $bertasEdit,
			EditPage::AS_SUCCESS_UPDATE, null, 'expected successful update' );

		$this->forceRevisionDate( $page, '20120101111111' );
		$rev2 = $page->getRevisionRecord();

		if ( !empty( $newEdit['editRevId'] ) ) {
			$newEdit['editRevId'] = $newEdit['editRevId'] === 1 ? $rev1->getId() : $rev2->getId();
		}

		if ( !empty( $newEdit['wpEdittime'] ) ) {
			$newEdit['wpEdittime'] =
				$newEdit['wpEdittime'] === 1 ? $rev1->getTimestamp() : $rev2->getTimestamp();
		}

		// third edit
		$this->assertEdit( __METHOD__, null, $editUser, $newEdit,
			$expectedCode, null, $message );
	}

	public static function provideAutoMerge() {
		$tests = [];

		$tests[] = [ # 0: plain conflict
			"Elmo", # base edit user
			"one\n\ntwo\n\nthree\n",
			[ # adam's edit
				'wpTextbox1' => "ONE\n\ntwo\n\nthree\n",
			],
			[ # berta's edit
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
				'wpTextbox1' => str_replace( 'one', 'ONE', $section ),
				'wpSection' => '1'
			],
			[ # berta's edit
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
			$page->doDeleteArticleReal(
				"clean slate for testing",
				$this->getTestSysop()->getUser()
			);
		}

		$baseEdit = [
			'wpTextbox1' => $text,
		];

		$page = $this->assertEdit( 'EditPageTest_testAutoMerge', null,
			$baseUser, $baseEdit, null, null, __METHOD__ );

		$this->forceRevisionDate( $page, '20120101000000' );

		$edittime = $page->getTimestamp();
		$revId = $page->getLatest();

		$adamsEdit['wpSummary'] = 'Adam\'s edit';
		$bertasEdit['wpSummary'] = 'Bertas\'s edit';

		$adamsEdit['wpEdittime'] = $edittime;
		$bertasEdit['wpEdittime'] = $edittime;

		$adamsEdit['editRevId'] = $revId;
		$bertasEdit['editRevId'] = $revId;

		// first edit
		$this->assertEdit( 'EditPageTest_testAutoMerge', null, 'Adam', $adamsEdit,
			EditPage::AS_SUCCESS_UPDATE, null, "expected successful update" );

		// second edit
		$this->assertEdit( 'EditPageTest_testAutoMerge', null, 'Berta', $bertasEdit,
			$expectedCode, $expectedText, $message );
	}

	/**
	 * @depends testAutoMerge
	 * @covers EditPage
	 */
	public function testCheckDirectEditingDisallowed_forNonTextContent() {
		$user = $GLOBALS['wgUser'];

		$edit = [
			'wpTextbox1' => serialize( 'non-text content' ),
			'wpEditToken' => $user->getEditToken(),
			'wpEdittime' => '',
			'editRevId' => 0,
			'wpStarttime' => wfTimestampNow(),
			'wpUnicodeCheck' => EditPage::UNICODE_CHECK,
		];

		$this->expectException( MWException::class );
		$this->expectExceptionMessage( 'This content model is not supported: testing' );

		$this->doEditDummyNonTextPage( $edit );
	}

	/** @covers EditPage */
	public function testShouldPreventChangingContentModelWhenUserCannotChangeModelForTitle() {
		$this->setTemporaryHook( 'getUserPermissionsErrors',
			function ( Title $page, $user, $action, &$result ) {
				if ( $action === 'editcontentmodel' &&
					 $page->getContentModel() === CONTENT_MODEL_WIKITEXT ) {
					$result = false;

					return false;
				}
			} );

		$user = $GLOBALS['wgUser'];

		$status = $this->doEditDummyNonTextPage( [
			'wpTextbox1' => 'some text',
			'wpEditToken' => $user->getEditToken(),
			'wpEdittime' => '',
			'editRevId' => 0,
			'wpStarttime' => wfTimestampNow(),
			'wpUnicodeCheck' => EditPage::UNICODE_CHECK,
			'model' => CONTENT_MODEL_WIKITEXT,
			'format' => CONTENT_FORMAT_WIKITEXT,
		] );

		$this->assertFalse( $status->isOK() );
		$this->assertEquals( EditPage::AS_NO_CHANGE_CONTENT_MODEL, $status->getValue() );
	}

	/** @covers EditPage */
	public function testShouldPreventChangingContentModelWhenUserCannotEditTargetTitle() {
		$this->setTemporaryHook( 'getUserPermissionsErrors',
			function ( Title $page, $user, $action, &$result ) {
				if ( $action === 'edit' && $page->getContentModel() === CONTENT_MODEL_WIKITEXT ) {
					$result = false;
					return false;
				}
			} );

		$user = $GLOBALS['wgUser'];

		$status = $this->doEditDummyNonTextPage( [
			'wpTextbox1' => 'some text',
			'wpEditToken' => $user->getEditToken(),
			'wpEdittime' => '',
			'editRevId' => 0,
			'wpStarttime' => wfTimestampNow(),
			'wpUnicodeCheck' => EditPage::UNICODE_CHECK,
			'model' => CONTENT_MODEL_WIKITEXT,
			'format' => CONTENT_FORMAT_WIKITEXT,
		] );

		$this->assertFalse( $status->isOK() );
		$this->assertEquals( EditPage::AS_NO_CHANGE_CONTENT_MODEL, $status->getValue() );
	}

	private function doEditDummyNonTextPage( array $edit ): Status {
		$title = Title::newFromText( 'Dummy:NonTextPageForEditPage' );

		$article = new Article( $title );
		$article->getContext()->setTitle( $title );
		$ep = new EditPage( $article );
		$ep->setContextTitle( $title );

		$req = new FauxRequest( $edit, true );
		$ep->importFormData( $req );

		return $ep->internalAttemptSave( $result, false );
	}

	/**
	 * The watchlist expiry field should select the entered value on preview, rather than the
	 * calculated number of days till the expiry (as it shows on edit).
	 * @covers EditPage::getCheckboxesDefinition()
	 * @dataProvider provideWatchlistExpiry()
	 */
	public function testWatchlistExpiry( $existingExpiry, $postVal, $selected, $options ) {
		// Set up config and fake current time.
		$this->setMwGlobals( 'wgWatchlistExpiry', true );
		MWTimestamp::setFakeTime( '20200505120000' );
		$user = $this->getTestUser()->getUser();
		$this->assertTrue( $user->isLoggedIn() );

		// Create the EditPage.
		$title = Title::newFromText( __METHOD__ );
		$context = new RequestContext();
		$context->setUser( $user );
		$context->setTitle( $title );
		$article = new Article( $title );
		$article->setContext( $context );
		$ep = new EditPage( $article );
		WatchAction::doWatchOrUnwatch( (bool)$existingExpiry, $title, $user, $existingExpiry );

		// Send the request.
		$req = new FauxRequest( [ 'wpWatchlistExpiry' => $postVal ], true );
		$context->setRequest( $req );
		$req->getSession()->setUser( $user );
		$ep->importFormData( $req );
		$def = $ep->getCheckboxesDefinition( [ 'watch' => true ] )['wpWatchlistExpiry'];

		// Test selected and available options.
		$this->assertSame( $selected, $def['default'] );
		$dropdownOptions = [];
		foreach ( $def['options'] as $option ) {
			// Reformat dropdown options for easier test comparison.
			$dropdownOptions[] = $option['data'];
		}
		$this->assertSame( $options, $dropdownOptions );
	}

	public function provideWatchlistExpiry() {
		$standardOptions = [ 'infinite', '1 week', '1 month', '3 months', '6 months' ];
		return [
			'not watched, request nothing' => [
				'existingExpiry' => '',
				'postVal' => '',
				'selected' => 'infinite',
				'options' => $standardOptions,
			],
			'not watched' => [
				'existingExpiry' => '',
				'postVal' => '1 month',
				'result' => '1 month',
				'options' => $standardOptions,
			],
			'watched with current selected' => [
				'existingExpiry' => '2020-05-05T12:00:01Z',
				'postVal' => '2020-05-05T12:00:01Z',
				'result' => '2020-05-05T12:00:01Z',
				'options' => array_merge( [ '2020-05-05T12:00:01Z' ], $standardOptions ),
			],
			'watched with 1 week selected' => [
				'existingExpiry' => '2020-05-05T12:00:02Z',
				'postVal' => '1 week',
				'result' => '1 week',
				'options' => array_merge( [ '2020-05-05T12:00:02Z' ], $standardOptions ),
			],
		];
	}
}
