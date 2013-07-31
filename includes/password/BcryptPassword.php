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
 * A Bcrypt-hashed password (the default in MW as of 1.22)
 *
 * This is a computationally complex password hash for use in modern applications.
 * The number of rounds can be configured by $wgPasswordCost.
 *
 * @since 1.23
 */
class BcryptPassword extends ParameterizedPassword {
	function getDefaultParams() {
		return array(
			'rounds' => $this->config['cost'],
		);
	}

	function getDelimiter() {
		return '$';
	}

	function parseHash( $hash ) {
		parent::parseHash( $hash );

		$this->params['rounds'] = (int)$this->params['rounds'];
	}

	function crypt( $password ) {
		if ( defined( 'CRYPT_BLOWFISH' ) ) {
			// Either use existing hash or make a new salt
			// Bcrypt expects 22 characters of base64-encoded salt
			$existing = $this->hash ?:
				substr( strtr( base64_encode( MWCryptRand::generate( 16, true ) ), '+', '.' ), 0, 22 );

			$hash = crypt( $password, sprintf( '$2y$%02d$%s', (int)$this->params['rounds'], $existing ) );
			if ( !is_string( $hash ) || strlen( $hash ) <= 13 ) {
				throw new PasswordError( 'Error when hashing password.' );
			}
		} else {
			throw new MWException( 'Bcrypt is not supported.' );
		}

		// Strip the $2y$
		$parts = explode( $this->getDelimiter(), substr( $hash, 4 ) );
		$this->params['rounds'] = (int)$parts[0];
		$this->hash = $parts[1];
	}

	function tests() {
		$prefix = ":{$this->config['type']}:";
		return array(
			// Tests from glibc bcrypt implementation
			array( true, "{$prefix}05\$CCCCCCCCCCCCCCCCCCCCC.E5YPO9kmyuRGyh0XouQYb4YMJKvyOeW", "U*U" ),
			array( true, "{$prefix}05\$CCCCCCCCCCCCCCCCCCCCC.VGOzA784oUp/Z0DY336zx7pLYAy0lwK", "U*U*" ),
			array( true, "{$prefix}05\$XXXXXXXXXXXXXXXXXXXXXOAcXxm9kjPGEMsLznoKqmqw7tc8WCx4a", "U*U*U" ),
			array( true, "{$prefix}05\$abcdefghijklmnopqrstuu5s2v8.iXieOjg/.AySBTTZIIVFJeBui","0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789chars after 72 are ignored" ),
			array( true, "{$prefix}05$/OK.fbVrR/bpIqNJ5ianF.CE5elHaaO4EbggVDjb8P19RukzXSM3e", "\xff\xff\xa3" ),
			array( true, "{$prefix}05$/OK.fbVrR/bpIqNJ5ianF.Sa7shbm4.OzKpvFnX1pQLmQW96oUlCq", "\xa3" ),
			array( true, "{$prefix}05$/OK.fbVrR/bpIqNJ5ianF.Sa7shbm4.OzKpvFnX1pQLmQW96oUlCq", "\xa3" ),
			array( true, "{$prefix}05$/OK.fbVrR/bpIqNJ5ianF.o./n25XVfn6oAPaUvHe.Csk4zRfsYPi", "\xff\xa334\xff\xff\xff\xa3345" ),
			array( true, "{$prefix}05$/OK.fbVrR/bpIqNJ5ianF.nRht2l/HRhr6zmCp9vYUvvsqynflf9e", "\xff\xa3345" ),
			array( true, "{$prefix}05$/OK.fbVrR/bpIqNJ5ianF.nRht2l/HRhr6zmCp9vYUvvsqynflf9e", "\xff\xa3345" ),
			array( true, "{$prefix}05$/OK.fbVrR/bpIqNJ5ianF.6IflQkJytoRVc1yuaNtHfiuq.FRlSIS", "\xa3ab" ),
			array( true, "{$prefix}05$/OK.fbVrR/bpIqNJ5ianF.6IflQkJytoRVc1yuaNtHfiuq.FRlSIS", "\xa3ab" ),
			array( true, "{$prefix}05$/OK.fbVrR/bpIqNJ5ianF.swQOIzjOiJ9GHEPuhEkvqrUyvWhEMx6", "\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaachars after 72 are ignored as usual" ),
			array( true, "{$prefix}05$/OK.fbVrR/bpIqNJ5ianF.R9xrDjiycxMbQE2bp.vgqlYpW5wx2yy", "\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55" ),
			array( true, "{$prefix}05$/OK.fbVrR/bpIqNJ5ianF.9tQZzcJfm3uj2NvJ/n5xkhpqLrMpWCe", "\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff" ),
			array( true, "{$prefix}05\$CCCCCCCCCCCCCCCCCCCCC.7uG0VCzI2bS7j6ymqJi9CdcdxiRTWNy", "" ),
			// One or two false sanity tests
			array( false, "{$prefix}05\$CCCCCCCCCCCCCCCCCCCCC.E5YPO9kmyuRGyh0XouQYb4YMJKvyOeW", "UXU" ),
			array( false, "{$prefix}05\$CCCCCCCCCCCCCCCCCCCCC.E5YPO9kmyuRGyh0XouQYb4YMJKvyOeW", "" ),
		);
	}
}
