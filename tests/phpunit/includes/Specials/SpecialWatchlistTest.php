<?php
namespace MediaWiki\Tests\Specials;

use DatabaseTestHelper;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Exception\UserNotLoggedIn;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Specials\SpecialWatchlist;
use MediaWiki\User\Registration\UserRegistrationLookup;
use MediaWiki\User\StaticUserOptionsLookup;
use TestUser;
use Wikimedia\Rdbms\LBFactorySingle;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @author Addshore
 *
 * @group Database
 *
 * @covers \MediaWiki\Specials\SpecialWatchlist
 */
class SpecialWatchlistTest extends SpecialPageTestBase {
	protected function setUp(): void {
		parent::setUp();

		$this->setTemporaryHook(
			'ChangesListSpecialPageQuery',
			HookContainer::NOOP
		);

		$this->overrideConfigValues( [
			MainConfigNames::DefaultUserOptions =>
				[
					'extendwatchlist' => 1,
					'watchlistdays' => 3.0,
					'watchlisthideanons' => 0,
					'watchlisthidebots' => 0,
					'watchlisthideliu' => 0,
					'watchlisthideminor' => 0,
					'watchlisthideown' => 0,
					'watchlisthidepatrolled' => 1,
					'watchlisthidecategorization' => 0,
					'watchlistreloadautomatically' => 0,
					'watchlistunwatchlinks' => 0,
					'timecorrection' => '0'
				],
			MainConfigNames::WatchlistExpiry => true
		] );
	}

	/**
	 * Returns a new instance of the special page under test.
	 *
	 * @return SpecialWatchlist
	 */
	protected function newSpecialPage() {
		$services = $this->getServiceContainer();
		return new SpecialWatchlist(
			$services->getWatchedItemStore(),
			$services->getWatchlistManager(),
			$services->getUserOptionsLookup(),
			$services->getUserIdentityUtils(),
			$services->getTempUserConfig(),
			$services->getRecentChangeFactory(),
			$services->getChangesListQueryFactory(),
		);
	}

	public function testNotLoggedIn_throwsException() {
		$this->expectException( UserNotLoggedIn::class );
		$this->executeSpecialPage();
	}

	public function testUserWithNoWatchedItems_displaysNoWatchlistMessage() {
		$user = new TestUser( __METHOD__ );
		[ $html, ] = $this->executeSpecialPage( '', null, 'qqx', $user->getUser() );
		$this->assertStringContainsString( '(nowatchlist)', $html );
	}

	/**
	 * @dataProvider provideFetchOptionsFromRequest
	 */
	public function testFetchOptionsFromRequest(
		$expectedValuesDefaults, $expectedValues, $preferences, $inputParams
	) {
		// $defaults and $allFalse are just to make the expected values below
		// shorter by hiding the background.

		/** @var SpecialWatchlist $page */
		$page = TestingAccessWrapper::newFromObject(
			$this->newSpecialPage()
		);

		$page->registerFilters();

		// Does not consider $preferences, just wiki's defaults
		$wikiDefaults = $page->getDefaultOptions()->getAllValues();

		switch ( $expectedValuesDefaults ) {
			case 'allFalse':
				$allFalse = $wikiDefaults;

				foreach ( $allFalse as $key => $value ) {
					if ( $value === true ) {
						$allFalse[$key] = false;
					}
				}

				// This is not exposed on the form (only in preferences) so it
				// respects the preference.
				$allFalse['extended'] = true;

				$expectedValues += $allFalse;
				break;
			case 'wikiDefaults':
				$expectedValues += $wikiDefaults;
				break;
			default:
				$this->fail( "Unknown \$expectedValuesDefaults: $expectedValuesDefaults" );
		}

		$page = TestingAccessWrapper::newFromObject(
			$this->newSpecialPage()
		);

		$context = new DerivativeContext( $page->getContext() );

		$fauxRequest = new FauxRequest( $inputParams, /* $wasPosted= */ false );
		$user = $this->getTestUser()->getUser();

		$userOptionsManager = $this->getServiceContainer()->getUserOptionsManager();
		foreach ( $preferences as $key => $value ) {
			$userOptionsManager->setOption( $user, $key, $value );
		}

		$context->setRequest( $fauxRequest );
		$context->setUser( $user );
		$page->setContext( $context );

		$page->registerFilters();
		$formOptions = $page->getDefaultOptions();
		$page->fetchOptionsFromRequest( $formOptions );

		$this->assertArrayEquals(
			$expectedValues,
			$formOptions->getAllValues(),
			/* $ordered= */ false,
			/* $named= */ true
		);
	}

	public static function provideFetchOptionsFromRequest() {
		return [
			'ignores casing' => [
				'expectedValuesDefaults' => 'wikiDefaults',
				'expectedValues' => [
					'hideminor' => true,
				],
				'preferences' => [],
				'inputParams' => [
					'hideMinor' => 1,
				],
			],

			'first two same as prefs, second two overridden' => [
				'expectedValuesDefaults' => 'wikiDefaults',
				'expectedValues' => [
					// First two same as prefs
					'hideminor' => true,
					'hidebots' => false,

					// Second two overridden
					'hideanons' => false,
					'hideliu' => true,
					'userExpLevel' => 'registered'
				],
				'preferences' => [
					'watchlisthideminor' => 1,
					'watchlisthidebots' => 0,

					'watchlisthideanons' => 1,
					'watchlisthideliu' => 0,
				],
				'inputParams' => [
					'hideanons' => 0,
					'hideliu' => 1,
				],
			],

			'Defaults/preferences for form elements are entirely ignored for '
			. 'action=submit and omitted elements become false' => [
				'expectedValuesDefaults' => 'allFalse',
				'expectedValues' => [
					'hideminor' => false,
					'hidebots' => true,
					'hideanons' => false,
					'hideliu' => true,
					'userExpLevel' => 'unregistered'
				],
				'preferences' => [
					'watchlisthideminor' => 0,
					'watchlisthidebots' => 1,

					'watchlisthideanons' => 0,
					'watchlisthideliu' => 1,
				],
				'inputParams' => [
					'hidebots' => 1,
					'hideliu' => 1,
					'action' => 'submit',
				],
			],
		];
	}

	/**
	 * Check the exact SQL used in the main query so that we can see if
	 * refactoring changes it.
	 *
	 * We could probably delete this when we are done with refactoring.
	 */
	public function testDoMainQuery() {
		ConvertibleTimestamp::setFakeTime( '20250101000000' );
		$user = $this->getTestUser()->getUser();
		$db = new DatabaseTestHelper( SpecialWatchlist::class );
		$this->setService(
			'ConnectionProvider',
			LBFactorySingle::newFromConnection( $db )
		);
		$userOptionsLookup = new StaticUserOptionsLookup( [], [ 'wllimit' => 50 ] );
		$this->setService(
			'UserOptionsLookup',
			$userOptionsLookup
		);
		$userRegistrationLookup = $this->createMock( UserRegistrationLookup::class );
		$userRegistrationLookup->method( 'getRegistration' )
			->willReturn( '20250101000000' );
		$this->setService(
			'UserRegistrationLookup',
			$userRegistrationLookup
		);
		$page = $this->newSpecialPage();
		TestingAccessWrapper::newFromObject( $page )->userOptionsLookup
			= $userOptionsLookup;
		$page->getContext()->setUser( $user );
		$page->getRows();
		// Warning: significant line-ending whitespace
		$expected = <<<SQL
SELECT 
	recentchanges_actor.actor_user AS rc_user,
	recentchanges_actor.actor_name AS rc_user_text,
	rc_bot,
	rc_minor,
	rc_this_oldid,
	page_latest,
	rc_source,
	rc_log_type,
	rc_timestamp,
	rc_namespace,
	rc_title,
	wl_notificationtimestamp,
	(
		SELECT GROUP_CONCAT(ctd_name SEPARATOR ',') 
		FROM change_tag JOIN change_tag_def ON ((ct_tag_id=ctd_id)) 
		WHERE (ct_rc_id=rc_id)  
	) AS ts_tags,
	rc_id,
	rc_cur_id,
	rc_last_oldid,
	rc_type,
	rc_patrolled,
	rc_ip,
	rc_old_len,
	rc_new_len,
	rc_deleted,
	rc_logid,
	rc_log_action,
	rc_params,
	rc_actor,
	recentchanges_comment.comment_text AS rc_comment_text,
	recentchanges_comment.comment_data AS rc_comment_data,
	recentchanges_comment.comment_id AS rc_comment_id,
	we_expiry 
FROM recentchanges 
	JOIN actor recentchanges_actor ON ((actor_id=rc_actor)) 
	JOIN comment recentchanges_comment ON ((comment_id=rc_comment_id)) 
	LEFT JOIN page ON ((page_id=rc_cur_id)) 
	JOIN watchlist ON ((wl_namespace=rc_namespace) AND (wl_title=rc_title) AND wl_user = 1) 
	LEFT JOIN watchlist_expiry ON ((we_item=wl_id)) 
WHERE ((rc_this_oldid = page_latest OR rc_this_oldid = 0)) 
	AND ((we_expiry IS NULL OR we_expiry > '20250101000000')) 
	AND ((rc_source != 'mw.log' OR (rc_deleted & 1) != 1)) 
	AND (rc_timestamp >= '20250101000000') 
ORDER BY rc_timestamp DESC,rc_id DESC 
LIMIT 50
SQL;

		$this->assertSame(
			$this->normalizeSql( $expected ),
			$this->normalizeSql( $db->getLastSqls() )
		);
	}

	private function normalizeSql( $sql ) {
		$sql = preg_replace( '/^\t*/m', '', $sql );
		$sql = str_replace( "\n", '', $sql );
		$sql = preg_replace( '/(?!\'),/', ",\n", $sql );
		$sql = preg_replace( '/SELECT|FROM|LEFT JOIN|JOIN|AND|WHERE|ORDER BY|LIMIT/', "\n$0", $sql );
		return $sql;
	}

	/**
	 * Regression test for T407996
	 */
	public function testUnstructuredFilters() {
		$user = $this->getTestUser()->getUser();
		$userOptionsLookup = new StaticUserOptionsLookup( [], [
			'wlenhancedfilters-disable' => true,
			'extendwatchlist' => false,
		] );
		$this->setService(
			'UserOptionsLookup',
			$userOptionsLookup
		);
		$page = $this->newSpecialPage();
		TestingAccessWrapper::newFromObject( $page )->userOptionsLookup
			= $userOptionsLookup;
		$page->getContext()->setUser( $user );
		$page->getRows();
		$this->assertTrue(
			$page->getFilterGroup( 'extended-group' )->getFilter( 'extended' )
				->isActive( $page->getOptions(), false )
		);
	}
}
