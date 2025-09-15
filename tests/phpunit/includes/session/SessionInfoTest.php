<?php

namespace MediaWiki\Tests\Session;

use InvalidArgumentException;
use MediaWiki\Session\SessionInfo;
use MediaWiki\Session\SessionProvider;
use MediaWiki\Session\UserInfo;
use MediaWikiIntegrationTestCase;
use stdClass;

/**
 * @group Session
 * @group Database
 * @covers \MediaWiki\Session\SessionInfo
 */
class SessionInfoTest extends MediaWikiIntegrationTestCase {
	use SessionProviderTestTrait;

	public function testBasics() {
		$anonInfo = UserInfo::newAnonymous();
		$username = 'SessionInfoTestTestBasics';
		$userInfo = UserInfo::newFromName( $username, true );
		$unverifiedUserInfo = UserInfo::newFromName( $username, false );

		try {
			new SessionInfo( SessionInfo::MIN_PRIORITY - 1, [] );
			$this->fail( 'Expected exception not thrown', 'priority < min' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid priority', $ex->getMessage(), 'priority < min' );
		}

		try {
			new SessionInfo( SessionInfo::MAX_PRIORITY + 1, [] );
			$this->fail( 'Expected exception not thrown', 'priority > max' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid priority', $ex->getMessage(), 'priority > max' );
		}

		try {
			new SessionInfo( SessionInfo::MIN_PRIORITY, [ 'id' => 'ABC?' ] );
			$this->fail( 'Expected exception not thrown', 'bad session ID' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid session ID', $ex->getMessage(), 'bad session ID' );
		}

		try {
			new SessionInfo( SessionInfo::MIN_PRIORITY, [ 'userInfo' => new stdClass ] );
			$this->fail( 'Expected exception not thrown', 'bad userInfo' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid userInfo', $ex->getMessage(), 'bad userInfo' );
		}

		try {
			new SessionInfo( SessionInfo::MIN_PRIORITY, [] );
			$this->fail( 'Expected exception not thrown', 'no provider, no id' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'Must supply an ID when no provider is given', $ex->getMessage(),
				'no provider, no id' );
		}

		try {
			new SessionInfo( SessionInfo::MIN_PRIORITY, [ 'copyFrom' => new stdClass ] );
			$this->fail( 'Expected exception not thrown', 'bad copyFrom' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid copyFrom', $ex->getMessage(),
				'bad copyFrom' );
		}

		$manager = $this->getServiceContainer()->getSessionManager();
		$provider = $this->getMockBuilder( SessionProvider::class )
			->onlyMethods( [ 'persistsSessionId', 'canChangeUser', '__toString' ] )
			->getMockForAbstractClass();
		$this->initProvider( $provider, null, null, $manager );
		$provider->method( 'persistsSessionId' )
			->willReturn( true );
		$provider->method( 'canChangeUser' )
			->willReturn( true );
		$provider->method( '__toString' )
			->willReturn( 'Mock' );

		$provider2 = $this->getMockBuilder( SessionProvider::class )
			->onlyMethods( [ 'persistsSessionId', 'canChangeUser', '__toString' ] )
			->getMockForAbstractClass();
		$this->initProvider( $provider2, null, null, $manager );
		$provider2->method( 'persistsSessionId' )
			->willReturn( true );
		$provider2->method( 'canChangeUser' )
			->willReturn( true );
		$provider2->method( '__toString' )
			->willReturn( 'Mock2' );

		try {
			new SessionInfo( SessionInfo::MIN_PRIORITY, [
				'provider' => $provider,
				'userInfo' => $anonInfo,
				'metadata' => 'foo',
			] );
			$this->fail( 'Expected exception not thrown', 'bad metadata' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid metadata', $ex->getMessage(), 'bad metadata' );
		}

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, [
			'provider' => $provider,
			'userInfo' => $anonInfo
		] );
		$this->assertSame( $provider, $info->getProvider() );
		$this->assertNotNull( $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $anonInfo, $info->getUserInfo() );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertFalse( $info->needsRefresh() );
		$this->assertFalse( $info->forceUse() );
		$this->assertFalse( $info->wasPersisted() );
		$this->assertFalse( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertNull( $info->getProviderMetadata() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, [
			'provider' => $provider,
			'userInfo' => $unverifiedUserInfo,
			'metadata' => [ 'Foo' ],
		] );
		$this->assertSame( $provider, $info->getProvider() );
		$this->assertNotNull( $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $unverifiedUserInfo, $info->getUserInfo() );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertFalse( $info->needsRefresh() );
		$this->assertFalse( $info->forceUse() );
		$this->assertFalse( $info->wasPersisted() );
		$this->assertFalse( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertSame( [ 'Foo' ], $info->getProviderMetadata() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, [
			'provider' => $provider,
			'userInfo' => $userInfo
		] );
		$this->assertSame( $provider, $info->getProvider() );
		$this->assertNotNull( $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $userInfo, $info->getUserInfo() );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertFalse( $info->needsRefresh() );
		$this->assertFalse( $info->forceUse() );
		$this->assertFalse( $info->wasPersisted() );
		$this->assertTrue( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertNull( $info->getProviderMetadata() );

		$id = $manager->generateSessionId();

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, [
			'provider' => $provider,
			'id' => $id,
			'persisted' => true,
			'userInfo' => $anonInfo
		] );
		$this->assertSame( $provider, $info->getProvider() );
		$this->assertSame( $id, $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $anonInfo, $info->getUserInfo() );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertFalse( $info->needsRefresh() );
		$this->assertFalse( $info->forceUse() );
		$this->assertTrue( $info->wasPersisted() );
		$this->assertFalse( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertNull( $info->getProviderMetadata() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, [
			'provider' => $provider,
			'id' => $id,
			'userInfo' => $userInfo
		] );
		$this->assertSame( $provider, $info->getProvider() );
		$this->assertSame( $id, $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $userInfo, $info->getUserInfo() );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertFalse( $info->needsRefresh() );
		$this->assertFalse( $info->forceUse() );
		$this->assertFalse( $info->wasPersisted() );
		$this->assertTrue( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertNull( $info->getProviderMetadata() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, [
			'id' => $id,
			'persisted' => true,
			'userInfo' => $userInfo,
			'metadata' => [ 'Foo' ],
		] );
		$this->assertSame( $id, $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertSame( $userInfo, $info->getUserInfo() );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertFalse( $info->needsRefresh() );
		$this->assertFalse( $info->forceUse() );
		$this->assertTrue( $info->wasPersisted() );
		$this->assertFalse( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertNull( $info->getProviderMetadata() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, [
			'id' => $id,
			'remembered' => true,
			'userInfo' => $userInfo,
		] );
		$this->assertFalse( $info->wasRemembered(), 'no provider' );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, [
			'provider' => $provider,
			'id' => $id,
			'remembered' => true,
		] );
		$this->assertFalse( $info->wasRemembered(), 'no user' );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, [
			'provider' => $provider,
			'id' => $id,
			'remembered' => true,
			'userInfo' => $anonInfo,
		] );
		$this->assertFalse( $info->wasRemembered(), 'anonymous user' );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, [
			'provider' => $provider,
			'id' => $id,
			'remembered' => true,
			'userInfo' => $unverifiedUserInfo,
		] );
		$this->assertFalse( $info->wasRemembered(), 'unverified user' );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, [
			'provider' => $provider,
			'id' => $id,
			'remembered' => false,
			'userInfo' => $userInfo,
		] );
		$this->assertFalse( $info->wasRemembered(), 'specific override' );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, [
			'id' => $id,
			'idIsSafe' => true,
		] );
		$this->assertSame( $id, $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 5, $info->getPriority() );
		$this->assertTrue( $info->isIdSafe() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, [
			'id' => $id,
			'persisted' => true,
			'needsRefresh' => true,
		] );
		$this->assertTrue( $info->needsRefresh(), 'needsRefresh override' );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, [
			'id' => $id,
			'forceUse' => true,
		] );
		$this->assertFalse( $info->forceUse(), 'no provider' );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, [
			'provider' => $provider,
			'forceUse' => true,
		] );
		$this->assertFalse( $info->forceUse(), 'no id' );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 5, [
			'provider' => $provider,
			'id' => $id,
			'forceUse' => true,
		] );
		$this->assertTrue( $info->forceUse(), 'correct use' );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'id' => $id,
			'forceHTTPS' => 1,
		] );
		$this->assertTrue( $info->forceHTTPS() );

		$fromInfo = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'id' => $id . 'A',
			'provider' => $provider,
			'userInfo' => $userInfo,
			'idIsSafe' => true,
			'needsRefresh' => true,
			'forceUse' => true,
			'persisted' => true,
			'remembered' => true,
			'forceHTTPS' => true,
			'metadata' => [ 'foo!' ],
		] );
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 4, [
			'copyFrom' => $fromInfo,
		] );
		$this->assertSame( $id . 'A', $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 4, $info->getPriority() );
		$this->assertSame( $provider, $info->getProvider() );
		$this->assertSame( $userInfo, $info->getUserInfo() );
		$this->assertTrue( $info->isIdSafe() );
		$this->assertTrue( $info->needsRefresh() );
		$this->assertTrue( $info->forceUse() );
		$this->assertTrue( $info->wasPersisted() );
		$this->assertTrue( $info->wasRemembered() );
		$this->assertTrue( $info->forceHTTPS() );
		$this->assertSame( [ 'foo!' ], $info->getProviderMetadata() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY + 4, [
			'id' => $id . 'X',
			'provider' => $provider2,
			'userInfo' => $unverifiedUserInfo,
			'idIsSafe' => false,
			'needsRefresh' => false,
			'forceUse' => false,
			'persisted' => false,
			'remembered' => false,
			'forceHTTPS' => false,
			'metadata' => null,
			'copyFrom' => $fromInfo,
		] );
		$this->assertSame( $id . 'X', $info->getId() );
		$this->assertSame( SessionInfo::MIN_PRIORITY + 4, $info->getPriority() );
		$this->assertSame( $provider2, $info->getProvider() );
		$this->assertSame( $unverifiedUserInfo, $info->getUserInfo() );
		$this->assertFalse( $info->isIdSafe() );
		$this->assertFalse( $info->needsRefresh() );
		$this->assertFalse( $info->forceUse() );
		$this->assertFalse( $info->wasPersisted() );
		$this->assertFalse( $info->wasRemembered() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertNull( $info->getProviderMetadata() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'id' => $id,
		] );
		$this->assertSame(
			'[' . SessionInfo::MIN_PRIORITY . "]null<null>$id",
			(string)$info,
			'toString'
		);

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'persisted' => true,
			'userInfo' => $userInfo
		] );
		$this->assertSame(
			'[' . SessionInfo::MIN_PRIORITY . "]Mock<+:{$userInfo->getId()}:$username>$id",
			(string)$info,
			'toString'
		);

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $provider,
			'id' => $id,
			'persisted' => true,
			'userInfo' => $unverifiedUserInfo
		] );
		$this->assertSame(
			'[' . SessionInfo::MIN_PRIORITY . "]Mock<-:{$userInfo->getId()}:$username>$id",
			(string)$info,
			'toString'
		);
	}

	public function testCompare() {
		$id = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$info1 = new SessionInfo( SessionInfo::MIN_PRIORITY + 1, [ 'id' => $id ] );
		$info2 = new SessionInfo( SessionInfo::MIN_PRIORITY + 2, [ 'id' => $id ] );

		$this->assertLessThan( 0, SessionInfo::compare( $info1, $info2 ), '<' );
		$this->assertGreaterThan( 0, SessionInfo::compare( $info2, $info1 ), '>' );
		$this->assertSame( 0, SessionInfo::compare( $info1, $info1 ), '==' );
	}
}
