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

use Psr\Log\LoggerInterface;

/**
 * A base class that implements some of the boilerplate for an AuthenticationProvider
 * @ingroup Auth
 * @since 1.26
 * @deprecated since 1.26
 */
abstract class AbstractAuthenticationProvider implements AuthenticationProvider {
	/** @var LoggerInterface */
	protected $logger;
	/** @var AuthManager */
	protected $manager;
	/** @var Config */
	protected $config;

	/**
	 * @param LoggerInterface $logger
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @param AuthManager $manager
	 */
	public function setManager( AuthManager $manager ) {
		$this->manager = $manager;
	}

	/**
	 * @param Config $config
	 */
	public function setConfig( Config $config ) {
		$this->config = $config;
	}

	/**
	 * @note Override this if if makes sense to support more than one instance
	 * @return string
	 */
	public function getUniqueId() {
		return get_class( $this );
	}

}
