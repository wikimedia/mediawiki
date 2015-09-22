<?php

namespace MediaWiki\Session;

use Psr\Log\LogLevel;
use MediaWikiTestCase;

/**
 * @group Session
 * @group Database
 * @covers MediaWiki\Session\SessionInfo
 */
class SessionInfoTest extends MediaWikiTestCase {

	public function testBasics() {
		$anonInfo = UserInfo::newAnonymous();
		$userInfo = UserInfo::newFromName( 'UTSysop', true );
		$unverifiedUserInfo = UserInfo::newFromName( 'UTSysop', false );

		try {
			new SessionInfo( SessionInfo::MIN_PRIORITY - 1, array() );
			$this->fail( 'Expected exception not thrown', 'priority < min' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid priority', $ex->getMessage(), 'priority < min' );
		}

		try {
			new SessionInfo( SessionInfo::MAX_PRIORITY + 1, array() );
			$this->fail( 'Expected exception not thrown', 'priority > max' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid priority', $ex->getMessage(), 'priority > max' );
		}

		try {
			new SessionInfo( SessionInfo::MIN_PRIORITY, array( 'id' => 'ABC?' ) );
			$this->fail( 'Expected exception not thrown', 'bad session ID' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid session ID', $ex->getMessage(), 'bad session ID' );
		}

		try {
			new SessionInfo( SessionInfo::MIN_PRIORITY, array( 'userInfo' => new \stdClass ) );
			$this->fail( 'Expected exception not thrown', 'bad userInfo' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid userInfo', $ex->getMessage(), 'bad userInfo' );
		}

		try {
			new SessionInfo( SessionInfo::MIN_PRIORITY, array() );
			$this->fail( 'Expected exception not thrown', 'no provider, no id' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame( 'Must supply an ID when no provider is given', $ex->getMessage(),
				'no provider, no id' );
		}

		$manager = new SessionManager();
		$provider = $this->getMockBuilder( 'MediaWiki\\Session\\SessionProvider' )
			->setMethods( array( 'persistsSessionId', 'canChangeUser', '__toString' ) )
			->getMockForAbstractClass();
		$provider->setManager( $manager );
		$provider->expects( $this->any() )->method( 'persistsSessionId' )
			->will( $this->returnValue( true ) );
		$provider->expects( $this->any() )->method( 'canChangeUser' )
			->will( $this->returnValue( false ) );
		$provider->expects( $this->any() )->method( '__toString' )
			->will( $this->returnValue( 'Mock' ) );

		try {
			new SessionInfo( SessionInfo::MIN_PRIORITY, array(
				'provider' => $provider
			) );
			$this->fail( 'Expected exception not thrown', 'immutable provider, no user' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'Provider Mock cannot set user info, but no verified userInfo was provided',
				$ex->getMessage(),
				'immutable provider, no userInfo'
			);
		}

		try {
			new SessionInfo( SessionInfo::MIN_PRIORITY, array(
				'provider' => $provider,
				'userInfo' => $unverifiedUserInfo,
			) );
			$this->fail( 'Expected exception not thrown', 'immutable provider, unverified userInfo' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'Provider Mock cannot set user info, but no verified userInfo was provided',
				$ex->getMessage(),
				'immutable provider, unverified userInfo'
			);
		}

		$provider = $this->getMockBuilder( 'MediaWiki\\Session\\SessionProvider' )
			->setMethods( array( 'persistsSessionId', 'canChangeUser', '__toString' ) )
			->getMockForAbstractClass();
		$provider->setManager( $manager );
		$provider->expects( $this->any() )->method( 'persistsSessionId' )
			->will( $this->returnValue( true ) );
		$provider->expects( $this->any() )->method( 'canChangeUser' )
			->will( $this->returnValue( true ) );
		$provider->expects( $this->any() )->method( '__toString' )
			->will( $this->returnValue( 'Mock' ) );

		try {
			new SessionInfo( SessionInfo::MIN_PRIORITY, array(
				'provider' => $provider,
				'userInfo' => $anonInfo,
				'metadata' => 'foo',
			) );
			$this->fail( 'Expected exception not thrown', 'bad metadata' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid metadata', $ex->getMessage(), 'bad metadata' );
		}

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'provider' => $provider,
			'userInfo' => $anonInfo
		) );
		$this->assertSame( $provider, $info->getProvider() );
		$this->assertNotNull( $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $anonInfo, $info->getUserInfo() );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertFalse( $info->wasPersisted() );
		$this->assertFalse( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertNull( $info->getProviderMetadata() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'provider' => $provider,
			'userInfo' => $unverifiedUserInfo,
			'metadata' => array( 'Foo' ),
		) );
		$this->assertSame( $provider, $info->getProvider() );
		$this->assertNotNull( $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $unverifiedUserInfo, $info->getUserInfo() );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertFalse( $info->wasPersisted() );
		$this->assertFalse( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertSame( array( 'Foo' ), $info->getProviderMetadata() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'provider' => $provider,
			'userInfo' => $userInfo
		) );
		$this->assertSame( $provider, $info->getProvider() );
		$this->assertNotNull( $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $userInfo, $info->getUserInfo() );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertFalse( $info->wasPersisted() );
		$this->assertTrue( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertNull( $info->getProviderMetadata() );

		$id = $manager->generateSessionId();

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'provider' => $provider,
			'id' => $id,
			'persisted' => true,
			'userInfo' => $anonInfo
		) );
		$this->assertSame( $provider, $info->getProvider() );
		$this->assertSame( $id, $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $anonInfo, $info->getUserInfo() );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertTrue( $info->wasPersisted() );
		$this->assertFalse( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertNull( $info->getProviderMetadata() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
		) );
		$this->assertSame( $provider, $info->getProvider() );
		$this->assertSame( $id, $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $userInfo, $info->getUserInfo() );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertFalse( $info->wasPersisted() );
		$this->assertTrue( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertNull( $info->getProviderMetadata() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'id' => $id,
			'persisted' => true,
			'userInfo' => $userInfo,
			'metadata' => array( 'Foo' ),
		) );
		$this->assertSame( $id, $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $userInfo, $info->getUserInfo() );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertTrue( $info->wasPersisted() );
		$this->assertFalse( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertNull( $info->getProviderMetadata() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'id' => $id,
			'remembered' => true,
			'userInfo' => $userInfo,
		) );
		$this->assertFalse( $info->wasRemembered(), 'no provider' );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'provider' => $provider,
			'id' => $id,
			'remembered' => true,
		) );
		$this->assertFalse( $info->wasRemembered(), 'no user' );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'provider' => $provider,
			'id' => $id,
			'remembered' => true,
			'userInfo' => $anonInfo,
		) );
		$this->assertFalse( $info->wasRemembered(), 'anonymous user' );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'provider' => $provider,
			'id' => $id,
			'remembered' => true,
			'userInfo' => $unverifiedUserInfo,
		) );
		$this->assertFalse( $info->wasRemembered(), 'unverified user' );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'provider' => $provider,
			'id' => $id,
			'remembered' => false,
			'userInfo' => $userInfo,
		) );
		$this->assertFalse( $info->wasRemembered(), 'specific override' );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'id' => $id,
			'idIsSafe' => true,
		) );
		$this->assertSame( $id, $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertTrue( $info->isIdSafe() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'id' => $id,
			'forceHTTPS' => 1,
		) );
		$this->assertTrue( $info->forceHTTPS() );

		$this->assertSame(
			'[' . SessionInfo::MIN_PRIORITY . "]null<null>$id",
			(string)$info,
			'toString'
		);

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'persisted' => true,
			'userInfo' => $userInfo
		) );
		$this->assertSame(
			'[' . SessionInfo::MIN_PRIORITY . "]Mock<+:{$userInfo->getId()}:UTSysop>$id",
			(string)$info,
			'toString'
		);

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'persisted' => true,
			'userInfo' => $unverifiedUserInfo
		) );
		$this->assertSame(
			'[' . SessionInfo::MIN_PRIORITY . "]Mock<-:{$userInfo->getId()}:UTSysop>$id",
			(string)$info,
			'toString'
		);
	}

	public function testCompare() {
		$id = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$info1 = new SessionInfo( SessionInfo::MIN_PRIORITY + 1, array( 'id' => $id ) );
		$info2 = new SessionInfo( SessionInfo::MIN_PRIORITY + 2, array( 'id' => $id ) );

		$this->assertTrue( SessionInfo::compare( $info1, $info2 ) < 0, '<' );
		$this->assertTrue( SessionInfo::compare( $info2, $info1 ) > 0, '>' );
		$this->assertTrue( SessionInfo::compare( $info1, $info1 ) === 0, '==' );
	}
}
