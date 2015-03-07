<?php
/**
 * Authn session provider interface
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
use Psr\Log\LoggerInterface;

/**
 * An AuthnSessionProvider, as its name implies, provides an AuthnSession
 *
 * @ingroup Auth
 * @since 1.26
 */
interface AuthnSessionProvider extends LoggerAwareInterface {

	/**
	 * Set configuration
	 * @param Config $config
	 */
	public function setConfig( Config $config );

	/**
	 * Set session storage backend
	 * @param BagOStuff $store
	 */
	public function setStore( BagOStuff $store );

	/**
	 * Provide the session for a request
	 *
	 * Return null if no session exists. Otherwise, simply construct an
	 * AuthnSession with a priority and optional key and return it; AuthManager
	 * will handle setting the store and logger if necessary.
	 *
	 * If multiple AuthnSessionProviders provide sessions, the one with highest
	 * priority wins. In case of a tie, an exception is thrown.
	 * AuthnSessionProviders are encouraged to make priorities
	 * user-configurable unless only max-priority makes sense.
	 *
	 * @param WebRequest $request
	 * @param bool $empty If true, the returned session should have no user and no key set.
	 * @return AuthnSession|null
	 */
	public function provideAuthnSession( WebRequest $request, $empty = false );

	/**
	 * Return a MessageSpecifier describing the types of sessions this provider
	 * returns.
	 *
	 * For example, "OAuth authentication", "Cookie-based sessions".
	 *
	 * @return MessageSpecifier
	 */
	public function describeSessions();

	/**
	 * Return the HTTP headers that need varying on.
	 *
	 * The return value is such that someone could theoretically do this:
	 * @code
	 *  foreach ( $provider->getVaryHeaders() as $header => $options ) {
	 *  	$outputPage->addVaryHeader( $header, $options );
	 *  }
	 * @endcode
	 *
	 * @return array
	 */
	public function getVaryHeaders();

	/**
	 * Return the list of cookies that need varying on.
	 * @return string[]
	 */
	public function getVaryCookies();

}
