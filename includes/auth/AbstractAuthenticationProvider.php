<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Auth;

use MediaWiki\Config\Config;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\User\UserNameUtils;
use Psr\Log\LoggerInterface;

/**
 * A base class that implements some of the boilerplate for an AuthenticationProvider
 *
 * @stable to extend
 * @ingroup Auth
 * @since 1.27
 */
abstract class AbstractAuthenticationProvider implements AuthenticationProvider {
	protected LoggerInterface $logger;
	protected AuthManager $manager;
	protected Config $config;
	private HookContainer $hookContainer;
	private HookRunner $hookRunner;
	protected UserNameUtils $userNameUtils;

	/**
	 * Initialise with dependencies of an AuthenticationProvider
	 *
	 * @since 1.37
	 * @internal In production code AuthManager will initialize the
	 * AbstractAuthenticationProvider, in tests
	 * AuthenticationProviderTestTrait must be used.
	 */
	public function init(
		LoggerInterface $logger,
		AuthManager $manager,
		HookContainer $hookContainer,
		Config $config,
		UserNameUtils $userNameUtils
	) {
		$this->logger = $logger;
		$this->manager = $manager;
		$this->hookContainer = $hookContainer;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->config = $config;
		$this->userNameUtils = $userNameUtils;
		$this->postInitSetup();
	}

	/**
	 * A provider can override this to do any necessary setup after init()
	 * is called.
	 *
	 * @since 1.37
	 * @stable to override
	 */
	protected function postInitSetup() {
	}

	/**
	 * @inheritDoc
	 * @note Override this if it makes sense to support more than one instance
	 */
	public function getUniqueId() {
		return static::class;
	}

	/**
	 * @since 1.35
	 * @return HookContainer
	 */
	protected function getHookContainer(): HookContainer {
		return $this->hookContainer;
	}

	/**
	 * @internal This is for use by core only. Hook interfaces may be removed
	 *   without notice.
	 * @since 1.35
	 * @return HookRunner
	 */
	protected function getHookRunner(): HookRunner {
		return $this->hookRunner;
	}
}
