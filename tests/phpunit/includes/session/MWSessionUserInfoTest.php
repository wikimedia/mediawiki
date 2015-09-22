<?php

/**
 * @group Session
 * @group Database
 * @covers MWSessionUserInfo
 */
class MWSessionUserInfoTest extends MediaWikiTestCase {

	public function testNewAnonymous() {
		$userinfo = MWSessionUserInfo::newAnonymous();

		$this->assertTrue( $userinfo->isAnon() );
		$this->assertTrue( $userinfo->isAuthenticated() );
		$this->assertSame( 0, $userinfo->getId() );
		$this->assertSame( null, $userinfo->getName() );
		$this->assertSame( null, $userinfo->getToken() );
		$this->assertNotNull( $userinfo->getUser() );
		$this->assertSame( $userinfo, $userinfo->authenticated() );
		$this->assertSame( '<anon>', (string)$userinfo );
	}

	public function testNewFromId() {
		$id = wfGetDB( DB_MASTER )->selectField( 'user', 'MAX(user_id)' ) + 1;
		try {
			MWSessionUserInfo::newFromId( $id );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid ID', $ex->getMessage() );
		}

		$user = User::newFromName( 'UTSysop' );
		$userinfo = MWSessionUserInfo::newFromId( $user->getId() );
		$this->assertFalse( $userinfo->isAnon() );
		$this->assertFalse( $userinfo->isAuthenticated() );
		$this->assertSame( $user->getId(), $userinfo->getId() );
		$this->assertSame( $user->getName(), $userinfo->getName() );
		$this->assertSame( $user->getToken( true ), $userinfo->getToken() );
		$this->assertInstanceOf( 'User', $userinfo->getUser() );
		$userinfo2 = $userinfo->authenticated();
		$this->assertNotSame( $userinfo2, $userinfo );
		$this->assertSame( "<-:{$user->getId()}:{$user->getName()}>", (string)$userinfo );

		$this->assertFalse( $userinfo2->isAnon() );
		$this->assertTrue( $userinfo2->isAuthenticated() );
		$this->assertSame( $user->getId(), $userinfo2->getId() );
		$this->assertSame( $user->getName(), $userinfo2->getName() );
		$this->assertSame( $user->getToken( true ), $userinfo2->getToken() );
		$this->assertInstanceOf( 'User', $userinfo2->getUser() );
		$this->assertSame( $userinfo2, $userinfo2->authenticated() );
		$this->assertSame( "<+:{$user->getId()}:{$user->getName()}>", (string)$userinfo2 );

		$userinfo = MWSessionUserInfo::newFromId( $user->getId(), true );
		$this->assertTrue( $userinfo->isAuthenticated() );
		$this->assertSame( $userinfo, $userinfo->authenticated() );
	}

	public function testNewFromName() {
		try {
			MWSessionUserInfo::newFromName( '<bad name>' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid user name', $ex->getMessage() );
		}

		// User name that exists
		$user = User::newFromName( 'UTSysop' );
		$userinfo = MWSessionUserInfo::newFromName( $user->getName() );
		$this->assertFalse( $userinfo->isAnon() );
		$this->assertFalse( $userinfo->isAuthenticated() );
		$this->assertSame( $user->getId(), $userinfo->getId() );
		$this->assertSame( $user->getName(), $userinfo->getName() );
		$this->assertSame( $user->getToken( true ), $userinfo->getToken() );
		$this->assertInstanceOf( 'User', $userinfo->getUser() );
		$userinfo2 = $userinfo->authenticated();
		$this->assertNotSame( $userinfo2, $userinfo );
		$this->assertSame( "<-:{$user->getId()}:{$user->getName()}>", (string)$userinfo );

		$this->assertFalse( $userinfo2->isAnon() );
		$this->assertTrue( $userinfo2->isAuthenticated() );
		$this->assertSame( $user->getId(), $userinfo2->getId() );
		$this->assertSame( $user->getName(), $userinfo2->getName() );
		$this->assertSame( $user->getToken( true ), $userinfo2->getToken() );
		$this->assertInstanceOf( 'User', $userinfo2->getUser() );
		$this->assertSame( $userinfo2, $userinfo2->authenticated() );
		$this->assertSame( "<+:{$user->getId()}:{$user->getName()}>", (string)$userinfo2 );

		$userinfo = MWSessionUserInfo::newFromName( $user->getName(), true );
		$this->assertTrue( $userinfo->isAuthenticated() );
		$this->assertSame( $userinfo, $userinfo->authenticated() );

		// User name that does not exist should still be non-anon
		$user = User::newFromName( 'DoesNotExist' );
		$this->assertSame( 0, $user->getId(), 'sanity check' );
		$userinfo = MWSessionUserInfo::newFromName( $user->getName() );
		$this->assertFalse( $userinfo->isAnon() );
		$this->assertFalse( $userinfo->isAuthenticated() );
		$this->assertSame( $user->getId(), $userinfo->getId() );
		$this->assertSame( $user->getName(), $userinfo->getName() );
		$this->assertSame( null, $userinfo->getToken() );
		$this->assertInstanceOf( 'User', $userinfo->getUser() );
		$userinfo2 = $userinfo->authenticated();
		$this->assertNotSame( $userinfo2, $userinfo );
		$this->assertSame( "<-:{$user->getId()}:{$user->getName()}>", (string)$userinfo );

		$this->assertFalse( $userinfo2->isAnon() );
		$this->assertTrue( $userinfo2->isAuthenticated() );
		$this->assertSame( $user->getId(), $userinfo2->getId() );
		$this->assertSame( $user->getName(), $userinfo2->getName() );
		$this->assertSame( null, $userinfo2->getToken() );
		$this->assertInstanceOf( 'User', $userinfo2->getUser() );
		$this->assertSame( $userinfo2, $userinfo2->authenticated() );
		$this->assertSame( "<+:{$user->getId()}:{$user->getName()}>", (string)$userinfo2 );

		$userinfo = MWSessionUserInfo::newFromName( $user->getName(), true );
		$this->assertTrue( $userinfo->isAuthenticated() );
		$this->assertSame( $userinfo, $userinfo->authenticated() );
	}

	public function testNewFromUser() {
		// User that exists
		$user = User::newFromName( 'UTSysop' );
		$userinfo = MWSessionUserInfo::newFromUser( $user );
		$this->assertFalse( $userinfo->isAnon() );
		$this->assertFalse( $userinfo->isAuthenticated() );
		$this->assertSame( $user->getId(), $userinfo->getId() );
		$this->assertSame( $user->getName(), $userinfo->getName() );
		$this->assertSame( $user->getToken( true ), $userinfo->getToken() );
		$this->assertSame( $user, $userinfo->getUser() );
		$userinfo2 = $userinfo->authenticated();
		$this->assertNotSame( $userinfo2, $userinfo );
		$this->assertSame( "<-:{$user->getId()}:{$user->getName()}>", (string)$userinfo );

		$this->assertFalse( $userinfo2->isAnon() );
		$this->assertTrue( $userinfo2->isAuthenticated() );
		$this->assertSame( $user->getId(), $userinfo2->getId() );
		$this->assertSame( $user->getName(), $userinfo2->getName() );
		$this->assertSame( $user->getToken( true ), $userinfo2->getToken() );
		$this->assertSame( $user, $userinfo2->getUser() );
		$this->assertSame( $userinfo2, $userinfo2->authenticated() );
		$this->assertSame( "<+:{$user->getId()}:{$user->getName()}>", (string)$userinfo2 );

		$userinfo = MWSessionUserInfo::newFromUser( $user, true );
		$this->assertTrue( $userinfo->isAuthenticated() );
		$this->assertSame( $userinfo, $userinfo->authenticated() );

		// User name that does not exist should still be non-anon
		$user = User::newFromName( 'DoesNotExist' );
		$this->assertSame( 0, $user->getId(), 'sanity check' );
		$userinfo = MWSessionUserInfo::newFromUser( $user );
		$this->assertFalse( $userinfo->isAnon() );
		$this->assertFalse( $userinfo->isAuthenticated() );
		$this->assertSame( $user->getId(), $userinfo->getId() );
		$this->assertSame( $user->getName(), $userinfo->getName() );
		$this->assertSame( null, $userinfo->getToken() );
		$this->assertSame( $user, $userinfo->getUser() );
		$userinfo2 = $userinfo->authenticated();
		$this->assertNotSame( $userinfo2, $userinfo );
		$this->assertSame( "<-:{$user->getId()}:{$user->getName()}>", (string)$userinfo );

		$this->assertFalse( $userinfo2->isAnon() );
		$this->assertTrue( $userinfo2->isAuthenticated() );
		$this->assertSame( $user->getId(), $userinfo2->getId() );
		$this->assertSame( $user->getName(), $userinfo2->getName() );
		$this->assertSame( null, $userinfo2->getToken() );
		$this->assertSame( $user, $userinfo2->getUser() );
		$this->assertSame( $userinfo2, $userinfo2->authenticated() );
		$this->assertSame( "<+:{$user->getId()}:{$user->getName()}>", (string)$userinfo2 );

		$userinfo = MWSessionUserInfo::newFromUser( $user, true );
		$this->assertTrue( $userinfo->isAuthenticated() );
		$this->assertSame( $userinfo, $userinfo->authenticated() );
	}

}
