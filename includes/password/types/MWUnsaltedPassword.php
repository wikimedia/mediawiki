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
 * The old style of MediaWiki password hashing, without a salt. It involves
 * running MD5 on the password.
 */ 
class MWUnsaltedPassword extends Password {
	function crypt( $plaintext ) {
		$this->hash = md5( $plaintext );
	}

	function tests() {
		$prefix = ":{$this->getType()}:";
		return array(
			// RFC 1321 test suite
			array( true, "{$prefix}d41d8cd98f00b204e9800998ecf8427e", '' ),
			array( true, "{$prefix}0cc175b9c0f1b6a831c399e269772661", 'a' ),
			array( true, "{$prefix}900150983cd24fb0d6963f7d28e17f72", 'abc' ),
			array( true, "{$prefix}f96b697d7cb7938d525a2f31aaf161d0", 'message digest' ),
			array( true, "{$prefix}c3fcd3d76192e4007dfb496cca67e13b", 'abcdefghijklmnopqrstuvwxyz' ),
			array( true, "{$prefix}d174ab98d277d9f5a5611c2c9f419d9f", 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789' ),
			array( true, "{$prefix}57edf4a22be3c955ac49da2e2107b67a", '12345678901234567890123456789012345678901234567890123456789012345678901234567890' ),
			// Some quick binary tests
			array( true, "{$prefix}c30c2bae308d204d1545417abc1f53a3", "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x4a\x66" ),
			array( true, "{$prefix}61491b37f93c3c66043fb7a93e6954b6", "\x4a\x66\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00" ),
			// Negative tests for sanity
			array( false, "{$prefix}d41d8cd98f00b204e9800998ecf8427e", 'abc' ),
			array( false, "{$prefix}f96b697d7cb7938d525a2f31aaf161d0", 'message digest2' ),
		);
	}
}