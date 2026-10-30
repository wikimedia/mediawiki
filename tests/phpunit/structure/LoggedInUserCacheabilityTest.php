<?php
namespace MediaWiki\Tests\Structure;

use MediaWiki\Context\RequestContext;
use MediaWiki\Output\OutputPage;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Skin\Skin;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;
use Wikimedia\Message\MessageValue;

/**
 * @group Database
 * @coversNothing
 */
class LoggedInUserCacheabilityTest extends MediaWikiIntegrationTestCase {
	use TempUserTestTrait;

	private const CACHE_ILLEGIBLE_PREFERENCES = [
		// Language varies the footer and heading
		'language',
		// Skin changes the entire HTML.
		'skin',
	];

	// These preferences should be updated so they don't vary cache.
	private const CACHE_BUSTING_PREFERENCES = [
		//
		// core
		// changes output of search HTML and sticky header in Vector 2022
		'search-special-page',
		//
		// MobileFrontend
		//
		// AMC changes menus
		// TODO: This is not currently being detected because enabling
		// mobile+AMC mode is a pain.
		'mf_amc_optin',
		//
		// PersonalDashboard
		//
		'personaldashboard-eligible',
		//
		// ParserMigration
		//
		'parsermigration-parsoid-readviews',
		'parsermigration',
		//
		// VisualEditor
		//
		// these change the edit icons shown in page
		'visualeditor-tabs',
		"visualeditor-editor",
		 "visualeditor-autodisable",
		"visualeditor-betatempdisable",
		//
		// Vector
		//
		'vector-page-tools-pinned',
		'vector-main-menu-pinned',
		'vector-toc-pinned',
		'vector-appearance-pinned',
		//
		// Extension:GrowthExperiments
		//
		// Adds banner
		'homepage_mobile_discovery_notice_seen',
		// Changes home page link
		'growthexperiments-homepage-enable',
	];

	private static function getNonDefaultPreference( string $name, string $default = '' ): string {
		switch ( $default ) {
			case '1':
				return '0';
			case '0':
				return '1';
			default:
				// Append rather than transform, so the new value can never coincidentally
				// collide with another meaningful value (e.g. reversing "en" gives "ne").
				return $default . '-nondefault-value';
		}
	}

	/**
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	private function getNewValue( string $name, $default ) {
		$newValue = $default;
		// Notifications are enabled by default
		// We intentionally do not flip them.
		if ( strpos( $name, 'echo-subscriptions-web-' ) === 0 ) {
			// In future if notifications don't impact cache set this true.
			$newValue = !$default;
		} elseif ( is_bool( $default ) ) {
			$newValue = !$default;
		} elseif ( is_int( $default ) ) {
			if ( $default === 0 ) {
				// assume binary on/off
				$default = 1;
			} elseif ( $default < 3 ) {
				// change a 1 to a 0 or a 2 to 0.
				// probably a [0,1,2] type
				$newValue = 0;
			} else {
				$newValue = $default + 2;
			}
		} elseif ( is_float( $default ) ) {
			$newValue = $default + 1.0;
		} elseif ( is_string( $default ) ) {
			$newValue = self::getNonDefaultPreference( $name, $default );
		} else {
			// assume a date
			$newValue = '20260901000000';
		}
		return $newValue;
	}

	private function changeAllPreferencesFromDefaults( User $user ) {
		$userOptionsManager = $this->getServiceContainer()->getUserOptionsManager();
		$defaultOptions = $userOptionsManager->getDefaultOptions( $user );
		$ignoredPreferences = array_merge(
			self::CACHE_ILLEGIBLE_PREFERENCES,
			self::CACHE_BUSTING_PREFERENCES
		);
		// The following preferences will not be cached so shouldn't be changed.
		foreach ( $defaultOptions as $name => $default ) {
			if ( in_array( $name, $ignoredPreferences ) ) {
				continue;
			}
			$userOptionsManager->setOption( $user, $name, $this->getNewValue( $name, $default ) );
		}
		$userOptionsManager->saveOptions( $user );
	}

	 /**
	  * Prepares a skin for testing, assigning context and output page
	  *
	  * @param string $skinName
	  * @param Title $title
	  * @param User $user
	  *
	  * @return Skin
	  * @throws SkinException
	  */
	protected function prepareSkin( string $skinName, Title $title, User $user ): Skin {
		$services = $this->getServiceContainer();
		// Configure the main context BEFORE constructing the skin. Some skins (e.g.
		// SkinMinerva) call $this->getContext() from their own constructor - to hand
		// off to Definitions/MinervaPagePermissions/SkinUserPageHelper - which falls
		// back to RequestContext::getMain() since $skin->setContext() hasn't run yet
		// at that point. If that fallback isn't already configured here, those
		// classes end up holding a context with no title set, and things like
		// Definitions::getMenuData() crash calling getPrefixedText() on null later.
		// Use a fresh main context (rather than a DerivativeContext) so that
		// RequestContext::getMain() returns $context too: this matters for code that
		// falls back to the global context instead of using the one passed to it.
		RequestContext::resetMain();
		$context = RequestContext::getMain();
		$context->setUser( $user );
		$context->setTitle( $title );

		$skinFactory = $services->getSkinFactory();
		$skin = $skinFactory->makeSkin( $skinName );
		$outputPage = new OutputPage( $context );
		$context->setOutput( $outputPage );
		$skin->setContext( $context );
		$context->setSkin( $skin );
		$outputPage->setTitle( $title );
		return $skin;
	}

	public function getSkin(): string {
		return 'vector-2022';
	}

	private static function unsetUsernameItems( Skin $skin, array &$menus ): void {
		unset( $menus['user-menu']['readinglists'] );
		unset( $menus['user-menu']['mytalk'] );
		unset( $menus['user-menu']['sandbox'] );
		unset( $menus['user-menu']['userpage'] );
		unset( $menus['user-page']['userpage'] );
		unset( $menus['user-menu']['mycontris'] );
		unset( $menus['user-menu']['mytalk'] );
	}

	private static function unsetLoggedInOnlyItems( Skin $skin, array &$menus ): void {
		unset( $menus['user-menu']['personaldashboard'] );
		unset( $menus['user-menu']['preferences'] );
		unset( $menus['user-menu']['betafeatures'] );
		unset( $menus['user-menu']['watchlist'] );
		unset( $menus['user-menu']['login'] );
		unset( $menus['user-menu']['createaccount'] );
		unset( $menus['user-menu']['sitesupport'] );
		unset( $menus['user-menu']['logout'] );

		unset( $menus['actions']['watch'] );
	}

	private static function unsetPermissionBasedItems( Skin $skin, array &$menus ): void {
		unset( $menus['actions']['protect'] );
		unset( $menus['actions']['move'] );
		unset( $menus['actions']['delete'] );
	}

	private static function unsetNotificationItems( Skin $skin, array &$menus ): void {
		unset( $menus['notifications']['notifications-alert'] );
		unset( $menus['notifications']['notifications-notice'] );
		unset( $menus['notifications']['talk-alert'] );
		// Minerva
		unset( $menus['notifications']['notifications'] );
	}

	private static function unsetEditCountItems( Skin $skin, array &$menus ): void {
		unset( $menus['user-menu']['watchlist'] );
	}

	private static function unsetWatchItems( Skin $skin, array &$menus ): void {
		unset( $menus['actions']['watch'] );
		unset( $menus['actions']['unwatch'] );
	}

	/**
	 * Tests whether all logged in users are eligible for caching.
	 *
	 * @coversNothing
	 *
	 * @return void
	 * @throws \Wikimedia\RequestTimeout\TimeoutException
	 * @throws MediaWiki\Config\ConfigException
	 */
	public function testCachingEligibilityLoggedIn() {
		$skinName = $this->getSkin();
		// Register with $replace=false so this is appended after, and thus runs later
		// than, any extension-registered handler.
		$this->setTemporaryHook( 'SkinTemplateNavigation::Universal', static function ( $skin, &$menus ) {
			// Permissions based items
			self::unsetPermissionBasedItems( $skin, $menus );
			// Watching a page changes the watch icon and label
			self::unsetWatchItems( $skin, $menus );
			// Vary by edit count
			self::unsetEditCountItems( $skin, $menus );
			// Vary by username
			self::unsetUsernameItems( $skin, $menus );
			// Notifications based.
			self::unsetNotificationItems( $skin, $menus );
		}, false );
		// Check template data
		$loggedinUser = $this->getTestUser()->getUser();
		$title = $this->getExistingTestPage()->getTitle();
		$skinLoggedIn = $this->prepareSkin( $skinName, $title, $loggedinUser );
		$namedSkinData = $skinLoggedIn->getTemplateData();
		// A mutable (not shared/cached) test user, since it's about to be promoted
		// to sysop below; getTestUser() with the same (empty) groups as $loggedinUser
		// would return that same shared immutable fixture instead of a distinct user.
		$sysopUser = $this->getMutableTestUser( [ 'sysop' ] );
		$loggedinUserExtended = $sysopUser->getUser();
		$this->changeAllPreferencesFromDefaults( $loggedinUserExtended );
		$serviceContainer = $this->getServiceContainer();
		$serviceContainer->getNotificationService()->notifySimple(
			MessageValue::new( 'test', [ 'a', 'b' ] ),
			$sysopUser->getUserIdentity()
		);
		// Perform an edit since this might result in changes to user-specific state.
		// Explicitly use the wikitext namespace: getNonexistingTestPage() defaults to
		// NS_MAIN, which some extensions (e.g. WikiLambda) repurpose for non-wikitext
		// content, so 'Hello World!' would fail to unserialize as that content model.
		$testPage = $this->getNonexistingTestPage(
			Title::newFromText( 'Test page ' . wfRandomString(), $this->getDefaultWikitextNS() )
		);
		$status = $this->editPage( $testPage, 'Hello World!', 'Hello World!', $this->getDefaultWikitextNS(), $loggedinUserExtended );
		$cascade = false; // map to variable to pass by reference.
		$testPage->doUpdateRestrictions(
			[ 'edit' => 'sysop' ],
			[],
			$cascade,
			'Test page protection for cacheability check',
			$loggedinUserExtended
		);

		// Trigger an "alert" section notification (edit-user-talk) as well, so both
		// notification badges (alert and message) are populated for this user, not
		// just the "message" one triggered by notifySimple() above. edit-user-talk
		// isn't canNotifyAgent, so the editor must be someone other than the
		// recipient - $loggedinUser edits $loggedinUserExtended's talk page.
		$this->editPage(
			$loggedinUserExtended->getTalkPage(),
			'Hello!',
			'Notify test',
			NS_MAIN,
			$loggedinUser
		);
		// Watch the page as the extended user, so the skins are compared with one
		// user's watch state genuinely differing from the other's (rather than both
		// being unwatched, which wouldn't exercise the watch-star/page-actions code
		// at all).
		$serviceContainer->getWatchlistManager()->addWatch(
			$loggedinUserExtended,
			$title
		);
		$skinExtendedUserExtended = $this->prepareSkin( $skinName, $title, $loggedinUserExtended );
		$extendedUserSkinData = $skinExtendedUserExtended->getTemplateData();
		$bodyAttrsLoggedInUser = $skinLoggedIn->getOutput()->getBodyAttributes(
			$skinLoggedIn
		);
		$bodyAttrsLoggedInUserExtended = $skinExtendedUserExtended->getOutput()->getBodyAttributes(
			$skinExtendedUserExtended
		);
		$this->assertSame(
			$bodyAttrsLoggedInUser,
			$bodyAttrsLoggedInUserExtended,
			'body tag should not vary by user'
		);
		$this->assertSame(
			array_keys( $namedSkinData ),
			array_keys( $extendedUserSkinData ),
			'Skin template data keys should not vary for different logged in users.'
		);
		foreach ( $extendedUserSkinData as $key => $value ) {
			$this->assertSame( $value, $namedSkinData[ $key ], 'Skin key ' . $key . ' varies for logged in user types in skin ' . $skinName );
		}
	}

	/**
	 * Tests whether all temporary users are eligible for caching.
	 *
	 * @coversNothing
	 *
	 * @return void
	 * @throws \Wikimedia\RequestTimeout\TimeoutException
	 * @throws MediaWiki\Config\ConfigException
	 */
	public function testCachingEligibilityTempAccounts() {
		$this->enableAutoCreateTempUser();
		$skinName = $this->getSkin();
		$this->setTemporaryHook( 'SkinTemplateNavigation::Universal', static function ( $skin, &$menus ) {
			self::unsetUsernameItems( $skin, $menus );
		}, false );
		// Check template data
		$loggedinUser = $this->getTestUser()->getUser();
		$title = $this->getExistingTestPage()->getTitle();
		$tuc = $this->getServiceContainer()->getTempUserCreator();
		$status = $tuc->create( null, new FauxRequest() );
		$userTmp = $status->getUser();
		$statusTwo = $tuc->create( null, new FauxRequest() );
		$userTmpTwo = $statusTwo->getUser();
		$skinTmp = $this->prepareSkin( $skinName, $title, $userTmp );
		$skinTmpTwo = $this->prepareSkin( $skinName, $title, $userTmpTwo );
		$tmpSkinData = $skinTmp->getTemplateData();
		$tmpSkinDataTwo = $skinTmpTwo->getTemplateData();
		$bodyAttrsTmp = $skinTmp->getOutput()->getBodyAttributes(
			$skinTmp
		);
		$bodyAttrsTmpTwo = $skinTmpTwo->getOutput()->getBodyAttributes(
			$skinTmpTwo
		);
		$this->assertSame(
			$bodyAttrsTmp,
			$bodyAttrsTmpTwo,
			'body tag for two different temp user should be same'
		);
		foreach ( $tmpSkinData as $key => $value ) {
			$this->assertSame( $value, $tmpSkinDataTwo[ $key ], 'Skin key ' . $key . ' varies for temp user types in skin ' . $skinName );
		}
	}
}
