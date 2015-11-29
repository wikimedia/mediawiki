<?php
/**
 * Authentication provider interface
 *
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
use Psr\Log\LoggerAwareInterface;

/**
 * An AuthenticationProvider is used by AuthManager when authenticating users.
 * @ingroup Auth
 * @since 1.27
 */
interface AuthenticationProvider extends LoggerAwareInterface {

	/**
	 * Set AuthManager
	 * @param AuthManager $manager
	 */
	public function setManager( AuthManager $manager );

	/**
	 * Set configuration
	 * @param Config $config
	 */
	public function setConfig( Config $config );

	/**
	 * Return a unique identifier for this instance
	 *
	 * This must be the same across requests. If multiple instances return the
	 * same ID, exceptions will be thrown from AuthManager.
	 *
	 * @return string
	 */
	public function getUniqueId();

	/**
	 * Return the applicable list of AuthenticationRequests
	 *
	 * @see AuthManager::getAuthenticationRequests()
	 * @param string $action One of the AuthManager::ACTION_* constant, except ACTION_UNLINK, which
	 *    is an alias for ACTION_REMOVE so that will be passed here instead.
	 * @return AuthenticationRequest[]
	 */
	public function getAuthenticationRequests( $action );

}
