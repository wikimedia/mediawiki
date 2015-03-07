<?php

/**
 * @group AuthManager
 * @covers AuthenticationResponse
 */
class AuthenticationResponseTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provideConstructors
	 * @param string $constructor
	 * @param array $args
	 * @param array $expectFields
	 */
	public function testConstructors( $constructor, $args, $expectFields ) {
		$expect = new AuthenticationResponse();
		foreach ( $expectFields as $field => $value ) {
			$expect->$field = $value;
		}

		$ret = call_user_func_array( "AuthenticationResponse::$constructor", $args );
		$this->assertEquals( $expect, $ret );
	}

	public function provideConstructors() {
		$req = $this->getMockForAbstractClass( 'AuthenticationRequest' );
		$msg = new Message( 'mainpage' );

		return array(
			array( 'newPass', array(), array(
				'status' => AuthenticationResponse::PASS,
			) ),
			array( 'newPass', array( 'name' ), array(
				'status' => AuthenticationResponse::PASS,
				'username' => 'name',
			) ),
			array( 'newPass', array( 'name', $req ), array(
				'status' => AuthenticationResponse::PASS,
				'username' => 'name',
				'createRequest' => $req,
			) ),
			array( 'newPass', array( null, $req ), array(
				'status' => AuthenticationResponse::PASS,
				'createRequest' => $req,
			) ),
			array( 'newPass', array( 'name', null ), array(
				'status' => AuthenticationResponse::PASS,
				'username' => 'name',
			) ),

			array( 'newFail', array( $msg ), array(
				'status' => AuthenticationResponse::FAIL,
				'message' => $msg,
			) ),

			array( 'newAbstain', array(), array(
				'status' => AuthenticationResponse::ABSTAIN,
			) ),

			array( 'newUI', array( array( 'foo' ), $msg ), array(
				'status' => AuthenticationResponse::UI,
				'neededRequests' => array( 'foo' ),
				'message' => $msg,
			) ),

			array( 'newRedirect', array( 'http://example.org/redir' ), array(
				'status' => AuthenticationResponse::REDIRECT,
				'redirectTarget' => 'http://example.org/redir',
			) ),
			array( 'newRedirect', array( 'http://example.org/redir', array( 'foo' => 'bar' ) ), array(
				'status' => AuthenticationResponse::REDIRECT,
				'redirectTarget' => 'http://example.org/redir',
				'redirectApiData' => array( 'foo' => 'bar' ),
			) ),
		);
	}

}
