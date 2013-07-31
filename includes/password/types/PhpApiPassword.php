<?php
/**
 * Implements the BcryptPassword class for the MediaWiki software.
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
 */

/**
 * A PHP API-hashed password
 *
 * This is a computationally complex password hash for use in modern applications.
 * The number of rounds can be configured by $wgPasswordCost.
 *
 * @since 1.22
 */
class PhpApiPassword extends Password {
	/**
	 * Array of hash info from PHP's password_get_info() function
	 * @var array|null
	 */
	private $info = null;

	function getDefaultParams() {
		global $wgPasswordCost;

		return array(
			'cost' => $wgPasswordCost,
		);
	}

	function parseHash( $hash ) {
		parent::parseHash( $hash );
		// Check if the API exists here so we don't have to do it multiple times.
		if ( !function_exists( 'password_hash' ) ) {
			throw new MWException( 'This hashing framework requires the PHP password hashing API.' );
		}

		if ( $hash ) {
			$this->info = password_get_info( $hash );
		}
	}

	function crypt( $password ) {
		if ( $this->info !== null ) {
			throw new MWException( 'This hashing framework does not support hashing based on existing hashes.' );
		}
		$this->hash = password_hash( $password, PASSWORD_DEFAULT );
	}

	function equalsPlaintext( $password ) {
		return password_verify( $password, $this->hash );
	}

	function needsUpdate() {
		return parent::needsUpdate() || password_needs_rehash( $this->hash, PASSWORD_DEFAULT );
	}

	function tests() {
		// Can't test this because PHP might change the algorithm
		return array();
	}
}