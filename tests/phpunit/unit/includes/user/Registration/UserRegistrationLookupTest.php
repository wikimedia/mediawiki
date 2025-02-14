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
use Psr\Container\ContainerInterface;
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

	/** @dataProvider provideGetFirstRegistration */
	public function testGetFirstRegistration( $valueLocal, $valueFoo, $expected ) {
		$userIdentity = new UserIdentityValue( 123, 'Admin' );
		$userRegistrationProviderMockLocal = $this->createMock( IUserRegistrationProvider::class );
		$userRegistrationProviderMockLocal->expects( $this->once() )
			->method( 'fetchRegistration' )
			->with( $userIdentity )
			->willReturn( $valueLocal );
		$userRegistrationProviderMockFoo = $this->createMock( IUserRegistrationProvider::class );
		$userRegistrationProviderMockFoo->expects( $this->once() )
			->method( 'fetchRegistration' )
			->with( $userIdentity )
			->willReturn( $valueFoo );
		$objectFactoryMock = $this->createMock( ObjectFactory::class );
		$objectFactoryMock->expects( $this->exactly( 2 ) )
			->method( 'createObject' )
			->willReturnCallback( static function ( $arg ) use ( $userRegistrationProviderMockLocal, $userRegistrationProviderMockFoo ) {
				if ( $arg === [ 'class' => LocalUserRegistrationProvider::class ] ) {
					return $userRegistrationProviderMockLocal;
				}
				return $userRegistrationProviderMockFoo;
			} );

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
			$objectFactoryMock
		);

		$this->assertSame( $expected, $lookup->getFirstRegistration( $userIdentity ) );
	}

	public static function provideGetFirstRegistration() {
		return [
			[ '20200101000000', '20190101000000', '20190101000000' ],
			[ null, false, null ],
			[ null, '20190101000000', '20190101000000' ],
			[ '20200101000000', null, '20200101000000' ],
		];
	}

	public function testGetFirstRegistrationBatchShouldSelectEarliestNonNullTimestampPerUser(): void {
		$user = new UserIdentityValue( 1, 'TestUser' );
		$otherUser = new UserIdentityValue( 2, 'OtherUser' );

		$localProvider = $this->createMock( IUserRegistrationProvider::class );
		$localProvider->method( 'fetchRegistrationBatch' )
			->with( [ $user, $otherUser ] )
			->willReturn( [
				$user->getId() => '20200101000000',
				$otherUser->getId() => null,
			] );
		$otherProvider = $this->createMock( IUserRegistrationProvider::class );
		$otherProvider->method( 'fetchRegistrationBatch' )
			->with( [ $user, $otherUser ] )
			->willReturn( [
				$user->getId() => '20240102000000',
				$otherUser->getId() => '20230103000000',
			] );

		$lookup = new UserRegistrationLookup(
			new ServiceOptions( UserRegistrationLookup::CONSTRUCTOR_OPTIONS, [
				MainConfigNames::UserRegistrationProviders => [
					LocalUserRegistrationProvider::TYPE => [
						'factory' => static fn () => $localProvider,
					],
					'other' => [
						'factory' => static fn () => $otherProvider,
					],
				]
			] ),
			new ObjectFactory( $this->createMock( ContainerInterface::class ) )
		);

		$earliestTimestampsById = $lookup->getFirstRegistrationBatch( [ $user, $otherUser ] );

		$this->assertCount( 2, $earliestTimestampsById );
		$this->assertSame( '20200101000000', $earliestTimestampsById[$user->getId()] );
		$this->assertSame( '20230103000000', $earliestTimestampsById[$otherUser->getId()] );
	}

	public function testGetFirstRegistrationBatchShouldHandleNoUsers(): void {
		$localProvider = $this->createMock( IUserRegistrationProvider::class );
		$localProvider->method( 'fetchRegistrationBatch' )
			->with( [] )
			->willReturn( [] );
		$otherProvider = $this->createMock( IUserRegistrationProvider::class );
		$otherProvider->method( 'fetchRegistrationBatch' )
			->with( [] )
			->willReturn( [] );

		$lookup = new UserRegistrationLookup(
			new ServiceOptions( UserRegistrationLookup::CONSTRUCTOR_OPTIONS, [
				MainConfigNames::UserRegistrationProviders => [
					LocalUserRegistrationProvider::TYPE => [
						'factory' => static fn () => $localProvider,
					],
					'other' => [
						'factory' => static fn () => $otherProvider,
					],
				]
			] ),
			new ObjectFactory( $this->createMock( ContainerInterface::class ) )
		);

		$earliestTimestampsById = $lookup->getFirstRegistrationBatch( [] );

		$this->assertSame( [], $earliestTimestampsById );
	}
}
