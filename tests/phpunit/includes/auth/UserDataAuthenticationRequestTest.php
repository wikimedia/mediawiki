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
		$label, array $args, array $data, $expectState /* $hiddenPref, $enableEmail */
	) {
		list( $label, $args, $data, $expectState, $hiddenPref, $enableEmail ) = func_get_args();
		$this->setMwGlobals( 'wgHiddenPrefs', $hiddenPref );
		$this->setMwGlobals( 'wgEnableEmail', $enableEmail );
		parent::testLoadFromSubmission( $label, $args, $data, $expectState );
	}

	public function provideLoadFromSubmission() {
		$unhidden = array();
		$hidden = array( 'realname' );

		return array(
			array(
				'Empty request, unhidden, email enabled',
				array(),
				array(),
				false,
				$unhidden,
				true
			),
			array(
				'email + realname, unhidden, email enabled',
				array(),
				$data = array( 'email' => 'Email', 'realname' => 'Name' ),
				$data,
				$unhidden,
				true
			),
			array(
				'email empty, unhidden, email enabled',
				array(),
				$data = array( 'email' => '', 'realname' => 'Name' ),
				$data,
				$unhidden,
				true
			),
			array(
				'email omitted, unhidden, email enabled',
				array(),
				array( 'realname' => 'Name' ),
				false,
				$unhidden,
				true
			),
			array(
				'realname empty, unhidden, email enabled',
				array(),
				$data = array( 'email' => 'Email', 'realname' => '' ),
				$data,
				$unhidden,
				true
			),
			array(
				'realname omitted, unhidden, email enabled',
				array(),
				array( 'email' => 'Email' ),
				false,
				$unhidden,
				true
			),
			array(
				'Empty request, hidden, email enabled',
				array(),
				array(),
				false,
				$hidden,
				true
			),
			array(
				'email + realname, hidden, email enabled',
				array(),
				array( 'email' => 'Email', 'realname' => 'Name' ),
				array( 'email' => 'Email' ),
				$hidden,
				true
			),
			array(
				'email empty, hidden, email enabled',
				array(),
				$data = array( 'email' => '', 'realname' => 'Name' ),
				array( 'email' => '' ),
				$hidden,
				true
			),
			array(
				'email omitted, hidden, email enabled',
				array(),
				array( 'realname' => 'Name' ),
				false,
				$hidden,
				true
			),
			array(
				'realname empty, hidden, email enabled',
				array(),
				$data = array( 'email' => 'Email', 'realname' => '' ),
				array( 'email' => 'Email' ),
				$hidden,
				true
			),
			array(
				'realname omitted, hidden, email enabled',
				array(),
				array( 'email' => 'Email' ),
				array( 'email' => 'Email' ),
				$hidden,
				true
			),
			array(
				'email + realname, unhidden, email disabled',
				array(),
				array( 'email' => 'Email', 'realname' => 'Name' ),
				array( 'realname' => 'Name' ),
				$unhidden,
				false
			),
			array(
				'email omitted, unhidden, email disabled',
				array(),
				array( 'realname' => 'Name' ),
				array( 'realname' => 'Name' ),
				$unhidden,
				false
			),
			array(
				'email empty, unhidden, email disabled',
				array(),
				array( 'email' => '', 'realname' => 'Name' ),
				array( 'realname' => 'Name' ),
				$unhidden,
				false
			),
		);
	}
}
