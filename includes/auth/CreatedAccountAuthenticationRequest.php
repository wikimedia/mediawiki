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
 * Returned from account creation to allow for logging into the created account
 * @ingroup Auth
 * @since 1.26
 */
class CreatedAccountAuthenticationRequest extends AuthenticationRequest {

	/** @var int User id */
	public $id;

	/** @var AuthenticationRequest|null */
	public $sessionReq;

	/**
	 * @return array As above
	 */
	public static function getFieldInfo() {
		return array();
	}

	/**
	 * @param int $id User id
	 * @param string $name Username
	 * @param AuthenticationRequest|null $sessionReq
	 */
	public function __construct( $id, $name, $sessionReq = null ) {
		$this->id = $id;
		$this->username = $name;
		$this->sessionReq = $sessionReq;
	}

}
