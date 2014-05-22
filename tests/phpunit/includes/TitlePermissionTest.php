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

		$langObj = Language::factory( 'en' );
		$localZone = 'UTC';
		$localOffset = date( 'Z' ) / 60;

		$this->setMwGlobals( array(
			'wgMemc' => new EmptyBagOStuff,
			'wgContLang' => $langObj,
			'wgLanguageCode' => 'en',
			'wgLang' => $langObj,
			'wgLocaltimezone' => $localZone,
			'wgLocalTZoffset' => $localOffset,
			'wgNamespaceProtection' => array(
				NS_MEDIAWIKI => 'editinterface',
			),
		) );
		// Without this testUserBlock will use a non-English context on non-English MediaWiki
		// installations (because of how Title::checkUserBlock is implemented) and fail.
		RequestContext::resetMain();

		$this->userName = 'Useruser';
		$this->altUserName = 'Altuseruser';
		date_default_timezone_set( $localZone );

		$this->title = Title::makeTitle( NS_MAIN, "Main Page" );
		if ( !isset( $this->userUser ) || !( $this->userUser instanceof User ) ) {
			$this->userUser = User::newFromName( $this->userName );

			if ( !$this->userUser->getID() ) {
				$this->userUser = User::createNew( $this->userName, array(
					"email" => "test@example.com",
					"real_name" => "Test User" ) );
				$this->userUser->load();
			}

			$this->altUser = User::newFromName( $this->altUserName );
			if ( !$this->altUser->getID() ) {
				$this->altUser = User::createNew( $this->altUserName, array(
					"email" => "alttest@example.com",
					"real_name" => "Test User Alt" ) );
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
			$this->user->mRights = array( $perm );
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
	 */
	public function testQuickPermissions() {
		global $wgContLang;
		$prefix = $wgContLang->getFormattedNsText( NS_PROJECT );

		$this->setUser( 'anon' );
		$this->setTitle( NS_TALK );
		$this->setUserPerm( "createtalk" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( array(), $res );

		$this->setTitle( NS_TALK );
		$this->setUserPerm( "createpage" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( array( array( "nocreatetext" ) ), $res );

		$this->setTitle( NS_TALK );
		$this->setUserPerm( "" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( array( array( 'nocreatetext' ) ), $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "createpage" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( array(), $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "createtalk" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( array( array( 'nocreatetext' ) ), $res );

		$this->setUser( $this->userName );
		$this->setTitle( NS_TALK );
		$this->setUserPerm( "createtalk" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( array(), $res );

		$this->setTitle( NS_TALK );
		$this->setUserPerm( "createpage" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( array( array( 'nocreate-loggedin' ) ), $res );

		$this->setTitle( NS_TALK );
		$this->setUserPerm( "" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( array( array( 'nocreate-loggedin' ) ), $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "createpage" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( array(), $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "createtalk" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( array( array( 'nocreate-loggedin' ) ), $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( array( array( 'nocreate-loggedin' ) ), $res );

		$this->setUser( 'anon' );
		$this->setTitle( NS_USER, $this->userName . '' );
		$this->setUserPerm( "" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( array( array( 'cant-move-user-page' ), array( 'movenologintext' ) ), $res );

		$this->setTitle( NS_USER, $this->userName . '/subpage' );
		$this->setUserPerm( "" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( array( array( 'movenologintext' ) ), $res );

		$this->setTitle( NS_USER, $this->userName . '' );
		$this->setUserPerm( "move-rootuserpages" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( array( array( 'movenologintext' ) ), $res );

		$this->setTitle( NS_USER, $this->userName . '/subpage' );
		$this->setUserPerm( "move-rootuserpages" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( array( array( 'movenologintext' ) ), $res );

		$this->setTitle( NS_USER, $this->userName . '' );
		$this->setUserPerm( "" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( array( array( 'cant-move-user-page' ), array( 'movenologintext' ) ), $res );

		$this->setTitle( NS_USER, $this->userName . '/subpage' );
		$this->setUserPerm( "" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( array( array( 'movenologintext' ) ), $res );

		$this->setTitle( NS_USER, $this->userName . '' );
		$this->setUserPerm( "move-rootuserpages" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( array( array( 'movenologintext' ) ), $res );

		$this->setTitle( NS_USER, $this->userName . '/subpage' );
		$this->setUserPerm( "move-rootuserpages" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( array( array( 'movenologintext' ) ), $res );

		$this->setUser( $this->userName );
		$this->setTitle( NS_FILE, "img.png" );
		$this->setUserPerm( "" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( array( array( 'movenotallowedfile' ), array( 'movenotallowed' ) ), $res );

		$this->setTitle( NS_FILE, "img.png" );
		$this->setUserPerm( "movefile" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( array( array( 'movenotallowed' ) ), $res );

		$this->setUser( 'anon' );
		$this->setTitle( NS_FILE, "img.png" );
		$this->setUserPerm( "" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( array( array( 'movenotallowedfile' ), array( 'movenologintext' ) ), $res );

		$this->setTitle( NS_FILE, "img.png" );
		$this->setUserPerm( "movefile" );
		$res = $this->title->getUserPermissionsErrors( 'move', $this->user );
		$this->assertEquals( array( array( 'movenologintext' ) ), $res );

		$this->setUser( $this->userName );
		$this->setUserPerm( "move" );
		$this->runGroupPermissions( 'move', array( array( 'movenotallowedfile' ) ) );

		$this->setUserPerm( "" );
		$this->runGroupPermissions(
			'move',
			array( array( 'movenotallowedfile' ), array( 'movenotallowed' ) )
		);

		$this->setUser( 'anon' );
		$this->setUserPerm( "move" );
		$this->runGroupPermissions( 'move', array( array( 'movenotallowedfile' ) ) );

		$this->setUserPerm( "" );
		$this->runGroupPermissions(
			'move',
			array( array( 'movenotallowedfile' ), array( 'movenotallowed' ) ),
			array( array( 'movenotallowedfile' ), array( 'movenologintext' ) )
		);

		if ( $this->isWikitextNS( NS_MAIN ) ) {
			//NOTE: some content models don't allow moving
			// @todo find a Wikitext namespace for testing

			$this->setTitle( NS_MAIN );
			$this->setUser( 'anon' );
			$this->setUserPerm( "move" );
			$this->runGroupPermissions( 'move', array() );

			$this->setUserPerm( "" );
			$this->runGroupPermissions( 'move', array( array( 'movenotallowed' ) ),
				array( array( 'movenologintext' ) ) );

			$this->setUser( $this->userName );
			$this->setUserPerm( "" );
			$this->runGroupPermissions( 'move', array( array( 'movenotallowed' ) ) );

			$this->setUserPerm( "move" );
			$this->runGroupPermissions( 'move', array() );

			$this->setUser( 'anon' );
			$this->setUserPerm( 'move' );
			$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
			$this->assertEquals( array(), $res );

			$this->setUserPerm( '' );
			$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
			$this->assertEquals( array( array( 'movenotallowed' ) ), $res );
		}

		$this->setTitle( NS_USER );
		$this->setUser( $this->userName );
		$this->setUserPerm( array( "move", "move-rootuserpages" ) );
		$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
		$this->assertEquals( array(), $res );

		$this->setUserPerm( "move" );
		$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
		$this->assertEquals( array( array( 'cant-move-to-user-page' ) ), $res );

		$this->setUser( 'anon' );
		$this->setUserPerm( array( "move", "move-rootuserpages" ) );
		$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
		$this->assertEquals( array(), $res );

		$this->setTitle( NS_USER, "User/subpage" );
		$this->setUserPerm( array( "move", "move-rootuserpages" ) );
		$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
		$this->assertEquals( array(), $res );

		$this->setUserPerm( "move" );
		$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
		$this->assertEquals( array(), $res );

		$this->setUser( 'anon' );
		$check = array(
			'edit' => array(
				array( array( 'badaccess-groups', "*, [[$prefix:Users|Users]]", 2 ) ),
				array( array( 'badaccess-group0' ) ),
				array(),
				true
			),
			'protect' => array(
				array( array(
					'badaccess-groups',
					"[[$prefix:Administrators|Administrators]]", 1 ),
					array( 'protect-cantedit'
				) ),
				array( array( 'badaccess-group0' ), array( 'protect-cantedit' ) ),
				array( array( 'protect-cantedit' ) ),
				false
			),
			'' => array( array(), array(), array(), true )
		);

		foreach ( array( "edit", "protect", "" ) as $action ) {
			$this->setUserPerm( null );
			$this->assertEquals( $check[$action][0],
				$this->title->getUserPermissionsErrors( $action, $this->user, true ) );
			$this->assertEquals( $check[$action][0],
				$this->title->getUserPermissionsErrors( $action, $this->user, 'full' ) );
			$this->assertEquals( $check[$action][0],
				$this->title->getUserPermissionsErrors( $action, $this->user, 'secure' ) );

			global $wgGroupPermissions;
			$old = $wgGroupPermissions;
			$wgGroupPermissions = array();

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
	 */
	public function testSpecialsAndNSPermissions() {
		global $wgNamespaceProtection;
		$this->setUser( $this->userName );

		$this->setTitle( NS_SPECIAL );

		$this->assertEquals( array( array( 'badaccess-group0' ), array( 'ns-specialprotected' ) ),
			$this->title->getUserPermissionsErrors( 'bogus', $this->user ) );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( 'bogus' );
		$this->assertEquals( array(),
			$this->title->getUserPermissionsErrors( 'bogus', $this->user ) );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( '' );
		$this->assertEquals( array( array( 'badaccess-group0' ) ),
			$this->title->getUserPermissionsErrors( 'bogus', $this->user ) );

		$wgNamespaceProtection[NS_USER] = array( 'bogus' );

		$this->setTitle( NS_USER );
		$this->setUserPerm( '' );
		$this->assertEquals( array( array( 'badaccess-group0' ),
				array( 'namespaceprotected', 'User', 'bogus' ) ),
			$this->title->getUserPermissionsErrors( 'bogus', $this->user ) );

		$this->setTitle( NS_MEDIAWIKI );
		$this->setUserPerm( 'bogus' );
		$this->assertEquals( array( array( 'protectedinterface', 'bogus' ) ),
			$this->title->getUserPermissionsErrors( 'bogus', $this->user ) );

		$this->setTitle( NS_MEDIAWIKI );
		$this->setUserPerm( 'bogus' );
		$this->assertEquals( array( array( 'protectedinterface', 'bogus' ) ),
			$this->title->getUserPermissionsErrors( 'bogus', $this->user ) );

		$wgNamespaceProtection = null;

		$this->setUserPerm( 'bogus' );
		$this->assertEquals( array(),
			$this->title->getUserPermissionsErrors( 'bogus', $this->user ) );
		$this->assertEquals( true,
			$this->title->userCan( 'bogus', $this->user ) );

		$this->setUserPerm( '' );
		$this->assertEquals( array( array( 'badaccess-group0' ) ),
			$this->title->getUserPermissionsErrors( 'bogus', $this->user ) );
		$this->assertEquals( false,
			$this->title->userCan( 'bogus', $this->user ) );
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 */
	public function testCssAndJavascriptPermissions() {
		$this->setUser( $this->userName );

		$this->setTitle( NS_USER, $this->userName . '/test.js' );
		$this->runCSSandJSPermissions(
			array( array( 'badaccess-group0' ), array( 'mycustomjsprotected', 'bogus' ) ),
			array( array( 'badaccess-group0' ), array( 'mycustomjsprotected', 'bogus' ) ),
			array( array( 'badaccess-group0' ) ),
			array( array( 'badaccess-group0' ), array( 'mycustomjsprotected', 'bogus' ) ),
			array( array( 'badaccess-group0' ) )
		);

		$this->setTitle( NS_USER, $this->userName . '/test.css' );
		$this->runCSSandJSPermissions(
			array( array( 'badaccess-group0' ), array( 'mycustomcssprotected', 'bogus' ) ),
			array( array( 'badaccess-group0' ) ),
			array( array( 'badaccess-group0' ), array( 'mycustomcssprotected', 'bogus' ) ),
			array( array( 'badaccess-group0' ) ),
			array( array( 'badaccess-group0' ), array( 'mycustomcssprotected', 'bogus' ) )
		);

		$this->setTitle( NS_USER, $this->altUserName . '/test.js' );
		$this->runCSSandJSPermissions(
			array( array( 'badaccess-group0' ), array( 'customjsprotected', 'bogus' ) ),
			array( array( 'badaccess-group0' ), array( 'customjsprotected', 'bogus' ) ),
			array( array( 'badaccess-group0' ), array( 'customjsprotected', 'bogus' ) ),
			array( array( 'badaccess-group0' ), array( 'customjsprotected', 'bogus' ) ),
			array( array( 'badaccess-group0' ) )
		);

		$this->setTitle( NS_USER, $this->altUserName . '/test.css' );
		$this->runCSSandJSPermissions(
			array( array( 'badaccess-group0' ), array( 'customcssprotected', 'bogus' ) ),
			array( array( 'badaccess-group0' ), array( 'customcssprotected', 'bogus' ) ),
			array( array( 'badaccess-group0' ), array( 'customcssprotected', 'bogus' ) ),
			array( array( 'badaccess-group0' ) ),
			array( array( 'badaccess-group0' ), array( 'customcssprotected', 'bogus' ) )
		);

		$this->setTitle( NS_USER, $this->altUserName . '/tempo' );
		$this->runCSSandJSPermissions(
			array( array( 'badaccess-group0' ) ),
			array( array( 'badaccess-group0' ) ),
			array( array( 'badaccess-group0' ) ),
			array( array( 'badaccess-group0' ) ),
			array( array( 'badaccess-group0' ) )
		);
	}

	protected function runCSSandJSPermissions( $result0, $result1, $result2, $result3, $result4 ) {
		$this->setUserPerm( '' );
		$this->assertEquals( $result0,
			$this->title->getUserPermissionsErrors( 'bogus',
				$this->user ) );

		$this->setUserPerm( 'editmyusercss' );
		$this->assertEquals( $result1,
			$this->title->getUserPermissionsErrors( 'bogus',
				$this->user ) );

		$this->setUserPerm( 'editmyuserjs' );
		$this->assertEquals( $result2,
			$this->title->getUserPermissionsErrors( 'bogus',
				$this->user ) );

		$this->setUserPerm( 'editusercss' );
		$this->assertEquals( $result3,
			$this->title->getUserPermissionsErrors( 'bogus',
				$this->user ) );

		$this->setUserPerm( 'edituserjs' );
		$this->assertEquals( $result4,
			$this->title->getUserPermissionsErrors( 'bogus',
				$this->user ) );

		$this->setUserPerm( 'editusercssjs' );
		$this->assertEquals( array( array( 'badaccess-group0' ) ),
			$this->title->getUserPermissionsErrors( 'bogus',
				$this->user ) );

		$this->setUserPerm( array( 'edituserjs', 'editusercss' ) );
		$this->assertEquals( array( array( 'badaccess-group0' ) ),
			$this->title->getUserPermissionsErrors( 'bogus',
				$this->user ) );
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 */
	public function testPageRestrictions() {
		global $wgContLang;

		$prefix = $wgContLang->getFormattedNsText( NS_PROJECT );

		$this->setTitle( NS_MAIN );
		$this->title->mRestrictionsLoaded = true;
		$this->setUserPerm( "edit" );
		$this->title->mRestrictions = array( "bogus" => array( 'bogus', "sysop", "protect", "" ) );

		$this->assertEquals( array(),
			$this->title->getUserPermissionsErrors( 'edit',
				$this->user ) );

		$this->assertEquals( true,
			$this->title->quickUserCan( 'edit', $this->user ) );
		$this->title->mRestrictions = array( "edit" => array( 'bogus', "sysop", "protect", "" ),
			"bogus" => array( 'bogus', "sysop", "protect", "" ) );

		$this->assertEquals( array( array( 'badaccess-group0' ),
				array( 'protectedpagetext', 'bogus', 'bogus' ),
				array( 'protectedpagetext', 'editprotected', 'bogus' ),
				array( 'protectedpagetext', 'protect', 'bogus' ) ),
			$this->title->getUserPermissionsErrors( 'bogus',
				$this->user ) );
		$this->assertEquals( array( array( 'protectedpagetext', 'bogus', 'edit' ),
				array( 'protectedpagetext', 'editprotected', 'edit' ),
				array( 'protectedpagetext', 'protect', 'edit' ) ),
			$this->title->getUserPermissionsErrors( 'edit',
				$this->user ) );
		$this->setUserPerm( "" );
		$this->assertEquals( array( array( 'badaccess-group0' ),
				array( 'protectedpagetext', 'bogus', 'bogus' ),
				array( 'protectedpagetext', 'editprotected', 'bogus' ),
				array( 'protectedpagetext', 'protect', 'bogus' ) ),
			$this->title->getUserPermissionsErrors( 'bogus',
				$this->user ) );
		$this->assertEquals( array( array( 'badaccess-groups', "*, [[$prefix:Users|Users]]", 2 ),
				array( 'protectedpagetext', 'bogus', 'edit' ),
				array( 'protectedpagetext', 'editprotected', 'edit' ),
				array( 'protectedpagetext', 'protect', 'edit' ) ),
			$this->title->getUserPermissionsErrors( 'edit',
				$this->user ) );
		$this->setUserPerm( array( "edit", "editprotected" ) );
		$this->assertEquals( array( array( 'badaccess-group0' ),
				array( 'protectedpagetext', 'bogus', 'bogus' ),
				array( 'protectedpagetext', 'protect', 'bogus' ) ),
			$this->title->getUserPermissionsErrors( 'bogus',
				$this->user ) );
		$this->assertEquals( array(
				array( 'protectedpagetext', 'bogus', 'edit' ),
				array( 'protectedpagetext', 'protect', 'edit' ) ),
			$this->title->getUserPermissionsErrors( 'edit',
				$this->user ) );

		$this->title->mCascadeRestriction = true;
		$this->setUserPerm( "edit" );
		$this->assertEquals( false,
			$this->title->quickUserCan( 'bogus', $this->user ) );
		$this->assertEquals( false,
			$this->title->quickUserCan( 'edit', $this->user ) );
		$this->assertEquals( array( array( 'badaccess-group0' ),
				array( 'protectedpagetext', 'bogus', 'bogus' ),
				array( 'protectedpagetext', 'editprotected', 'bogus' ),
				array( 'protectedpagetext', 'protect', 'bogus' ) ),
			$this->title->getUserPermissionsErrors( 'bogus',
				$this->user ) );
		$this->assertEquals( array( array( 'protectedpagetext', 'bogus', 'edit' ),
				array( 'protectedpagetext', 'editprotected', 'edit' ),
				array( 'protectedpagetext', 'protect', 'edit' ) ),
			$this->title->getUserPermissionsErrors( 'edit',
				$this->user ) );

		$this->setUserPerm( array( "edit", "editprotected" ) );
		$this->assertEquals( false,
			$this->title->quickUserCan( 'bogus', $this->user ) );
		$this->assertEquals( false,
			$this->title->quickUserCan( 'edit', $this->user ) );
		$this->assertEquals( array( array( 'badaccess-group0' ),
				array( 'protectedpagetext', 'bogus', 'bogus' ),
				array( 'protectedpagetext', 'protect', 'bogus' ),
				array( 'protectedpagetext', 'protect', 'bogus' ) ),
			$this->title->getUserPermissionsErrors( 'bogus',
				$this->user ) );
		$this->assertEquals( array( array( 'protectedpagetext', 'bogus', 'edit' ),
				array( 'protectedpagetext', 'protect', 'edit' ),
				array( 'protectedpagetext', 'protect', 'edit' ) ),
			$this->title->getUserPermissionsErrors( 'edit',
				$this->user ) );
	}

	public function testCascadingSourcesRestrictions() {
		$this->setTitle( NS_MAIN, "test page" );
		$this->setUserPerm( array( "edit", "bogus" ) );

		$this->title->mCascadeSources = array(
			Title::makeTitle( NS_MAIN, "Bogus" ),
			Title::makeTitle( NS_MAIN, "UnBogus" )
		);
		$this->title->mCascadingRestrictions = array(
			"bogus" => array( 'bogus', "sysop", "protect", "" )
		);

		$this->assertEquals( false,
			$this->title->userCan( 'bogus', $this->user ) );
		$this->assertEquals( array(
				array( "cascadeprotected", 2, "* [[:Bogus]]\n* [[:UnBogus]]\n", 'bogus' ),
				array( "cascadeprotected", 2, "* [[:Bogus]]\n* [[:UnBogus]]\n", 'bogus' ),
				array( "cascadeprotected", 2, "* [[:Bogus]]\n* [[:UnBogus]]\n", 'bogus' ) ),
			$this->title->getUserPermissionsErrors( 'bogus', $this->user ) );

		$this->assertEquals( true,
			$this->title->userCan( 'edit', $this->user ) );
		$this->assertEquals( array(),
			$this->title->getUserPermissionsErrors( 'edit', $this->user ) );
	}

	/**
	 * @todo This test method should be split up into separate test methods and
	 * data providers
	 */
	public function testActionPermissions() {
		$this->setUserPerm( array( "createpage" ) );
		$this->setTitle( NS_MAIN, "test page" );
		$this->title->mTitleProtection['permission'] = '';
		$this->title->mTitleProtection['user'] = $this->user->getID();
		$this->title->mTitleProtection['expiry'] = 'infinity';
		$this->title->mTitleProtection['reason'] = 'test';
		$this->title->mCascadeRestriction = false;

		$this->assertEquals( array( array( 'titleprotected', 'Useruser', 'test' ) ),
			$this->title->getUserPermissionsErrors( 'create', $this->user ) );
		$this->assertEquals( false,
			$this->title->userCan( 'create', $this->user ) );

		$this->title->mTitleProtection['permission'] = 'editprotected';
		$this->setUserPerm( array( 'createpage', 'protect' ) );
		$this->assertEquals( array( array( 'titleprotected', 'Useruser', 'test' ) ),
			$this->title->getUserPermissionsErrors( 'create', $this->user ) );
		$this->assertEquals( false,
			$this->title->userCan( 'create', $this->user ) );

		$this->setUserPerm( array( 'createpage', 'editprotected' ) );
		$this->assertEquals( array(),
			$this->title->getUserPermissionsErrors( 'create', $this->user ) );
		$this->assertEquals( true,
			$this->title->userCan( 'create', $this->user ) );

		$this->setUserPerm( array( 'createpage' ) );
		$this->assertEquals( array( array( 'titleprotected', 'Useruser', 'test' ) ),
			$this->title->getUserPermissionsErrors( 'create', $this->user ) );
		$this->assertEquals( false,
			$this->title->userCan( 'create', $this->user ) );

		$this->setTitle( NS_MEDIA, "test page" );
		$this->setUserPerm( array( "move" ) );
		$this->assertEquals( false,
			$this->title->userCan( 'move', $this->user ) );
		$this->assertEquals( array( array( 'immobile-source-namespace', 'Media' ) ),
			$this->title->getUserPermissionsErrors( 'move', $this->user ) );

		$this->setTitle( NS_HELP, "test page" );
		$this->assertEquals( array(),
			$this->title->getUserPermissionsErrors( 'move', $this->user ) );
		$this->assertEquals( true,
			$this->title->userCan( 'move', $this->user ) );

		$this->title->mInterwiki = "no";
		$this->assertEquals( array( array( 'immobile-source-page' ) ),
			$this->title->getUserPermissionsErrors( 'move', $this->user ) );
		$this->assertEquals( false,
			$this->title->userCan( 'move', $this->user ) );

		$this->setTitle( NS_MEDIA, "test page" );
		$this->assertEquals( false,
			$this->title->userCan( 'move-target', $this->user ) );
		$this->assertEquals( array( array( 'immobile-target-namespace', 'Media' ) ),
			$this->title->getUserPermissionsErrors( 'move-target', $this->user ) );

		$this->setTitle( NS_HELP, "test page" );
		$this->assertEquals( array(),
			$this->title->getUserPermissionsErrors( 'move-target', $this->user ) );
		$this->assertEquals( true,
			$this->title->userCan( 'move-target', $this->user ) );

		$this->title->mInterwiki = "no";
		$this->assertEquals( array( array( 'immobile-target-page' ) ),
			$this->title->getUserPermissionsErrors( 'move-target', $this->user ) );
		$this->assertEquals( false,
			$this->title->userCan( 'move-target', $this->user ) );
	}

	public function testUserBlock() {
		global $wgEmailConfirmToEdit, $wgEmailAuthentication;
		$wgEmailConfirmToEdit = true;
		$wgEmailAuthentication = true;

		$this->setUserPerm( array( "createpage", "move" ) );
		$this->setTitle( NS_HELP, "test page" );

		# $short
		$this->assertEquals( array( array( 'confirmedittext' ) ),
			$this->title->getUserPermissionsErrors( 'move-target', $this->user ) );
		$wgEmailConfirmToEdit = false;
		$this->assertEquals( true, $this->title->userCan( 'move-target', $this->user ) );

		# $wgEmailConfirmToEdit && !$user->isEmailConfirmed() && $action != 'createaccount'
		$this->assertEquals( array(),
			$this->title->getUserPermissionsErrors( 'move-target',
				$this->user ) );

		global $wgLang;
		$prev = time();
		$now = time() + 120;
		$this->user->mBlockedby = $this->user->getId();
		$this->user->mBlock = new Block( array(
			'address' => '127.0.8.1',
			'by' => $this->user->getId(),
			'reason' => 'no reason given',
			'timestamp' => $prev + 3600,
			'auto' => true,
			'expiry' => 0
		) );
		$this->user->mBlock->mTimestamp = 0;
		$this->assertEquals( array( array( 'autoblockedtext',
				'[[User:Useruser|Useruser]]', 'no reason given', '127.0.0.1',
				'Useruser', null, 'infinite', '127.0.8.1',
				$wgLang->timeanddate( wfTimestamp( TS_MW, $prev ), true ) ) ),
			$this->title->getUserPermissionsErrors( 'move-target',
				$this->user ) );

		$this->assertEquals( false, $this->title->userCan( 'move-target', $this->user ) );
		// quickUserCan should ignore user blocks
		$this->assertEquals( true, $this->title->quickUserCan( 'move-target', $this->user ) );

		global $wgLocalTZoffset;
		$wgLocalTZoffset = -60;
		$this->user->mBlockedby = $this->user->getName();
		$this->user->mBlock = new Block( array(
			'address' => '127.0.8.1',
			'by' => $this->user->getId(),
			'reason' => 'no reason given',
			'timestamp' => $now,
			'auto' => false,
			'expiry' => 10,
		) );
		$this->assertEquals( array( array( 'blockedtext',
				'[[User:Useruser|Useruser]]', 'no reason given', '127.0.0.1',
				'Useruser', null, '23:00, 31 December 1969', '127.0.8.1',
				$wgLang->timeanddate( wfTimestamp( TS_MW, $now ), true ) ) ),
			$this->title->getUserPermissionsErrors( 'move-target', $this->user ) );
		# $action != 'read' && $action != 'createaccount' && $user->isBlockedFrom( $this )
		#   $user->blockedFor() == ''
		#   $user->mBlock->mExpiry == 'infinity'
	}
}
