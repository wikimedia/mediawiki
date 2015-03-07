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

/**
 * Authn session provider for cookie-based sessions
 * @ingroup Auth
 * @since 1.26
 */
class CookieAuthnSessionProvider extends AbstractAuthnSessionProvider {

	protected $params = array();

	/**
	 * @param array $params Keys include:
	 *  - priority: (required) Priority of the returned sessions
	 *  - emptyPriority: Priority if the session isn't for a logged-in user, if different
	 *  - callUserSetCookiesHook: Whether to call the deprecated hook
	 *  - prefix: Cookie prefix, defaults to $wgCookiePrefix
	 *  - path: Cookie path, defaults to $wgCookiePath
	 *  - domain: Cookie domain, defaults to $wgCookieDomain
	 *  - secure: Cookie secure flag, defaults to $wgCookieSecure
	 *  - httpOnly: Cookie httpOnly flag, defaults to $wgCookieHttpOnly
	 *  - sessionName: Session cookie name. Doesn't honor 'prefix'. Defaults to
	 *    $wgSessionName, or $wgCookiePrefix . '_session' if that is unset.
	 */
	public function __construct( $params = array() ) {
		if ( !isset( $params['priority'] ) ) {
			throw new InvalidArgumentException( __METHOD__ . ': priority must be specified' );
		}
		$this->params = $params;
	}

	public function setConfig( Config $config ) {
		parent::setConfig( $config );

		$this->params += array(
			'emptyPriority' => $this->params['priority'],
			'callUserSetCookiesHook' => false,
			'prefix' => $config->get( 'CookiePrefix' ),
			'path' => $config->get( 'CookiePath' ),
			'domain' => $config->get( 'CookieDomain' ),
			'secure' => $config->get( 'CookieSecure' ),
			'httpOnly' => $config->get( 'CookieHttpOnly' ),
			'sessionName' => $config->get( 'SessionName' )
				?: $config->get( 'CookiePrefix' ) . '_session',
		);
	}

	/**
	 * @param WebRequest $request
	 * @param bool $empty
	 * @return AuthnSession
	 */
	public function provideAuthnSession( WebRequest $request, $empty = false ) {
		return new CookieAuthnSession(
			$request, $this->store, $this->logger, $this->params + array( 'empty' => $empty )
		);
	}

	/**
	 * @return Message
	 */
	public function describeSessions() {
		return new Message( 'cookieauthnsession-description' );
	}

	/**
	 * @return string[]
	 */
	public function getVaryCookies() {
		return array(
			// Vary on token and session because those are the real authn
			// determiners. UserID and UserName don't matter without those.
			$this->params['prefix'] . 'Token',
			$this->params['sessionName'],
			'forceHTTPS',
		);
	}

}
