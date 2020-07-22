<?php

namespace MediaWiki\Tests\Rest\Handler;

use User;

trait ContributionsTestTrait {

	public function makeMockUser( $name ) {
		$isIP = ( $name === '127.0.0.1' );
		$isBad = ( $name === 'B/A/D' );
		$isUnknown = ( $name === 'UNKNOWN' );
		$isAnon = $isIP || $isBad || $isUnknown;

		if ( $isBad ) {
			// per the contract of UserFactory::newFromName
			return false;
		}

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
