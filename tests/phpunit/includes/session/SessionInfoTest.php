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
			->setMethods( array( 'canChangeUser', '__toString' ) )
			->getMockForAbstractClass();
		$provider->setManager( $manager );
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
			->setMethods( array( 'canChangeUser', '__toString' ) )
			->getMockForAbstractClass();
		$provider->setManager( $manager );
		$provider->expects( $this->any() )->method( 'canChangeUser' )
			->will( $this->returnValue( true ) );
		$provider->expects( $this->any() )->method( '__toString' )
			->will( $this->returnValue( 'Mock' ) );

		try {
			new SessionInfo( SessionInfo::MIN_PRIORITY, array(
				'provider' => $provider,
				'user' => $anon,
				'metadata' => 'foo',
			) );
			$this->fail( 'Expected exception not thrown', 'bad metadata' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid metadata', $ex->getMessage(), 'bad metadata' );
		}

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
		$this->assertNull( $info->getProviderMetadata() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'provider' => $provider,
			'user' => $unauthUser,
			'metadata' => array( 'Foo' ),
		) );
		$this->assertSame( $provider, $info->getProvider() );
		$this->assertNotNull( $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $unauthUser, $info->getUser() );
		$this->assertFalse( $info->wasPersisted() );
		$this->assertFalse( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertSame( array( 'Foo' ), $info->getProviderMetadata() );

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
		$this->assertNull( $info->getProviderMetadata() );

		$id = $manager->generateSessionId();

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'provider' => $provider,
			'id' => $id,
			'persisted' => true,
			'user' => $anon
		) );
		$this->assertSame( $provider, $info->getProvider() );
		$this->assertSame( $id, $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $anon, $info->getUser() );
		$this->assertTrue( $info->wasPersisted() );
		$this->assertFalse( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertNull( $info->getProviderMetadata() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertSame( $provider, $info->getProvider() );
		$this->assertSame( $id, $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $user, $info->getUser() );
		$this->assertFalse( $info->wasPersisted() );
		$this->assertTrue( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertNull( $info->getProviderMetadata() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, array(
			'id' => $id,
			'persisted' => true,
			'user' => $user,
			'metadata' => array( 'Foo' ),
		) );
		$this->assertSame( $id, $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $user, $info->getUser() );
		$this->assertTrue( $info->wasPersisted() );
		$this->assertFalse( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertNull( $info->getProviderMetadata() );

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
			'persisted' => true,
			'user' => $unauthUser
		) );
		$this->assertSame(
			'[' . SessionInfo::MIN_PRIORITY . "]Mock<-:{$user->getId()}:UTSysop>$id",
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

	public function testLoadFromStore() {
		$manager = new SessionManager();
		$store = new TestBagOStuff();
		$logger = new \TestLogger( true, function ( $m ) {
			return preg_replace(
				'/^Session \[\d+\]\w+<(?:null|anon|[+-]:\d+:\w+)>\w+: /', 'Session X: ', $m
			);
		} );
		$request = new \FauxRequest();

		$user = UserInfo::newFromName( 'UTSysop', true );
		$unauthUser = UserInfo::newFromName( 'UTSysop', false );

		$id = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$metadata = array(
			'userId' => $user->getId(),
			'userName' => $user->getName(),
			'userToken' => $user->getToken( true ),
			'provider' => 'Mock',
		);

		$provider = $this->getMockBuilder( 'MediaWiki\\Session\\SessionProvider' )
			->setMethods( array( '__toString', 'mergeMetadata' ) )
			->getMockForAbstractClass();
		$provider->setManager( $manager );
		$provider->expects( $this->any() )->method( 'persistsSessionId' )
			->will( $this->returnValue( true ) );
		$provider->expects( $this->any() )->method( 'canChangeUser' )
			->will( $this->returnValue( true ) );
		$provider->expects( $this->any() )->method( '__toString' )
			->will( $this->returnValue( 'Mock' ) );
		$provider->expects( $this->any() )->method( 'mergeMetadata' )
			->will( $this->returnCallback( function ( $a, $b ) {
				if ( $b === array( 'Throw' ) ) {
					throw new \UnexpectedValueException( 'no merge!' );
				}
				return array( 'Merged' );
			} ) );

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
		$this->assertSame( array(), $logger->getBuffer() );

		// Unauthenticated user, no metadata
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $unauthUser
		) );
		$this->assertSame( $unauthUser, $info->getUser() );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(
			array( LogLevel::WARNING, 'Session X: Unauthenticated user provided and no metadata to auth it' )
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// No metadata, missing data
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'id' => $id,
			'user' => $user
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(
			array( LogLevel::WARNING, 'Session X: Null provider and no metadata' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertInstanceOf( 'MediaWiki\\Session\\UserInfo', $info->getUser() );
		$this->assertTrue( $info->getUser()->isAuthenticated() );
		$this->assertTrue( $info->getUser()->isAnon() );
		$this->assertSame( array(), $logger->getBuffer() );

		// Incomplete/bad metadata
		$store->setRawSession( $id, true );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(
			array( LogLevel::WARNING, 'Session X: Bad data' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		$store->setRawSession( $id, array( 'data' => array() ) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(
			array( LogLevel::WARNING, 'Session X: Bad data structure' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		$store->deleteSession( $id );
		$store->setRawSession( $id, array( 'metadata' => $metadata ) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(
			array( LogLevel::WARNING, 'Session X: Bad data structure' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		$store->setRawSession( $id, array( 'metadata' => $metadata, 'data' => true ) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(
			array( LogLevel::WARNING, 'Session X: Bad data structure' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		$store->setRawSession( $id, array( 'metadata' => true, 'data' => array() ) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(
			array( LogLevel::WARNING, 'Session X: Bad data structure' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		foreach ( $metadata as $key => $dummy ) {
			$tmp = $metadata;
			unset( $tmp[$key] );
			$store->setRawSession( $id, array( 'metadata' => $tmp, 'data' => array() ) );
			$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
			$this->assertSame( array(
				array( LogLevel::WARNING, 'Session X: Bad metadata' ),
			), $logger->getBuffer() );
			$logger->clearBuffer();
		}

		// Basic usage with metadata
		$store->setRawSession( $id, array( 'metadata' => $metadata, 'data' => array() ) );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(), $logger->getBuffer() );

		// Mismatched provider
		$store->setSessionMeta( $id, array( 'provider' => 'Bad' ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(
			array( LogLevel::WARNING, 'Session X: Wrong provider, Bad !== Mock' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Unknown provider
		$store->setSessionMeta( $id, array( 'provider' => 'Bad' ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'id' => $id,
			'user' => $user
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(
			array( LogLevel::WARNING, 'Session X: Unknown provider, Bad' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Fill in provider
		$store->setSessionMeta( $id, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'id' => $id,
			'user' => $user
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(), $logger->getBuffer() );

		// Bad user metadata
		$store->setSessionMeta( $id, array( 'userId' => -1, 'userToken' => null ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(
			array( LogLevel::ERROR, 'Session X: Invalid ID' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		$store->setSessionMeta(
			$id, array( 'userId' => 0, 'userName' => '<X>', 'userToken' => null ) + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(
			array( LogLevel::ERROR, 'Session X: Invalid user name' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Mismatched user by ID
		$store->setSessionMeta(
			$id, array( 'userId' => $user->getId() + 1, 'userToken' => null ) + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(
			array( LogLevel::WARNING, 'Session X: User ID mismatch, 2 !== 1' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Mismatched user by name
		$store->setSessionMeta(
			$id, array( 'userId' => 0, 'userName' => 'X', 'userToken' => null ) + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(
			array( LogLevel::WARNING, 'Session X: User name mismatch, X !== UTSysop' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// ID matches, name doesn't
		$store->setSessionMeta(
			$id, array( 'userId' => $user->getId(), 'userName' => 'X', 'userToken' => null ) + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(
			array(
				LogLevel::WARNING, 'Session X: User ID matched but name didn\'t (rename?), X !== UTSysop'
			),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Mismatched anon user
		$store->setSessionMeta(
			$id, array( 'userId' => 0, 'userName' => null, 'userToken' => null ) + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(
			array(
				LogLevel::WARNING, 'Session X: Metadata has an anonymous user, but a non-anon user was provided'
			),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Lookup user by ID
		$store->setSessionMeta( $id, array( 'userToken' => null ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( $user->getId(), $info->getUser()->getId() );
		$this->assertSame( array(), $logger->getBuffer() );

		// Lookup user by name
		$store->setSessionMeta(
			$id, array( 'userId' => 0, 'userName' => 'UTSysop', 'userToken' => null ) + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( $user->getId(), $info->getUser()->getId() );
		$this->assertSame( array(), $logger->getBuffer() );

		// Lookup anonymous user
		$store->setSessionMeta(
			$id, array( 'userId' => 0, 'userName' => null, 'userToken' => null ) + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->getUser()->isAnon() );
		$this->assertSame( array(), $logger->getBuffer() );

		// Unauthenticated user with metadata
		$store->setSessionMeta( $id, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $unauthUser
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->getUser()->isAuthenticated() );
		$this->assertSame( $unauthUser->getId(), $info->getUser()->getId() );
		$this->assertSame( $unauthUser->getName(), $info->getUser()->getName() );
		$this->assertSame( array(), $logger->getBuffer() );

		// Unauthenticated user with metadata
		$store->setSessionMeta( $id, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $unauthUser
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->getUser()->isAuthenticated() );
		$this->assertSame( $unauthUser->getId(), $info->getUser()->getId() );
		$this->assertSame( $unauthUser->getName(), $info->getUser()->getName() );
		$this->assertSame( array(), $logger->getBuffer() );

		// Wrong token
		$store->setSessionMeta( $id, array( 'userToken' => 'Bad' ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(
			array( LogLevel::WARNING, 'Session X: User token mismatch' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Provider metadata
		$store->setSessionMeta( $id, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user,
			'metadata' => array( 'Info' ),
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array( 'Info' ), $info->getProviderMetadata() );
		$this->assertSame( array(), $logger->getBuffer() );

		$store->setSessionMeta( $id, array( 'providerMetadata' => array( 'Saved' ) ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user,
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array( 'Saved' ), $info->getProviderMetadata() );
		$this->assertSame( array(), $logger->getBuffer() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user,
			'metadata' => array( 'Info' ),
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array( 'Merged' ), $info->getProviderMetadata() );
		$this->assertSame( array(), $logger->getBuffer() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user,
			'metadata' => array( 'Throw' ),
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(
			array( LogLevel::WARNING, 'Session X: Metadata merge failed: no merge!' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// Remember from session
		$store->setSessionMeta( $id, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertFalse( $info->wasRemembered() );
		$this->assertSame( array(), $logger->getBuffer() );

		$store->setSessionMeta( $id, array( 'remember' => true ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->wasRemembered() );
		$this->assertSame( array(), $logger->getBuffer() );

		$store->setSessionMeta( $id, array( 'remember' => false ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->wasRemembered() );
		$this->assertSame( array(), $logger->getBuffer() );

		// forceHTTPS from session
		$store->setSessionMeta( $id, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertSame( array(), $logger->getBuffer() );

		$store->setSessionMeta( $id, array( 'forceHTTPS' => true ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->forceHTTPS() );
		$this->assertSame( array(), $logger->getBuffer() );

		$store->setSessionMeta( $id, array( 'forceHTTPS' => false ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'user' => $user,
			'forceHTTPS' => true
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->forceHTTPS() );
		$this->assertSame( array(), $logger->getBuffer() );

		// Hook
		$that = $this;
		$called = false;
		$data = array( 'foo' => 1 );
		$store->setSession( $id, array( 'metadata' => $metadata, 'data' => $data ) );
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
		$this->assertSame( array(
			array( LogLevel::WARNING, 'Session X: Hook aborted' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

	}
}
