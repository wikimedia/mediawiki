<?php

class TitlePermissionTest extends PhpUnit_Framework_TestCase {
	static $title;
	static $user;
	static $anonUser;
	static $userUser;
	static $altUser;
	static $userName;
	static $altUserName;

	function setUp() {
		global $wgLocaltimezone, $wgLocalTZoffset, $wgMemc, $wgContLang, $wgLang, $wgMessageCache;

		$wgMemc = new FakeMemCachedClient;
		$wgMessageCache = new MessageCache( $wgMemc, true, 3600 );
		$wgContLang = $wgLang = Language::factory( 'en' );

		self::$userName = "Useruser";
		self::$altUserName = "Altuseruser";
		date_default_timezone_set( $wgLocaltimezone );
		$wgLocalTZoffset = date( "Z" ) / 60;

		self::$title = Title::makeTitle( NS_MAIN, "Main Page" );
		if ( !isset( self::$userUser ) || !( self::$userUser instanceOf User ) ) {
			self::$userUser = User::newFromName( self::$userName );

			if ( !self::$userUser->getID() ) {
				self::$userUser = User::createNew( self::$userName, array(
					"email" => "test@example.com",
					"real_name" => "Test User" ) );
			}

			self::$altUser = User::newFromName( self::$altUserName );
			if ( !self::$altUser->getID() ) {
				self::$altUser = User::createNew( self::$altUserName, array(
					"email" => "alttest@example.com",
					"real_name" => "Test User Alt" ) );
			}

			self::$anonUser = User::newFromId( 0 );

			self::$user = self::$userUser;
		}
	}

	function tearDown() {
		global $wgMemc, $wgContLang, $wgLang;
		$wgMemc = $wgContLang = $wgLang = null;
	}

	function setUserPerm( $perm ) {
		if ( is_array( $perm ) ) {
			self::$user->mRights = $perm;
		} else {
			self::$user->mRights = array( $perm );
		}
	}

	function setTitle( $ns, $title = "Main_Page" ) {
		self::$title = Title::makeTitle( $ns, $title );
	}

	function setUser( $userName = null ) {
		if ( $userName === 'anon' ) {
			self::$user = self::$anonUser;
		} else if ( $userName === null || $userName === self::$userName ) {
			self::$user = self::$userUser;
		} else {
			self::$user = self::$altUser;
		}

		global $wgUser;
		$wgUser = self::$user;
	}

	function testQuickPermissions() {
		global $wgContLang;
		$prefix = $wgContLang->getNsText( NS_PROJECT );

		$this->setUser( 'anon' );
		$this->setTitle( NS_TALK );
		$this->setUserPerm( "createtalk" );
		$res = self::$title->getUserPermissionsErrors( 'create', self::$user );
		$this->assertEquals( array(), $res );

		$this->setTitle( NS_TALK );
		$this->setUserPerm( "createpage" );
		$res = self::$title->getUserPermissionsErrors( 'create', self::$user );
		$this->assertEquals( array( array( "nocreatetext" ) ), $res );

		$this->setTitle( NS_TALK );
		$this->setUserPerm( "" );
		$res = self::$title->getUserPermissionsErrors( 'create', self::$user );
		$this->assertEquals( array( array( 'nocreatetext' ) ), $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "createpage" );
		$res = self::$title->getUserPermissionsErrors( 'create', self::$user );
		$this->assertEquals( array( ), $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "createtalk" );
		$res = self::$title->getUserPermissionsErrors( 'create', self::$user );
		$this->assertEquals( array( array( 'nocreatetext' ) ), $res );

		$this->setUser( self::$userName );
		$this->setTitle( NS_TALK );
		$this->setUserPerm( "createtalk" );
		$res = self::$title->getUserPermissionsErrors( 'create', self::$user );
		$this->assertEquals( array( ), $res );

		$this->setTitle( NS_TALK );
		$this->setUserPerm( "createpage" );
		$res = self::$title->getUserPermissionsErrors( 'create', self::$user );
		$this->assertEquals( array( array( 'nocreate-loggedin' ) ), $res );

		$this->setTitle( NS_TALK );
		$this->setUserPerm( "" );
		$res = self::$title->getUserPermissionsErrors( 'create', self::$user );
		$this->assertEquals( array( array( 'nocreate-loggedin' ) ), $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "createpage" );
		$res = self::$title->getUserPermissionsErrors( 'create', self::$user );
		$this->assertEquals( array( ), $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "createtalk" );
		$res = self::$title->getUserPermissionsErrors( 'create', self::$user );
		$this->assertEquals( array( array( 'nocreate-loggedin' ) ), $res );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( "" );
		$res = self::$title->getUserPermissionsErrors( 'create', self::$user );
		$this->assertEquals( array( array( 'nocreate-loggedin' ) ), $res );

		$this->setUser( 'anon' );
		$this->setTitle( NS_USER, self::$userName . '' );
		$this->setUserPerm( "" );
		$res = self::$title->getUserPermissionsErrors( 'move', self::$user );
		$this->assertEquals( array( array( 'cant-move-user-page' ), array( 'movenologintext' ) ), $res );

		$this->setTitle( NS_USER, self::$userName . '/subpage' );
		$this->setUserPerm( "" );
		$res = self::$title->getUserPermissionsErrors( 'move', self::$user );
		$this->assertEquals( array( array( 'movenologintext' ) ), $res );

		$this->setTitle( NS_USER, self::$userName . '' );
		$this->setUserPerm( "move-rootuserpages" );
		$res = self::$title->getUserPermissionsErrors( 'move', self::$user );
		$this->assertEquals( array( array( 'movenologintext' ) ), $res );

		$this->setTitle( NS_USER, self::$userName . '/subpage' );
		$this->setUserPerm( "move-rootuserpages" );
		$res = self::$title->getUserPermissionsErrors( 'move', self::$user );
		$this->assertEquals( array( array( 'movenologintext' ) ), $res );

		$this->setTitle( NS_USER, self::$userName . '' );
		$this->setUserPerm( "" );
		$res = self::$title->getUserPermissionsErrors( 'move', self::$user );
		$this->assertEquals( array( array( 'cant-move-user-page' ), array( 'movenologintext' ) ), $res );

		$this->setTitle( NS_USER, self::$userName . '/subpage' );
		$this->setUserPerm( "" );
		$res = self::$title->getUserPermissionsErrors( 'move', self::$user );
		$this->assertEquals( array( array( 'movenologintext' ) ), $res );

		$this->setTitle( NS_USER, self::$userName . '' );
		$this->setUserPerm( "move-rootuserpages" );
		$res = self::$title->getUserPermissionsErrors( 'move', self::$user );
		$this->assertEquals( array( array( 'movenologintext' ) ), $res );

		$this->setTitle( NS_USER, self::$userName . '/subpage' );
		$this->setUserPerm( "move-rootuserpages" );
		$res = self::$title->getUserPermissionsErrors( 'move', self::$user );
		$this->assertEquals( array( array( 'movenologintext' ) ), $res );

		$this->setUser( self::$userName );
		$this->setTitle( NS_FILE, "img.png" );
		$this->setUserPerm( "" );
		$res = self::$title->getUserPermissionsErrors( 'move', self::$user );
		$this->assertEquals( array( array( 'movenotallowedfile' ), array( 'movenotallowed' ) ), $res );

		$this->setTitle( NS_FILE, "img.png" );
		$this->setUserPerm( "movefile" );
		$res = self::$title->getUserPermissionsErrors( 'move', self::$user );
		$this->assertEquals( array( array( 'movenotallowed' ) ), $res );

		$this->setUser( 'anon' );
		$this->setTitle( NS_FILE, "img.png" );
		$this->setUserPerm( "" );
		$res = self::$title->getUserPermissionsErrors( 'move', self::$user );
		$this->assertEquals( array( array( 'movenotallowedfile' ), array( 'movenologintext' ) ), $res );

		$this->setTitle( NS_FILE, "img.png" );
		$this->setUserPerm( "movefile" );
		$res = self::$title->getUserPermissionsErrors( 'move', self::$user );
		$this->assertEquals( array( array( 'movenologintext' ) ), $res );

		$this->setUser( self::$userName );
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

		$this->setUser( self::$userName );
		$this->setUserPerm( "" );
		$this->runGroupPermissions( 'move', array( array( 'movenotallowed' ) ) );

		$this->setUserPerm( "move" );
		$this->runGroupPermissions( 'move', array( ) );

		$this->setUser( 'anon' );
		$this->setUserPerm( 'move' );
		$res = self::$title->getUserPermissionsErrors( 'move-target', self::$user );
		$this->assertEquals( array( ), $res );

		$this->setUserPerm( '' );
		$res = self::$title->getUserPermissionsErrors( 'move-target', self::$user );
		$this->assertEquals( array( array( 'movenotallowed' ) ), $res );

		$this->setTitle( NS_USER );
		$this->setUser( self::$userName );
		$this->setUserPerm( array( "move", "move-rootuserpages" ) );
		$res = self::$title->getUserPermissionsErrors( 'move-target', self::$user );
		$this->assertEquals( array( ), $res );

		$this->setUserPerm( "move" );
		$res = self::$title->getUserPermissionsErrors( 'move-target', self::$user );
		$this->assertEquals( array( array( 'cant-move-to-user-page' ) ), $res );

		$this->setUser( 'anon' );
		$this->setUserPerm( array( "move", "move-rootuserpages" ) );
		$res = self::$title->getUserPermissionsErrors( 'move-target', self::$user );
		$this->assertEquals( array( ), $res );

		$this->setTitle( NS_USER, "User/subpage" );
		$this->setUserPerm( array( "move", "move-rootuserpages" ) );
		$res = self::$title->getUserPermissionsErrors( 'move-target', self::$user );
		$this->assertEquals( array( ), $res );

		$this->setUserPerm( "move" );
		$res = self::$title->getUserPermissionsErrors( 'move-target', self::$user );
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
		$wgUser = self::$user;
   		foreach ( array( "edit", "protect", "" ) as $action ) {
			$this->setUserPerm( null );
			$this->assertEquals( $check[$action][0],
				self::$title->getUserPermissionsErrors( $action, self::$user, true ) );

			global $wgGroupPermissions;
			$old = $wgGroupPermissions;
			$wgGroupPermissions = array();

			$this->assertEquals( $check[$action][1],
				self::$title->getUserPermissionsErrors( $action, self::$user, true ) );
			$wgGroupPermissions = $old;

			$this->setUserPerm( $action );
			$this->assertEquals( $check[$action][2],
				self::$title->getUserPermissionsErrors( $action, self::$user, true ) );

			$this->setUserPerm( $action );
			$this->assertEquals( $check[$action][3],
				self::$title->userCan( $action, true ) );
			$this->assertEquals( $check[$action][3],
				self::$title->quickUserCan( $action, false ) );

			# count( User::getGroupsWithPermissions( $action ) ) < 1
		}
	}

	function runGroupPermissions( $action, $result, $result2 = null ) {
		global $wgGroupPermissions;

		if ( $result2 === null ) $result2 = $result;

		$wgGroupPermissions['autoconfirmed']['move'] = false;
		$wgGroupPermissions['user']['move'] = false;
		$res = self::$title->getUserPermissionsErrors( $action, self::$user );
		$this->assertEquals( $result, $res );

		$wgGroupPermissions['autoconfirmed']['move'] = true;
		$wgGroupPermissions['user']['move'] = false;
		$res = self::$title->getUserPermissionsErrors( $action, self::$user );
		$this->assertEquals( $result2, $res );

		$wgGroupPermissions['autoconfirmed']['move'] = true;
		$wgGroupPermissions['user']['move'] = true;
		$res = self::$title->getUserPermissionsErrors( $action, self::$user );
		$this->assertEquals( $result2, $res );

		$wgGroupPermissions['autoconfirmed']['move'] = false;
		$wgGroupPermissions['user']['move'] = true;
		$res = self::$title->getUserPermissionsErrors( $action, self::$user );
		$this->assertEquals( $result2, $res );
	}

	function testPermissionHooks() { }
	function testSpecialsAndNSPermissions() {
		$this->setUser( self::$userName );
		global $wgUser, $wgContLang;
		$wgUser = self::$user;
		$prefix = $wgContLang->getNsText( NS_PROJECT );

		$this->setTitle( NS_SPECIAL );

		$this->assertEquals( array( array( 'badaccess-group0' ), array( 'ns-specialprotected' ) ),
							 self::$title->getUserPermissionsErrors( 'bogus', self::$user ) );
		$this->assertEquals( array( array( 'badaccess-groups', "*, [[$prefix:Administrators|Administrators]]", 2 ) ),
							 self::$title->getUserPermissionsErrors( 'createaccount', self::$user ) );
		$this->assertEquals( array( array( 'badaccess-group0' ) ),
							 self::$title->getUserPermissionsErrors( 'execute', self::$user ) );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( 'bogus' );
		$this->assertEquals( array( ),
							 self::$title->getUserPermissionsErrors( 'bogus', self::$user ) );

		$this->setTitle( NS_MAIN );
		$this->setUserPerm( '' );
		$this->assertEquals( array( array( 'badaccess-group0' ) ),
							 self::$title->getUserPermissionsErrors( 'bogus', self::$user ) );

		global $wgNamespaceProtection;
		$wgNamespaceProtection[NS_USER] = array ( 'bogus' );
		$this->setTitle( NS_USER );
		$this->setUserPerm( '' );
		$this->assertEquals( array( array( 'badaccess-group0' ), array( 'namespaceprotected', 'User' ) ),
							 self::$title->getUserPermissionsErrors( 'bogus', self::$user ) );

		$this->setTitle( NS_MEDIAWIKI );
		$this->setUserPerm( 'bogus' );
		$this->assertEquals( array( array( 'protectedinterface' ) ),
							 self::$title->getUserPermissionsErrors( 'bogus', self::$user ) );

		$this->setTitle( NS_MEDIAWIKI );
		$this->setUserPerm( 'bogus' );
		$this->assertEquals( array( array( 'protectedinterface' ) ),
							 self::$title->getUserPermissionsErrors( 'bogus', self::$user ) );

		$wgNamespaceProtection = null;
		$this->setUserPerm( 'bogus' );
		$this->assertEquals( array( ),
							 self::$title->getUserPermissionsErrors( 'bogus', self::$user ) );
		$this->assertEquals( true,
							 self::$title->userCan( 'bogus' ) );

		$this->setUserPerm( '' );
		$this->assertEquals( array( array( 'badaccess-group0' ) ),
							 self::$title->getUserPermissionsErrors( 'bogus', self::$user ) );
		$this->assertEquals( false,
							 self::$title->userCan( 'bogus' ) );
	}

	function testCSSandJSPermissions() {
		$this->setUser( self::$userName );
		global $wgUser;
		$wgUser = self::$user;

		$this->setTitle( NS_USER, self::$altUserName . '/test.js' );
		$this->runCSSandJSPermissions(
			array( array( 'badaccess-group0' ), array( 'customcssjsprotected' ) ),
			array( array( 'badaccess-group0' ), array( 'customcssjsprotected'  ) ),
			array( array( 'badaccess-group0' ) ) );

		$this->setTitle( NS_USER, self::$altUserName . '/test.css' );
		$this->runCSSandJSPermissions(
			array( array( 'badaccess-group0' ), array( 'customcssjsprotected' ) ),
			array( array( 'badaccess-group0' ) ),
			array( array( 'badaccess-group0' ),  array( 'customcssjsprotected' ) ) );

		$this->setTitle( NS_USER, self::$altUserName . '/tempo' );
		$this->runCSSandJSPermissions(
			array( array( 'badaccess-group0' ) ),
			array( array( 'badaccess-group0' ) ),
			array( array( 'badaccess-group0' ) ) );
	}

	function runCSSandJSPermissions( $result0, $result1, $result2 ) {
		$this->setUserPerm( '' );
		$this->assertEquals( $result0,
							 self::$title->getUserPermissionsErrors( 'bogus',
																	 self::$user ) );

		$this->setUserPerm( 'editusercss' );
		$this->assertEquals( $result1,
							 self::$title->getUserPermissionsErrors( 'bogus',
																	 self::$user ) );

		$this->setUserPerm( 'edituserjs' );
		$this->assertEquals( $result2,
							 self::$title->getUserPermissionsErrors( 'bogus',
																	 self::$user ) );

		$this->setUserPerm( 'editusercssjs' );
		$this->assertEquals( array( array( 'badaccess-group0' ) ),
							 self::$title->getUserPermissionsErrors( 'bogus',
																	 self::$user ) );

		$this->setUserPerm( array( 'edituserjs', 'editusercss' ) );
		$this->assertEquals( array( array( 'badaccess-group0' ) ),
							 self::$title->getUserPermissionsErrors( 'bogus',
																	 self::$user ) );
	}

	function testPageRestrictions() {
		global $wgUser, $wgContLang;

		$prefix = $wgContLang->getNsText( NS_PROJECT );

		$wgUser = self::$user;
		$this->setTitle( NS_MAIN );
		self::$title->mRestrictionsLoaded = true;
		$this->setUserPerm( "edit" );
		self::$title->mRestrictions = array( "bogus" => array( 'bogus', "sysop", "protect", "" ) );

		$this->assertEquals( array( ),
							 self::$title->getUserPermissionsErrors( 'edit',
																	 self::$user ) );

		$this->assertEquals( true,
							 self::$title->quickUserCan( 'edit', false ) );
		self::$title->mRestrictions = array( "edit" => array( 'bogus', "sysop", "protect", "" ),
										   "bogus" => array( 'bogus', "sysop", "protect", "" ) );

		$this->assertEquals( array( array( 'badaccess-group0' ),
									array( 'protectedpagetext', 'bogus' ),
									array( 'protectedpagetext', 'protect' ),
									array( 'protectedpagetext', 'protect' ) ),
							 self::$title->getUserPermissionsErrors( 'bogus',
																	 self::$user ) );
		$this->assertEquals( array( array( 'protectedpagetext', 'bogus' ),
									array( 'protectedpagetext', 'protect' ),
									array( 'protectedpagetext', 'protect' ) ),
							 self::$title->getUserPermissionsErrors( 'edit',
																	 self::$user ) );
		$this->setUserPerm( "" );
		$this->assertEquals( array( array( 'badaccess-group0' ),
									array( 'protectedpagetext', 'bogus' ),
									array( 'protectedpagetext', 'protect' ),
									array( 'protectedpagetext', 'protect' ) ),
							 self::$title->getUserPermissionsErrors( 'bogus',
																	 self::$user ) );
		$this->assertEquals( array( array( 'badaccess-groups', "*, [[$prefix:Users|Users]]", 2 ),
									array( 'protectedpagetext', 'bogus' ),
									array( 'protectedpagetext', 'protect' ),
									array( 'protectedpagetext', 'protect' ) ),
							 self::$title->getUserPermissionsErrors( 'edit',
																	 self::$user ) );
		$this->setUserPerm( array( "edit", "editprotected" ) );
		$this->assertEquals( array( array( 'badaccess-group0' ),
									array( 'protectedpagetext', 'bogus' ),
									array( 'protectedpagetext', 'protect' ),
									array( 'protectedpagetext', 'protect' ) ),
							 self::$title->getUserPermissionsErrors( 'bogus',
																	 self::$user ) );
		$this->assertEquals( array(  ),
							 self::$title->getUserPermissionsErrors( 'edit',
																	 self::$user ) );
		self::$title->mCascadeRestriction = true;
		$this->assertEquals( false,
							 self::$title->quickUserCan( 'bogus', false ) );
		$this->assertEquals( false,
							 self::$title->quickUserCan( 'edit', false ) );
		$this->assertEquals( array( array( 'badaccess-group0' ),
									array( 'protectedpagetext', 'bogus' ),
									array( 'protectedpagetext', 'protect' ),
									array( 'protectedpagetext', 'protect' ) ),
							 self::$title->getUserPermissionsErrors( 'bogus',
																	 self::$user ) );
		$this->assertEquals( array( array( 'protectedpagetext', 'bogus' ),
									array( 'protectedpagetext', 'protect' ),
									array( 'protectedpagetext', 'protect' ) ),
							 self::$title->getUserPermissionsErrors( 'edit',
																	 self::$user ) );
	}

	function testCascadingSourcesRestrictions() {
		global $wgUser;
		$wgUser = self::$user;
		$this->setTitle( NS_MAIN, "test page" );
		$this->setUserPerm( array( "edit", "bogus" ) );

		self::$title->mCascadeSources = array( Title::makeTitle( NS_MAIN, "Bogus" ), Title::makeTitle( NS_MAIN, "UnBogus" ) );
		self::$title->mCascadingRestrictions = array( "bogus" => array( 'bogus', "sysop", "protect", "" ) );

		$this->assertEquals( false,
							 self::$title->userCan( 'bogus' ) );
		$this->assertEquals( array( array( "cascadeprotected", 2, "* [[:Bogus]]\n* [[:UnBogus]]\n" ),
									array( "cascadeprotected", 2, "* [[:Bogus]]\n* [[:UnBogus]]\n" ) ),
							 self::$title->getUserPermissionsErrors( 'bogus', self::$user ) );

		$this->assertEquals( true,
							 self::$title->userCan( 'edit' ) );
		$this->assertEquals( array( ),
							 self::$title->getUserPermissionsErrors( 'edit', self::$user ) );

	}

	function testActionPermissions() {
		global $wgUser;
		$wgUser = self::$user;

		$this->setUserPerm( array( "createpage" ) );
		$this->setTitle( NS_MAIN, "test page" );
		self::$title->mTitleProtection['pt_create_perm'] = '';
		self::$title->mTitleProtection['pt_user'] = self::$user->getID();
		self::$title->mTitleProtection['pt_expiry'] = Block::infinity();
		self::$title->mTitleProtection['pt_reason'] = 'test';
		self::$title->mCascadeRestriction = false;

		$this->assertEquals( array( array( 'titleprotected', 'Useruser', 'test' ) ),
							 self::$title->getUserPermissionsErrors( 'create', self::$user ) );
		$this->assertEquals( false,
							 self::$title->userCan( 'create' ) );

		self::$title->mTitleProtection['pt_create_perm'] = 'sysop';
		$this->setUserPerm( array( 'createpage', 'protect' ) );
		$this->assertEquals( array( ),
							 self::$title->getUserPermissionsErrors( 'create', self::$user ) );
		$this->assertEquals( true,
							 self::$title->userCan( 'create' ) );


		$this->setUserPerm( array( 'createpage' ) );
		$this->assertEquals( array( array( 'titleprotected', 'Useruser', 'test' ) ),
							 self::$title->getUserPermissionsErrors( 'create', self::$user ) );
		$this->assertEquals( false,
							 self::$title->userCan( 'create' ) );

		$this->setTitle( NS_MEDIA, "test page" );
		$this->setUserPerm( array( "move" ) );
		$this->assertEquals( false,
							 self::$title->userCan( 'move' ) );
		$this->assertEquals( array( array( 'immobile-source-namespace', 'Media' ) ),
							 self::$title->getUserPermissionsErrors( 'move', self::$user ) );

		$this->setTitle( NS_MAIN, "test page" );
		$this->assertEquals( array( ),
							 self::$title->getUserPermissionsErrors( 'move', self::$user ) );
		$this->assertEquals( true,
							 self::$title->userCan( 'move' ) );

		self::$title->mInterwiki = "no";
		$this->assertEquals( array( array( 'immobile-page' ) ),
							 self::$title->getUserPermissionsErrors( 'move', self::$user ) );
		$this->assertEquals( false,
							 self::$title->userCan( 'move' ) );

		$this->setTitle( NS_MEDIA, "test page" );
		$this->assertEquals( false,
							 self::$title->userCan( 'move-target' ) );
		$this->assertEquals( array( array( 'immobile-target-namespace', 'Media' ) ),
							 self::$title->getUserPermissionsErrors( 'move-target', self::$user ) );

		$this->setTitle( NS_MAIN, "test page" );
		$this->assertEquals( array( ),
							 self::$title->getUserPermissionsErrors( 'move-target', self::$user ) );
		$this->assertEquals( true,
							 self::$title->userCan( 'move-target' ) );

		self::$title->mInterwiki = "no";
		$this->assertEquals( array( array( 'immobile-target-page' ) ),
							 self::$title->getUserPermissionsErrors( 'move-target', self::$user ) );
		$this->assertEquals( false,
							 self::$title->userCan( 'move-target' ) );

	}

	function testUserBlock() {
		global $wgUser, $wgEmailConfirmToEdit, $wgEmailAuthentication;
		$wgEmailConfirmToEdit = true;
		$wgEmailAuthentication = true;
		$wgUser = self::$user;

		$this->setUserPerm( array( "createpage", "move" ) );
		$this->setTitle( NS_MAIN, "test page" );

		# $short
		$this->assertEquals( array( array( 'confirmedittext' ) ),
							 self::$title->getUserPermissionsErrors( 'move-target', self::$user ) );
		$this->assertEquals( true, self::$title->userCan( 'move-target' ) );

		$wgEmailConfirmToEdit = false;
		# $wgEmailConfirmToEdit && !$user->isEmailConfirmed() && $action != 'createaccount'
		$this->assertEquals( array( ),
							 self::$title->getUserPermissionsErrors( 'move-target',
			self::$user ) );

		global $wgLang;
		$prev = time();
		$now = time() + 120;
		self::$user->mBlockedby = self::$user->getId();
		self::$user->mBlock = new Block( '127.0.8.1', self::$user->getId(), self::$user->getId(),
										'no reason given', $prev + 3600, 1, 0 );
		self::$user->mBlock->mTimestamp = 0;
		$this->assertEquals( array( array( 'autoblockedtext',
			'[[User:Useruser|Useruser]]', 'no reason given', '127.0.0.1',
			'Useruser', 0, 'infinite', '127.0.8.1',
			$wgLang->timeanddate( wfTimestamp( TS_MW, $prev ), true ) ) ),
			self::$title->getUserPermissionsErrors( 'move-target',
			self::$user ) );

		$this->assertEquals( true,
							 self::$title->userCan( 'move-target', self::$user ) );

		global $wgLocalTZoffset;
		$wgLocalTZoffset = -60;
		self::$user->mBlockedby = self::$user->getName();
		self::$user->mBlock = new Block( '127.0.8.1', 2, 1, 'no reason given', $now, 0, 10 );
		$this->assertEquals( array( array( 'blockedtext',
			'[[User:Useruser|Useruser]]', 'no reason given', '127.0.0.1',
			'Useruser', 0, '23:00, 31 December 1969', '127.0.8.1',
			$wgLang->timeanddate( wfTimestamp( TS_MW, $now ), true ) ) ),
			self::$title->getUserPermissionsErrors( 'move-target', self::$user ) );

		# $action != 'read' && $action != 'createaccount' && $user->isBlockedFrom( $this )
		#   $user->blockedFor() == ''
		#   $user->mBlock->mExpiry == 'infinity'
	}
}
