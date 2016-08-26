<?php

/**
 * @group Database
 *
 * @covers Title::getUserPermissionsErrors
 * @covers Title::getUserPermissionsErrorsInternal
 */
class TitlePermissionTest extends MediaWikiLangTestCase {

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
	 * @covers Title::checkQuickPermissions
	 */
	public function testQuickPermissions() {
		global $wgContLang;
		$prefix = $wgContLang->getFormattedNsText( NS_PROJECT );

		$this->setUser( 'anon' );
		$this->setTitle( NS_TALK );
		$this->setUserPerm( "createtalk" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( [], $res );

		$this->setTitle( NS_TALK );
		$this->setUserPerm( "createpage" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( [ [ "nocreatetext" ] ], $res );

		$this->setTitle( NS_TALK );
		$this->setUserPerm( "" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( [ [ 'nocreatetext' ] ], $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "createpage" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( [], $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "createtalk" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( [ [ 'nocreatetext' ] ], $res );

		$this->setUser( $this->userName );
		$this->setTitle( NS_TALK );
		$this->setUserPerm( "createtalk" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( [], $res );

		$this->setTitle( NS_TALK );
		$this->setUserPerm( "createpage" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( [ [ 'nocreate-loggedin' ] ], $res );

		$this->setTitle( NS_TALK );
		$this->setUserPerm( "" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( [ [ 'nocreate-loggedin' ] ], $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "createpage" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( [], $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "createtalk" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( [ [ 'nocreate-loggedin' ] ], $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( [ [ 'nocreate-loggedin' ] ], $res );

		$this->setUser( 'anon' );
		$this->setTitle( NS_USER, $this->userName . '' );
		$this->setUserPerm( "" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( [ [ 'cant-move-user-page' ], [ 'movenologintext' ] ], $res );

		$this->setTitle( NS_USER, $this->userName . '/subpage' );
		$this->setUserPerm( "" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( [ [ 'movenologintext' ] ], $res );

		$this->setTitle( NS_USER, $this->userName . '' );
		$this->setUserPerm( "move-rootuserpages" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( [ [ 'movenologintext' ] ], $res );

		$this->setTitle( NS_USER, $this->userName . '/subpage' );
		$this->setUserPerm( "move-rootuserpages" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( [ [ 'movenologintext' ] ], $res );

		$this->setTitle( NS_USER, $this->userName . '' );
		$this->setUserPerm( "" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( [ [ 'cant-move-user-page' ], [ 'movenologintext' ] ], $res );

		$this->setTitle( NS_USER, $this->userName . '/subpage' );
		$this->setUserPerm( "" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( [ [ 'movenologintext' ] ], $res );

		$this->setTitle( NS_USER, $this->userName . '' );
		$this->setUserPerm( "move-rootuserpages" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( [ [ 'movenologintext' ] ], $res );

		$this->setTitle( NS_USER, $this->userName . '/subpage' );
		$this->setUserPerm( "move-rootuserpages" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( [ [ 'movenologintext' ] ], $res );

		$this->setUser( $this->userName );
		$this->setTitle( NS_FILE, "img.png" );
		$this->setUserPerm( "" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( [ [ 'movenotallowedfile' ], [ 'movenotallowed' ] ], $res );

		$this->setTitle( NS_FILE, "img.png" );
		$this->setUserPerm( "movefile" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( [ [ 'movenotallowed' ] ], $res );

		$this->setUser( 'anon' );
		$this->setTitle( NS_FILE, "img.png" );
		$this->setUserPerm( "" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( [ [ 'movenotallowedfile' ], [ 'movenologintext' ] ], $res );

		$this->setTitle( NS_FILE, "img.png" );
		$this->setUserPerm( "movefile" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
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
			$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
			$this->assertEquals( [], $res );

			$this->setUserPerm( '' );
			$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
			$this->assertEquals( [ [ 'movenotallowed' ] ], $res );
		}

		$this->setTitle( NS_USER );
		$this->setUser( $this->userName );
		$this->setUserPerm( [ "move", "move-rootuserpages" ] );
		$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
		$this->assertEquals( [], $res );

		$this->setUserPerm( "move" );
		$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
		$this->assertEquals( [ [ 'cant-move-to-user-page' ] ], $res );

		$this->setUser( 'anon' );
		$this->setUserPerm( [ "move", "move-rootuserpages" ] );
		$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
		$this->assertEquals( [], $res );

		$this->setTitle( NS_USER, "User/subpage" );
		$this->setUserPerm( [ "move", "move-rootuserpages" ] );
		$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
		$this->assertEquals( [], $res );

		$this->setUserPerm( "move" );
		$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
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
				$this->title->getUserPermissionsErrors( $action, $this->user, true ) );
			$this->assertEquals( $check[$action][0],
				$this->title->getUserPermissionsErrors( $action, $this->user, 'full' ) );
			$this->assertEquals( $check[$action][0],
				$this->title->getUserPermissionsErrors( $action, $this->user, 'secure' ) );

			global $wgGroupPermissions;
			$old = $wgGroupPermissions;
			$wgGroupPermissions = [];

			$this->assertEquals( $check[$action][1],
				$this->title->getUserPermissionsErrors( $action, $this->user, true ) );
			$this->assertEquals( $check[$action][1],
				$this->title->getUserPermissionsErrors( $action, $this->user, 'full' ) );
			$this->assertEquals( $check[$action][1],
				$this->title->getUserPermissionsErrors( $action, $this->user, 'secure' ) );
			$wgGroupPermissions = $old;

			$this->setUserPerm( $action );
			$this->assertEquals( $check[$action][2],
				$this->title->getUserPermissionsErrors( $action, $this->user, true ) );
			$this->assertEquals( $check[$action][2],
				$this->title->getUserPermissionsErrors( $action, $this->user, 'full' ) );
			$this->assertEquals( $check[$action][2],
				$this->title->getUserPermissionsErrors( $action, $this->user, 'secure' ) );

			$this->setUserPerm( $action );
			$this->assertEquals( $check[$action][3],
				$this->title->userCan( $action, $this->user, true ) );
			$this->assertEquals( $check[$action][3],
				$this->title->quickUserCan( $action, $this->user ) );
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
		$res = $this->title->getUserPermissionsErrors( $action, $this->user );
		$this->assertEquals( $result, $res );

		$wgGroupPermissions['autoconfirmed']['move'] = true;
		$wgGroupPermissions['user']['move'] = false;
		$res = $this->title->getUserPermissionsErrors( $action, $this->user );
		$this->assertEquals( $result2, $res );

		$wgGroupPermissions['autoconfirmed']['move'] = true;
		$wgGroupPermissions['user']['move'] = true;
		$res = $this->title->getUserPermissionsErrors( $action, $this->user );
		$this->assertEquals( $result2, $res );

		$wgGroupPermissions['autoconfirmed']['move'] = false;
		$wgGroupPermissions['user']['move'] = true;
		$res = $this->title->getUserPermissionsErrors( $action, $this->user );
		$this->assertEquals( $result2, $res );
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 * @covers Title::checkSpecialsAndNSPermissions
	 */
	public function testSpecialsAndNSPermissions() {
		global $wgNamespaceProtection;
		$this->setUser( $this->userName );

		$this->setTitle( NS_SPECIAL );

		$this->assertEquals( [ [ 'badaccess-group0' ], [ 'ns-specialprotected' ] ],
			$this->title->getUserPermissionsErrors( 'bogus', $this->user ) );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( 'bogus' );
		$this->assertEquals( [],
			$this->title->getUserPermissionsErrors( 'bogus', $this->user ) );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( '' );
		$this->assertEquals( [ [ 'badaccess-group0' ] ],
			$this->title->getUserPermissionsErrors( 'bogus', $this->user ) );

		$wgNamespaceProtection[NS_USER] = [ 'bogus' ];

		$this->setTitle( NS_USER );
		$this->setUserPerm( '' );
		$this->assertEquals( [ [ 'badaccess-group0' ],
				[ 'namespaceprotected', 'User', 'bogus' ] ],
			$this->title->getUserPermissionsErrors( 'bogus', $this->user ) );

		$this->setTitle( NS_MEDIAWIKI );
		$this->setUserPerm( 'bogus' );
		$this->assertEquals( [ [ 'protectedinterface', 'bogus' ] ],
			$this->title->getUserPermissionsErrors( 'bogus', $this->user ) );

		$this->setTitle( NS_MEDIAWIKI );
		$this->setUserPerm( 'bogus' );
		$this->assertEquals( [ [ 'protectedinterface', 'bogus' ] ],
			$this->title->getUserPermissionsErrors( 'bogus', $this->user ) );

		$wgNamespaceProtection = null;

		$this->setUserPerm( 'bogus' );
		$this->assertEquals( [],
			$this->title->getUserPermissionsErrors( 'bogus', $this->user ) );
		$this->assertEquals( true,
			$this->title->userCan( 'bogus', $this->user ) );

		$this->setUserPerm( '' );
		$this->assertEquals( [ [ 'badaccess-group0' ] ],
			$this->title->getUserPermissionsErrors( 'bogus', $this->user ) );
		$this->assertEquals( false,
			$this->title->userCan( 'bogus', $this->user ) );
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 * @covers Title::checkUserConfigPermissions
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
			[ [ 'badaccess-group0' ] ]
		);
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 * @covers Title::checkUserConfigPermissions
	 */
	public function testJsonConfigEditPermissions() {
		$this->setUser( $this->userName );

		$this->setTitle( NS_USER, $this->userName . '/test.json' );
		$this->runConfigEditPermissions(
			[ [ 'badaccess-group0' ], [ 'mycustomjsonprotected', 'bogus' ] ],

			[ [ 'badaccess-group0' ], [ 'mycustomjsonprotected', 'bogus' ] ],
			[ [ 'badaccess-group0' ] ],
			[ [ 'badaccess-group0' ], [ 'mycustomjsonprotected', 'bogus' ] ],

			[ [ 'badaccess-group0' ], [ 'mycustomjsonprotected', 'bogus' ] ],
			[ [ 'badaccess-group0' ] ],
			[ [ 'badaccess-group0' ], [ 'mycustomjsonprotected', 'bogus' ] ]
		);
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 * @covers Title::checkUserConfigPermissions
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
			[ [ 'badaccess-group0' ], [ 'mycustomcssprotected', 'bogus' ] ]
		);
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 * @covers Title::checkUserConfigPermissions
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
			[ [ 'badaccess-group0' ] ]
		);
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 * @covers Title::checkUserConfigPermissions
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
			[ [ 'badaccess-group0' ], [ 'customjsonprotected', 'bogus' ] ]
		);
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 * @covers Title::checkUserConfigPermissions
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
			[ [ 'badaccess-group0' ], [ 'customcssprotected', 'bogus' ] ]
		);
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 * @covers Title::checkUserConfigPermissions
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
			[ [ 'badaccess-group0' ] ]
		);
	}

	protected function runConfigEditPermissions(
		$resultNone,
		$resultMyCss,
		$resultMyJson,
		$resultMyJs,
		$resultUserCss,
		$resultUserJson,
		$resultUserJs
	) {
		$this->setUserPerm( '' );
		$result = $this->title->getUserPermissionsErrors( 'bogus', $this->user );
		$this->assertEquals( $resultNone, $result );

		$this->setUserPerm( 'editmyusercss' );
		$result = $this->title->getUserPermissionsErrors( 'bogus', $this->user );
		$this->assertEquals( $resultMyCss, $result );

		$this->setUserPerm( 'editmyuserjson' );
		$result = $this->title->getUserPermissionsErrors( 'bogus', $this->user );
		$this->assertEquals( $resultMyJson, $result );

		$this->setUserPerm( 'editmyuserjs' );
		$result = $this->title->getUserPermissionsErrors( 'bogus', $this->user );
		$this->assertEquals( $resultMyJs, $result );

		$this->setUserPerm( 'editusercss' );
		$result = $this->title->getUserPermissionsErrors( 'bogus', $this->user );
		$this->assertEquals( $resultUserCss, $result );

		$this->setUserPerm( 'edituserjson' );
		$result = $this->title->getUserPermissionsErrors( 'bogus', $this->user );
		$this->assertEquals( $resultUserJson, $result );

		$this->setUserPerm( 'edituserjs' );
		$result = $this->title->getUserPermissionsErrors( 'bogus', $this->user );
		$this->assertEquals( $resultUserJs, $result );

		$this->setUserPerm( [ 'edituserjs', 'edituserjson', 'editusercss' ] );
		$result = $this->title->getUserPermissionsErrors( 'bogus', $this->user );
		$this->assertEquals( [ [ 'badaccess-group0' ] ], $result );
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 * @covers Title::checkPageRestrictions
	 */
	public function testPageRestrictions() {
		global $wgContLang;

		$prefix = $wgContLang->getFormattedNsText( NS_PROJECT );

		$this->setTitle( NS_MAIN );
		$this->title->mRestrictionsLoaded = true;
		$this->setUserPerm( "edit" );
		$this->title->mRestrictions = [ "bogus" => [ 'bogus', "sysop", "protect", "" ] ];

		$this->assertEquals( [],
			$this->title->getUserPermissionsErrors( 'edit',
				$this->user ) );

		$this->assertEquals( true,
			$this->title->quickUserCan( 'edit', $this->user ) );
		$this->title->mRestrictions = [ "edit" => [ 'bogus', "sysop", "protect", "" ],
			"bogus" => [ 'bogus', "sysop", "protect", "" ] ];

		$this->assertEquals( [ [ 'badaccess-group0' ],
				[ 'protectedpagetext', 'bogus', 'bogus' ],
				[ 'protectedpagetext', 'editprotected', 'bogus' ],
				[ 'protectedpagetext', 'protect', 'bogus' ] ],
			$this->title->getUserPermissionsErrors( 'bogus',
				$this->user ) );
		$this->assertEquals( [ [ 'protectedpagetext', 'bogus', 'edit' ],
				[ 'protectedpagetext', 'editprotected', 'edit' ],
				[ 'protectedpagetext', 'protect', 'edit' ] ],
			$this->title->getUserPermissionsErrors( 'edit',
				$this->user ) );
		$this->setUserPerm( "" );
		$this->assertEquals( [ [ 'badaccess-group0' ],
				[ 'protectedpagetext', 'bogus', 'bogus' ],
				[ 'protectedpagetext', 'editprotected', 'bogus' ],
				[ 'protectedpagetext', 'protect', 'bogus' ] ],
			$this->title->getUserPermissionsErrors( 'bogus',
				$this->user ) );
		$this->assertEquals( [ [ 'badaccess-groups', "*, [[$prefix:Users|Users]]", 2 ],
				[ 'protectedpagetext', 'bogus', 'edit' ],
				[ 'protectedpagetext', 'editprotected', 'edit' ],
				[ 'protectedpagetext', 'protect', 'edit' ] ],
			$this->title->getUserPermissionsErrors( 'edit',
				$this->user ) );
		$this->setUserPerm( [ "edit", "editprotected" ] );
		$this->assertEquals( [ [ 'badaccess-group0' ],
				[ 'protectedpagetext', 'bogus', 'bogus' ],
				[ 'protectedpagetext', 'protect', 'bogus' ] ],
			$this->title->getUserPermissionsErrors( 'bogus',
				$this->user ) );
		$this->assertEquals( [
				[ 'protectedpagetext', 'bogus', 'edit' ],
				[ 'protectedpagetext', 'protect', 'edit' ] ],
			$this->title->getUserPermissionsErrors( 'edit',
				$this->user ) );

		$this->title->mCascadeRestriction = true;
		$this->setUserPerm( "edit" );
		$this->assertEquals( false,
			$this->title->quickUserCan( 'bogus', $this->user ) );
		$this->assertEquals( false,
			$this->title->quickUserCan( 'edit', $this->user ) );
		$this->assertEquals( [ [ 'badaccess-group0' ],
				[ 'protectedpagetext', 'bogus', 'bogus' ],
				[ 'protectedpagetext', 'editprotected', 'bogus' ],
				[ 'protectedpagetext', 'protect', 'bogus' ] ],
			$this->title->getUserPermissionsErrors( 'bogus',
				$this->user ) );
		$this->assertEquals( [ [ 'protectedpagetext', 'bogus', 'edit' ],
				[ 'protectedpagetext', 'editprotected', 'edit' ],
				[ 'protectedpagetext', 'protect', 'edit' ] ],
			$this->title->getUserPermissionsErrors( 'edit',
				$this->user ) );

		$this->setUserPerm( [ "edit", "editprotected" ] );
		$this->assertEquals( false,
			$this->title->quickUserCan( 'bogus', $this->user ) );
		$this->assertEquals( false,
			$this->title->quickUserCan( 'edit', $this->user ) );
		$this->assertEquals( [ [ 'badaccess-group0' ],
				[ 'protectedpagetext', 'bogus', 'bogus' ],
				[ 'protectedpagetext', 'protect', 'bogus' ],
				[ 'protectedpagetext', 'protect', 'bogus' ] ],
			$this->title->getUserPermissionsErrors( 'bogus',
				$this->user ) );
		$this->assertEquals( [ [ 'protectedpagetext', 'bogus', 'edit' ],
				[ 'protectedpagetext', 'protect', 'edit' ],
				[ 'protectedpagetext', 'protect', 'edit' ] ],
			$this->title->getUserPermissionsErrors( 'edit',
				$this->user ) );
	}

	/**
	 * @covers Title::checkCascadingSourcesRestrictions
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
			$this->title->userCan( 'bogus', $this->user ) );
		$this->assertEquals( [
				[ "cascadeprotected", 2, "* [[:Bogus]]\n* [[:UnBogus]]\n", 'bogus' ],
				[ "cascadeprotected", 2, "* [[:Bogus]]\n* [[:UnBogus]]\n", 'bogus' ],
				[ "cascadeprotected", 2, "* [[:Bogus]]\n* [[:UnBogus]]\n", 'bogus' ] ],
			$this->title->getUserPermissionsErrors( 'bogus', $this->user ) );

		$this->assertEquals( true,
			$this->title->userCan( 'edit', $this->user ) );
		$this->assertEquals( [],
			$this->title->getUserPermissionsErrors( 'edit', $this->user ) );
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 * @covers Title::checkActionPermissions
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
			$this->title->getUserPermissionsErrors( 'create', $this->user ) );
		$this->assertEquals( false,
			$this->title->userCan( 'create', $this->user ) );

		$this->title->mTitleProtection['permission'] = 'editprotected';
		$this->setUserPerm( [ 'createpage', 'protect' ] );
		$this->assertEquals( [ [ 'titleprotected', 'Useruser', 'test' ] ],
			$this->title->getUserPermissionsErrors( 'create', $this->user ) );
		$this->assertEquals( false,
			$this->title->userCan( 'create', $this->user ) );

		$this->setUserPerm( [ 'createpage', 'editprotected' ] );
		$this->assertEquals( [],
			$this->title->getUserPermissionsErrors( 'create', $this->user ) );
		$this->assertEquals( true,
			$this->title->userCan( 'create', $this->user ) );

		$this->setUserPerm( [ 'createpage' ] );
		$this->assertEquals( [ [ 'titleprotected', 'Useruser', 'test' ] ],
			$this->title->getUserPermissionsErrors( 'create', $this->user ) );
		$this->assertEquals( false,
			$this->title->userCan( 'create', $this->user ) );

		$this->setTitle( NS_MEDIA, "test page" );
		$this->setUserPerm( [ "move" ] );
		$this->assertEquals( false,
			$this->title->userCan( 'move', $this->user ) );
		$this->assertEquals( [ [ 'immobile-source-namespace', 'Media' ] ],
			$this->title->getUserPermissionsErrors( 'move', $this->user ) );

		$this->setTitle( NS_HELP, "test page" );
		$this->assertEquals( [],
			$this->title->getUserPermissionsErrors( 'move', $this->user ) );
		$this->assertEquals( true,
			$this->title->userCan( 'move', $this->user ) );

		$this->title->mInterwiki = "no";
		$this->assertEquals( [ [ 'immobile-source-page' ] ],
			$this->title->getUserPermissionsErrors( 'move', $this->user ) );
		$this->assertEquals( false,
			$this->title->userCan( 'move', $this->user ) );

		$this->setTitle( NS_MEDIA, "test page" );
		$this->assertEquals( false,
			$this->title->userCan( 'move-target', $this->user ) );
		$this->assertEquals( [ [ 'immobile-target-namespace', 'Media' ] ],
			$this->title->getUserPermissionsErrors( 'move-target', $this->user ) );

		$this->setTitle( NS_HELP, "test page" );
		$this->assertEquals( [],
			$this->title->getUserPermissionsErrors( 'move-target', $this->user ) );
		$this->assertEquals( true,
			$this->title->userCan( 'move-target', $this->user ) );

		$this->title->mInterwiki = "no";
		$this->assertEquals( [ [ 'immobile-target-page' ] ],
			$this->title->getUserPermissionsErrors( 'move-target', $this->user ) );
		$this->assertEquals( false,
			$this->title->userCan( 'move-target', $this->user ) );
	}

	/**
	 * @covers Title::checkUserBlock
	 */
	public function testUserBlock() {
		$this->setMwGlobals( [
			'wgEmailConfirmToEdit' => true,
			'wgEmailAuthentication' => true,
		] );

		$this->setUserPerm( [ "createpage", "move" ] );
		$this->setTitle( NS_HELP, "test page" );

		# $wgEmailConfirmToEdit only applies to 'edit' action
		$this->assertEquals( [],
			$this->title->getUserPermissionsErrors( 'move-target', $this->user ) );
		$this->assertContains( [ 'confirmedittext' ],
			$this->title->getUserPermissionsErrors( 'edit', $this->user ) );

		$this->setMwGlobals( 'wgEmailConfirmToEdit', false );
		$this->assertNotContains( [ 'confirmedittext' ],
			$this->title->getUserPermissionsErrors( 'edit', $this->user ) );

		# $wgEmailConfirmToEdit && !$user->isEmailConfirmed() && $action != 'createaccount'
		$this->assertEquals( [],
			$this->title->getUserPermissionsErrors( 'move-target',
				$this->user ) );

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
			$this->title->getUserPermissionsErrors( 'move-target',
				$this->user ) );

		$this->assertEquals( false, $this->title->userCan( 'move-target', $this->user ) );
		// quickUserCan should ignore user blocks
		$this->assertEquals( true, $this->title->quickUserCan( 'move-target', $this->user ) );

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
			$this->title->getUserPermissionsErrors( 'move-target', $this->user ) );
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
		$this->assertEquals( [ [ 'systemblockedtext',
				'[[User:Useruser|Useruser]]', 'no reason given', '127.0.0.1',
				'Useruser', 'test', '23:00, 31 December 1969', '127.0.8.1',
				$wgLang->timeanddate( wfTimestamp( TS_MW, $now ), true ) ] ],
			$this->title->getUserPermissionsErrors( 'move-target', $this->user ) );
	}
}
