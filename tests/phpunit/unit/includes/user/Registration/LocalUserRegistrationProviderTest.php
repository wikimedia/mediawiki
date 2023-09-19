<?php

namespace MediaWiki\Tests\User\Registration;

use MediaWiki\User\Registration\LocalUserRegistrationProvider;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\User\Registration\LocalUserRegistrationProvider
 */
class LocalUserRegistrationProviderTest extends MediaWikiUnitTestCase {

	public function testFetchRegistration() {
		$userIdentity = new UserIdentityValue( 123, 'Admin' );
		$userMock = $this->createMock( User::class );
		$userMock->expects( $this->once() )
			->method( 'getRegistration' )
			->willReturn( '20200102000000' );
		$userFactoryMock = $this->createMock( UserFactory::class );
		$userFactoryMock->expects( $this->once() )
			->method( 'newFromUserIdentity' )
			->with( $userIdentity )
			->willReturn( $userMock );

		$provider = new LocalUserRegistrationProvider( $userFactoryMock );
		$this->assertSame( '20200102000000', $provider->fetchRegistration( $userIdentity ) );
	}
}
