<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Config\HashConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler\DeviceBoundSessionRefreshHandler;
use MediaWiki\Rest\Handler\DeviceBoundSessionRegisterHandler;
use MediaWiki\Rest\RequestData;
use MediaWiki\Session\DeviceBoundSessionManager;
use MediaWiki\Session\DeviceBoundSessionStore;
use MediaWiki\Session\Session;
use MediaWiki\Session\SessionManagerInterface;
use MediaWiki\User\User;
use MediaWikiUnitTestCase;
use ReportUri\Dbsc\AuditLoggerInterface;
use ReportUri\Dbsc\Config as DbscConfig;
use ReportUri\Dbsc\DbscServer;
use ReportUri\Dbsc\InMemoryStore;
use ReportUri\Dbsc\ScopeRule;

/**
 * Runs the real DbscServer over its InMemoryStore through the REST adapters,
 * so the full protocol is exercised with only MediaWiki context mocked.
 *
 * @group Session
 * @covers \MediaWiki\Rest\Handler\DeviceBoundSessionHandlerBase
 * @covers \MediaWiki\Rest\Handler\DeviceBoundSessionRegisterHandler
 * @covers \MediaWiki\Rest\Handler\DeviceBoundSessionRefreshHandler
 * @covers \MediaWiki\Session\DeviceBoundSessionManager
 */
class DeviceBoundSessionHandlersTest extends MediaWikiUnitTestCase {
	use HandlerTestTrait;

	private const COOKIE = '__Host-dbsc';

	private function newManager(): DeviceBoundSessionManager {
		$sessionManager = $this->createMock( SessionManagerInterface::class );
		$sessionManager->method( 'getSubjectIdentifier' )->willReturn( 'mw:local:7' );
		return new DeviceBoundSessionManager(
			static fn ( AuditLoggerInterface $audit ) => new DbscServer(
				new DbscConfig(
					cookieName: self::COOKIE,
					cookieMaxAgeSeconds: 600,
					challengeTtlSeconds: 900,
					registerPath: '/w/rest.php/dbsc/v0/register',
					refreshPath: '/w/rest.php/dbsc/v0/refresh',
					scopeSpecification: [ ScopeRule::exclude( path: '/w/load.php' ) ]
				),
				new InMemoryStore(),
				audit: $audit
			),
			$this->createMock( DeviceBoundSessionStore::class ),
			$sessionManager,
			'https://wiki.example.org',
			'test-secret'
		);
	}

	private function config( bool $enabled = true, bool $reportOnly = false ): HashConfig {
		return new HashConfig( [
			MainConfigNames::DeviceBoundSessions => $enabled,
			MainConfigNames::DeviceBoundSessionsReportOnly => $reportOnly,
		] );
	}

	private function mwSession( bool $registered = true ): Session {
		$user = $this->createMock( User::class );
		$user->method( 'isRegistered' )->willReturn( $registered );
		$user->method( 'getName' )->willReturn( 'Testuser' );
		$session = $this->createMock( Session::class );
		$session->method( 'getUser' )->willReturn( $user );
		$session->method( 'getId' )->willReturn( 'mw-sess-1' );
		return $session;
	}

	private function advertise( DeviceBoundSessionManager $manager ): string {
		$ctx = $manager->buildContext( $this->mwSession(), [], [] );
		$header = $manager->server()->buildRegistrationHeaderResponse( $ctx )
			->headers['Secure-Session-Registration'];
		$this->assertSame( 1, preg_match( '/challenge="([^"]+)"/', $header, $m ) );
		return $m[1];
	}

	private static function post( array $headers = [], array $cookies = [] ): RequestData {
		return new RequestData( [
			'method' => 'POST', 'headers' => $headers, 'cookieParams' => $cookies,
		] );
	}

	public function testUnresolvableUserIsNotBound() {
		$sessionManager = $this->createMock( SessionManagerInterface::class );
		$sessionManager->method( 'getSubjectIdentifier' )->willReturn( 'mw:anon' );
		$manager = new DeviceBoundSessionManager(
			static fn ( AuditLoggerInterface $audit ) => new DbscServer(
				new DbscConfig( cookieName: self::COOKIE, cookieMaxAgeSeconds: 600,
					challengeTtlSeconds: 900 ),
				new InMemoryStore(),
				audit: $audit
			),
			$this->createMock( DeviceBoundSessionStore::class ),
			$sessionManager,
			'https://wiki.example.org'
		);

		$this->assertNull( $manager->buildContext( $this->mwSession(), [], [] ),
			'the shared anonymous identifier must never key a binding' );
	}

	public function testReportOnlyClosesTheRefreshEndpoint() {
		$handler = new DeviceBoundSessionRefreshHandler(
			$this->newManager(), $this->config( enabled: true, reportOnly: true ) );

		$response = $this->executeHandler(
			$handler, self::post( [ 'Sec-Secure-Session-Id' => 'anything' ] ),
			[], [], [], [], null, $this->mwSession() );

		$this->assertSame( 404, $response->getStatusCode() );
	}

	public function testRegisterIsDisabledByDefault() {
		$handler = new DeviceBoundSessionRegisterHandler( $this->newManager(), $this->config( false ) );
		$response = $this->executeHandler(
			$handler, self::post(), [], [], [], [], null, $this->mwSession() );
		$this->assertSame( 404, $response->getStatusCode() );
	}

	public function testRegisterRequiresLogin() {
		$handler = new DeviceBoundSessionRegisterHandler( $this->newManager(), $this->config() );
		$response = $this->executeHandler(
			$handler, self::post(), [], [], [], [], null, $this->mwSession( false ) );
		$this->assertSame( 401, $response->getStatusCode() );
	}

	public function testRegisterIgnoresBodyParams() {
		$handler = new DeviceBoundSessionRegisterHandler( $this->newManager(), $this->config() );

		$response = $this->executeHandler(
			$handler, self::post(), [], [], [], [ 'registration' => 'x.y.z' ],
			null, $this->mwSession() );

		$this->assertSame( 400, $response->getStatusCode() );
	}

	public function testRegisterThenRefreshRoundTrip() {
		$manager = $this->newManager();
		$challenge = $this->advertise( $manager );
		[ $pk, $jwk ] = self::makeKey();

		$register = new DeviceBoundSessionRegisterHandler( $manager, $this->config() );
		$response = $this->executeHandler(
			$register,
			self::post( [ 'Secure-Session-Response' => '"' . self::signJws( $pk,
				[ 'alg' => 'ES256', 'typ' => 'dbsc+jwt', 'jwk' => $jwk ],
				[ 'jti' => $challenge ] ) . '"' ] ),
			[], [], [], [], null, $this->mwSession()
		);
		$this->assertSame( 200, $response->getStatusCode() );
		$body = json_decode( (string)$response->getBody(), true );
		$this->assertArrayHasKey( 'session_identifier', $body );
		$this->assertSame(
			[ [ 'type' => 'exclude', 'path' => '/w/load.php' ] ],
			$body['scope']['scope_specification'],
			'asset paths are excluded so they are not suspended during a refresh'
		);
		$cookies = $response->getCookies();
		$this->assertCount( 1, $cookies );
		$this->assertSame( self::COOKIE, $cookies[0]['name'] );
		$this->assertSame( '', $cookies[0]['options']['prefix'],
			'__Host- cookies must not be prefixed' );
		$this->assertSame( '', $cookies[0]['options']['domain'] );
		$boundCookie = $cookies[0]['value'];

		// Phase 1: no proof => 403 + Secure-Session-Challenge naming the session.
		$refresh = new DeviceBoundSessionRefreshHandler( $manager, $this->config() );
		$response = $this->executeHandler(
			$refresh,
			self::post(
				[ 'Sec-Secure-Session-Id' => $body['session_identifier'] ],
				[ self::COOKIE => $boundCookie ]
			),
			[], [], [], [], null, $this->mwSession()
		);
		$this->assertSame( 403, $response->getStatusCode() );
		$challengeHeader = $response->getHeaderLine( 'Secure-Session-Challenge' );
		$this->assertMatchesRegularExpression( '/;\s*id="/', $challengeHeader );
		$this->assertSame( 1, preg_match( '/^"([^"]+)"/', $challengeHeader, $cm ) );

		// Phase 2: signed proof => 200, rotated cookie, next challenge attached.
		// Handler objects are single-request; a re-executed instance trips
		// initContext()'s lifecycle precondition, so phase 2 gets a fresh one.
		$response = $this->executeHandler(
			new DeviceBoundSessionRefreshHandler( $manager, $this->config() ),
			self::post(
				[
					'Sec-Secure-Session-Id' => $body['session_identifier'],
					'Secure-Session-Response' => '"' . self::signJws( $pk,
						[ 'alg' => 'ES256', 'typ' => 'dbsc+jwt' ], [ 'jti' => $cm[1] ] ) . '"',
				],
				[ self::COOKIE => $boundCookie ]
			),
			[], [], [], [], null, $this->mwSession()
		);
		$this->assertSame( 200, $response->getStatusCode() );
		$rotated = $response->getCookies();
		$this->assertSame( self::COOKIE, $rotated[0]['name'] );
		$this->assertNotSame( $boundCookie, $rotated[0]['value'], 'cookie must rotate' );
		$this->assertNotSame( '', $response->getHeaderLine( 'Secure-Session-Challenge' ),
			'steady-state refreshes carry the next challenge' );
	}

	public function testRegisterRejectsBadProof() {
		$manager = $this->newManager();
		$this->advertise( $manager );
		$handler = new DeviceBoundSessionRegisterHandler( $manager, $this->config() );
		$response = $this->executeHandler(
			$handler, self::post( [ 'Secure-Session-Response' => '"a.b.c"' ] ),
			[], [], [], [], null, $this->mwSession() );
		$this->assertSame( 400, $response->getStatusCode() );
	}

	public function testRefreshWithoutBindingIsTerminal404() {
		$handler = new DeviceBoundSessionRefreshHandler( $this->newManager(), $this->config() );
		$response = $this->executeHandler(
			$handler, self::post( [ 'Sec-Secure-Session-Id' => 'never-registered' ] ),
			[], [], [], [], null, $this->mwSession() );
		$this->assertSame( 404, $response->getStatusCode() );
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
