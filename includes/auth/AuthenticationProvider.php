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

use Psr\Log\LoggerAwareInterface;

/**
 * An AuthenticationProvider is used by AuthManager when authenticating users.
 * @ingroup Auth
 * @since 1.26
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
	 * @see AuthManager::getAuthenticationRequestTypes()
	 * @param string $which
	 * @return string[] AuthenticationRequest class names
	 */
	public function getAuthenticationRequestTypes( $which );

}
