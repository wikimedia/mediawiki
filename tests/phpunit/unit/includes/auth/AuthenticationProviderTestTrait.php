<?php

namespace MediaWiki\Tests\Unit\Auth;

use MediaWiki\Auth\AbstractAuthenticationProvider;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Config\Config;
use MediaWiki\Config\HashConfig;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\User\UserNameUtils;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * A trait providing utility function for testing subclasses of
 * AbstractAuthenticationProvider. This trait is intended to be used on
 * subclasses of MediaWikiIntegrationTestCase or MediaWikiUnitTestCase.
 *
 * @stable to use
 */

trait AuthenticationProviderTestTrait {

	/**
	 * Calls init() on an AuthenticationProvider.
	 *
	 * @param AbstractAuthenticationProvider $provider
	 * @param Config|null $config
	 * @param LoggerInterface|null $logger
	 * @param AuthManager|null $manager
	 * @param HookContainer|null $hookContainer
	 * @param UserNameUtils|null $userNameUtils
	 */
	private function initProvider(
		AbstractAuthenticationProvider $provider,
		?Config $config = null,
		?LoggerInterface $logger = null,
		?AuthManager $manager = null,
		?HookContainer $hookContainer = null,
		?UserNameUtils $userNameUtils = null
	) {
		$provider->init(
			$logger ?? new NullLogger(),
			$manager ?? $this->createNoOpMock( AuthManager::class ),
			$hookContainer ?? $this->createHookContainer(),
			$config ?? new HashConfig(),
			$userNameUtils ?? $this->createNoOpMock( UserNameUtils::class )
		);
	}
}
