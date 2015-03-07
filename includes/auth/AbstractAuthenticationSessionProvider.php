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
 * An base class to handle the boilerplate for AuthenticationSessionProvider
 * @ingroup Auth
 * @since 1.26
 */
abstract class AbstractAuthenticationSessionProvider implements AuthenticationSessionProvider {

	/** @var LoggerInterface */
	protected $logger;

	/** @var Config */
	protected $config;

	/** @var BagOStuff */
	protected $store;

	/**
	 * @param LoggerInterface $logger
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @param Config $config
	 */
	public function setConfig( Config $config ) {
		$this->config = $config;
	}

	/**
	 * @param BagOStuff $store
	 */
	public function setStore( BagOStuff $store ) {
		$this->store = $store;
	}

	/**
	 * @param WebRequest $request
	 * @param bool $empty If true, the returned session should have no user and no key set.
	 * @return AuthenticationSession|null
	 */
	abstract public function provideAuthenticationSession( WebRequest $request, $empty = false );

	/**
	 * @param string $username
	 * @return bool
	 */
	public function immutableSessionCouldExistForUser( $username ) {
		return false;
	}

	/**
	 * Prevent immutable sessions from being created for the user.
	 *
	 * This might add the username to a blacklist, or it might just delete
	 * whatever authentication credentials would allow such a session in the
	 * first place (e.g. remove all OAuth grants or delete record of the SSL
	 * client certificate).
	 *
	 * @param string $username
	 */
	public function preventImmutableSessionsForUser( $username ) {
	}

	/**
	 * @return MessageSpecifier
	 */
	abstract public function describeSessions();

	/**
	 * @return array
	 */
	public function getVaryHeaders() {
		return array();
	}

	/**
	 * @return string[]
	 */
	public function getVaryCookies() {
		return array();
	}

}
