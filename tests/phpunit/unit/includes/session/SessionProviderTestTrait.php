<?php

namespace MediaWiki\Tests\Session;

use MediaWiki\Config\Config;
use MediaWiki\Config\HashConfig;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Session\SessionManager;
use MediaWiki\Session\SessionProvider;
use MediaWiki\User\UserNameUtils;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * A trait providing utility function for testing subclasses of
 * SessionProvider. This trait is intended to be used on
 * subclasses of MediaWikiIntegrationTestCase or MediaWikiUnitTestCase.
 *
 * @stable to use
 */
trait SessionProviderTestTrait {

	/**
	 * Calls init() on an SessionProvider.
	 *
	 * @param SessionProvider $provider
	 * @param LoggerInterface|null $logger
	 * @param Config|null $config
	 * @param SessionManager|null $manager
	 * @param HookContainer|null $hookContainer
	 * @param UserNameUtils|null $userNameUtils
	 */
	private function initProvider(
		SessionProvider $provider,
		?LoggerInterface $logger = null,
		?Config $config = null,
		?SessionManager $manager = null,
		?HookContainer $hookContainer = null,
		?UserNameUtils $userNameUtils = null
	) {
		$provider->init(
			$logger ?? new NullLogger(),
			$config ?? new HashConfig(),
			$manager ?? $this->createNoOpMock( SessionManager::class ),
			$hookContainer ?? $this->createHookContainer(),
			$userNameUtils ?? $this->createNoOpMock( UserNameUtils::class )
		);
	}
}
