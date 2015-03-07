<?php
/**
 * Authn session provider for cookie-based sessions
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

use Psr\Log\LoggerInterface;

/**
 * Authn session provider for cookie-based sessions
 *
 * @ingroup Auth
 * @since 1.25
 */
class CookieAuthnSessionProvider implements AuthnSessionProvider {

	protected $params = array();

	public function __construct( $params ) {
		if ( !isset( $params['priority'] ) ) {
			throw new InvalidArgumentException( __METHOD__ . ': priority must be specified' );
		}

		$config = ConfigFactory::getDefaultInstance()->makeConfig( 'main' );
		$this->params = $params + array(
			'prefix' => $config->get( 'CookiePrefix' ),
			'path' => $config->get( 'CookiePath' ),
			'domain' => $config->get( 'CookieDomain' ),
			'secure' => $config->get( 'CookieSecure' ),
			'httpOnly' => $config->get( 'CookieHttpOnly' ),
			'sessionName' => $config->get( 'SessionName' )
				?: $config->get( 'CookiePrefix' ) . '_session',
		);
	}

	public function provideAuthnSession(
		WebRequest $request, BagOStuff $store, LoggerInterface $logger, $empty = false
	) {
		return new CookieAuthnSession(
			$request, $store, $logger, $this->params + array( 'empty' => $empty )
		);
	}

	public function describeSessions() {
		return new SimpleMessageSpecifier( 'cookieauthnsession-description' );
	}
}
