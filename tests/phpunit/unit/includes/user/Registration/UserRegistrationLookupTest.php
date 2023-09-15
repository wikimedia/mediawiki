<?php

namespace MediaWiki\Tests\User\Registration;

use InvalidArgumentException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\User\Registration\IUserRegistrationProvider;
use MediaWiki\User\Registration\LocalUserRegistrationProvider;
use MediaWiki\User\Registration\UserRegistrationLookup;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * @covers \MediaWiki\User\Registration\UserRegistrationLookup
 */
class UserRegistrationLookupTest extends MediaWikiUnitTestCase {

	public function testIsRegistered() {
		$lookup = new UserRegistrationLookup(
			new ServiceOptions( UserRegistrationLookup::CONSTRUCTOR_OPTIONS, [
				MainConfigNames::UserRegistrationProviders => [
					'local' => [
						'class' => LocalUserRegistrationProvider::class
					],
					'foo' => [
						'class' => 'FooUserRegistrationLookup'
					],
				]
			] ),
			$this->createNoOpMock( ObjectFactory::class )
		);

		$this->assertTrue( $lookup->isRegistered( 'local' ) );
		$this->assertTrue( $lookup->isRegistered( 'foo' ) );
		$this->assertFalse( $lookup->isRegistered( 'bar' ) );
	}

	public function testGetRegistration() {
		$userIdentity = new UserIdentityValue( 123, 'Admin' );
		$userRegistrationProviderMock = $this->createMock( IUserRegistrationProvider::class );
		$userRegistrationProviderMock->expects( $this->once() )
			->method( 'fetchRegistration' )
			->with( $userIdentity )
			->willReturn( '20200101000000' );
		$objectFactoryMock = $this->createMock( ObjectFactory::class );
		$objectFactoryMock->expects( $this->once() )
			->method( 'createObject' )
			->with( [ 'class' => LocalUserRegistrationProvider::class ] )
			->willReturn( $userRegistrationProviderMock );

		$lookup = new UserRegistrationLookup(
			new ServiceOptions( UserRegistrationLookup::CONSTRUCTOR_OPTIONS, [
				MainConfigNames::UserRegistrationProviders => [
					'local' => [
						'class' => LocalUserRegistrationProvider::class
					],
				]
			] ),
			$objectFactoryMock
		);

		$this->assertSame( '20200101000000', $lookup->getRegistration( $userIdentity ) );
	}

	public function testGetRegistrationFails() {
		$this->expectException( InvalidArgumentException::class );

		$userIdentity = new UserIdentityValue( 123, 'Admin' );
		$lookup = new UserRegistrationLookup(
			new ServiceOptions( UserRegistrationLookup::CONSTRUCTOR_OPTIONS, [
				MainConfigNames::UserRegistrationProviders => []
			] ),
			$this->createNoOpMock( ObjectFactory::class )
		);
		$lookup->getRegistration( $userIdentity, 'invalid' );
	}
}
