<?php

/**
 * @covers EnhancedChangesList
 *
 * @group Database
 *
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class EnhancedChangesListTest extends MediaWikiLangTestCase {

	/**
	 * @var TestRecentChangesHelper
	 */
	private $testRecentChangesHelper;

	public function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->testRecentChangesHelper = new TestRecentChangesHelper();
	}

	public function testBeginRecentChangesList_styleModules() {
		$enhancedChangesList = $this->newEnhancedChangesList();
		$enhancedChangesList->beginRecentChangesList();

		$styleModules = $enhancedChangesList->getOutput()->getModuleStyles();

		$this->assertContains(
			'mediawiki.icon',
			$styleModules,
			'has mediawiki.icon'
		);

		$this->assertContains(
			'mediawiki.special.changeslist',
			$styleModules,
			'has mediawiki.special.changeslist'
		);

		$this->assertContains(
			'mediawiki.special.changeslist.enhanced',
			$styleModules,
			'has mediawiki.special.changeslist.enhanced'
		);
	}

	public function testBeginRecentChangesList_jsModules() {
		$enhancedChangesList = $this->newEnhancedChangesList();
		$enhancedChangesList->beginRecentChangesList();

		$modules = $enhancedChangesList->getOutput()->getModules();

		$this->assertContains( 'jquery.makeCollapsible', $modules, 'has jquery.makeCollapsible' );
	}

	public function testBeginRecentChangesList_html() {
		$enhancedChangesList = $this->newEnhancedChangesList();
		$html = $enhancedChangesList->beginRecentChangesList();

		$this->assertEquals( '<div class="mw-changeslist" aria-live="polite">', $html );
	}

	/**
	 * @todo more tests
	 */
	public function testRecentChangesLine() {
		$enhancedChangesList = $this->newEnhancedChangesList();
		$enhancedChangesList->beginRecentChangesList();

		$recentChange = $this->getEditChange( '20131103092153' );
		$html = $enhancedChangesList->recentChangesLine( $recentChange, false );

		$this->assertIsString( $html );

		$recentChange2 = $this->getEditChange( '20131103092253' );
		$html = $enhancedChangesList->recentChangesLine( $recentChange2, false );

		$this->assertSame( '', $html );
	}

	public function testRecentChangesPrefix() {
		$mockContext = $this->getMockBuilder( RequestContext::class )
			->setMethods( [ 'getTitle' ] )
			->getMock();
		$mockContext->method( 'getTitle' )
			->will( $this->returnValue( Title::newFromText( 'Expected Context Title' ) ) );

		// One group of two lines
		$enhancedChangesList = $this->newEnhancedChangesList();
		$enhancedChangesList->setContext( $mockContext );
		$enhancedChangesList->setChangeLinePrefixer( function ( $rc, $changesList ) {
			// Make sure RecentChange and ChangesList objects are the same
			$this->assertEquals( 'Expected Context Title', $changesList->getContext()->getTitle() );
			$this->assertTrue( $rc->getTitle() == 'Cat' || $rc->getTitle() == 'Dog' );
			return 'Hello world prefix';
		} );

		$this->setTemporaryHook( 'EnhancedChangesListModifyLineData', function (
			$enhancedChangesList, &$data, $block, $rc, &$classes, &$attribs
		) {
			$data['recentChangesFlags']['minor'] = 1;
		} );

		$this->setTemporaryHook( 'EnhancedChangesListModifyBlockLineData', function (
			$enhancedChangesList, &$data, $rcObj
		) {
			$data['recentChangesFlags']['bot'] = 1;
		} );

		$enhancedChangesList->beginRecentChangesList();

		$recentChange = $this->getEditChange( '20131103092153' );
		$enhancedChangesList->recentChangesLine( $recentChange );
		$recentChange = $this->getEditChange( '20131103092154' );
		$enhancedChangesList->recentChangesLine( $recentChange );

		$html = $enhancedChangesList->endRecentChangesList();

		$this->assertRegExp( '/Hello world prefix/', $html );

		// Test EnhancedChangesListModifyLineData hook was run
		$this->assertRegExp( '/This is a minor edit/', $html );

		// Two separate lines
		$enhancedChangesList->beginRecentChangesList();

		$recentChange = $this->getEditChange( '20131103092153' );
		$enhancedChangesList->recentChangesLine( $recentChange );
		$recentChange = $this->getEditChange( '20131103092154', 'Dog' );
		$enhancedChangesList->recentChangesLine( $recentChange );

		$html = $enhancedChangesList->endRecentChangesList();

		// Test EnhancedChangesListModifyBlockLineData hook was run
		$this->assertRegExp( '/This edit was performed by a bot/', $html );

		preg_match_all( '/Hello world prefix/', $html, $matches );
		$this->assertCount( 2, $matches[0] );
	}

	public function testCategorizationLineFormatting() {
		$html = $this->createCategorizationLine(
			$this->getCategorizationChange( '20150629191735', 0, 0 )
		);
		$this->assertStringNotContainsString( 'diffhist', strip_tags( $html ) );
	}

	public function testCategorizationLineFormattingWithRevision() {
		$html = $this->createCategorizationLine(
			$this->getCategorizationChange( '20150629191735', 1025, 1024 )
		);
		$this->assertStringContainsString( 'diffhist', strip_tags( $html ) );
	}

	/**
	 * @todo more tests for actual formatting, this is more of a smoke test
	 */
	public function testEndRecentChangesList() {
		$enhancedChangesList = $this->newEnhancedChangesList();
		$enhancedChangesList->beginRecentChangesList();

		$recentChange = $this->getEditChange( '20131103092153' );
		$enhancedChangesList->recentChangesLine( $recentChange, false );

		$html = $enhancedChangesList->endRecentChangesList();
		$this->assertRegExp(
			'/data-mw-revid="5" data-mw-ts="20131103092153" class="[^"]*mw-enhanced-rc[^"]*"/',
			$html
		);

		$recentChange2 = $this->getEditChange( '20131103092253' );
		$enhancedChangesList->recentChangesLine( $recentChange2, false );

		$html = $enhancedChangesList->endRecentChangesList();

		preg_match_all( '/td class="mw-enhanced-rc-nested"/', $html, $matches );
		$this->assertCount( 2, $matches[0] );

		preg_match_all( '/data-target-page="Cat"/', $html, $matches );
		$this->assertCount( 2, $matches[0] );

		$recentChange3 = $this->getLogChange();
		$enhancedChangesList->recentChangesLine( $recentChange3, false );

		$html = $enhancedChangesList->endRecentChangesList();
		$this->assertStringContainsString( 'data-mw-logaction="foo/bar"', $html );
		$this->assertStringContainsString( 'data-mw-logid="25"', $html );
		$this->assertStringContainsString( 'data-target-page="Title"', $html );
	}

	/**
	 * @return EnhancedChangesList
	 */
	private function newEnhancedChangesList() {
		$user = User::newFromId( 0 );
		$context = $this->testRecentChangesHelper->getTestContext( $user );

		return new EnhancedChangesList( $context );
	}

	/**
	 * @return RecentChange
	 */
	private function getEditChange( $timestamp, $pageTitle = 'Cat' ) {
		$user = $this->getMutableTestUser()->getUser();
		$recentChange = $this->testRecentChangesHelper->makeEditRecentChange(
			$user, $pageTitle, 0, 5, 191, $timestamp, 0, 0
		);

		return $recentChange;
	}

	private function getLogChange() {
		$user = $this->getMutableTestUser()->getUser();
		$recentChange = $this->testRecentChangesHelper->makeLogRecentChange( 'foo', 'bar', $user,
			'Title', '20131103092153', 0, 0
		);

		return $recentChange;
	}

	/**
	 * @return RecentChange
	 */
	private function getCategorizationChange( $timestamp, $thisId, $lastId ) {
		$wikiPage = new WikiPage( Title::newFromText( 'Testpage' ) );
		$wikiPage->doEditContent( new WikitextContent( 'Some random text' ), 'page created' );

		$wikiPage = new WikiPage( Title::newFromText( 'Category:Foo' ) );
		$wikiPage->doEditContent( new WikitextContent( 'Some random text' ), 'category page created' );

		$user = $this->getMutableTestUser()->getUser();
		$recentChange = $this->testRecentChangesHelper->makeCategorizationRecentChange(
			$user, 'Category:Foo', $wikiPage->getId(), $thisId, $lastId, $timestamp
		);

		return $recentChange;
	}

	private function createCategorizationLine( $recentChange ) {
		$enhancedChangesList = $this->newEnhancedChangesList();
		$cacheEntry = $this->testRecentChangesHelper->getCacheEntry( $recentChange );

		$reflection = new \ReflectionClass( get_class( $enhancedChangesList ) );
		$method = $reflection->getMethod( 'recentChangesBlockLine' );
		$method->setAccessible( true );

		return $method->invokeArgs( $enhancedChangesList, [ $cacheEntry ] );
	}

	public function testExpiringWatchlistItem(): void {
		// Set current time to 2020-05-05.
		MWTimestamp::setFakeTime( '20200505000000' );
		$enhancedChangesList = $this->newEnhancedChangesList();
		$enhancedChangesList->getOutput()->enableOOUI();
		$enhancedChangesList->setWatchlistDivs( true );

		$row = (object)[
			'rc_namespace' => NS_MAIN,
			'rc_title' => '',
			'rc_timestamp' => '20150921134808',
			'rc_deleted' => '',
			'rc_comment_text' => 'comment',
			'rc_comment_data' => null,
			'rc_user' => $this->getTestUser()->getUser()->getId(),
			'we_expiry' => '20200101000000',
		];
		$rc = RecentChange::newFromRow( $row );

		// Make sure it doesn't output anything for a past expiry.
		$html1 = $enhancedChangesList->getWatchlistExpiry( $rc );
		$this->assertSame( '', $html1 );

		// Check a future expiry for the right tooltip text.
		$rc->watchlistExpiry = '20200512000000';
		$html2 = $enhancedChangesList->getWatchlistExpiry( $rc );
		$this->assertStringContainsString( "title='7 days left in your watchlist'", $html2 );

		// Check that multiple changes on the same day all get the clock icon.
		$enhancedChangesList->beginRecentChangesList();
		// 1. Expire on 2020-06-01 (27 days):
		$rc1 = $this->getEditChange( '20200501000001', __METHOD__ . '1' );
		$rc1->watchlistExpiry = '20200601000000';
		$enhancedChangesList->recentChangesLine( $rc1 );
		// 2. Expire on 2020-06-08 (34 days):
		$rc2 = $this->getEditChange( '20200501000002', __METHOD__ . '2' );
		$rc2->watchlistExpiry = '20200608000000';
		$enhancedChangesList->recentChangesLine( $rc2 );
		// Get and test the HTML.
		$html3 = $enhancedChangesList->endRecentChangesList();
		$this->assertStringContainsString( '27 days left in your watchlist', $html3 );
		$this->assertStringContainsString( '34 days left in your watchlist', $html3 );
	}
}
