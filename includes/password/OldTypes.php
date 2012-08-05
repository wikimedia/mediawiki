<?php
/**
 * Our old password types.
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
 * @author Daniel Friesen <mediawiki@danielfriesen.name>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @ingroup Password
 */

/**
 * Type :A:, our oldest non-salted password type.
 * Simply md5s the password.
 * @ingroup Password
 */
class Password_TypeA extends BasePasswordType {

	protected function run( $params, $password ) {
		self::params( $params, 0 );
		return md5( $password );
	}

	protected function cryptParams() {
		return array();
	}

}

/**
 * Type :B:, our first salted password type.
 * md5s a combination of a 32bit salt a '-' separator and
 * the md5 of the password.
 * @ingroup Password
 */
class Password_TypeB extends BasePasswordType {

	protected function run( $params, $password ) {
		list( $salt ) = self::params( $params, 1 );
		return md5( $salt . '-' . md5( $password ) );
	}

	protected function cryptParams() {
		$salt = MWCryptRand::generateHex( 8 );
		return array( $salt );
	}

}
