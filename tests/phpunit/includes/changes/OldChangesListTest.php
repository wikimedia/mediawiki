<?php

/**
 * @covers OldChangesList
 *
 * @todo add tests to cover article link, timestamp, character difference,
 *       log entry, user tool links, direction marks, tags, rollback,
 *       watching users, and date header.
 *
 * @group Database
 *
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class OldChangesListTest extends MediaWikiLangTestCase {

	/**
	 * @var TestRecentChangesHelper
	 */
	private $testRecentChangesHelper;

	public function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->testRecentChangesHelper = new TestRecentChangesHelper();
	}

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgArticlePath' => '/wiki/$1',
		] );
		$this->setUserLang( 'qqx' );
	}

	/**
	 * @dataProvider recentChangesLine_CssForLineNumberProvider
	 */
	public function testRecentChangesLine_CssForLineNumber( $expected, $linenumber, $message ) {
		$oldChangesList = $this->getOldChangesList();
		$recentChange = $this->getEditChange();

		$line = $oldChangesList->recentChangesLine( $recentChange, false, $linenumber );

		$this->assertRegExp( $expected, $line, $message );
	}

	public function recentChangesLine_CssForLineNumberProvider() {
		return [
			[ '/mw-line-odd/', 1, 'odd line number' ],
			[ '/mw-line-even/', 2, 'even line number' ]
		];
	}

	public function testRecentChangesLine_NotWatchedCssClass() {
		$oldChangesList = $this->getOldChangesList();
		$recentChange = $this->getEditChange();

		$line = $oldChangesList->recentChangesLine( $recentChange, false, 1 );

		$this->assertRegExp( '/mw-changeslist-line-not-watched/', $line );
	}

	public function testRecentChangesLine_WatchedCssClass() {
		$oldChangesList = $this->getOldChangesList();
		$recentChange = $this->getEditChange();

		$line = $oldChangesList->recentChangesLine( $recentChange, true, 1 );

		$this->assertRegExp( '/mw-changeslist-line-watched/', $line );
	}

	public function testRecentChangesLine_LogTitle() {
		$oldChangesList = $this->getOldChangesList();
		$recentChange = $this->getLogChange( 'delete', 'delete' );

		$line = $oldChangesList->recentChangesLine( $recentChange, false, 1 );

		$this->assertRegExp( '/href="\/wiki\/Special:Log\/delete/', $line, 'link has href attribute' );
		$this->assertRegExp( '/title="Special:Log\/delete/', $line, 'link has title attribute' );
		$this->assertRegExp( "/dellogpage/", $line, 'link text' );
	}

	public function testRecentChangesLine_DiffHistLinks() {
		$oldChangesList = $this->getOldChangesList();
		$recentChange = $this->getEditChange();

		$line = $oldChangesList->recentChangesLine( $recentChange, false, 1 );

		$this->assertRegExp(
			'/title=Cat&amp;curid=20131103212153&amp;diff=5&amp;oldid=191/',
			$line,
			'assert diff link'
		);

		$this->assertRegExp( '/tabindex="0"/', $line, 'assert tab index' );
		$this->assertRegExp(
			'/title=Cat&amp;curid=20131103212153&amp;action=history"/',
			$line,
			'assert history link'
		);
	}

	public function testRecentChangesLine_Flags() {
		$oldChangesList = $this->getOldChangesList();
		$recentChange = $this->getNewBotEditChange();

		$line = $oldChangesList->recentChangesLine( $recentChange, false, 1 );

		$this->assertContains(
			'<abbr class="newpage" title="(recentchanges-label-newpage)">(newpageletter)</abbr>',
			$line,
			'new page flag'
		);

		$this->assertContains(
			'<abbr class="botedit" title="(recentchanges-label-bot)">(boteditletter)</abbr>',
			$line,
			'bot flag'
		);
	}

	public function testRecentChangesLine_Tags() {
		$recentChange = $this->getEditChange();
		$recentChange->mAttribs['ts_tags'] = 'vandalism,newbie';

		$oldChangesList = $this->getOldChangesList();
		$line = $oldChangesList->recentChangesLine( $recentChange, false, 1 );

		$this->assertRegExp( '/<li class="[\w\s-]*mw-tag-vandalism[\w\s-]*">/', $line );
		$this->assertRegExp( '/<li class="[\w\s-]*mw-tag-newbie[\w\s-]*">/', $line );
	}

	public function testRecentChangesLine_numberOfWatchingUsers() {
		$oldChangesList = $this->getOldChangesList();

		$recentChange = $this->getEditChange();
		$recentChange->numberofWatchingusers = 100;

		$line = $oldChangesList->recentChangesLine( $recentChange, false, 1 );
		$this->assertRegExp( "/(number_of_watching_users_RCview: 100)/", $line );
	}

	public function testRecentChangesLine_watchlistCssClass() {
		$oldChangesList = $this->getOldChangesList();
		$oldChangesList->setWatchlistDivs( true );

		$recentChange = $this->getEditChange();
		$line = $oldChangesList->recentChangesLine( $recentChange, false, 1 );
		$this->assertRegExp( "/watchlist-0-Cat/", $line );
	}

	private function getNewBotEditChange() {
		$user = $this->getTestUser();

		$recentChange = $this->testRecentChangesHelper->makeNewBotEditRecentChange(
			$user, 'Abc', '20131103212153', 5, 191, 190, 0, 0
		);

		return $recentChange;
	}

	private function getLogChange( $logType, $logAction ) {
		$user = $this->getTestUser();

		$recentChange = $this->testRecentChangesHelper->makeLogRecentChange(
			$logType, $logAction, $user, 'Abc', '20131103212153', 0, 0
		);

		return $recentChange;
	}

	private function getEditChange() {
		$user = $this->getTestUser();
		$recentChange = $this->testRecentChangesHelper->makeEditRecentChange(
			$user, 'Cat', '20131103212153', 5, 191, 190, 0, 0
		);

		return $recentChange;
	}

	private function getOldChangesList() {
		$context = $this->getContext();
		return new OldChangesList( $context );
	}

	private function getTestUser() {
		$user = User::newFromName( 'TestRecentChangesUser' );

		if ( !$user->getId() ) {
			$user->addToDatabase();
		}

		return $user;
	}

	private function getContext() {
		$user = $this->getTestUser();
		$context = $this->testRecentChangesHelper->getTestContext( $user );
		$context->setLanguage( 'qqx' );

		return $context;
	}

}
