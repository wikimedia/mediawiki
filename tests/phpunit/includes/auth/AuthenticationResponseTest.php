<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\AuthenticationResponse
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
		$req = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\AuthenticationRequest' );
		$msg = new \Message( 'mainpage' );

		return array(
			array( 'newPass', array(), array(
				'status' => AuthenticationResponse::PASS,
			) ),
			array( 'newPass', array( 'name' ), array(
				'status' => AuthenticationResponse::PASS,
				'username' => 'name',
			) ),
			array( 'newPass', array( 'name', null ), array(
				'status' => AuthenticationResponse::PASS,
				'username' => 'name',
			) ),

			array( 'newFail', array( $msg ), array(
				'status' => AuthenticationResponse::FAIL,
				'message' => $msg,
			) ),

			array( 'newRestart', array( $msg ), array(
				'status' => AuthenticationResponse::RESTART,
				'message' => $msg,
			) ),

			array( 'newAbstain', array(), array(
				'status' => AuthenticationResponse::ABSTAIN,
			) ),

			array( 'newUI', array( array( $req ), $msg ), array(
				'status' => AuthenticationResponse::UI,
				'neededRequests' => array( $req ),
				'message' => $msg,
			) ),
			array( 'newUI', array( array(), $msg ),
				new \InvalidArgumentException( '$reqs may not be empty' )
			),

			array( 'newRedirect', array( array( $req ), 'http://example.org/redir' ), array(
				'status' => AuthenticationResponse::REDIRECT,
				'neededRequests' => array( $req ),
				'redirectTarget' => 'http://example.org/redir',
			) ),
			array(
				'newRedirect',
				array( array( $req ), 'http://example.org/redir', array( 'foo' => 'bar' ) ),
				array(
					'status' => AuthenticationResponse::REDIRECT,
					'neededRequests' => array( $req ),
					'redirectTarget' => 'http://example.org/redir',
					'redirectApiData' => array( 'foo' => 'bar' ),
				)
			),
			array( 'newRedirect', array( array(), 'http://example.org/redir' ),
				new \InvalidArgumentException( '$reqs may not be empty' )
			),
		);
	}

}
