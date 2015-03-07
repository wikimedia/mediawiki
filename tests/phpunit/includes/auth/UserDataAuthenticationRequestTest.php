<?php

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers UserDataAuthenticationRequest
 */
class UserDataAuthenticationRequestTest extends AuthenticationRequestTestCase {
	protected static $class = 'UserDataAuthenticationRequest';

	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( 'wgHiddenPrefs', array() );
	}

	/**
	 * @dataProvider providePopulateUser
	 * @uses AuthenticationRequest
	 */
	public function testPopulateUser( $email, $realname ) {
		$user = new User();
		$user->setEmail( 'default@example.com' );
		$user->setRealName( 'Fake Name' );

		$req = UserDataAuthenticationRequest::newFromSubmission( array(
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

	/**
	 * @dataProvider provideNewFromSubmission
	 * @uses AuthenticationRequest
	 */
	public function testNewFromSubmission( $label, $data, $expectState /* $hiddenPref */ ) {
		list( $label, $data, $expectState, $hiddenPref ) = func_get_args();
		$this->setMwGlobals( 'wgHiddenPrefs', $hiddenPref );
		parent::testNewFromSubmission( $label, $data, $expectState );
	}

	public function provideNewFromSubmission() {
		$unhidden = array();
		$hidden = array( 'realname' );

		return array(
			array(
				'Empty request, unhidden',
				array(),
				null,
				$unhidden
			),
			array(
				'email + realname, unhidden',
				$data = array( 'email' => 'Email', 'realname' => 'Name' ),
				$data,
				$unhidden
			),
			array(
				'email empty, unhidden',
				$data = array( 'email' => '', 'realname' => 'Name' ),
				$data,
				$unhidden
			),
			array(
				'email omitted, unhidden',
				array( 'realname' => 'Name' ),
				null,
				$unhidden
			),
			array(
				'realname empty, unhidden',
				$data = array( 'email' => 'Email', 'realname' => '' ),
				$data,
				$unhidden
			),
			array(
				'realname omitted, unhidden',
				array( 'email' => 'Email' ),
				null,
				$unhidden
			),
			array(
				'Empty request, hidden',
				array(),
				null,
				$hidden
			),
			array(
				'email + realname, hidden',
				array( 'email' => 'Email', 'realname' => 'Name' ),
				array( 'email' => 'Email' ),
				$hidden
			),
			array(
				'email empty, hidden',
				$data = array( 'email' => '', 'realname' => 'Name' ),
				array( 'email' => '' ),
				$hidden
			),
			array(
				'email omitted, hidden',
				array( 'realname' => 'Name' ),
				null,
				$hidden
			),
			array(
				'realname empty, hidden',
				$data = array( 'email' => 'Email', 'realname' => '' ),
				array( 'email' => 'Email' ),
				$hidden
			),
			array(
				'realname omitted, hidden',
				array( 'email' => 'Email' ),
				array( 'email' => 'Email' ),
				$hidden
			),
		);
	}
}
