<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\AuthenticationResponse
 */
class AuthenticationResponseTest extends \MediaWikiTestCase {
	/**
	 * @dataProvider provideConstructors
	 * @param string $constructor
	 * @param array $args
	 * @param array|Exception $expect
	 */
	public function testConstructors( $constructor, $args, $expect ) {
		if ( is_array( $expect ) ) {
			$res = new AuthenticationResponse();
			$res->messageType = 'warning';
			foreach ( $expect as $field => $value ) {
				$res->$field = $value;
			}
			$ret = call_user_func_array( "MediaWiki\\Auth\\AuthenticationResponse::$constructor", $args );
			$this->assertEquals( $res, $ret );
		} else {
			try {
				call_user_func_array( "MediaWiki\\Auth\\AuthenticationResponse::$constructor", $args );
				$this->fail( 'Expected exception not thrown' );
			} catch ( \Exception $ex ) {
				$this->assertEquals( $expect, $ex );
			}
		}
	}

	public function provideConstructors() {
		$req = $this->getMockForAbstractClass( AuthenticationRequest::class );
		$msg = new \Message( 'mainpage' );

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
			] ],

			[ 'newRestart', [ $msg ], [
				'status' => AuthenticationResponse::RESTART,
				'message' => $msg,
			] ],

			[ 'newAbstain', [], [
				'status' => AuthenticationResponse::ABSTAIN,
			] ],

			[ 'newUI', [ [ $req ], $msg ], [
				'status' => AuthenticationResponse::UI,
				'neededRequests' => [ $req ],
				'message' => $msg,
				'messageType' => 'warning',
			] ],

			[ 'newUI', [ [ $req ], $msg, 'warning' ], [
				'status' => AuthenticationResponse::UI,
				'neededRequests' => [ $req ],
				'message' => $msg,
				'messageType' => 'warning',
			] ],

			[ 'newUI', [ [ $req ], $msg, 'error' ], [
				'status' => AuthenticationResponse::UI,
				'neededRequests' => [ $req ],
				'message' => $msg,
				'messageType' => 'error',
			] ],
			[ 'newUI', [ [], $msg ],
				new \InvalidArgumentException( '$reqs may not be empty' )
			],

			[ 'newRedirect', [ [ $req ], 'http://example.org/redir' ], [
				'status' => AuthenticationResponse::REDIRECT,
				'neededRequests' => [ $req ],
				'redirectTarget' => 'http://example.org/redir',
			] ],
			[
				'newRedirect',
				[ [ $req ], 'http://example.org/redir', [ 'foo' => 'bar' ] ],
				[
					'status' => AuthenticationResponse::REDIRECT,
					'neededRequests' => [ $req ],
					'redirectTarget' => 'http://example.org/redir',
					'redirectApiData' => [ 'foo' => 'bar' ],
				]
			],
			[ 'newRedirect', [ [], 'http://example.org/redir' ],
				new \InvalidArgumentException( '$reqs may not be empty' )
			],
		];
	}

}
