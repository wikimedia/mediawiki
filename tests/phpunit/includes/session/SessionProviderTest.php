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
		$logger = new \Psr\Log\NullLogger();
		$config = new \HashConfig();

		$provider = $this->getMockForAbstractClass( 'MediaWiki\\Session\\SessionProvider' );
		$priv = \TestingAccessWrapper::newFromObject( $provider );

		$provider->setConfig( $config );
		$this->assertSame( $config, $priv->config );
		$provider->setLogger( $logger );
		$this->assertSame( $logger, $priv->logger );
		$provider->setManager( $manager );
		$this->assertSame( $manager, $priv->manager );
		$this->assertSame( $manager, $provider->getManager() );

		$this->assertSame( array(), $provider->getVaryHeaders() );
		$this->assertSame( array(), $provider->getVaryCookies() );
		$this->assertSame( null, $provider->suggestLoginUsername( new \FauxRequest ) );

		$this->assertSame( get_class( $provider ), (string)$provider );
	}

	/**
	 * @dataProvider provideNewSessionInfo
	 * @param bool $persistId Return value for ->persistsSessionId()
	 * @param bool $persistUser Return value for ->persistsSessionUser()
	 * @param bool $ok Whether a SessionInfo is provided
	 */
	public function testNewSessionInfo( $persistId, $persistUser, $ok ) {
		$manager = new SessionManager();

		$provider = $this->getMockBuilder( 'MediaWiki\\Session\\SessionProvider' )
			->setMethods( array( 'persistsUser', 'persistsSessionId' ) )
			->getMockForAbstractClass();
		$provider->expects( $this->any() )->method( 'persistsSessionId' )
			->will( $this->returnValue( $persistId ) );
		$provider->expects( $this->any() )->method( 'persistsUser' )
			->will( $this->returnValue( $persistUser ) );
		$provider->setManager( $manager );

		if ( $ok ) {
			$info = $provider->newSessionInfo();
			$this->assertNotNull( $info );
			$this->assertFalse( $info->wasPersisted() );

			$id = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
			$info = $provider->newSessionInfo( $id );
			$this->assertNotNull( $info );
			$this->assertSame( $id, $info->getId() );
			$this->assertFalse( $info->wasPersisted() );
		} else {
			$this->assertNull( $provider->newSessionInfo() );
		}
	}

	public static function provideNewSessionInfo() {
		return array(
			array( false, false, false ),
			array( true, false, false ),
			array( false, true, false ),
			array( true, true, true ),
		);
	}

	public function testPersistsUser() {
		$provider = $this->getMockForAbstractClass( 'MediaWiki\\Session\\SessionProvider' );
		$provider->expects( $this->any() )->method( 'persistsSessionId' )
			->will( $this->returnValue( true ) );
		$this->assertTrue( $provider->persistsUser() );

		$provider = $this->getMockForAbstractClass( 'MediaWiki\\Session\\SessionProvider' );
		$provider->expects( $this->any() )->method( 'persistsSessionId' )
			->will( $this->returnValue( false ) );
		$this->assertFalse( $provider->persistsUser() );
	}

	public function testImmutableSessions() {
		$provider = $this->getMockBuilder( 'MediaWiki\\Session\\SessionProvider' )
			->setMethods( array( 'persistsUser', 'persistsSessionId' ) )
			->getMockForAbstractClass();
		$provider->expects( $this->any() )->method( 'persistsUser' )
			->will( $this->returnValue( true ) );
		$this->assertFalse( $provider->immutableSessionCouldExistForUser( 'Foo' ) );
		$provider->preventImmutableSessionsForUser( 'Foo' );

		$provider = $this->getMockBuilder( 'MediaWiki\\Session\\SessionProvider' )
			->setMethods( array( 'persistsUser', 'persistsSessionId' ) )
			->getMockForAbstractClass();
		$provider->expects( $this->any() )->method( 'persistsUser' )
			->will( $this->returnValue( false ) );
		try {
			$provider->immutableSessionCouldExistForUser( 'Foo' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \BadMethodCallException $ex ) {
		}
		try {
			$provider->preventImmutableSessionsForUser( 'Foo' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \BadMethodCallException $ex ) {
		}

	}

	public function testHashToSessionId() {
		$config = new \HashConfig( array(
			'SecretKey' => 'Shhh!',
		) );

		$provider = $this->getMockForAbstractClass( 'MediaWiki\\Session\\SessionProvider',
			array(), 'MockSessionProvider' );
		$provider->setConfig( $config );
		$priv = \TestingAccessWrapper::newFromObject( $provider );

		$this->assertSame( '2refg4855cgh73pt6gooh32j9da825g4', $priv->hashToSessionId( 'foobar' ) );
		$this->assertSame( '1hjeglrlap79giv20llta52pj3u27dtj',
			$priv->hashToSessionId( 'foobar', 'secret' ) );
	}

	public function testDescribe() {
		$provider = $this->getMockForAbstractClass( 'MediaWiki\\Session\\SessionProvider',
			array(), 'MockSessionProvider' );

		$this->assertSame(
			'MockSessionProvider sessions',
			$provider->describe( \Language::factory( 'en' ) )
		);
	}

}
