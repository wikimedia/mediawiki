<?php

namespace MediaWiki\Tests\Integration\Json;

use LogicException;
use MediaWiki\Json\JwtException;
use MediaWiki\Json\RsaJwtCodec;
use MediaWiki\MainConfigNames;
use MediaWikiIntegrationTestCase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Json\RsaJwtCodec
 */
class RsaJwtCodecTest extends MediaWikiIntegrationTestCase {

	public function testEnabled() {
		$this->overrideConfigValue( MainConfigNames::JwtPublicKey, false );
		$this->overrideConfigValue( MainConfigNames::JwtPrivateKey, false );
		/** @var RsaJwtCodec $jwtCodec */
		$jwtCodec = $this->getServiceContainer()->get( 'JwtCodec' );
		$this->assertFalse( $jwtCodec->isEnabled() );
		try {
			$jwtCodec->create( [] );
			$this->fail( 'Expected exception was not thrown' );
		} catch ( LogicException $e ) {
			$this->assertSame( 'JWT handling is not configured', $e->getMessage() );
		}
		try {
			$jwtCodec->parse( '' );
			$this->fail( 'Expected exception was not thrown' );
		} catch ( LogicException $e ) {
			$this->assertSame( 'JWT handling is not configured', $e->getMessage() );
		}

		$this->overrideConfigValue( MainConfigNames::JwtPublicKey, $this->key( 1, 'public' ) );
		$this->overrideConfigValue( MainConfigNames::JwtPrivateKey, $this->key( 1, 'private' ) );
		$this->getServiceContainer()->resetServiceForTesting( 'JwtCodec' );
		/** @var RsaJwtCodec $jwtCodec */
		$jwtCodec = $this->getServiceContainer()->get( 'JwtCodec' );
		$this->assertTrue( $jwtCodec->isEnabled() );
	}

	public function testJwt() {
		$this->overrideConfigValue( MainConfigNames::JwtPublicKey, $this->key( 1, 'public' ) );
		$this->overrideConfigValue( MainConfigNames::JwtPrivateKey, $this->key( 1, 'private' ) );
		ConvertibleTimestamp::setFakeTime( '20200101000000' );
		/** @var RsaJwtCodec $jwtCodec */
		$jwtCodec = $this->getServiceContainer()->get( 'JwtCodec' );

		// basic case
		$jwt = $jwtCodec->create( [ 'foo' => 'bar' ] );
		$claims = $jwtCodec->parse( $jwt );
		$this->assertCount( 1, $claims );
		$this->assertSame( 'bar', $claims['foo'] );

		$jwt = $jwtCodec->create( [ 'exp' => ConvertibleTimestamp::time() + 1 ] );
		$claims = $jwtCodec->parse( $jwt );
		$this->assertSame( ConvertibleTimestamp::time() + 1, $claims['exp'] );

		// 'exp' in the past
		$jwt = $jwtCodec->create( [ 'exp' => ConvertibleTimestamp::time() - 1 ] );
		try {
			$jwtCodec->parse( $jwt );
			$this->fail( 'Expected exception was not thrown for expired token' );
		} catch ( JwtException ) {
		}

		// wrong singing key
		$jwt = $jwtCodec->create( [] );
		$this->overrideConfigValue( MainConfigNames::JwtPublicKey, $this->key( 2, 'public' ) );
		$this->overrideConfigValue( MainConfigNames::JwtPrivateKey, $this->key( 2, 'private' ) );
		/** @var RsaJwtCodec $jwtCodec */
		$jwtCodec = $this->getServiceContainer()->get( 'JwtCodec' );
		try {
			$jwtCodec->parse( $jwt );
			$this->fail( 'Expected exception was not thrown for invalid signature' );
		} catch ( JwtException ) {
		}
	}

	private function key( int $number, string $type ): string {
		$ext = match ( $type ) {
			'public' => 'pem.pub',
			'private' => 'pem',
		};
		return file_get_contents( __DIR__ . "/key{$number}.$ext" );
	}

}
