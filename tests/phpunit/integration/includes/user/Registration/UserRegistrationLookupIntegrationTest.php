<?php

namespace MediaWiki\Tests\User\Registration;

use MediaWiki\Config\ConfigException;
use MediaWiki\MainConfigNames;
use MediaWiki\User\Registration\IUserRegistrationProvider;
use MediaWiki\User\Registration\UserRegistrationLookup;
use MediaWiki\User\UserIdentity;
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
		$dbw->update(
			'user',
			[ 'user_registration' => $dbw->timestamp( '20050101000000' ) ],
			[ 'user_id' => $user->getId() ],
			__METHOD__
		);

		$this->assertSame(
			'20050101000000',
			$this->getServiceContainer()->getUserRegistrationLookup()->getRegistration(
				$this->getServiceContainer()->getUserFactory()->newFromName( $user->getName() )
			)
		);
	}

	public function testCustom() {
		$providers = $this->getConfVar( MainConfigNames::UserRegistrationProviders );
		$providers['test-foo'] = [
			'factory' => static function () {
				return new class implements IUserRegistrationProvider {
					/**
					 * @inheritDoc
					 */
					public function fetchRegistration( UserIdentity $user ) {
						return '20230101000000';
					}
				};
			}
		];
		$this->overrideConfigValue( MainConfigNames::UserRegistrationProviders, $providers );

		$user = $this->getTestUser()->getUser();
		$this->assertSame(
			'20230101000000',
			$this->getServiceContainer()->getUserRegistrationLookup()->getRegistration(
				$user,
				'test-foo'
			)
		);
	}
}
