<?php

namespace MediaWiki\Auth;

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\UserDataAuthenticationRequest
 */
class UserDataAuthenticationRequestTest extends AuthenticationRequestTestCase {
	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( 'wgHiddenPrefs', array() );
	}

	/**
	 * @dataProvider providePopulateUser
	 */
	public function testPopulateUser( $email, $realname ) {
		$user = new \User();
		$user->setEmail( 'default@example.com' );
		$user->setRealName( 'Fake Name' );

		$req = new UserDataAuthenticationRequest();
		$req->loadFromSubmission( array(
			'email' => $email,
			'realname' => $realname,
		) );

		$req->email = $email;
		$req->realname = $realname;
		$req->populateUser( $user );
		$this->assertSame( $email ?: 'default@example.com', $user->getEmail() );
		$this->assertSame( $realname ?: 'Fake Name', $user->getRealName() );

	}

	public static function providePopulateUser() {
		return array(
			array( 'email@example.com', 'Real Name' ),
			array( 'email@example.com', '' ),
			array( '', 'Real Name' ),
			array( '', '' ),
		);
	}

	public function provideGetFieldInfo() {
		return array( array( new UserDataAuthenticationRequest() ) );
	}

	/**
	 * @dataProvider provideLoadFromSubmission
	 */
	public function testLoadFromSubmission( $label, $req, $data, $expectState /* $hiddenPref */ ) {
		list( $label, $req, $data, $expectState, $hiddenPref ) = func_get_args();
		$this->setMwGlobals( 'wgHiddenPrefs', $hiddenPref );
		parent::testLoadFromSubmission( $label, $req, $data, $expectState );
	}

	public function provideLoadFromSubmission() {
		$unhidden = array();
		$hidden = array( 'realname' );

		return array(
			array(
				'Empty request, unhidden',
				new UserDataAuthenticationRequest(),
				array(),
				null,
				$unhidden
			),
			array(
				'email + realname, unhidden',
				new UserDataAuthenticationRequest(),
				$data = array( 'email' => 'Email', 'realname' => 'Name' ),
				$data,
				$unhidden
			),
			array(
				'email empty, unhidden',
				new UserDataAuthenticationRequest(),
				$data = array( 'email' => '', 'realname' => 'Name' ),
				$data,
				$unhidden
			),
			array(
				'email omitted, unhidden',
				new UserDataAuthenticationRequest(),
				array( 'realname' => 'Name' ),
				null,
				$unhidden
			),
			array(
				'realname empty, unhidden',
				new UserDataAuthenticationRequest(),
				$data = array( 'email' => 'Email', 'realname' => '' ),
				$data,
				$unhidden
			),
			array(
				'realname omitted, unhidden',
				new UserDataAuthenticationRequest(),
				array( 'email' => 'Email' ),
				null,
				$unhidden
			),
			array(
				'Empty request, hidden',
				new UserDataAuthenticationRequest(),
				array(),
				null,
				$hidden
			),
			array(
				'email + realname, hidden',
				new UserDataAuthenticationRequest(),
				array( 'email' => 'Email', 'realname' => 'Name' ),
				array( 'email' => 'Email' ),
				$hidden
			),
			array(
				'email empty, hidden',
				new UserDataAuthenticationRequest(),
				$data = array( 'email' => '', 'realname' => 'Name' ),
				array( 'email' => '' ),
				$hidden
			),
			array(
				'email omitted, hidden',
				new UserDataAuthenticationRequest(),
				array( 'realname' => 'Name' ),
				null,
				$hidden
			),
			array(
				'realname empty, hidden',
				new UserDataAuthenticationRequest(),
				$data = array( 'email' => 'Email', 'realname' => '' ),
				array( 'email' => 'Email' ),
				$hidden
			),
			array(
				'realname omitted, hidden',
				new UserDataAuthenticationRequest(),
				array( 'email' => 'Email' ),
				array( 'email' => 'Email' ),
				$hidden
			),
		);
	}
}
