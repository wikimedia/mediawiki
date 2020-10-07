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

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	public function setManager( AuthManager $manager ) {
		$this->manager = $manager;
	}

	/**
	 * @stable to override
	 * @param Config $config
	 */
	public function setConfig( Config $config ) {
		$this->config = $config;
	}

	public function setHookContainer( HookContainer $hookContainer ) {
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
	protected function getHookContainer() : HookContainer {
		return $this->hookContainer;
	}

	/**
	 * @internal This is for use by core only. Hook interfaces may be removed
	 *   without notice.
	 * @since 1.35
	 * @return HookRunner
	 */
	protected function getHookRunner() : HookRunner {
		return $this->hookRunner;
	}
}
