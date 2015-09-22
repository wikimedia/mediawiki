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

		$this->assertNull( $provider->whyNoSession() );

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'id' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
			'provider' => $provider,
		) );
		$metadata = array( 'foo' );
		$this->assertTrue( $provider->refreshSessionInfo( $info, new \FauxRequest, $metadata ) );
		$this->assertSame( array( 'foo' ), $metadata );
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
			->setMethods( array( 'canChangeUser', 'persistsSessionId' ) )
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
		$provider = $this->getMockBuilder( 'MediaWiki\\Session\\SessionProvider' )
			->getMockForAbstractClass();

		try {
			$provider->mergeMetadata(
				array( 'foo' => 1, 'baz' => 3 ),
				array( 'bar' => 2, 'baz' => '3' )
			);
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame( 'Key "baz" changed', $ex->getMessage() );
		}

		$res = $provider->mergeMetadata(
			array( 'foo' => 1, 'baz' => 3 ),
			array( 'bar' => 2, 'baz' => 3 )
		);
		$this->assertSame( array( 'bar' => 2, 'baz' => 3 ), $res );
	}

	public static function provideNewSessionInfo() {
		return array(
			array( false, false, false ),
			array( true, false, false ),
			array( false, true, false ),
			array( true, true, true ),
		);
	}

	public function testImmutableSessions() {
		$provider = $this->getMockBuilder( 'MediaWiki\\Session\\SessionProvider' )
			->setMethods( array( 'canChangeUser', 'persistsSessionId' ) )
			->getMockForAbstractClass();
		$provider->expects( $this->any() )->method( 'canChangeUser' )
			->will( $this->returnValue( true ) );
		$provider->preventSessionsForUser( 'Foo' );

		$provider = $this->getMockBuilder( 'MediaWiki\\Session\\SessionProvider' )
			->setMethods( array( 'canChangeUser', 'persistsSessionId' ) )
			->getMockForAbstractClass();
		$provider->expects( $this->any() )->method( 'canChangeUser' )
			->will( $this->returnValue( false ) );
		try {
			$provider->preventSessionsForUser( 'Foo' );
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

		$this->assertSame( 'eoq8cb1mg7j30ui5qolafps4hg29k5bb', $priv->hashToSessionId( 'foobar' ) );
		$this->assertSame( '4do8j7tfld1g8tte9jqp3csfgmulaun9',
			$priv->hashToSessionId( 'foobar', 'secret' ) );

		try {
			$priv->hashToSessionId( array() );
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
		$provider = $this->getMockForAbstractClass( 'MediaWiki\\Session\\SessionProvider',
			array(), 'MockSessionProvider' );

		$this->assertSame(
			'MockSessionProvider sessions',
			$provider->describe( \Language::factory( 'en' ) )
		);
	}

}
