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
			'byId' => true,
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

	public function testLoadFromStore() {
		$manager = new SessionManager();
		$store = new TestBagOStuff();
		$logger = new \TestLogger( true, function ( $m ) {
			return preg_replace(
				'/^Session \[\d+\]\w+<(?:null|anon|[+-]:\d+:\w+)>\w+: /', 'Session X: ', $m
			);
		} );
		$request = new \FauxRequest();

		$userInfo = UserInfo::newFromName( 'UTSysop', true );
		$unverifiedUserInfo = UserInfo::newFromName( 'UTSysop', false );

		$id = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$metadata = array(
			'userId' => $userInfo->getId(),
			'userName' => $userInfo->getName(),
			'userToken' => $userInfo->getToken( true ),
			'provider' => 'Mock',
		);

		$builder = $this->getMockBuilder( 'MediaWiki\\Session\\SessionProvider' )
			->setMethods( array( '__toString', 'mergeMetadata' ) );
		$provider = $builder->getMockForAbstractClass();
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

		$provider2 = $builder->getMockForAbstractClass();
		$provider2->setManager( $manager );
		$provider2->expects( $this->any() )->method( 'persistsSessionId' )
			->will( $this->returnValue( false ) );
		$provider2->expects( $this->any() )->method( 'canChangeUser' )
			->will( $this->returnValue( false ) );
		$provider2->expects( $this->any() )->method( '__toString' )
			->will( $this->returnValue( 'Mock2' ) );

		\TestingAccessWrapper::newFromObject( $manager )->sessionProviders = array(
			(string)$provider => $provider,
			(string)$provider2 => $provider2,
		);

		// No metadata, basic usage
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
		) );
		$this->assertFalse( $info->isIdSafe(), 'sanity check' );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertSame( array(), $logger->getBuffer() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'userInfo' => $userInfo
		) );
		$this->assertTrue( $info->isIdSafe(), 'sanity check' );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertSame( array(), $logger->getBuffer() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider2,
			'id' => $id,
			'userInfo' => $userInfo
		) );
		$this->assertFalse( $info->isIdSafe(), 'sanity check' );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertSame( array(), $logger->getBuffer() );

		// Unverified user, no metadata
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $unverifiedUserInfo
		) );
		$this->assertSame( $unverifiedUserInfo, $info->getUserInfo() );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(
			array( LogLevel::WARNING, 'Session X: Unverified user provided and no metadata to auth it' )
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// No metadata, missing data
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'id' => $id,
			'userInfo' => $userInfo
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
		$this->assertFalse( $info->isIdSafe(), 'sanity check' );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertInstanceOf( 'MediaWiki\\Session\\UserInfo', $info->getUserInfo() );
		$this->assertTrue( $info->getUserInfo()->isVerified() );
		$this->assertTrue( $info->getUserInfo()->isAnon() );
		$this->assertFalse( $info->isIdSafe() );
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
			'userInfo' => $userInfo
		) );
		$this->assertFalse( $info->isIdSafe(), 'sanity check' );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertSame( array(), $logger->getBuffer() );

		// Mismatched provider
		$store->setSessionMeta( $id, array( 'provider' => 'Bad' ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
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
			'userInfo' => $userInfo
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
			'userInfo' => $userInfo
		) );
		$this->assertFalse( $info->isIdSafe(), 'sanity check' );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->isIdSafe() );
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
			$id, array( 'userId' => $userInfo->getId() + 1, 'userToken' => null ) + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
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
			'userInfo' => $userInfo
		) );
		$this->assertFalse( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array(
			array( LogLevel::WARNING, 'Session X: User name mismatch, X !== UTSysop' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		// ID matches, name doesn't
		$store->setSessionMeta(
			$id, array( 'userId' => $userInfo->getId(), 'userName' => 'X', 'userToken' => null ) + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
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
			'userInfo' => $userInfo
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
		$this->assertFalse( $info->isIdSafe(), 'sanity check' );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( $userInfo->getId(), $info->getUserInfo()->getId() );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertSame( array(), $logger->getBuffer() );

		// Lookup user by name
		$store->setSessionMeta(
			$id, array( 'userId' => 0, 'userName' => 'UTSysop', 'userToken' => null ) + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
		) );
		$this->assertFalse( $info->isIdSafe(), 'sanity check' );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( $userInfo->getId(), $info->getUserInfo()->getId() );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertSame( array(), $logger->getBuffer() );

		// Lookup anonymous user
		$store->setSessionMeta(
			$id, array( 'userId' => 0, 'userName' => null, 'userToken' => null ) + $metadata
		);
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
		) );
		$this->assertFalse( $info->isIdSafe(), 'sanity check' );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->getUserInfo()->isAnon() );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertSame( array(), $logger->getBuffer() );

		// Unverified user with metadata
		$store->setSessionMeta( $id, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $unverifiedUserInfo
		) );
		$this->assertFalse( $info->isIdSafe(), 'sanity check' );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->getUserInfo()->isVerified() );
		$this->assertSame( $unverifiedUserInfo->getId(), $info->getUserInfo()->getId() );
		$this->assertSame( $unverifiedUserInfo->getName(), $info->getUserInfo()->getName() );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertSame( array(), $logger->getBuffer() );

		// Unverified user with metadata
		$store->setSessionMeta( $id, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $unverifiedUserInfo
		) );
		$this->assertFalse( $info->isIdSafe(), 'sanity check' );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->getUserInfo()->isVerified() );
		$this->assertSame( $unverifiedUserInfo->getId(), $info->getUserInfo()->getId() );
		$this->assertSame( $unverifiedUserInfo->getName(), $info->getUserInfo()->getName() );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertSame( array(), $logger->getBuffer() );

		// Wrong token
		$store->setSessionMeta( $id, array( 'userToken' => 'Bad' ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
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
			'userInfo' => $userInfo,
			'metadata' => array( 'Info' ),
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array( 'Info' ), $info->getProviderMetadata() );
		$this->assertSame( array(), $logger->getBuffer() );

		$store->setSessionMeta( $id, array( 'providerMetadata' => array( 'Saved' ) ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo,
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array( 'Saved' ), $info->getProviderMetadata() );
		$this->assertSame( array(), $logger->getBuffer() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo,
			'metadata' => array( 'Info' ),
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertSame( array( 'Merged' ), $info->getProviderMetadata() );
		$this->assertSame( array(), $logger->getBuffer() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo,
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
			'userInfo' => $userInfo
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->wasRemembered() );
		$this->assertSame( array(), $logger->getBuffer() );

		// forceHTTPS from session
		$store->setSessionMeta( $id, $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertSame( array(), $logger->getBuffer() );

		$store->setSessionMeta( $id, array( 'forceHTTPS' => true ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
		) );
		$this->assertTrue( $info->loadFromStore( $manager, $store, $logger, $request ) );
		$this->assertTrue( $info->forceHTTPS() );
		$this->assertSame( array(), $logger->getBuffer() );

		$store->setSessionMeta( $id, array( 'forceHTTPS' => false ) + $metadata );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo,
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
			'userInfo' => $userInfo
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
