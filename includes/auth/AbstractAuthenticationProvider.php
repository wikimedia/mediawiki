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
 * @ingroup Auth
 */

namespace MediaWiki\Auth;

use Config;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\User\UserNameUtils;
use Psr\Log\LoggerInterface;

/**
 * A base class that implements some of the boilerplate for an AuthenticationProvider
 * @stable to extend
 * @ingroup Auth
 * @since 1.27
 */
abstract class AbstractAuthenticationProvider implements AuthenticationProvider {
	/** @var LoggerInterface */
	protected $logger;

	/** @var AuthManager */
	protected $manager;

	/** @var Config */
	protected $config;

	/** @var HookContainer */
	private $hookContainer;

	/** @var HookRunner */
	private $hookRunner;

	/** @var UserNameUtils */
	protected $userNameUtils;

	/**
	 * Initialise with dependencies of an AuthenticationProvider
	 *
	 * @since 1.37
	 * @internal In production code AuthManager will initialize the
	 * AbstractAuthenticationProvider, in tests
	 * AuthenticationProviderTestTrait must be used.
	 *
	 * @param LoggerInterface $logger
	 * @param AuthManager $manager
	 * @param HookContainer $hookContainer
	 * @param Config $config
	 * @param UserNameUtils $userNameUtils
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
	 * @deprecated since 1.37. For extension-defined authentication providers
	 * that were using this method to trigger other work, please override
	 * AbstractAuthenticationProvider::postInitSetup instead. If your extension
	 * was using this to explicitly change the logger of an existing
	 * AuthenticationProvider object, please file a report on phabricator -
	 * there is no non-deprecated way to do this anymore.
	 */
	public function setLogger( LoggerInterface $logger ) {
		wfDeprecated( __METHOD__, '1.37' );
		$this->logger = $logger;
	}

	/**
	 * @deprecated since 1.37. For extension-defined authentication providers
	 * that were using this method to trigger other work, please override
	 * AbstractAuthenticationProvider::postInitSetup instead. If your extension
	 * was using this to explicitly change the AuthManager of an existing
	 * AuthenticationProvider object, please file a report on phabricator -
	 * there is no non-deprecated way to do this anymore.
	 */
	public function setManager( AuthManager $manager ) {
		wfDeprecated( __METHOD__, '1.37' );
		$this->manager = $manager;
	}

	/**
	 * @deprecated since 1.37. For extension-defined authentication providers
	 * that were using this method to trigger other work, please override
	 * AbstractAuthenticationProvider::postInitSetup instead. If your extension
	 * was using this to explicitly change the Config of an existing
	 * AuthenticationProvider object, please file a report on phabricator -
	 * there is no non-deprecated way to do this anymore.
	 * @param Config $config
	 */
	public function setConfig( Config $config ) {
		wfDeprecated( __METHOD__, '1.37' );
		$this->config = $config;
	}

	/**
	 * @deprecated since 1.37. For extension-defined authentication providers
	 * that were using this method to trigger other work, please override
	 * AbstractAuthenticationProvider::postInitSetup instead. If your extension
	 * was using this to explicitly change the HookContainer of an existing
	 * AuthenticationProvider object, please file a report on phabricator -
	 * there is no non-deprecated way to do this anymore.
	 */
	public function setHookContainer( HookContainer $hookContainer ) {
		wfDeprecated( __METHOD__, '1.37' );
		$this->hookContainer = $hookContainer;
		$this->hookRunner = new HookRunner( $hookContainer );
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
