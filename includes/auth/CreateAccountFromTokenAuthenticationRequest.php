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

/**
 * Request for passing authentication status into account creation
 *
 * AuthnSession::getAutoCreateAuthenticationRequest() might return one of these
 * if an account should be auto-created. One might also be created by combining
 * the username and authnToken fields from an AuthenticationResponse.
 *
 * @ingroup Auth
 * @since 1.25
 */
class CreateAccountFromTokenAuthenticationRequest extends AuthenticationRequest {

	/** @var string|null Return-to URL, in case of redirect */
	public $returnToUrl = null;

	/** @var string|null Username to be created, if any */
	public $username;

	/** @var string Unique ID for a PrimaryAuthenticationProvider */
	public $primaryAuthenticationProviderId;

	/**
	 * @var mixed Data that allows the PrimaryAuthenticationProvider to
	 * create/link an account without further input.
	 */
	public $token;

	public static function getFieldInfo() {
		return array();
	}

	public static function newFromSubmission( array $data ) {
		// Can't be created from a submission
		return null;
	}

	/**
	 * @param string|null $username
	 * @param string $PAPId
	 * @param mixed $token
	 */
	public function __construct( $username, $PAPId, $token ) {
		$this->username = $username;
		$this->primaryAuthenticationProviderId = $PAPId;
		$this->token = $token;
	}

}
