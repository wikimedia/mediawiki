<?php

namespace MediaWiki\Tests\User\Registration;

use MediaWiki\Config\ConfigException;
use MediaWiki\MainConfigNames;
use MediaWiki\User\Registration\IUserRegistrationProvider;
use MediaWiki\User\Registration\UserRegistrationLookup;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\User\Registration\UserRegistrationLookup
 * @group Database
 */
class UserRegistrationLookupIntegrationTest extends MediaWikiIntegrationTestCase {
	public function testLocalRequired() {
		$this->expectException( ConfigException::class );

		$this->overrideConfigValue( MainConfigNames::UserRegistrationProviders, [] );
		$this->assertInstanceOf(
			UserRegistrationLookup::class,
			$this->getServiceContainer()->getUserRegistrationLookup()
		);
	}

	public function testLocal() {
		$user = $this->getMutableTestUser()->getUser();
		$dbw = $this->getDb();
		$dbw->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [ 'user_registration' => $dbw->timestamp( '20050101000000' ) ] )
			->where( [ 'user_id' => $user->getId() ] )
			->caller( __METHOD__ )
			->execute();

		$this->assertSame(
			'20050101000000',
			$this->getServiceContainer()->getUserRegistrationLookup()->getRegistration(
				$this->getServiceContainer()->getUserFactory()->newFromName( $user->getName() )
			)
		);
	}

	public function testCustom() {
		$user = $this->getTestUser()->getUser();
		$otherUser = $this->getMutableTestUser( [], 'OtherUser' )->getUser();

		$providers = $this->getConfVar( MainConfigNames::UserRegistrationProviders );

		$testProvider = $this->createMock( IUserRegistrationProvider::class );
		$testProvider->method( 'fetchRegistration' )
			->with( $user )
			->willReturn( '20230101000000' );
		$testProvider->method( 'fetchRegistrationBatch' )
			->with( [ $user, $otherUser ] )
			->willReturn( [
				$user->getId() => '20230101000000',
				$otherUser->getId() => '20230201000000',
			] );

		$providers['test-foo'] = [
			'factory' => static fn () => $testProvider,
		];

		$this->overrideConfigValue( MainConfigNames::UserRegistrationProviders, $providers );

		$this->assertNotSame( $user->getId(), $otherUser->getId() );
		$this->assertSame(
			'20230101000000',
			$this->getServiceContainer()->getUserRegistrationLookup()->getRegistration(
				$user,
				'test-foo'
			)
		);

		$this->assertSame(
			'20230101000000',
			$this->getServiceContainer()->getUserRegistrationLookup()->getFirstRegistration(
				$user
			)
		);

		$this->assertSame(
			[
				$user->getId() => '20230101000000',
				$otherUser->getId() => '20230201000000',
			],
			$this->getServiceContainer()->getUserRegistrationLookup()->getFirstRegistrationBatch(
				[ $user, $otherUser ]
			)
		);
	}
}
