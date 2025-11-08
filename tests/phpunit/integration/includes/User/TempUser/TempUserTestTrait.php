<?php

namespace MediaWiki\Tests\User\TempUser;

use MediaWiki\MainConfigNames;

/**
 * Helper trait for defining the temporary user configuration settings for tests.
 * This can only be used for test classes that extend MediaWikiIntegrationTestCase.
 *
 * @stable to use
 * @since 1.42
 */
trait TempUserTestTrait {

	/**
	 * Array of default configuration to use in tests.
	 */
	private function getTempAccountConfigTestDefaults(): array {
		return [
			'expireAfterDays' => 90,
			'notifyBeforeExpirationDays' => 10,
			'actions' => [ 'edit' ],
			'genPattern' => '~$1',
			'reservedPattern' => '~$1',
			'serialProvider' => [ 'type' => 'local', 'useYear' => true ],
			'serialMapping' => [ 'type' => 'plain-numeric' ],
		];
	}

	/**
	 * Loads configuration that enables the automatic creation of temporary accounts using the defaults
	 * for the generation pattern and match pattern.
	 *
	 * @param array $configOverrides Specify overrides to the default wgAutoCreateTempUser configuration
	 *   setting (all values are the default except 'enabled' which is set to true).
	 * @since 1.42
	 */
	protected function enableAutoCreateTempUser( array $configOverrides = [] ): void {
		$configOverrides['enabled'] = true;
		$this->overrideConfigValue(
			MainConfigNames::TempAccountNameAcquisitionThrottle,
			[ 'count' => 0, 'seconds' => 86400 ]
		);
		$this->overrideConfigValue(
			MainConfigNames::AutoCreateTempUser,
			array_merge( $this->getTempAccountConfigTestDefaults(), $configOverrides )
		);
		$this->setGroupPermissions( '*', 'createaccount', true );
	}

	/**
	 * Disables the automatic creation of temporary accounts for the test.
	 *
	 * This is done to avoid exceptions when a test or the code being tested creates an actor for an IP address.
	 *
	 * @param array $configOverrides Specify overrides to the default wgAutoCreateTempUser configuration
	 *     setting (all values are the default except 'enabled' which is set to false).
	 * @since 1.42
	 */
	protected function disableAutoCreateTempUser( array $configOverrides = [] ): void {
		$configOverrides['enabled'] = false;
		$this->overrideConfigValue(
			MainConfigNames::AutoCreateTempUser,
			array_merge( $this->getTempAccountConfigTestDefaults(), $configOverrides )
		);
	}

	/**
	 * Defined to ensure that the class has the overrideConfigValue method that we can use.
	 *
	 * @see \MediaWikiIntegrationTestCase::overrideConfigValue
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	abstract protected function overrideConfigValue( string $key, $value );
}
