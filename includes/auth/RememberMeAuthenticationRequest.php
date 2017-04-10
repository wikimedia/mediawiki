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

use MediaWiki\Session\SessionManager;
use MediaWiki\Session\SessionProvider;

/**
 * This is an authentication request added by AuthManager to show a "remember
 * me" checkbox. When checked, it will take more time for the authenticated session to expire.
 * @ingroup Auth
 * @since 1.27
 */
class RememberMeAuthenticationRequest extends AuthenticationRequest {

	public $required = self::OPTIONAL;

	/** @var int How long the user will be remembered, in seconds */
	protected $expiration = null;

	/** @var bool */
	public $rememberMe = false;

	public function __construct() {
		/** @var SessionProvider $provider */
		$provider = SessionManager::getGlobalSession()->getProvider();
		$this->expiration = $provider->getRememberUserDuration();
	}

	public function getFieldInfo() {
		if ( !$this->expiration ) {
			return [];
		}

		$expirationDays = ceil( $this->expiration / ( 3600 * 24 ) );
		return [
			'rememberMe' => [
				'type' => 'checkbox',
				'label' => wfMessage( 'userlogin-remembermypassword' )->numParams( $expirationDays ),
				'help' => wfMessage( 'authmanager-userlogin-remembermypassword-help' ),
				'optional' => true,
			]
		];
	}
}
