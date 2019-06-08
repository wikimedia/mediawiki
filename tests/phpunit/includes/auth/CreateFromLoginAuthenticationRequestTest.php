<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\CreateFromLoginAuthenticationRequest
 */
class CreateFromLoginAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = [] ) {
		return new CreateFromLoginAuthenticationRequest(
			null, []
		);
	}

	public function provideLoadFromSubmission() {
		return [
			'Empty request' => [
				[],
				[],
				[],
			],
		];
	}

	/**
	 * @dataProvider provideState
	 */
	public function testState(
		$createReq, $maybeLink, $username, $loginState, $createState, $createPrimaryState
	) {
		$req = new CreateFromLoginAuthenticationRequest( $createReq, $maybeLink );
		$this->assertSame( $username, $req->username );
		$this->assertSame( $loginState, $req->hasStateForAction( AuthManager::ACTION_LOGIN ) );
		$this->assertSame( $createState, $req->hasStateForAction( AuthManager::ACTION_CREATE ) );
		$this->assertFalse( $req->hasStateForAction( AuthManager::ACTION_LINK ) );
		$this->assertFalse( $req->hasPrimaryStateForAction( AuthManager::ACTION_LOGIN ) );
		$this->assertSame( $createPrimaryState,
			$req->hasPrimaryStateForAction( AuthManager::ACTION_CREATE ) );
	}

	public static function provideState() {
		$req1 = new UsernameAuthenticationRequest;
		$req2 = new UsernameAuthenticationRequest;
		$req2->username = 'Bob';

		return [
			'Nothing' => [ null, [], null, false, false, false ],
			'Link, no create' => [ null, [ $req2 ], null, true, true, false ],
			'No link, create but no name' => [ $req1, [], null, false, true, true ],
			'Link and create but no name' => [ $req1, [ $req2 ], null, true, true, true ],
			'No link, create with name' => [ $req2, [], 'Bob', false, true, true ],
			'Link and create with name' => [ $req2, [ $req2 ], 'Bob', true, true, true ],
		];
	}
}
