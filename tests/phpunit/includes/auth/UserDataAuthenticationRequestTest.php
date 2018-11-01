<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\UserDataAuthenticationRequest
 */
class UserDataAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = [] ) {
		return new UserDataAuthenticationRequest;
	}

	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( 'wgHiddenPrefs', [] );
	}

	/**
	 * @dataProvider providePopulateUser
	 * @param string $email Email to set
	 * @param string $realname Realname to set
	 * @param StatusValue $expect Expected return
	 */
	public function testPopulateUser( $email, $realname, $expect ) {
		$user = new \User();
		$user->setEmail( 'default@example.com' );
		$user->setRealName( 'Fake Name' );

		$req = new UserDataAuthenticationRequest;
		$req->email = $email;
		$req->realname = $realname;
		$this->assertEquals( $expect, $req->populateUser( $user ) );
		if ( $expect->isOk() ) {
			$this->assertSame( $email ?: 'default@example.com', $user->getEmail() );
			$this->assertSame( $realname ?: 'Fake Name', $user->getRealName() );
		}
	}

	public static function providePopulateUser() {
		$good = \StatusValue::newGood();
		return [
			[ 'email@example.com', 'Real Name', $good ],
			[ 'email@example.com', '', $good ],
			[ '', 'Real Name', $good ],
			[ '', '', $good ],
			[ 'invalid-email', 'Real Name', \StatusValue::newFatal( 'invalidemailaddress' ) ],
		];
	}

	/**
	 * @dataProvider provideLoadFromSubmission
	 */
	public function testLoadFromSubmission(
		array $args, array $data, $expectState /* $hiddenPref, $enableEmail */
	) {
		list( $args, $data, $expectState, $hiddenPref, $enableEmail ) = func_get_args();
		$this->setMwGlobals( 'wgHiddenPrefs', $hiddenPref );
		$this->setMwGlobals( 'wgEnableEmail', $enableEmail );
		parent::testLoadFromSubmission( $args, $data, $expectState );
	}

	public function provideLoadFromSubmission() {
		$unhidden = [];
		$hidden = [ 'realname' ];

		return [
			'Empty request, unhidden, email enabled' => [
				[],
				[],
				false,
				$unhidden,
				true
			],
			'email + realname, unhidden, email enabled' => [
				[],
				$data = [ 'email' => 'Email', 'realname' => 'Name' ],
				$data,
				$unhidden,
				true
			],
			'email empty, unhidden, email enabled' => [
				[],
				$data = [ 'email' => '', 'realname' => 'Name' ],
				$data,
				$unhidden,
				true
			],
			'email omitted, unhidden, email enabled' => [
				[],
				[ 'realname' => 'Name' ],
				false,
				$unhidden,
				true
			],
			'realname empty, unhidden, email enabled' => [
				[],
				$data = [ 'email' => 'Email', 'realname' => '' ],
				$data,
				$unhidden,
				true
			],
			'realname omitted, unhidden, email enabled' => [
				[],
				[ 'email' => 'Email' ],
				false,
				$unhidden,
				true
			],
			'Empty request, hidden, email enabled' => [
				[],
				[],
				false,
				$hidden,
				true
			],
			'email + realname, hidden, email enabled' => [
				[],
				[ 'email' => 'Email', 'realname' => 'Name' ],
				[ 'email' => 'Email' ],
				$hidden,
				true
			],
			'email empty, hidden, email enabled' => [
				[],
				$data = [ 'email' => '', 'realname' => 'Name' ],
				[ 'email' => '' ],
				$hidden,
				true
			],
			'email omitted, hidden, email enabled' => [
				[],
				[ 'realname' => 'Name' ],
				false,
				$hidden,
				true
			],
			'realname empty, hidden, email enabled' => [
				[],
				$data = [ 'email' => 'Email', 'realname' => '' ],
				[ 'email' => 'Email' ],
				$hidden,
				true
			],
			'realname omitted, hidden, email enabled' => [
				[],
				[ 'email' => 'Email' ],
				[ 'email' => 'Email' ],
				$hidden,
				true
			],
			'email + realname, unhidden, email disabled' => [
				[],
				[ 'email' => 'Email', 'realname' => 'Name' ],
				[ 'realname' => 'Name' ],
				$unhidden,
				false
			],
			'email omitted, unhidden, email disabled' => [
				[],
				[ 'realname' => 'Name' ],
				[ 'realname' => 'Name' ],
				$unhidden,
				false
			],
			'email empty, unhidden, email disabled' => [
				[],
				[ 'email' => '', 'realname' => 'Name' ],
				[ 'realname' => 'Name' ],
				$unhidden,
				false
			],
		];
	}
}
