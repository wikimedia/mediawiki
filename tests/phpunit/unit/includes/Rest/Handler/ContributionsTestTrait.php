<?php

namespace MediaWiki\Tests\Rest\Handler;

use User;

trait ContributionsTestTrait {

	/**
	 * @param string $name
	 * @return bool|User
	 */
	public function makeMockUser( $name ) {
		$isIP = ( $name === '127.0.0.1' );
		$isUnknown = ( $name === 'UNKNOWN' );
		$isAnon = $isIP || $isUnknown;
		$user = $this->createNoOpMock(
			User::class,
			[ 'isAnon', 'getId', 'getName', 'isRegistered' ]
		);
		$user->method( 'isAnon' )->willReturn( $isAnon );
		$user->method( 'isRegistered' )->willReturn( !$isAnon );
		$user->method( 'getId' )->willReturn( $isAnon ? 0 : 7 );
		$user->method( 'getName' )->willReturn( $name );

		return $user;
	}
}
