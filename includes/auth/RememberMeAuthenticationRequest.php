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

/**
 * This is an authentication request added by AuthManager to show a "remember
 * me" checkbox.
 * @ingroup Auth
 * @since 1.27
 */
class RememberMeAuthenticationRequest extends AuthenticationRequest {

	public $required = self::OPTIONAL;

	/** @var bool */
	public $rememberMe = false;

	public function getFieldInfo() {
		global $wgCookieExpiration;
		$expirationDays = ceil( $wgCookieExpiration / ( 3600 * 24 ) );

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
