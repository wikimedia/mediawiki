<?php
/**
 * Authentication fields for cookie-based sessions
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
 * @ingroup Auth
 * @since 1.26
 */
class TokenSessionAuthenticationRequest extends AuthenticationRequest {

	/** @var bool */
	public $remember = false;

	/** @var bool */
	public $forceHTTPS = false;

	/**
	 * @return array As above
	 */
	public static function getFieldInfo() {
		return array(
			'remember' => array(
				'type' => 'checkbox',
				'label' => wfMessage( 'tokensessionauthenticationrequest-remember-label' ),
				'help' => wfMessage( 'tokensessionauthenticationrequest-remember-help' ),
				'optional' => true,
			),
			//'forceHTTPS' => array(
			//	'type' => 'checkbox',
			//	'label' => 'tokensessionauthenticationrequest-forcehttps-label',
			//	'help' => 'tokensessionauthenticationrequest-forcehttps-help',
			//	'optional' => true,
			//),
		);
	}

}
