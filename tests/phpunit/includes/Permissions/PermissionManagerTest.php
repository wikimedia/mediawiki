<?php

namespace MediaWiki\Tests\Permissions;

use Action;
use Block;
use MediaWikiLangTestCase;
use RequestContext;
use Title;
use User;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionManager;

/**
 * @group Database
 *
 * @covers \MediaWiki\Permissions\PermissionManager
 */
class PermissionManagerTest extends MediaWikiLangTestCase {

	/**
	 * @var string
	 */
	protected $userName, $altUserName;

	/**
	 * @var Title
	 */
	protected $title;

	/**
	 * @var User
	 */
	protected $user, $anonUser, $userUser, $altUser;

	/**
	 * @var PermissionManager
	 */
	protected $permissionManager;

	/** Constant for self::testIsBlockedFrom */
	const USER_TALK_PAGE = '<user talk page>';

	protected function setUp() {
		parent::setUp();

		$localZone = 'UTC';
		$localOffset = date( 'Z' ) / 60;

		$this->setMwGlobals( [
			'wgLocaltimezone' => $localZone,
			'wgLocalTZoffset' => $localOffset,
			'wgNamespaceProtection' => [
				NS_MEDIAWIKI => 'editinterface',
			],
		] );
		// Without this testUserBlock will use a non-English context on non-English MediaWiki
		// installations (because of how Title::checkUserBlock is implemented) and fail.
		RequestContext::resetMain();

		$this->userName = 'Useruser';
		$this->altUserName = 'Altuseruser';
		date_default_timezone_set( $localZone );

		$this->title = Title::makeTitle( NS_MAIN, "Main Page" );
		if ( !isset( $this->userUser ) || !( $this->userUser instanceof User ) ) {
			$this->userUser = User::newFromName( $this->userName );

			if ( !$this->userUser->getId() ) {
				$this->userUser = User::createNew( $this->userName, [
					"email" => "test@example.com",
					"real_name" => "Test User" ] );
				$this->userUser->load();
			}

			$this->altUser = User::newFromName( $this->altUserName );
			if ( !$this->altUser->getId() ) {
				$this->altUser = User::createNew( $this->altUserName, [
					"email" => "alttest@example.com",
					"real_name" => "Test User Alt" ] );
				$this->altUser->load();
			}

			$this->anonUser = User::newFromId( 0 );

			$this->user = $this->userUser;
		}

		$this->permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

		$this->overrideMwServices();
	}

	protected function setUserPerm( $perm ) {
		// Setting member variables is evil!!!

		if ( is_array( $perm ) ) {
			$this->user->mRights = $perm;
		} else {
			$this->user->mRights = [ $perm ];
		}
	}

	protected function setTitle( $ns, $title = "Main_Page" ) {
		$this->title = Title::makeTitle( $ns, $title );
	}

	protected function setUser( $userName = null ) {
		if ( $userName === 'anon' ) {
			$this->user = $this->anonUser;
		} elseif ( $userName === null || $userName === $this->userName ) {
			$this->user = $this->userUser;
		} else {
			$this->user = $this->altUser;
		}
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 *
	 * This test is failing per T201776.
	 *
	 * @group Broken
	 * @covers \MediaWiki\Permissions\PermissionManager::checkQuickPermissions
	 */
	public function testQuickPermissions() {
		$prefix = MediaWikiServices::getInstance()->getContentLanguage()->
		getFormattedNsText( NS_PROJECT );

		$this->setUser( 'anon' );
		$this->setTitle( NS_TALK );
		$this->setUserPerm( "createtalk" );
		$res = $this->permissionManager
			->getPermissionErrors( 'create', $this->user, $this->title );
		$this->assertEquals( [], $res );

		$this->setTitle( NS_TALK );
		$this->setUserPerm( "createpage" );
		$res = $this->permissionManager
			->getPermissionErrors( 'create', $this->user, $this->title );
		$this->assertEquals( [ [ "nocreatetext" ] ], $res );

		$this->setTitle( NS_TALK );
		$this->setUserPerm( "" );
		$res = $this->permissionManager
			->getPermissionErrors( 'create', $this->user, $this->title );
		$this->assertEquals( [ [ 'nocreatetext' ] ], $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "createpage" );
		$res = $this->permissionManager
			->getPermissionErrors( 'create', $this->user, $this->title );
		$this->assertEquals( [], $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "createtalk" );
		$res = $this->permissionManager
			->getPermissionErrors( 'create', $this->user, $this->title );
		$this->assertEquals( [ [ 'nocreatetext' ] ], $res );

		$this->setUser( $this->userName );
		$this->setTitle( NS_TALK );
		$this->setUserPerm( "createtalk" );
		$res = $this->permissionManager
			->getPermissionErrors( 'create', $this->user, $this->title );
		$this->assertEquals( [], $res );

		$this->setTitle( NS_TALK );
		$this->setUserPerm( "createpage" );
		$res = $this->permissionManager
			->getPermissionErrors( 'create', $this->user, $this->title );
		$this->assertEquals( [ [ 'nocreate-loggedin' ] ], $res );

		$this->setTitle( NS_TALK );
		$this->setUserPerm( "" );
		$res = $this->permissionManager
			->getPermissionErrors( 'create', $this->user, $this->title );
		$this->assertEquals( [ [ 'nocreate-loggedin' ] ], $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "createpage" );
		$res = $this->permissionManager
			->getPermissionErrors( 'create', $this->user, $this->title );
		$this->assertEquals( [], $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "createtalk" );
		$res = $this->permissionManager
			->getPermissionErrors( 'create', $this->user, $this->title );
		$this->assertEquals( [ [ 'nocreate-loggedin' ] ], $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "" );
		$res = $this->permissionManager
			->getPermissionErrors( 'create', $this->user, $this->title );
		$this->assertEquals( [ [ 'nocreate-loggedin' ] ], $res );

		$this->setUser( 'anon' );
		$this->setTitle( NS_USER, $this->userName . '' );
		$this->setUserPerm( "" );
		$res = $this->permissionManager
			->getPermissionErrors( 'move', $this->user, $this->title );
		$this->assertEquals( [ [ 'cant-move-user-page' ], [ 'movenologintext' ] ], $res );

		$this->setTitle( NS_USER, $this->userName . '/subpage' );
		$this->setUserPerm( "" );
		$res = $this->permissionManager
			->getPermissionErrors( 'move', $this->user, $this->title );
		$this->assertEquals( [ [ 'movenologintext' ] ], $res );

		$this->setTitle( NS_USER, $this->userName . '' );
		$this->setUserPerm( "move-rootuserpages" );
		$res = $this->permissionManager
			->getPermissionErrors( 'move', $this->user, $this->title );
		$this->assertEquals( [ [ 'movenologintext' ] ], $res );

		$this->setTitle( NS_USER, $this->userName . '/subpage' );
		$this->setUserPerm( "move-rootuserpages" );
		$res = $this->permissionManager
			->getPermissionErrors( 'move', $this->user, $this->title );
		$this->assertEquals( [ [ 'movenologintext' ] ], $res );

		$this->setTitle( NS_USER, $this->userName . '' );
		$this->setUserPerm( "" );
		$res = $this->permissionManager
			->getPermissionErrors( 'move', $this->user, $this->title );
		$this->assertEquals( [ [ 'cant-move-user-page' ], [ 'movenologintext' ] ], $res );

		$this->setTitle( NS_USER, $this->userName . '/subpage' );
		$this->setUserPerm( "" );
		$res = $this->permissionManager
			->getPermissionErrors( 'move', $this->user, $this->title );
		$this->assertEquals( [ [ 'movenologintext' ] ], $res );

		$this->setTitle( NS_USER, $this->userName . '' );
		$this->setUserPerm( "move-rootuserpages" );
		$res = $this->permissionManager
			->getPermissionErrors( 'move', $this->user, $this->title );
		$this->assertEquals( [ [ 'movenologintext' ] ], $res );

		$this->setTitle( NS_USER, $this->userName . '/subpage' );
		$this->setUserPerm( "move-rootuserpages" );
		$res = $this->permissionManager
			->getPermissionErrors( 'move', $this->user, $this->title );
		$this->assertEquals( [ [ 'movenologintext' ] ], $res );

		$this->setUser( $this->userName );
		$this->setTitle( NS_FILE, "img.png" );
		$this->setUserPerm( "" );
		$res = $this->permissionManager
			->getPermissionErrors( 'move', $this->user, $this->title );
		$this->assertEquals( [ [ 'movenotallowedfile' ], [ 'movenotallowed' ] ], $res );

		$this->setTitle( NS_FILE, "img.png" );
		$this->setUserPerm( "movefile" );
		$res = $this->permissionManager
			->getPermissionErrors( 'move', $this->user, $this->title );
		$this->assertEquals( [ [ 'movenotallowed' ] ], $res );

		$this->setUser( 'anon' );
		$this->setTitle( NS_FILE, "img.png" );
		$this->setUserPerm( "" );
		$res = $this->permissionManager
			->getPermissionErrors( 'move', $this->user, $this->title );
		$this->assertEquals( [ [ 'movenotallowedfile' ], [ 'movenologintext' ] ], $res );

		$this->setTitle( NS_FILE, "img.png" );
		$this->setUserPerm( "movefile" );
		$res = $this->permissionManager
			->getPermissionErrors( 'move', $this->user, $this->title );
		$this->assertEquals( [ [ 'movenologintext' ] ], $res );

		$this->setUser( $this->userName );
		$this->setUserPerm( "move" );
		$this->runGroupPermissions( 'move', [ [ 'movenotallowedfile' ] ] );

		$this->setUserPerm( "" );
		$this->runGroupPermissions(
			'move',
			[ [ 'movenotallowedfile' ], [ 'movenotallowed' ] ]
		);

		$this->setUser( 'anon' );
		$this->setUserPerm( "move" );
		$this->runGroupPermissions( 'move', [ [ 'movenotallowedfile' ] ] );

		$this->setUserPerm( "" );
		$this->runGroupPermissions(
			'move',
			[ [ 'movenotallowedfile' ], [ 'movenotallowed' ] ],
			[ [ 'movenotallowedfile' ], [ 'movenologintext' ] ]
		);

		if ( $this->isWikitextNS( NS_MAIN ) ) {
			// NOTE: some content models don't allow moving
			// @todo find a Wikitext namespace for testing

			$this->setTitle( NS_MAIN );
			$this->setUser( 'anon' );
			$this->setUserPerm( "move" );
			$this->runGroupPermissions( 'move', [] );

			$this->setUserPerm( "" );
			$this->runGroupPermissions( 'move', [ [ 'movenotallowed' ] ],
				[ [ 'movenologintext' ] ] );

			$this->setUser( $this->userName );
			$this->setUserPerm( "" );
			$this->runGroupPermissions( 'move', [ [ 'movenotallowed' ] ] );

			$this->setUserPerm( "move" );
			$this->runGroupPermissions( 'move', [] );

			$this->setUser( 'anon' );
			$this->setUserPerm( 'move' );
			$res = $this->permissionManager
				->getPermissionErrors( 'move-target', $this->user, $this->title );
			$this->assertEquals( [], $res );

			$this->setUserPerm( '' );
			$res = $this->permissionManager
				->getPermissionErrors( 'move-target', $this->user, $this->title );
			$this->assertEquals( [ [ 'movenotallowed' ] ], $res );
		}

		$this->setTitle( NS_USER );
		$this->setUser( $this->userName );
		$this->setUserPerm( [ "move", "move-rootuserpages" ] );
		$res = $this->permissionManager
			->getPermissionErrors( 'move-target', $this->user, $this->title );
		$this->assertEquals( [], $res );

		$this->setUserPerm( "move" );
		$res = $this->permissionManager
			->getPermissionErrors( 'move-target', $this->user, $this->title );
		$this->assertEquals( [ [ 'cant-move-to-user-page' ] ], $res );

		$this->setUser( 'anon' );
		$this->setUserPerm( [ "move", "move-rootuserpages" ] );
		$res = $this->permissionManager
			->getPermissionErrors( 'move-target', $this->user, $this->title );
		$this->assertEquals( [], $res );

		$this->setTitle( NS_USER, "User/subpage" );
		$this->setUserPerm( [ "move", "move-rootuserpages" ] );
		$res = $this->permissionManager
			->getPermissionErrors( 'move-target', $this->user, $this->title );
		$this->assertEquals( [], $res );

		$this->setUserPerm( "move" );
		$res = $this->permissionManager
			->getPermissionErrors( 'move-target', $this->user, $this->title );
		$this->assertEquals( [], $res );

		$this->setUser( 'anon' );
		$check = [
			'edit' => [
				[ [ 'badaccess-groups', "*, [[$prefix:Users|Users]]", 2 ] ],
				[ [ 'badaccess-group0' ] ],
				[],
				true
			],
			'protect' => [
				[ [
					'badaccess-groups',
					"[[$prefix:Administrators|Administrators]]", 1 ],
					[ 'protect-cantedit'
					] ],
				[ [ 'badaccess-group0' ], [ 'protect-cantedit' ] ],
				[ [ 'protect-cantedit' ] ],
				false
			],
			'' => [ [], [], [], true ]
		];

		foreach ( [ "edit", "protect", "" ] as $action ) {
			$this->setUserPerm( null );
			$this->assertEquals( $check[$action][0],
				$this->permissionManager
					->getPermissionErrors( $action, $this->user, $this->title, true ) );
			$this->assertEquals( $check[$action][0],
				$this->permissionManager
					->getPermissionErrors( $action, $this->user, $this->title, 'full' ) );
			$this->assertEquals( $check[$action][0],
				$this->permissionManager
					->getPermissionErrors( $action, $this->user, $this->title, 'secure' ) );

			global $wgGroupPermissions;
			$old = $wgGroupPermissions;
			$wgGroupPermissions = [];

			$this->assertEquals( $check[$action][1],
				$this->permissionManager
					->getPermissionErrors( $action, $this->user, $this->title, true ) );
			$this->assertEquals( $check[$action][1],
				$this->permissionManager
					->getPermissionErrors( $action, $this->user, $this->title, 'full' ) );
			$this->assertEquals( $check[$action][1],
				$this->permissionManager
					->getPermissionErrors( $action, $this->user, $this->title, 'secure' ) );
			$wgGroupPermissions = $old;

			$this->setUserPerm( $action );
			$this->assertEquals( $check[$action][2],
				$this->permissionManager
					->getPermissionErrors( $action, $this->user, $this->title, true ) );
			$this->assertEquals( $check[$action][2],
				$this->permissionManager
					->getPermissionErrors( $action, $this->user, $this->title, 'full' ) );
			$this->assertEquals( $check[$action][2],
				$this->permissionManager
					->getPermissionErrors( $action, $this->user, $this->title, 'secure' ) );

			$this->setUserPerm( $action );
			$this->assertEquals( $check[$action][3],
				$this->permissionManager->userCan( $action, $this->user, $this->title, true ) );
			$this->assertEquals( $check[$action][3],
				$this->permissionManager->userCan( $action, $this->user, $this->title,
					PermissionManager::RIGOR_QUICK ) );
			# count( User::getGroupsWithPermissions( $action ) ) < 1
		}
	}

	protected function runGroupPermissions( $action, $result, $result2 = null ) {
		global $wgGroupPermissions;

		if ( $result2 === null ) {
			$result2 = $result;
		}

		$wgGroupPermissions['autoconfirmed']['move'] = false;
		$wgGroupPermissions['user']['move'] = false;
		$res = $this->permissionManager
			->getPermissionErrors( $action, $this->user, $this->title );
		$this->assertEquals( $result, $res );

		$wgGroupPermissions['autoconfirmed']['move'] = true;
		$wgGroupPermissions['user']['move'] = false;
		$res = $this->permissionManager
			->getPermissionErrors( $action, $this->user, $this->title );
		$this->assertEquals( $result2, $res );

		$wgGroupPermissions['autoconfirmed']['move'] = true;
		$wgGroupPermissions['user']['move'] = true;
		$res = $this->permissionManager
			->getPermissionErrors( $action, $this->user, $this->title );
		$this->assertEquals( $result2, $res );

		$wgGroupPermissions['autoconfirmed']['move'] = false;
		$wgGroupPermissions['user']['move'] = true;
		$res = $this->permissionManager
			->getPermissionErrors( $action, $this->user, $this->title );
		$this->assertEquals( $result2, $res );
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 * @covers MediaWiki\Permissions\PermissionManager::checkSpecialsAndNSPermissions
	 */
	public function testSpecialsAndNSPermissions() {
		global $wgNamespaceProtection;
		$this->setUser( $this->userName );

		$this->setTitle( NS_SPECIAL );

		$this->assertEquals( [ [ 'badaccess-group0' ], [ 'ns-specialprotected' ] ],
			$this->permissionManager
				->getPermissionErrors( 'bogus', $this->user, $this->title ) );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( 'bogus' );
		$this->assertEquals( [],
			$this->permissionManager
				->getPermissionErrors( 'bogus', $this->user, $this->title ) );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( '' );
		$this->assertEquals( [ [ 'badaccess-group0' ] ],
			$this->permissionManager
				->getPermissionErrors( 'bogus', $this->user, $this->title ) );

		$wgNamespaceProtection[NS_USER] = [ 'bogus' ];

		$this->setTitle( NS_USER );
		$this->setUserPerm( '' );
		$this->assertEquals( [ [ 'badaccess-group0' ],
			[ 'namespaceprotected', 'User', 'bogus' ] ],
			$this->permissionManager
				->getPermissionErrors( 'bogus', $this->user, $this->title ) );

		$this->setTitle( NS_MEDIAWIKI );
		$this->setUserPerm( 'bogus' );
		$this->assertEquals( [ [ 'protectedinterface', 'bogus' ] ],
			$this->permissionManager
				->getPermissionErrors( 'bogus', $this->user, $this->title ) );

		$this->setTitle( NS_MEDIAWIKI );
		$this->setUserPerm( 'bogus' );
		$this->assertEquals( [ [ 'protectedinterface', 'bogus' ] ],
			$this->permissionManager
				->getPermissionErrors( 'bogus', $this->user, $this->title ) );

		$wgNamespaceProtection = null;

		$this->setUserPerm( 'bogus' );
		$this->assertEquals( [],
			$this->permissionManager
				->getPermissionErrors( 'bogus', $this->user, $this->title ) );
		$this->assertEquals( true,
			$this->permissionManager->userCan( 'bogus', $this->user, $this->title ) );

		$this->setUserPerm( '' );
		$this->assertEquals( [ [ 'badaccess-group0' ] ],
			$this->permissionManager
				->getPermissionErrors( 'bogus', $this->user, $this->title ) );
		$this->assertEquals( false,
			$this->permissionManager->userCan( 'bogus', $this->user, $this->title ) );
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 * @covers \MediaWiki\Permissions\PermissionManager::checkUserConfigPermissions
	 */
	public function testJsConfigEditPermissions() {
		$this->setUser( $this->userName );

		$this->setTitle( NS_USER, $this->userName . '/test.js' );
		$this->runConfigEditPermissions(
			[ [ 'badaccess-group0' ], [ 'mycustomjsprotected', 'bogus' ] ],

			[ [ 'badaccess-group0' ], [ 'mycustomjsprotected', 'bogus' ] ],
			[ [ 'badaccess-group0' ], [ 'mycustomjsprotected', 'bogus' ] ],
			[ [ 'badaccess-group0' ] ],

			[ [ 'badaccess-group0' ], [ 'mycustomjsprotected', 'bogus' ] ],
			[ [ 'badaccess-group0' ], [ 'mycustomjsprotected', 'bogus' ] ],
			[ [ 'badaccess-group0' ] ],
			[ [ 'badaccess-groups' ] ]
		);
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 * @covers \MediaWiki\Permissions\PermissionManager::checkUserConfigPermissions
	 */
	public function testJsonConfigEditPermissions() {
		$prefix = MediaWikiServices::getInstance()->getContentLanguage()->
		getFormattedNsText( NS_PROJECT );
		$this->setUser( $this->userName );

		$this->setTitle( NS_USER, $this->userName . '/test.json' );
		$this->runConfigEditPermissions(
			[ [ 'badaccess-group0' ], [ 'mycustomjsonprotected', 'bogus' ] ],

			[ [ 'badaccess-group0' ], [ 'mycustomjsonprotected', 'bogus' ] ],
			[ [ 'badaccess-group0' ] ],
			[ [ 'badaccess-group0' ], [ 'mycustomjsonprotected', 'bogus' ] ],

			[ [ 'badaccess-group0' ], [ 'mycustomjsonprotected', 'bogus' ] ],
			[ [ 'badaccess-group0' ] ],
			[ [ 'badaccess-group0' ], [ 'mycustomjsonprotected', 'bogus' ] ],
			[ [ 'badaccess-groups' ] ]
		);
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 * @covers \MediaWiki\Permissions\PermissionManager::checkUserConfigPermissions
	 */
	public function testCssConfigEditPermissions() {
		$this->setUser( $this->userName );

		$this->setTitle( NS_USER, $this->userName . '/test.css' );
		$this->runConfigEditPermissions(
			[ [ 'badaccess-group0' ], [ 'mycustomcssprotected', 'bogus' ] ],

			[ [ 'badaccess-group0' ] ],
			[ [ 'badaccess-group0' ], [ 'mycustomcssprotected', 'bogus' ] ],
			[ [ 'badaccess-group0' ], [ 'mycustomcssprotected', 'bogus' ] ],

			[ [ 'badaccess-group0' ] ],
			[ [ 'badaccess-group0' ], [ 'mycustomcssprotected', 'bogus' ] ],
			[ [ 'badaccess-group0' ], [ 'mycustomcssprotected', 'bogus' ] ],
			[ [ 'badaccess-groups' ] ]
		);
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 * @covers \MediaWiki\Permissions\PermissionManager::checkUserConfigPermissions
	 */
	public function testOtherJsConfigEditPermissions() {
		$this->setUser( $this->userName );

		$this->setTitle( NS_USER, $this->altUserName . '/test.js' );
		$this->runConfigEditPermissions(
			[ [ 'badaccess-group0' ], [ 'customjsprotected', 'bogus' ] ],

			[ [ 'badaccess-group0' ], [ 'customjsprotected', 'bogus' ] ],
			[ [ 'badaccess-group0' ], [ 'customjsprotected', 'bogus' ] ],
			[ [ 'badaccess-group0' ], [ 'customjsprotected', 'bogus' ] ],

			[ [ 'badaccess-group0' ], [ 'customjsprotected', 'bogus' ] ],
			[ [ 'badaccess-group0' ], [ 'customjsprotected', 'bogus' ] ],
			[ [ 'badaccess-group0' ] ],
			[ [ 'badaccess-groups' ] ]
		);
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 * @covers \MediaWiki\Permissions\PermissionManager::checkUserConfigPermissions
	 */
	public function testOtherJsonConfigEditPermissions() {
		$this->setUser( $this->userName );

		$this->setTitle( NS_USER, $this->altUserName . '/test.json' );
		$this->runConfigEditPermissions(
			[ [ 'badaccess-group0' ], [ 'customjsonprotected', 'bogus' ] ],

			[ [ 'badaccess-group0' ], [ 'customjsonprotected', 'bogus' ] ],
			[ [ 'badaccess-group0' ], [ 'customjsonprotected', 'bogus' ] ],
			[ [ 'badaccess-group0' ], [ 'customjsonprotected', 'bogus' ] ],

			[ [ 'badaccess-group0' ], [ 'customjsonprotected', 'bogus' ] ],
			[ [ 'badaccess-group0' ] ],
			[ [ 'badaccess-group0' ], [ 'customjsonprotected', 'bogus' ] ],
			[ [ 'badaccess-groups' ] ]
		);
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 * @covers \MediaWiki\Permissions\PermissionManager::checkUserConfigPermissions
	 */
	public function testOtherCssConfigEditPermissions() {
		$this->setUser( $this->userName );

		$this->setTitle( NS_USER, $this->altUserName . '/test.css' );
		$this->runConfigEditPermissions(
			[ [ 'badaccess-group0' ], [ 'customcssprotected', 'bogus' ] ],

			[ [ 'badaccess-group0' ], [ 'customcssprotected', 'bogus' ] ],
			[ [ 'badaccess-group0' ], [ 'customcssprotected', 'bogus' ] ],
			[ [ 'badaccess-group0' ], [ 'customcssprotected', 'bogus' ] ],

			[ [ 'badaccess-group0' ] ],
			[ [ 'badaccess-group0' ], [ 'customcssprotected', 'bogus' ] ],
			[ [ 'badaccess-group0' ], [ 'customcssprotected', 'bogus' ] ],
			[ [ 'badaccess-groups' ] ]
		);
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 * @covers \MediaWiki\Permissions\PermissionManager::checkUserConfigPermissions
	 */
	public function testOtherNonConfigEditPermissions() {
		$this->setUser( $this->userName );

		$this->setTitle( NS_USER, $this->altUserName . '/tempo' );
		$this->runConfigEditPermissions(
			[ [ 'badaccess-group0' ] ],

			[ [ 'badaccess-group0' ] ],
			[ [ 'badaccess-group0' ] ],
			[ [ 'badaccess-group0' ] ],

			[ [ 'badaccess-group0' ] ],
			[ [ 'badaccess-group0' ] ],
			[ [ 'badaccess-group0' ] ],
			[ [ 'badaccess-groups' ] ]
		);
	}

	/**
	 * @todo This should use data providers like the other methods here.
	 * @covers \MediaWiki\Permissions\PermissionManager::checkUserConfigPermissions
	 */
	public function testPatrolActionConfigEditPermissions() {
		$this->setUser( 'anon' );
		$this->setTitle( NS_USER, 'ToPatrolOrNotToPatrol' );
		$this->runConfigEditPermissions(
			[ [ 'badaccess-group0' ] ],

			[ [ 'badaccess-group0' ] ],
			[ [ 'badaccess-group0' ] ],
			[ [ 'badaccess-group0' ] ],

			[ [ 'badaccess-group0' ] ],
			[ [ 'badaccess-group0' ] ],
			[ [ 'badaccess-group0' ] ],
			[ [ 'badaccess-groups' ] ]
		);
	}

	protected function runConfigEditPermissions(
		$resultNone,
		$resultMyCss,
		$resultMyJson,
		$resultMyJs,
		$resultUserCss,
		$resultUserJson,
		$resultUserJs,
		$resultPatrol
	) {
		$this->setUserPerm( '' );
		$result = $this->permissionManager
			->getPermissionErrors( 'bogus', $this->user, $this->title );
		$this->assertEquals( $resultNone, $result );

		$this->setUserPerm( 'editmyusercss' );
		$result = $this->permissionManager
			->getPermissionErrors( 'bogus', $this->user, $this->title );
		$this->assertEquals( $resultMyCss, $result );

		$this->setUserPerm( 'editmyuserjson' );
		$result = $this->permissionManager
			->getPermissionErrors( 'bogus', $this->user, $this->title );
		$this->assertEquals( $resultMyJson, $result );

		$this->setUserPerm( 'editmyuserjs' );
		$result = $this->permissionManager
			->getPermissionErrors( 'bogus', $this->user, $this->title );
		$this->assertEquals( $resultMyJs, $result );

		$this->setUserPerm( 'editusercss' );
		$result = $this->permissionManager
			->getPermissionErrors( 'bogus', $this->user, $this->title );
		$this->assertEquals( $resultUserCss, $result );

		$this->setUserPerm( 'edituserjson' );
		$result = $this->permissionManager
			->getPermissionErrors( 'bogus', $this->user, $this->title );
		$this->assertEquals( $resultUserJson, $result );

		$this->setUserPerm( 'edituserjs' );
		$result = $this->permissionManager
			->getPermissionErrors( 'bogus', $this->user, $this->title );
		$this->assertEquals( $resultUserJs, $result );

		$this->setUserPerm( '' );
		$result = $this->permissionManager
			->getPermissionErrors( 'patrol', $this->user, $this->title );
		$this->assertEquals( reset( $resultPatrol[0] ), reset( $result[0] ) );

		$this->setUserPerm( [ 'edituserjs', 'edituserjson', 'editusercss' ] );
		$result = $this->permissionManager
			->getPermissionErrors( 'bogus', $this->user, $this->title );
		$this->assertEquals( [ [ 'badaccess-group0' ] ], $result );
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 *
	 * This test is failing per T201776.
	 *
	 * @group Broken
	 * @covers \MediaWiki\Permissions\PermissionManager::checkPageRestrictions
	 */
	public function testPageRestrictions() {
		$prefix = MediaWikiServices::getInstance()->getContentLanguage()->
		getFormattedNsText( NS_PROJECT );

		$this->setTitle( NS_MAIN );
		$this->title->mRestrictionsLoaded = true;
		$this->setUserPerm( "edit" );
		$this->title->mRestrictions = [ "bogus" => [ 'bogus', "sysop", "protect", "" ] ];

		$this->assertEquals( [],
			$this->permissionManager->getPermissionErrors( 'edit',
				$this->user, $this->title ) );

		$this->assertEquals( true,
			$this->permissionManager->userCan( 'edit', $this->user, $this->title,
				PermissionManager::RIGOR_QUICK ) );

		$this->title->mRestrictions = [ "edit" => [ 'bogus', "sysop", "protect", "" ],
			"bogus" => [ 'bogus', "sysop", "protect", "" ] ];

		$this->assertEquals( [ [ 'badaccess-group0' ],
			[ 'protectedpagetext', 'bogus', 'bogus' ],
			[ 'protectedpagetext', 'editprotected', 'bogus' ],
			[ 'protectedpagetext', 'protect', 'bogus' ] ],
			$this->permissionManager->getPermissionErrors( 'bogus',
				$this->user, $this->title ) );
		$this->assertEquals( [ [ 'protectedpagetext', 'bogus', 'edit' ],
			[ 'protectedpagetext', 'editprotected', 'edit' ],
			[ 'protectedpagetext', 'protect', 'edit' ] ],
			$this->permissionManager->getPermissionErrors( 'edit',
				$this->user, $this->title ) );
		$this->setUserPerm( "" );
		$this->assertEquals( [ [ 'badaccess-group0' ],
			[ 'protectedpagetext', 'bogus', 'bogus' ],
			[ 'protectedpagetext', 'editprotected', 'bogus' ],
			[ 'protectedpagetext', 'protect', 'bogus' ] ],
			$this->permissionManager->getPermissionErrors( 'bogus',
				$this->user, $this->title ) );
		$this->assertEquals( [ [ 'badaccess-groups', "*, [[$prefix:Users|Users]]", 2 ],
			[ 'protectedpagetext', 'bogus', 'edit' ],
			[ 'protectedpagetext', 'editprotected', 'edit' ],
			[ 'protectedpagetext', 'protect', 'edit' ] ],
			$this->permissionManager->getPermissionErrors( 'edit',
				$this->user, $this->title ) );
		$this->setUserPerm( [ "edit", "editprotected" ] );
		$this->assertEquals( [ [ 'badaccess-group0' ],
			[ 'protectedpagetext', 'bogus', 'bogus' ],
			[ 'protectedpagetext', 'protect', 'bogus' ] ],
			$this->permissionManager->getPermissionErrors( 'bogus',
				$this->user, $this->title ) );
		$this->assertEquals( [
			[ 'protectedpagetext', 'bogus', 'edit' ],
			[ 'protectedpagetext', 'protect', 'edit' ] ],
			$this->permissionManager->getPermissionErrors( 'edit',
				$this->user, $this->title ) );

		$this->title->mCascadeRestriction = true;
		$this->setUserPerm( "edit" );

		$this->assertEquals( false,
			$this->permissionManager->userCan( 'bogus', $this->user, $this->title,
				PermissionManager::RIGOR_QUICK ) );

		$this->assertEquals( false,
			$this->permissionManager->userCan( 'edit', $this->user, $this->title,
				PermissionManager::RIGOR_QUICK ) );

		$this->assertEquals( [ [ 'badaccess-group0' ],
			[ 'protectedpagetext', 'bogus', 'bogus' ],
			[ 'protectedpagetext', 'editprotected', 'bogus' ],
			[ 'protectedpagetext', 'protect', 'bogus' ] ],
			$this->permissionManager->getPermissionErrors( 'bogus',
				$this->user, $this->title ) );
		$this->assertEquals( [ [ 'protectedpagetext', 'bogus', 'edit' ],
			[ 'protectedpagetext', 'editprotected', 'edit' ],
			[ 'protectedpagetext', 'protect', 'edit' ] ],
			$this->permissionManager->getPermissionErrors( 'edit',
				$this->user, $this->title ) );

		$this->setUserPerm( [ "edit", "editprotected" ] );
		$this->assertEquals( false,
			$this->permissionManager->userCan( 'bogus', $this->user, $this->title,
				PermissionManager::RIGOR_QUICK ) );

		$this->assertEquals( false,
			$this->permissionManager->userCan( 'edit', $this->user, $this->title,
				PermissionManager::RIGOR_QUICK ) );

		$this->assertEquals( [ [ 'badaccess-group0' ],
			[ 'protectedpagetext', 'bogus', 'bogus' ],
			[ 'protectedpagetext', 'protect', 'bogus' ],
			[ 'protectedpagetext', 'protect', 'bogus' ] ],
			$this->permissionManager->getPermissionErrors( 'bogus',
				$this->user, $this->title ) );
		$this->assertEquals( [ [ 'protectedpagetext', 'bogus', 'edit' ],
			[ 'protectedpagetext', 'protect', 'edit' ],
			[ 'protectedpagetext', 'protect', 'edit' ] ],
			$this->permissionManager->getPermissionErrors( 'edit',
				$this->user, $this->title ) );
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::checkCascadingSourcesRestrictions
	 */
	public function testCascadingSourcesRestrictions() {
		$this->setTitle( NS_MAIN, "test page" );
		$this->setUserPerm( [ "edit", "bogus" ] );

		$this->title->mCascadeSources = [
			Title::makeTitle( NS_MAIN, "Bogus" ),
			Title::makeTitle( NS_MAIN, "UnBogus" )
		];
		$this->title->mCascadingRestrictions = [
			"bogus" => [ 'bogus', "sysop", "protect", "" ]
		];

		$this->assertEquals( false,
			$this->permissionManager->userCan( 'bogus', $this->user, $this->title ) );
		$this->assertEquals( [
			[ "cascadeprotected", 2, "* [[:Bogus]]\n* [[:UnBogus]]\n", 'bogus' ],
			[ "cascadeprotected", 2, "* [[:Bogus]]\n* [[:UnBogus]]\n", 'bogus' ],
			[ "cascadeprotected", 2, "* [[:Bogus]]\n* [[:UnBogus]]\n", 'bogus' ] ],
			$this->permissionManager->getPermissionErrors( 'bogus', $this->user, $this->title ) );

		$this->assertEquals( true,
			$this->permissionManager->userCan( 'edit', $this->user, $this->title ) );
		$this->assertEquals( [],
			$this->permissionManager->getPermissionErrors( 'edit', $this->user, $this->title ) );
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 * @covers \MediaWiki\Permissions\PermissionManager::checkActionPermissions
	 */
	public function testActionPermissions() {
		$this->setUserPerm( [ "createpage" ] );
		$this->setTitle( NS_MAIN, "test page" );
		$this->title->mTitleProtection['permission'] = '';
		$this->title->mTitleProtection['user'] = $this->user->getId();
		$this->title->mTitleProtection['expiry'] = 'infinity';
		$this->title->mTitleProtection['reason'] = 'test';
		$this->title->mCascadeRestriction = false;

		$this->assertEquals( [ [ 'titleprotected', 'Useruser', 'test' ] ],
			$this->permissionManager
				->getPermissionErrors( 'create', $this->user, $this->title ) );
		$this->assertEquals( false,
			$this->permissionManager->userCan( 'create', $this->user, $this->title ) );

		$this->title->mTitleProtection['permission'] = 'editprotected';
		$this->setUserPerm( [ 'createpage', 'protect' ] );
		$this->assertEquals( [ [ 'titleprotected', 'Useruser', 'test' ] ],
			$this->permissionManager
				->getPermissionErrors( 'create', $this->user, $this->title ) );
		$this->assertEquals( false,
			$this->permissionManager->userCan( 'create', $this->user, $this->title ) );

		$this->setUserPerm( [ 'createpage', 'editprotected' ] );
		$this->assertEquals( [],
			$this->permissionManager
				->getPermissionErrors( 'create', $this->user, $this->title ) );
		$this->assertEquals( true,
			$this->permissionManager->userCan( 'create', $this->user, $this->title ) );

		$this->setUserPerm( [ 'createpage' ] );
		$this->assertEquals( [ [ 'titleprotected', 'Useruser', 'test' ] ],
			$this->permissionManager
				->getPermissionErrors( 'create', $this->user, $this->title ) );
		$this->assertEquals( false,
			$this->permissionManager->userCan( 'create', $this->user, $this->title ) );

		$this->setTitle( NS_MEDIA, "test page" );
		$this->setUserPerm( [ "move" ] );
		$this->assertEquals( false,
			$this->permissionManager->userCan( 'move', $this->user, $this->title ) );
		$this->assertEquals( [ [ 'immobile-source-namespace', 'Media' ] ],
			$this->permissionManager
				->getPermissionErrors( 'move', $this->user, $this->title ) );

		$this->setTitle( NS_HELP, "test page" );
		$this->assertEquals( [],
			$this->permissionManager
				->getPermissionErrors( 'move', $this->user, $this->title ) );
		$this->assertEquals( true,
			$this->permissionManager->userCan( 'move', $this->user, $this->title ) );

		$this->title->mInterwiki = "no";
		$this->assertEquals( [ [ 'immobile-source-page' ] ],
			$this->permissionManager
				->getPermissionErrors( 'move', $this->user, $this->title ) );
		$this->assertEquals( false,
			$this->permissionManager->userCan( 'move', $this->user, $this->title ) );

		$this->setTitle( NS_MEDIA, "test page" );
		$this->assertEquals( false,
			$this->permissionManager->userCan( 'move-target', $this->user, $this->title ) );
		$this->assertEquals( [ [ 'immobile-target-namespace', 'Media' ] ],
			$this->permissionManager
				->getPermissionErrors( 'move-target', $this->user, $this->title ) );

		$this->setTitle( NS_HELP, "test page" );
		$this->assertEquals( [],
			$this->permissionManager
				->getPermissionErrors( 'move-target', $this->user, $this->title ) );
		$this->assertEquals( true,
			$this->permissionManager->userCan( 'move-target', $this->user, $this->title ) );

		$this->title->mInterwiki = "no";
		$this->assertEquals( [ [ 'immobile-target-page' ] ],
			$this->permissionManager
				->getPermissionErrors( 'move-target', $this->user, $this->title ) );
		$this->assertEquals( false,
			$this->permissionManager->userCan( 'move-target', $this->user, $this->title ) );
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::checkUserBlock
	 */
	public function testUserBlock() {
		$this->setMwGlobals( [
			'wgEmailConfirmToEdit' => true,
			'wgEmailAuthentication' => true,
		] );

		$this->overrideMwServices();
		$this->permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

		$this->setUserPerm( [
			'createpage',
			'edit',
			'move',
			'rollback',
			'patrol',
			'upload',
			'purge'
		] );
		$this->setTitle( NS_HELP, "test page" );

		# $wgEmailConfirmToEdit only applies to 'edit' action
		$this->assertEquals( [],
			$this->permissionManager->getPermissionErrors( 'move-target',
				$this->user, $this->title ) );
		$this->assertContains( [ 'confirmedittext' ],
			$this->permissionManager
				->getPermissionErrors( 'edit', $this->user, $this->title ) );

		$this->setMwGlobals( 'wgEmailConfirmToEdit', false );
		$this->overrideMwServices();
		$this->permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

		$this->assertNotContains( [ 'confirmedittext' ],
			$this->permissionManager
				->getPermissionErrors( 'edit', $this->user, $this->title ) );

		# $wgEmailConfirmToEdit && !$user->isEmailConfirmed() && $action != 'createaccount'
		$this->assertEquals( [],
			$this->permissionManager->getPermissionErrors( 'move-target',
				$this->user, $this->title ) );

		global $wgLang;
		$prev = time();
		$now = time() + 120;
		$this->user->mBlockedby = $this->user->getId();
		$this->user->mBlock = new Block( [
			'address' => '127.0.8.1',
			'by' => $this->user->getId(),
			'reason' => 'no reason given',
			'timestamp' => $prev + 3600,
			'auto' => true,
			'expiry' => 0
		] );
		$this->user->mBlock->mTimestamp = 0;
		$this->assertEquals( [ [ 'autoblockedtext',
			'[[User:Useruser|Useruser]]', 'no reason given', '127.0.0.1',
			'Useruser', null, 'infinite', '127.0.8.1',
			$wgLang->timeanddate( wfTimestamp( TS_MW, $prev ), true ) ] ],
			$this->permissionManager->getPermissionErrors( 'move-target',
				$this->user, $this->title ) );

		$this->assertEquals( false, $this->permissionManager
			->userCan( 'move-target', $this->user, $this->title ) );
		// quickUserCan should ignore user blocks
		$this->assertEquals( true, $this->permissionManager
			->userCan( 'move-target', $this->user, $this->title,
				PermissionManager::RIGOR_QUICK ) );

		global $wgLocalTZoffset;
		$wgLocalTZoffset = -60;
		$this->user->mBlockedby = $this->user->getName();
		$this->user->mBlock = new Block( [
			'address' => '127.0.8.1',
			'by' => $this->user->getId(),
			'reason' => 'no reason given',
			'timestamp' => $now,
			'auto' => false,
			'expiry' => 10,
		] );
		$this->assertEquals( [ [ 'blockedtext',
			'[[User:Useruser|Useruser]]', 'no reason given', '127.0.0.1',
			'Useruser', null, '23:00, 31 December 1969', '127.0.8.1',
			$wgLang->timeanddate( wfTimestamp( TS_MW, $now ), true ) ] ],
			$this->permissionManager
				->getPermissionErrors( 'move-target', $this->user, $this->title ) );
		# $action != 'read' && $action != 'createaccount' && $user->isBlockedFrom( $this )
		#   $user->blockedFor() == ''
		#   $user->mBlock->mExpiry == 'infinity'

		$this->user->mBlockedby = $this->user->getName();
		$this->user->mBlock = new Block( [
			'address' => '127.0.8.1',
			'by' => $this->user->getId(),
			'reason' => 'no reason given',
			'timestamp' => $now,
			'auto' => false,
			'expiry' => 10,
			'systemBlock' => 'test',
		] );

		$errors = [ [ 'systemblockedtext',
			'[[User:Useruser|Useruser]]', 'no reason given', '127.0.0.1',
			'Useruser', 'test', '23:00, 31 December 1969', '127.0.8.1',
			$wgLang->timeanddate( wfTimestamp( TS_MW, $now ), true ) ] ];

		$this->assertEquals( $errors,
			$this->permissionManager
				->getPermissionErrors( 'edit', $this->user, $this->title ) );
		$this->assertEquals( $errors,
			$this->permissionManager
				->getPermissionErrors( 'move-target', $this->user, $this->title ) );
		$this->assertEquals( $errors,
			$this->permissionManager
				->getPermissionErrors( 'rollback', $this->user, $this->title ) );
		$this->assertEquals( $errors,
			$this->permissionManager
				->getPermissionErrors( 'patrol', $this->user, $this->title ) );
		$this->assertEquals( $errors,
			$this->permissionManager
				->getPermissionErrors( 'upload', $this->user, $this->title ) );
		$this->assertEquals( [],
			$this->permissionManager
				->getPermissionErrors( 'purge', $this->user, $this->title ) );

		// partial block message test
		$this->user->mBlockedby = $this->user->getName();
		$this->user->mBlock = new Block( [
			'address' => '127.0.8.1',
			'by' => $this->user->getId(),
			'reason' => 'no reason given',
			'timestamp' => $now,
			'sitewide' => false,
			'expiry' => 10,
		] );

		$this->assertEquals( [],
			$this->permissionManager
				->getPermissionErrors( 'edit', $this->user, $this->title ) );
		$this->assertEquals( [],
			$this->permissionManager
				->getPermissionErrors( 'move-target', $this->user, $this->title ) );
		$this->assertEquals( [],
			$this->permissionManager
				->getPermissionErrors( 'rollback', $this->user, $this->title ) );
		$this->assertEquals( [],
			$this->permissionManager
				->getPermissionErrors( 'patrol', $this->user, $this->title ) );
		$this->assertEquals( [],
			$this->permissionManager
				->getPermissionErrors( 'upload', $this->user, $this->title ) );
		$this->assertEquals( [],
			$this->permissionManager
				->getPermissionErrors( 'purge', $this->user, $this->title ) );

		$this->user->mBlock->setRestrictions( [
			( new PageRestriction( 0, $this->title->getArticleID() ) )->setTitle( $this->title ),
		] );

		$errors = [ [ 'blockedtext-partial',
			'[[User:Useruser|Useruser]]', 'no reason given', '127.0.0.1',
			'Useruser', null, '23:00, 31 December 1969', '127.0.8.1',
			$wgLang->timeanddate( wfTimestamp( TS_MW, $now ), true ) ] ];

		$this->assertEquals( $errors,
			$this->permissionManager
				->getPermissionErrors( 'edit', $this->user, $this->title ) );
		$this->assertEquals( $errors,
			$this->permissionManager
				->getPermissionErrors( 'move-target', $this->user, $this->title ) );
		$this->assertEquals( $errors,
			$this->permissionManager
				->getPermissionErrors( 'rollback', $this->user, $this->title ) );
		$this->assertEquals( $errors,
			$this->permissionManager
				->getPermissionErrors( 'patrol', $this->user, $this->title ) );
		$this->assertEquals( [],
			$this->permissionManager
				->getPermissionErrors( 'upload', $this->user, $this->title ) );
		$this->assertEquals( [],
			$this->permissionManager
				->getPermissionErrors( 'purge', $this->user, $this->title ) );

		// Test no block.
		$this->user->mBlockedby = null;
		$this->user->mBlock = null;

		$this->assertEquals( [],
			$this->permissionManager
				->getPermissionErrors( 'edit', $this->user, $this->title ) );
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::checkUserBlock
	 *
	 * Tests to determine that the passed in permission does not get mixed up with
	 * an action of the same name.
	 */
	public function testUserBlockAction() {
		global $wgLang;

		$tester = $this->getMockBuilder( Action::class )
					   ->disableOriginalConstructor()
					   ->getMock();
		$tester->method( 'getName' )
			   ->willReturn( 'tester' );
		$tester->method( 'getRestriction' )
			   ->willReturn( 'test' );
		$tester->method( 'requiresUnblock' )
			   ->willReturn( false );

		$this->setMwGlobals( [
			'wgActions' => [
				'tester' => $tester,
			],
			'wgGroupPermissions' => [
				'*' => [
					'tester' => true,
				],
			],
		] );

		$now = time();
		$this->user->mBlockedby = $this->user->getName();
		$this->user->mBlock = new Block( [
			'address' => '127.0.8.1',
			'by' => $this->user->getId(),
			'reason' => 'no reason given',
			'timestamp' => $now,
			'auto' => false,
			'expiry' => 'infinity',
		] );

		$errors = [ [ 'blockedtext',
			'[[User:Useruser|Useruser]]', 'no reason given', '127.0.0.1',
			'Useruser', null, 'infinite', '127.0.8.1',
			$wgLang->timeanddate( wfTimestamp( TS_MW, $now ), true ) ] ];

		$this->assertEquals( $errors,
			$this->permissionManager
				->getPermissionErrors( 'tester', $this->user, $this->title ) );
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::isBlockedFrom
	 */
	public function testBlockInstanceCache() {
		// First, check the user isn't blocked
		$user = $this->getMutableTestUser()->getUser();
		$ut = Title::makeTitle( NS_USER_TALK, $user->getName() );
		$this->assertNull( $user->getBlock( false ), 'sanity check' );
		//$this->assertSame( '', $user->blockedBy(), 'sanity check' );
		//$this->assertSame( '', $user->blockedFor(), 'sanity check' );
		//$this->assertFalse( (bool)$user->isHidden(), 'sanity check' );
		$this->assertFalse( $this->permissionManager
			->isBlockedFrom( $user, $ut ), 'sanity check' );

		// Block the user
		$blocker = $this->getTestSysop()->getUser();
		$block = new Block( [
			'hideName' => true,
			'allowUsertalk' => false,
			'reason' => 'Because',
		] );
		$block->setTarget( $user );
		$block->setBlocker( $blocker );
		$res = $block->insert();
		$this->assertTrue( (bool)$res['id'], 'sanity check: Failed to insert block' );

		// Clear cache and confirm it loaded the block properly
		$user->clearInstanceCache();
		$this->assertInstanceOf( Block::class, $user->getBlock( false ) );
		//$this->assertSame( $blocker->getName(), $user->blockedBy() );
		//$this->assertSame( 'Because', $user->blockedFor() );
		//$this->assertTrue( (bool)$user->isHidden() );
		$this->assertTrue( $this->permissionManager->isBlockedFrom( $user, $ut ) );

		// Unblock
		$block->delete();

		// Clear cache and confirm it loaded the not-blocked properly
		$user->clearInstanceCache();
		$this->assertNull( $user->getBlock( false ) );
		//$this->assertSame( '', $user->blockedBy() );
		//$this->assertSame( '', $user->blockedFor() );
		//$this->assertFalse( (bool)$user->isHidden() );
		$this->assertFalse( $this->permissionManager->isBlockedFrom( $user, $ut ) );
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::isBlockedFrom
	 * @dataProvider provideIsBlockedFrom
	 * @param string|null $title Title to test.
	 * @param bool $expect Expected result from User::isBlockedFrom()
	 * @param array $options Additional test options:
	 *  - 'blockAllowsUTEdit': (bool, default true) Value for $wgBlockAllowsUTEdit
	 *  - 'allowUsertalk': (bool, default false) Passed to Block::__construct()
	 *  - 'pageRestrictions': (array|null) If non-empty, page restriction titles for the block.
	 */
	public function testIsBlockedFrom( $title, $expect, array $options = [] ) {
		$this->setMwGlobals( [
			'wgBlockAllowsUTEdit' => $options['blockAllowsUTEdit'] ?? true,
		] );

		$user = $this->getTestUser()->getUser();

		if ( $title === self::USER_TALK_PAGE ) {
			$title = $user->getTalkPage();
		} else {
			$title = Title::newFromText( $title );
		}

		$restrictions = [];
		foreach ( $options['pageRestrictions'] ?? [] as $pagestr ) {
			$page = $this->getExistingTestPage(
				$pagestr === self::USER_TALK_PAGE ? $user->getTalkPage() : $pagestr
			);
			$restrictions[] = new PageRestriction( 0, $page->getId() );
		}
		foreach ( $options['namespaceRestrictions'] ?? [] as $ns ) {
			$restrictions[] = new NamespaceRestriction( 0, $ns );
		}

		$block = new Block( [
			'expiry' => wfTimestamp( TS_MW, wfTimestamp() + ( 40 * 60 * 60 ) ),
			'allowUsertalk' => $options['allowUsertalk'] ?? false,
			'sitewide' => !$restrictions,
		] );
		$block->setTarget( $user );
		$block->setBlocker( $this->getTestSysop()->getUser() );
		if ( $restrictions ) {
			$block->setRestrictions( $restrictions );
		}
		$block->insert();

		try {
			$this->assertSame( $expect, $this->permissionManager->isBlockedFrom( $user, $title ) );
		} finally {
			$block->delete();
		}
	}

	public static function provideIsBlockedFrom() {
		return [
			'Sitewide block, basic operation' => [ 'Test page', true ],
			'Sitewide block, not allowing user talk' => [
				self::USER_TALK_PAGE, true, [
					'allowUsertalk' => false,
				]
			],
			'Sitewide block, allowing user talk' => [
				self::USER_TALK_PAGE, false, [
					'allowUsertalk' => true,
				]
			],
			'Sitewide block, allowing user talk but $wgBlockAllowsUTEdit is false' => [
				self::USER_TALK_PAGE, true, [
					'allowUsertalk' => true,
					'blockAllowsUTEdit' => false,
				]
			],
			'Partial block, blocking the page' => [
				'Test page', true, [
					'pageRestrictions' => [ 'Test page' ],
				]
			],
			'Partial block, not blocking the page' => [
				'Test page 2', false, [
					'pageRestrictions' => [ 'Test page' ],
				]
			],
			'Partial block, not allowing user talk but user talk page is not blocked' => [
				self::USER_TALK_PAGE, false, [
					'allowUsertalk' => false,
					'pageRestrictions' => [ 'Test page' ],
				]
			],
			'Partial block, allowing user talk but user talk page is blocked' => [
				self::USER_TALK_PAGE, true, [
					'allowUsertalk' => true,
					'pageRestrictions' => [ self::USER_TALK_PAGE ],
				]
			],
			'Partial block, user talk page is not blocked but $wgBlockAllowsUTEdit is false' => [
				self::USER_TALK_PAGE, false, [
					'allowUsertalk' => false,
					'pageRestrictions' => [ 'Test page' ],
					'blockAllowsUTEdit' => false,
				]
			],
			'Partial block, user talk page is blocked and $wgBlockAllowsUTEdit is false' => [
				self::USER_TALK_PAGE, true, [
					'allowUsertalk' => true,
					'pageRestrictions' => [ self::USER_TALK_PAGE ],
					'blockAllowsUTEdit' => false,
				]
			],
			'Partial user talk namespace block, not allowing user talk' => [
				self::USER_TALK_PAGE, true, [
					'allowUsertalk' => false,
					'namespaceRestrictions' => [ NS_USER_TALK ],
				]
			],
			'Partial user talk namespace block, allowing user talk' => [
				self::USER_TALK_PAGE, false, [
					'allowUsertalk' => true,
					'namespaceRestrictions' => [ NS_USER_TALK ],
				]
			],
			'Partial user talk namespace block, where $wgBlockAllowsUTEdit is false' => [
				self::USER_TALK_PAGE, true, [
					'allowUsertalk' => true,
					'namespaceRestrictions' => [ NS_USER_TALK ],
					'blockAllowsUTEdit' => false,
				]
			],
		];
	}

}
