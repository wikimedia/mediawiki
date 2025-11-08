<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\RecentChanges\OldChangesList;
use MediaWiki\Title\Title;

/**
 * @todo add tests to cover article link, timestamp, character difference,
 *       log entry, user tool links, direction marks, tags, rollback,
 *       watching users, and date header.
 *
 * @covers \MediaWiki\RecentChanges\OldChangesList
 * @group Database
 * @author Katie Filbert <aude.wiki@gmail.com>
 */
class OldChangesListTest extends MediaWikiLangTestCase {

	/**
	 * @var TestRecentChangesHelper
	 */
	private $testRecentChangesHelper;

	protected function setUp(): void {
		parent::setUp();

		$this->setUserLang( 'qqx' );
		$this->testRecentChangesHelper = new TestRecentChangesHelper();
	}

	/**
	 * @dataProvider recentChangesLine_CssForLineNumberProvider
	 */
	public function testRecentChangesLine_CssForLineNumber( $expected, $linenumber, $message ) {
		$oldChangesList = $this->getOldChangesList();
		$recentChange = $this->getEditChange();

		$line = $oldChangesList->recentChangesLine( $recentChange, false, $linenumber );

		$this->assertMatchesRegularExpression( $expected, $line, $message );
	}

	public static function recentChangesLine_CssForLineNumberProvider() {
		return [
			[ '/mw-line-odd/', 1, 'odd line number' ],
			[ '/mw-line-even/', 2, 'even line number' ]
		];
	}

	public function testRecentChangesLine_NotWatchedCssClass() {
		$oldChangesList = $this->getOldChangesList();
		$recentChange = $this->getEditChange();

		$line = $oldChangesList->recentChangesLine( $recentChange, false, 1 );

		$this->assertMatchesRegularExpression( '/mw-changeslist-line-not-watched/', $line );
	}

	public function testRecentChangesLine_WatchedCssClass() {
		$oldChangesList = $this->getOldChangesList();
		$recentChange = $this->getEditChange();

		$line = $oldChangesList->recentChangesLine( $recentChange, true, 1 );

		$this->assertMatchesRegularExpression( '/mw-changeslist-line-watched/', $line );
	}

	public function testRecentChangesLine_LogTitle() {
		$oldChangesList = $this->getOldChangesList();
		$recentChange = $this->getLogChange( 'delete', 'delete' );

		$line = $oldChangesList->recentChangesLine( $recentChange, false, 1 );

		$this->assertMatchesRegularExpression( '/href="\/wiki\/Special:Log\/delete/', $line, 'link has href attribute' );
		$this->assertMatchesRegularExpression( '/title="Special:Log\/delete/', $line, 'link has title attribute' );
		$this->assertMatchesRegularExpression( "/dellogpage/", $line, 'link text' );
	}

	public function testRecentChangesLine_DiffHistLinks() {
		$oldChangesList = $this->getOldChangesList();
		$recentChange = $this->getEditChange();

		$line = $oldChangesList->recentChangesLine( $recentChange, false, 1 );

		$this->assertMatchesRegularExpression(
			'/title=Cat&amp;curid=20131103212153&amp;diff=5&amp;oldid=191/',
			$line,
			'assert diff link'
		);

		$this->assertMatchesRegularExpression(
			'/title=Cat&amp;curid=20131103212153&amp;action=history"/',
			$line,
			'assert history link'
		);
	}

	public function testRecentChangesLine_Flags() {
		$oldChangesList = $this->getOldChangesList();
		$recentChange = $this->getNewBotEditChange();

		$line = $oldChangesList->recentChangesLine( $recentChange, false, 1 );

		$this->assertStringContainsString(
			'<abbr class="newpage" title="(recentchanges-label-newpage)">(newpageletter)</abbr>',
			$line,
			'new page flag'
		);

		$this->assertStringContainsString(
			'<abbr class="botedit" title="(recentchanges-label-bot)">(boteditletter)</abbr>',
			$line,
			'bot flag'
		);
	}

	public function testRecentChangesLine_Attribs() {
		$recentChange = $this->getEditChange();
		$recentChange->mAttribs['ts_tags'] = 'vandalism,newbie';

		$this->setTemporaryHook( 'OldChangesListRecentChangesLine', static function (
			$oldChangesList, &$html, $rc, $classes, $attribs
		) {
			$html = $html . '/<div>Additional change line </div>/';
		} );

		$oldChangesList = $this->getOldChangesList();
		$line = $oldChangesList->recentChangesLine( $recentChange, false, 1 );

		$this->assertStringContainsString(
			'/<div>Additional change line </div>/',
			$line
		);
		$this->assertMatchesRegularExpression(
			'/<li data-mw-revid="\d+" data-mw-ts="\d+" class="[\w\s-]*mw-tag-vandalism[\w\s-]*">/',
			$line
		);
		$this->assertMatchesRegularExpression(
			'/<li data-mw-revid="\d+" data-mw-ts="\d+" class="[\w\s-]*mw-tag-newbie[\w\s-]*">/',
			$line
		);
	}

	public function testRecentChangesLine_numberOfWatchingUsers() {
		$oldChangesList = $this->getOldChangesList();

		$recentChange = $this->getEditChange();
		$recentChange->numberofWatchingusers = 100;

		$line = $oldChangesList->recentChangesLine( $recentChange, false, 1 );
		$this->assertMatchesRegularExpression( "/(number-of-watching-users-for-recent-changes: 100)/", $line );
	}

	public function testRecentChangesLine_watchlistCssClass() {
		$oldChangesList = $this->getOldChangesList();
		$oldChangesList->setWatchlistDivs( true );

		$recentChange = $this->getEditChange();
		$line = $oldChangesList->recentChangesLine( $recentChange, false, 1 );
		$this->assertMatchesRegularExpression( "/watchlist-0-Cat/", $line );
	}

	public function testRecentChangesLine_dataAttribute() {
		$oldChangesList = $this->getOldChangesList();
		$oldChangesList->setWatchlistDivs( true );

		$recentChange = $this->getEditChange();
		$line = $oldChangesList->recentChangesLine( $recentChange, false, 1 );
		$this->assertMatchesRegularExpression( '/data-target-page=\"Cat\"/', $line );

		$recentChange = $this->getLogChange( 'delete', 'delete' );
		$line = $oldChangesList->recentChangesLine( $recentChange, false, 1 );
		$this->assertMatchesRegularExpression( '/data-target-page="Abc"/', $line );
	}

	public function testRecentChangesLine_prefix() {
		$mockContext = $this->getMockBuilder( RequestContext::class )
			->onlyMethods( [ 'getTitle' ] )
			->getMock();
		$mockContext->method( 'getTitle' )
			->willReturn( Title::makeTitle( NS_MAIN, 'Expected Context Title' ) );

		$oldChangesList = $this->getOldChangesList();
		$oldChangesList->setContext( $mockContext );
		$recentChange = $this->getEditChange();

		$oldChangesList->setChangeLinePrefixer( function ( $rc, $changesList ) {
			// Make sure RecentChange and ChangesList objects are the same
			$this->assertEquals( 'Expected Context Title', $changesList->getContext()->getTitle() );
			$this->assertEquals( 'Cat', $rc->getTitle() );
			return 'I am a prefix';
		} );
		$line = $oldChangesList->recentChangesLine( $recentChange );
		$this->assertMatchesRegularExpression( "/I am a prefix/", $line );
	}

	private function getNewBotEditChange() {
		$user = $this->getMutableTestUser()->getUser();

		$recentChange = $this->testRecentChangesHelper->makeNewBotEditRecentChange(
			$user, 'Abc', '20131103212153', 5, 191, 190, 0, 0
		);

		return $recentChange;
	}

	private function getLogChange( $logType, $logAction ) {
		$user = $this->getMutableTestUser()->getUser();

		$recentChange = $this->testRecentChangesHelper->makeLogRecentChange(
			$logType, $logAction, $user, 'Abc', '20131103212153', 0, 0
		);

		return $recentChange;
	}

	private function getEditChange() {
		$user = $this->getMutableTestUser()->getUser();
		$recentChange = $this->testRecentChangesHelper->makeEditRecentChange(
			$user, 'Cat', '20131103212153', 5, 191, 190, 0, 0
		);

		return $recentChange;
	}

	private function getOldChangesList() {
		$context = $this->getContext();
		return new OldChangesList( $context );
	}

	private function getContext() {
		$user = $this->getMutableTestUser()->getUser();
		$context = $this->testRecentChangesHelper->getTestContext( $user );
		$context->setLanguage( 'qqx' );

		return $context;
	}

}
