<?php

use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\TextContent;
use MediaWiki\Context\RequestContext;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\EditPage\EditPage;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Exception\MWException;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Page\Article;
use MediaWiki\Page\WikiPage;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Status\Status;
use MediaWiki\Storage\EditResult;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWiki\Utils\MWTimestamp;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Editing
 * @group Database
 * @group medium
 */
class EditPageTest extends MediaWikiLangTestCase {

	use TempUserTestTrait;

	/** @var User[] */
	private static $editUsers;

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::ExtraNamespaces => [
				12312 => 'Dummy',
				12313 => 'Dummy_talk',
			],
			MainConfigNames::NamespaceContentModels => [ 12312 => 'testing' ],
			MainConfigNames::ContentHandlers =>
				[ 'testing' => 'DummyContentHandlerForTesting' ] +
				MainConfigSchema::getDefaultValue( MainConfigNames::ContentHandlers ),
		] );

		// Disable WAN cache to avoid edit conflicts in testUpdateNoMinor
		$this->setMainCache( CACHE_NONE );
	}

	public function addDBDataOnce() {
		$userFactory = $this->getServiceContainer()->getUserFactory();
		self::$editUsers = [
			'anon' => new User(),
			'UTSysop' => $userFactory->newFromName( 'UTSysop' ),
			'user' => $userFactory->newFromName( 'UTUser' ),
			'Adam' => $userFactory->newFromName( 'Adam' ),
			'Berta' => $userFactory->newFromName( 'Berta' ),
			'Elmo' => $userFactory->newFromName( 'Elmo' ),
		];

		foreach ( self::$editUsers as $key => $user ) {
			if ( $key !== 'anon' ) {
				$user->addToDatabase();
			}
		}

		$groupManager = $this->getServiceContainer()->getUserGroupManager();
		$groupManager->addUserToMultipleGroups(
			self::$editUsers['UTSysop'], [ 'sysop', 'bureaucrat' ] );
	}

	/**
	 * @dataProvider provideExtractSectionTitle
	 * @covers \MediaWiki\EditPage\EditPage::extractSectionTitle
	 */
	public function testExtractSectionTitle( $section, $title ) {
		$this->assertEquals(
			$title,
			TestingAccessWrapper::newFromClass( EditPage::class )->extractSectionTitle( $section )
		);
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
		$dbw = $this->getDb();

		$dbw->newUpdateQueryBuilder()
			->update( 'revision' )
			->set( [ 'rev_timestamp' => $dbw->timestamp( $timestamp ) ] )
			->where( [ 'rev_id' => $page->getLatest() ] )
			->execute();

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
	 * @param string $userKey The user to perform the edit as.
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
	 *              * wpMinoredit: mark as minor edit
	 *              * wpWatchthis: whether to watch the page
	 * @param int|null $expectedCode The expected result code (EditPage::AS_XXX constants).
	 *                  Set to null to skip the check.
	 * @param string|null $expectedText The text expected to be on the page after the edit.
	 *                  Set to null to skip the check.
	 * @param string|null $message An optional message to show along with any error message.
	 *
	 * @return WikiPage The page that was just edited, useful for getting the edit's rev_id, etc.
	 */
	protected function assertEdit( $title, $baseText, $userKey, array $edit,
		$expectedCode = null, $expectedText = null, $message = null
	) {
		if ( is_string( $title ) ) {
			$ns = $this->getDefaultWikitextNS();
			$title = Title::newFromText( $title, $ns );
		}
		$this->assertNotNull( $title );

		if ( !isset( self::$editUsers[$userKey] ) ) {
			$this->fail( "User $userKey not registered in addDBDataOnce" );
		}
		$user = self::$editUsers[$userKey];

		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();
		$page = $wikiPageFactory->newFromTitle( $title );

		if ( $baseText !== null ) {
			$content = ContentHandler::makeContent( $baseText, $title );
			$page->doUserEditContent( $content, $user, "base text for test" );
			$this->forceRevisionDate( $page, '20120101000000' );

			$page->clear();
			$content = $page->getContent();

			$this->assertInstanceOf( TextContent::class, $content );
			$currentText = $content->getText();

			# EditPage rtrim() the user input, so we alter our expected text
			# to reflect that.
			$this->assertEditedTextEquals( $baseText, $currentText );
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

		$context = new RequestContext();
		$context->setRequest( $req );
		$context->setTitle( $title );
		$context->setUser( $user );
		$article = new Article( $title );
		$article->setContext( $context );
		$ep = new EditPage( $article );
		$ep->setContextTitle( $title );
		$ep->importFormData( $req );

		// this is where the edit happens!
		// Note: don't want to use EditPage::AttemptSave, because it messes with $wgOut
		// and throws exceptions like PermissionsError
		$status = $ep->attemptSave( $result );

		if ( $expectedCode !== null ) {
			// check edit code
			$this->assertEquals( $expectedCode, $status->value,
				"Expected result code mismatch. $message" );
		}

		$page = $wikiPageFactory->newFromTitle( $title );

		if ( $expectedText !== null ) {
			// check resulting page text
			$content = $page->getContent();
			$text = ( $content instanceof TextContent ) ? $content->getText() : '';

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
				'user',
				'Hello World!',
				EditPage::AS_SUCCESS_NEW_ARTICLE,
				'Hello World!'
			],
			[ 'expected article not being created if empty',
				'EditPageTest_testCreatePage',
				'user',
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
				'user',
				'',
				EditPage::AS_SUCCESS_NEW_ARTICLE,
				'',
				true
			],
		];
	}

	/**
	 * @dataProvider provideCreatePages
	 * @covers \MediaWiki\EditPage\EditPage
	 */
	public function testCreatePage(
		$desc, $pageTitle, $user, $editText, $expectedCode, $expectedText, $ignoreBlank = false
	) {
		$checkId = null;

		$this->setTemporaryHook(
			'PageSaveComplete',
			static function (
				WikiPage $page, UserIdentity $user, string $summary,
				int $flags, RevisionRecord $revisionRecord, EditResult $editResult
			) use ( &$checkId ) {
				$checkId = $revisionRecord->getId();
				// types/refs checked
			}
		);

		$edit = [ 'wpTextbox1' => $editText ];
		if ( $ignoreBlank ) {
			$edit['wpIgnoreBlankArticle'] = 1;
		}

		$page = $this->assertEdit( $pageTitle, null, $user, $edit, $expectedCode, $expectedText, $desc );

		if ( $expectedCode != EditPage::AS_BLANK_ARTICLE ) {
			$latest = $page->getLatest();
			$this->deletePage( $page );

			$this->assertGreaterThan( 0, $latest, "Page revision ID updated in object" );
			$this->assertEquals( $latest, $checkId, "Revision in Status for hook" );
		}
	}

	/**
	 * @dataProvider provideCreatePages
	 * @covers \MediaWiki\EditPage\EditPage
	 */
	public function testCreatePageTrx(
		$desc, $pageTitle, $user, $editText, $expectedCode, $expectedText, $ignoreBlank = false
	) {
		$checkIds = [];
		$this->setTemporaryHook(
			'PageSaveComplete',
			static function (
				WikiPage $page, UserIdentity $user, string $summary,
				int $flags, RevisionRecord $revisionRecord, EditResult $editResult
			) use ( &$checkIds ) {
				$checkIds[] = $revisionRecord->getId();
				// types/refs checked
			}
		);

		$this->getDb()->begin( __METHOD__ );

		$edit = [ 'wpTextbox1' => $editText ];
		if ( $ignoreBlank ) {
			$edit['wpIgnoreBlankArticle'] = 1;
		}

		$page = $this->assertEdit(
			$pageTitle, null, $user, $edit, $expectedCode, $expectedText, $desc );

		$pageTitle2 = (string)$pageTitle . '/x';
		$page2 = $this->assertEdit(
			$pageTitle2, null, $user, $edit, $expectedCode, $expectedText, $desc );

		$this->getDb()->commit( __METHOD__ );
		$this->runDeferredUpdates();

		$this->assertSame( 0, DeferredUpdates::pendingUpdatesCount(), 'No deferred updates' );

		if ( $expectedCode != EditPage::AS_BLANK_ARTICLE ) {
			$latest = $page->getLatest();
			$this->deletePage( $page );

			$this->assertGreaterThan( 0, $latest, "Page #1 revision ID updated in object" );
			$this->assertEquals( $latest, $checkIds[0], "Revision #1 in Status for hook" );

			$latest2 = $page2->getLatest();
			$this->deletePage( $page2 );

			$this->assertGreaterThan( 0, $latest2, "Page #2 revision ID updated in object" );
			$this->assertEquals( $latest2, $checkIds[1], "Revision #2 in Status for hook" );
		}
	}

	/**
	 * @covers \MediaWiki\EditPage\EditPage
	 */
	public function testUpdatePage() {
		$checkIds = [];
		$this->setTemporaryHook(
			'PageSaveComplete',
			static function (
				WikiPage $page, UserIdentity $user, string $summary,
				int $flags, RevisionRecord $revisionRecord, EditResult $editResult
			) use ( &$checkIds ) {
				$checkIds[] = $revisionRecord->getId();
				// types/refs checked
			}
		);

		$text = "one";
		$edit = [
			'wpTextbox1' => $text,
			'wpSummary' => 'first update',
		];

		$page = $this->assertEdit( 'EditPageTest_testUpdatePage', "zero", 'user', $edit,
			EditPage::AS_SUCCESS_UPDATE, $text,
			"expected successful update with given text" );
		$this->assertGreaterThan( 0, $checkIds[0], "First event rev ID set" );

		$this->forceRevisionDate( $page, '20120101000000' );

		$text = "two";
		$edit = [
			'wpTextbox1' => $text,
			'wpSummary' => 'second update',
		];

		$this->assertEdit( 'EditPageTest_testUpdatePage', null, 'user', $edit,
			EditPage::AS_SUCCESS_UPDATE, $text,
			"expected successful update with given text" );
		$this->assertGreaterThan( 0, $checkIds[1], "Second edit hook rev ID set" );
		$this->assertGreaterThan( $checkIds[0], $checkIds[1], "Second event rev ID is higher" );
	}

	/**
	 * @covers \MediaWiki\EditPage\EditPage
	 */
	public function testUpdateNoMinor() {
		// Test that page creation can never be minor
		$edit = [
			'wpTextbox1' => 'testing',
			'wpSummary' => 'first update',
			'wpMinoredit' => 'minor'
		];

		$page = $this->assertEdit( 'EditPageTest_testUpdateNoMinor', null, 'user', $edit,
			EditPage::AS_SUCCESS_NEW_ARTICLE, 'testing', "expected successful update" );

		$this->assertFalse(
			$page->getRevisionRecord()->isMinor(),
			'page creation should not be minor'
		);

		// Test that anons can't make an update minor
		$this->forceRevisionDate( $page, '20120101000000' );

		$edit = [
			'wpTextbox1' => 'testing 2',
			'wpSummary' => 'second update',
			'wpMinoredit' => 'minor'
		];

		// Next assertion uses an anon editor, so disable temp accounts
		$this->disableAutoCreateTempUser();
		$page = $this->assertEdit( 'EditPageTest_testUpdateNoMinor', null, 'anon', $edit,
			EditPage::AS_SUCCESS_UPDATE, 'testing 2', "expected successful update" );

		$this->assertFalse(
			$page->getRevisionRecord()->isMinor(),
			'anon edit should not be minor'
		);

		// Test that users can make an update minor
		$this->forceRevisionDate( $page, '20120102000000' );

		$edit = [
			'wpTextbox1' => 'testing 3',
			'wpSummary' => 'third update',
			'wpMinoredit' => 'minor'
		];

		$page = $this->assertEdit( 'EditPageTest_testUpdateNoMinor', null, 'user', $edit,
			EditPage::AS_SUCCESS_UPDATE, 'testing 3', "expected successful update" );

		$this->assertTrue(
			$page->getRevisionRecord()->isMinor(),
			'users can make edits minor'
		);
	}

	/**
	 * @covers \MediaWiki\EditPage\EditPage
	 */
	public function testUpdatePageTrx() {
		$text = "one";
		$edit = [
			'wpTextbox1' => $text,
			'wpSummary' => 'first update',
		];

		$page = $this->assertEdit( 'EditPageTest_testTrxUpdatePage', "zero", 'user', $edit,
			EditPage::AS_SUCCESS_UPDATE, $text,
			"expected successful update with given text" );

		$this->forceRevisionDate( $page, '20120101000000' );

		$checkIds = [];
		$this->setTemporaryHook(
			'PageSaveComplete',
			static function (
				WikiPage $page, UserIdentity $user, string $summary,
				int $flags, RevisionRecord $revisionRecord, EditResult $editResult
			) use ( &$checkIds ) {
				$checkIds[] = $revisionRecord->getId();
				// types/refs checked
			}
		);

		$this->getDb()->begin( __METHOD__ );

		$text = "two";
		$edit = [
			'wpTextbox1' => $text,
			'wpSummary' => 'second update',
		];

		$this->assertEdit( 'EditPageTest_testTrxUpdatePage', null, 'user', $edit,
			EditPage::AS_SUCCESS_UPDATE, $text,
			"expected successful update with given text" );

		$text = "three";
		$edit = [
			'wpTextbox1' => $text,
			'wpSummary' => 'third update',
		];

		$this->assertEdit( 'EditPageTest_testTrxUpdatePage', null, 'user', $edit,
			EditPage::AS_SUCCESS_UPDATE, $text,
			"expected successful update with given text" );

		$this->getDb()->commit( __METHOD__ );
		$this->runDeferredUpdates();

		$this->assertGreaterThan( 0, $checkIds[0], "First event rev ID set" );
		$this->assertGreaterThan( 0, $checkIds[1], "Second edit hook rev ID set" );
		$this->assertGreaterThan( $checkIds[0], $checkIds[1], "Second event rev ID is higher" );
	}

	public static function provideSectionEdit() {
		$title = 'EditPageTest_testSectionEdit';
		$title2 = Title::newFromText( __FUNCTION__ );
		$title2->setContentModel( CONTENT_MODEL_CSS );
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
				$title,
				$text,
				'',
				'hello',
				'replace all',
				'hello'
			],

			[ # 1
				$title,
				$text,
				'1',
				$sectionOne,
				'replace first section',
				$textWithNewSectionOne,
			],

			[ # 2
				$title,
				$text,
				'new',
				'hello',
				'new section',
				$textWithNewSectionAdded,
			],

			[ # 3 Section edit not supported
				$title2,
				$text,
				'1',
				'hello',
				'',
				'',
			],
		];
	}

	/**
	 * @dataProvider provideSectionEdit
	 * @covers \MediaWiki\EditPage\EditPage
	 */
	public function testSectionEdit( $title, $base, $section, $text, $summary, $expected ) {
		$edit = [
			'wpTextbox1' => $text,
			'wpSummary' => $summary,
			'wpSection' => $section,
		];

		$msg = "expected successful update of section";
		$result = EditPage::AS_SUCCESS_UPDATE;

		if ( $title instanceof Title ) {
			$result = null;
			$this->expectException( ErrorPageError::class );
		}
		$this->assertEdit( $title, $base, 'user', $edit, $result, $expected, $msg );
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
	 * @covers \MediaWiki\EditPage\EditPage
	 */
	public function testConflictDetection( $editUser, $newEdit, $expectedCode, $message ) {
		// create page
		$ns = $this->getDefaultWikitextNS();
		$title = Title::newFromText( __METHOD__, $ns );
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();
		$page = $wikiPageFactory->newFromTitle( $title );

		if ( $page->exists() ) {
			$this->deletePage( $page, "clean slate for testing" );
		}

		$elmosEdit['wpTextbox1'] = 'Elmo\'s text';
		$bertasEdit['wpTextbox1'] = 'Berta\'s text';
		$newEdit['wpTextbox1'] = 'new text';

		$elmosEdit['wpSummary'] = 'Elmo\'s edit';
		$bertasEdit['wpSummary'] = 'Bertas\'s edit';
		$newEdit['wpSummary'] ??= 'new edit';

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
		$testsWithAdam = array_map( static function ( $test ) {
			$test[0] = 'Adam'; // change base edit user
			return $test;
		}, $tests );

		$testsWithBerta = array_map( static function ( $test ) {
			$test[0] = 'Berta'; // change base edit user
			return $test;
		}, $tests );

		return array_merge( $tests, $testsWithAdam, $testsWithBerta );
	}

	/**
	 * @dataProvider provideAutoMerge
	 * @covers \MediaWiki\EditPage\EditPage
	 */
	public function testAutoMerge( $baseUser, $text, $adamsEdit, $bertasEdit,
		$expectedCode, $expectedText, $message = null
	) {
		$this->markTestSkippedIfNoDiff3();

		// create page
		$ns = $this->getDefaultWikitextNS();
		$title = Title::makeTitle( $ns, 'EditPageTest_testAutoMerge' );
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();
		$page = $wikiPageFactory->newFromTitle( $title );

		if ( $page->exists() ) {
			$this->deletePage( $page, "clean slate for testing" );
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
	 * @covers \MediaWiki\EditPage\EditPage
	 */
	public function testCheckDirectEditingDisallowed_forNonTextContent() {
		$user = self::$editUsers['user'];

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

	/** @covers \MediaWiki\EditPage\EditPage */
	public function testShouldPreventChangingContentModelWhenUserCannotChangeModelForTitle() {
		$this->setTemporaryHook( 'getUserPermissionsErrors',
			static function ( Title $page, $user, $action, &$result ) {
				if ( $action === 'editcontentmodel' &&
					$page->getContentModel() === CONTENT_MODEL_WIKITEXT
				) {
					$result = false;

					return false;
				}
			} );

		$user = self::$editUsers['user'];

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

		$this->assertStatusNotOK( $status );
		$this->assertStatusValue( EditPage::AS_NO_CHANGE_CONTENT_MODEL, $status );
	}

	/** @covers \MediaWiki\EditPage\EditPage */
	public function testShouldPreventChangingContentModelWhenUserCannotEditTargetTitle() {
		$this->setTemporaryHook( 'getUserPermissionsErrors',
			static function ( Title $page, $user, $action, &$result ) {
				if ( $action === 'edit' && $page->getContentModel() === CONTENT_MODEL_WIKITEXT ) {
					$result = false;
					return false;
				}
			} );

		$user = $this->getTestUser()->getUser();

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

		$this->assertStatusNotOK( $status );
		$this->assertStatusValue( EditPage::AS_NO_CHANGE_CONTENT_MODEL, $status );
	}

	private function doEditDummyNonTextPage( array $edit ): Status {
		$title = Title::newFromText( 'Dummy:NonTextPageForEditPage' );

		$article = new Article( $title );
		$article->getContext()->setTitle( $title );
		$ep = new EditPage( $article );
		$ep->setContextTitle( $title );

		$req = new FauxRequest( $edit, true );
		$ep->importFormData( $req );

		return $ep->attemptSave( $result );
	}

	/**
	 * The watchlist expiry field should select the entered value on preview, rather than the
	 * calculated number of days till the expiry (as it shows on edit).
	 * @covers \MediaWiki\EditPage\EditPage::getCheckboxesDefinition()
	 * @dataProvider provideWatchlistExpiry()
	 */
	public function testWatchlistExpiry( $existingExpiry, $postVal, $selected, $options ) {
		// Set up config and fake current time.
		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
		MWTimestamp::setFakeTime( '20200505120000' );
		$user = $this->getTestUser()->getUser();
		$this->assertTrue( $user->isRegistered() );

		// Create the EditPage.
		$title = Title::newFromText( __METHOD__ );
		$context = new RequestContext();
		$context->setUser( $user );
		$context->setTitle( $title );
		$article = new Article( $title );
		$article->setContext( $context );
		$ep = new EditPage( $article );
		$this->getServiceContainer()->getWatchlistManager()
			->setWatch( (bool)$existingExpiry, $user, $title, $existingExpiry );

		// Send the request.
		$req = new FauxRequest( [], true );
		$context->setRequest( $req );
		$req->getSession()->setUser( $user );
		$ep->importFormData( $req );
		$def = $ep->getCheckboxesDefinition( [ 'watch' => true, 'wpWatchlistExpiry' => $postVal ] )['wpWatchlistExpiry'];

		// Test selected and available options.
		$this->assertSame( $selected, $def['default'] );
		$dropdownOptions = [];
		foreach ( $def['options'] as $option ) {
			// Reformat dropdown options for easier test comparison.
			$dropdownOptions[] = $option['data'];
		}
		$this->assertSame( $options, $dropdownOptions );
	}

	public static function provideWatchlistExpiry() {
		$standardOptions = [ 'infinite', '1 week', '1 month', '3 months', '6 months', '1 year' ];
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

	/**
	 * T277204
	 * @covers \MediaWiki\EditPage\EditPage
	 */
	public function testFalseyEditRevId() {
		$elmosEdit['wpTextbox1'] = 'Elmo\'s text';
		$bertasEdit['wpTextbox1'] = 'Berta\'s text';

		$elmosEdit['wpSummary'] = 'Elmo\'s edit';
		$bertasEdit['wpSummary'] = 'Bertas\'s edit';

		$bertasEdit['editRevId'] = 0;

		$this->assertEdit( __METHOD__,
			null, 'Elmo', $elmosEdit,
			EditPage::AS_SUCCESS_NEW_ARTICLE, null, 'expected successful creation' );

		// A successful update would probably be OK too. The important thing is
		// that it doesn't throw an exception.
		$this->assertEdit( __METHOD__, null, 'Berta', $bertasEdit,
			EditPage::AS_CONFLICT_DETECTED, null, 'expected successful update' );
	}

}
