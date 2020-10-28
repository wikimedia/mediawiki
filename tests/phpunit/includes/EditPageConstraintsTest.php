<?php

use MediaWiki\EditPage\SpamChecker;
use MediaWiki\Permissions\PermissionManager;

/**
 * Integration tests for the various edit constraints, ensuring
 * that they result in failures as expected
 *
 * @covers EditPage::internalAttemptSave
 *
 * @group Editing
 * @group Database
 * @group medium
 */
class EditPageConstraintsTest extends MediaWikiLangTestCase {

	protected function setUp() : void {
		parent::setUp();

		$this->setContentLang( $this->getServiceContainer()->getContentLanguage() );

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
	 * Based on method in EditPageTest
	 * Performs an edit and checks the result matches the expected failure code
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
	 * @param int $expectedCode The expected result code (EditPage::AS_XXX constants).
	 * @param string $message Message to show along with any error message.
	 *
	 * @return WikiPage The page that was just edited, useful for getting the edit's rev_id, etc.
	 */
	protected function assertEdit(
		$title,
		$baseText,
		$user,
		array $edit,
		$expectedCode,
		$message
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

			// Set the latest timestamp back a while
			$dbw = wfGetDB( DB_MASTER );
			$dbw->update(
				'revision',
				[ 'rev_timestamp' => $dbw->timestamp( '20120101000000' ) ],
				[ 'rev_id' => $page->getLatest() ]
			);
			$page->clear();

			// sanity check
			$currentText = ContentHandler::getContentText( $page->getContent() );

			# EditPage rtrim() the user input, so we alter our expected text
			# to reflect that.
			$this->assertEquals(
				rtrim( $baseText ),
				rtrim( $currentText ),
				'Sanity check: page should have the text specified'
			);
		}

		if ( $user == null ) {
			$user = $this->getTestUser()->getUser();
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

		$bot = isset( $edit['bot'] ) ? (bool)$edit['bot'] : false;

		// this is where the edit happens!
		// Note: don't want to use EditPage::attemptSave, because it messes with $wgOut
		// and throws exceptions like PermissionsError
		$status = $ep->internalAttemptSave( $result, $bot );

		// check edit code
		$this->assertSame(
			$expectedCode,
			$status->value,
			"Expected result code mismatch. $message"
		);

		return WikiPage::factory( $title );
	}

	/** ContentModelChangeConstraint integration */
	public function testContentModelChangeConstraint() {
		$user = $this->createMock( User::class );
		$user->method( 'isAnon' )->willReturn( false );
		$user->method( 'getName' )->willReturn( 'NameGoesHere' );
		$user->method( 'getId' )->willReturn( 12345 );

		$permissionManager = $this->getServiceContainer()->getPermissionManager();
		// Needs edit rights to pass EditRightConstraint and reach ContentModelChangeConstraint
		$permissionManager->overrideUserRightsForTesting( $user, [ 'edit' ] );

		$edit = [
			'wpTextbox1' => 'New text goes here',
			'wpSummary' => 'Summary',
			'model' => CONTENT_MODEL_TEXT,
			'format' => CONTENT_FORMAT_TEXT,
		];

		$title = Title::newFromText( 'Example', NS_MAIN );
		$this->assertSame(
			CONTENT_MODEL_WIKITEXT,
			$title->getContentModel(),
			'Sanity check: title should start as wikitext content model'
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

	/**
	 * EditRightConstraint integration
	 * @dataProvider provideTestEditRightConstraint
	 * @param bool $anon
	 * @param int $expectedErrorCode
	 */
	public function testEditRightConstraint( $anon, $expectedErrorCode ) {
		$user = $this->createMock( User::class );
		$user->method( 'isAnon' )->willReturn( $anon );
		$user->method( 'getName' )->willReturn( $anon ? '1.1.1.1' : 'NameGoesHere' );
		$user->method( 'getId' )->willReturn( $anon ? 0 : 12345 );

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

	public function provideTestEditRightConstraint() {
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
		$user = $this->createMock( User::class );
		$user->method( 'isAnon' )->willReturn( $anon );
		$user->method( 'getName' )->willReturn( $anon ? '1.1.1.1' : 'NameGoesHere' );
		$user->method( 'getId' )->willReturn( $anon ? 0 : 12345 );

		$permissionManager = $this->getServiceContainer()->getPermissionManager();
		// Needs edit rights to pass EditRightConstraint and reach ImageRedirectConstraint
		$permissionManager->overrideUserRightsForTesting( $user, [ 'edit' ] );

		$edit = [
			'wpTextbox1' => '#REDIRECT [[File:Example other file.jpg]]',
			'wpSummary' => 'Summary'
		];

		$title = Title::newFromText( 'Example.jpg', NS_FILE );
		$this->assertEdit(
			$title,
			null,
			$user,
			$edit,
			$expectedErrorCode,
			'expected AS_IMAGE_REDIRECT_* update'
		);
	}

	public function provideTestImageRedirectConstraint() {
		yield 'Anonymous user' => [ true, EditPage::AS_IMAGE_REDIRECT_ANON ];
		yield 'Registered user' => [ false, EditPage::AS_IMAGE_REDIRECT_LOGGED ];
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
			->will( $this->returnArgument( 0 ) );
		$spamChecker->method( 'checkSummary' )
			->will( $this->returnArgument( 0 ) );
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

	/** UserBlockConstraint integration */
	public function testUserBlockConstraint() {
		$user = $this->createMock( User::class );
		$user->method( 'getName' )->willReturn( 'NameGoesHere' );
		$user->method( 'getId' )->willReturn( 12345 );

		$permissionManager = $this->createMock( PermissionManager::class );
		// Needs edit rights to pass EditRightConstraint and reach UserBlockConstraint
		$permissionManager->method( 'userHasRight' )->willReturn( true );

		// Not worried about the specifics of the method call, those are tested in
		// the UserBlockConstraintTest
		$permissionManager->method( 'isBlockedFrom' )->willReturn( true );

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

	/** UserRateLimitConstraint integration */
	public function testUserRateLimitConstraint() {
		$this->setTemporaryHook(
			'PingLimiter',
			function ( $user, $action, &$result, $incrBy ) {
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
