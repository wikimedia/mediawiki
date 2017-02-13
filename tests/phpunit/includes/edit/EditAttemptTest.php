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
class EditAttemptTest extends MediaWikiLangTestCase {

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
		$this->assertEquals(
			rtrim( $expected ),
			rtrim( $actual ),
			$msg
		) ;
	}

	protected function assertInternalAttemptSave( $title, $user, $data, $info = [], $options = [],
		$baseText = null, $expectedCode = null, $expectedText = null, $message = null
	) {
		if ( is_string( $title ) ) {
			$ns = $this->getDefaultWikitextNS();
			$title = Title::newFromText( $title, $ns );
		}
		$this->assertNotNull( $title );

		if ( !$user ) {
			$user = new User();
		}
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
			$data->editTime = '20120101000000';

			// sanity check
			$page->clear();
			$currentText = ContentHandler::getContentText( $page->getContent() );

			$this->assertEditedTextEquals( $baseText, $currentText );
		}

		$data->contentModel = $title->getContentModel();
		$data->contentFormat = ContentHandler::getForModelID( $data->contentModel )
			->getDefaultFormat();

		// EditAttempt expects an EditFormDataWrapper
		$dataWrapper = new EditFormDataWrapper( $title );
		$reflection = new ReflectionClass( $dataWrapper );
		$prop = $reflection->getProperty( 'data' );
		$prop->setAccessible( true );
		$prop->setValue( $dataWrapper, $data );

		$editAttempt = new EditAttempt( $page, $user, $dataWrapper );
		$info += [
			'autoSumm' => md5( '' ),
			'changeTags' => null,
			'undidRevId' => 0,
		];
		$options += [
			'allowBlankArticle' => false,
			'allowBlankSummary' => false,
			'allowSelfRedirect' => false,
			'recreate' => false,
			'bot' => false,
			'minorEdit' => false,
			'watchThis' => false,
		];
		$result = [];
		$status = $editAttempt->internalAttemptSave( $info, $options, $result );

		if ( $expectedCode !== null ) {
			// check edit code
			$this->assertEquals( $expectedCode, $status->value,
				"Expected result code mismatch. $message" );
		}

		if ( $expectedText !== null ) {
			// check resulting page text
			$page->clear();
			$content = $page->getContent();
			$text = ContentHandler::getContentText( $content );

			$this->assertEditedTextEquals( $expectedText, $text,
				"Expected article text mismatch. $message" );
		}
		return $page;
	}

	public static function provideCreatePages() {
		return [
			[ 'expected article being created',
				'EditAttemptTest_testCreatePage',
				null,
				'Hello World!',
				EditAttempt::AS_SUCCESS_NEW_ARTICLE,
				'Hello World!'
			],
			[ 'expected article not being created if empty',
				'EditAttemptTest_testCreatePage',
				null,
				'',
				EditAttempt::AS_BLANK_ARTICLE,
				null
			],
			[ 'expected MediaWiki: page being created',
				'MediaWiki:January',
				'UTSysop',
				'Not January',
				EditAttempt::AS_SUCCESS_NEW_ARTICLE,
				'Not January'
			],
			[ 'expected not-registered MediaWiki: page not being created if empty',
				'MediaWiki:EditAttemptTest_testCreatePage',
				'UTSysop',
				'',
				EditAttempt::AS_BLANK_ARTICLE,
				null
			],
			[ 'expected registered MediaWiki: page being created even if empty',
				'MediaWiki:January',
				'UTSysop',
				'',
				EditAttempt::AS_SUCCESS_NEW_ARTICLE,
				''
			],
			[ 'expected registered MediaWiki: page whose default content is empty'
					. ' not being created if empty',
				'MediaWiki:Ipb-default-expiry',
				'UTSysop',
				'',
				EditAttempt::AS_BLANK_ARTICLE,
				''
			],
			[ 'expected MediaWiki: page not being created if text equals default message',
				'MediaWiki:January',
				'UTSysop',
				'January',
				EditAttempt::AS_BLANK_ARTICLE,
				null
			],
			[ 'expected empty article being created',
				'EditAttemptTest_testCreatePage',
				null,
				'',
				EditAttempt::AS_SUCCESS_NEW_ARTICLE,
				'',
				true
			],
		];
	}

	/**
	 * @dataProvider provideCreatePages
	 * @covers EditAttempt::internalAttemptSave
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

		$edit = new EditFormData();
		$edit->textbox1 = $editText;
		$opts = [];
		if ( $ignoreBlank ) {
			$opts['allowBlankArticle'] = true;
		}

		$page = $this->assertInternalAttemptSave( $pageTitle, $user, $edit, [], $opts, null,
			$expectedCode, $expectedText, $desc );

		if ( $expectedCode != EditAttempt::AS_BLANK_ARTICLE ) {
			$latest = $page->getLatest();
			$page->doDeleteArticleReal( $pageTitle );

			$this->assertGreaterThan( 0, $latest, "Page revision ID updated in object" );
			$this->assertEquals( $latest, $checkId, "Revision in Status for hook" );
		}
	}

	/**
	 * @dataProvider provideCreatePages
	 * @covers EditAttempt::internalAttemptSave
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

		$edit = new EditFormData();
		$edit->textbox1 = $editText;
		$opts = [];
		if ( $ignoreBlank ) {
			$opts['allowBlankArticle'] = true;
		}

		$page = $this->assertInternalAttemptSave(
			$pageTitle, $user, $edit, [], $opts, null, $expectedCode, $expectedText, $desc );

		$pageTitle2 = (string)$pageTitle . '/x';
		$page2 = $this->assertInternalAttemptSave(
			$pageTitle2, $user, $edit, [], $opts, null, $expectedCode, $expectedText, $desc );

		wfGetDB( DB_MASTER )->commit( __METHOD__ );

		$this->assertEquals( 0, DeferredUpdates::pendingUpdatesCount(), 'No deferred updates' );

		if ( $expectedCode != EditAttempt::AS_BLANK_ARTICLE ) {
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
		$edit = new EditFormData();
		$edit->textbox1 = $text;
		$edit->summary = 'first update';

		$page = $this->assertInternalAttemptSave(
			'EditAttemptTest_testUpdatePage', null, $edit, [], [], "zero",
			EditAttempt::AS_SUCCESS_UPDATE, $text,
			"expected successfull update with given text" );
		$this->assertGreaterThan( 0, $checkIds[0], "First event rev ID set" );

		$this->forceRevisionDate( $page, '20120101000000' );

		$text = "two";
		$edit = new EditFormData();
		$edit->textbox1 = $text;
		$edit->editTime = '20120101000000';
		$edit->summary = 'second update';

		$this->assertInternalAttemptSave(
			'EditAttemptTest_testUpdatePage', null, $edit, [], [], null,
			EditAttempt::AS_SUCCESS_UPDATE, $text,
			"expected successfull update with given text" );
		$this->assertGreaterThan( 0, $checkIds[1], "Second edit hook rev ID set" );
		$this->assertGreaterThan( $checkIds[0], $checkIds[1], "Second event rev ID is higher" );
	}

	public function testUpdatePageTrx() {
		$text = "one";
		$edit = new EditFormData();
		$edit->textbox1 = $text;
		$edit->summary = 'first update';

		$page = $this->assertInternalAttemptSave(
			'EditAttemptTest_testTrxUpdatePage', null, $edit, [], [], "zero",
			EditAttempt::AS_SUCCESS_UPDATE, $text,
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
		$edit = new EditFormData();
		$edit->textbox1 = $text;
		$edit->editTime = '20120101000000';
		$edit->summary = 'second update';

		$this->assertInternalAttemptSave(
			'EditAttemptTest_testTrxUpdatePage', null, $edit, [], [], null,
			EditAttempt::AS_SUCCESS_UPDATE, $text,
			"expected successfull update with given text" );
		$this->forceRevisionDate( $page, '20120101000000' );

		$text = "three";
		$edit = new EditFormData();
		$edit->textbox1 = $text;
		$edit->editTime = '20120101000000';
		$edit->summary = 'third update';

		$this->assertInternalAttemptSave(
			'EditAttemptTest_testTrxUpdatePage', null, $edit, [], [], null,
			EditAttempt::AS_SUCCESS_UPDATE, $text,
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
		$edit = new EditFormData();
		$edit->textbox1 = rtrim( $text );
		$edit->summary = $summary;
		$edit->section = $section;

		$this->assertInternalAttemptSave(
			'EditPageTest_testSectionEdit', null, $edit, [], [], $base,
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
			EditAttempt::AS_CONFLICT_DETECTED, # expected code
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
			EditAttempt::AS_SUCCESS_UPDATE, # expected code
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
			EditAttempt::AS_SUCCESS_UPDATE, # expected code
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
	 * @covers EditAttempt::internalAttemptSave
	 */
	public function testAutoMerge( $baseUser, $text, $adamsEdit, $bertasEdit,
		$expectedCode, $expectedText, $message = null
	) {
		$this->markTestSkippedIfNoDiff3();

		// create page
		$ns = $this->getDefaultWikitextNS();
		$title = Title::newFromText( 'EditAttemptTest_testAutoMerge', $ns );
		$page = WikiPage::factory( $title );

		if ( $page->exists() ) {
			$page->doDeleteArticle( "clean slate for testing" );
		}

		$baseEditData = new EditFormData();
		$baseEditData->textbox1 = $text;

		$page = $this->assertInternalAttemptSave( 'EditAttemptTest_testAutoMerge',
			$baseUser, $baseEditData, [], [], null, null, null, __METHOD__ );

		$this->forceRevisionDate( $page, '20120101000000' );

		$editTime = $page->getTimestamp();

		$adamsEditData = new EditFormData();
		$bertasEditData = new EditFormData();

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

		$adamsEditData->startTime = $adamsTime;
		$bertasEditData->startTime = $bertasTime;

		$adamsEditData->summary = 'Adam\'s edit';
		$bertasEditData->summary = 'Bertas\'s edit';

		$adamsEditData->editTime = $editTime;
		$bertasEditData->editTime = $editTime;

		$adamsEditData->textbox1 = rtrim( $adamsEdit['wpTextbox1'] );
		$bertasEditData->textbox1 = rtrim( $bertasEdit['wpTextbox1'] );

		if ( isset( $adamsEdit['wpSection'] ) ) {
			$adamsEditData->section = $adamsEdit['wpSection'];
		}
		if ( isset( $bertasEdit['wpSection'] ) ) {
			$bertasEditData->section = $bertasEdit['wpSection'];
		}

		// first edit
		$this->assertInternalAttemptSave( 'EditAttemptTest_testAutoMerge', 'Adam', $adamsEditData,
			[], [], null, EditAttempt::AS_SUCCESS_UPDATE, null, "expected successfull update" );

		// second edit
		$this->assertInternalAttemptSave( 'EditAttemptTest_testAutoMerge', 'Berta', $bertasEditData,
			[], [], null, $expectedCode, $expectedText, $message );
	}

}
