<?php

namespace MediaWiki\Session;

use MediaWikiTestCase;

/**
 * @group Session
 * @group Database
 * @covers MediaWiki\Session\SessionProvider
 */
class SessionProviderTest extends MediaWikiTestCase {

	public function testBasics() {
		$manager = new SessionManager();
		$logger = new \TestLogger();
		$config = new \HashConfig();

		$provider = $this->getMockForAbstractClass( SessionProvider::class );
		$priv = \TestingAccessWrapper::newFromObject( $provider );

		$provider->setConfig( $config );
		$this->assertSame( $config, $priv->config );
		$provider->setLogger( $logger );
		$this->assertSame( $logger, $priv->logger );
		$provider->setManager( $manager );
		$this->assertSame( $manager, $priv->manager );
		$this->assertSame( $manager, $provider->getManager() );

		$provider->invalidateSessionsForUser( new \User );

		$this->assertSame( [], $provider->getVaryHeaders() );
		$this->assertSame( [], $provider->getVaryCookies() );
		$this->assertSame( null, $provider->suggestLoginUsername( new \FauxRequest ) );

		$this->assertSame( get_class( $provider ), (string)$provider );

		$this->assertNull( $provider->getRememberUserDuration() );

		$this->assertNull( $provider->whyNoSession() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'id' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
			'provider' => $provider,
		] );
		$metadata = [ 'foo' ];
		$this->assertTrue( $provider->refreshSessionInfo( $info, new \FauxRequest, $metadata ) );
		$this->assertSame( [ 'foo' ], $metadata );
	}

	/**
	 * @dataProvider provideNewSessionInfo
	 * @param bool $persistId Return value for ->persistsSessionId()
	 * @param bool $persistUser Return value for ->persistsSessionUser()
	 * @param bool $ok Whether a SessionInfo is provided
	 */
	public function testNewSessionInfo( $persistId, $persistUser, $ok ) {
		$manager = new SessionManager();

		$provider = $this->getMockBuilder( SessionProvider::class )
			->setMethods( [ 'canChangeUser', 'persistsSessionId' ] )
			->getMockForAbstractClass();
		$provider->expects( $this->any() )->method( 'persistsSessionId' )
			->will( $this->returnValue( $persistId ) );
		$provider->expects( $this->any() )->method( 'canChangeUser' )
			->will( $this->returnValue( $persistUser ) );
		$provider->setManager( $manager );

		if ( $ok ) {
			$info = $provider->newSessionInfo();
			$this->assertNotNull( $info );
			$this->assertFalse( $info->wasPersisted() );
			$this->assertTrue( $info->isIdSafe() );

			$id = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
			$info = $provider->newSessionInfo( $id );
			$this->assertNotNull( $info );
			$this->assertSame( $id, $info->getId() );
			$this->assertFalse( $info->wasPersisted() );
			$this->assertTrue( $info->isIdSafe() );
		} else {
			$this->assertNull( $provider->newSessionInfo() );
		}
	}

	public function testMergeMetadata() {
		$provider = $this->getMockBuilder( SessionProvider::class )
			->getMockForAbstractClass();

		try {
			$provider->mergeMetadata(
				[ 'foo' => 1, 'baz' => 3 ],
				[ 'bar' => 2, 'baz' => '3' ]
			);
			$this->fail( 'Expected exception not thrown' );
		} catch ( MetadataMergeException $ex ) {
			$this->assertSame( 'Key "baz" changed', $ex->getMessage() );
			$this->assertSame(
				[ 'old_value' => 3, 'new_value' => '3' ], $ex->getContext() );
		}

		$res = $provider->mergeMetadata(
			[ 'foo' => 1, 'baz' => 3 ],
			[ 'bar' => 2, 'baz' => 3 ]
		);
		$this->assertSame( [ 'bar' => 2, 'baz' => 3 ], $res );
	}

	public static function provideNewSessionInfo() {
		return [
			[ false, false, false ],
			[ true, false, false ],
			[ false, true, false ],
			[ true, true, true ],
		];
	}

	public function testImmutableSessions() {
		$provider = $this->getMockBuilder( SessionProvider::class )
			->setMethods( [ 'canChangeUser', 'persistsSessionId' ] )
			->getMockForAbstractClass();
		$provider->expects( $this->any() )->method( 'canChangeUser' )
			->will( $this->returnValue( true ) );
		$provider->preventSessionsForUser( 'Foo' );

		$provider = $this->getMockBuilder( SessionProvider::class )
			->setMethods( [ 'canChangeUser', 'persistsSessionId' ] )
			->getMockForAbstractClass();
		$provider->expects( $this->any() )->method( 'canChangeUser' )
			->will( $this->returnValue( false ) );
		try {
			$provider->preventSessionsForUser( 'Foo' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \BadMethodCallException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\SessionProvider::preventSessionsForUser must be implmented ' .
					'when canChangeUser() is false',
				$ex->getMessage()
			);
		}

	}

	public function testHashToSessionId() {
		$config = new \HashConfig( [
			'SecretKey' => 'Shhh!',
		] );

		$provider = $this->getMockForAbstractClass( SessionProvider::class,
			[], 'MockSessionProvider' );
		$provider->setConfig( $config );
		$priv = \TestingAccessWrapper::newFromObject( $provider );

		$this->assertSame( 'eoq8cb1mg7j30ui5qolafps4hg29k5bb', $priv->hashToSessionId( 'foobar' ) );
		$this->assertSame( '4do8j7tfld1g8tte9jqp3csfgmulaun9',
			$priv->hashToSessionId( 'foobar', 'secret' ) );

		try {
			$priv->hashToSessionId( [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'$data must be a string, array was passed',
				$ex->getMessage()
			);
		}
		try {
			$priv->hashToSessionId( '', false );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'$key must be a string or null, boolean was passed',
				$ex->getMessage()
			);
		}
	}

	public function testDescribe() {
		$provider = $this->getMockForAbstractClass( SessionProvider::class,
			[], 'MockSessionProvider' );

		$this->assertSame(
			'MockSessionProvider sessions',
			$provider->describe( \Language::factory( 'en' ) )
		);
	}

	public function testGetAllowedUserRights() {
		$provider = $this->getMockForAbstractClass( SessionProvider::class );
		$backend = TestUtils::getDummySessionBackend();

		try {
			$provider->getAllowedUserRights( $backend );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'Backend\'s provider isn\'t $this',
				$ex->getMessage()
			);
		}

		\TestingAccessWrapper::newFromObject( $backend )->provider = $provider;
		$this->assertNull( $provider->getAllowedUserRights( $backend ) );
	}

}
