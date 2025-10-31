<?php

namespace MediaWiki\Tests\Auth;

use Exception;
use InvalidArgumentException;
use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Message\Message;
use MediaWikiUnitTestCase;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\AuthenticationResponse
 */
class AuthenticationResponseTest extends MediaWikiUnitTestCase {
	/**
	 * @dataProvider provideConstructors
	 *
	 * @param string $constructor
	 * @param array $args
	 * @param array|Exception $expect
	 */
	public function testConstructors( $constructor, $args, $expect ) {
		if ( $args && is_array( $args[0] ) ) {
			foreach ( $args[0] as &$arg ) {
				if ( $arg === 'authrequest' ) {
					$arg = $this->getMockForAbstractClass( AuthenticationRequest::class );
				}
			}
		}
		if ( is_array( $expect ) && isset( $expect['neededRequests'] ) ) {
			foreach ( $expect['neededRequests'] as &$neededRequests ) {
				if ( $neededRequests === 'authrequest' ) {
					$neededRequests = $this->getMockForAbstractClass( AuthenticationRequest::class );
				}
			}
		}

		if ( is_array( $expect ) ) {
			$res = new AuthenticationResponse();
			$res->messageType = 'warning';
			foreach ( $expect as $field => $value ) {
				$res->$field = $value;
			}
			$ret = AuthenticationResponse::$constructor( ...$args );
			$this->assertEquals( $res, $ret );
		} else {
			try {
				AuthenticationResponse::$constructor( ...$args );
				$this->fail( 'Expected exception not thrown' );
			} catch ( Exception $ex ) {
				$this->assertEquals( $expect, $ex );
			}
		}
	}

	public static function provideConstructors() {
		$msg = new Message( 'mainpage' );

		return [
			[ 'newPass', [], [
				'status' => AuthenticationResponse::PASS,
			] ],
			[ 'newPass', [ 'name' ], [
				'status' => AuthenticationResponse::PASS,
				'username' => 'name',
			] ],
			[ 'newPass', [ 'name', null ], [
				'status' => AuthenticationResponse::PASS,
				'username' => 'name',
			] ],

			[ 'newFail', [ $msg ], [
				'status' => AuthenticationResponse::FAIL,
				'message' => $msg,
				'messageType' => 'error',
				'failReasons' => []
			] ],

			[ 'newRestart', [ $msg ], [
				'status' => AuthenticationResponse::RESTART,
				'message' => $msg,
			] ],

			[ 'newAbstain', [], [
				'status' => AuthenticationResponse::ABSTAIN,
			] ],

			[ 'newUI', [ [ 'authrequest' ], $msg ], [
				'status' => AuthenticationResponse::UI,
				'neededRequests' => [ 'authrequest' ],
				'message' => $msg,
				'messageType' => 'warning',
			] ],

			[ 'newUI', [ [ 'authrequest' ], $msg, 'warning' ], [
				'status' => AuthenticationResponse::UI,
				'neededRequests' => [ 'authrequest' ],
				'message' => $msg,
				'messageType' => 'warning',
			] ],

			[ 'newUI', [ [ 'authrequest' ], $msg, 'error' ], [
				'status' => AuthenticationResponse::UI,
				'neededRequests' => [ 'authrequest' ],
				'message' => $msg,
				'messageType' => 'error',
			] ],
			[ 'newUI', [ [], $msg ],
				new InvalidArgumentException( '$reqs may not be empty' )
			],

			[ 'newRedirect', [ [ 'authrequest' ], 'http://example.org/redir' ], [
				'status' => AuthenticationResponse::REDIRECT,
				'neededRequests' => [ 'authrequest' ],
				'redirectTarget' => 'http://example.org/redir',
			] ],
			[
				'newRedirect',
				[ [ 'authrequest' ], 'http://example.org/redir', [ 'foo' => 'bar' ] ],
				[
					'status' => AuthenticationResponse::REDIRECT,
					'neededRequests' => [ 'authrequest' ],
					'redirectTarget' => 'http://example.org/redir',
					'redirectApiData' => [ 'foo' => 'bar' ],
				]
			],
			[ 'newRedirect', [ [], 'http://example.org/redir' ],
				new InvalidArgumentException( '$reqs may not be empty' )
			],
		];
	}

}
