<?php

namespace MediaWiki\Session;

use MediaWikiIntegrationTestCase;
use Psr\Log\LogLevel;
use User;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Session
 * @covers MediaWiki\Session\Session
 */
class SessionTest extends MediaWikiIntegrationTestCase {

	public function testClear() {
		$session = TestUtils::getDummySession();
		$priv = TestingAccessWrapper::newFromObject( $session );

		$backend = $this->getMockBuilder( DummySessionBackend::class )
			->setMethods( [ 'canSetUser', 'setUser', 'save' ] )
			->getMock();
		$backend->expects( $this->once() )->method( 'canSetUser' )
			->will( $this->returnValue( true ) );
		$backend->expects( $this->once() )->method( 'setUser' )
			->with( $this->callback( function ( $user ) {
				return $user instanceof User && $user->isAnon();
			} ) );
		$backend->expects( $this->once() )->method( 'save' );
		$priv->backend = $backend;
		$session->clear();
		$this->assertSame( [], $backend->data );
		$this->assertTrue( $backend->dirty );

		$backend = $this->getMockBuilder( DummySessionBackend::class )
			->setMethods( [ 'canSetUser', 'setUser', 'save' ] )
			->getMock();
		$backend->data = [];
		$backend->expects( $this->once() )->method( 'canSetUser' )
			->will( $this->returnValue( true ) );
		$backend->expects( $this->once() )->method( 'setUser' )
			->with( $this->callback( function ( $user ) {
				return $user instanceof User && $user->isAnon();
			} ) );
		$backend->expects( $this->once() )->method( 'save' );
		$priv->backend = $backend;
		$session->clear();
		$this->assertFalse( $backend->dirty );

		$backend = $this->getMockBuilder( DummySessionBackend::class )
			->setMethods( [ 'canSetUser', 'setUser', 'save' ] )
			->getMock();
		$backend->expects( $this->once() )->method( 'canSetUser' )
			->will( $this->returnValue( false ) );
		$backend->expects( $this->never() )->method( 'setUser' );
		$backend->expects( $this->once() )->method( 'save' );
		$priv->backend = $backend;
		$session->clear();
		$this->assertSame( [], $backend->data );
		$this->assertTrue( $backend->dirty );
	}

	public function testSecrets() {
		$logger = new \TestLogger;
		$session = TestUtils::getDummySession( null, -1, $logger );

		// Simple defaulting
		$this->assertEquals( 'defaulted', $session->getSecret( 'test', 'defaulted' ) );

		// Bad encrypted data
		$session->set( 'test', 'foobar' );
		$logger->setCollect( true );
		$this->assertEquals( 'defaulted', $session->getSecret( 'test', 'defaulted' ) );
		$logger->setCollect( false );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Invalid sealed-secret format' ]
		], $logger->getBuffer() );
		$logger->clearBuffer();

		// Tampered data
		$session->setSecret( 'test', 'foobar' );
		$encrypted = $session->get( 'test' );
		$session->set( 'test', $encrypted . 'x' );
		$logger->setCollect( true );
		$this->assertEquals( 'defaulted', $session->getSecret( 'test', 'defaulted' ) );
		$logger->setCollect( false );
		$this->assertSame( [
			[ LogLevel::WARNING, 'Sealed secret has been tampered with, aborting.' ]
		], $logger->getBuffer() );
		$logger->clearBuffer();

		// Unserializable data
		$iv = random_bytes( 16 );
		list( $encKey, $hmacKey ) = TestingAccessWrapper::newFromObject( $session )->getSecretKeys();
		$ciphertext = openssl_encrypt( 'foobar', 'aes-256-ctr', $encKey, OPENSSL_RAW_DATA, $iv );
		$sealed = base64_encode( $iv ) . '.' . base64_encode( $ciphertext );
		$hmac = hash_hmac( 'sha256', $sealed, $hmacKey, true );
		$encrypted = base64_encode( $hmac ) . '.' . $sealed;
		$session->set( 'test', $encrypted );
		\Wikimedia\suppressWarnings();
		$this->assertEquals( 'defaulted', $session->getSecret( 'test', 'defaulted' ) );
		\Wikimedia\restoreWarnings();
	}

	/**
	 * @dataProvider provideSecretsRoundTripping
	 * @param mixed $data
	 */
	public function testSecretsRoundTripping( $data ) {
		$session = TestUtils::getDummySession();

		// Simple round-trip
		$session->setSecret( 'secret', $data );
		$this->assertNotEquals( $data, $session->get( 'secret' ) );
		$this->assertEquals( $data, $session->getSecret( 'secret', 'defaulted' ) );
	}

	public static function provideSecretsRoundTripping() {
		return [
			[ 'Foobar' ],
			[ 42 ],
			[ [ 'foo', 'bar' => 'baz', 'subarray' => [ 1, 2, 3 ] ] ],
			[ (object)[ 'foo', 'bar' => 'baz', 'subarray' => [ 1, 2, 3 ] ] ],
			[ true ],
			[ false ],
			[ null ],
		];
	}

}
