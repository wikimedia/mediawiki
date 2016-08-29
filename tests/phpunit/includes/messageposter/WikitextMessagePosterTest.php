<?php

use MediaWiki\MessagePoster\WikitextMessagePoster;

/**
 * Tests for WikitextMessagePoster
 *
 * @group Database
 *
 * @covers MediaWiki\MessagePoster\WikitextMessagePoster
 */
class WikitextMessagePosterTest extends MediaWikiTestCase {
	protected $talkPageTitle;

	protected $messagePoster;

	protected $sysop;

	function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[ 'ipblocks', 'logging', 'page', 'protected_titles', 'revision', 'text', 'user_groups' ]
		);
	}

	protected function setUp() {
		parent::setUp();

		Title::clearCaches();

		$this->setMwGlobals( 'wgNamespaceContentModels', [] );
		$this->talkPageTitle = Title::newFromText( 'Talk:WikitextMessagePoster' );

		$this->messagePoster = new WikitextMessagePoster( $this->talkPageTitle );

		$this->sysop = $this->getTestSysop()->getUser();
	}

	public function testSuccessfulPost() {
		$this->messagePoster->post( $this->sysop, 'Subject', 'Body text' );

		$revision = Revision::newFromTitle( $this->talkPageTitle, 0, Revision::READ_LATEST );
		$actualContent = $revision->getContent( Revision::RAW );
		$actualText = $actualContent->getNativeData();

		$rc = $revision->getRecentChange();
		$this->assertSame(
			'0',
			$rc->getAttribute( 'rc_bot' ),
			'Non-bot user with bot parameter false should not set bot flag'
		);

		$this->assertStringStartsWith(
			"== Subject ==\n\nBody text",
			$actualText,
			'Page is created with correct section heading and body text'
		);
	}

	/**
	 * @expectedException MWException
	 * @expectedExceptionMessage You have been blocked from editing
	 */
	public function testBlockedUserPost() {
		$blockedUser = $this->getMutableTestUser()->getUser();
		$block = new Block();
		$block->setTarget( $blockedUser );
		$block->setBlocker( $this->sysop );
		$block->mReason = 'Test';
		$block->mExpiry = 'infinite';
		$block->prevents( 'editownusertalk', false );
		$block->insert();

		$this->messagePoster->post( $blockedUser, 'Subject', 'Body text' );
	}

	/**
	 * @expectedException MWException
	 * @expectedExceptionMessage The "editprotected" right is required to edit this page
	 */
	public function testProtectedPagePost() {
		$normalUser = $this->getTestUser()->getUser();
		$talkPage = WikiPage::factory( $this->talkPageTitle );
		$cascade = true;
		$talkPage->doUpdateRestrictions(
			[ 'create' => 'sysop' ],
			[ 'infinity' ],
			$cascade,
			'Test',
			$this->sysop
		);

		$this->messagePoster->post( $normalUser, 'Subject', 'Body text' );
	}

	public function testBotPost() {
		$botUser = $this->getMutableTestUser()->getUser();
		$botUser->addGroup( 'bot' );

		$this->messagePoster->post( $botUser, 'Posted with bot flag true', 'Body text', true );
		$firstRevision = Revision::newFromTitle( $this->talkPageTitle, 0, Revision::READ_LATEST );
		$firstRc = $firstRevision->getRecentChange();
		$this->assertSame(
			'1',
			$firstRc->getAttribute( 'rc_bot' ),
			'Bot user with bot parameter true should set bot flag'
		);

		$this->messagePoster->post( $botUser, 'Posted with bot flag false', 'Body text', false );
		$secondRevision = Revision::newFromTitle( $this->talkPageTitle, 0, Revision::READ_LATEST );
		$secondRc = $secondRevision->getRecentChange();
		$this->assertSame(
			'0',
			$secondRc->getAttribute( 'rc_bot' ),
			'Bot user with bot parameter false should not set bot flag'
		);
	}
}
