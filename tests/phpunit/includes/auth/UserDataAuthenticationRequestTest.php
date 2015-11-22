<?php

namespace MediaWiki\Auth;

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\UserDataAuthenticationRequest
 */
class UserDataAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = array() ) {
		return new UserDataAuthenticationRequest;
	}

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

		$req = new UserDataAuthenticationRequest;
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
	 * @dataProvider provideLoadFromSubmission
	 */
	public function testLoadFromSubmission(
		$label, array $args, array $data, $expectState /* $hiddenPref */
	) {
		list( $label, $args, $data, $expectState, $hiddenPref ) = func_get_args();
		$this->setMwGlobals( 'wgHiddenPrefs', $hiddenPref );
		parent::testLoadFromSubmission( $label, $args, $data, $expectState );
	}

	public function provideLoadFromSubmission() {
		$unhidden = array();
		$hidden = array( 'realname' );

		return array(
			array(
				'Empty request, unhidden',
				array(),
				array(),
				false,
				$unhidden
			),
			array(
				'email + realname, unhidden',
				array(),
				$data = array( 'email' => 'Email', 'realname' => 'Name' ),
				$data,
				$unhidden
			),
			array(
				'email empty, unhidden',
				array(),
				$data = array( 'email' => '', 'realname' => 'Name' ),
				$data,
				$unhidden
			),
			array(
				'email omitted, unhidden',
				array(),
				array( 'realname' => 'Name' ),
				false,
				$unhidden
			),
			array(
				'realname empty, unhidden',
				array(),
				$data = array( 'email' => 'Email', 'realname' => '' ),
				$data,
				$unhidden
			),
			array(
				'realname omitted, unhidden',
				array(),
				array( 'email' => 'Email' ),
				false,
				$unhidden
			),
			array(
				'Empty request, hidden',
				array(),
				array(),
				false,
				$hidden
			),
			array(
				'email + realname, hidden',
				array(),
				array( 'email' => 'Email', 'realname' => 'Name' ),
				array( 'email' => 'Email' ),
				$hidden
			),
			array(
				'email empty, hidden',
				array(),
				$data = array( 'email' => '', 'realname' => 'Name' ),
				array( 'email' => '' ),
				$hidden
			),
			array(
				'email omitted, hidden',
				array(),
				array( 'realname' => 'Name' ),
				false,
				$hidden
			),
			array(
				'realname empty, hidden',
				array(),
				$data = array( 'email' => 'Email', 'realname' => '' ),
				array( 'email' => 'Email' ),
				$hidden
			),
			array(
				'realname omitted, hidden',
				array(),
				array( 'email' => 'Email' ),
				array( 'email' => 'Email' ),
				$hidden
			),
		);
	}
}
