<?php

namespace MediaWiki\Tests\Session;

use MediaWikiUnitTestCase;
use ReportUri\Dbsc\Exception\JwtInvalidException;
use ReportUri\Dbsc\JwtVerifier;

/**
 * Adversarial checks against the vendored report-uri/dbsc-php JwtVerifier, so
 * a vendor bump that weakens proof validation fails MediaWiki CI.
 *
 * @group Session
 * @coversNothing
 */
class DeviceBoundSessionVendorCryptoTest extends MediaWikiUnitTestCase {

	public function testAcceptsValidRegistrationAndRefresh() {
		$verifier = new JwtVerifier();
		[ $pk, $jwk ] = self::makeKey();

		$result = $verifier->verifyRegistrationJwt( $verifier->parse(
			self::signJws( $pk, [ 'alg' => 'ES256', 'jwk' => $jwk ], [ 'jti' => 'c1' ] ) ) );
		$this->assertSame( 'c1', $result->challenge );
		$this->assertSame( 'ES256', $result->algorithm );

		$verifier->verifyRefreshJwt( $verifier->parse(
			self::signJws( $pk, [ 'alg' => 'ES256' ], [ 'jti' => 'c2' ] ) ), $result->publicKeyPem );
		$this->addToAssertionCount( 1 );
	}

	/**
	 * @dataProvider provideRejected
	 */
	public function testRejectsForgedOrConfusedProofs( callable $case ) {
		$this->expectException( JwtInvalidException::class );
		$case();
	}

	public static function provideRejected(): array {
		return [
			'wrong key on refresh' => [ static function () {
				$verifier = new JwtVerifier();
				[ $pk, $jwk ] = self::makeKey();
				$pem = $verifier->verifyRegistrationJwt( $verifier->parse(
					self::signJws( $pk, [ 'alg' => 'ES256', 'jwk' => $jwk ], [ 'jti' => 'c' ] )
				) )->publicKeyPem;
				[ $otherPk, ] = self::makeKey();
				$verifier->verifyRefreshJwt( $verifier->parse(
					self::signJws( $otherPk, [ 'alg' => 'ES256' ], [ 'jti' => 'c' ] ) ), $pem );
			} ],
			'alg none' => [ static function () {
				[ , $jwk ] = self::makeKey();
				$jwt = self::b64( json_encode( [ 'alg' => 'none', 'jwk' => $jwk ] ) )
					. '.' . self::b64( json_encode( [ 'jti' => 'c' ] ) ) . '.';
				( new JwtVerifier() )->verifyRegistrationJwt( ( new JwtVerifier() )->parse( $jwt ) );
			} ],
			'RS256 header' => [ static function () {
				$verifier = new JwtVerifier();
				[ $pk, $jwk ] = self::makeKey();
				$verifier->verifyRegistrationJwt( $verifier->parse(
					self::signJws( $pk, [ 'alg' => 'RS256', 'jwk' => $jwk ], [ 'jti' => 'c' ] ) ) );
			} ],
			'tampered payload' => [ static function () {
				$verifier = new JwtVerifier();
				[ $pk, $jwk ] = self::makeKey();
				$parts = explode( '.', self::signJws(
					$pk, [ 'alg' => 'ES256', 'jwk' => $jwk ], [ 'jti' => 'good' ] ) );
				$parts[1] = self::b64( json_encode( [ 'jti' => 'evil' ] ) );
				$verifier->verifyRegistrationJwt( $verifier->parse( implode( '.', $parts ) ) );
			} ],
			'registration without jwk' => [ static function () {
				$verifier = new JwtVerifier();
				[ $pk, ] = self::makeKey();
				$verifier->verifyRegistrationJwt( $verifier->parse(
					self::signJws( $pk, [ 'alg' => 'ES256' ], [ 'jti' => 'c' ] ) ) );
			} ],
			'wrong curve jwk' => [ static function () {
				$verifier = new JwtVerifier();
				[ $pk, ] = self::makeKey();
				$bad = [ 'kty' => 'EC', 'crv' => 'P-384', 'x' => 'AA', 'y' => 'AA' ];
				$verifier->verifyRegistrationJwt( $verifier->parse(
					self::signJws( $pk, [ 'alg' => 'ES256', 'jwk' => $bad ], [ 'jti' => 'c' ] ) ) );
			} ],
			'malformed jwt' => [ static function () {
				( new JwtVerifier() )->parse( 'not-a-jwt' );
			} ],
		];
	}

	private static function b64( string $bytes ): string {
		return rtrim( strtr( base64_encode( $bytes ), '+/', '-_' ), '=' );
	}

	/**
	 * @return array{0:\OpenSSLAsymmetricKey,1:array} Private key and public JWK
	 */
	private static function makeKey(): array {
		$pk = openssl_pkey_new( [
			'private_key_type' => OPENSSL_KEYTYPE_EC,
			'curve_name' => 'prime256v1',
		] );
		$d = openssl_pkey_get_details( $pk );
		return [ $pk, [
			'kty' => 'EC',
			'crv' => 'P-256',
			'x' => self::b64( str_pad( $d['ec']['x'], 32, "\x00", STR_PAD_LEFT ) ),
			'y' => self::b64( str_pad( $d['ec']['y'], 32, "\x00", STR_PAD_LEFT ) ),
		] ];
	}

	/**
	 * Sign a compact JWS with an ES256 (r || s) signature.
	 *
	 * @param \OpenSSLAsymmetricKey $pk
	 * @param array $header
	 * @param array $claims
	 * @return string
	 */
	private static function signJws( $pk, array $header, array $claims ): string {
		$signingInput = self::b64( json_encode( $header ) ) . '.' . self::b64( json_encode( $claims ) );
		openssl_sign( $signingInput, $der, $pk, OPENSSL_ALGO_SHA256 );
		$offset = 2 + ( ( ord( $der[1] ) & 0x80 ) ? ( ord( $der[1] ) & 0x7f ) : 0 ) + 1;
		$rLen = ord( $der[$offset] );
		$r = ltrim( substr( $der, $offset + 1, $rLen ), "\x00" );
		$offset += 1 + $rLen + 1;
		$sLen = ord( $der[$offset] );
		$s = ltrim( substr( $der, $offset + 1, $sLen ), "\x00" );
		return $signingInput . '.' . self::b64(
			str_pad( $r, 32, "\x00", STR_PAD_LEFT ) . str_pad( $s, 32, "\x00", STR_PAD_LEFT )
		);
	}
}
