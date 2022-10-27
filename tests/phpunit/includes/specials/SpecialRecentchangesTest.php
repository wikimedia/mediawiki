<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use Wikimedia\TestingAccessWrapper;

/**
 * Test class for SpecialRecentchanges class
 *
 * @group Database
 *
 * @covers SpecialRecentChanges
 * @covers ChangesListSpecialPage
 */
class SpecialRecentchangesTest extends AbstractChangesListSpecialPageTestCase {
	use MockAuthorityTrait;

	protected function getPage(): SpecialRecentChanges {
		return new SpecialRecentChanges(
			$this->getServiceContainer()->getWatchedItemStore(),
			$this->getServiceContainer()->getMessageCache(),
			$this->getServiceContainer()->getDBLoadBalancer(),
			$this->getServiceContainer()->getUserOptionsLookup()
		);
	}

	/**
	 * @return TestingAccessWrapper
	 */
	protected function getPageAccessWrapper() {
		return TestingAccessWrapper::newFromObject( $this->getPage() );
	}

	// Below providers should only be for features specific to
	// RecentChanges.  Otherwise, it should go in ChangesListSpecialPageTest

	public function provideParseParameters() {
		return [
			[ 'limit=123', [ 'limit' => '123' ] ],

			[ '234', [ 'limit' => '234' ] ],

			[ 'days=3', [ 'days' => '3' ] ],

			[ 'days=0.25', [ 'days' => '0.25' ] ],

			[ 'namespace=5', [ 'namespace' => '5' ] ],

			[ 'namespace=5|3', [ 'namespace' => '5|3' ] ],

			[ 'tagfilter=foo', [ 'tagfilter' => 'foo' ] ],

			[ 'tagfilter=foo;bar', [ 'tagfilter' => 'foo;bar' ] ],
		];
	}

	public function validateOptionsProvider() {
		return [
			[
				// hidebots=1 is default for Special:RecentChanges
				[ 'hideanons' => 1, 'hideliu' => 1 ],
				true,
				[ 'hideliu' => 1 ],
				false,
			],
		];
	}

	public function testAddWatchlistJoins() {
		// Edit a test page so that it shows up in RC.
		$testPage = $this->getExistingTestPage( 'Test page' );
		$this->editPage( $testPage, 'Test content', '' );

		// Set up RC.
		$context = new RequestContext;
		$context->setTitle( Title::newFromText( __METHOD__ ) );
		$context->setUser( $this->getTestUser()->getUser() );
		$context->setRequest( new FauxRequest );

		// Confirm that the test page is in RC.
		$rc1 = $this->getPage();
		$rc1->setContext( $context );
		$rc1->execute( null );
		$this->assertStringContainsString( 'Test page', $rc1->getOutput()->getHTML() );
		$this->assertStringContainsString( 'mw-changeslist-line-not-watched', $rc1->getOutput()->getHTML() );

		// Watch the page, and check that it's now watched in RC.
		$watchedItemStore = $this->getServiceContainer()->getWatchedItemStore();
		$watchedItemStore->addWatch( $context->getUser(), $testPage );
		$rc2 = $this->getPage();
		$rc2->setContext( $context );
		$rc2->execute( null );
		$this->assertStringContainsString( 'Test page', $rc2->getOutput()->getHTML() );
		$this->assertStringContainsString( 'mw-changeslist-line-watched', $rc2->getOutput()->getHTML() );

		// Force a past expiry date on the watchlist item.
		$db = wfGetDB( DB_PRIMARY );
		$queryConds = [ 'wl_namespace' => $testPage->getNamespace(), 'wl_title' => $testPage->getDBkey() ];
		$watchedItemId = $db->selectField( 'watchlist', 'wl_id', $queryConds, __METHOD__ );
		$db->update(
			'watchlist_expiry',
			[ 'we_expiry' => $db->timestamp( '20200101000000' ) ],
			[ 'we_item' => $watchedItemId ],
			__METHOD__
		);

		// Check that the page is still in RC, but that it's no longer watched.
		$rc3 = $this->getPage();
		$rc3->setContext( $context );
		$rc3->execute( null );
		$this->assertStringContainsString( 'Test page', $rc3->getOutput()->getHTML() );
		$this->assertStringContainsString( 'mw-changeslist-line-not-watched', $rc3->getOutput()->getHTML() );
	}

	public function testExperienceLevelFilter() {
		// Edit a test page so that it shows up in RC.
		$testPage = $this->getExistingTestPage( 'Experience page' );
		$this->editPage( $testPage, 'Registered content',
			'registered summary', NS_MAIN, $this->getTestUser()->getUser() );
		$this->editPage( $testPage, 'Anon content',
			'anon summary', NS_MAIN, $this->mockAnonUltimateAuthority() );

		// Set up RC.
		$context = new RequestContext;
		$context->setTitle( Title::newFromText( __METHOD__ ) );
		$context->setUser( $this->getTestUser()->getUser() );
		$context->setRequest( new FauxRequest );

		// Confirm that the test page is in RC.
		[ $html ] = ( new SpecialPageExecutor() )->executeSpecialPage(
			$this->getPage(),
			'',
			new FauxRequest()
		);
		$this->assertStringContainsString( 'Experience page', $html );

		// newcomer
		$req = new FauxRequest();
		$req->setVal( 'userExpLevel', 'newcomer' );
		[ $html ] = ( new SpecialPageExecutor() )->executeSpecialPage(
			$this->getPage(),
			'',
			$req
		);
		$this->assertStringContainsString( 'registered summary', $html );

		// anon
		$req = new FauxRequest();
		$req->setVal( 'userExpLevel', 'unregistered' );
		[ $html ] = ( new SpecialPageExecutor() )->executeSpecialPage(
			$this->getPage(),
			'',
			$req
		);
		$this->assertStringContainsString( 'anon summary', $html );
		$this->assertStringNotContainsString( 'registered summary', $html );

		// registered
		$req = new FauxRequest();
		$req->setVal( 'userExpLevel', 'registered' );
		[ $html ] = ( new SpecialPageExecutor() )->executeSpecialPage(
			$this->getPage(),
			'',
			$req
		);
		$this->assertStringContainsString( 'registered summary', $html );
		$this->assertStringNotContainsString( 'anon summary', $html );
	}

	/**
	 * This integration test just tries to run the isDenseFilter() queries, to
	 * check for syntax errors etc. It doesn't verify the logic.
	 */
	public function testIsDenseTagFilter() {
		$this->tablesUsed[] = 'change_tag_def';
		$this->tablesUsed[] = 'change_tag';
		ChangeTags::defineTag( 'rc-test-tag' );
		$req = new FauxRequest();
		$req->setVal( 'tagfilter', 'rc-test-tag' );
		$page = $this->getPage();

		// Make sure thresholds are passed
		$page->denseRcSizeThreshold = 0;
		$this->overrideConfigValue( MainConfigNames::MiserMode, true );

		( new SpecialPageExecutor() )->executeSpecialPage( $page, '', $req );
		$this->assertTrue( true );
	}

	public static function provideDenseTagFilter() {
		return [
			[ false ],
			[ true ]
		];
	}

	/**
	 * This integration test injects the return value of isDenseFilter(),
	 * verifying the correctness of the resulting STRAIGHT_JOIN.
	 *
	 * @dataProvider provideDenseTagFilter
	 */
	public function testDenseTagFilter( $dense ) {
		$this->tablesUsed[] = 'change_tag_def';
		$this->tablesUsed[] = 'change_tag';
		ChangeTags::defineTag( 'rc-test-tag' );
		$req = new FauxRequest();
		$req->setVal( 'tagfilter', 'rc-test-tag' );

		$page = new class (
			$dense,
			$this->getServiceContainer()->getWatchedItemStore(),
			$this->getServiceContainer()->getMessageCache(),
			$this->getServiceContainer()->getDBLoadBalancer(),
			$this->getServiceContainer()->getUserOptionsLookup()
		)  extends SpecialRecentChanges {
			private $dense;

			public function __construct(
				$dense,
				WatchedItemStoreInterface $watchedItemStore = null,
				MessageCache $messageCache = null, \Wikimedia\Rdbms\ILoadBalancer $loadBalancer = null,
				\MediaWiki\User\UserOptionsLookup $userOptionsLookup = null
			) {
				parent::__construct( $watchedItemStore, $messageCache, $loadBalancer,
					$userOptionsLookup );
				$this->dense = $dense;
			}

			protected function isDenseTagFilter( $tagIds, $limit ) {
				return $this->dense;
			}
		};

		( new SpecialPageExecutor() )->executeSpecialPage( $page, '', $req );
		$this->assertTrue( true );
	}
}
