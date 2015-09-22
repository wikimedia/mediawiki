<?php

namespace MediaWiki\Session;

use MediaWikiTestCase;

/**
 * @group Session
 * @group Database
 * @covers MediaWiki\Session\SessionInfo
 * @uses MediaWiki\Session\UserInfo
 */
class SessionInfoTest extends MediaWikiTestCase {

	/**
	 * @uses MediaWiki\Session\SessionManager
	 * @uses MediaWiki\Session\SessionProvider
	 */
	public function testBasics() {
		$anon = UserInfo::newAnonymous();
		$user = UserInfo::newFromName( 'UTSysop', true );
		$unauthUser = UserInfo::newFromName( 'UTSysop', false );

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
			new SessionInfo( SessionInfo::MIN_PRIORITY, array( 'user' => new \stdClass ) );
			$this->fail( 'Expected exception not thrown', 'bad user' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid user', $ex->getMessage(), 'bad user' );
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
			->setMethods( array( 'persistsUser', '__toString' ) )
			->getMockForAbstractClass();
		$provider->setManager( $manager );
		$provider->expects( $this->any() )->method( 'persistsUser' )
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
				'Provider Mock cannot set user info, but no authenticated user was provided',
				$ex->getMessage(),
				'immutable provider, no user'
			);
		}

		try {
			new SessionInfo( SessionInfo::MIN_PRIORITY, array(
				'provider' => $provider,
				'user' => $unauthUser,
			) );
			$this->fail( 'Expected exception not thrown', 'immutable provider, unauth user' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'Provider Mock cannot set user info, but no authenticated user was provided',
				$ex->getMessage(),
				'immutable provider, unauth user'
			);
		}

		$provider = $this->getMockBuilder( 'MediaWiki\\Session\\SessionProvider' )
			->setMethods( array( 'persistsUser', '__toString' ) )
			->getMockForAbstractClass();
		$provider->setManager( $manager );
		$provider->expects( $this->any() )->method( 'persistsUser' )
			->will( $this->returnValue( true ) );
		$provider->expects( $this->any() )->method( '__toString' )
			->will( $this->returnValue( 'Mock' ) );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'provider' => $provider,
			'user' => $anon
		) );
		$this->assertSame( $provider, $info->getProvider() );
		$this->assertNotNull( $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $anon, $info->getUser() );
		$this->assertFalse( $info->wasPersisted() );
		$this->assertFalse( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'provider' => $provider,
			'user' => $unauthUser,
		) );
		$this->assertSame( $provider, $info->getProvider() );
		$this->assertNotNull( $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $unauthUser, $info->getUser() );
		$this->assertFalse( $info->wasPersisted() );
		$this->assertFalse( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'provider' => $provider,
			'user' => $user
		) );
		$this->assertSame( $provider, $info->getProvider() );
		$this->assertNotNull( $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $user, $info->getUser() );
		$this->assertFalse( $info->wasPersisted() );
		$this->assertTrue( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );

		$id = $manager->generateSessionId();

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $anon
		) );
		$this->assertSame( $provider, $info->getProvider() );
		$this->assertSame( $id, $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $anon, $info->getUser() );
		$this->assertTrue( $info->wasPersisted() );
		$this->assertFalse( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertSame( $provider, $info->getProvider() );
		$this->assertSame( $id, $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $user, $info->getUser() );
		$this->assertTrue( $info->wasPersisted() );
		$this->assertTrue( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'id' => $id,
			'user' => $user
		) );
		$this->assertSame( $id, $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $user, $info->getUser() );
		$this->assertFalse( $info->wasPersisted() );
		$this->assertFalse( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );

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
			'user' => $user
		) );
		$this->assertSame(
			'[' . SessionInfo::MIN_PRIORITY . "]Mock<+:{$user->getId()}:UTSysop>$id",
			(string)$info,
			'toString'
		);

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $unauthUser
		) );
		$this->assertSame(
			'[' . SessionInfo::MIN_PRIORITY . "]Mock<-:{$user->getId()}:UTSysop>$id",
			(string)$info,
			'toString'
		);
	}

	/**
	 * @uses MediaWiki\Session\SessionManager::validateSessionId
	 */
	public function testCompare() {
		$id = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$info1 = new SessionInfo( SessionInfo::MIN_PRIORITY + 1, array( 'id' => $id ) );
		$info2 = new SessionInfo( SessionInfo::MIN_PRIORITY + 2, array( 'id' => $id ) );

		$this->assertTrue( SessionInfo::compare( $info1, $info2 ) < 0, '<' );
		$this->assertTrue( SessionInfo::compare( $info2, $info1 ) > 0, '>' );
		$this->assertTrue( SessionInfo::compare( $info1, $info1 ) === 0, '==' );
	}

	/**
	 * @uses MediaWiki\Session\SessionManager
	 * @uses MediaWiki\Session\SessionProvider
	 */
	public function testLoadFromStore() {
		$manager = new SessionManager();
		$store = new \HashBagOStuff();
		$logger = new \Psr\Log\NullLogger();
		$request = new \FauxRequest();

		$user = UserInfo::newFromName( 'UTSysop', true );
		$unauthUser = UserInfo::newFromName( 'UTSysop', false );

		$id = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$dataKey = wfMemcKey( 'MWSession', 'data', $id );
		$metaKey = wfMemcKey( 'MWSession', 'metadata', $id );
		$metadata = array(
			'userId' => $user->getId(),
			'userName' => $user->getName(),
			'userToken' => $user->getToken( true ),
			'provider' => 'Mock',
		);

		$provider = $this->getMockBuilder( 'MediaWiki\\Session\\SessionProvider' )
			->setMethods( array( '__toString' ) )
			->getMockForAbstractClass();
		$provider->setManager( $manager );
		$provider->expects( $this->any() )->method( 'persistsSessionId' )
			->will( $this->returnValue( true ) );
		$provider->expects( $this->any() )->method( '__toString' )
			->will( $this->returnValue( 'Mock' ) );

		\TestingAccessWrapper::newFromObject( $manager )->sessionProviders = array(
			(string)$provider => $provider
		);

		// No metadata, basic usage
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );

		// Unauthenticated user, no metadata
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $unauthUser
		) );
		$this->assertSame( $unauthUser, $info->getUser() );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );

		// No metadata, missing data
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'id' => $id,
			'user' => $user
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertInstanceOf( 'MediaWiki\\Session\\UserInfo', $info->getUser() );
		$this->assertTrue( $info->getUser()->isAuthenticated() );
		$this->assertTrue( $info->getUser()->isAnon() );

		// Incomplete/bad metadata
		$store->set( $dataKey, array() );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );

		$store->delete( $dataKey );
		$store->set( $metaKey, $metadata );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );

		$store->set( $dataKey, true );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );

		$store->set( $dataKey, array() );
		$store->set( $metaKey, true );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );

		foreach ( $metadata as $key => $dummy ) {
			$tmp = $metadata;
			unset( $tmp[$key] );
			$store->set( $metaKey, $tmp );
			$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		}

		// Basic usage with metadata
		$store->set( $metaKey, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );

		// Mismatched provider
		$store->set( $metaKey, array( 'provider' => 'Bad' ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );

		// Unknown provider
		$store->set( $metaKey, array( 'provider' => 'Bad' ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'id' => $id,
			'user' => $user
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );

		// Fill in provider
		$store->set( $metaKey, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'id' => $id,
			'user' => $user
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );

		// Bad user metadata
		$store->set( $metaKey, array( 'userId' => -1, 'userToken' => null ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );

		$store->set(
			$metaKey, array( 'userId' => 0, 'userName' => '<X>', 'userToken' => null ) + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );

		// Mismatched user by ID
		$store->set(
			$metaKey, array( 'userId' => $user->getId() + 1, 'userToken' => null ) + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );

		// Mismatched user by name
		$store->set(
			$metaKey, array( 'userId' => 0, 'userName' => 'X', 'userToken' => null ) + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );

		// Mismatched anon user
		$store->set(
			$metaKey, array( 'userId' => 0, 'userName' => null, 'userToken' => null ) + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );

		// Lookup user by ID
		$store->set( $metaKey, array( 'userToken' => null ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( $user->getId(), $info->getUser()->getId() );

		// Lookup user by name
		$store->set(
			$metaKey, array( 'userId' => 0, 'userName' => 'UTSysop', 'userToken' => null ) + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( $user->getId(), $info->getUser()->getId() );

		// Lookup anonymous user
		$store->set(
			$metaKey, array( 'userId' => 0, 'userName' => null, 'userToken' => null ) + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->getUser()->isAnon() );

		// Unauthenticated user with metadata
		$store->set( $metaKey, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $unauthUser
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->getUser()->isAuthenticated() );
		$this->assertSame( $unauthUser->getId(), $info->getUser()->getId() );
		$this->assertSame( $unauthUser->getName(), $info->getUser()->getName() );

		// Unauthenticated user with metadata
		$store->set( $metaKey, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $unauthUser
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->getUser()->isAuthenticated() );
		$this->assertSame( $unauthUser->getId(), $info->getUser()->getId() );
		$this->assertSame( $unauthUser->getName(), $info->getUser()->getName() );

		// Wrong token
		$store->set( $metaKey, array( 'userToken' => 'Bad' ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );

		// Remember from session
		$store->set( $metaKey, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertFalse( $info->wasRemembered() );

		$store->set( $metaKey, array( 'remember' => true ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->wasRemembered() );

		$store->set( $metaKey, array( 'remember' => false ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->wasRemembered() );

		// forceHTTPS from session
		$store->set( $metaKey, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertFalse( $info->forceHTTPS() );

		$store->set( $metaKey, array( 'forceHTTPS' => true ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->forceHTTPS() );

		$store->set( $metaKey, array( 'forceHTTPS' => false ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user,
			'forceHTTPS' => true
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->forceHTTPS() );

		// Hook
		$that = $this;
		$called = false;
		$data = array( 'foo' => 1 );
		$store->set( $dataKey, $data );
		$store->set( $metaKey, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->mergeMwGlobalArrayValue( 'wgHooks', array(
			'SessionCheckInfo' => array( function ( &$reason, $i, $r, $m, $d ) use (
				$that, $info, $metadata, $data, $request, &$called
			) {
				$that->assertSame( $info, $i );
				$that->assertSame( $request, $r );
				$that->assertEquals( $metadata, $m );
				$that->assertEquals( $data, $d );
				$called = true;
				return false;
			} )
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $called );

	}
}
