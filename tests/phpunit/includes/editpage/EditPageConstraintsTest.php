<?php

use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\TextContent;
use MediaWiki\Context\RequestContext;
use MediaWiki\EditPage\EditPage;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Unit\MockBlockTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * Integration tests for the various edit constraints, ensuring
 * that they result in failures as expected
 *
 * @covers \MediaWiki\EditPage\EditPage::internalAttemptSavePrivate
 *
 * @group Editing
 * @group Database
 * @group medium
 */
class EditPageConstraintsTest extends MediaWikiLangTestCase {

	use TempUserTestTrait;
	use MockBlockTrait;

	protected function setUp(): void {
		parent::setUp();

		$contLang = $this->getServiceContainer()->getContentLanguage();
		$this->overrideConfigValues( [
			MainConfigNames::ExtraNamespaces => [
				12312 => 'Dummy',
				12313 => 'Dummy_talk',
			],
			MainConfigNames::NamespaceContentModels => [ 12312 => 'testing' ],
			MainConfigNames::LanguageCode => $contLang->getCode(),
		] );
		$this->mergeMwGlobalArrayValue(
			'wgContentHandlers',
			[ 'testing' => 'DummyContentHandlerForTesting' ]
		);
	}

	/**
	 * Based on method in EditPageTest
	 * Performs an edit and checks the result matches the expected failure code
	 *
	 * @param string|Title $title The title of the page to edit
	 * @param string|null $baseText Some text to create the page with before attempting the edit.
	 * @param User|null $user The user to perform the edit as.
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
	 * @param int $expectedCode The expected result code (EditPage::AS_XXX constants).
	 * @param string $message Message to show along with any error message.
	 *
	 * @return WikiPage The page that was just edited, useful for getting the edit's rev_id, etc.
	 */
	protected function assertEdit(
		$title,
		$baseText,
		?User $user,
		array $edit,
		$expectedCode,
		$message
	) {
		if ( is_string( $title ) ) {
			$ns = $this->getDefaultWikitextNS();
			$title = Title::newFromText( $title, $ns );
		}
		$this->assertNotNull( $title );

		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();
		$page = $wikiPageFactory->newFromTitle( $title );

		$user ??= $this->getTestUser()->getUser();

		if ( $baseText !== null ) {
			$content = ContentHandler::makeContent( $baseText, $title );
			$page->doUserEditContent( $content, $user, "base text for test" );

			// Set the latest timestamp back a while
			$dbw = $this->getDb();
			$dbw->newUpdateQueryBuilder()
				->update( 'revision' )
				->set( [ 'rev_timestamp' => $dbw->timestamp( '20120101000000' ) ] )
				->where( [ 'rev_id' => $page->getLatest() ] )
				->execute();
			$page->clear();

			$content = $page->getContent();
			$this->assertInstanceOf( TextContent::class, $content );
			$currentText = $content->getText();

			# EditPage rtrim() the user input, so we alter our expected text
			# to reflect that.
			$this->assertEquals(
				rtrim( $baseText ),
				rtrim( $currentText ),
				'page should have the text specified'
			);
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
		$status = $ep->attemptSave( $result );

		// check edit code
		$this->assertSame(
			$expectedCode,
			$status->value,
			"Expected result code mismatch. $message"
		);

		return $wikiPageFactory->newFromTitle( $title );
	}

	/** AccidentalRecreationConstraint integration */
	public function testAccidentalRecreationConstraint() {
		// Make sure it exists
		$this->getExistingTestPage( 'AccidentalRecreationConstraintPage' );

		// And now delete it, so that there is a deletion log
		$page = $this->getNonexistingTestPage( 'AccidentalRecreationConstraintPage' );
		$title = $page->getTitle();

		// Set the time of the deletion to be a specific time, so we can be sure to start the
		// edit before it. Since the constraint will query for the most recent timestamp,
		// update *all* deletion logs for the page to the same timestamp (1 January 2020)
		$dbw = $this->getDb();
		$dbw->newUpdateQueryBuilder()
			->update( 'logging' )
			->set( [ 'log_timestamp' => $dbw->timestamp( '20200101000000' ) ] )
			->where( [
				'log_namespace' => $title->getNamespace(),
				'log_title' => $title->getDBkey(),
				'log_type' => 'delete',
				'log_action' => 'delete',
			] )
			->caller( __METHOD__ )->execute();

		$user = $this->getTestUser()->getUser();

		$permissionManager = $this->getServiceContainer()->getPermissionManager();
		// Needs these rights to pass AuthorizationConstraint and reach AccidentalRecreationConstraint
		$permissionManager->overrideUserRightsForTesting( $user, [ 'edit', 'createpage' ] );

		// Started the edit on 1 January 2019, page was deleted on 1 January 2020
		$edit = [
			'wpTextbox1' => 'New content',
			'wpStarttime' => '20190101000000'
		];
		$this->assertEdit(
			$title,
			null,
			$user,
			$edit,
			EditPage::AS_ARTICLE_WAS_DELETED,
			'expected AS_ARTICLE_WAS_DELETED update'
		);
	}

	/** ExistingSectionEditConstraint integration */
	public function testExistingSectionEditConstraint() {
		// Require the summary
		$this->mergeMwGlobalArrayValue(
			'wgDefaultUserOptions',
			[ 'forceeditsummary' => 1 ]
		);

		$page = $this->getExistingTestPage( 'ExistingSectionEditConstraint page does exist' );
		$title = $page->getTitle();

		$user = $this->getTestUser()->getUser();

		$permissionManager = $this->getServiceContainer()->getPermissionManager();
		// Needs these rights to pass AuthorizationConstraint and reach NewSectionMissingSubjectConstraint
		$permissionManager->overrideUserRightsForTesting( $user, [ 'edit' ] );

		$edit = [
			'wpTextbox1' => 'New content, different from base content',
			'wpSummary' => 'SameAsAutoSummary',
			'wpAutoSummary' => md5( 'SameAsAutoSummary' )
		];
		$this->assertEdit(
			$title,
			'Base content, different from new content',
			$user,
			$edit,
			EditPage::AS_SUMMARY_NEEDED,
			'expected AS_SUMMARY_NEEDED update'
		);
	}

	/** ChangeTagsConstraint integration */
	public function testChangeTagsConstraint() {
		// Remove rights
		$this->mergeMwGlobalArrayValue(
			'wgRevokePermissions',
			[ 'user' => [ 'applychangetags' => true ] ]
		);
		$edit = [
			'wpTextbox1' => 'Text',
			'wpChangeTags' => 'tag-name'
		];
		$this->assertEdit(
			'EditPageTest_changeTagsConstraint',
			null,
			null,
			$edit,
			EditPage::AS_CHANGE_TAG_ERROR,
			'expected AS_CHANGE_TAG_ERROR update'
		);
	}

	/** ContentModelChangeConstraint integration */
	public function testContentModelChangeConstraint() {
		$user = $this->getTestUser()->getUser();
		$permissionManager = $this->getServiceContainer()->getPermissionManager();
		// Needs these rights to pass AuthorizationConstraint and reach ContentModelChangeConstraint
		$permissionManager->overrideUserRightsForTesting( $user, [ 'edit' ] );

		$edit = [
			'wpTextbox1' => 'New text goes here',
			'wpSummary' => 'Summary',
			'model' => CONTENT_MODEL_TEXT,
			'format' => CONTENT_FORMAT_TEXT,
		];

		$title = Title::makeTitle( NS_MAIN, 'Example' );
		$this->assertSame(
			CONTENT_MODEL_WIKITEXT,
			$title->getContentModel(),
			'title should start as wikitext content model'
		);

		$this->assertEdit(
			$title,
			'Base text',
			$user,
			$edit,
			EditPage::AS_NO_CHANGE_CONTENT_MODEL,
			'expected AS_NO_CHANGE_CONTENT_MODEL update'
		);
	}

	/** AuthorizationConstraint integration - 'create' rights */
	public function testAuthorizationConstraint_create() {
		$page = $this->getNonexistingTestPage( 'AuthorizationConstraint_create page does not exist' );
		$title = $page->getTitle();

		$user = $this->getTestUser()->getUser();
		$permissionManager = $this->getServiceContainer()->getPermissionManager();
		$permissionManager->overrideUserRightsForTesting( $user, [ 'edit' ] );

		$edit = [
			'wpTextbox1' => 'Page content',
			'wpSummary' => 'Summary'
		];
		$this->assertEdit(
			$title,
			null,
			$user,
			$edit,
			EditPage::AS_NO_CREATE_PERMISSION,
			'expected AS_NO_CREATE_PERMISSION creation'
		);
	}

	/** DefaultTextConstraint integration */
	public function testDefaultTextConstraint() {
		$page = $this->getNonexistingTestPage( 'DefaultTextConstraint page does not exist' );
		$title = $page->getTitle();

		$user = $this->getTestUser()->getUser();
		$permissionManager = $this->getServiceContainer()->getPermissionManager();
		// Needs these rights to pass AuthorizationConstraint
		$permissionManager->overrideUserRightsForTesting( $user, [ 'edit', 'createpage' ] );

		$edit = [
			'wpTextbox1' => '',
			'wpSummary' => 'Summary'
		];
		$this->assertEdit(
			$title,
			null,
			$user,
			$edit,
			EditPage::AS_BLANK_ARTICLE,
			'expected AS_BLANK_ARTICLE creation'
		);
	}

	/**
	 * EditFilterMergedContentHookConstraint integration
	 * @dataProvider provideTestEditFilterMergedContentHookConstraint
	 * @param bool $hookReturn
	 * @param ?int $statusValue
	 * @param bool $statusFatal
	 * @param int $expectedFailure
	 * @param string $expectedFailureStr
	 */
	public function testEditFilterMergedContentHookConstraint(
		bool $hookReturn,
		$statusValue,
		bool $statusFatal,
		int $expectedFailure,
		string $expectedFailureStr
	) {
		$this->setTemporaryHook(
			'EditFilterMergedContent',
			static function ( $context, $content, $status, $summary, $user, $minorEdit )
				use ( $hookReturn, $statusValue, $statusFatal )
			{
				if ( $statusValue !== null ) {
					$status->value = $statusValue;
				}
				if ( $statusFatal ) {
					$status->fatal( 'SomeErrorInTheHook' );
				}
				return $hookReturn;
			}
		);

		$user = $this->getTestUser()->getUser();
		$permissionManager = $this->getServiceContainer()->getPermissionManager();
		// Needs these rights to pass AuthorizationConstraint
		$permissionManager->overrideUserRightsForTesting( $user, [ 'edit', 'createpage' ] );

		$edit = [
			'wpTextbox1' => 'Text',
			'wpSummary' => 'Summary'
		];
		$this->assertEdit(
			'EditPageTest_testEditFilterMergedContentHookConstraint',
			null,
			$user,
			$edit,
			$expectedFailure,
			"expected $expectedFailureStr creation"
		);
	}

	public static function provideTestEditFilterMergedContentHookConstraint() {
		yield 'Hook returns false, status is good, no value set' => [
			false, null, false, EditPage::AS_HOOK_ERROR_EXPECTED, 'AS_HOOK_ERROR_EXPECTED'
		];
		yield 'Hook returns false, status is good, value set' => [
			false, 1234567, false, 1234567, 'custom value 1234567'
		];
		yield 'Hook returns false, status is not good' => [
			false, null, true, EditPage::AS_HOOK_ERROR_EXPECTED, 'AS_HOOK_ERROR_EXPECTED'
		];
		yield 'Hook returns true, status is not ok' => [
			true, null, true, EditPage::AS_HOOK_ERROR_EXPECTED, 'AS_HOOK_ERROR_EXPECTED'
		];
	}

	/**
	 * AuthorizationConstraint integration - 'edit' rights
	 * @dataProvider provideTestAuthorizationConstraint_edit
	 * @param bool $anon
	 * @param int $expectedErrorCode
	 */
	public function testAuthorizationConstraint_edit( $anon, $expectedErrorCode ) {
		if ( $anon ) {
			$this->disableAutoCreateTempUser();
			$user = $this->getServiceContainer()->getUserFactory()->newAnonymous( '127.0.0.1' );
		} else {
			$user = $this->getTestUser()->getUser();
		}
		$permissionManager = $this->getServiceContainer()->getPermissionManager();
		$permissionManager->overrideUserRightsForTesting( $user, [] );

		$edit = [
			'wpTextbox1' => 'Page content',
			'wpSummary' => 'Summary'
		];
		$this->assertEdit(
			'EditPageTest_noEditRight',
			'base text',
			$user,
			$edit,
			$expectedErrorCode,
			'expected AS_READ_ONLY_PAGE_* update'
		);
	}

	public static function provideTestAuthorizationConstraint_edit() {
		yield 'Anonymous user' => [ true, EditPage::AS_READ_ONLY_PAGE_ANON ];
		yield 'Registered user' => [ false, EditPage::AS_READ_ONLY_PAGE_LOGGED ];
	}

	/**
	 * ImageRedirectConstraint integration
	 * @dataProvider provideTestImageRedirectConstraint
	 * @param bool $anon
	 * @param int $expectedErrorCode
	 */
	public function testImageRedirectConstraint( $anon, $expectedErrorCode ) {
		if ( $anon ) {
			$this->disableAutoCreateTempUser();
			$user = $this->getServiceContainer()->getUserFactory()->newAnonymous( '127.0.0.1' );
		} else {
			$user = $this->getTestUser()->getUser();
		}

		$permissionManager = $this->getServiceContainer()->getPermissionManager();
		// Needs these rights to pass AuthorizationConstraint and reach ImageRedirectConstraint
		$permissionManager->overrideUserRightsForTesting( $user, [ 'edit' ] );

		$edit = [
			'wpTextbox1' => '#REDIRECT [[File:Example other file.jpg]]',
			'wpSummary' => 'Summary'
		];

		$title = Title::makeTitle( NS_FILE, 'Example.jpg' );
		$this->assertEdit(
			$title,
			null,
			$user,
			$edit,
			$expectedErrorCode,
			'expected AS_IMAGE_REDIRECT_* update'
		);
	}

	public static function provideTestImageRedirectConstraint() {
		yield 'Anonymous user' => [ true, EditPage::AS_IMAGE_REDIRECT_ANON ];
		yield 'Registered user' => [ false, EditPage::AS_IMAGE_REDIRECT_LOGGED ];
	}

	/** MissingCommentConstraint integration */
	public function testMissingCommentConstraint() {
		$page = $this->getExistingTestPage( 'MissingCommentConstraint page does exist' );
		$title = $page->getTitle();

		$user = $this->getTestUser()->getUser();

		$permissionManager = $this->getServiceContainer()->getPermissionManager();
		// Needs these rights to pass AuthorizationConstraint and reach MissingCommentConstraint
		$permissionManager->overrideUserRightsForTesting( $user, [ 'edit' ] );

		$edit = [
			'wpTextbox1' => '',
			'wpSection' => 'new',
			'wpSummary' => 'Summary'
		];
		$this->assertEdit(
			$title,
			null,
			$user,
			$edit,
			EditPage::AS_TEXTBOX_EMPTY,
			'expected AS_TEXTBOX_EMPTY update'
		);
	}

	/** NewSectionMissingSubjectConstraint integration */
	public function testNewSectionMissingSubjectConstraint() {
		// Require the summary
		$this->mergeMwGlobalArrayValue(
			'wgDefaultUserOptions',
			[ 'forceeditsummary' => 1 ]
		);

		$page = $this->getExistingTestPage( 'NewSectionMissingSubjectConstraint page does exist' );
		$title = $page->getTitle();

		$user = $this->getTestUser()->getUser();

		$permissionManager = $this->getServiceContainer()->getPermissionManager();
		// Needs these rights to pass AuthorizationConstraint and reach NewSectionMissingSubjectConstraint
		$permissionManager->overrideUserRightsForTesting( $user, [ 'edit' ] );

		$edit = [
			'wpTextbox1' => 'Comment',
			'wpSection' => 'new',
			'wpSummary' => ''
		];
		$this->assertEdit(
			$title,
			null,
			$user,
			$edit,
			EditPage::AS_SUMMARY_NEEDED,
			'expected AS_SUMMARY_NEEDED update'
		);
	}

	/** PageSizeConstraint integration */
	public function testPageSizeConstraintBeforeMerge() {
		// Max size: 1 kibibyte
		$this->overrideConfigValue( MainConfigNames::MaxArticleSize, 1 );

		$edit = [
			'wpTextbox1' => str_repeat( 'text', 1000 )
		];
		$this->assertEdit(
			'EditPageTest_pageSizeConstraintBeforeMerge',
			null,
			null,
			$edit,
			EditPage::AS_CONTENT_TOO_BIG,
			'expected AS_CONTENT_TOO_BIG update'
		);
	}

	/** PageSizeConstraint integration */
	public function testPageSizeConstraintAfterMerge() {
		// Max size: 1 kibibyte
		$this->overrideConfigValue( MainConfigNames::MaxArticleSize, 1 );

		$edit = [
			'wpSection' => 'new',
			'wpTextbox1' => str_repeat( 'b', 600 )
		];
		$this->assertEdit(
			'EditPageTest_pageSizeConstraintAfterMerge',
			str_repeat( 'a', 600 ),
			null,
			$edit,
			EditPage::AS_MAX_ARTICLE_SIZE_EXCEEDED,
			'expected AS_MAX_ARTICLE_SIZE_EXCEEDED update'
		);
	}

	/** ReadOnlyConstraint integration */
	public function testReadOnlyConstraint() {
		$readOnlyMode = $this->createMock( ReadOnlyMode::class );
		$readOnlyMode->method( 'isReadOnly' )->willReturn( true );
		$this->setService( 'ReadOnlyMode', $readOnlyMode );

		$edit = [
			'wpTextbox1' => 'Text goes here'
		];
		$this->assertEdit(
			'EditPageTest_readOnlyConstraint',
			null,
			null,
			$edit,
			EditPage::AS_READ_ONLY_PAGE,
			'expected AS_READ_ONLY_PAGE update'
		);
	}

	/** SelfRedirectConstraint integration */
	public function testSelfRedirectConstraint() {
		// Use a page that does not exist to be sure that it is not already a self redirect
		$page = $this->getNonexistingTestPage( 'SelfRedirectConstraint page does not exist' );
		$title = $page->getTitle();

		$edit = [
			'wpTextbox1' => '#REDIRECT [[SelfRedirectConstraint page does not exist]]',
			'wpSummary' => 'Redirect to self'
		];
		$this->assertEdit(
			$title,
			'zero',
			null,
			$edit,
			EditPage::AS_SELF_REDIRECT,
			'expected AS_SELF_REDIRECT update'
		);
	}

	/** SimpleAntiSpamConstraint integration */
	public function testSimpleAntiSpamConstraint() {
		$edit = [
			'wpTextbox1' => 'one',
			'wpSummary' => 'first update',
			'wpAntispam' => 'tatata'
		];
		$this->assertEdit(
			'EditPageTest_testUpdatePageSpamError',
			'zero',
			null,
			$edit,
			EditPage::AS_SPAM_ERROR,
			'expected AS_SPAM_ERROR update'
		);
	}

	/** SpamRegexConstraint integration */
	public function testSpamRegexConstraint() {
		$spamChecker = $this->createMock( SpamChecker::class );
		$spamChecker->method( 'checkContent' )
			->willReturnArgument( 0 );
		$spamChecker->method( 'checkSummary' )
			->willReturnArgument( 0 );
		$this->setService( 'SpamChecker', $spamChecker );

		$edit = [
			'wpTextbox1' => 'two',
			'wpSummary' => 'spam summary'
		];
		$this->assertEdit(
			'EditPageTest_testUpdatePageSpamRegexError',
			'zero',
			null,
			$edit,
			EditPage::AS_SPAM_ERROR,
			'expected AS_SPAM_ERROR update'
		);
	}

	/** AuthorizationConstraint integration - user blocks */
	public function testAuthorizationConstraint_block() {
		$permissionManager = $this->createMock( PermissionManager::class );
		$permissionStatus = PermissionStatus::newEmpty();
		$permissionStatus->setBlock( $this->makeMockBlock() );
		// Not worried about the specifics of the method call, those are tested in
		// the AuthorizationConstraintTest
		$permissionManager->method( 'getPermissionStatus' )->willReturn( $permissionStatus );
		$this->setService( 'PermissionManager', $permissionManager );

		$edit = [
			'wpTextbox1' => 'Page content',
			'wpSummary' => 'Summary'
		];
		$this->assertEdit(
			'EditPageTest_userBlocked',
			'base text',
			null,
			$edit,
			EditPage::AS_BLOCKED_PAGE_FOR_USER,
			'expected AS_BLOCKED_PAGE_FOR_USER update'
		);
	}

	/** LinkPurgeRateLimitConstraint integration */
	public function testLinkPurgeRateLimitConstraint() {
		$this->setTemporaryHook(
			'PingLimiter',
			static function ( $user, $action, &$result, $incrBy ) {
				// Always fail
				$result = true;
				return false;
			}
		);

		$edit = [
			'wpTextbox1' => 'Text goes here'
		];
		$this->assertEdit(
			'EditPageTest_userRateLimitConstraint',
			null,
			null,
			$edit,
			EditPage::AS_RATE_LIMITED,
			'expected AS_RATE_LIMITED update'
		);
	}

}
