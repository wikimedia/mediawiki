<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\TokenAwareHandlerTrait;
use MediaWiki\Session\Session;
use MediaWiki\Session\SessionProvider;
use MediaWiki\Session\Token;
use MediaWiki\User\User;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Rest\TokenAwareHandlerTrait
 */
class TokenAwareHandlerTraitTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider provideValidateToken
	 */
	public function testValidateToken(
		array $body,
		?string $sessionToken,
		bool $safeAgainstCsrf,
		bool $isAnon,
		bool $allowAnonymousToken,
		bool $shouldPass
	) {
		$handler = $this->getHandler( $body, $sessionToken, $safeAgainstCsrf, $isAnon );
		$exception = null;
		try {
			$handler->validateToken( $allowAnonymousToken );
		} catch ( LocalizedHttpException $e ) {
			$exception = $e;
		}
		if ( $shouldPass ) {
			$this->assertNull( $exception ? $exception->getMessageValue()->getKey() : null );
		} else {
			$this->assertInstanceOf( LocalizedHttpException::class, $exception );
		}
	}

	public static function provideValidateToken() {
		$secret = 'foo';
		$anotherSecret = 'bar';
		$token = strval( new Token( $secret, '' ) );

		foreach ( [
			'missing token (anon)' => [
				'body' => [],
				'isAnon' => true,
				'allowAnonymousToken' => true,
				'shouldPass' => false,
			],
			'missing token (user)' => [
				'body' => [],
				'isAnon' => false,
				'shouldPass' => false,
			],
			'anonymous token (disallowed)' => [
				'body' => [ 'token' => '+\\' ],
				'isAnon' => true,
				'allowAnonymousToken' => false,
				'shouldPass' => false,
			],
			'anonymous token (allowed)' => [
				'body' => [ 'token' => '+\\' ],
				'isAnon' => true,
				'allowAnonymousToken' => true,
				'shouldPass' => true,
			],
			'anonymous token for logged-in user' => [
				'body' => [ 'token' => '+\\' ],
				'isAnon' => false,
				'allowAnonymousToken' => true,
				'shouldPass' => false,
			],
			'CSRF-safe method' => [
				'body' => [],
				'safeAgainstCsrf' => true,
				'isAnon' => false,
				'shouldPass' => true,
			],
			'wrong token (anon)' => [
				'body' => [ 'token' => $token ],
				'isAnon' => true,
				'shouldPass' => false,
			],
			'wrong token (user)' => [
				'body' => [ 'token' => $token ],
				'isAnon' => false,
				'shouldPass' => false,
			],
			'wrong token #2 (anon)' => [
				'body' => [ 'token' => $token ],
				'sessionToken' => $anotherSecret,
				'isAnon' => true,
				'shouldPass' => false,
			],
			'wrong token #2 (user)' => [
				'body' => [ 'token' => $token ],
				'sessionToken' => $anotherSecret,
				'isAnon' => false,
				'shouldPass' => false,
			],
			'good token (anon)' => [
				'body' => [ 'token' => $token ],
				'sessionToken' => $secret,
				'isAnon' => true,
				'shouldPass' => true,
			],
			'good token (user)' => [
				'body' => [ 'token' => $token ],
				'sessionToken' => $secret,
				'isAnon' => false,
				'shouldPass' => true,
			],
			'good token (anon w/ anon tokens)' => [
				'body' => [ 'token' => $token ],
				'sessionToken' => $secret,
				'allowAnonymousToken' => true,
				'isAnon' => true,
				// this will fail since we expect an anonymous token
				'shouldPass' => false,
			],
		] as $name => $test ) {
			yield $name => array_merge( [
				'body' => null,
				'sessionToken' => null,
				'safeAgainstCsrf' => false,
				'isAnon' => null,
				'allowAnonymousToken' => false,
				'shouldPass' => null,
			], $test );
		}
	}

	private function getHandler(
		array $body,
		?string $sessionToken,
		bool $safeAgainstCsrf,
		bool $isAnon
	) {
		$session = $this->createNoOpMock( Session::class,
			[ 'getProvider', 'isPersistent', 'hasToken', 'getToken', 'getUser' ] );
		$sessionProvider = $this->createNoOpMock( SessionProvider::class, [ 'safeAgainstCsrf' ] );
		$sessionProvider->method( 'safeAgainstCsrf' )->willReturn( $safeAgainstCsrf );
		$session->method( 'getProvider' )->willReturn( $sessionProvider );
		$session->method( 'isPersistent' )->willReturn( true );
		$session->method( 'hasToken' )->willReturn( $sessionToken !== null );
		$session->method( 'getToken' )->willReturn( new Token( $sessionToken, '' ) );
		$user = $this->createNoOpMock( User::class, [ 'isAnon' ] );
		$user->method( 'isAnon' )->willReturn( $isAnon );
		$session->method( 'getUser' )->willReturn( $user );

		// PHPUnit can't mock a class and a trait at the same time
		return new class( $session, $body ) extends Handler {
			use TokenAwareHandlerTrait {
				validateToken as public;
			}

			private Session $session;
			private array $validatedBody;

			public function __construct( Session $session, array $validatedBody ) {
				$this->session = $session;
				$this->validatedBody = $validatedBody;
			}

			public function execute() {
			}

			public function getSession(): Session {
				return $this->session;
			}

			public function getValidatedBody() {
				return $this->validatedBody;
			}
		};
	}

}
