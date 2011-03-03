<?php

/**
 * @group Database
 */
class TitlePermissionTest extends MediaWikiTestCase {
	protected $title;
	protected $user;
	protected $anonUser;
	protected $userUser;
	protected $altUser;
	protected $userName;
	protected $altUserName;

	function setUp() {
		global $wgLocaltimezone, $wgLocalTZoffset, $wgMemc, $wgContLang, $wgLang;

		if(!$wgMemc) {
			$wgMemc = new EmptyBagOStuff;
		}
		$wgContLang = $wgLang = Language::factory( 'en' );

		$this->userName = "Useruser";
		$this->altUserName = "Altuseruser";
		date_default_timezone_set( $wgLocaltimezone );
		$wgLocalTZoffset = date( "Z" ) / 60;

		$this->title = Title::makeTitle( NS_MAIN, "Main Page" );
		if ( !isset( $this->userUser ) || !( $this->userUser instanceOf User ) ) {
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

	function setUserPerm( $perm ) {
		if ( is_array( $perm ) ) {
			$this->user->mRights = $perm;
		} else {
			$this->user->mRights = array( $perm );
		}
	}

	function setTitle( $ns, $title = "Main_Page" ) {
		$this->title = Title::makeTitle( $ns, $title );
	}

	function setUser( $userName = null ) {
		if ( $userName === 'anon' ) {
			$this->user = $this->anonUser;
		} else if ( $userName === null || $userName === $this->userName ) {
			$this->user = $this->userUser;
		} else {
			$this->user = $this->altUser;
		}

		global $wgUser;
		$wgUser = $this->user;
	}

	function testQuickPermissions() {
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
		$this->assertEquals( array( ), $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "createtalk" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( array( array( 'nocreatetext' ) ), $res );

		$this->setUser( $this->userName );
		$this->setTitle( NS_TALK );
		$this->setUserPerm( "createtalk" );
		$res = $this->title->getUserPermissionsErrors( 'create', $this->user );
		$this->assertEquals( array( ), $res );

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
		$this->assertEquals( array( ), $res );

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
		$this->runGroupPermissions( 'move', array( array( 'movenotallowedfile' ), array( 'movenotallowed' ) ) );

		$this->setUser( 'anon' );
		$this->setUserPerm( "move" );
		$this->runGroupPermissions( 'move', array( array( 'movenotallowedfile' ) ) );

		$this->setUserPerm( "" );
		$this->runGroupPermissions( 'move', array( array( 'movenotallowedfile' ), array( 'movenotallowed' ) ),
			array( array( 'movenotallowedfile' ), array( 'movenologintext' ) ) );

		$this->setTitle( NS_MAIN );
		$this->setUser( 'anon' );
		$this->setUserPerm( "move" );
		$this->runGroupPermissions( 'move', array(  ) );

		$this->setUserPerm( "" );
		$this->runGroupPermissions( 'move', array( array( 'movenotallowed' ) ),
			array( array( 'movenologintext' ) ) );

		$this->setUser( $this->userName );
		$this->setUserPerm( "" );
		$this->runGroupPermissions( 'move', array( array( 'movenotallowed' ) ) );

		$this->setUserPerm( "move" );
		$this->runGroupPermissions( 'move', array( ) );

		$this->setUser( 'anon' );
		$this->setUserPerm( 'move' );
		$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
		$this->assertEquals( array( ), $res );

		$this->setUserPerm( '' );
		$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
		$this->assertEquals( array( array( 'movenotallowed' ) ), $res );

		$this->setTitle( NS_USER );
		$this->setUser( $this->userName );
		$this->setUserPerm( array( "move", "move-rootuserpages" ) );
		$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
		$this->assertEquals( array( ), $res );

		$this->setUserPerm( "move" );
		$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
		$this->assertEquals( array( array( 'cant-move-to-user-page' ) ), $res );

		$this->setUser( 'anon' );
		$this->setUserPerm( array( "move", "move-rootuserpages" ) );
		$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
		$this->assertEquals( array( ), $res );

		$this->setTitle( NS_USER, "User/subpage" );
		$this->setUserPerm( array( "move", "move-rootuserpages" ) );
		$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
		$this->assertEquals( array( ), $res );

		$this->setUserPerm( "move" );
		$res = $this->title->getUserPermissionsErrors( 'move-target', $this->user );
		$this->assertEquals( array( ), $res );

		$this->setUser( 'anon' );
		$check = array( 'edit' => array( array( array( 'badaccess-groups', "*, [[$prefix:Users|Users]]", 2 ) ),
										 array( array( 'badaccess-group0' ) ),
										 array( ), true ),
						'protect' => array( array( array( 'badaccess-groups', "[[$prefix:Administrators|Administrators]]", 1 ), array( 'protect-cantedit' ) ),
											array( array( 'badaccess-group0' ), array( 'protect-cantedit' ) ),
											array( array( 'protect-cantedit' ) ), false ),
						'' => array( array( ), array( ), array( ), true ) );
		global $wgUser;
		$wgUser = $this->user;
		foreach ( array( "edit", "protect", "" ) as $action ) {
			$this->setUserPerm( null );
			$this->assertEquals( $check[$action][0],
				$this->title->getUserPermissionsErrors( $action, $this->user, true ) );

			global $wgGroupPermissions;
			$old = $wgGroupPermissions;
			$wgGroupPermissions = array();

			$this->assertEquals( $check[$action][1],
				$this->title->getUserPermissionsErrors( $action, $this->user, true ) );
			$wgGroupPermissions = $old;

			$this->setUserPerm( $action );
			$this->assertEquals( $check[$action][2],
				$this->title->getUserPermissionsErrors( $action, $this->user, true ) );

			$this->setUserPerm( $action );
			$this->assertEquals( $check[$action][3],
				$this->title->userCan( $action, true ) );
			$this->assertEquals( $check[$action][3],
				$this->title->quickUserCan( $action, false ) );

			# count( User::getGroupsWithPermissions( $action ) ) < 1
		}
	}

	function runGroupPermissions( $action, $result, $result2 = null ) {
		global $wgGroupPermissions;

		if ( $result2 === null ) $result2 = $result;

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

	function testPermissionHooks() { }
	function testSpecialsAndNSPermissions() {
		$this->setUser( $this->userName );
		global $wgUser, $wgContLang;
		$wgUser = $this->user;
		$prefix = $wgContLang->getFormattedNsText( NS_PROJECT );

		$this->setTitle( NS_SPECIAL );
		
		$this->assertEquals( array( array( 'badaccess-group0' ), array( 'ns-specialprotected' ) ),
							 $this->title->getUserPermissionsErrors( 'bogus', $this->user ) );
		$this->assertEquals( array( array( 'badaccess-group0' ) ),
							 $this->title->getUserPermissionsErrors( 'execute', $this->user ) );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( 'bogus' );
		$this->assertEquals( array( ),
							 $this->title->getUserPermissionsErrors( 'bogus', $this->user ) );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( '' );
		$this->assertEquals( array( array( 'badaccess-group0' ) ),
							 $this->title->getUserPermissionsErrors( 'bogus', $this->user ) );

		global $wgNamespaceProtection;
		$wgNamespaceProtection[NS_USER] = array ( 'bogus' );
		$this->setTitle( NS_USER );
		$this->setUserPerm( '' );
		$this->assertEquals( array( array( 'badaccess-group0' ), array( 'namespaceprotected', 'User' ) ),
							 $this->title->getUserPermissionsErrors( 'bogus', $this->user ) );

		$this->setTitle( NS_MEDIAWIKI );
		$this->setUserPerm( 'bogus' );
		$this->assertEquals( array( array( 'protectedinterface' ) ),
							 $this->title->getUserPermissionsErrors( 'bogus', $this->user ) );

		$this->setTitle( NS_MEDIAWIKI );
		$this->setUserPerm( 'bogus' );
		$this->assertEquals( array( array( 'protectedinterface' ) ),
							 $this->title->getUserPermissionsErrors( 'bogus', $this->user ) );

		$wgNamespaceProtection = null;
		$this->setUserPerm( 'bogus' );
		$this->assertEquals( array( ),
							 $this->title->getUserPermissionsErrors( 'bogus', $this->user ) );
		$this->assertEquals( true,
							 $this->title->userCan( 'bogus' ) );

		$this->setUserPerm( '' );
		$this->assertEquals( array( array( 'badaccess-group0' ) ),
							 $this->title->getUserPermissionsErrors( 'bogus', $this->user ) );
		$this->assertEquals( false,
							 $this->title->userCan( 'bogus' ) );
	}

	function testCSSandJSPermissions() {
		$this->setUser( $this->userName );
		global $wgUser;
		$wgUser = $this->user;

		$this->setTitle( NS_USER, $this->altUserName . '/test.js' );
		$this->runCSSandJSPermissions(
			array( array( 'badaccess-group0' ), array( 'customcssjsprotected' ) ),
			array( array( 'badaccess-group0' ), array( 'customcssjsprotected'  ) ),
			array( array( 'badaccess-group0' ) ) );

		$this->setTitle( NS_USER, $this->altUserName . '/test.css' );
		$this->runCSSandJSPermissions(
			array( array( 'badaccess-group0' ), array( 'customcssjsprotected' ) ),
			array( array( 'badaccess-group0' ) ),
			array( array( 'badaccess-group0' ),  array( 'customcssjsprotected' ) ) );

		$this->setTitle( NS_USER, $this->altUserName . '/tempo' );
		$this->runCSSandJSPermissions(
			array( array( 'badaccess-group0' ) ),
			array( array( 'badaccess-group0' ) ),
			array( array( 'badaccess-group0' ) ) );
	}

	function runCSSandJSPermissions( $result0, $result1, $result2 ) {
		$this->setUserPerm( '' );
		$this->assertEquals( $result0,
							 $this->title->getUserPermissionsErrors( 'bogus',
																	 $this->user ) );

		$this->setUserPerm( 'editusercss' );
		$this->assertEquals( $result1,
							 $this->title->getUserPermissionsErrors( 'bogus',
																	 $this->user ) );

		$this->setUserPerm( 'edituserjs' );
		$this->assertEquals( $result2,
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

	function testPageRestrictions() {
		global $wgUser, $wgContLang;

		$prefix = $wgContLang->getFormattedNsText( NS_PROJECT );

		$wgUser = $this->user;
		$this->setTitle( NS_MAIN );
		$this->title->mRestrictionsLoaded = true;
		$this->setUserPerm( "edit" );
		$this->title->mRestrictions = array( "bogus" => array( 'bogus', "sysop", "protect", "" ) );

		$this->assertEquals( array( ),
							 $this->title->getUserPermissionsErrors( 'edit',
																	 $this->user ) );

		$this->assertEquals( true,
							 $this->title->quickUserCan( 'edit', false ) );
		$this->title->mRestrictions = array( "edit" => array( 'bogus', "sysop", "protect", "" ),
										   "bogus" => array( 'bogus', "sysop", "protect", "" ) );

		$this->assertEquals( array( array( 'badaccess-group0' ),
									array( 'protectedpagetext', 'bogus' ),
									array( 'protectedpagetext', 'protect' ),
									array( 'protectedpagetext', 'protect' ) ),
							 $this->title->getUserPermissionsErrors( 'bogus',
																	 $this->user ) );
		$this->assertEquals( array( array( 'protectedpagetext', 'bogus' ),
									array( 'protectedpagetext', 'protect' ),
									array( 'protectedpagetext', 'protect' ) ),
							 $this->title->getUserPermissionsErrors( 'edit',
																	 $this->user ) );
		$this->setUserPerm( "" );
		$this->assertEquals( array( array( 'badaccess-group0' ),
									array( 'protectedpagetext', 'bogus' ),
									array( 'protectedpagetext', 'protect' ),
									array( 'protectedpagetext', 'protect' ) ),
							 $this->title->getUserPermissionsErrors( 'bogus',
																	 $this->user ) );
		$this->assertEquals( array( array( 'badaccess-groups', "*, [[$prefix:Users|Users]]", 2 ),
									array( 'protectedpagetext', 'bogus' ),
									array( 'protectedpagetext', 'protect' ),
									array( 'protectedpagetext', 'protect' ) ),
							 $this->title->getUserPermissionsErrors( 'edit',
																	 $this->user ) );
		$this->setUserPerm( array( "edit", "editprotected" ) );
		$this->assertEquals( array( array( 'badaccess-group0' ),
									array( 'protectedpagetext', 'bogus' ),
									array( 'protectedpagetext', 'protect' ),
									array( 'protectedpagetext', 'protect' ) ),
							 $this->title->getUserPermissionsErrors( 'bogus',
																	 $this->user ) );
		$this->assertEquals( array(  ),
							 $this->title->getUserPermissionsErrors( 'edit',
																	 $this->user ) );
		$this->title->mCascadeRestriction = true;
		$this->assertEquals( false,
							 $this->title->quickUserCan( 'bogus', false ) );
		$this->assertEquals( false,
							 $this->title->quickUserCan( 'edit', false ) );
		$this->assertEquals( array( array( 'badaccess-group0' ),
									array( 'protectedpagetext', 'bogus' ),
									array( 'protectedpagetext', 'protect' ),
									array( 'protectedpagetext', 'protect' ) ),
							 $this->title->getUserPermissionsErrors( 'bogus',
																	 $this->user ) );
		$this->assertEquals( array( array( 'protectedpagetext', 'bogus' ),
									array( 'protectedpagetext', 'protect' ),
									array( 'protectedpagetext', 'protect' ) ),
							 $this->title->getUserPermissionsErrors( 'edit',
																	 $this->user ) );
	}

	function testCascadingSourcesRestrictions() {
		global $wgUser;
		$wgUser = $this->user;
		$this->setTitle( NS_MAIN, "test page" );
		$this->setUserPerm( array( "edit", "bogus" ) );

		$this->title->mCascadeSources = array( Title::makeTitle( NS_MAIN, "Bogus" ), Title::makeTitle( NS_MAIN, "UnBogus" ) );
		$this->title->mCascadingRestrictions = array( "bogus" => array( 'bogus', "sysop", "protect", "" ) );

		$this->assertEquals( false,
							 $this->title->userCan( 'bogus' ) );
		$this->assertEquals( array( array( "cascadeprotected", 2, "* [[:Bogus]]\n* [[:UnBogus]]\n" ),
									array( "cascadeprotected", 2, "* [[:Bogus]]\n* [[:UnBogus]]\n" ) ),
							 $this->title->getUserPermissionsErrors( 'bogus', $this->user ) );

		$this->assertEquals( true,
							 $this->title->userCan( 'edit' ) );
		$this->assertEquals( array( ),
							 $this->title->getUserPermissionsErrors( 'edit', $this->user ) );

	}

	function testActionPermissions() {
		global $wgUser;
		$wgUser = $this->user;

		$this->setUserPerm( array( "createpage" ) );
		$this->setTitle( NS_MAIN, "test page" );
		$this->title->mTitleProtection['pt_create_perm'] = '';
		$this->title->mTitleProtection['pt_user'] = $this->user->getID();
		$this->title->mTitleProtection['pt_expiry'] = Block::infinity();
		$this->title->mTitleProtection['pt_reason'] = 'test';
		$this->title->mCascadeRestriction = false;

		$this->assertEquals( array( array( 'titleprotected', 'Useruser', 'test' ) ),
							 $this->title->getUserPermissionsErrors( 'create', $this->user ) );
		$this->assertEquals( false,
							 $this->title->userCan( 'create' ) );

		$this->title->mTitleProtection['pt_create_perm'] = 'sysop';
		$this->setUserPerm( array( 'createpage', 'protect' ) );
		$this->assertEquals( array( ),
							 $this->title->getUserPermissionsErrors( 'create', $this->user ) );
		$this->assertEquals( true,
							 $this->title->userCan( 'create' ) );


		$this->setUserPerm( array( 'createpage' ) );
		$this->assertEquals( array( array( 'titleprotected', 'Useruser', 'test' ) ),
							 $this->title->getUserPermissionsErrors( 'create', $this->user ) );
		$this->assertEquals( false,
							 $this->title->userCan( 'create' ) );

		$this->setTitle( NS_MEDIA, "test page" );
		$this->setUserPerm( array( "move" ) );
		$this->assertEquals( false,
							 $this->title->userCan( 'move' ) );
		$this->assertEquals( array( array( 'immobile-source-namespace', 'Media' ) ),
							 $this->title->getUserPermissionsErrors( 'move', $this->user ) );

		$this->setTitle( NS_MAIN, "test page" );
		$this->assertEquals( array( ),
							 $this->title->getUserPermissionsErrors( 'move', $this->user ) );
		$this->assertEquals( true,
							 $this->title->userCan( 'move' ) );

		$this->title->mInterwiki = "no";
		$this->assertEquals( array( array( 'immobile-page' ) ),
							 $this->title->getUserPermissionsErrors( 'move', $this->user ) );
		$this->assertEquals( false,
							 $this->title->userCan( 'move' ) );

		$this->setTitle( NS_MEDIA, "test page" );
		$this->assertEquals( false,
							 $this->title->userCan( 'move-target' ) );
		$this->assertEquals( array( array( 'immobile-target-namespace', 'Media' ) ),
							 $this->title->getUserPermissionsErrors( 'move-target', $this->user ) );

		$this->setTitle( NS_MAIN, "test page" );
		$this->assertEquals( array( ),
							 $this->title->getUserPermissionsErrors( 'move-target', $this->user ) );
		$this->assertEquals( true,
							 $this->title->userCan( 'move-target' ) );

		$this->title->mInterwiki = "no";
		$this->assertEquals( array( array( 'immobile-target-page' ) ),
							 $this->title->getUserPermissionsErrors( 'move-target', $this->user ) );
		$this->assertEquals( false,
							 $this->title->userCan( 'move-target' ) );

	}

	function testUserBlock() {
		global $wgUser, $wgEmailConfirmToEdit, $wgEmailAuthentication;
		$wgEmailConfirmToEdit = true;
		$wgEmailAuthentication = true;
		$wgUser = $this->user;

		$this->setUserPerm( array( "createpage", "move" ) );
		$this->setTitle( NS_MAIN, "test page" );

		# $short
		$this->assertEquals( array( array( 'confirmedittext' ) ),
							 $this->title->getUserPermissionsErrors( 'move-target', $this->user ) );
		$wgEmailConfirmToEdit = false;
		$this->assertEquals( true, $this->title->userCan( 'move-target' ) );

		# $wgEmailConfirmToEdit && !$user->isEmailConfirmed() && $action != 'createaccount'
		$this->assertEquals( array( ),
							 $this->title->getUserPermissionsErrors( 'move-target',
			$this->user ) );

		global $wgLang;
		$prev = time();
		$now = time() + 120;
		$this->user->mBlockedby = $this->user->getId();
		$this->user->mBlock = new Block( '127.0.8.1', $this->user->getId(), $this->user->getId(),
										'no reason given', $prev + 3600, 1, 0 );
		$this->user->mBlock->mTimestamp = 0;
		$this->assertEquals( array( array( 'autoblockedtext',
			'[[User:Useruser|Useruser]]', 'no reason given', '127.0.0.1',
			'Useruser', 0, 'infinite', '127.0.8.1',
			$wgLang->timeanddate( wfTimestamp( TS_MW, $prev ), true ) ) ),
			$this->title->getUserPermissionsErrors( 'move-target',
			$this->user ) );

		$this->assertEquals( false,
							 $this->title->userCan( 'move-target', $this->user ) );

		global $wgLocalTZoffset;
		$wgLocalTZoffset = -60;
		$this->user->mBlockedby = $this->user->getName();
		$this->user->mBlock = new Block( '127.0.8.1', 2, 1, 'no reason given', $now, 0, 10 );
		$this->assertEquals( array( array( 'blockedtext',
			'[[User:Useruser|Useruser]]', 'no reason given', '127.0.0.1',
			'Useruser', 0, '23:00, 31 December 1969', '127.0.8.1',
			$wgLang->timeanddate( wfTimestamp( TS_MW, $now ), true ) ) ),
			$this->title->getUserPermissionsErrors( 'move-target', $this->user ) );

		# $action != 'read' && $action != 'createaccount' && $user->isBlockedFrom( $this )
		#   $user->blockedFor() == ''
		#   $user->mBlock->mExpiry == 'infinity'
	}
}
